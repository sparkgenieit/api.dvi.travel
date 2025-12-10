// FILE: src/pages/daily-moment-tracker/DailyMomentTracker.tsx

import React, { useEffect, useMemo, useState } from "react";
import { useNavigate } from "react-router-dom";
import {
  Calendar as CalendarIcon,
  Download,
  CarIcon,
} from "lucide-react";

import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";
import { Calendar } from "@/components/ui/calendar";

import {
  fetchDailyMoments,
  TripType,
  DailyMomentApiRow,
} from "@/services/dailyMomentTracker";

// REPLACE whole DailyMomentRow type with:

type DailyMomentRow = {
  id: number; // row counter from backend
  itineraryPlanId: number;
  itineraryRouteId: number;

  // Guest
  guestName: string;
  guestMobile: string;
  guestEmail: string;

  quoteId: string;
  routeDate: Date;
  type: TripType;
  fromLocation: string;
  toLocation: string;
  arrivalDetails: string;
  departureDetails: string;
  hotel: string;
  mealPlan: string;
  vendor: string;
  vehicle: string;
  vehicleNo: string;
  driverName: string;
  driverMobile: string;
  specialRemark: string;

  // Travel expert
  travelExpert: string;
  travelExpertMobile: string;
  travelExpertEmail: string;

  agent: string;
};

function formatDateDisplay(date: Date | undefined) {
  if (!date) return "";
  const d = date.getDate().toString().padStart(2, "0");
  const m = (date.getMonth() + 1).toString().padStart(2, "0");
  const y = date.getFullYear();
  return `${d}-${m}-${y}`;
}

function formatDateForApi(date: Date | undefined) {
  // backend expects DD-MM-YYYY (same as PHP)
  return date ? formatDateDisplay(date) : "";
}

function parseDDMMYYYY(value: string): Date {
  const [dd, mm, yyyy] = value.split("-");
  return new Date(Number(yyyy), Number(mm) - 1, Number(dd));
}

function cleanText(value: string | null | undefined): string {
  if (!value) return "";
  const trimmed = value.trim();
  if (trimmed === "--") return "";
  return trimmed;
}

