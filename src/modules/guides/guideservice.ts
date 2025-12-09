// FILE: src/modules/guides/guideservice.ts

import {
  BadRequestException,
  Injectable,
  NotFoundException,
} from '@nestjs/common';
import { Prisma, dvi_guide_details } from '@prisma/client';
import { PrismaService } from '../../prisma.service';

// ───────────────────────────────── helpers ──────────────────────────────────
const DEFAULT_PAGE = 1;
const DEFAULT_SIZE = 5000;

function toNum(v: unknown): number {
  const n = typeof v === 'number' ? v : Number(String(v ?? '').trim());
  return Number.isFinite(n) ? n : 0;
}
function pad2(n: number) {
  return n.toString().padStart(2, '0');
}
function ymd(d: Date | string | null | undefined): string {
  if (!d) return '';
  const dt = d instanceof Date ? d : new Date(d);
  if (!Number.isFinite(dt.getTime())) return '';
  return `${dt.getFullYear()}-${pad2(dt.getMonth() + 1)}-${pad2(dt.getDate())}`;
}
function startOfDayUTC(date?: Date | string | null): Date | null {
  if (!date) return null;
  const d = date instanceof Date ? date : new Date(date);
  if (!Number.isFinite(d.getTime())) return null;
  return new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate(), 0, 0, 0, 0));
}
function clamp(n: number, lo: number, hi: number) {
  return Math.max(lo, Math.min(hi, n));
}
function monthName(dt: Date) {
  return dt.toLocaleString('en-US', { month: 'long' });
}
function ensureId(id?: number) {
  if (!id || id <= 0) throw new BadRequestException('Invalid id');
  return id;
}

// Blood groups as PHP getBLOOD_GROUP(label) equivalent (1-indexed)
const BLOOD_GROUPS = [
  'A RhD positive (A+)',
  'A RhD negative (A-)',
  'B RhD positive (B+)',
  'B RhD negative (B-)',
  'O RhD positive (O+)',
  'O RhD negative (O-)',
  'AB RhD positive (AB+)',
  'AB RhD negative (AB-)',
];

// Gender enum parity with schema (guide_gender tinyint)
const GENDERS = [
  { id: 1, label: 'Male' },
  { id: 2, label: 'Female' },
  { id: 3, label: 'Other' },
];

// Guide slots like UI chips
const GUIDE_SLOTS = [
  { id: 1, label: 'Slot 1: 9 AM to 1 PM' },
  { id: 2, label: 'Slot 2: 2 PM to 4 PM' },
  { id: 3, label: 'Slot 3: 6 PM to 9 PM' },
];

// Pax buckets used in PHP screen
const GUIDE_PAX = [
  { id: 1, label: '1–5 Pax', min: 1, max: 5 },
  { id: 2, label: '6–14 Pax', min: 6, max: 14 },
  { id: 3, label: '15–40 Pax', min: 15, max: 40 },
];

// Slot types used in pricebook
const SLOT_TYPES = [
  { id: 1, label: '9 AM to 1 PM' },
  { id: 2, label: '9 AM to 4 PM' },
  { id: 3, label: '6 PM to 9 PM' },
];

// ───────────────────────────── local DTO shapes ─────────────────────────────
export type GuideListQueryDto = {
  page?: number;
  size?: number;
  q?: string;
  status?: number; // 0/1
};

export type GuideBasicDto = {
  id?: number;
  guide_name: string;
  guide_dob?: string; // yyyy-mm-dd
  guide_bloodgroup?: string;
  guide_gender?: number; // 1/2/3
  guide_primary_mobile_number: string;
  guide_alternative_mobile_number?: string;
  guide_email?: string;
  guide_emergency_mobile_number?: string;
  guide_language_proficiency?: string;
  guide_aadhar_number?: string;
  guide_experience?: string;
  guide_country?: number;
  guide_state?: number;
  guide_city?: number;

  gst_type?: number; // 1=Included, 2=Excluded, 3=NA
  guide_gst?: number; // e.g. 18
  guide_available_slot?: number[]; // [1,2,3]

  // Bank
  guide_bank_name?: string;
  guide_bank_branch_name?: string;
  guide_ifsc_code?: string;
  guide_account_number?: string;
  guide_confirm_account_number?: string;

  // Preferred For (CSV "hotspot,activity,itinerary" parity)
  guide_preffered_for?: string[];

  status?: number; // 0/1
  deleted?: number; // 0/1
};

