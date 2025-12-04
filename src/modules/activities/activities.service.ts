// FILE: src/modules/activities/activities.service.ts
import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';

// ——— helpers ———
function toTimeDate(hhmmss?: string | null): Date | null {
  if (!hhmmss) return null;
  // Expect "HH:MM" or "HH:MM:SS"
  const parts = String(hhmmss).split(':').map((x) => parseInt(x, 10));
  if (!parts.length || Number.isNaN(parts[0])) return null;
  const d = new Date();
  d.setHours(parts[0] || 0, parts[1] || 0, parts[2] || 0, 0);
  return d;
}

function toDateOnly(d?: string | Date | null): Date | null {
  if (!d) return null;
  const x = new Date(d as any);
  if (Number.isNaN(x.getTime())) return null;
  x.setHours(0, 0, 0, 0);
  return x;
}

function toInt(v: any, def = 0) {
  const n = Number(v);
  return Number.isFinite(n) ? Math.trunc(n) : def;
}

function toFloat(v: any, def = 0) {
  const n = Number(v);
  return Number.isFinite(n) ? n : def;
}

function toBigIntSafe(v: any): bigint {
  if (typeof v === 'bigint') return v;
  if (typeof v === 'number' && Number.isFinite(v)) return BigInt(Math.trunc(v));
  const s = String(v ?? '').trim();
  if (!s) return BigInt(0);
  return BigInt(s);
}

type StatusFilter = '0' | '1' | undefined;

/** ---- NEW: safe formatters to avoid {} in JSON ---- */
function fmtHMS(v: unknown): string | null {
  if (!v) return null;
  if (v instanceof Date) {
    const hh = String(v.getHours()).padStart(2, '0');
    const mm = String(v.getMinutes()).padStart(2, '0');
    const ss = String(v.getSeconds()).padStart(2, '0');
    return `${hh}:${mm}:${ss}`;
  }
  // Some drivers may already give "HH:MM:SS" strings
  const s = String(v);
  if (!s || s.toLowerCase() === 'invalid date') return null;
  const [hh = '00', mm = '00', ss = '00'] = s.split(':');
  return `${hh.padStart(2, '0')}:${mm.padStart(2, '0')}:${(ss ?? '00').padStart(2, '0')}`;
}

function fmtDateISO(d: unknown): string | null {
  if (!d) return null;
  if (d instanceof Date) return d.toISOString().slice(0, 10);
  const x = new Date(String(d));
  if (Number.isNaN(x.getTime())) return null;
  return x.toISOString().slice(0, 10);
}

function fmtDateTimeISO(d: unknown): string | null {
  if (!d) return null;
  if (d instanceof Date) return d.toISOString();
  const x = new Date(String(d));
  if (Number.isNaN(x.getTime())) return null;
  return x.toISOString();
}

@Injectable()
export class ActivitiesService {
  constructor(private readonly prisma: PrismaService) {}

  // ====== LIST ======
  async list(opts: { q?: string; status?: StatusFilter }) {
    const where: any = {
      deleted: 0,
    };
    if (opts?.status === '0' || opts?.status === '1') {
      where.status = toInt(opts.status);
    }
    if (opts?.q) {
      where.OR = [{ activity_title: { contains: opts.q } }];
    }

    // Join hotspot for name/location
    const rows = await this.prisma.dvi_activity.findMany({
      where,
      orderBy: { activity_id: 'desc' },
      select: {
        activity_id: true,
        activity_title: true,
        hotspot_id: true,
        status: true,
      },
    });

    // fetch hotspot details map
    const hotspotIds = Array.from(new Set(rows.map((r) => r.hotspot_id).filter(Boolean)));
    let hotspotMap = new Map<number, { name: string; location: string }>();
    if (hotspotIds.length) {
      const hotspots = await this.prisma.dvi_hotspot_place.findMany({
        where: { hotspot_ID: { in: hotspotIds as any } },
        select: {
          hotspot_ID: true,
          hotspot_name: true,
          hotspot_location: true,
        },
      });
      hotspotMap = new Map(
        hotspots.map((h) => [h.hotspot_ID, { name: h.hotspot_name ?? '', location: h.hotspot_location ?? '' }]),
      );
    }

    let counter = 0;
    const data = rows.map((r) => {
      const hp = hotspotMap.get(r.hotspot_id) ?? { name: '', location: '' };
      return {
        counter: ++counter,
        modify: r.activity_id, // keep parity with PHP JSON field
        activity_title: r.activity_title ?? '',
        hotspot_name: hp.name,
        hotspot_location: hp.location,
        status: r.status,
        activity_id: r.activity_id,
      };
    });

    return { data };
  }

