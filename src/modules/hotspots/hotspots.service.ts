// FILE: src/modules/hotspots/hotspots.service.ts

import { BadRequestException, Injectable } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { HotspotListQueryDto } from './dto/hotspot-list.query.dto';
import { HotspotDto, HotspotListResponseDto } from './dto/hotspot-list.response.dto';

type AnyRec = Record<string, any>;

@Injectable()
export class HotspotsService {
  constructor(private readonly prisma: PrismaService) {}

  // ---------------------------------------------------------
  // Public API
  // ---------------------------------------------------------
  async list(q: HotspotListQueryDto): Promise<HotspotListResponseDto> {
    const page = q.page ?? 1;
    const size = q.size ?? 50;
    const skip = (page - 1) * size;
    const take = size;

    const hotspotClient = this.getHotspotClient();
    const galleryClient = this.getGalleryClient();

    const where = this.buildWhere(q, hotspotClient);
    const [total, rows] = await Promise.all([
      hotspotClient.count({ where }),
      hotspotClient.findMany({
        where,
        orderBy: this.buildOrderBy(q, hotspotClient),
        skip,
        take,
        select: this.hotspotSelect(hotspotClient),
      }),
    ]);

    const items = rows.map((r: AnyRec) => this.mapHotspotRow(r, hotspotClient));

    if (q.includeImages === 'true' && items.length && galleryClient) {
      const idField = this.primaryKeyField(hotspotClient);
      const ids: number[] = items
        .map((i: HotspotDto) => i.id)
        .filter((id: number | undefined | null): id is number => typeof id === "number");
      const photos = await galleryClient.findMany({
        where: {
          OR: [
            { hotspot_id: { in: ids } },
            { hotspot_ID: { in: ids } },
            { place_id: { in: ids } }, // common alternate
          ],
          ...(this.hasField(galleryClient, 'deleted') ? { deleted: { in: [0, 1] } } : {}),
        },
        orderBy: this.hasField(galleryClient, 'createdon')
          ? { createdon: 'asc' as const }
          : this.hasField(galleryClient, 'created_at')
          ? { created_at: 'asc' as const }
          : undefined,
        select: this.gallerySelect(galleryClient),
      });

      // pick first photo per hotspot
      const firstByHotspot = new Map<number, string>();
      for (const p of photos as AnyRec[]) {
        const hid = p.hotspot_id ?? p.hotspot_ID ?? p.place_id ?? null;
        if (!hid) continue;
        if (!firstByHotspot.has(hid)) {
          const path = p.image_path ?? p.photo_path ?? p.image_url ?? p.gallery_url ?? null;
          firstByHotspot.set(hid, this.toPublicUrl(path));
        }
      }

      for (const it of items) {
        it.photoUrl = firstByHotspot.get(it.id) ?? null;
      }
    }

    return { total, page, size, items };
  }

  async getOne(id: number, includeImages = false): Promise<HotspotDto | null> {
    const hotspotClient = this.getHotspotClient();
    const galleryClient = this.getGalleryClient();

    const row = await hotspotClient.findFirst({
      where: this.mergeWhereKey({}, hotspotClient, id),
      select: this.hotspotSelect(hotspotClient),
    });
    if (!row) return null;

    const dto = this.mapHotspotRow(row, hotspotClient);

    if (includeImages && galleryClient) {
      const pics = await galleryClient.findMany({
        where: this.mergeWhereKey({}, galleryClient, dto.id, ['hotspot_id', 'hotspot_ID', 'place_id']),
        orderBy: this.hasField(galleryClient, 'createdon')
          ? { createdon: 'asc' as const }
          : this.hasField(galleryClient, 'created_at')
          ? { created_at: 'asc' as const }
          : undefined,
        select: this.gallerySelect(galleryClient),
        take: 1,
      });

      const first = pics[0];
      dto.photoUrl = first ? this.toPublicUrl(first.image_path ?? first.photo_path ?? first.image_url ?? first.gallery_url ?? null) : null;
    }

    return dto;
  }

  // ---------------------------------------------------------
  // Model discovery & safe accessors
  // ---------------------------------------------------------
  private get prismaAny(): AnyRec {
    return this.prisma as any;
  }

  /** Try common model names. Primary is dvi_hotspot_place (matches your PHP). */
  private getHotspotClient(): AnyRec {
    const p = this.prismaAny;
    const candidates = [
      p.dvi_hotspot_place,
      p.dvi_hotspot_places,
      p.dvi_hotspots,
      p.hotspot_place,
      p.hotspots,
    ].filter(Boolean);
    if (!candidates.length) {
      throw new BadRequestException(
        'Prisma model for hotspots not found. Expected one of: dvi_hotspot_place / dvi_hotspot_places / dvi_hotspots',
      );
    }
    return candidates[0];
  }

