// REPLACE-WHOLE-FILE
// FILE: src/modules/inbuilt-amenities/inbuilt-amenities.service.ts

import { Injectable, NotFoundException, BadRequestException } from "@nestjs/common";
import { PrismaService } from "../../../prisma.service";
import { CreateInbuiltAmenityDto } from "./dto/create-inbuilt-amenity.dto";
import { UpdateInbuiltAmenityDto } from "./dto/update-inbuilt-amenity.dto";

type AmenityRow = {
  id: number;
  title: string;
  status: 0 | 1;
};

@Injectable()
export class InbuiltAmenitiesService {
  constructor(private readonly prisma: PrismaService) {}

  private toRow(db: any): AmenityRow {
    return {
      id: Number(db.inbuilt_amenity_type_id),
      title: String(db.inbuilt_amenity_title ?? "").trim(),
      status: (Number(db.status) === 1 ? 1 : 0) as 0 | 1,
    };
  }

  async list(): Promise<AmenityRow[]> {
    const rows = await this.prisma.dvi_inbuilt_amenities.findMany({
      where: { deleted: 0 },
      orderBy: { inbuilt_amenity_type_id: "desc" },
      select: {
        inbuilt_amenity_type_id: true,
        inbuilt_amenity_title: true,
        status: true,
      },
    });

    return rows.map((r) => this.toRow(r));
  }

  async getOne(id: number): Promise<AmenityRow> {
    const row = await this.prisma.dvi_inbuilt_amenities.findFirst({
      where: { inbuilt_amenity_type_id: id, deleted: 0 },
      select: {
        inbuilt_amenity_type_id: true,
        inbuilt_amenity_title: true,
        status: true,
      },
    });

    if (!row) throw new NotFoundException("Inbuilt amenity not found");
    return this.toRow(row);
  }

  /**
   * PHP parity:
   * - Always inserts status=1
   * - Writes createdby = logged_user_id
   * - Does NOT set createdon explicitly in code (DB may handle / or remain null)
   */
  async create(dto: CreateInbuiltAmenityDto, userId = 0): Promise<AmenityRow> {
    const title = String(dto.title ?? "").trim();
    if (!title) throw new BadRequestException("title is required");

    const created = await this.prisma.dvi_inbuilt_amenities.create({
      data: {
        inbuilt_amenity_title: title,
        createdby: Number(userId) || 0,
        status: 1, // PHP hardcodes "1"
        deleted: 0,
      },
      select: {
        inbuilt_amenity_type_id: true,
        inbuilt_amenity_title: true,
        status: true,
      },
    });

    return this.toRow(created);
  }

  /**
   * PHP parity for update:
   * - When editing title, PHP sets status=1 AND overwrites createdby again.
   * - When toggling status, PHP FLIPS based on current status passed.
   */
  async update(id: number, dto: UpdateInbuiltAmenityDto, userId = 0): Promise<AmenityRow> {
    // Make sure record exists (PHP doesn't check deleted, but list/get do)
    const existing = await this.prisma.dvi_inbuilt_amenities.findFirst({
      where: { inbuilt_amenity_type_id: id },
      select: {
        inbuilt_amenity_type_id: true,
        inbuilt_amenity_title: true,
        status: true,
        deleted: true,
      },
    });

    if (!existing) throw new NotFoundException("Inbuilt amenity not found");

    // CASE 1: Status toggle (PHP expects CURRENT status and flips it)
    if (typeof dto.status === "number" && dto.title === undefined) {
      const current = dto.status;
      const newStatus = (current === 0 ? 1 : 0) as 0 | 1;

      const updated = await this.prisma.dvi_inbuilt_amenities.update({
        where: { inbuilt_amenity_type_id: id },
        data: { status: newStatus },
        select: {
          inbuilt_amenity_type_id: true,
          inbuilt_amenity_title: true,
          status: true,
        },
      });

      return this.toRow(updated);
    }

    // CASE 2: Title update (PHP sets status=1 and overwrites createdby)
    if (dto.title !== undefined) {
      const title = String(dto.title ?? "").trim();
      if (!title) throw new BadRequestException("title is required");

      const updated = await this.prisma.dvi_inbuilt_amenities.update({
        where: { inbuilt_amenity_type_id: id },
        data: {
          inbuilt_amenity_title: title,
          createdby: Number(userId) || 0,
          status: 1, // PHP hardcodes 1 on add/update
        },
        select: {
          inbuilt_amenity_type_id: true,
          inbuilt_amenity_title: true,
          status: true,
        },
      });

      return this.toRow(updated);
    }

    // If nothing to update
    throw new BadRequestException("Nothing to update");
  }

  /**
   * PHP parity:
   * confirmdelete => deleted=1, updatedon=NOW() WHERE id
   */
  async remove(id: number): Promise<{ result: true }> {
    const existing = await this.prisma.dvi_inbuilt_amenities.findFirst({
      where: { inbuilt_amenity_type_id: id },
      select: { inbuilt_amenity_type_id: true },
    });

    if (!existing) throw new NotFoundException("Inbuilt amenity not found");

    await this.prisma.dvi_inbuilt_amenities.update({
      where: { inbuilt_amenity_type_id: id },
      data: {
        deleted: 1,
        updatedon: new Date(),
      },
    });

    return { result: true };
  }
}
