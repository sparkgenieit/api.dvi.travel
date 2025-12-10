// FILE: src/services/hotspotService.ts

import { api, API_BASE_URL } from "@/lib/api";

function computeFileBase(apiBase: string): string {
  if (!apiBase) return "";
  let s = apiBase.replace(/\/+$/i, "");
  s = s.replace(/\/api(?:\/v\d+)?$/i, "");
  return s;
}

/** Public file base used to prefix `/uploads/...` URLs returned by backend */
const FILE_BASE = computeFileBase(API_BASE_URL);

/* ---------- List & Form shapes ---------- */
type ListRow = {
  modify: number | string;
  hotspot_photo_url: string; // <img ...> HTML
  hotspot_name: string | null;
  hotspot_priority: number | string | null;
  hotspot_locations: string | null; // "a<br>b"
  local_members: string;   // HTML
  foreign_members: string; // HTML
};

type FormGetResponse = {
  payload: {
    id: number;
    hotspot_name: string;
    hotspot_type: string | null;
    hotspot_priority: number | null;
    hotspot_description: string | null;
    hotspot_landmark: string | null;
    hotspot_address: string | null;
    hotspot_adult_entry_cost?: number | null;
    hotspot_child_entry_cost?: number | null;
    hotspot_infant_entry_cost?: number | null;
    hotspot_foreign_adult_entry_cost?: number | null;
    hotspot_foreign_child_entry_cost?: number | null;
    hotspot_foreign_infant_entry_cost?: number | null;
    hotspot_rating?: number | null;
    hotspot_video_url?: string | null;
    hotspot_latitude?: string | null;
    hotspot_longitude?: string | null;
    hotspot_location_list?: string[];
    gallery: Array<{ id?: number | string; name: string; delete?: boolean }>;
    parkingCharges: Array<{ id?: number | string; vehicleTypeId: number; charge: number }>;
    operatingHours?: Record<
      string,
      { open24hrs?: boolean; closed24hrs?: boolean; slots: Array<{ id?: number | string; start: string; end: string }> }
    >;
  };
  options: {
    hotspotTypes: string[];
    locations: string[];
    vehicleTypes: Array<{ id: number; name: string }>;
  };
};

export type HotspotListItem = {
  id: string;
  imageUrl: string;
  name: string;
  priority: number;
  places: string[];
  localHtml: string;
  foreignHtml: string;
};

export type HotspotFormData = {
  id?: number;
  name: string;
  type: string | null;
  priority: number;
  description: string;
  landmark: string;
  address: string;
  adultCost: number;
  childCost: number;
  infantCost: number;
  foreignAdultCost: number;
  foreignChildCost: number;
  foreignInfantCost: number;
  rating: number;
  duration?: string;
  latitude: string;
  longitude: string;
  videoUrl: string;
  locations: string[];
  /** Simple mode: array of URLs or filenames (we’ll extract the filename) */
  galleryImages: string[];
  /** Advanced mode (edit): send objects to preserve IDs and deletes */
  gallery?: Array<{ id?: number | string; name: string; delete?: boolean }>;
  /** key = vehicleTypeId (string/numbery), value = charge */
  parkingCharges: Record<string, number>;
  /** NOTE: include closed24Hours for correct persistence */
  openingHours: Record<string, {
    is24Hours?: boolean;
    closed24Hours?: boolean;
    timeSlots: Array<{ start: string; end: string }>
  }>;
};

/* ---------- NEW: Parking CSV import shapes ---------- */
export type ParkingUploadResponse = {
  sessionId: string;
  stagedCount: number;
};
export type ParkingTempRow = {
  id: number;
  hotspot_name: string;
  hotspot_location: string;
  vehicle_type_title: string;
  parking_charge: number;
};
export type ParkingTempListResponse = {
  sessionId: string;
  rows: ParkingTempRow[];
};
export type ParkingConfirmResponse = {
  sessionId: string;
  total: number;
  imported: number;
  failed: number;
};

