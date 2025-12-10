// REPLACE-WHOLE-FILE: src/pages/LatestItinerary.tsx
import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";

import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
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
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  Download,
  Eye,
  Copy,
  Calendar as CalendarIcon,
} from "lucide-react";

import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";
import { Calendar } from "@/components/ui/calendar";

import { ItineraryService } from "@/services/itinerary";

// ------------------------------------------------------------------
// small local util (no date-fns)
// ------------------------------------------------------------------
function formatToDDMMYYYY(date: Date | undefined) {
  if (!date) return "";
  const d = date.getDate().toString().padStart(2, "0");
  const m = (date.getMonth() + 1).toString().padStart(2, "0");
  const y = date.getFullYear();
  return `${d}/${m}/${y}`;
}

// ------------------------------------------------------------------
// STATIC DROPDOWN OPTIONS (can later be replaced with API-driven lists)
// ------------------------------------------------------------------
const STATIC_ORIGINS = [
  "Madurai Airport",
  "Chennai International Airport",
  "Chennai Domestic Airport",
  "Cochin Airport",
];

const STATIC_DESTINATIONS = [
  "Trivandrum, Domestic Airport",
  "Chennai",
  "Cochin Airport",
  "Chennai Domestic Airport",
];

const STATIC_AGENTS = ["admindvi", "agent1", "agent2"];
const STATIC_STAFFS = ["staff1", "staff2", "staff3"];

