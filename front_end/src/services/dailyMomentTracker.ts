// FILE: src/services/dailyMomentTracker.ts

export type TripType = "Arrival" | "Departure" | "Ongoing";

// Raw backend DTO (DailyMomentRowDto from NestJS)
export type DailyMomentApiRow = {
  count: number;

  // Guest details
  guest_name: string;
  guest_mobile: string | null; // NEW
  guest_email: string | null; // NEW

  quote_id: string | null;
  itinerary_plan_ID: number;
  itinerary_route_ID: number;
  route_date: string; // "dd-mm-yyyy"
  trip_type: TripType;
  location_name: string | null;
  next_visiting_location: string | null;
  arrival_flight_details: string | null;
  departure_flight_details: string | null;
  hotel_name: string | null;
  vehicle_type_title: string | null;
  vendor_name: string | null;
  meal_plan: string | null;
  vehicle_no: string | null;
  driver_name: string | null;
  driver_mobile: string | null;
  special_remarks: string | null;

  // Travel expert details
  travel_expert_name: string | null;
  travel_expert_mobile: string | null; // NEW
  travel_expert_email: string | null; // NEW

  agent_name: string | null;
};

// ---------------------------------------------------------------------------
// Mapped row for React UI (DailyMoment list / header for Day View)
// ---------------------------------------------------------------------------

export type DailyMomentListRow = {
  itineraryPlanId?: number;
  itineraryRouteId?: number;

  // Guest
  guestName: string;
  guestMobile?: string | null;
  guestEmail?: string | null;

  // Travel expert
  travelExpert: string;
  travelExpertMobile?: string | null;
  travelExpertEmail?: string | null;

  quoteId: string;
  routeDate: Date;
  type: TripType | string;

  fromLocation: string;
  toLocation: string;

  hotel: string;
  vendor: string;
  vehicle: string;
  vehicleNo: string;

  driverName: string;
  driverMobile: string;

  agent: string;
};

// Safely parse "dd-mm-yyyy" (PHP style) into Date, with fallbacks
function parseRouteDate(routeDate: string | null | undefined): Date {
  if (!routeDate) return new Date();

  const parts = routeDate.split("-");
  if (parts.length === 3) {
    const [ddStr, mmStr, yyyyStr] = parts;
    const dd = Number(ddStr);
    const mm = Number(mmStr);
    const yyyy = Number(yyyyStr);

    if (
      Number.isFinite(dd) &&
      Number.isFinite(mm) &&
      Number.isFinite(yyyy) &&
      dd > 0 &&
      dd <= 31 &&
      mm > 0 &&
      mm <= 12
    ) {
      const d = new Date(yyyy, mm - 1, dd);
      if (!Number.isNaN(d.getTime())) return d;
    }
  }

  // Fallback: let JS try to parse whatever came
  const fallback = new Date(routeDate);
  if (!Number.isNaN(fallback.getTime())) return fallback;

  return new Date();
}

// Map a raw API row into a UI-friendly row (used by list + DayView header)
export function mapDailyMomentApiRowToListRow(
  apiRow: DailyMomentApiRow
): DailyMomentListRow {
  return {
    itineraryPlanId: apiRow.itinerary_plan_ID,
    itineraryRouteId: apiRow.itinerary_route_ID,

    guestName: apiRow.guest_name ?? "",
    guestMobile: apiRow.guest_mobile ?? null,
    guestEmail: apiRow.guest_email ?? null,

    travelExpert: apiRow.travel_expert_name ?? "",
    travelExpertMobile: apiRow.travel_expert_mobile ?? null,
    travelExpertEmail: apiRow.travel_expert_email ?? null,

    quoteId: apiRow.quote_id ?? "",
    routeDate: parseRouteDate(apiRow.route_date),
    type: apiRow.trip_type ?? "Ongoing",

    fromLocation: apiRow.location_name ?? "",
    toLocation: apiRow.next_visiting_location ?? "",

    hotel: apiRow.hotel_name ?? "",
    vendor: apiRow.vendor_name ?? "",
    vehicle: apiRow.vehicle_type_title ?? "",
    vehicleNo: apiRow.vehicle_no ?? "",

    driverName: apiRow.driver_name ?? "",
    driverMobile: apiRow.driver_mobile ?? "",

    agent: apiRow.agent_name ?? "",
  };
}

// Convenience helper if you want to map an entire list at once
export function mapDailyMomentApiRowsToListRows(
  apiRows: DailyMomentApiRow[]
): DailyMomentListRow[] {
  return apiRows.map(mapDailyMomentApiRowToListRow);
}