/* ---------- helpers ---------- */
function imgFromHtml(html: string): string {
  const m = /<img[^>]*src=["']([^"']+)["']/i.exec(html || "");
  return m?.[1] ?? "";
}
function brToArray(s: string | null): string[] {
  return (s || "")
    .split(/<br\s*\/?>/i)
    .map((x) => x.trim())
    .filter(Boolean);
}
function fileNameFromUrl(u: string): string {
  const p = (u || "").split("?")[0]; // strip query if any
  const seg = p.split("/");
  return seg[seg.length - 1] || "";
}
function buildGalleryPayload(form: Partial<HotspotFormData>) {
  // Prefer advanced mode if provided (preserves ids/deletes on edit)
  if (Array.isArray(form.gallery) && form.gallery.length) {
    return form.gallery.map((g) => ({
      ...(g.id != null ? { id: g.id } : {}),
      name: g.name,
      ...(g.delete ? { delete: true } : {}),
    }));
  }
  // Fallback to simple mode: turn image URLs into { name }
  return (form.galleryImages ?? []).map((u) => ({ name: fileNameFromUrl(u) }));
}

/** Convert "hh:mm AM/PM" -> "HH:mm"; if already 24-hr "HH:mm", leave as-is */
function toHHmm24(t: string | null | undefined): string | null {
  const s = (t || "").trim();
  if (!s) return null;
  // Already 24-hr?
  if (/^\d{1,2}:\d{2}$/.test(s) && !/am|pm/i.test(s)) {
    // normalize hour to 2 digits
    const [h, m] = s.split(":");
    const hh = String(Math.max(0, Math.min(23, Number(h)))).padStart(2, "0");
    const mm = String(Math.max(0, Math.min(59, Number(m)))).padStart(2, "0");
    return `${hh}:${mm}`;
  }
  const m = /^\s*(\d{1,2}):(\d{2})\s*(AM|PM)\s*$/i.exec(s);
  if (!m) return null;
  let hh = Number(m[1]) % 12;
  if (m[3].toUpperCase() === "PM") hh += 12;
  const mm = Number(m[2]);
  return `${String(hh).padStart(2, "0")}:${String(mm).padStart(2, "0")}`;
}