export type GuidePricebookSaveDto = {
  guide_id: number;
  start_date: string; // yyyy-mm-dd
  end_date: string; // yyyy-mm-dd
  pax_prices: Array<{
    pax_id: number; // 1,2,3
    slot_id: number; // 1,2,3
    price: number;
  }>;
};

export type GuideReviewSaveDto = {
  guide_id: number;
  rating: number; // 1..5
  description: string;
};

@Injectable()
export class GuidesService {
  constructor(private readonly prisma: PrismaService) {}

  // ─────────────────────────────── List (DataTable) ──────────────────────────
  async list(q: GuideListQueryDto) {
    const page = q.page ?? DEFAULT_PAGE;
    const size = q.size ?? DEFAULT_SIZE;
    const skip = (page - 1) * size;
    const take = size;

    const where: Prisma.dvi_guide_detailsWhereInput = {
      deleted: 0,
    };
    if (q.status != null) where.status = q.status;
    if (q.q?.trim()) {
      const text = q.q.trim();
      where.OR = [
        { guide_name: { contains: text } as any },
        { guide_email: { contains: text } as any },
        { guide_primary_mobile_number: { contains: text } as any },
        { guide_alternative_mobile_number: { contains: text } as any },
      ];
    }

    const rows = await this.prisma.dvi_guide_details.findMany({
      where,
      orderBy: [{ guide_name: 'asc' }, { guide_id: 'desc' }],
      skip,
      take,
      select: {
        guide_id: true,
        guide_name: true,
        guide_dob: true,
        guide_bloodgroup: true,
        guide_gender: true,
        guide_primary_mobile_number: true,
        guide_alternative_mobile_number: true,
        guide_email: true,
        guide_emergency_mobile_number: true,
        guide_language_proficiency: true,
        status: true,
      },
    });

    const data = rows.map((r, idx) => ([
      {
        counter: skip + idx + 1,
        modify: r.guide_id,
        guide_name: r.guide_name ?? '',
        guide_primary_mobile_number: r.guide_primary_mobile_number ?? '',
        guide_email: r.guide_email ?? '',
        status: Number(r.status ?? 0),
        // extra
        guide_gender: r.guide_gender ?? 0,
        guide_bloodgroup: r.guide_bloodgroup ?? null,
        guide_dob: ymd(r.guide_dob ?? null),
      }
    ] as unknown as any)).flat();

    return { data };
  }

  // ────────────────────── Dynamic dropdowns for Add/Edit ─────────────────────
  async formOptions() {
    const languagesRaw = await this.prisma.dvi_language.findMany({
      where: { deleted: false, status: 1 as any },
      orderBy: { language_id: 'asc' },
      select: { language_id: true, language: true },
    });

    const states = await this.prisma.dvi_states.findMany({
      where: { deleted: 0 },
      orderBy: [{ country_id: 'asc' }, { name: 'asc' }],
      select: { id: true, name: true, country_id: true },
    });

    const gst = await this.prisma.dvi_gst_setting.findMany({
      where: { deleted: 0, status: 1 as any },
      orderBy: [{ gst_value: 'asc' }],
      select: { gst_setting_id: true, gst_title: true, gst_value: true },
    });

    return {
      bloodGroups: BLOOD_GROUPS,
      genders: GENDERS,
      guideSlots: GUIDE_SLOTS,
      languages: languagesRaw.map((l) => ({
        id: Number(l.language_id),
        label: l.language ?? '',
      })),
      states: states.map((s) => ({
        id: Number(s.id),
        name: s.name,
        countryId: Number(s.country_id),
      })),
      gst: gst.map((g) => ({
        id: Number(g.gst_setting_id),
        title: g.gst_title ?? '',
        value: Number(g.gst_value ?? 0),
      })),
    };
  }

