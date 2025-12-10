// FILE: src/services/hotels.ts
import { api,RAW_API_BASE } from "@/lib/api";

/**
 * UI-facing types stay the same.
 * Internally we map to/from backend field names:
 *  - hotel_id        ↔ id
 *  - hotel_name      ↔ name
 *  - hotel_code      ↔ code
 *  - hotel_state     ↔ state
 *  - hotel_city      ↔ city
 *  - hotel_place     ↔ place
 *  - hotel_mobile    ↔ phone
 *  - hotel_email     ↔ email
 *  - hotel_address   ↔ addressLine1
 *  - hotel_pincode   ↔ pinCode
 *  - hotel_rating    ↔ starRating
 *  - hotel_latitude  ↔ latitude
 *  - hotel_longitude ↔ longitude
 *  - hotel_margin    ↔ margin
 *  - hotel_margin_gst_type        ↔ gstType
 *  - hotel_margin_gst_percentage  ↔ gstPercent
 *  - hotel_powerbackup            ↔ powerBackup (boolean)
 *  - hotel_hotspot_status         ↔ hotSpot (boolean)
 *  - status (0|1)    ↔ isActive (boolean)
 */
export type Hotel = {
  id?: string;
  name: string;
  code?: string;
  starRating?: number | null;
  description?: string | null;
  addressLine1?: string | null;
  addressLine2?: string | null;
  city?: string | null;
  state?: string | null;
  country?: string | null;
  pinCode?: string | null;
  phone?: string | null;
  email?: string | null;
  website?: string | null;
  isActive?: boolean;

  // === Extra fields for PHP parity ===
  place?: string | null;
  latitude?: number | null;
  longitude?: number | null;
  margin?: number | null;
  gstType?: number | null; // 0-none, 1-CGST/SGST, 2-IGST
  gstPercent?: number | null;
  powerBackup?: boolean;
  hotSpot?: boolean;
};

export type HotelsListResponse = {
  page: number;
  total: number;
  items: Hotel[];
};

/* =========================
 * Helpers
 * ========================= */

function toBool(v: any): boolean {
  if (typeof v === "boolean") return v;
  if (v === 1 || v === "1" || v === "true" || v === "TRUE") return true;
  return false;
}

function numOrNull(v: any): number | null {
  if (v === "" || v === undefined || v === null) return null;
  const n = Number(v);
  return Number.isFinite(n) ? n : null;
}

