// FILE: src/pages/AccountsLedger.tsx

import React, { useEffect, useMemo, useRef, useState } from "react";
import { Download, Calendar as CalendarIcon } from "lucide-react";

// ✅ shadcn components (same as AccountsManager / LatestItinerary)
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
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Calendar } from "@/components/ui/calendar";

// 🔌 Ledger service + types
import {
  fetchLedgerFromApi,
  fetchLedgerFilterOptions,
  ComponentType,
  LedgerRow,
} from "@/services/accountsLedgerApi";

const formatINR = (v: number) =>
  new Intl.NumberFormat("en-IN", {
    style: "currency",
    currency: "INR",
    minimumFractionDigits: 2,
  }).format(v);

// ──────────────────────────────────────────────
// small utils
// ─────────────────────────────────────────────-
function formatToDDMMYYYY(date: Date | undefined) {
  if (!date) return "";
  const d = date.getDate().toString().padStart(2, "0");
  const m = (date.getMonth() + 1).toString().padStart(2, "0");
  const y = date.getFullYear();
  return `${d}/${m}/${y}`;
}

function ddmmyyyyToIso(d: string): string {
  // "03/10/2025" -> "2025-10-03"
  if (!d) return "";
  const [day, month, year] = d.split("/");
  if (!day || !month || !year) return "";
  return `${year}-${month}-${day}`;
}