  // ====== HOTSPOTS for dropdown ======
  async hotspots(q?: string) {
    const where: any = { deleted: 0, status: 1 };
    if (q) {
      where.OR = [{ hotspot_name: { contains: q } }, { hotspot_location: { contains: q } }];
    }
    const rows = await this.prisma.dvi_hotspot_place.findMany({
      where,
      orderBy: [{ hotspot_priority: 'desc' }, { hotspot_name: 'asc' }],
      select: { hotspot_ID: true, hotspot_name: true, hotspot_location: true },
    });
    return rows.map((r) => ({
      id: r.hotspot_ID,
      label: [r.hotspot_name, r.hotspot_location].filter(Boolean).join(' — '),
    }));
  }

  // ====== CREATE BASIC INFO ======
  async createActivity(dto: {
    activity_title: string;
    hotspot_id: number | string;
    max_allowed_person_count: number | string;
    activity_duration?: string; // "HH:MM[:SS]"
    activity_description?: string;
    createdby?: number;
    // gallery (optional at create)
    imageNames?: string[];
    // default slots
    defaultSlots?: Array<{ start_time: string; end_time: string }>;
    // special day slots (optional)
    specialEnabled?: boolean;
    specialSlots?: Array<{ date: string; start_time: string; end_time: string }>;
  }) {
    if (!dto.activity_title) throw new BadRequestException('activity_title required');
    const hotspotId = toInt(dto.hotspot_id, 0);
    if (!hotspotId) throw new BadRequestException('hotspot_id required');
    const createdby = toInt(dto.createdby, 0);

    const created = await this.prisma.dvi_activity.create({
      data: {
        activity_title: dto.activity_title,
        hotspot_id: hotspotId,
        max_allowed_person_count: toInt(dto.max_allowed_person_count, 0),
        activity_duration: toTimeDate(dto.activity_duration),
        activity_description: dto.activity_description ?? null,
        createdby,
        status: 1,
        deleted: 0,
        createdon: new Date(),
      },
    });

    // gallery
    if (dto.imageNames?.length) {
      await this.prisma.dvi_activity_image_gallery_details.createMany({
        data: dto.imageNames.map((name) => ({
          activity_id: created.activity_id,
          activity_image_gallery_name: name,
          createdby,
          status: 1,
          deleted: 0,
          createdon: new Date(),
        })),
      });
    }

    // default time slots
    if (dto.defaultSlots?.length) {
      await this.prisma.dvi_activity_time_slot_details.createMany({
        data: dto.defaultSlots.map((s) => ({
          activity_id: created.activity_id,
          time_slot_type: 1, // Default
          special_date: null,
          start_time: toTimeDate(s.start_time),
          end_time: toTimeDate(s.end_time),
          createdby,
          status: 1,
          deleted: 0,
          createdon: new Date(),
        })),
      });
    }

    // special time slots
    if (dto.specialEnabled && dto.specialSlots?.length) {
      await this.prisma.dvi_activity_time_slot_details.createMany({
        data: dto.specialSlots.map((s) => ({
          activity_id: created.activity_id,
          time_slot_type: 2, // Special
          special_date: toDateOnly(s.date),
          start_time: toTimeDate(s.start_time),
          end_time: toTimeDate(s.end_time),
          createdby,
          status: 1,
          deleted: 0,
          createdon: new Date(),
        })),
      });
    }

    return created;
  }

  // ====== UPDATE BASIC INFO ======
  async updateActivity(
    id: number,
    dto: {
      activity_title?: string;
      hotspot_id?: number | string;
      max_allowed_person_count?: number | string;
      activity_duration?: string;
      activity_description?: string;
      updatedby?: number;
    },
  ) {
    const existing = await this.prisma.dvi_activity.findFirst({ where: { activity_id: id, deleted: 0 } });
    if (!existing) throw new NotFoundException('Activity not found');

    return this.prisma.dvi_activity.update({
      where: { activity_id: id },
      data: {
        activity_title: dto.activity_title ?? existing.activity_title,
        hotspot_id: dto.hotspot_id != null ? toInt(dto.hotspot_id) : existing.hotspot_id,
        max_allowed_person_count:
          dto.max_allowed_person_count != null ? toInt(dto.max_allowed_person_count) : existing.max_allowed_person_count,
        activity_duration: dto.activity_duration != null ? toTimeDate(dto.activity_duration) : existing.activity_duration,
        activity_description: dto.activity_description ?? existing.activity_description,
        updatedon: new Date(),
      },
    });
  }

