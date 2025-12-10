import { api } from "@/lib/api";

const ACCOUNTS_BASE_PATH = "/accounts-manager";
const AGENTS_BASE_PATH = "/agents";

export type AccountsStatus = "all" | "paid" | "due";
export type AccountsComponentType =
  | "all"
  | "hotel"
  | "vehicle"
  | "guide"
  | "hotspot"
  | "activity";

export interface AccountsFilters {
  quoteId?: string;
  agent?: string; // backend expects agent name substring
  status?: AccountsStatus;
  componentType?: AccountsComponentType;
  fromDate?: string; // "DD/MM/YYYY"
  toDate?: string; // "DD/MM/YYYY"
  search?: string;
}

export interface AccountsRow {
  id: number;
  headerId: number;
  quoteId: string;
  hotelName: string;
  amount: number;
  payout: number;
  payable: number;
  status: "paid" | "due";
  componentType: AccountsComponentType;
  agent: string;
  startDate: string; // "DD/MM/YYYY"
  endDate: string; // "DD/MM/YYYY"
  routeDate?: string;
  vehicleId?: number;
  vendorId?: number;
}

export interface AccountsSummary {
  totalPayable: number;
  totalPaid: number;
  totalBalance: number;
  rowCount: number;
}

export interface QuoteOption {
  quoteId: string;
}

export interface AgentOption {
  id: number;
  name: string;
}

export interface PaymentModeOption {
  id: number;
  label: string;
}

export interface PayPayload {
  componentType: Exclude<AccountsComponentType, "all">;
  accountsItineraryDetailsId: number;
  componentDetailId: number;
  routeDate?: string;
  amount: number;
  modeOfPaymentId?: number;
  utrNumber?: string;
  processedBy?: string;
}

// Helpers
function buildQuery(params: Record<string, any>): string {
  const searchParams = new URLSearchParams();
  Object.entries(params).forEach(([key, value]) => {
    if (
      value !== undefined &&
      value !== null &&
      value !== "" &&
      !(Array.isArray(value) && value.length === 0)
    ) {
      searchParams.append(key, String(value));
    }
  });
  const qs = searchParams.toString();
  return qs ? `?${qs}` : "";
}

// 1) Main list
export async function fetchAccountsList(
  filters: AccountsFilters,
): Promise<AccountsRow[]> {
  const qs = buildQuery(filters);
  return api(`${ACCOUNTS_BASE_PATH}${qs}`, {
    method: "GET",
    auth: true,
  });
}

// 2) Summary
export async function fetchAccountsSummary(
  filters: AccountsFilters,
): Promise<AccountsSummary> {
  const qs = buildQuery(filters);
  return api(`${ACCOUNTS_BASE_PATH}/summary${qs}`, {
    method: "GET",
    auth: true,
  });
}

// 3) Quote autocomplete
export async function searchQuotes(
  phrase: string,
): Promise<QuoteOption[]> {
  const qs = phrase ? `?q=${encodeURIComponent(phrase)}` : "";
  return api(`${ACCOUNTS_BASE_PATH}/quotes${qs}`, {
    method: "GET",
    auth: true,
  });
}

// 4) Agent dropdown (now uses /agents module)
export async function fetchAgents(
  travelExpertId?: number,
): Promise<AgentOption[]> {
  const qs = travelExpertId
    ? `?travelExpertId=${encodeURIComponent(String(travelExpertId))}`
    : "";
  return api(`${AGENTS_BASE_PATH}${qs}`, {
    method: "GET",
    auth: true,
  });
}

// 5) Payment modes
export async function fetchPaymentModes(): Promise<PaymentModeOption[]> {
  return api(`${ACCOUNTS_BASE_PATH}/payment-modes`, {
    method: "GET",
    auth: true,
  });
}

// 6) Pay Now
export async function postPayment(payload: PayPayload): Promise<void> {
  await api(`${ACCOUNTS_BASE_PATH}/pay`, {
    method: "POST",
    auth: true,
    body: payload,
  });
}