export const DailyMomentTracker: React.FC = () => {
  const navigate = useNavigate();

  const [fromDateObj, setFromDateObj] = useState<Date | undefined>(
    new Date()
  );
  const [toDateObj, setToDateObj] = useState<Date | undefined>(
    new Date()
  );

  const [rows, setRows] = useState<DailyMomentRow[]>([]);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);

  const [search, setSearch] = useState<string>("");
  const [entriesPerPage, setEntriesPerPage] = useState<number>(10);
  const [currentPage, setCurrentPage] = useState<number>(1);

  // ================== DATA FETCH ==================

  useEffect(() => {
    // Load data whenever dates change (mimics PHP behaviour)
    const fromDateStr = formatDateForApi(fromDateObj);
    const toDateStr = formatDateForApi(toDateObj);

    if (!fromDateStr || !toDateStr) {
      return;
    }

    let cancelled = false;

    async function load() {
      try {
        setLoading(true);
        setError(null);
        const apiRows = await fetchDailyMoments({
          fromDate: fromDateStr,
          toDate: toDateStr,
        });

        if (cancelled) return;

        const mapped: DailyMomentRow[] = apiRows.map(
          (r: DailyMomentApiRow) => ({
            id: r.count,
            itineraryPlanId: r.itinerary_plan_ID,
            itineraryRouteId: r.itinerary_route_ID,

            // Guest
            guestName: cleanText(r.guest_name),
            guestMobile: cleanText(r.guest_mobile),
            guestEmail: cleanText(r.guest_email),

            quoteId: cleanText(r.quote_id) || "--",
            routeDate: parseDDMMYYYY(r.route_date),
            type: r.trip_type,
            fromLocation: cleanText(r.location_name),
            toLocation: cleanText(r.next_visiting_location),
            arrivalDetails: cleanText(r.arrival_flight_details),
            departureDetails: cleanText(r.departure_flight_details),
            hotel: cleanText(r.hotel_name),
            mealPlan: cleanText(r.meal_plan),
            vendor: cleanText(r.vendor_name),
            vehicle: cleanText(r.vehicle_type_title),
            vehicleNo: cleanText(r.vehicle_no),
            driverName: cleanText(r.driver_name),
            driverMobile: cleanText(r.driver_mobile),
            specialRemark: cleanText(r.special_remarks),

            // Travel expert
            travelExpert: cleanText(r.travel_expert_name),
            travelExpertMobile: cleanText(r.travel_expert_mobile),
            travelExpertEmail: cleanText(r.travel_expert_email),

            agent: cleanText(r.agent_name),
          })
        );

        setRows(mapped);
        setCurrentPage(1);
      } catch (e: any) {
        console.error(e);
        if (!cancelled) {
          setError(
            e?.message || "Failed to load Daily Moment data from server."
          );
          setRows([]);
        }
      } finally {
        if (!cancelled) setLoading(false);
      }
    }

    load();

    return () => {
      cancelled = true;
    };
  }, [fromDateObj, toDateObj]);

  // ================== TABLE FILTERING + PAGINATION ==================

  const filteredRows = useMemo(() => {
    let filtered = rows;

    if (search.trim()) {
      const q = search.toLowerCase();
      filtered = filtered.filter((r) => {
        const haystack = [
          r.guestName,
          r.quoteId,
          r.fromLocation,
          r.toLocation,
          r.hotel,
          r.vendor,
          r.vehicle,
          r.vehicleNo,
          r.driverName,
          r.driverMobile,
          r.travelExpert,
          r.agent,
        ]
          .join(" ")
          .toLowerCase();
        return haystack.includes(q);
      });
    }

    return filtered;
  }, [rows, search]);

  const totalEntries = filteredRows.length;
  const totalPages = Math.max(1, Math.ceil(totalEntries / entriesPerPage));
  const currentPageSafe = Math.min(currentPage, totalPages);

  const paginatedRows = useMemo(() => {
    const start = (currentPageSafe - 1) * entriesPerPage;
    const end = start + entriesPerPage;
    return filteredRows.slice(start, end);
  }, [filteredRows, entriesPerPage, currentPageSafe]);

  const startEntry =
    totalEntries === 0 ? 0 : (currentPageSafe - 1) * entriesPerPage + 1;
  const endEntry =
    totalEntries === 0
      ? 0
      : Math.min(currentPageSafe * entriesPerPage, totalEntries);

  const handleChangePerPage = (value: string) => {
    const num = parseInt(value, 10);
    setEntriesPerPage(num);
    setCurrentPage(1);
  };

  const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setSearch(e.target.value);
    setCurrentPage(1);
  };

  const handlePrev = () => {
    setCurrentPage((p) => Math.max(1, p - 1));
  };

  const handleNext = () => {
    setCurrentPage((p) => Math.min(totalPages, p + 1));
  };

  const pageNumbers = Array.from({ length: totalPages }, (_, i) => i + 1);

  // ================== CAR ICON – NAVIGATE TO DAY VIEW ==================

  const handleOpenDayView = (row: DailyMomentRow) => {
    navigate(
      `/daily-moment/day-view/${row.itineraryPlanId}/${row.itineraryRouteId}`,
      { state: { row } }
    );
  };

  // ================== RENDER ==================

  return (
    <div className="w-full min-h-screen bg-[#fbeef8] p-4 md:p-6">
      {/* FILTER CARD */}
      <div className="bg-[#fefefe]/40 rounded-xl border border-[#f6dfff] mb-5">
        <div className="px-6 py-5">
          <p className="text-sm font-semibold text-[#4a4260] mb-4">FILTER</p>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {/* From Date */}
            <div className="space-y-2">
              <Label className="text-sm text-[#4a4260]">From Date:</Label>
              <Popover>
                <PopoverTrigger asChild>
                  <Button
                    variant="outline"
                    className={`w-full justify-start h-10 text-left font-normal ${
                      !fromDateObj ? "text-muted-foreground" : ""
                    } bg-white border border-[#f0d8ff] text-[#4a4260]`}
                  >
                    <CalendarIcon className="mr-2 h-4 w-4" />
                    {fromDateObj
                      ? formatDateDisplay(fromDateObj)
                      : "DD-MM-YYYY"}
                  </Button>
                </PopoverTrigger>
                <PopoverContent className="p-0" align="start">
                  <Calendar
                    mode="single"
                    selected={fromDateObj}
                    onSelect={(date) => setFromDateObj(date ?? undefined)}
                    initialFocus
                  />
                </PopoverContent>
              </Popover>
            </div>

            {/* To Date */}
            <div className="space-y-2">
              <Label className="text-sm text-[#4a4260]">To Date:</Label>
              <Popover>
                <PopoverTrigger asChild>
                  <Button
                    variant="outline"
                    className={`w-full justify-start h-10 text-left font-normal ${
                      !toDateObj ? "text-muted-foreground" : ""
                    } bg-white border border-[#f0d8ff] text-[#4a4260]`}
                  >
                    <CalendarIcon className="mr-2 h-4 w-4" />
                    {toDateObj ? formatDateDisplay(toDateObj) : "DD-MM-YYYY"}
                  </Button>
                </PopoverTrigger>
                <PopoverContent className="p-0" align="start">
                  <Calendar
                    mode="single"
                    selected={toDateObj}
                    onSelect={(date) => setToDateObj(date ?? undefined)}
                    initialFocus
                  />
                </PopoverContent>
              </Popover>
            </div>
          </div>
        </div>
      </div>

      {/* LIST CARD */}
      <div className="bg-white/70 rounded-xl border border-[#f6dfff]">
        {/* Header: title + search/export */}
        <div className="px-6 pt-5">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <p className="text-sm font-semibold text-[#4a4260]">
              List of Daily Moment
            </p>

            <div className="flex items-center gap-3">
              <div className="flex items-center gap-2">
                <Label className="text-xs text-[#4a4260]">Search:</Label>
                <Input
                  value={search}
                  onChange={handleSearchChange}
                  className="h-9 w-40 md:w-56"
                  placeholder="Search…"
                />
              </div>
              <Button className="h-9 px-4 gap-2 rounded-md bg-[#e5fff1] border border-[#b7f7d9] text-[#0f9c34] text-sm flex items-center">
                <Download className="h-4 w-4" />
                Export
              </Button>
            </div>
          </div>

          {/* Show entries */}
          <div className="flex items-center gap-2 mt-4 mb-2 text-xs text-[#4a4260]">
            <span>Show</span>
            <Select
              value={entriesPerPage.toString()}
              onValueChange={handleChangePerPage}
            >
              <SelectTrigger className="h-8 w-20">
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="10">10</SelectItem>
                <SelectItem value="25">25</SelectItem>
                <SelectItem value="50">50</SelectItem>
              </SelectContent>
            </Select>
            <span>entries</span>
          </div>
        </div>

        {/* TABLE */}
        <div className="max-h-[460px] overflow-x-auto overflow-y-auto border-t border-[#f3e0ff]">
          <table className="min-w-[1700px] text-xs">
            <thead className="bg-[#fbf2ff] sticky top-0 z-10">
              <tr>
                <th className="text-left px-5 py-3 text-[11px] text-[#4a4260]">
                  ACTION
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  GUEST NAME
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  QUOTE ID
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  ROUTE DATE
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  TYPE(A/D/O)
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  FROM LOCATION
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  TO LOCATION
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  ARRIVAL FLIGHT/TRAIN DETAILS
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  DEPARTURE FLIGHT/TRAIN DETAILS
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  HOTEL
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  MEAL PLAN
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  VENDOR
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  VEHICLE
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  VEHICLE NO
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  DRIVER NAME
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  DRIVER MOBILE
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  SPECIAL REMARK
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  TRAVEL EXPERT
                </th>
                <th className="text-left px-3 py-3 text-[11px] text-[#4a4260]">
                  AGENT
                </th>
              </tr>
            </thead>
            <tbody>
              {loading ? (
                <tr>
                  <td
                    colSpan={19}
                    className="text-center py-14 text-[#4a4260] text-sm"
                  >
                    Loading…
                  </td>
                </tr>
              ) : error ? (
                <tr>
                  <td
                    colSpan={19}
                    className="text-center py-14 text-[#f4008f] text-sm"
                  >
                    {error}
                  </td>
                </tr>
              ) : paginatedRows.length === 0 ? (
                <tr>
                  <td
                    colSpan={19}
                    className="text-center py-14 text-[#f4008f] text-sm"
                  >
                    No data Found
                  </td>
                </tr>
              ) : (
                paginatedRows.map((row) => (
                  <tr key={row.id} className="hover:bg-[#fff7ff]">
                    <td className="px-5 py-2 text-[#4a4260]">
                    <button
                        type="button"
                        onClick={() => {
                        if (!row.itineraryPlanId) return;

                        // routeId is optional; avoid "undefined" ending up in URL
                        const safeRouteId =
                            row.itineraryRouteId === null || row.itineraryRouteId === undefined
                            ? 0
                            : row.itineraryRouteId;

                        navigate(
                            `/daily-moment/day-view/${row.itineraryPlanId}/${safeRouteId}`,
                            {
                            state: { row }, // pass header row data for Day View
                            }
                        );
                        }}
                        className="inline-flex items-center justify-center rounded-full border border-[#e3d4ff] bg-white h-7 w-7"
                    >
                        <CarIcon className="h-4 w-4 text-[#7b6f9a]" />
                    </button>
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.guestName || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#c219a4] font-medium">
                      {row.quoteId || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {formatDateDisplay(row.routeDate)}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.type}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.fromLocation || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.toLocation || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.arrivalDetails || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.departureDetails || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.hotel || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.mealPlan || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.vendor || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.vehicle || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.vehicleNo || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.driverName || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.driverMobile || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.specialRemark || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.travelExpert || "--"}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.agent || "--"}
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>

        {/* FOOTER: entries text + pagination */}
        <div className="flex flex-col md:flex-row items-center justify-between gap-3 px-6 py-4 text-xs text-[#4a4260]">
          <div>
            {totalEntries === 0 ? (
              <span>Showing 0 entries</span>
            ) : (
              <span>
                Showing {startEntry} to {endEntry} of {totalEntries} entries
              </span>
            )}
          </div>

          <div className="flex items-center gap-2">
            <Button
              variant="outline"
              size="sm"
              className="h-8 px-3 rounded-md bg-[#f6ecff] border-none text-[#4a4260]"
              onClick={handlePrev}
              disabled={currentPageSafe === 1}
            >
              Previous
            </Button>
            {pageNumbers.map((num) => (
              <Button
                key={num}
                variant="outline"
                size="sm"
                className={`h-8 w-8 px-0 rounded-md border-none ${
                  num === currentPageSafe
                    ? "bg-[#a448ff] text-white"
                    : "bg-[#f6ecff] text-[#4a4260]"
                }`}
                onClick={() => setCurrentPage(num)}
              >
                {num}
              </Button>
            ))}
            <Button
              variant="outline"
              size="sm"
              className="h-8 px-3 rounded-md bg-[#f6ecff] border-none text-[#4a4260]"
              onClick={handleNext}
              disabled={currentPageSafe === totalPages}
            >
              Next
            </Button>
          </div>
        </div>

        <div className="py-3 text-center text-[11px] text-[#a593c7] border-t border-[#f6dfff]">
          DVI Holidays @ 2025
        </div>
      </div>
    </div>
  );
};