  // ====== STATUS / DELETE ======
  async toggleStatus(id: number, status: number) {
    const existing = await this.prisma.dvi_activity.findFirst({ where: { activity_id: id, deleted: 0 } });
    if (!existing) throw new NotFoundException('Activity not found');
    return this.prisma.dvi_activity.update({
      where: { activity_id: id },
      data: { status: toInt(status) },
    });
  }

  async softDelete(id: number) {
    const existing = await this.prisma.dvi_activity.findFirst({ where: { activity_id: id, deleted: 0 } });
    if (!existing) throw new NotFoundException('Activity not found');
    return this.prisma.dvi_activity.update({
      where: { activity_id: id },
      data: { deleted: 1, updatedon: new Date() },
    });
  }

  // ====== GALLERY ======
  async addImages(activityId: number, imageNames: string[], createdby: number) {
    if (!imageNames?.length) return { count: 0 };
    const existing = await this.prisma.dvi_activity.findFirst({ where: { activity_id: activityId, deleted: 0 } });
    if (!existing) throw new NotFoundException('Activity not found');

    const res = await this.prisma.dvi_activity_image_gallery_details.createMany({
      data: imageNames.map((name) => ({
        activity_id: activityId,
        activity_image_gallery_name: name,
        createdby,
        status: 1,
        deleted: 0,
        createdon: new Date(),
      })),
    });
    return res;
  }

  async deleteImage(activityId: number, imageId: number) {
    // optional: verify image belongs to activity
    await this.prisma.dvi_activity_image_gallery_details.delete({
      where: { activity_image_gallery_details_id: imageId },
    });
    return { ok: true };
  }

  // ====== TIME SLOTS ======
  async saveTimeSlots(
    activityId: number,
    dto: {
      defaultSlots?: Array<{ start_time: string; end_time: string }>;
      specialEnabled?: boolean;
      specialSlots?: Array<{ date: string; start_time: string; end_time: string }>;
      createdby?: number;
    },
  ) {
    const existing = await this.prisma.dvi_activity.findFirst({ where: { activity_id: activityId, deleted: 0 } });
    if (!existing) throw new NotFoundException('Activity not found');

    // Strategy (parity with PHP UX):
    // - Remove existing default/special slots then re-insert provided
    await this.prisma.dvi_activity_time_slot_details.deleteMany({
      where: { activity_id: activityId },
    });

    const createdby = toInt(dto.createdby, 0);
    const data: any[] = [];

    if (dto.defaultSlots?.length) {
      for (const s of dto.defaultSlots) {
        data.push({
          activity_id: activityId,
          time_slot_type: 1,
          special_date: null,
          start_time: toTimeDate(s.start_time),
          end_time: toTimeDate(s.end_time),
          createdby,
          status: 1,
          deleted: 0,
          createdon: new Date(),
        });
      }
    }
    if (dto.specialEnabled && dto.specialSlots?.length) {
      for (const s of dto.specialSlots) {
        data.push({
          activity_id: activityId,
          time_slot_type: 2,
          special_date: toDateOnly(s.date),
          start_time: toTimeDate(s.start_time),
          end_time: toTimeDate(s.end_time),
          createdby,
          status: 1,
          deleted: 0,
          createdon: new Date(),
        });
      }
    }
    if (data.length) {
      await this.prisma.dvi_activity_time_slot_details.createMany({ data });
    }
    return { ok: true, count: data.length };
  }