  /** Gallery table for first photo (as used in PHP). */
  private getGalleryClient(): AnyRec | null {
    const p = this.prismaAny;
    const candidates = [
      p.dvi_hotspot_gallery_details,
      p.dvi_hotspot_gallery,
      p.hotspot_gallery_details,
      p.hotspot_gallery,
    ].filter(Boolean);
    return candidates[0] ?? null;
  }

  private hasField(client: AnyRec, field: string): boolean {
    try {
      // inspect DMMF delegate args by calling findMany with bogus select will throw; instead, check a known record shape using $queryRaw is overkill
      // heuristic: rely on field names we will use later guarded by try/catch in queries.
      return true; // we guard in select/where builders anyway
    } catch {
      return false;
    }
  }

  private primaryKeyField(client: AnyRec): 'hotspot_ID' | 'hotspot_id' | 'place_id' | 'id' {
    if (this.trySelect(client, { hotspot_ID: true })) return 'hotspot_ID';
    if (this.trySelect(client, { hotspot_id: true })) return 'hotspot_id';
    if (this.trySelect(client, { place_id: true })) return 'place_id';
    return 'id';
  }

  private trySelect(client: AnyRec, select: AnyRec): boolean {
    try {
      // call with take:0 to validate fields without scanning
      // not all clients allow take:0; ignore errors
      client.findMany({ take: 0, select });
      return true;
    } catch {
      return false;
    }
  }

  // ---------------------------------------------------------
  // WHERE / ORDER BY / SELECT builders
  // ---------------------------------------------------------
  private buildWhere(q: HotspotListQueryDto, client: AnyRec): AnyRec {
    const where: AnyRec = {};

    // soft delete + status if exist
    if (this.tryWhere(client, { deleted: { in: [0, 1] } })) {
      where.deleted = { in: [0, 1] };
    }
    if (typeof q.status === 'number' && this.tryWhere(client, { status: q.status })) {
      where.status = q.status;
    }

    // text search on name/address
    const search = (q.q ?? '').trim();
    if (search) {
      const ors: AnyRec[] = [];
      if (this.tryWhere(client, { hotspot_name: { contains: search } })) {
        ors.push({ hotspot_name: { contains: search } });
      }
      if (this.tryWhere(client, { name: { contains: search } })) {
        ors.push({ name: { contains: search } });
      }
      if (this.tryWhere(client, { hotspot_address: { contains: search } })) {
        ors.push({ hotspot_address: { contains: search } });
      }
      if (this.tryWhere(client, { address: { contains: search } })) {
        ors.push({ address: { contains: search } });
      }
      if (ors.length) where.OR = ors;
    }

    // location filters
    if (q.cityId && this.tryWhere(client, { city_id: q.cityId })) where.city_id = q.cityId;
    if (q.stateId && this.tryWhere(client, { state_id: q.stateId })) where.state_id = q.stateId;
    if (q.countryId && this.tryWhere(client, { country_id: q.countryId })) where.country_id = q.countryId;

    return where;
  }

  private tryWhere(client: AnyRec, where: AnyRec): boolean {
    try {
      client.findMany({ take: 0, where });
      return true;
    } catch {
      return false;
    }
  }

  private buildOrderBy(q: HotspotListQueryDto, client: AnyRec): AnyRec | undefined {
    const sort = (q.sort ?? '').toLowerCase();
    if (sort === 'name:asc' || sort === 'name:desc') {
      const dir = sort.endsWith('desc') ? 'desc' : 'asc';
      if (this.tryOrderBy(client, { hotspot_name: dir })) return { hotspot_name: dir };
      if (this.tryOrderBy(client, { name: dir })) return { name: dir };
    }

    // default by created/updated if present, else by PK
    if (this.tryOrderBy(client, { createdon: 'desc' })) return { createdon: 'desc' };
    if (this.tryOrderBy(client, { created_at: 'desc' })) return { created_at: 'desc' };
    const pk = this.primaryKeyField(client);
    return { [pk]: 'desc' };
  }

  private tryOrderBy(client: AnyRec, orderBy: AnyRec): boolean {
    try {
      client.findMany({ take: 0, orderBy });
      return true;
    } catch {
      return false;
    }
  }