/** Strip /api/v1 if someone passes it by mistake. */
function stripApiPrefix(path: string) {
  if (/^https?:\/\//i.test(path)) return path;
  return path.replace(/^\/api\/v1/, "");
}

/* =========================
 * Helpers: mapping
 * ========================= */

function fromBackend(h: any): Hotel {
  if (!h) return { name: "" };
  return {
    id: String(h.hotel_id ?? h.id ?? ""),
    name: h.hotel_name ?? h.name ?? "",
    code: h.hotel_code ?? h.code ?? null,

    // numbers / misc
    starRating: h.hotel_rating ?? h.starRating ?? null,
    latitude: h.hotel_latitude ?? h.latitude ?? null,
    longitude: h.hotel_longitude ?? h.longitude ?? null,
    margin: h.hotel_margin ?? h.margin ?? null,
    gstType: h.hotel_margin_gst_type ?? h.gstType ?? null,
    gstPercent: h.hotel_margin_gst_percentage ?? h.gstPercent ?? null,

    // toggles
    powerBackup: toBool(h.hotel_powerbackup ?? h.powerBackup ?? false),
    hotSpot: toBool(h.hotel_hotspot_status ?? h.hotSpot ?? false),

    // description
    description: h.description ?? null,

    // address / contact
    addressLine1: h.hotel_address ?? h.addressLine1 ?? null,
    addressLine2: h.addressLine2 ?? null,
    place: h.hotel_place ?? h.place ?? null,
    city: h.hotel_city ?? h.city ?? null,
    state: h.hotel_state ?? h.state ?? null,
    country: h.country ?? null,
    pinCode: h.hotel_pincode ?? h.pinCode ?? null,
    phone: h.hotel_mobile ?? h.phone ?? null,
    email: h.hotel_email ?? h.email ?? null,
    website: h.website ?? null,

    // status
    isActive:
      typeof h.status === "number"
        ? h.status === 1
        : typeof h.status === "boolean"
        ? h.status
        : Boolean(h.isActive),
  };
}

function toBackend(body: Partial<Hotel>): any {
  const out: any = {};

  // Basic identity & codes
  if (body.name !== undefined) out.hotel_name = body.name;
  if (body.code !== undefined) out.hotel_code = body.code ?? null;

  // Address & location
  if (body.addressLine1 !== undefined) out.hotel_address = body.addressLine1 ?? null;
  if (body.place !== undefined) out.hotel_place = body.place ?? null;
  if (body.city !== undefined) out.hotel_city = body.city ?? null;
  if (body.state !== undefined) out.hotel_state = body.state ?? null;
  if (body.pinCode !== undefined) out.hotel_pincode = body.pinCode ?? null;
  if (body.country !== undefined) out.country = body.country ?? null;
  if (body.addressLine2 !== undefined) out.addressLine2 = body.addressLine2 ?? null;

  // Contact
  if (body.phone !== undefined) out.hotel_mobile = body.phone ?? null;
  if (body.email !== undefined) out.hotel_email = body.email ?? null;
  if (body.website !== undefined) out.website = body.website ?? null;

  // Description / star rating
  if (body.description !== undefined) out.description = body.description ?? null;
  if (body.starRating !== undefined) out.hotel_rating = numOrNull(body.starRating);

  // Lat/Lng
  if (body.latitude !== undefined) out.hotel_latitude = numOrNull(body.latitude);
  if (body.longitude !== undefined) out.hotel_longitude = numOrNull(body.longitude);

  // Margin + GST
  if (body.margin !== undefined) out.hotel_margin = numOrNull(body.margin);
  if (body.gstType !== undefined) out.hotel_margin_gst_type = numOrNull(body.gstType);
  if (body.gstPercent !== undefined) out.hotel_margin_gst_percentage = numOrNull(body.gstPercent);

  // Toggles (backend expects 0|1)
  if (body.powerBackup !== undefined) out.hotel_powerbackup = body.powerBackup ? 1 : 0;
  if (body.hotSpot !== undefined) out.hotel_hotspot_status = body.hotSpot ? 1 : 0;

  // Status (backend expects 0|1)
  if (body.isActive !== undefined) out.status = body.isActive ? 1 : 0;

  return out;
}

// Try a primary route, on 404 fall back to an alternate route (to avoid breaking existing calls)
async function apiWithFallback<T>(
  primaryPath: string,
  init?: any,
  fallbackPath?: string
): Promise<T> {
  try {
    return (await api(stripApiPrefix(primaryPath), init)) as T;
  } catch (e: any) {
    const msg = String(e?.message ?? "");
    const parsedStatus =
      e?.status ??
      e?.response?.status ??
      (/[^0-9]/.test(msg) ? 404 : undefined);

    if (parsedStatus === 404 && fallbackPath) {
      return (await api(stripApiPrefix(fallbackPath), init)) as T;
    }
    throw e;
  }
}

/* =========================
 * Public API
 * ========================= */

export async function listHotels(params: { search?: string; page?: number; limit?: number } = {}) {
  const q = new URLSearchParams();
  if (params.search) q.set("search", params.search);
  q.set("page", String(params.page ?? 1));
  q.set("limit", String(params.limit ?? 20));

  const raw = (await api(`/hotels?${q.toString()}`)) as
    | { page?: number; limit?: number; total?: number; rows?: any[]; items?: any[] }
    | any;

  const rawItems = Array.isArray(raw?.rows)
    ? raw.rows
    : Array.isArray(raw?.items)
    ? raw.items
    : [];
  const items: Hotel[] = rawItems.map(fromBackend);

  const resp: HotelsListResponse = {
    page: Number(raw?.page ?? params.page ?? 1),
    total: Number(raw?.total ?? rawItems.length),
    items,
  };
  return resp;
}

export async function getHotel(id: string) {
  const h = await api(`/hotels/${id}`);
  const hotel = fromBackend(h);

  return {
    ...hotel,
    rooms: [] as any[],
    amenities: [] as any[],
    priceBooks: [] as any[],
    reviews: [] as any[],
  } as Hotel & {
    rooms: any[];
    amenities: any[];
    priceBooks: any[];
    reviews: any[];
  };
}

export async function getHotelBackendRow(id: string) {
  // Raw row for Preview step (PHP-like shape if needed)
  return api(`/hotels/${id}`);
}

export async function createHotel(payload: Partial<Hotel>) {
  const body = toBackend(payload);
  const created = await api(`/hotels`, { method: "POST", body });
  return fromBackend(created);
}

export async function updateHotel(id: string, payload: Partial<Hotel>) {
  const body = toBackend(payload);
  const updated = await api(`/hotels/${id}`, { method: "PATCH", body });
  return fromBackend(updated);
}

export async function deleteHotel(id: string) {
  return api(`/hotels/${id}`, { method: "DELETE" }) as Promise<{ success: boolean }>;
}

/* ========= Meta (states / cities) ========= */

export async function listStatesMeta() {
  // backend: GET /meta/states?all=1
  return api(`/meta/states?all=1`);
}

export async function listCitiesMeta() {
  // backend: GET /meta/cities?all=1
  return api(`/meta/cities?all=1`);
}

/* =========================
 * Nested resources
 * ========================= */

export async function addRoom(
  hotelId: string,
  body: { name: string; occupancy?: number; bedType?: string; sizeSqft?: number; isActive?: boolean }
) {
  return api(`/hotels/${hotelId}/rooms`, { method: "POST", body });
}

export async function deleteRoom(hotelId: string, roomId: string) {
  return api(`/hotels/${hotelId}/rooms/${roomId}`, { method: "DELETE" });
}

export async function addAmenity(
  hotelId: string,
  body: { name: string; category?: string; isActive?: boolean }
) {
  return api(`/hotels/${hotelId}/amenities`, { method: "POST", body });
}

export async function deleteAmenity(hotelId: string, amenityId: string) {
  return api(`/hotels/${hotelId}/amenities/${amenityId}`, { method: "DELETE" });
}

export async function addPriceBook(
  hotelId: string,
  body: {
    roomName: string;
    season?: string;
    basePrice: string;
    currency?: string;
    extraAdult?: string;
    extraChild?: string;
    effectiveFrom: string | Date;
    effectiveTo: string | Date;
  }
) {
  const normalized = {
    ...body,
    effectiveFrom:
      typeof body.effectiveFrom === "string"
        ? new Date(body.effectiveFrom).toISOString()
        : body.effectiveFrom.toISOString(),
    effectiveTo:
      typeof body.effectiveTo === "string"
        ? new Date(body.effectiveTo).toISOString()
        : body.effectiveTo.toISOString(),
  };

  return apiWithFallback(
    `/hotels/${hotelId}/pricebooks`,
    { method: "POST", body: normalized },
    `/hotels/${hotelId}/pricebook`
  );
}

export async function deletePriceBook(hotelId: string, priceBookId: string) {
  return apiWithFallback(
    `/hotels/${hotelId}/pricebooks/${priceBookId}`,
    { method: "DELETE" },
    `/hotels/${hotelId}/pricebook/${priceBookId}`
  );
}

export async function addReview(
  hotelId: string,
  body: { rating: number; comment?: string; author?: string }
) {
  return api(`/hotels/${hotelId}/reviews`, { method: "POST", body });
}

export async function deleteReview(hotelId: string, reviewId: string) {
  return api(`/hotels/${hotelId}/reviews/${reviewId}`, { method: "DELETE" });
}

/* ========= Hotel Form Wizard API context =========
   Used by BasicStep / RoomsStep / AmenitiesStep / PriceBookStep / ReviewStep
   So TSX never calls api() directly – it just uses this context.
==================================================== */

async function apiGetFirstInternal(paths: string[]) {
  let lastErr: any;
  for (const p of paths) {
    try {
      return await api(stripApiPrefix(p));
    } catch (e) {
      lastErr = e;
    }
  }
  throw lastErr || new Error("All fallback endpoints failed");
}

const API_BASE_URL = (import.meta as any)?.env?.VITE_API_DVI_BASE_URL ?? "";

export const hotelFormApi = {
  API_BASE_URL:RAW_API_BASE,
  // must be a function returning string to satisfy ApiCtx
    token: () => localStorage.getItem("token") ?? "",
  apiGet: (path: string) => api(stripApiPrefix(path)),
  apiPost: (path: string, body: any) =>
    api(stripApiPrefix(path), { method: "POST", body }),
  apiPatch: (path: string, body: any) =>
    api(stripApiPrefix(path), { method: "PATCH", body }),
  apiGetFirst: apiGetFirstInternal,
};