  // ====== PRICEBOOK (month rows with 31 day columns) ======
  async savePriceBook(
    activityId: number,
    dto: {
      hotspot_id: number | string; // BigInt in schema
      start_date: string; // yyyy-mm-dd
      end_date: string; // yyyy-mm-dd
      createdby?: number;
      // flags per nationality
      indian?: {
        adult_cost?: number | string;
        child_cost?: number | string;
        infant_cost?: number | string;
      };
      nonindian?: {
        adult_cost?: number | string;
        child_cost?: number | string;
        infant_cost?: number | string;
      };
    },
  ) {
    const existing = await this.prisma.dvi_activity.findFirst({ where: { activity_id: activityId, deleted: 0 } });
    if (!existing) throw new NotFoundException('Activity not found');

    const hotspotId = toBigIntSafe(dto.hotspot_id);
    const start = toDateOnly(dto.start_date);
    const end = toDateOnly(dto.end_date);
    if (!start || !end || start > end) throw new BadRequestException('Invalid date range');

    const createdby = toInt(dto.createdby, 0);

    // Expand month by month
    const months: Array<{ y: number; m: number }> = [];
    {
      const cur = new Date(start);
      cur.setDate(1);
      const endMonth = new Date(end);
      endMonth.setDate(1);
      while (cur <= endMonth) {
        months.push({ y: cur.getFullYear(), m: cur.getMonth() + 1 });
        cur.setMonth(cur.getMonth() + 1);
      }
    }

    // helper to upsert a month row with a flat price across all days in that month
    const upsertMonth = async (year: number, month: number, priceType: number, nationality: number, value: number) => {
      const yyyy = String(year);
      const monthName = new Date(year, month - 1, 1).toLocaleString('en-US', { month: 'long' }); // e.g., "January"
      const dayVals: Record<string, number> = {};
      // fill all possible day1..31 with the price; PHP does the same when a month row is created
      for (let d = 1; d <= 31; d++) {
        dayVals[`day_${d}`] = value;
      }

      const existingRow = await this.prisma.dvi_activity_pricebook.findFirst({
        where: {
          activity_id: activityId,
          hotspot_id: hotspotId,
          nationality,
          price_type: priceType,
          year: yyyy,
        // @ts-ignore (Prisma model has `month` as string, e.g. "January")
          month: monthName,
          deleted: 0,
        },
        select: { activity_price_book_id: true },
      });

      if (existingRow) {
        await this.prisma.dvi_activity_pricebook.update({
          where: { activity_price_book_id: existingRow.activity_price_book_id },
          data: {
            ...dayVals,
            updatedon: new Date(),
            status: 1,
          },
        });
      } else {
        await this.prisma.dvi_activity_pricebook.create({
          data: {
            hotspot_id: hotspotId,
            activity_id: activityId,
            nationality,
            price_type: priceType,
            year: yyyy,
            month: monthName,
            ...dayVals,
            createdby,
            createdon: new Date(),
            status: 1,
            deleted: 0,
          } as any,
        });
      }
    };

    // Indian: nationality=1; price_type: 1=Adult, 2=Child, 3=Infant
    if (dto.indian) {
      const adult = toFloat(dto.indian.adult_cost, 0);
      const child = toFloat(dto.indian.child_cost, 0);
      const infant = toFloat(dto.indian.infant_cost, 0);
      for (const { y, m } of months) {
        if (adult > 0) await upsertMonth(y, m, 1, 1, adult);
        if (child > 0) await upsertMonth(y, m, 2, 1, child);
        if (infant > 0) await upsertMonth(y, m, 3, 1, infant);
      }
    }

    // Non-Indian: nationality=2
    if (dto.nonindian) {
      const adult = toFloat(dto.nonindian.adult_cost, 0);
      const child = toFloat(dto.nonindian.child_cost, 0);
      const infant = toFloat(dto.nonindian.infant_cost, 0);
      for (const { y, m } of months) {
        if (adult > 0) await upsertMonth(y, m, 1, 2, adult);
        if (child > 0) await upsertMonth(y, m, 2, 2, child);
        if (infant > 0) await upsertMonth(y, m, 3, 2, infant);
      }
    }

    return { ok: true, months: months.length };
  }

  // ====== REVIEWS ======
  async addOrUpdateReview(
    activityId: number,
    dto: { reviewId?: number; activity_rating: string; activity_description?: string; createdby?: number },
  ) {
    const existing = await this.prisma.dvi_activity.findFirst({ where: { activity_id: activityId, deleted: 0 } });
    if (!existing) throw new NotFoundException('Activity not found');

    if (!dto.activity_rating) throw new BadRequestException('activity_rating required');
    // NOTE: schema has VarChar(20) for activity_description (!)
    const trimmedDesc = (dto.activity_description ?? '').slice(0, 20);

    if (dto.reviewId) {
      const rev = await this.prisma.dvi_activity_review_details.findFirst({
        where: { activity_review_id: dto.reviewId, activity_id: activityId, deleted: 0 },
      });
      if (!rev) throw new NotFoundException('Review not found');
      return this.prisma.dvi_activity_review_details.update({
        where: { activity_review_id: dto.reviewId },
        data: {
          activity_rating: dto.activity_rating,
          activity_description: trimmedDesc || null,
          updatedon: new Date(),
        },
      });
    }

    return this.prisma.dvi_activity_review_details.create({
      data: {
        activity_id: activityId,
        activity_rating: dto.activity_rating,
        activity_description: trimmedDesc || null,
        createdby: toInt(dto.createdby, 0),
        createdon: new Date(),
        status: 1,
        deleted: 0,
      },
    });
  }