  // ─────────────────────────────── Get form (edit) ───────────────────────────
  async getForm(id: number) {
    id = ensureId(id);

    const g = await this.prisma.dvi_guide_details.findUnique({
      where: { guide_id: id },
    });
    if (!g || g.deleted === 1) throw new NotFoundException('Guide not found');

    const reviews = await this.prisma.dvi_guide_review_details.findMany({
      where: { guide_id: id, deleted: 0 },
      orderBy: [{ guide_review_id: 'desc' }],
    });

    const payload: GuideBasicDto = {
      id: g.guide_id,
      guide_name: g.guide_name ?? '',
      guide_dob: ymd(g.guide_dob),
      guide_bloodgroup: g.guide_bloodgroup ?? '',
      guide_gender: Number(g.guide_gender ?? 0),
      guide_primary_mobile_number: g.guide_primary_mobile_number ?? '',
      guide_alternative_mobile_number: g.guide_alternative_mobile_number ?? '',
      guide_email: g.guide_email ?? '',
      guide_emergency_mobile_number: g.guide_emergency_mobile_number ?? '',
      guide_language_proficiency: g.guide_language_proficiency ?? '',
      guide_aadhar_number: g.guide_aadhar_number ?? '',
      guide_experience: g.guide_experience ?? '',
      guide_country: Number(g.guide_country ?? 0),
      guide_state: Number(g.guide_state ?? 0),
      guide_city: Number(g.guide_city ?? 0),
      gst_type: Number((g as any).gst_type ?? 0),
      guide_gst: Number((g as any).guide_gst ?? 0),
      guide_available_slot: String((g as any).guide_available_slot ?? '')
        .split(',')
        .map((s) => Number(s.trim()))
        .filter((x) => Number.isFinite(x) && x > 0),
      guide_bank_name: (g as any).guide_bank_name ?? '',
      guide_bank_branch_name: (g as any).guide_bank_branch_name ?? '',
      guide_ifsc_code: (g as any).guide_ifsc_code ?? '',
      guide_account_number: (g as any).guide_account_number ?? '',
      guide_confirm_account_number: (g as any).guide_confirm_account_number ?? '',
      guide_preffered_for: String((g as any).guide_preffered_for ?? '')
        .split(',')
        .map((s) => s.trim())
        .filter(Boolean),
      status: Number(g.status ?? 1),
      deleted: Number(g.deleted ?? 0),
    };

    const now = new Date();
    const yearStr = String(now.getFullYear());
    const monthStr = monthName(now);

    const pricebookRows = await this.prisma.dvi_guide_pricebook.findMany({
      where: {
        guide_id: id,
        year: yearStr as any,
        month: monthStr as any,
        deleted: 0,
      },
      orderBy: [{ pax_count: 'asc' }, { slot_type: 'asc' }],
    });

    return {
      payload,
      reviews,
      pricebook: pricebookRows,
      options: await this.formOptions(),
    };
  }