/* ---------- service ---------- */
export const hotspotService = {
  async listHotspots(): Promise<HotspotListItem[]> {
    const json = await api("/hotspots"); // GET
    const rows: ListRow[] = json.data ?? [];
    return rows.map((r) => ({
      id: String(r.modify),
      imageUrl: imgFromHtml(r.hotspot_photo_url),
      name: r.hotspot_name ?? "",
      priority: Number(r.hotspot_priority ?? 0),
      places: brToArray(r.hotspot_locations),
      localHtml: r.local_members,
      foreignHtml: r.foreign_members,
    }));
  },

  async getHotspotForm(hotspotId: string): Promise<FormGetResponse> {
    return api(`/hotspots/${hotspotId}/form`);
  },

  async getFormOptions() {
    return api("/hotspots/form-options");
  },

  // ===================== UPDATED: saveHotspot (fixes for timings/parking/gallery) =====================
  async saveHotspot(form: Partial<HotspotFormData>): Promise<{ id: number }> {
    // helpers
    const DAY_ZERO_INDEX: Record<string, number> = {
      monday: 0, tuesday: 1, wednesday: 2, thursday: 3, friday: 4, saturday: 5, sunday: 6,
    };

    // Build gallery payload (names; upload endpoint inserts rows)
    const gallery = buildGalleryPayload(form);

    // Parking charges (keep zeros; filter only invalid ids/NaN)
    const parkingCharges = Object.entries(form.parkingCharges ?? {})
      .map(([k, charge]) => ({ vehicleTypeId: Number(k), charge: Number(charge) }))
      .filter(
        (x) => Number.isFinite(x.vehicleTypeId) && x.vehicleTypeId > 0 && Number.isFinite(x.charge) && x.charge >= 0
      );

    // sanitize coords
    const lat = (form.latitude ?? "").toString().trim() || null;
    const lng = (form.longitude ?? "").toString().trim() || null;

    // ---- Convert openingHours (UI) -> operatingHours (backend expects "HH:mm") ----
    const operatingHours = Object.fromEntries(
      Object.entries(form.openingHours ?? {}).map(([day, v]) => [
        day,
        {
          open24hrs: !!v?.is24Hours,
          closed24hrs: !!v?.closed24Hours,
          slots: (v?.timeSlots || [])
            .map((s) => {
              const start24 = toHHmm24(s.start);
              const end24 = toHHmm24(s.end);
              if (!start24 || !end24) return null;
              return { start: start24, end: end24 };
            })
            .filter(Boolean) as Array<{ start: string; end: string }>,
        },
      ])
    );

    // ---- Optional: also send flattened zero-based `timings` (backend ignores if not used) ----
    type TimingRow = {
      day: number;               // 0..6 (0=Mon)
      open24hrs: boolean;
      closed24hrs: boolean;
      start_time: string | null; // "HH:mm:ss" or null
      end_time: string | null;   // "HH:mm:ss" or null
    };
    const timings: TimingRow[] = [];
    for (const [dayKey, def] of Object.entries(form.openingHours ?? {})) {
      const d = (def || {}) as HotspotFormData["openingHours"][string];
      const day = DAY_ZERO_INDEX[dayKey] ?? 0;
      const open24 = !!d.is24Hours;
      const closed24 = !!d.closed24Hours;
      if (open24 || closed24) {
        timings.push({ day, open24hrs: open24, closed24hrs: closed24, start_time: null, end_time: null });
      } else {
        const slots = (d.timeSlots ?? []).length ? d.timeSlots : [{ start: "", end: "" }];
        for (const s of slots) {
          const s24 = toHHmm24(s.start);
          const e24 = toHHmm24(s.end);
          timings.push({
            day,
            open24hrs: false,
            closed24hrs: false,
            start_time: s24 ? `${s24}:00` : null,
            end_time: e24 ? `${e24}:00` : null,
          });
        }
      }
    }

    const body = {
      id: form.id,
      hotspot_name: form.name,
      hotspot_type: form.type ?? null,
      hotspot_priority: form.priority ?? 0,
      hotspot_description: form.description ?? null,
      hotspot_landmark: form.landmark ?? null,
      hotspot_address: form.address ?? null,
      hotspot_adult_entry_cost: form.adultCost ?? null,
      hotspot_child_entry_cost: form.childCost ?? null,
      hotspot_infant_entry_cost: form.infantCost ?? null,
      hotspot_foreign_adult_entry_cost: form.foreignAdultCost ?? null,
      hotspot_foreign_child_entry_cost: form.foreignChildCost ?? null,
      hotspot_foreign_infant_entry_cost: form.foreignInfantCost ?? null,
      hotspot_rating: form.rating ?? null,
      hotspot_video_url: form.videoUrl ?? null,
      hotspot_latitude: lat,
      hotspot_longitude: lng,
      hotspot_location_list: form.locations ?? [],

      // child tables
      operatingHours,     // -> dvi_hotspot_timing (backend reads this; now "HH:mm")
      timings,            // optional helper array (0..6 day index)
      parkingCharges,     // -> dvi_hotspot_vehicle_parking_charges
      gallery,            // -> dvi_hotspot_gallery_details
    };

    return api("/hotspots/form", { method: "POST", body });
  },
  // ==============================================================================

  async deleteHotspot(id: string): Promise<void> {
    await api(`/hotspots/${id}`, { method: "DELETE" });
  },

  async updatePriority(id: string, priority: number) {
    await api(`/hotspots/${id}/priority`, { method: "PATCH", body: { priority } });
  },

  async uploadGallery(hotspotId: string | number, file: File) {
    const fd = new FormData();
    fd.append("file", file);
    const r = await api(`/hotspots/${hotspotId}/gallery/upload`, {
      method: "POST",
      body: fd,
    });
    // Normalize returned URL to absolute for the UI preview
    return {
      ...r,
      url: r?.url?.startsWith("http") ? r.url : `${FILE_BASE}${r.url || ""}`,
    } as { ok: true; id: number | string; name: string; url: string };
  },

  /* ---------- NEW: Parking Charges Bulk Import (CSV) ---------- */
  async uploadParkingCsv(file: File): Promise<ParkingUploadResponse> {
    const fd = new FormData();
    fd.append("file", file);
    return api("/hotspots/parking-charge/upload", { method: "POST", body: fd });
  },

  async getParkingTempList(sessionId: string): Promise<ParkingTempListResponse> {
    return api(
      `/hotspots/parking-charge/templist?sessionId=${encodeURIComponent(sessionId)}`
    );
  },

  async confirmParkingImport(
    sessionId: string,
    tempIds: number[]
  ): Promise<ParkingConfirmResponse> {
    return api("/hotspots/parking-charge/confirm", {
      method: "POST",
      body: { sessionId, tempIds },
    });
  },

  fileBase(): string {
    return FILE_BASE;
  },
};