// Optional convenience: fetch + map in one call (non-breaking addition)
export async function fetchDailyMomentList(params: {
  fromDate: string; // DD-MM-YYYY
  toDate: string; // DD-MM-YYYY
  itineraryPlanId?: number;
  agentId?: number;
}): Promise<DailyMomentListRow[]> {
  const raw = await fetchDailyMoments(params);
  return mapDailyMomentApiRowsToListRows(raw);
}

// Charges DTO (extra charges form via car icon)
export type DailyMomentCharge = {
  driver_charge_ID: number;
  itinerary_plan_ID: number;
  itinerary_route_ID: number;
  charge_type: string;
  charge_amount: number;
};

// Vite-style base URL (same pattern as accountsLedgerApi)
const API_BASE_URL = (import.meta as any).env?.VITE_API_DVI_BASE_URL

// 🔐 Helper: attach JWT from localStorage (same idea as other secured APIs)
function getAuthHeaders(): Record<string, string> {
  if (typeof window === "undefined") return {};
  const token =
    window.localStorage.getItem("token") ||
    window.localStorage.getItem("accessToken") ||
    window.localStorage.getItem("access_token") ||
    window.localStorage.getItem("jwt");

  return token ? { Authorization: `Bearer ${token}` } : {};
}

/**
 * Fetch list of Daily Moments between fromDate and toDate.
 * fromDate / toDate are expected in DD-MM-YYYY format (same as PHP UI).
 * (Existing behaviour preserved – still returns raw DailyMomentApiRow[])
 */
export async function fetchDailyMoments(params: {
  fromDate: string; // DD-MM-YYYY
  toDate: string; // DD-MM-YYYY
  itineraryPlanId?: number;
  agentId?: number;
}): Promise<DailyMomentApiRow[]> {
  const search = new URLSearchParams();

  if (params.fromDate.trim()) {
    search.set("fromDate", params.fromDate.trim());
  }
  if (params.toDate.trim()) {
    search.set("toDate", params.toDate.trim());
  }
  if (params.itineraryPlanId) {
    search.set("itineraryPlanId", String(params.itineraryPlanId));
  }
  if (params.agentId) {
    search.set("agentId", String(params.agentId));
  }

  // include global prefix /api/v1 from main.ts
  const url = `${API_BASE_URL}/api/v1/daily-moment-tracker?${search.toString()}`;

  const res = await fetch(url, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      ...getAuthHeaders(),
    },
  });

  if (!res.ok) {
    console.error(
      "Failed to fetch daily moments",
      res.status,
      await safeReadText(res)
    );
    throw new Error("Failed to fetch daily moments");
  }

  const data = (await res.json()) as DailyMomentApiRow[];
  return data;
}

/**
 * Fetch extra charges for a given itinerary plan + route
 * (used by car icon popup).
 */
export async function fetchDailyMomentCharges(
  itineraryPlanId: number,
  itineraryRouteId: number
): Promise<DailyMomentCharge[]> {
  const search = new URLSearchParams();
  search.set("itineraryPlanId", String(itineraryPlanId));
  search.set("itineraryRouteId", String(itineraryRouteId));

  const url = `${API_BASE_URL}/api/v1/daily-moment-tracker/charges?${search.toString()}`;

  const res = await fetch(url, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      ...getAuthHeaders(),
    },
  });

  if (!res.ok) {
    console.error(
      "Failed to fetch daily moment charges",
      res.status,
      await safeReadText(res)
    );
    throw new Error("Failed to fetch daily moment charges");
  }

  const data = (await res.json()) as DailyMomentCharge[];
  return data;
}

/**
 * Create / update an extra charge row for Daily Moment.
 * Matches UpsertDailyMomentChargeDto on the backend.
 */
export async function upsertDailyMomentCharge(payload: {
  driverChargeId?: number;
  itineraryPlanId: number;
  itineraryRouteId: number;
  chargeType: string;
  chargeAmount: number;
}): Promise<DailyMomentCharge> {
  const body = {
    driverChargeId: payload.driverChargeId ?? null,
    itineraryPlanId: payload.itineraryPlanId,
    itineraryRouteId: payload.itineraryRouteId,
    chargeType: payload.chargeType,
    chargeAmount: payload.chargeAmount,
  };

  const url = `${API_BASE_URL}/api/v1/daily-moment-tracker/charges`;

  const res = await fetch(url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      ...getAuthHeaders(),
    },
    body: JSON.stringify(body),
  });

  if (!res.ok) {
    console.error(
      "Failed to save daily moment charge",
      res.status,
      await safeReadText(res)
    );
    throw new Error("Failed to save daily moment charge");
  }

  const data = (await res.json()) as DailyMomentCharge;
  return data;
}

// Small helper to safely read text for logging
async function safeReadText(res: Response): Promise<string> {
  try {
    return await res.text();
  } catch {
    return "";
  }
}