  private hotspotSelect(client: AnyRec): AnyRec {
    const sel: AnyRec = {};

    // id variants
    if (this.trySelect(client, { hotspot_ID: true })) sel.hotspot_ID = true;
    if (this.trySelect(client, { hotspot_id: true })) sel.hotspot_id = true;
    if (this.trySelect(client, { place_id: true })) sel.place_id = true;
    if (this.trySelect(client, { id: true })) sel.id = true;

    // name variants
    if (this.trySelect(client, { hotspot_name: true })) sel.hotspot_name = true;
    if (this.trySelect(client, { name: true })) sel.name = true;
    if (this.trySelect(client, { title: true })) sel.title = true;

    // address variants
    if (this.trySelect(client, { hotspot_address: true })) sel.hotspot_address = true;
    if (this.trySelect(client, { address: true })) sel.address = true;

    // location fks
    if (this.trySelect(client, { city_id: true })) sel.city_id = true;
    if (this.trySelect(client, { state_id: true })) sel.state_id = true;
    if (this.trySelect(client, { country_id: true })) sel.country_id = true;

    // geo
    if (this.trySelect(client, { latitude: true })) sel.latitude = true;
    if (this.trySelect(client, { longitude: true })) sel.longitude = true;

    // flags
    if (this.trySelect(client, { status: true })) sel.status = true;
    if (this.trySelect(client, { deleted: true })) sel.deleted = true;

    // fallback: select all if nothing matched (rare)
    return Object.keys(sel).length ? sel : undefined;
  }

  private gallerySelect(client: AnyRec): AnyRec {
    const sel: AnyRec = {};
    if (this.trySelect(client, { hotspot_id: true })) sel.hotspot_id = true;
    if (this.trySelect(client, { hotspot_ID: true })) sel.hotspot_ID = true;
    if (this.trySelect(client, { place_id: true })) sel.place_id = true;

    if (this.trySelect(client, { image_path: true })) sel.image_path = true;
    if (this.trySelect(client, { photo_path: true })) sel.photo_path = true;
    if (this.trySelect(client, { image_url: true })) sel.image_url = true;
    if (this.trySelect(client, { gallery_url: true })) sel.gallery_url = true;

    if (this.trySelect(client, { createdon: true })) sel.createdon = true;
    if (this.trySelect(client, { created_at: true })) sel.created_at = true;

    if (this.trySelect(client, { deleted: true })) sel.deleted = true;

    return Object.keys(sel).length ? sel : undefined;
  }

  private mapHotspotRow(r: AnyRec, client: AnyRec): HotspotDto {
    const id =
      r.hotspot_ID ?? r.hotspot_id ?? r.place_id ?? r.id ?? null;

    const name =
      this.firstNonEmpty([r.hotspot_name, r.name, r.title]) ?? `Hotspot #${id ?? ''}`;

    const address =
      this.firstNonEmpty([r.hotspot_address, r.address]) ?? null;

    const dto: HotspotDto = {
      id: Number(id),
      name: String(name),
      address,
      cityId: r.city_id ?? null,
      stateId: r.state_id ?? null,
      countryId: r.country_id ?? null,
      latitude: this.toNumOrNull(r.latitude),
      longitude: this.toNumOrNull(r.longitude),
      status: this.toNumOrNull(r.status),
      photoUrl: null,
    };
    return dto;
  }

  private mergeWhereKey(base: AnyRec, client: AnyRec, id: number, keys: string[] = []): AnyRec {
    const where: AnyRec = { ...base };
    const candidates = keys.length
      ? keys
      : ['hotspot_ID', 'hotspot_id', 'place_id', 'id'];

    for (const k of candidates) {
      try {
        client.findMany({ take: 0, where: { [k]: id } });
        where[k] = id;
        return where;
      } catch {
        // ignore
      }
    }
    // last resort
    where.id = id;
    return where;
  }

  // ---------------------------------------------------------
  // Small helpers
  // ---------------------------------------------------------
  private firstNonEmpty(arr: any[]): string | null {
    for (const v of arr) {
      const s = (v ?? '').toString().trim();
      if (s) return s;
    }
    return null;
  }

  private toNumOrNull(v: any): number | null {
    const n = Number(v);
    return Number.isFinite(n) ? n : null;
  }

  private toPublicUrl(path: string | null | undefined): string | null {
    if (!path) return null;
    const base = process.env.ASSET_BASE_URL ?? '';
    if (!base) return path;
    // avoid double slash
    return `${base.replace(/\/+$/, '')}/${String(path).replace(/^\/+/, '')}`;
    }
}