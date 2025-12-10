// FILE: src/services/vehicle-availability.ts

import { api } from "@/lib/api";

export type SimpleOption = { id: number; label: string };

export type VehicleAvailabilityRouteSegment = {
  locationName: string;
  nextVisitingLocation: string;
};

export type VehicleAvailabilityCell = {
  date: string; // YYYY-MM-DD
  itineraryPlanId: number | null;
  itineraryQuoteId: string | null;

  isWithinTrip: boolean;
  isStart: boolean;
  isEnd: boolean;
  isInBetween: boolean;
  isToday: boolean;

  isVehicleAssigned: boolean;
  assignedVehicleId: number | null;

  hasDriver: boolean;
  driverId: number | null;

  routeSegments: VehicleAvailabilityRouteSegment[];
};

export type VehicleAvailabilityRow = {
  vendorId: number;
  vendorName: string;

  // NOTE: this is vendor_vehicle_type_ID on backend
  vehicleTypeId: number;
  vehicleTypeTitle: string;

  vehicleId: number;
  registrationNumber: string;

  cells: VehicleAvailabilityCell[];
};

export type VehicleAvailabilityResponse = {
  dates: string[];
  rows: VehicleAvailabilityRow[];
};

export type VehicleAvailabilityQuery = {
  dateFrom?: string; // YYYY-MM-DD
  dateTo?: string; // YYYY-MM-DD
  vendorId?: number;
  vehicleTypeId?: number;

  // UI filters (backend must support if you want server-side filtering)
  agentId?: number;
  locationId?: number;
};

function buildQueryString(params: Record<string, string | number | undefined | null>) {
  const q = new URLSearchParams();
  for (const [k, v] of Object.entries(params)) {
    if (v === undefined || v === null || v === "") continue;
    q.set(k, String(v));
  }
  const s = q.toString();
  return s ? `?${s}` : "";
}

// ==============================
// CHART
// ==============================
export async function fetchVehicleAvailability(
  query: VehicleAvailabilityQuery,
): Promise<VehicleAvailabilityResponse> {
  return api(`/vehicle-availability${buildQueryString(query)}`, { auth: true });
}

// ==============================
// FILTER / DROPDOWN DATA
// ==============================
export async function fetchVendors(): Promise<SimpleOption[]> {
  return api(`/vehicle-availability/vendors`, { auth: true });
}

export async function fetchVehicleTypes(): Promise<SimpleOption[]> {
  return api(`/vehicle-availability/vehicle-types`, { auth: true });
}

export async function fetchAgents(): Promise<SimpleOption[]> {
  return api(`/vehicle-availability/agents`, { auth: true });
}

export async function fetchLocations(q?: string): Promise<SimpleOption[]> {
  return api(`/vehicle-availability/locations${buildQueryString({ q })}`, { auth: true });
}

export async function fetchVendorBranches(vendorId: number): Promise<SimpleOption[]> {
  return api(
    `/vehicle-availability/vendor-branches${buildQueryString({ vendorId })}`,
    { auth: true },
  );
}

// Vendor-specific vehicle types (Selectize equivalent)
export async function fetchVendorVehicleTypes(vendorId: number): Promise<SimpleOption[]> {
  return api(
    `/vehicle-availability/vendor-vehicle-types${buildQueryString({ vendorId })}`,
    { auth: true },
  );
}

// Vehicles for Assign modal (by vendor + vendor_vehicle_type_ID)
export async function fetchVehiclesForAssign(
  vendorId: number,
  vendorVehicleTypeId: number
): Promise<SimpleOption[]> {
  return api(
    `/vehicle-availability/vehicles-for-assign${buildQueryString({ vendorId, vendorVehicleTypeId })}`,
    { auth: true },
  );
}

// Drivers for Assign modal (by vendor [+ vendor_vehicle_type_ID])
export async function fetchDriversForAssign(
  vendorId: number,
  vendorVehicleTypeId?: number
): Promise<SimpleOption[]> {
  return api(
    `/vehicle-availability/drivers-for-assign${buildQueryString({ vendorId, vendorVehicleTypeId })}`,
    { auth: true },
  );
}

// ==============================
// LOCATION META (EasyAutocomplete → getSTATE_CITY_COUNTRY)
// ==============================
export type LocationMeta = {
  label: string;
  location_id: number | null;
  city_id: number | null;
  state_id: number | null;
  country_id: number | null;
};

