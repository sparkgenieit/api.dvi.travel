// FILE: src/services/accountsLedgerApi.ts
import { API_BASE_URL } from "@/lib/api";

// Component type – matches backend DTO / PHP split
export type ComponentType =
  | "all"
  | "guide"
  | "hotspot"
  | "activity"
  | "hotel"
  | "vehicle"
  | "agent";

// Flattened row used by AccountsLedger UI
export type LedgerRow = {
  id: number;
  bookingId: string;
  componentType: ComponentType;
  agentName: string;
  branch?: string;
  vehicle?: string;
  vehicleVendor?: string;
  guideName?: string;
  hotspotName?: string;
  activityName?: string;
  hotelName?: string;
  totalBilled: number;
  totalReceived: number;
  totalReceivable: number;
  totalPaid: number;
  totalBalance: number;
  guest: string;
  arrival: string;
  startDate: string; // YYYY-MM-DD
  endDate: string; // YYYY-MM-DD
};

// Dynamic dropdown options type
export type LedgerFilterOptions = {
  agents: string[];
  vehicleBranches: string[];
  vehicles: string[];
  vendors: string[];
  guides: string[];
  hotspots: string[];
  activities: string[];
  hotels: string[];
};


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

// Raw header type (dvi_accounts_itinerary_details)
type HeaderRow = {
  accounts_itinerary_details_ID: number;
  itinerary_plan_ID: number;
  agent_id: number;
  staff_id: number;
  confirmed_itinerary_plan_ID: number;
  itinerary_quote_ID: string | null;
  trip_start_date_and_time: string | null;
  trip_end_date_and_time: string | null;
  total_billed_amount: number;
  total_received_amount: number;
  total_receivable_amount: number;
  total_payable_amount: number;
  total_payout_amount: number;
};

// Generic component row returned by backend for non-agent types
type ComponentBackendRow = {
  componentType?: ComponentType; // present for "all" API
  header: HeaderRow;
  details: any; // specific *_details table (hotel/vehicle/guide/...)
  transactions: any[]; // *_transaction_history rows
};

// Helper: format Date / DateTime → "YYYY-MM-DD"
function toYyyyMmDd(dt: string | Date | null | undefined): string {
  if (!dt) return "";

  // If backend sent a JS Date
  if (dt instanceof Date) {
    // toISOString: "2025-10-03T00:00:00.000Z"
    return dt.toISOString().slice(0, 10);
  }

  // If it’s a string (e.g. "2025-10-03T00:00:00.000Z" or "2025-10-03")
  if (typeof dt === "string") {
    if (dt.length >= 10) {
      return dt.slice(0, 10);
    }
    return dt; // already short, just return
  }

  return "";
}


// Flatten backend (PHP-style data) → UI LedgerRow[]
function mapBackendToLedgerRows(
  data: any[],
  requestedComponentType: ComponentType
): LedgerRow[] {
  const rows: LedgerRow[] = [];

  for (const raw of data) {
    // 1) AGENT LEDGER (componentType=agent in query)
    // Backend returns plain header rows (no "header"/"details" wrapper)
    if (requestedComponentType === "agent" && !("header" in raw)) {
      const h = raw as HeaderRow;

      rows.push({
        id: h.accounts_itinerary_details_ID,
        bookingId: h.itinerary_quote_ID ?? "",
        componentType: "agent",
        agentName: `Agent #${h.agent_id}`, // TODO: later join dvi_agent for real names
        branch: undefined,
        vehicle: undefined,
        vehicleVendor: undefined,
        guideName: undefined,
        hotspotName: undefined,
        activityName: undefined,
        hotelName: undefined,
        totalBilled: h.total_billed_amount ?? 0,
        totalReceived: h.total_received_amount ?? 0,
        totalReceivable: h.total_receivable_amount ?? 0,
        totalPaid: h.total_payout_amount ?? 0,
        totalBalance: h.total_receivable_amount ?? 0,
        guest: "",
        arrival: "",
        startDate: toYyyyMmDd(h.trip_start_date_and_time),
        endDate: toYyyyMmDd(h.trip_end_date_and_time),
      });

      continue;
    }

    // 2) OTHER COMPONENTS & "all" – backend returns { header, details, transactions }
    const row = raw as ComponentBackendRow;
    const h = row.header;
    const d = row.details || {};
    const effectiveType: ComponentType =
      (row.componentType as ComponentType) || requestedComponentType;

    // Base totals (header-level)
    let totalBilled = h.total_billed_amount ?? 0;
    let totalReceived = h.total_received_amount ?? 0;
    let totalReceivable = h.total_receivable_amount ?? 0;
    let totalPaid = 0;
    let totalBalance = 0;

    // Component-specific override using *_details totals
    if (
      effectiveType === "vehicle" ||
      effectiveType === "hotel" ||
      effectiveType === "guide" ||
      effectiveType === "activity" ||
      effectiveType === "hotspot"
    ) {
      totalBilled =
        typeof d.total_payable === "number"
          ? d.total_payable
          : typeof d.total_purchase === "number"
          ? d.total_purchase
          : 0;
      totalReceived = 0;
      totalReceivable = 0;
      totalPaid = typeof d.total_paid === "number" ? d.total_paid : 0;
      totalBalance =
        typeof d.total_balance === "number" ? d.total_balance : 0;
    } else if (effectiveType === "agent") {
      // agent row wrapped inside "all" result
      totalPaid = h.total_payout_amount ?? 0;
      totalBalance = h.total_receivable_amount ?? 0;
    }

    // Component-specific label fields – currently showing IDs.
    const agentName = `Agent #${h.agent_id}`;
    let branch: string | undefined;
    let vehicle: string | undefined;
    let vehicleVendor: string | undefined;
    let guideName: string | undefined;
    let hotspotName: string | undefined;
    let activityName: string | undefined;
    let hotelName: string | undefined;

    if (effectiveType === "vehicle") {
      vehicleVendor =
        d.vendor_id !== undefined ? `Vendor #${d.vendor_id}` : undefined;
      branch =
        d.vendor_branch_id !== undefined
          ? `Branch #${d.vendor_branch_id}`
          : undefined;
      vehicle =
        d.vehicle_id !== undefined ? `Vehicle #${d.vehicle_id}` : undefined;
    } else if (effectiveType === "guide") {
      guideName =
        d.guide_id !== undefined ? `Guide #${d.guide_id}` : undefined;
    } else if (effectiveType === "hotel") {
      hotelName =
        d.hotel_id !== undefined ? `Hotel #${d.hotel_id}` : undefined;
    } else if (effectiveType === "hotspot") {
      hotspotName =
        d.hotspot_ID !== undefined ? `Hotspot #${d.hotspot_ID}` : undefined;
    } else if (effectiveType === "activity") {
      activityName =
        d.activity_ID !== undefined ? `Activity #${d.activity_ID}` : undefined;
    }

    rows.push({
      id: rows.length + 1,
      bookingId: h.itinerary_quote_ID ?? "",
      componentType: effectiveType,
      agentName,
      branch,
      vehicle,
      vehicleVendor,
      guideName,
      hotspotName,
      activityName,
      hotelName,
      totalBilled,
      totalReceived,
      totalReceivable,
      totalPaid,
      totalBalance,
      guest: "",
      arrival: "",
      startDate: toYyyyMmDd(h.trip_start_date_and_time),
      endDate: toYyyyMmDd(h.trip_end_date_and_time),
    });
  }

  return rows;
}

