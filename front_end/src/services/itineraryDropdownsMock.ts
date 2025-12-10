// FILE: src/services/itineraryDropdownsMock.ts
import { api } from "@/lib/api";

// Generic simple option for most dropdowns
export type SimpleOption = {
  id: string;
  label: string;
};

// Location option for arrival/departure & route details
export type LocationOption = {
  id: number;
  name: string;
};

/**
 * Normalizes any simple-option-like payload into { id, label }[]
 * Supports shapes like:
 *  - { id, label }
 *  - { id, name }
 *  - { hotel_category_id, hotel_category_title }
 *  - { value, text }
 */
function normalizeSimpleArray(payload: any): SimpleOption[] {
  if (!Array.isArray(payload)) return [];

  return payload
    .map((item: any, index: number): SimpleOption | null => {
      const rawId =
        item.id ??
        item.value ??
        item.hotel_category_id ??
        item.hotel_facility_id ??
        index + 1;

      const rawLabel =
        item.label ??
        item.name ??
        item.title ??
        item.hotel_category_title ??
        item.hotel_facility_title ??
        item.text ??
        "";

      const idStr = String(rawId ?? "").trim();
      const labelStr = String(rawLabel ?? "").trim();

      if (!labelStr) return null;
      return { id: idStr, label: labelStr };
    })
    .filter((x): x is SimpleOption => x !== null);
}

/**
 * Normalizes location payload into { id, name }[]
 * Supports shapes like:
 *  - { id, name }
 *  - { location_id, location_name }
 *  - plain string array ["Cochin", "Munnar", ...]
 */
function normalizeLocationArray(payload: any): LocationOption[] {
  if (Array.isArray(payload)) {
    if (typeof payload[0] === "string") {
      return payload.map((name, idx) => ({
        id: idx + 1,
        name: String(name),
      }));
    }

    return payload
      .map((item: any, index: number): LocationOption | null => {
        const rawId = item.id ?? item.location_id ?? index + 1;
        const rawName =
          item.name ??
          item.location_name ??
          item.source_location ??
          item.city ??
          item.destination_location;

        const nameStr = String(rawName ?? "").trim();
        if (!nameStr) return null;

        const idNum = Number(rawId);
        return {
          id: Number.isFinite(idNum) ? idNum : index + 1,
          name: nameStr,
        };
      })
      .filter((x): x is LocationOption => x !== null);
  }

  return [];
}

/** Generic helper for simple dropdowns hitting /itinerary-dropdowns/* */
async function fetchSimple(path: string): Promise<SimpleOption[]> {
  const res = await api(`/itinerary-dropdowns${path}`, {
    method: "GET",
    auth: true,
  });

  return normalizeSimpleArray(res);
}

// ----------------- LOCATIONS -----------------

/**
 * Fetch locations.
 *
 * mode = "source"       -> distinct source locations (PHP: selectize_source_location)
 * mode = "destination"  -> destinations for a given source (PHP: route_destination)
 *
 * This matches:
 *   GET /api/v1/itinerary-dropdowns/locations?type=source
 *   GET /api/v1/itinerary-dropdowns/locations?type=destination&source=Chennai
 */
export async function fetchLocations(
  mode: "source" | "destination" = "source",
  sourceLocation?: string
): Promise<LocationOption[]> {
  const params = new URLSearchParams();
  params.set("type", mode);
  if (mode === "destination" && sourceLocation) {
    params.set("source", sourceLocation);
  }

  const res = await api(
    `/itinerary-dropdowns/locations?${params.toString()}`,
    {
      method: "GET",
      auth: true,
    }
  );

  return normalizeLocationArray(res);
}

// ----------------- ITINERARY HEADER DROPDOWNS -----------------

export async function fetchItineraryTypes(): Promise<SimpleOption[]> {
  return fetchSimple("/itinerary-types");
}

export async function fetchTravelTypes(): Promise<SimpleOption[]> {
  return fetchSimple("/travel-types");
}

export async function fetchEntryTicketOptions(): Promise<SimpleOption[]> {
  return fetchSimple("/entry-ticket-options");
}

export async function fetchGuideOptions(): Promise<SimpleOption[]> {
  return fetchSimple("/guide-options");
}

