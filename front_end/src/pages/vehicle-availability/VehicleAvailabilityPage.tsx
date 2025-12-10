// REPLACE-WHOLE-FILE: src/pages/VehicleAvailability/VehicleAvailabilityPage.tsx

import React, { useEffect, useMemo, useState } from "react";
import { Filter } from "lucide-react";
import {
  fetchAgents,
  fetchVehicleAvailability,
  fetchVehicleTypes,
  fetchVendors,
  fetchLocations,
  SimpleOption,
  VehicleAvailabilityCell,
  VehicleAvailabilityResponse,
  VehicleAvailabilityRow,
} from "@/services/vehicle-availability";
import { AddVehicleModal } from "./modals/AddVehicleModal";
import { AddDriverModal } from "./modals/AddDriverModal";


function clsx(...parts: Array<string | false | null | undefined>) {
  return parts.filter(Boolean).join(" ");
}

function toYmd(d: Date) {
  const pad = (n: number) => n.toString().padStart(2, "0");
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
}

function defaultMonthRange() {
  const now = new Date();
  const first = new Date(now.getFullYear(), now.getMonth(), 1);
  const last = new Date(now.getFullYear(), now.getMonth() + 1, 0);
  return { dateFrom: toYmd(first), dateTo: toYmd(last) };
}

type SelectedCell = { row: VehicleAvailabilityRow; cell: VehicleAvailabilityCell } | null;

