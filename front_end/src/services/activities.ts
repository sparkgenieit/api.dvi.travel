// FILE: src/services/activities.ts
import { api } from "@/lib/api";

/** Build query string without external deps (arrays -> repeated keys) */
function toQuery(params?: Record<string, any>) {
  if (!params) return "";
  const parts: string[] = [];
  Object.entries(params).forEach(([k, v]) => {
    if (v === undefined || v === null || v === "") return;
    if (Array.isArray(v)) {
      v.forEach((item) =>
        parts.push(`${encodeURIComponent(k)}=${encodeURIComponent(String(item))}`)
      );
    } else {
      parts.push(`${encodeURIComponent(k)}=${encodeURIComponent(String(v))}`);
    }
  });
  return parts.length ? `?${parts.join("&")}` : "";
}
function withQuery(path: string, params?: Record<string, any>) {
  return `${path}${toQuery(params)}`;
}

/** Normalize api() and set headers/body correctly (handles FormData) */
async function request<T>(path: string, opts?: any): Promise<T> {
  const options: any = { ...(opts || {}) };

  // Safely handle body + headers
  if (options.body && !(options.body instanceof FormData)) {
    options.headers = { ...(options.headers || {}), "Content-Type": "application/json" };
    options.body = JSON.stringify(options.body);
  }
  // NOTE: if body is FormData -> let fetch set multipart boundary automatically

  const res = await api(path, options);
  if (res && typeof res === "object" && "data" in res) {
    return (res as any).data as T;
  }
  return res as T;
}

/* ================== Types ================== */

export type ActivityListRow = {
  counter: number;
  modify: number;
  activity_title: string;
  hotspot_name: string | null;
  hotspot_location: string | null;
  status: 0 | 1 | number;
  activity_id: number;
};

export type HotspotOption = { id: number; label: string };

export type ActivityDetails = {
  activity_id: number;
  activity_title: string | null;
  hotspot_id: number | null;
  max_allowed_person_count: number | null;
  activity_duration: string | null; // "HH:MM:SS"
  activity_description: string | null;
  status: number;
  deleted: number;
  hotspot?:
    | {
        hotspot_ID: number;
        hotspot_name: string | null;
        hotspot_location: string | null;
      }
    | null;
  images?: Array<{
    activity_image_gallery_details_id: number;
    activity_image_gallery_name: string;
  }>;
};

export type PreviewPayload = {
  basic: ActivityDetails;
  hotspot: ActivityDetails["hotspot"];
  images: NonNullable<ActivityDetails["images"]>;
  defaultSlots: Array<{ start_time: string; end_time: string } & any>;
  specialSlots: Array<{ special_date: string; start_time: string; end_time: string } & any>;
  reviews: Array<{
    activity_review_id: number;
    activity_rating: string;
    activity_description: string | null;
    createdon: string;
  }>;
};

/* ================== Mappers (fix UI vs Backend shape) ================== */

/** Map UI review shape -> backend shape */
function mapReviewBody(body: any) {
  if (!body) return body;
  // If the UI sends { rating, description }, convert:
  if ("rating" in body || "description" in body) {
    return {
      activity_rating: String(body.rating ?? ""),
      activity_description: body.description ?? null,
      createdby: body.createdby ?? 0,
    };
  }
  // Already backend shape
  return body;
}

/** Map UI pricebook shape -> backend shape */
function mapPricebookBody(activityId: number, body: any) {
  if (!body) return body;

  // If it already looks like backend DTO, pass through
  if (
    "hotspot_id" in body &&
    "start_date" in body &&
    "end_date" in body &&
    ("indian" in body || "nonindian" in body)
  ) {
    return body;
  }

  // Otherwise, assume UI ActivityForm fields and remap
  // Expecting: { hotspotId?, startDate, endDate, adult, children, infant, foreignAdult, foreignChildren, foreignInfant }
  const {
    hotspotId,
    startDate,
    endDate,
    adult,
    children,
    infant,
    foreignAdult,
    foreignChildren,
    foreignInfant,
    createdby,
  } = body;

  return {
    hotspot_id: hotspotId, // BigInt in DB, backend will coerce
    start_date: startDate, // "yyyy-mm-dd"
    end_date: endDate, // "yyyy-mm-dd"
    createdby: createdby ?? 0,
    indian: {
      adult_cost: adult ?? 0,
      child_cost: children ?? 0,
      infant_cost: infant ?? 0,
    },
    nonindian: {
      adult_cost: foreignAdult ?? 0,
      child_cost: foreignChildren ?? 0,
      infant_cost: foreignInfant ?? 0,
    },
  };
}