// ──────────────────────────────────────────────
// COMPONENT
// ─────────────────────────────────────────────-
export const AccountsLedger: React.FC = () => {
  // 👇 now all typed
  const [quoteId, setQuoteId] = useState<string>("");

  const [componentType, setComponentType] = useState<ComponentType>("vehicle");

  // we store both: real Date (for calendar) + string (DD/MM/YYYY) for button
  const [fromDateObj, setFromDateObj] = useState<Date | undefined>(
    new Date("2025-10-03")
  );
  const [toDateObj, setToDateObj] = useState<Date | undefined>(
    new Date("2025-11-02")
  );
  const [fromDate, setFromDate] = useState<string>("03/10/2025");
  const [toDate, setToDate] = useState<string>("02/11/2025");

  // conditional fields (selected values)
  const [guideName, setGuideName] = useState<string>("All");
  const [hotspotName, setHotspotName] = useState<string>("All");
  const [activityName, setActivityName] = useState<string>("All");
  const [hotelName, setHotelName] = useState<string>("All");

  // vehicle layout
  const [branch, setBranch] = useState<string>("All");
  const [vehicle, setVehicle] = useState<string>("All");
  const [vehicleVendor, setVehicleVendor] = useState<string>("All");

  // agent filter
  const [agentName, setAgentName] = useState<string>("All");

  // DROPDOWN OPTIONS (dynamic, from backend)
  const [guideOptions, setGuideOptions] = useState<string[]>(["All"]);
  const [hotspotOptions, setHotspotOptions] = useState<string[]>(["All"]);
  const [activityOptions, setActivityOptions] = useState<string[]>(["All"]);
  const [hotelOptions, setHotelOptions] = useState<string[]>(["All"]);
  const [branchOptions, setBranchOptions] = useState<string[]>(["All"]);
  const [vehicleOptions, setVehicleOptions] = useState<string[]>(["All"]);
  const [vendorOptions, setVendorOptions] = useState<string[]>(["All"]);
  const [agentOptions, setAgentOptions] = useState<string[]>(["All"]);

  // fetched rows
  const [rows, setRows] = useState<LedgerRow[]>([]);
  const [loading, setLoading] = useState<boolean>(false);

  // infinite scroll
  const [visibleCount, setVisibleCount] = useState<number>(25);
  const listRef = useRef<HTMLDivElement | null>(null);

  // fetch ledger rows when filters change
  useEffect(() => {
    let cancelled = false;
    (async () => {
      setLoading(true);
      try {
        const data = await fetchLedgerFromApi({
          quoteId,
          componentType,
          fromDate,
          toDate,
          guideName,
          hotspotName,
          activityName,
          hotelName,
          branch,
          vehicle,
          vehicleVendor,
          agentName,
        });
        if (!cancelled) {
          setRows(data);
          setVisibleCount(25);
        }
      } catch (err) {
        console.error("Error fetching ledger:", err);
        if (!cancelled) {
          setRows([]);
        }
      } finally {
        if (!cancelled) {
          setLoading(false);
        }
      }
    })();
    return () => {
      cancelled = true;
    };
  }, [
    quoteId,
    componentType,
    fromDate,
    toDate,
    guideName,
    hotspotName,
    activityName,
    hotelName,
    branch,
    vehicle,
    vehicleVendor,
    agentName,
  ]);

  // fetch dynamic dropdown options (like PHP: based on current filters)
  useEffect(() => {
    let cancelled = false;
    (async () => {
      try {
        const opts = await fetchLedgerFilterOptions({
          quoteId,
          componentType,
          fromDate,
          toDate,
        });
        if (cancelled) return;

        setGuideOptions(opts.guides);
        setHotspotOptions(opts.hotspots);
        setActivityOptions(opts.activities);
        setHotelOptions(opts.hotels);
        setBranchOptions(opts.vehicleBranches);
        setVehicleOptions(opts.vehicles);
        setVendorOptions(opts.vendors);
        setAgentOptions(opts.agents);

        // Ensure selected values always exist
        if (!opts.agents.includes(agentName)) setAgentName("All");
        if (!opts.guides.includes(guideName)) setGuideName("All");
        if (!opts.hotspots.includes(hotspotName)) setHotspotName("All");
        if (!opts.activities.includes(activityName)) setActivityName("All");
        if (!opts.hotels.includes(hotelName)) setHotelName("All");
        if (!opts.vehicleBranches.includes(branch)) setBranch("All");
        if (!opts.vehicles.includes(vehicle)) setVehicle("All");
        if (!opts.vendors.includes(vehicleVendor)) setVehicleVendor("All");
      } catch (err) {
        console.error("Error fetching ledger filter options:", err);
        // keep existing options if request fails
      }
    })();
    return () => {
      cancelled = true;
    };
  }, [quoteId, componentType, fromDate, toDate]);

  const totals = useMemo(() => {
    const billed = rows.reduce((s, r) => s + r.totalBilled, 0);
    const received = rows.reduce((s, r) => s + r.totalReceived, 0);
    const receivable = rows.reduce((s, r) => s + r.totalReceivable, 0);
    const paid = rows.reduce((s, r) => s + r.totalPaid, 0);
    const balance = rows.reduce((s, r) => s + r.totalBalance, 0);
    return { billed, received, receivable, paid, balance };
  }, [rows]);

  // infinite scroll
  useEffect(() => {
    const el = listRef.current;
    if (!el) return;
    const onScroll = () => {
      if (el.scrollTop + el.clientHeight >= el.scrollHeight - 150) {
        setVisibleCount((prev) => Math.min(prev + 25, rows.length));
      }
    };
    el.addEventListener("scroll", onScroll);
    return () => el.removeEventListener("scroll", onScroll);
  }, [rows.length]);

  const handleClear = () => {
    setQuoteId("");
    setComponentType("vehicle");
    setFromDate("03/10/2025");
    setToDate("02/11/2025");
    setFromDateObj(new Date("2025-10-03"));
    setToDateObj(new Date("2025-11-02"));
    setGuideName("All");
    setHotspotName("All");
    setActivityName("All");
    setHotelName("All");
    setBranch("All");
    setVehicle("All");
    setVehicleVendor("All");
    setAgentName("All");
  };

  const renderRightFieldRow1 = () => {
    switch (componentType) {
      case "vehicle":
        return (
          <div className="space-y-2">
            <Label className="text-sm text-[#4a4260]">Vendor</Label>
            <Select
              value={vehicleVendor}
              onValueChange={(v) => setVehicleVendor(v)}
            >
              <SelectTrigger className="h-9">
                <SelectValue placeholder="All" />
              </SelectTrigger>
              <SelectContent>
                {vendorOptions.map((v) => (
                  <SelectItem key={v} value={v}>
                    {v}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        );
      case "agent":
        return (
          <div className="space-y-2">
            <Label className="text-sm text-[#4a4260]">Agent</Label>
            <Select value={agentName} onValueChange={(v) => setAgentName(v)}>
              <SelectTrigger className="h-9">
                <SelectValue placeholder="All" />
              </SelectTrigger>
              <SelectContent>
                {agentOptions.map((a) => (
                  <SelectItem key={a} value={a}>
                    {a}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        );
      case "guide":
        return (
          <div className="space-y-2">
            <Label className="text-sm text-[#4a4260]">Guide Name</Label>
            <Select value={guideName} onValueChange={(v) => setGuideName(v)}>
              <SelectTrigger className="h-9">
                <SelectValue placeholder="All" />
              </SelectTrigger>
              <SelectContent>
                {guideOptions.map((g) => (
                  <SelectItem key={g} value={g}>
                    {g}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        );
      case "hotspot":
        return (
          <div className="space-y-2">
            <Label className="text-sm text-[#4a4260]">Hotspot Name</Label>
            <Select value={hotspotName} onValueChange={(v) => setHotspotName(v)}>
              <SelectTrigger className="h-9">
                <SelectValue placeholder="All" />
              </SelectTrigger>
              <SelectContent>
                {hotspotOptions.map((h) => (
                  <SelectItem key={h} value={h}>
                    {h}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        );
      case "activity":
        return (
          <div className="space-y-2">
            <Label className="text-sm text-[#4a4260]">Activity Name</Label>
            <Select value={activityName} onValueChange={(v) => setActivityName(v)}>
              <SelectTrigger className="h-9">
                <SelectValue placeholder="All" />
              </SelectTrigger>
              <SelectContent>
                {activityOptions.map((h) => (
                  <SelectItem key={h} value={h}>
                    {h}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        );
      case "hotel":
        return (
          <div className="space-y-2">
            <Label className="text-sm text-[#4a4260]">Hotel Name</Label>
            <Select value={hotelName} onValueChange={(v) => setHotelName(v)}>
              <SelectTrigger className="h-9">
                <SelectValue placeholder="All" />
              </SelectTrigger>
              <SelectContent>
                {hotelOptions.map((h) => (
                  <SelectItem key={h} value={h}>
                    {h}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        );
      default:
        return <div />;
    }
  };

  return (
    <div className="w-full min-h-screen bg-[#fbeef8] p-4 md:p-6">
      {/* FILTER CARD */}
      <div className="bg-[#fefefe]/40 rounded-xl border border-[#f6dfff] mb-5">
        <div className="px-6 py-5">
          <p className="text-sm font-semibold text-[#4a4260] mb-4">FILTER</p>

          {/* ROW 1 */}
          <div className="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            {/* Quote ID */}
            <div className="space-y-2">
              <Label className="text-sm text-[#4a4260]">Quote ID</Label>
              <Input
                value={quoteId}
                onChange={(e) => setQuoteId(e.target.value)}
                placeholder="Enter the Quote ID"
                className="h-9"
              />
            </div>

            {/* Component Type */}
            <div className="space-y-2">
              <Label className="text-sm text-[#4a4260]">Component Type</Label>
              <Select
                value={componentType}
                onValueChange={(v) => setComponentType(v as ComponentType)}
              >
                <SelectTrigger className="h-9">
                  <SelectValue placeholder="Select Component" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="vehicle">Vehicle</SelectItem>
                  <SelectItem value="agent">Agent</SelectItem>
                  <SelectItem value="guide">Guide</SelectItem>
                  <SelectItem value="hotspot">Hotspot</SelectItem>
                  <SelectItem value="activity">Activity</SelectItem>
                  <SelectItem value="hotel">Hotel</SelectItem>
                  <SelectItem value="all">All</SelectItem>
                </SelectContent>
              </Select>
            </div>

            {/* From Date */}
            <div className="space-y-2">
              <Label className="text-sm text-[#4a4260]">From Date</Label>
              <Popover>
                <PopoverTrigger asChild>
                  <Button
                    variant="outline"
                    className={`w-full justify-start h-9 text-left font-normal ${
                      !fromDate ? "text-muted-foreground" : ""
                    } bg-white border border-[#f0d8ff] text-[#4a4260]`}
                  >
                    <CalendarIcon className="mr-2 h-4 w-4" />
                    {fromDate || "DD/MM/YYYY"}
                  </Button>
                </PopoverTrigger>
                <PopoverContent className="p-0" align="start">
                  <Calendar
                    mode="single"
                    selected={fromDateObj}
                    onSelect={(date) => {
                      setFromDateObj(date ?? undefined);
                      const formatted = formatToDDMMYYYY(date ?? undefined);
                      setFromDate(formatted);
                    }}
                    initialFocus
                  />
                </PopoverContent>
              </Popover>
            </div>

            {/* To Date */}
            <div className="space-y-2">
              <Label className="text-sm text-[#4a4260]">To Date</Label>
              <Popover>
                <PopoverTrigger asChild>
                  <Button
                    variant="outline"
                    className={`w-full justify-start h-9 text-left font-normal ${
                      !toDate ? "text-muted-foreground" : ""
                    } bg-white border border-[#f0d8ff] text-[#4a4260]`}
                  >
                    <CalendarIcon className="mr-2 h-4 w-4" />
                    {toDate || "DD/MM/YYYY"}
                  </Button>
                </PopoverTrigger>
                <PopoverContent className="p-0" align="start">
                  <Calendar
                    mode="single"
                    selected={toDateObj}
                    onSelect={(date) => {
                      setToDateObj(date ?? undefined);
                      const formatted = formatToDDMMYYYY(date ?? undefined);
                      setToDate(formatted);
                    }}
                    initialFocus
                  />
                </PopoverContent>
              </Popover>
            </div>

            {/* right dynamic field */}
            {renderRightFieldRow1()}
          </div>

          {/* ROW 2 — VEHICLE */}
          {componentType === "vehicle" && (
            <div className="grid grid-cols-1 md:grid-cols-5 gap-4 items-end mt-4">
              {/* Branch */}
              <div className="space-y-2">
                <Label className="text-sm text-[#4a4260]">Branch</Label>
                <Select value={branch} onValueChange={(v) => setBranch(v)}>
                  <SelectTrigger className="h-9">
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    {branchOptions.map((b) => (
                      <SelectItem key={b} value={b}>
                        {b}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>

              {/* Vehicle */}
              <div className="space-y-2">
                <Label className="text-sm text-[#4a4260]">Vehicle</Label>
                <Select value={vehicle} onValueChange={(v) => setVehicle(v)}>
                  <SelectTrigger className="h-9">
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    {vehicleOptions.map((val) => (
                      <SelectItem key={val} value={val}>
                        {val}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>

              {/* spacer */}
              <div className="hidden md:block md:col-span-2" />

              {/* Clear */}
              <div className="flex md:justify-end">
                <Button
                  onClick={handleClear}
                  className="h-9 px-6 bg-[#f057b8] hover:bg-[#df43a6] text-white text-sm font-medium"
                >
                  Clear
                </Button>
              </div>
            </div>
          )}

          {/* NON vehicle clear */}
          {componentType !== "vehicle" && (
            <div className="flex justify-end mt-4">
              <Button
                onClick={handleClear}
                className="h-9 px-6 bg-[#f057b8] hover:bg-[#df43a6] text-white text-sm font-medium"
              >
                Clear
              </Button>
            </div>
          )}
        </div>
      </div>

      {/* SUMMARY CARDS */}
      <div className="grid grid-cols-1 md:grid-cols-5 gap-4 mb-5">
        <div className="bg-white rounded-md shadow-sm py-4 px-5">
          <p className="text-sm text-[#8a7da5] mb-1">Total Billed</p>
          <p className="text-xl font-semibold text-[#3d3551]">
            {formatINR(totals.billed)}
          </p>
        </div>
        <div className="bg-white rounded-md shadow-sm py-4 px-5">
          <p className="text-sm text-[#8a7da5] mb-1">Total Received</p>
          <p className="text-xl font-semibold text-[#3d3551]">
            {formatINR(totals.received)}
          </p>
        </div>
        <div className="bg-white rounded-md shadow-sm py-4 px-5">
          <p className="text-sm text-[#8a7da5] mb-1">Total Receivable</p>
          <p className="text-xl font-semibold text-[#3d3551]">
            {formatINR(totals.receivable)}
          </p>
        </div>
        <div className="bg-white rounded-md shadow-sm py-4 px-5">
          <p className="text-sm text-[#8a7da5] mb-1">Total Paid</p>
          <p className="text-xl font-semibold text-[#3d3551]">
            {formatINR(totals.paid)}
          </p>
        </div>
        <div className="bg-white rounded-md shadow-sm py-4 px-5">
          <p className="text-sm text-[#8a7da5] mb-1">Total Balance</p>
          <p className="text-xl font-semibold text-[#10a037]">
            {formatINR(totals.balance)}
          </p>
        </div>
      </div>

      {/* LIST */}
      <div className="bg-white/70 rounded-xl border border-[#f6dfff]">
        <div className="flex items-center justify-between px-6 pt-5 pb-3">
          <p className="text-sm font-semibold text-[#4a4260]">
            {componentType === "agent" && "List of Agent"}
            {componentType === "vehicle" && "List of Vehicle"}
            {componentType === "hotel" && "List of Hotel"}
            {componentType === "guide" && "List of Guide"}
            {componentType === "hotspot" && "List of Hotspot"}
            {componentType === "activity" && "List of Activity"}
            {componentType === "all" && "List of All Components"}
          </p>
          <Button className="h-9 px-4 gap-2 rounded-md bg-[#e5fff1] border border-[#b7f7d9] text-[#0f9c34] text-sm flex items-center">
            <Download className="h-4 w-4" />
            Export
          </Button>
        </div>

        <div
          ref={listRef}
          className="max-h-[460px] overflow-y-auto border-t border-[#f3e0ff]"
        >
          <table className="min-w-full text-sm">
            <thead className="bg-[#fbf2ff] sticky top-0 z-10">
              <tr>
                <th className="text-left px-6 py-3 text-xs text-[#4a4260]">
                  BOOKING ID
                </th>
                <th className="text-left px-3 py-3 text-xs text-[#4a4260]">
                  AGENT NAME
                </th>
                <th className="text-right px-3 py-3 text-xs text-[#4a4260]">
                  TOTAL BILLED
                </th>
                <th className="text-right px-3 py-3 text-xs text-[#4a4260]">
                  TOTAL RECEIVED
                </th>
                <th className="text-right px-3 py-3 text-xs text-[#4a4260]">
                  TOTAL RECEIVABLE
                </th>
                <th className="text-right px-3 py-3 text-xs text-[#4a4260]">
                  TOTAL PAID
                </th>
                <th className="text-right px-3 py-3 text-xs text-[#4a4260]">
                  TOTAL BALANCE
                </th>
                <th className="text-left px-3 py-3 text-xs text-[#4a4260]">
                  GUEST
                </th>
                <th className="text-left px-3 py-3 text-xs text-[#4a4260]">
                  ARRIVAL
                </th>
                <th className="text-left px-3 py-3 text-xs text-[#4a4260]">
                  START DATE
                </th>
                <th className="text-left px-3 py-3 text-xs text-[#4a4260]">
                  END DATE
                </th>
              </tr>
            </thead>
            <tbody>
              {loading ? (
                <tr>
                  <td colSpan={11} className="text-center py-10 text-xs">
                    Loading records…
                  </td>
                </tr>
              ) : rows.length === 0 ? (
                <tr>
                  <td
                    colSpan={11}
                    className="text-center py-16 text-[#f4008f] text-sm"
                  >
                    No data Found
                  </td>
                </tr>
              ) : (
                rows.slice(0, visibleCount).map((row) => (
                  <tr key={row.id} className="hover:bg-[#fff7ff]">
                    <td className="px-6 py-2 text-[#7032c8] font-medium">
                      {row.bookingId}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.agentName}
                    </td>
                    <td className="px-3 py-2 text-right text-[#4a4260]">
                      {formatINR(row.totalBilled)}
                    </td>
                    <td className="px-3 py-2 text-right text-[#4a4260]">
                      {formatINR(row.totalReceived)}
                    </td>
                    <td className="px-3 py-2 text-right text-[#4a4260]">
                      {formatINR(row.totalReceivable)}
                    </td>
                    <td className="px-3 py-2 text-right text-[#4a4260]">
                      {formatINR(row.totalPaid)}
                    </td>
                    <td className="px-3 py-2 text-right text-[#4a4260]">
                      {formatINR(row.totalBalance)}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">{row.guest}</td>
                    <td className="px-3 py-2 text-[#4a4260]">{row.arrival}</td>
                    <td className="px-3 py-2 text-[#4a4260]">
                      {row.startDate}
                    </td>
                    <td className="px-3 py-2 text-[#4a4260]">{row.endDate}</td>
                  </tr>
                ))
              )}

              {!loading &&
                rows.length > 0 &&
                visibleCount < rows.length && (
                  <tr>
                    <td colSpan={11} className="text-center py-4 text-xs">
                      Loading more…
                    </td>
                  </tr>
                )}

              {!loading &&
                rows.length > 0 &&
                visibleCount >= rows.length && (
                  <tr>
                    <td colSpan={11} className="text-center py-4 text-xs">
                      All rows loaded
                    </td>
                  </tr>
                )}
            </tbody>
          </table>
        </div>

        <div className="py-4 text-center text-xs text-[#a593c7]">
          DVI Holidays @ 2025
        </div>
      </div>
    </div>
  );
};