// ------------------------------------------------------------------
// COMPONENT
// ------------------------------------------------------------------
export const LatestItinerary = () => {
  // table state
  const [rows, setRows] = useState<any[]>([]);
  const [total, setTotal] = useState(0);

  // ui state
  const [entriesPerPage, setEntriesPerPage] = useState("10");
  const [currentPage, setCurrentPage] = useState(1);
  const [searchQuery, setSearchQuery] = useState("");

  // dropdown options (currently static – later can come from API)
  const [origins] = useState<string[]>(STATIC_ORIGINS);
  const [destinations] = useState<string[]>(STATIC_DESTINATIONS);
  const [agents] = useState<string[]>(STATIC_AGENTS);
  const [staffs] = useState<string[]>(STATIC_STAFFS);

  // date objects for calendar
  const [startDateObj, setStartDateObj] = useState<Date | undefined>(
    undefined,
  );
  const [endDateObj, setEndDateObj] = useState<Date | undefined>(
    undefined,
  );

  // filters -> to be sent to API
  const [filters, setFilters] = useState({
    origin: "",
    destination: "",
    agentName: "",
    agentStaff: "",
    startDate: "",
    endDate: "",
  });

  // sort (UI only for now; backend always sorts by latest plan ID desc)
  const [sortConfig, setSortConfig] = useState<{
    field:
      | "sno"
      | "quoteId"
      | "arrival"
      | "departure"
      | "createdBy"
      | "startDate"
      | "endDate"
      | "createdOn"
      | "nights"
      | "persons";
    dir: "asc" | "desc";
  }>({
    field: "sno",
    dir: "asc",
  });

  // fetch whenever deps change
  useEffect(() => {
    const load = async () => {
      const pageSize = Number(entriesPerPage);

      const res: any = await ItineraryService.getLatest({
        page: currentPage,
        pageSize,
        search: searchQuery,
        startDate: filters.startDate || undefined,
        endDate: filters.endDate || undefined,
        sourceLocation: filters.origin || undefined,
        destinationLocation: filters.destination || undefined,
        // agentId / staffId wiring can be added later when you have real IDs
        agentId: undefined,
        staffId: undefined,
      });

      // API shape from service:
      // {
      //   draw,
      //   recordsTotal,
      //   recordsFiltered,
      //   data: [
      //     {
      //       counter,
      //       modify,
      //       itinerary_quote_ID,
      //       itinerary_booking_ID,
      //       arrival_location,
      //       departure_location,
      //       itinerary_preference,
      //       no_of_days_and_nights, // "N& D"
      //       no_of_person,          // HTML span
      //       trip_start_date_and_time,
      //       trip_end_date_and_time,
      //       total_adult,
      //       total_children,
      //       total_infants,
      //       username,
      //       createdon,
      //     }
      //   ]
      // }

      const totalRecords = res?.recordsFiltered ?? res?.recordsTotal ?? 0;
      setTotal(totalRecords);

      const mapped =
        (res?.data ?? []).map((r: any) => {
          const quoteId =
            r.itinerary_quote_ID || r.itinerary_booking_ID || "";

          const ndStr = String(r.no_of_days_and_nights ?? "0&0");
          const [nightsStr] = ndStr.split("&");
          const nights = Number(nightsStr) || 0;

          const total_adult = Number(r.total_adult ?? 0) || 0;
          const total_children = Number(r.total_children ?? 0) || 0;
          const total_infants = Number(r.total_infants ?? 0) || 0;
          const persons =
            total_adult + total_children + total_infants;

          return {
            id: Number(r.modify ?? 0) || 0,
            quoteId,
            arrival: r.arrival_location ?? "",
            departure: r.departure_location ?? "",
            createdBy: r.username ?? "",
            startDate: r.trip_start_date_and_time ?? "",
            endDate: r.trip_end_date_and_time ?? "",
            createdOn: r.createdon ?? "",
            nights,
            persons,
          };
        }) ?? [];

      setRows(mapped);
    };

    load();
  }, [
    currentPage,
    entriesPerPage,
    searchQuery,
    sortConfig, // kept for future server-side sort if needed
    filters.origin,
    filters.destination,
    filters.agentName,
    filters.agentStaff,
    filters.startDate,
    filters.endDate,
  ]);

  const totalPages =
    total === 0 ? 1 : Math.ceil(total / Number(entriesPerPage));

  const handleChangePage = (page: number) => {
    if (page < 1 || page > totalPages) return;
    setCurrentPage(page);
  };

  const getPageNumbers = () => {
    const pages: (number | string)[] = [];
    const MAX = 5;
    if (totalPages <= MAX + 1) {
      for (let i = 1; i <= totalPages; i++) pages.push(i);
    } else {
      for (let i = 1; i <= MAX; i++) pages.push(i);
      pages.push("...");
      pages.push(totalPages);
    }
    return pages;
  };

  const toggleSort = (
    field:
      | "sno"
      | "quoteId"
      | "arrival"
      | "departure"
      | "createdBy"
      | "startDate"
      | "endDate"
      | "createdOn"
      | "nights"
      | "persons",
  ) => {
    setSortConfig((prev) => {
      if (prev.field === field) {
        const nextDir = prev.dir === "asc" ? "desc" : "asc";
        return { field, dir: nextDir };
      }
      return { field, dir: "asc" };
    });
    // For now backend sort is fixed (latest first); this is just UI state.
    setCurrentPage(1);
  };

  const renderSortIcon = (field: typeof sortConfig.field) => {
    const active = sortConfig.field === field;
    const up = sortConfig.dir === "asc";
    return (
      <span
        className={`inline-block ml-1 text-[10px] ${
          active ? "text-[#d546ab]" : "text-[#c1b6d4]"
        }`}
      >
        {up ? "▲" : "▼"}
      </span>
    );
  };

  const startItem =
    total === 0 ? 0 : (currentPage - 1) * Number(entriesPerPage) + 1;
  const endItem = Math.min(currentPage * Number(entriesPerPage), total);

  const handleClearFilters = () => {
    setFilters({
      origin: "",
      destination: "",
      agentName: "",
      agentStaff: "",
      startDate: "",
      endDate: "",
    });
    setStartDateObj(undefined);
    setEndDateObj(undefined);
    setCurrentPage(1);
  };

  return (
    <div className="w-full max-w-full space-y-6">
      {/* FILTER CARD */}
      <Card className="border-none shadow-none bg-white">
        <CardContent className="pt-6">
          <h2 className="text-base font-semibold mb-4 text-[#4a4260]">
            FILTER
          </h2>
          {/* 6 fields like PHP */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            {/* Start Date (calendar) */}
            <div className="space-y-2">
              <Label className="text-sm text-[#4a4260]">
                Start Date
              </Label>
              <Popover>
                <PopoverTrigger asChild>
                  <Button
                    variant="outline"
                    className={`w-full justify-start h-9 text-left font-normal ${
                      !filters.startDate ? "text-muted-foreground" : ""
                    }`}
                  >
                    <CalendarIcon className="mr-2 h-4 w-4" />
                    {filters.startDate || "DD/MM/YYYY"}
                  </Button>
                </PopoverTrigger>
                <PopoverContent className="p-0" align="start">
                  <Calendar
                    mode="single"
                    selected={startDateObj}
                    onSelect={(date) => {
                      setStartDateObj(date ?? undefined);
                      const formatted =
                        formatToDDMMYYYY(date ?? undefined);
                      setFilters((p) => ({
                        ...p,
                        startDate: formatted,
                      }));
                      setCurrentPage(1);
                    }}
                    initialFocus
                  />
                </PopoverContent>
              </Popover>
            </div>

            {/* End Date (calendar) */}
            <div className="space-y-2">
              <Label className="text-sm text-[#4a4260]">
                End Date
              </Label>
              <Popover>
                <PopoverTrigger asChild>
                  <Button
                    variant="outline"
                    className={`w-full justify-start h-9 text-left font-normal ${
                      !filters.endDate ? "text-muted-foreground" : ""
                    }`}
                  >
                    <CalendarIcon className="mr-2 h-4 w-4" />
                    {filters.endDate || "DD/MM/YYYY"}
                  </Button>
                </PopoverTrigger>
                <PopoverContent className="p-0" align="start">
                  <Calendar
                    mode="single"
                    selected={endDateObj}
                    onSelect={(date) => {
                      setEndDateObj(date ?? undefined);
                      const formatted =
                        formatToDDMMYYYY(date ?? undefined);
                      setFilters((p) => ({
                        ...p,
                        endDate: formatted,
                      }));
                      setCurrentPage(1);
                    }}
                    initialFocus
                  />
                </PopoverContent>
              </Popover>
            </div>

            {/* Origin */}
            <div className="space-y-2">
              <Label className="text-sm text-[#4a4260]">Origin</Label>
              <Select
                value={filters.origin}
                onValueChange={(v) => {
                  setFilters((p) => ({ ...p, origin: v }));
                  setCurrentPage(1);
                }}
              >
                <SelectTrigger className="h-9">
                  <SelectValue placeholder="Choose Location" />
                </SelectTrigger>
                <SelectContent>
                  {origins.map((o) => (
                    <SelectItem key={o} value={o}>
                      {o}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            {/* Destination */}
            <div className="space-y-2">
              <Label className="text-sm text-[#4a4260]">
                Destination
              </Label>
              <Select
                value={filters.destination}
                onValueChange={(v) => {
                  setFilters((p) => ({ ...p, destination: v }));
                  setCurrentPage(1);
                }}
              >
                <SelectTrigger className="h-9">
                  <SelectValue placeholder="Choose Location" />
                </SelectTrigger>
                <SelectContent>
                  {destinations.map((d) => (
                    <SelectItem key={d} value={d}>
                      {d}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            {/* Agent Name */}
            <div className="space-y-2">
              <Label className="text-sm text-[#4a4260]">
                Agent Name
              </Label>
              <Select
                value={filters.agentName}
                onValueChange={(v) => {
                  setFilters((p) => ({ ...p, agentName: v }));
                  setCurrentPage(1);
                }}
              >
                <SelectTrigger className="h-9">
                  <SelectValue placeholder="Select Agent" />
                </SelectTrigger>
                <SelectContent>
                  {agents.map((a) => (
                    <SelectItem key={a} value={a}>
                      {a}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            {/* Agent Staff + Clear */}
            <div className="space-y-2 flex flex-col gap-2">
              <div>
                <Label className="text-sm text-[#4a4260]">
                  Agent Staff
                </Label>
                <Select
                  value={filters.agentStaff}
                  onValueChange={(v) => {
                    setFilters((p) => ({ ...p, agentStaff: v }));
                    setCurrentPage(1);
                  }}
                >
                  <SelectTrigger className="h-9">
                    <SelectValue placeholder="Choose the Agent Staff" />
                  </SelectTrigger>
                  <SelectContent>
                    {staffs.map((s) => (
                      <SelectItem key={s} value={s}>
                        {s}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
              <Button
                onClick={handleClearFilters}
                className="bg-[#c6c6c6] hover:bg-[#b3b3b3] text-white h-8"
              >
                Clear
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* LIST CARD */}
      <Card className="border-none shadow-none bg-white">
        <CardContent className="pt-6 pb-4">
          {/* header */}
          <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <h2 className="text-base md:text-lg font-semibold text-[#4a4260]">
              List of Itinerary{" "}
              <span className="text-[#828282] text-sm">
                (Total Itinerary Count : {total})
              </span>
            </h2>
            <Button className="bg-gradient-to-r from-[#ae3bd0] to-[#f057b8] hover:from-[#9b31bd] hover:to-[#e048a7]">
              + Add Itinerary
            </Button>
          </div>

          {/* show entries + search */}
          <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-3">
            <div className="flex items-center gap-2 text-sm">
              <span>Show</span>
              <Select
                value={entriesPerPage}
                onValueChange={(v) => {
                  setEntriesPerPage(v);
                  setCurrentPage(1);
                }}
              >
                <SelectTrigger className="w-20 h-9">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="10">10</SelectItem>
                  <SelectItem value="25">25</SelectItem>
                  <SelectItem value="50">50</SelectItem>
                  <SelectItem value="100">100</SelectItem>
                </SelectContent>
              </Select>
              <span>entries</span>
            </div>

            <div className="flex items-center gap-2">
              <Label htmlFor="search" className="text-sm">
                Search:
              </Label>
              <Input
                id="search"
                value={searchQuery}
                onChange={(e) => {
                  setSearchQuery(e.target.value);
                  setCurrentPage(1);
                }}
                className="h-9 w-52"
              />
            </div>
          </div>

          {/* table */}
          <div className="overflow-x-auto border rounded-md">
            <Table className="min-w-full">
              <TableHeader>
                <TableRow className="bg-[#fbf7ff]">
                  <TableHead
                    onClick={() => toggleSort("sno")}
                    className="w-16 text-xs font-medium text-[#4a4260] cursor-pointer select-none"
                  >
                    S.No {renderSortIcon("sno")}
                  </TableHead>
                  <TableHead
                    onClick={() => toggleSort("quoteId")}
                    className="text-xs font-medium text-[#4a4260] min-w-[220px] cursor-pointer select-none"
                  >
                    Quote ID {renderSortIcon("quoteId")}
                  </TableHead>
                  <TableHead
                    onClick={() => toggleSort("arrival")}
                    className="text-xs font-medium text-[#4a4260] min-w-[180px] cursor-pointer select-none"
                  >
                    Arrival {renderSortIcon("arrival")}
                  </TableHead>
                  <TableHead
                    onClick={() => toggleSort("departure")}
                    className="text-xs font-medium text-[#4a4260] min-w-[200px] cursor-pointer select-none"
                  >
                    Departure {renderSortIcon("departure")}
                  </TableHead>
                  <TableHead
                    onClick={() => toggleSort("createdBy")}
                    className="text-xs font-medium text-[#4a4260] cursor-pointer select-none"
                  >
                    Created By {renderSortIcon("createdBy")}
                  </TableHead>
                  <TableHead
                    onClick={() => toggleSort("startDate")}
                    className="text-xs font-medium text-[#4a4260] cursor-pointer select-none"
                  >
                    Start Date {renderSortIcon("startDate")}
                  </TableHead>
                  <TableHead
                    onClick={() => toggleSort("endDate")}
                    className="text-xs font-medium text-[#4a4260] cursor-pointer select-none"
                  >
                    End Date {renderSortIcon("endDate")}
                  </TableHead>
                  <TableHead
                    onClick={() => toggleSort("createdOn")}
                    className="text-xs font-medium text-[#4a4260] cursor-pointer select-none"
                  >
                    Created On {renderSortIcon("createdOn")}
                  </TableHead>
                  <TableHead
                    onClick={() => toggleSort("nights")}
                    className="text-xs font-medium text-[#4a4260] cursor-pointer select-none"
                  >
                    Nights &amp; Days {renderSortIcon("nights")}
                  </TableHead>
                  <TableHead
                    onClick={() => toggleSort("persons")}
                    className="text-xs font-medium text-[#4a4260] cursor-pointer select-none"
                  >
                    No of Person {renderSortIcon("persons")}
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {rows.map((itinerary, idx) => (
                  <TableRow
                    key={itinerary.id ?? idx}
                    className="hover:bg-[#fdf6ff]"
                  >
                    <TableCell className="text-sm">
                      {(currentPage - 1) * Number(entriesPerPage) +
                        idx +
                        1}
                    </TableCell>
                    <TableCell>
                      <div className="flex items-center gap-2">
                        <Link
                          to={`/itinerary-details/${itinerary.quoteId}`}
                        >
                          <div className="h-8 w-8 rounded-md bg-[#f057b8] flex items-center justify-center text-white cursor-pointer hover:bg-[#d546ab]">
                            <Eye className="h-4 w-4" />
                          </div>
                        </Link>
                        <Button
                          variant="ghost"
                          size="icon"
                          className="h-8 w-8 text-[#343434]"
                        >
                          <Copy className="h-4 w-4" />
                        </Button>
                        <Button
                          variant="ghost"
                          size="icon"
                          className="h-8 w-8 text-[#343434]"
                        >
                          <Download className="h-4 w-4" />
                        </Button>
                        <Link
                          to={`/itinerary-details/${itinerary.quoteId}`}
                        >
                          <span className="font-semibold text-[#3b2f55] hover:text-[#d546ab] cursor-pointer">
                            {itinerary.quoteId}
                          </span>
                        </Link>
                      </div>
                    </TableCell>
                    <TableCell className="text-sm">
                      {itinerary.arrival}
                    </TableCell>
                    <TableCell className="text-sm">
                      {itinerary.departure}
                    </TableCell>
                    <TableCell className="text-sm">
                      {itinerary.createdBy}
                    </TableCell>
                    <TableCell className="text-sm">
                      {itinerary.startDate}
                    </TableCell>
                    <TableCell className="text-sm">
                      {itinerary.endDate}
                    </TableCell>
                    <TableCell className="text-sm">
                      {itinerary.createdOn}
                    </TableCell>
                    <TableCell className="text-sm">
                      {itinerary.nights}N / {itinerary.nights + 1}D
                    </TableCell>
                    <TableCell className="text-sm">
                      {itinerary.persons}
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>

          {/* bottom */}
          <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 mt-4">
            <p className="text-sm text-[#4a4260]">
              Showing{" "}
              <span className="font-semibold">{startItem}</span> to{" "}
              <span className="font-semibold">{endItem}</span> of{" "}
              <span className="font-semibold">{total}</span> entries
            </p>
            <div className="flex items-center gap-1">
              <Button
                variant="outline"
                size="sm"
                onClick={() =>
                  handleChangePage(currentPage - 1)
                }
                disabled={currentPage === 1}
                className="h-8 px-3"
              >
                Previous
              </Button>
              {getPageNumbers().map((p, i) =>
                typeof p === "number" ? (
                  <Button
                    key={i}
                    variant={
                      p === currentPage ? "default" : "outline"
                    }
                    size="sm"
                    onClick={() => handleChangePage(p)}
                    className={
                      p === currentPage
                        ? "bg-[#8b43d1] hover:bg-[#7c37c1] text-white h-8 px-3"
                        : "h-8 px-3"
                    }
                  >
                    {p}
                  </Button>
                ) : (
                  <span
                    key={i}
                    className="px-2 text-sm text-[#6c6c6c]"
                  >
                    ...
                  </span>
                ),
              )}
              <Button
                variant="outline"
                size="sm"
                onClick={() =>
                  handleChangePage(currentPage + 1)
                }
                disabled={currentPage === totalPages}
                className="h-8 px-3"
              >
                Next
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  );
};