/* ================== API ================== */

export const ActivitiesAPI = {
  /** LIST — returns plain rows array */
  list: async (q?: string, status?: "0" | "1"): Promise<ActivityListRow[]> => {
    // Use querystring instead of unsupported `searchParams`
    return request<ActivityListRow[]>(withQuery("/activities", { q, status }), {
      method: "GET",
    });
  },

  /** HOTSPOT DROPDOWN */
  hotspots: async (q?: string) =>
    request<HotspotOption[]>(withQuery("/activities/hotspots", { q }), { method: "GET" }),

  /** CREATE */
  create: async (body: any) =>
    request<ActivityDetails>("/activities", { method: "POST", body }),

  /** UPDATE */
  update: async (id: number, body: any) =>
    request<ActivityDetails>(`/activities/${id}`, { method: "PUT", body }),

  /** STATUS TOGGLE */
  toggleStatus: async (id: number, status: 0 | 1) =>
    request<any>(`/activities/${id}/status`, { method: "PATCH", body: { status } }),

  /** DELETE ACTIVITY */
  delete: async (id: number) =>
    request<any>(`/activities/${id}`, { method: "DELETE" }),

  /** ============ IMAGES ============ */

  /**
   * Upload real files via Multer route and save names to DB.
   * Backend endpoint should be: POST /activities/:id/images/upload
   * Multer field: "files"[]
   */
  uploadImages: async (id: number, files: File[], createdby?: number) => {
  const fd = new FormData();
  // 🔧 MUST be "images" to match FilesInterceptor('images', ...)
  files.forEach((f) => fd.append("images", f));
  if (createdby != null) fd.append("createdby", String(createdby));

  return request<any>(`/activities/${id}/images/upload`, {
    method: "POST",
    body: fd, // don't set Content-Type
  });
}, 

  /** (legacy) Save by filenames only (when files are already on disk) */
  addImages: async (id: number, imageNames: string[], createdby?: number) =>
    request<any>(`/activities/${id}/images`, {
      
      method: "POST",
      body: { imageNames, createdby },
    }),

  deleteImage: async (id: number, imageId: number) =>
    request<any>(`/activities/${id}/images/${imageId}`, { method: "DELETE" }),

  /** ============ TIME SLOTS ============ */
  saveTimeSlots: async (id: number, body: any) =>
    request<any>(`/activities/${id}/time-slots`, { method: "POST", body }),

  /** ============ PRICEBOOK ============ */
  /**
   * Accepts either backend DTO shape or UI ActivityForm shape.
   * We normalize to backend DTO automatically.
   */
  savePriceBook: async (id: number, body: any) => {
    const mapped = mapPricebookBody(id, body);
    return request<any>(`/activities/${id}/pricebook`, {
      method: "POST",
      body: mapped,
    });
  },

  /** ============ REVIEWS ============ */
  /**
   * Accepts either { rating, description } or backend shape.
   */
  addReview: async (id: number, body: any) =>
    request<any>(`/activities/${id}/reviews`, {
      method: "POST",
      body: mapReviewBody(body),
    }),

  updateReview: async (id: number, reviewId: number, body: any) =>
    request<any>(`/activities/${id}/reviews/${reviewId}`, {
      method: "PUT",
      body: mapReviewBody(body),
    }),

  deleteReview: async (id: number, reviewId: number) =>
    request<any>(`/activities/${id}/reviews/${reviewId}`, { method: "DELETE" }),

  /** ============ DETAILS & PREVIEW ============ */
  details: async (id: number) =>
    request<ActivityDetails>(`/activities/${id}`, { method: "GET" }),

  preview: async (id: number) =>
    request<PreviewPayload>(`/activities/${id}/preview`, { method: "GET" }),
};