  async deleteReview(activityId: number, reviewId: number) {
    const rev = await this.prisma.dvi_activity_review_details.findFirst({
      where: { activity_review_id: reviewId, activity_id: activityId, deleted: 0 },
    });
    if (!rev) throw new NotFoundException('Review not found');
    await this.prisma.dvi_activity_review_details.update({
      where: { activity_review_id: reviewId },
      data: { deleted: 1, updatedon: new Date() },
    });
    return { ok: true };
  }

  // ====== PREVIEW ======
  async preview(activityId: number) {
    const act = await this.prisma.dvi_activity.findFirst({
      where: { activity_id: activityId, deleted: 0 },
    });
    if (!act) throw new NotFoundException('Activity not found');

    const [hotspot, images, slots, reviews] = await Promise.all([
      this.prisma.dvi_hotspot_place.findFirst({
        where: { hotspot_ID: act.hotspot_id as any },
        select: { hotspot_ID: true, hotspot_name: true, hotspot_location: true },
      }),
      this.prisma.dvi_activity_image_gallery_details.findMany({
        where: { activity_id: activityId, deleted: 0, status: 1 },
        orderBy: { activity_image_gallery_details_id: 'asc' },
        select: { activity_image_gallery_details_id: true, activity_image_gallery_name: true },
      }),
      this.prisma.dvi_activity_time_slot_details.findMany({
        where: { activity_id: activityId, deleted: 0, status: 1 },
        orderBy: [{ time_slot_type: 'asc' }, { special_date: 'asc' }, { start_time: 'asc' }],
      }),
      this.prisma.dvi_activity_review_details.findMany({
        where: { activity_id: activityId, deleted: 0, status: 1 },
        orderBy: { activity_review_id: 'desc' },
      }),
    ]);

    // ---- NEW: serialize time/date fields to strings ----
    const basic = {
      ...act,
      activity_duration: fmtHMS(act.activity_duration),
      createdon: fmtDateTimeISO(act.createdon),
      updatedon: fmtDateTimeISO(act.updatedon),
    };

    const defaultSlots = slots
      .filter((s) => s.time_slot_type === 1)
      .map((s) => ({
        ...s,
        start_time: fmtHMS(s.start_time),
        end_time: fmtHMS(s.end_time),
        special_date: null,
        createdon: fmtDateTimeISO(s.createdon),
        updatedon: fmtDateTimeISO(s.updatedon),
      }));

    const specialSlots = slots
      .filter((s) => s.time_slot_type === 2)
      .map((s) => ({
        ...s,
        start_time: fmtHMS(s.start_time),
        end_time: fmtHMS(s.end_time),
        special_date: fmtDateISO(s.special_date),
        createdon: fmtDateTimeISO(s.createdon),
        updatedon: fmtDateTimeISO(s.updatedon),
      }));

    const reviewsOut = reviews.map((r) => ({
      ...r,
      createdon: fmtDateTimeISO(r.createdon),
      updatedon: fmtDateTimeISO(r.updatedon),
    }));

    return {
      basic,
      hotspot,
      images,
      defaultSlots,
      specialSlots,
      reviews: reviewsOut,
    };
  }

  // ====== DETAILS ======
  async details(activityId: number) {
    const act = await this.prisma.dvi_activity.findFirst({
      where: { activity_id: activityId, deleted: 0 },
    });
    if (!act) throw new NotFoundException('Activity not found');

    const [hotspot, images] = await Promise.all([
      this.prisma.dvi_hotspot_place.findFirst({
        where: { hotspot_ID: act.hotspot_id as any },
        select: { hotspot_ID: true, hotspot_name: true, hotspot_location: true },
      }),
      this.prisma.dvi_activity_image_gallery_details.findMany({
        where: { activity_id: activityId, deleted: 0, status: 1 },
        orderBy: { activity_image_gallery_details_id: 'asc' },
        select: { activity_image_gallery_details_id: true, activity_image_gallery_name: true },
      }),
    ]);

    // ---- NEW: serialize time/date in details as well ----
    const out = {
      ...act,
      activity_duration: fmtHMS(act.activity_duration),
      createdon: fmtDateTimeISO(act.createdon),
      updatedon: fmtDateTimeISO(act.updatedon),
    };

    return { ...out, hotspot, images };
  }
}