export async function fetchNationalities(): Promise<SimpleOption[]> {
  return fetchSimple("/nationalities");
}

export async function fetchFoodPreferences(): Promise<SimpleOption[]> {
  return fetchSimple("/food-preferences");
}

export async function fetchVehicleTypes(): Promise<SimpleOption[]> {
  return fetchSimple("/vehicle-types");
}

// ----------------- HOTEL CATEGORY / FACILITY -----------------

/**
 * Hotel categories – maps PHP dvi_hotel_category:
 *   hotel_category_id, hotel_category_title
 * into:
 *   { id: string, label: string }  e.g. "3*"
 */
export async function fetchHotelCategories(): Promise<SimpleOption[]> {
  const res = await api("/itinerary-dropdowns/hotel-categories", {
    method: "GET",
    auth: true,
  });

  return normalizeSimpleArray(res);
}

/**
 * Hotel facilities – maps PHP facility master to:
 *   { id, label }
 */
export async function fetchHotelFacilities(): Promise<SimpleOption[]> {
  const res = await api("/itinerary-dropdowns/hotel-facilities", {
    method: "GET",
    auth: true,
  });

  return normalizeSimpleArray(res);
}

// ----------------- VIA ROUTES (Hotspots between source & destination) -----------------

export type ViaRouteFormResult = {
  options: SimpleOption[];
  existingLabels: string[];
  existingIds: string[]; // NEW – exact via_route_location IDs
};

/**
 * Full via-route form (options + existing selection) – Nest equivalent of
 * ajax_latest_itineary_via_route_form.php?type=show_form
 *
 * Backend response shape:
 *   { success: true, data: { existing: [...], options: [{ id, label }, ...] } }
 */
export async function fetchViaRouteForm(args: {
  dayNo?: number;
  source: string;
  next: string;
  date?: string;
  itineraryPlanId?: number | null;
  itinerarySessionId?: string;
}): Promise<ViaRouteFormResult> {
  const params = new URLSearchParams();

  if (args.dayNo != null) {
    params.set("DAY_NO", String(args.dayNo));
  }

  if (args.source) {
    params.set("selected_source_location", args.source);
  }

  if (args.next) {
    params.set("selected_next_visiting_location", args.next);
  }

  if (args.date) {
    params.set("itinerary_route_date", args.date);
  }

  if (args.itinerarySessionId) {
    params.set("itinerary_session_id", args.itinerarySessionId);
  }

  if (args.itineraryPlanId != null) {
    params.set("itinerary_plan_ID", String(args.itineraryPlanId));
  }

  const res = await api(
    `/itinerary-via-routes/form?${params.toString()}`,
    {
      method: "GET",
      auth: true,
    }
  );

  // api() usually returns the JSON body; handle both shapes:
  //  1) { success, data: { existing, options } }
  //  2) { existing, options }
  const raw = (res as any)?.data ?? (res as any) ?? {};

  const optionsRaw =
    raw.options ??
    (raw.data && Array.isArray(raw.data.options) ? raw.data.options : []);

  const existingRaw =
    raw.existing ??
    (raw.data && Array.isArray(raw.data.existing) ? raw.data.existing : []);

  const options = normalizeSimpleArray(optionsRaw);

  const existingLabels: string[] = (existingRaw as any[])
    .map((v: any) =>
      String(
        v.viaLocationName ??
          v.itinerary_via_location_name ??
          v.via_route_location ??
          ""
      ).trim()
    )
    .filter((s: string) => !!s);

  const existingIds: string[] = (existingRaw as any[])
    .map((v: any) =>
      String(
        v.viaLocationId ??
          v.itinerary_via_location_ID ??
          v.itinerary_via_location_id ??
          ""
      ).trim()
    )
    .filter((s: string) => !!s);

  return { options, existingLabels, existingIds };
}

/**
 * Simple helper if somewhere you only need the dropdown options.
 * Currently not used in CreateItinerary, but kept for reuse.
 */
export async function fetchViaRoutes(
  sourceLocation: string,
  nextLocation: string
): Promise<SimpleOption[]> {
  if (!sourceLocation || !nextLocation) return [];
  const { options } = await fetchViaRouteForm({
    source: sourceLocation,
    next: nextLocation,
  });
  return options;
}
