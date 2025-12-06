import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';

type ListQuery = {
  search?: string;
  source?: string;
  destination?: string;
  page?: number;
  pageSize?: number;
};

@Injectable()
export class LocationsService {
  constructor(private readonly prisma: PrismaService) {}

  // ------ LIST + FILTERS ------
  async list(q: ListQuery) {
    const page = Math.max(1, Number(q.page) || 1);
    const pageSize = Math.min(100, Math.max(1, Number(q.pageSize) || 10));

    const where: any = { deleted: 0 };
    if (q.source) where.source_location = q.source;
    if (q.destination) where.destination_location = q.destination;
    if (q.search) {
      where.OR = [
        { source_location: { contains: q.search } },
        { destination_location: { contains: q.search } },
        { source_city: { contains: q.search } },
        { destination_city: { contains: q.search } },
      ];
    }

    const [rows, total] = await this.prisma.$transaction([
      this.prisma.dvi_stored_locations.findMany({
        where,
        orderBy: { location_ID: 'desc' },
        skip: (page - 1) * pageSize,
        take: pageSize,
      }),
      this.prisma.dvi_stored_locations.count({ where }),
    ]);

    return { rows, total, page, pageSize };
  }

  async dropdowns() {
    const [src, dst] = await this.prisma.$transaction([
      this.prisma.dvi_stored_locations.findMany({
        where: { deleted: 0 },
        select: { source_location: true },
        distinct: ['source_location'],
        orderBy: { source_location: 'asc' },
      }),
      this.prisma.dvi_stored_locations.findMany({
        where: { deleted: 0 },
        select: { destination_location: true },
        distinct: ['destination_location'],
        orderBy: { destination_location: 'asc' },
      }),
    ]);
    return {
      sources: src.map((x) => x.source_location).filter(Boolean),
      destinations: dst.map((x) => x.destination_location).filter(Boolean),
    };
  }

  // ------ CRUD ------
  async create(payload: any) {
    const created = await this.prisma.dvi_stored_locations.create({
      data: {
        ...payload,
        status: 1,
        deleted: 0,
        createdon: new Date(),
      },
    });
    return created;
  }

  async get(id: number) {
    const row = await this.prisma.dvi_stored_locations.findFirst({
      where: { location_ID: id, deleted: 0 },
    });
    if (!row) throw new NotFoundException('Location not found');
    return row;
  }

  async update(id: number, payload: any) {
    await this.get(id);
    return this.prisma.dvi_stored_locations.update({
      where: { location_ID: id },
      data: { ...payload, updatedon: new Date() },
    });
  }

  async softDelete(id: number) {
    await this.get(id);
    await this.prisma.dvi_stored_locations.update({
      where: { location_ID: id },
      data: { deleted: 1, updatedon: new Date() },
    });
    return { ok: true };
  }

  // ------ Modify Location Name (quick rename like PHP) ------
  async modifyName(id: number, scope: 'source' | 'destination', newName: string) {
    await this.get(id);
    const data =
      scope === 'source'
        ? { source_location: newName }
        : { destination_location: newName };
    return this.prisma.dvi_stored_locations.update({
      where: { location_ID: id },
      data: { ...data, updatedon: new Date() },
    });
  }

  // ------ TOLL CHARGES ------
  async getTolls(locationId: number) {
    // 1) Get all vehicle types (to render full grid)
    const vehicleTypes = await this.prisma.dvi_vehicle_type.findMany({
      where: { deleted: 0, status: 1 },
      orderBy: { vehicle_type_id: 'asc' },
      select: {
        vehicle_type_id: true,
        vehicle_type_title: true, // <-- this is the name field in your schema
      },
    });

    // 2) Get existing tolls for this location
    const existing = await this.prisma.dvi_vehicle_toll_charges.findMany({
      where: { location_id: BigInt(locationId), deleted: 0 },
      select: {
        vehicle_type_id: true,
        toll_charge: true,
      },
    });

    // Ensure the map key is a number to match vehicle_types list
    const tollMap = new Map<number, number>(
      existing.map((e) => [Number(e.vehicle_type_id), Number(e.toll_charge || 0)])
    );

    // 3) Merge into UI rows
    return vehicleTypes.map((vt) => ({
      vehicle_type_id: vt.vehicle_type_id,
      vehicle_type_name: vt.vehicle_type_title ?? '', // <-- fixed
      toll_charge: tollMap.get(Number(vt.vehicle_type_id)) ?? 0,
    }));
  }

  /**
   * Simple & safe way (schema lacks a unique composite on (location_id, vehicle_type_id)):
   * - delete all existing tolls for location
   * - insert the provided list
   */
  async upsertTolls(
    locationId: number,
    items: { vehicle_type_id: number; toll_charge: number }[],
    userId: number
  ) {
    const idBig = BigInt(locationId);
    await this.get(locationId);

    await this.prisma.dvi_vehicle_toll_charges.deleteMany({
      where: { location_id: idBig },
    });

    if (!items?.length) return { ok: true };

    await this.prisma.dvi_vehicle_toll_charges.createMany({
      data: items.map((it) => ({
        location_id: idBig,
        vehicle_type_id: it.vehicle_type_id,
        toll_charge: Number(it.toll_charge) || 0,
        createdby: userId ?? 0,
        status: 1,
        deleted: 0,
        createdon: new Date(),
      })),
      skipDuplicates: true,
    });

    return { ok: true };
  }
}