export async function fetchLocationMeta(label: string): Promise<LocationMeta> {
  return api(
    `/vehicle-availability/location-meta${buildQueryString({ label })}`,
    { auth: true },
  );
}

// ==============================
// CREATE (Add Vehicle / Add Driver)
// ==============================
export type CreateVehiclePayload = {
  vendorId: number;
  vehicleTypeId: number;            // == vendor_vehicle_type_ID
  registrationNumber: string;

  vendor_branch_id?: number;
  vehicle_origin?: string;          // free-text label (optional if you pass vehicle_location_id)
  vehicle_location_id?: number;     // resolved FK (preferred if available)

  vehicle_fc_expiry_date?: string;  // YYYY-MM-DD
  insurance_start_date?: string;    // YYYY-MM-DD
  insurance_end_date?: string;      // YYYY-MM-DD
};

/**
 * Backend accepts both camelCase and snake_case (service normalizes).
 */
export async function createVehicle(payload: CreateVehiclePayload) {
  const body: Record<string, any> = {
    // snake_case expected by PHP parity service
    vendor_id: payload.vendorId,
    vehicle_type_id: payload.vehicleTypeId,
    registration_number: payload.registrationNumber,
    vendor_branch_id: payload.vendor_branch_id,
    vehicle_orign: payload.vehicle_origin, // spelling matches legacy PHP
    vehicle_location_id: payload.vehicle_location_id,
    vehicle_fc_expiry_date: payload.vehicle_fc_expiry_date,
    insurance_start_date: payload.insurance_start_date,
    insurance_end_date: payload.insurance_end_date,
    // camelCase duplicates (service strips/normalizes safely)
    vendorId: payload.vendorId,
    vehicleTypeId: payload.vehicleTypeId,
    registrationNumber: payload.registrationNumber,
  };

  return api(`/vehicle-availability/vehicles`, {
    method: "POST",
    body,
    auth: true,
  });
}

/**
 * Helper: resolve `vehicle_location_id` from a free-text label using the backend’s
 * itinerary-derived location meta, then call `createVehicle`.
 * Falls back to sending `vehicle_origin` only if no meta match is found.
 */
export async function createVehicleWithOriginMeta(
  payload: Omit<CreateVehiclePayload, "vehicle_origin" | "vehicle_location_id">,
  originLabel: string
) {
  let location_id: number | null = null;
  try {
    const meta = await fetchLocationMeta(originLabel);
    location_id = meta?.location_id ?? null;
  } catch {
    location_id = null;
  }

  return createVehicle({
    ...payload,
    vehicle_location_id: location_id ?? undefined,
    vehicle_origin: originLabel || undefined,
  });
}

export type CreateDriverPayload = {
  vendorId: number;
  vehicleTypeId: number; // vendor_vehicle_type_ID
  driverName: string;
  mobile: string;
};

export async function createDriver(payload: CreateDriverPayload) {
  const body = {
    // snake_case expected by backend
    vendor_id: payload.vendorId,
    vehicle_type_id: payload.vehicleTypeId,
    driver_name: payload.driverName,
    driver_primary_mobile_number: payload.mobile,
    // camelCase duplicates
    vendorId: payload.vendorId,
    vehicleTypeId: payload.vehicleTypeId,
    driverName: payload.driverName,
    driverMobileNumber: payload.mobile,
  };
  return api(`/vehicle-availability/drivers`, {
    method: "POST",
    body,
    auth: true,
  });
}

// ==============================
// ASSIGN / REASSIGN
// ==============================
export type AssignVehiclePayload = {
  itineraryPlanId: number;
  vendor_id: number;
  vehicle_type_id: number; // vendor_vehicle_type_ID
  vehicle_id: number;
  driver_id?: number | null;
  createdby?: number | null;
};

export async function assignVehicle(body: AssignVehiclePayload) {
  return api(`/vehicle-availability/assign-vehicle`, {
    method: "POST",
    body,
    auth: true,
  });
}

export type ReassignDriverPayload = {
  itineraryPlanId: number;
  vendor_id: number;
  driver_id: number;
  vehicle_id?: number | null;
  createdby?: number | null;
};

export async function reassignDriver(body: ReassignDriverPayload) {
  return api(`/vehicle-availability/reassign-driver`, {
    method: "POST",
    body,
    auth: true,
  });
}