// Main ledger rows
export async function fetchLedgerFromApi(params: {
  quoteId: string;
  componentType: ComponentType;
  fromDate: string; // DD/MM/YYYY
  toDate: string; // DD/MM/YYYY
  guideName: string;
  hotspotName: string;
  activityName: string;
  hotelName: string;
  branch: string;
  vehicle: string;
  vehicleVendor: string;
  agentName: string;
}): Promise<LedgerRow[]> {
  const search = new URLSearchParams();

  search.set("componentType", params.componentType);
  if (params.quoteId.trim()) {
    search.set("quoteId", params.quoteId.trim());
  }
  if (params.fromDate.trim()) {
    search.set("fromDate", params.fromDate.trim());
  }
  if (params.toDate.trim()) {
    search.set("toDate", params.toDate.trim());
  }

  // include global prefix /api/v1 from main.ts
  const url = `${API_BASE_URL}/accounts-ledger?${search.toString()}`;

  const res = await fetch(url, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      ...getAuthHeaders(),
    },
  });

  if (!res.ok) {
    console.error("Failed to fetch ledger", res.status, await res.text());
    throw new Error("Failed to fetch ledger");
  }

  const data = (await res.json()) as any[];
  return mapBackendToLedgerRows(data, params.componentType);
}

// Dynamic dropdown options
export async function fetchLedgerFilterOptions(params: {
  quoteId: string;
  componentType: ComponentType;
  fromDate: string; // DD/MM/YYYY
  toDate: string; // DD/MM/YYYY
}): Promise<LedgerFilterOptions> {
  const search = new URLSearchParams();

  search.set("componentType", params.componentType);
  if (params.quoteId.trim()) {
    search.set("quoteId", params.quoteId.trim());
  }
  if (params.fromDate.trim()) {
    search.set("fromDate", params.fromDate.trim());
  }
  if (params.toDate.trim()) {
    search.set("toDate", params.toDate.trim());
  }

  // include global prefix /api/v1 from main.ts
  const url = `${API_BASE_URL}/accounts-ledger/options?${search.toString()}`;

  const res = await fetch(url, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      ...getAuthHeaders(),
    },
  });

  if (!res.ok) {
    console.error(
      "Failed to fetch ledger filter options",
      res.status,
      await res.text()
    );
    // safe fallback
    return {
      agents: ["All"],
      vehicleBranches: ["All"],
      vehicles: ["All"],
      vendors: ["All"],
      guides: ["All"],
      hotspots: ["All"],
      activities: ["All"],
      hotels: ["All"],
    };
  }

  const raw = (await res.json()) as Partial<LedgerFilterOptions>;

  return {
    agents: ["All", ...(raw.agents ?? [])],
    vehicleBranches: ["All", ...(raw.vehicleBranches ?? [])],
    vehicles: ["All", ...(raw.vehicles ?? [])],
    vendors: ["All", ...(raw.vendors ?? [])],
    guides: ["All", ...(raw.guides ?? [])],
    hotspots: ["All", ...(raw.hotspots ?? [])],
    activities: ["All", ...(raw.activities ?? [])],
    hotels: ["All", ...(raw.hotels ?? [])],
  };
}