export default function VehicleAvailabilityPage() {
  const initialRange = useMemo(() => defaultMonthRange(), []);
  const today = useMemo(() => toYmd(new Date()), []);

  // filter UI
  const [showFilters, setShowFilters] = useState(false);
  const [dateFrom, setDateFrom] = useState(initialRange.dateFrom);
  const [dateTo, setDateTo] = useState(initialRange.dateTo);
  const [vendorId, setVendorId] = useState<number | "">("");
  const [vehicleTypeId, setVehicleTypeId] = useState<number | "">("");
  const [agentId, setAgentId] = useState<number | "">("");
  // Location filter is now string-based (derived from API routeSegments)
  const [locationId, setLocationId] = useState<string>("");

  // lookups
  const [vendors, setVendors] = useState<SimpleOption[]>([]);
  const [vehicleTypes, setVehicleTypes] = useState<SimpleOption[]>([]);
  const [agents, setAgents] = useState<SimpleOption[]>([]);
  const [locations, setLocations] = useState<string[]>([]);


  // chart
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string>("");
  const [data, setData] = useState<VehicleAvailabilityResponse>({ dates: [], rows: [] });

  // search
  const [search, setSearch] = useState("");
  const [selected, setSelected] = useState<SelectedCell>(null);
  const [addVehicleOpen, setAddVehicleOpen] = useState(false);
  const [addDriverOpen, setAddDriverOpen] = useState(false);

  const filteredRows = useMemo(() => {
    const q = search.trim().toLowerCase();
    if (!q) return data.rows;
    return data.rows.filter((r) => {
      const s =
        `${r.vendorName} ${r.vendorId} ${r.vehicleTypeTitle} ${r.vehicleTypeId} ${r.registrationNumber} ${r.vehicleId}`.toLowerCase();
      return s.includes(q);
    });
  }, [data.rows, search]);

    async function loadLocationsBase(q?: string) {
    try {
      const opts = await fetchLocations(q); // [{ id, label }]
      const labels = (opts || []).map(o => o.label).filter(Boolean);
      setLocations(prev =>
        Array.from(new Set([...(prev || []), ...labels])).sort((a, b) => a.localeCompare(b)),
      );
    } catch {
      // keep silent; locations are optional
    }
  }

  function extractLocationsFromAvailability(res: VehicleAvailabilityResponse): string[] {
  const set = new Set<string>();

  for (const row of res.rows || []) {
    for (const cell of row.cells || []) {
      for (const seg of cell.routeSegments || []) {
        const a = (seg.locationName || "").trim();
        const b = (seg.nextVisitingLocation || "").trim();
        if (a) set.add(a);
        if (b) set.add(b);
      }
    }
  }

  return Array.from(set).sort((x, y) => x.localeCompare(y));
}

function rowHasLocation(row: VehicleAvailabilityRow, location: string): boolean {
  const needle = location.trim().toLowerCase();
  if (!needle) return true;

  for (const cell of row.cells || []) {
    for (const seg of cell.routeSegments || []) {
      if ((seg.locationName || "").trim().toLowerCase() === needle) return true;
      if ((seg.nextVisitingLocation || "").trim().toLowerCase() === needle) return true;
    }
  }
  return false;
}

   async function loadLookups() {
    setError("");
    try {
      const [v, vt, a] = await Promise.all([fetchVendors(), fetchVehicleTypes(), fetchAgents()]);
      setVendors(v);
      setVehicleTypes(vt);
      setAgents(a);

      // clear then prefill a base set of locations from backend
      setLocations([]);
      await loadLocationsBase(); // fills from arrival/departure labels even if chart has zero rows
    } catch (e: any) {
      setError(e?.message || "Failed to load dropdown data.");
      setVendors([]);
      setVehicleTypes([]);
      setAgents([]);
      setLocations([]);
    }
  }

  async function loadChart() {
    setLoading(true);
    setError("");
    try {
      const res = await fetchVehicleAvailability({
      dateFrom,
      dateTo,
      vendorId: vendorId === "" ? undefined : vendorId,
      vehicleTypeId: vehicleTypeId === "" ? undefined : vehicleTypeId,
      agentId: agentId === "" ? undefined : agentId,
      // NOTE: locationId is now a string label, not a numeric ID, so we do client-side filtering
    });

    // 1) Build dynamic dropdown options from routeSegments, then MERGE with base list
    const derivedLocations = extractLocationsFromAvailability(res);
    setLocations(prev =>
      Array.from(new Set([...(prev || []), ...derivedLocations])).sort((x, y) => x.localeCompare(y)),
    );

    // 2) Apply location filter locally (so results change only after clicking Apply -> loadChart())
    const loc = (locationId || "").trim();
    const rowsFilteredByLoc = loc ? res.rows.filter((r) => rowHasLocation(r, loc)) : res.rows;

    setData({ ...res, rows: rowsFilteredByLoc });
    } catch (e: any) {
      setError(e?.message || "Failed to load vehicle availability.");
      setData({ dates: [], rows: [] });
    } finally {
      setLoading(false);
    }
  }

  function handleClear() {
    const r = defaultMonthRange();
    setDateFrom(r.dateFrom);
    setDateTo(r.dateTo);
    setVendorId("");
    setVehicleTypeId("");
    setAgentId("");
    setLocationId("");
    setSearch("");
    setTimeout(() => loadChart(), 0);
  }

  useEffect(() => {
    loadLookups();
    loadChart();
    // also try to prefill with a small popular set (no query) at mount
    loadLocationsBase();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const stickyHeaderClass = "sticky top-0 z-20 bg-white border-b border-slate-200";
  const stickyCol1 = "sticky left-0 z-10 bg-white border-r border-slate-200";
  const stickyCol2 = "sticky left-[240px] z-10 bg-white border-r border-slate-200";

  return (
    <div className="p-4">
      {/* Top Right Filter button like screenshot */}
      <div className="mb-3 flex items-center justify-end">
        <button
            className="flex items-center gap-2 rounded-md border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-medium text-purple-700 hover:bg-purple-100"
            onClick={() => setShowFilters((s) => !s)}
            type="button"
          >
            <Filter size={16} />
            Filter
          </button>
      </div>

      {/* Filter Panel (open only when user clicks Filter) */}
      {showFilters ? (
        <div className="mb-4 rounded-xl border border-slate-200 bg-white p-5">
          <div className="mb-4 text-lg font-semibold text-slate-900">Filter</div>

          <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div className="flex flex-col gap-1">
              <label className="text-sm text-slate-700">
                Date from<span className="text-red-500">*</span>
              </label>
              <input
                type="date"
                className="h-11 rounded-md border border-slate-300 bg-white px-3 text-sm"
                value={dateFrom}
                onChange={(e) => setDateFrom(e.target.value)}
              />
            </div>

            <div className="flex flex-col gap-1">
              <label className="text-sm text-slate-700">
                Date To<span className="text-red-500">*</span>
              </label>
              <input
                type="date"
                className="h-11 rounded-md border border-slate-300 bg-white px-3 text-sm"
                value={dateTo}
                onChange={(e) => setDateTo(e.target.value)}
              />
            </div>

            <div className="flex flex-col gap-1">
              <label className="text-sm text-slate-700">
                Vendor<span className="text-red-500">*</span>
              </label>
              <select
                className="h-11 rounded-md border border-slate-300 bg-white px-3 text-sm"
                value={vendorId === "" ? "" : String(vendorId)}
                onChange={(e) => setVendorId(e.target.value ? Number(e.target.value) : "")}
              >
                <option value="">Choose Vendor</option>
                {vendors.map((v) => (
                  <option key={v.id} value={String(v.id)}>
                    {v.label}
                  </option>
                ))}
              </select>
            </div>

            <div className="flex flex-col gap-1">
              <label className="text-sm text-slate-700">
                Vehicle Type<span className="text-red-500">*</span>
              </label>
              <select
                className="h-11 rounded-md border border-slate-300 bg-white px-3 text-sm"
                value={vehicleTypeId === "" ? "" : String(vehicleTypeId)}
                onChange={(e) =>
                  setVehicleTypeId(e.target.value ? Number(e.target.value) : "")
                }
              >
                <option value="">Choose Vehicle Types</option>
                {vehicleTypes.map((v) => (
                  <option key={v.id} value={String(v.id)}>
                    {v.label}
                  </option>
                ))}
              </select>
            </div>

            <div className="flex flex-col gap-1">
              <label className="text-sm text-slate-700">
                Agent<span className="text-red-500">*</span>
              </label>
              <select
                className="h-11 rounded-md border border-slate-300 bg-white px-3 text-sm"
                value={agentId === "" ? "" : String(agentId)}
                onChange={(e) => setAgentId(e.target.value ? Number(e.target.value) : "")}
              >
                <option value="">Select Agent</option>
                {agents.map((a) => (
                  <option key={a.id} value={String(a.id)}>
                    {a.label}
                  </option>
                ))}
              </select>
            </div>

            <div className="flex flex-col gap-1">
              <label className="text-sm text-slate-700">
                Location<span className="text-red-500">*</span>
              </label>
              <select
                className="h-11 rounded-md border border-slate-300 bg-white px-3 text-sm"
                value={locationId}
                onChange={(e) => setLocationId(e.target.value)}
              >
                <option value="">Choose Location</option>
                {locations.map((label) => (
                  <option key={label} value={label}>
                    {label}
                  </option>
                ))}
              </select>
            </div>

            <div className="flex items-end gap-3">
              <button
                className="h-11 w-[120px] rounded-md bg-slate-900 text-sm font-medium text-white hover:bg-slate-800 disabled:opacity-60"
                onClick={() => {
                  loadChart();
                  setShowFilters(false); // like PHP: apply closes filter panel
                }}
                disabled={loading}
                type="button"
              >
                {loading ? "Loading..." : "Apply"}
              </button>

              <button
                className="h-11 w-[120px] rounded-md bg-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-400"
                onClick={handleClear}
                type="button"
                disabled={loading}
              >
                Clear
              </button>
            </div>
          </div>
        </div>
      ) : null}

      {/* Header row like screenshot 2 */}
      <div className="mb-3 rounded-xl border border-slate-200 bg-white p-4">
        <div className="flex flex-wrap items-center justify-between gap-3">
          <div className="text-lg font-semibold text-slate-900">
            Vehicle Availability Chart
          </div>

          <div className="flex flex-wrap items-center gap-3">
            <button
              className="rounded-md border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-medium text-purple-700 hover:bg-purple-100"
              type="button"
              onClick={() => setAddVehicleOpen(true)}
            >
              + Add New Vehicle
            </button>
            <button
              className="rounded-md border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-medium text-purple-700 hover:bg-purple-100"
              type="button"
              onClick={() => setAddDriverOpen(true)}
            >
              + Add New Driver
            </button>

            <div className="flex items-center gap-2">
              <div className="text-sm text-slate-700">Search:</div>
              <input
                className="h-9 w-[220px] rounded-md border border-slate-300 bg-white px-3 text-sm"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                placeholder=""
              />
            </div>
          </div>
        </div>
      </div>

      {error ? (
        <div className="mb-3 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-800">
          {error}
        </div>
      ) : null}

      {/* Chart Table */}
      <div className="rounded-xl border border-slate-200 bg-white">
        <div className="max-h-[75vh] overflow-auto">
          <table className="min-w-max border-collapse text-sm">
            <thead>
              <tr>
                <th
                  className={clsx(
                    stickyHeaderClass,
                    stickyCol1,
                    "min-w-[240px] p-2 text-left font-semibold text-slate-800",
                  )}
                >
                  Vendor
                </th>
                <th
                  className={clsx(
                    stickyHeaderClass,
                    stickyCol2,
                    "min-w-[340px] p-2 text-left font-semibold text-slate-800",
                  )}
                >
                  Vehicle
                </th>
                {data.dates.map((d) => (
                  <th
                    key={d}
                    className={clsx(
                      stickyHeaderClass,
                      "min-w-[120px] px-2 py-2 text-center font-semibold text-slate-700",
                    )}
                  >
                    <div className="text-xs">{d}</div>
                  </th>
                ))}
              </tr>
            </thead>

            <tbody>
              {!loading && filteredRows.length === 0 ? (
                <tr>
                  <td
                    colSpan={2 + data.dates.length}
                    className="p-4 text-center text-slate-600"
                  >
                    No vehicle availability data for this range.
                  </td>
                </tr>
              ) : null}

              {filteredRows.map((row) => (
                <tr key={`${row.vendorId}-${row.vehicleTypeId}-${row.vehicleId}`}>
                  <td className={clsx(stickyCol1, "border-b border-slate-200 p-2 align-top")}>
                    <div className="font-medium text-slate-900">
                      {row.vendorName || `Vendor #${row.vendorId}`}
                    </div>
                  </td>

                  <td className={clsx(stickyCol2, "border-b border-slate-200 p-2 align-top")}>
                    <div className="font-medium text-slate-900">
                      {row.vehicleTypeTitle?.trim()
                        ? row.vehicleTypeTitle
                        : `Type #${row.vehicleTypeId}`}
                    </div>
                    <div className="text-xs text-slate-600">
                      {" "}
                      <span className="font-semibold text-blue-700">
                        {row.registrationNumber}
                      </span>
                    </div>
                  </td>

                  {row.cells.map((cell) => {
                    const inTrip = Boolean(cell.isWithinTrip && cell.itineraryPlanId);
                    const bg =
                      !inTrip
                        ? "bg-white"
                        : cell.isStart
                        ? "bg-green-50"
                        : cell.isEnd
                        ? "bg-rose-50"
                        : "bg-amber-50";

                    const todayRing = cell.date === today ? "ring-2 ring-slate-600" : "";

                    return (
                      <td
                        key={`${row.vehicleId}-${cell.date}`}
                        className="border-b border-slate-200 px-1 py-1 align-top"
                      >
                        <button
                          type="button"
                          className={clsx(
                            "w-[110px] rounded-md border border-slate-200 p-2 text-left",
                            bg,
                            todayRing,
                            inTrip ? "hover:border-slate-400" : "hover:bg-slate-50",
                          )}
                          disabled={!cell.itineraryPlanId}
                          onClick={() => {
                            if (!cell.itineraryPlanId) return;
                            setSelected({ row, cell });
                          }}
                          title={cell.itineraryPlanId ? "Click to view details" : "No trip"}
                        >
                          {!cell.itineraryPlanId ? (
                            <div className="h-[48px]" />
                          ) : (
                            <>
                              <div className="flex items-center justify-between gap-2">
                                <div className="text-[11px] font-semibold text-slate-900">
                                  {cell.itineraryQuoteId || `Plan #${cell.itineraryPlanId}`}
                                </div>
                                <div className="text-[10px] text-slate-600">
                                  {cell.isStart ? "START" : cell.isEnd ? "END" : "MID"}
                                </div>
                              </div>

                              <div className="mt-1 flex flex-wrap gap-1">
                                <span
                                  className={clsx(
                                    "rounded-full px-2 py-[2px] text-[10px] font-medium",
                                    cell.isVehicleAssigned
                                      ? "bg-emerald-100 text-emerald-800"
                                      : "bg-slate-100 text-slate-700",
                                  )}
                                >
                                  {cell.isVehicleAssigned ? "Assigned" : "Unassigned"}
                                </span>

                                <span
                                  className={clsx(
                                    "rounded-full px-2 py-[2px] text-[10px] font-medium",
                                    cell.hasDriver
                                      ? "bg-indigo-100 text-indigo-800"
                                      : "bg-slate-100 text-slate-600",
                                  )}
                                >
                                  {cell.hasDriver ? "Driver ✓" : "Driver —"}
                                </span>

                                <span className="rounded-full bg-purple-100 px-2 py-[2px] text-[10px] font-medium text-purple-800">
                                  Routes: {cell.routeSegments.length}
                                </span>
                              </div>
                            </>
                          )}
                        </button>
                      </td>
                    );
                  })}
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Details Modal */}
      {selected ? (
        <div
          className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
          onClick={() => setSelected(null)}
        >
          <div
            className="w-full max-w-3xl rounded-xl bg-white shadow-xl"
            onClick={(e) => e.stopPropagation()}
          >
            <div className="flex items-start justify-between gap-3 border-b border-slate-200 p-4">
              <div>
                <div className="text-sm font-semibold text-slate-900">
                  {selected.cell.itineraryQuoteId ||
                    `Itinerary Plan #${selected.cell.itineraryPlanId}`}
                </div>
                <div className="mt-1 text-xs text-slate-600">
                  Date: <span className="font-medium">{selected.cell.date}</span> • Vendor:{" "}
                  <span className="font-medium">{selected.row.vendorName}</span> • Vehicle:{" "}
                  <span className="font-medium">
                    {(selected.row.vehicleTypeTitle?.trim()
                      ? selected.row.vehicleTypeTitle
                      : `Type #${selected.row.vehicleTypeId}`) +
                      ` (${selected.row.registrationNumber})`}
                  </span>
                </div>
              </div>

              <button
                className="rounded-md border border-slate-300 bg-white px-3 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50"
                onClick={() => setSelected(null)}
              >
                Close
              </button>
            </div>

            <div className="p-4">
              <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div className="rounded-lg border border-slate-200 p-3">
                  <div className="text-xs font-semibold text-slate-700">Trip Position</div>
                  <div className="mt-2 text-sm text-slate-900">
                    {selected.cell.isStart
                      ? "Start day"
                      : selected.cell.isEnd
                      ? "End day"
                      : "In-between day"}
                  </div>
                  <div className="mt-2 text-xs text-slate-600">
                    Today highlight:{" "}
                    <span className="font-medium">
                      {selected.cell.isToday ? "Yes" : "No"}
                    </span>
                  </div>
                </div>

                <div className="rounded-lg border border-slate-200 p-3">
                  <div className="text-xs font-semibold text-slate-700">Assignment</div>
                  <div className="mt-2 text-sm text-slate-900">
                    Vehicle:{" "}
                    <span className="font-medium">
                      {selected.cell.isVehicleAssigned ? "Assigned" : "Not assigned"}
                    </span>
                  </div>
                  <div className="mt-2 text-sm text-slate-900">
                    Driver:{" "}
                    <span className="font-medium">
                      {selected.cell.hasDriver ? "Assigned" : "Not assigned"}
                    </span>
                  </div>
                  {selected.cell.driverId ? (
                    <div className="mt-1 text-xs text-slate-600">
                      Driver ID: <span className="font-medium">{selected.cell.driverId}</span>
                    </div>
                  ) : null}
                </div>
              </div>

              <div className="mt-4 rounded-lg border border-slate-200 p-3">
                <div className="text-xs font-semibold text-slate-700">
                  Route Segments (this day)
                </div>

                {selected.cell.routeSegments.length === 0 ? (
                  <div className="mt-2 text-sm text-slate-600">No routes.</div>
                ) : (
                  <div className="mt-2 space-y-2">
                    {selected.cell.routeSegments.map((r, idx) => (
                      <div
                        key={`${idx}-${r.locationName}-${r.nextVisitingLocation}`}
                        className="rounded-md bg-slate-50 p-2 text-sm text-slate-900"
                      >
                        <span className="font-medium">{r.locationName}</span>{" "}
                        <span className="text-slate-500">→</span>{" "}
                        <span className="font-medium">{r.nextVisitingLocation}</span>
                      </div>
                    ))}
                  </div>
                )}
              </div>

              <div className="mt-4 text-[11px] text-slate-500">
                If you want Agent/Location filters to affect results, backend must accept
                agentId/locationId in query DTO and apply the same filtering logic as PHP.
              </div>
            </div>
          </div>
        </div>
      ) : null}
      <AddVehicleModal
          open={addVehicleOpen}
          onClose={() => setAddVehicleOpen(false)}
          onCreated={() => {
            // refresh chart so the newly added vehicle can appear
            loadChart();
          }}
          vendors={vendors}
          vehicleTypes={vehicleTypes}
          defaultVendorId={vendorId}
          defaultVehicleTypeId={vehicleTypeId}
        />

        <AddDriverModal
          open={addDriverOpen}
          onClose={() => setAddDriverOpen(false)}
          onCreated={() => {
            // optional refresh; harmless
            loadChart();
          }}
          vendors={vendors}
          vehicleTypes={vehicleTypes}
          defaultVendorId={vendorId}
          defaultVehicleTypeId={vehicleTypeId}
        />
    </div>
  );
}