  // ───────────────────────────── Save Step 1 (basic) ─────────────────────────
  async saveBasic(input: GuideBasicDto) {
    if (!input.guide_name?.trim()) {
      throw new BadRequestException('guide_name is required');
    }
    if (!input.guide_primary_mobile_number?.trim()) {
      throw new BadRequestException('guide_primary_mobile_number is required');
    }
    if (
      input.guide_account_number &&
      input.guide_confirm_account_number &&
      input.guide_account_number !== input.guide_confirm_account_number
    ) {
      throw new BadRequestException('Account number & confirm do not match');
    }

    const masterData: Prisma.dvi_guide_detailsUncheckedCreateInput = {
      guide_name: input.guide_name.trim(),
      guide_dob: input.guide_dob ? startOfDayUTC(input.guide_dob) as any : null,
      guide_bloodgroup: input.guide_bloodgroup ?? null,
      guide_gender: (input.guide_gender ?? 0) as any,
      guide_primary_mobile_number: input.guide_primary_mobile_number ?? null,
      guide_alternative_mobile_number: input.guide_alternative_mobile_number ?? null,
      guide_email: input.guide_email ?? null,
      guide_emergency_mobile_number: input.guide_emergency_mobile_number ?? null,
      guide_language_proficiency: input.guide_language_proficiency ?? null,
      guide_aadhar_number: input.guide_aadhar_number ?? null,
      guide_experience: input.guide_experience ?? null,
      guide_country: (input.guide_country ?? 0) as any,
      guide_state: (input.guide_state ?? 0) as any,
      guide_city: (input.guide_city ?? 0) as any,

      gst_type: (input.gst_type ?? 0) as any,
      guide_gst: (input.guide_gst ?? 0) as any,
      guide_available_slot: Array.isArray(input.guide_available_slot)
        ? input.guide_available_slot.join(',')
        : '',

      guide_bank_name: input.guide_bank_name ?? null,
      guide_bank_branch_name: input.guide_bank_branch_name ?? null,
      guide_ifsc_code: input.guide_ifsc_code ?? null,
      guide_account_number: input.guide_account_number ?? null,
      guide_confirm_account_number: input.guide_confirm_account_number ?? null,

      guide_preffered_for: Array.isArray(input.guide_preffered_for)
        ? input.guide_preffered_for.join(',')
        : null,

      status: (input.status ?? 1) as any,
      deleted: (input.deleted ?? 0) as any,
    } as any;

    let saved: dvi_guide_details;
    if (input.id && input.id > 0) {
      saved = await this.prisma.dvi_guide_details.update({
        where: { guide_id: input.id },
        data: masterData as any,
      });
    } else {
      saved = await this.prisma.dvi_guide_details.create({
        data: masterData as any,
      });
    }
    return { id: saved.guide_id };
  }

  // ───────────────────────────── Save Step 2 (pricebook) ─────────────────────
  async savePricebook(input: GuidePricebookSaveDto) {
    const guideId = ensureId(input.guide_id);
    const sd = startOfDayUTC(input.start_date);
    const ed = startOfDayUTC(input.end_date);
    if (!sd || !ed || ed < sd) throw new BadRequestException('Invalid date range');

    const year = sd.getUTCFullYear();
    const month = sd.getUTCMonth(); // 0..11
    const monthStr = monthName(sd);
    const yearStr = String(year);

    const lastDay = new Date(Date.UTC(year, month + 1, 0)).getUTCDate();

    const dayVal: number[] = new Array(lastDay + 1).fill(0); // 1..lastDay
    for (let d = 1; d <= lastDay; d++) {
      const cur = new Date(Date.UTC(year, month, d));
      if (cur >= sd && cur <= ed) dayVal[d] = 1;
    }

    for (const row of input.pax_prices ?? []) {
      const pax = clamp(Number(row.pax_id), 1, 3);
      const slot = clamp(Number(row.slot_id), 1, 3);
      const price = toNum(row.price);

      const perDay: Record<string, number | null> = {};
      for (let d = 1; d <= 31; d++) {
        const key = `day_${d}` as keyof Prisma.dvi_guide_pricebookUncheckedCreateInput;
        (perDay as any)[key] = d <= lastDay && dayVal[d] ? price : 0;
      }

      const existing = await this.prisma.dvi_guide_pricebook.findFirst({
        where: {
          guide_id: guideId as any,
          year: yearStr as any,
          month: monthStr as any,
          pax_count: pax as any,
          slot_type: slot as any,
          deleted: 0,
        },
        select: { guide_price_book_ID: true },
      });

      if (existing) {
        await this.prisma.dvi_guide_pricebook.update({
          where: { guide_price_book_ID: existing.guide_price_book_ID },
          data: {
            ...perDay,
            updatedon: new Date() as any,
            status: 1 as any,
          } as any,
        });
      } else {
        await this.prisma.dvi_guide_pricebook.create({
          data: {
            guide_id: guideId as any,
            year: yearStr as any,
            month: monthStr as any,
            pax_count: pax as any,
            slot_type: slot as any,
            ...perDay,
            status: 1 as any,
            deleted: 0 as any,
          } as any,
        });
      }
    }

    return { ok: true, guide_id: guideId, month: monthStr, year: yearStr };
  }

  // ───────────────────────────── Save Step 3 (reviews) ───────────────────────
  async addReview(input: GuideReviewSaveDto) {
    const guideId = ensureId(input.guide_id);
    const rating = clamp(Number(input.rating ?? 0), 1, 5);
    const description = String(input.description ?? '').trim();
    if (!description) throw new BadRequestException('description is required');

    const created = await this.prisma.dvi_guide_review_details.create({
      data: {
        guide_id: guideId as any,
        guide_rating: String(rating) as any,
        guide_description: description as any,
        status: 1 as any,
        deleted: 0 as any,
        createdon: new Date() as any,
      } as any,
    });

    return { id: created.guide_review_id };
  }

  async listReviews(guideId: number) {
    guideId = ensureId(guideId);
    const items = await this.prisma.dvi_guide_review_details.findMany({
      where: { guide_id: guideId, deleted: 0 },
      orderBy: [{ guide_review_id: 'desc' }],
    });
    return { data: items };
  }

  async deleteReview(reviewId: number) {
    reviewId = ensureId(reviewId);
    await this.prisma.dvi_guide_review_details.update({
      where: { guide_review_id: reviewId },
      data: { deleted: 1 as any },
    });
    return { ok: true };
  }

  // ───────────────────────────── Step 4 (preview) ────────────────────────────
  /**
   * Returns raw row + humanized `view` (PHP-parity labels).
   * Adds: city_name & country_name so React shows names instead of IDs.
   */
  async getPreview(guideId: number) {
    guideId = ensureId(guideId);

    const g = await this.prisma.dvi_guide_details.findUnique({
      where: { guide_id: guideId },
    });
    if (!g || g.deleted === 1) throw new NotFoundException('Guide not found');

    // Lookups needed for labels
    const [stateRow, cityRow, langRow] = await Promise.all([
      g.guide_state
        ? this.prisma.dvi_states.findUnique({
            where: { id: Number(g.guide_state) },
            select: { id: true, name: true, country_id: true },
          })
        : Promise.resolve(null),
      g.guide_city
        ? this.prisma.dvi_cities.findUnique({
            where: { id: Number(g.guide_city) },
            select: { id: true, name: true, state_id: true },
          })
        : Promise.resolve(null),
      g.guide_language_proficiency
        ? this.prisma.dvi_language.findUnique({
            where: { language_id: Number(g.guide_language_proficiency) as any },
            select: { language: true },
          })
        : Promise.resolve(null),
    ]);

    const dob = g.guide_dob ? new Date(g.guide_dob as any) : null;
    const dob_text =
      dob && Number.isFinite(dob.getTime())
        ? `${pad2(dob.getUTCDate())}-${pad2(dob.getUTCMonth() + 1)}-${dob.getUTCFullYear()}`
        : '';

    const gender_label =
      GENDERS.find((x) => x.id === Number(g.guide_gender ?? 0))?.label ?? '';

    const bgIndex = Number(g.guide_bloodgroup ?? 0) - 1; // DB stores "1".."8"
    const blood_group_label = BLOOD_GROUPS[bgIndex] ?? (g.guide_bloodgroup ?? '');

    const language_label =
      langRow?.language ??
      (typeof g.guide_language_proficiency === 'string'
        ? g.guide_language_proficiency
        : '');

    const state_name = stateRow?.name ?? '';
    const city_name = cityRow?.name ?? '';

    // Country display: PHP shows "India" for 101, else raw code
    const country_code = Number(stateRow?.country_id ?? g.guide_country ?? 0);
    const country_name = country_code === 101 ? 'India' : String(country_code || '');

    const gst_percent_text =
      (g as any).guide_gst != null && Number((g as any).guide_gst) !== 0
        ? `${Number((g as any).guide_gst)}%`
        : '';

    const reviews = await this.prisma.dvi_guide_review_details.findMany({
      where: { guide_id: guideId, deleted: 0 },
      orderBy: [{ guide_review_id: 'desc' }],
      select: {
        guide_review_id: true,
        guide_id: true,
        guide_rating: true,
        guide_description: true,
        createdon: true,
      },
    });

    const slots = String((g as any).guide_available_slot ?? '')
      .split(',')
      .map((s) => Number(String(s).trim()))
      .filter((x) => Number.isFinite(x) && x > 0)
      .map((id) => GUIDE_SLOTS.find((s) => s.id === id)?.label || `Slot ${id}`);

    const preferredFor = String((g as any).guide_preffered_for ?? '')
      .split(',')
      .map((s) => s.trim())
      .filter(Boolean);

    return {
      basic: g,
      view: {
        dob_text,
        gender_label,
        blood_group_label,
        language_label,
        state_name,
        city_name,          // ✅ NEW
        country_name,       // ✅ now "India" for 101
        gst_percent_text,
      },
      reviews,
      slots,
      preferredFor,
    };
  }

  async previewOptions() {
    const states = await this.prisma.dvi_states.findMany({
      where: { deleted: 0 },
      orderBy: [{ country_id: 'asc' }, { name: 'asc' }],
      select: { id: true, name: true, country_id: true },
    });

    return {
      states: states.map((s) => ({
        id: Number(s.id),
        name: s.name,
        countryId: Number(s.country_id),
      })),
    };
  }

  // ───────────────────────────── status / delete ─────────────────────────────
  async toggleStatus(id: number, status: number) {
    id = ensureId(id);
    await this.prisma.dvi_guide_details.update({
      where: { guide_id: id },
      data: { status: (status ? 1 : 0) as any },
    });
    return { ok: true };
  }

  async softDelete(id: number) {
    id = ensureId(id);
    await this.prisma.dvi_guide_details.update({
      where: { guide_id: id },
      data: { deleted: 1 as any },
    });
    return { ok: true };
  }

  // ─────────────────────── convenience (add/edit orchestration) ─────────────
  async saveFormStep1(input: GuideBasicDto) {
    return this.saveBasic(input);
  }

  async saveFormStep2AndPreview(pricing: GuidePricebookSaveDto) {
    await this.savePricebook(pricing);
    return this.getPreview(pricing.guide_id);
  }

  // ───────────────────────────── Dropdown Data (Service) ─────────────────────────────

/** Role dropdown → dvi_rolemenu.role_name */
async getRolesDropdown() {
  const rows = await this.prisma.dvi_rolemenu.findMany({
    where: { deleted: 0, status: 1 },
    select: { role_ID: true, role_name: true },
    orderBy: { role_name: 'asc' },
  });
  return rows.map(r => ({ id: r.role_ID, label: r.role_name }));
}

/** Language Proficiency dropdown → dvi_language.language */
async getLanguagesDropdown() {
  const rows = await this.prisma.dvi_language.findMany({
    where: { status: 1 },
    select: { language_id: true, language: true },
    orderBy: { language: 'asc' },
  });
  return rows.map(r => ({ id: r.language_id, label: r.language }));
}

/** Country dropdown → dvi_country (assuming column name `country`) */
async getCountriesDropdown() {
  const rows = await this.prisma.dvi_countries.findMany({
    where: { deleted: 0, status: 1 },
    select: { id: true, name: true },
    orderBy: { name: 'asc' },
  });
  return rows.map(r => ({ id: r.id, label: r.name }));
}

/** State dropdown (dependent) → dvi_state.state filtered by country_id */
async getStatesDropdown(countryId: number) {
  if (!countryId || countryId <= 0) {
    throw new BadRequestException('countryId is required');
  }
  const rows = await this.prisma.dvi_states.findMany({
    where: { deleted: 0, id: countryId },
    select: { id: true, name: true },
    orderBy: { name: 'asc' },
  });
  return rows.map(r => ({ id: r.id, label: r.name }));
}

/** City dropdown (dependent) → dvi_city.city filtered by state_id */
async getCitiesDropdown(stateId: number) {
  if (!stateId || stateId <= 0) {
    throw new BadRequestException('stateId is required');
  }
  const rows = await this.prisma.dvi_cities.findMany({
    where: { deleted: 0, status: 1, state_id: stateId },
    select: { id: true, name: true },
    orderBy: { name: 'asc' },
  });
  return rows.map(r => ({ id: r.id, label: r.name }));
}

/** GST Type dropdown → static mapping: Included=1, Excluded=2  */
async getGstTypesDropdown() {
  return [
    { value: 1, label: 'Included' },
    { value: 2, label: 'Excluded' },
  ];
}

/** GST% dropdown → dvi_gst_setting.gst_title */
async getGstPercentagesDropdown() {
  const rows = await this.prisma.dvi_gst_setting.findMany({
    where: { deleted: 0, status: 1 },
    select: { gst_setting_id: true, gst_title: true },
    orderBy: { gst_title: 'asc' },
  });
  // Expect gst_title like "5%", "12%", etc.
  return rows.map(r => ({ id: r.gst_setting_id, label: r.gst_title }));
}

/** Hotspot Place dropdown → dvi_hotspot_place.hotspot_name */
async getHotspotPlacesDropdown() {
  const rows = await this.prisma.dvi_hotspot_place.findMany({
    where: { deleted: 0, status: 1 },
    select: { hotspot_ID: true, hotspot_name: true },
    orderBy: { hotspot_name: 'asc' },
  });
  return rows.map(r => ({ id: r.hotspot_ID, label: r.hotspot_name }));
}

/** Activity dropdown → dvi_activity.activity_title */
async getActivitiesDropdown() {
  const rows = await this.prisma.dvi_activity.findMany({
    where: { deleted: 0, status: 1 },
    select: { activity_id: true, activity_title: true },
    orderBy: { activity_title: 'asc' },
  });
  return rows.map(r => ({ id: r.activity_id, label: r.activity_title }));
}

/**
 * All dropdowns in one call (optionally dependent lists via query input).
 * Pass `countryId` and/or `stateId` to get dependent lists scoped properly.
 */
async getAllDropdowns(params?: { countryId?: number; stateId?: number }) {
  const { countryId, stateId } = params ?? {};
  const [
    roles,
    languages,
    countries,
    gstTypes,
    gstPercentages,
    hotspots,
    activities,
  ] = await Promise.all([
    this.getRolesDropdown(),
    this.getLanguagesDropdown(),
    this.getCountriesDropdown(),
    this.getGstTypesDropdown(),
    this.getGstPercentagesDropdown(),
    this.getHotspotPlacesDropdown(),
    this.getActivitiesDropdown(),
  ]);

  const states = countryId ? await this.getStatesDropdown(countryId) : [];
  const cities = stateId ? await this.getCitiesDropdown(stateId) : [];

  return {
    roles,
    languages,
    countries,
    states,
    cities,
    gstTypes,
    gstPercentages,
    hotspots,
    activities,
  };
}
}
