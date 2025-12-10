// FILE: src/pages/itineraries/HotelList.tsx
import React, { useEffect, useMemo, useState } from "react";
import { useParams } from "react-router-dom";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import type {
  ItineraryHotelRow,
  ItineraryHotelTab,
} from "./ItineraryDetails";
import { ItineraryService } from "@/services/itinerary";

type HotelListProps = {
  hotels: ItineraryHotelRow[];
  hotelTabs: ItineraryHotelTab[];
  hotelRatesVisible: boolean;
  // Optional: in case you later wire an API to persist the toggle
  onToggleHotelRates?: (visible: boolean) => void;
};

// Shape of each room item coming from /itineraries/hotel_room_details
type HotelRoomDetail = {
  itineraryPlanId?: number;
  itineraryRouteId?: number;
  itineraryPlanHotelRoomDetailsId?: number;
  roomTypeName?: string;
  noOfRooms?: number;
  adultCount?: number;
  childWithBed?: number;
  childWithoutBed?: number;
  extraBedCount?: number;
  perNightAmount?: number;
  taxAmount?: number;
  totalAmount?: number;
  [key: string]: any; // keep flexible – we only use a few fields
};

const formatCurrency = (value: number | undefined | null): string => {
  const num = Number(value ?? 0);
  if (Number.isNaN(num)) return "₹ 0.00";
  return (
    "₹ " +
    num.toLocaleString("en-IN", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })
  );
};

export const HotelList: React.FC<HotelListProps> = ({
  hotels,
  hotelTabs,
  hotelRatesVisible,
  onToggleHotelRates,
}) => {
  // quoteId from route: /itinerary-details/:id
  const { id: quoteId } = useParams<{ id: string }>();

  // Active tab = current group_type from backend
  const [activeGroupType, setActiveGroupType] = useState<number | null>(null);
  // Local "Display Rates" state driven by backend flag
  const [showRates, setShowRates] = useState<boolean>(hotelRatesVisible);

  // Expanded hotel row key & loaded rooms
  const [expandedRowKey, setExpandedRowKey] = useState<string | null>(null);
  const [loadingRowKey, setLoadingRowKey] = useState<string | null>(null);
  const [roomDetails, setRoomDetails] = useState<HotelRoomDetail[]>([]);

  // Initialise active tab from backend groups
  useEffect(() => {
    if (!activeGroupType && hotelTabs && hotelTabs.length > 0) {
      setActiveGroupType(hotelTabs[0].groupType);
    }
  }, [activeGroupType, hotelTabs]);

  // Keep local switch in sync if backend changes
  useEffect(() => {
    setShowRates(hotelRatesVisible);
  }, [hotelRatesVisible]);

  // Current group's total (GRAND_TOTAL_OF_THE_HOTEL_CHARGES)
  const currentTabTotal = useMemo(() => {
    if (!hotelTabs || !hotelTabs.length || activeGroupType == null) return 0;
    const tab = hotelTabs.find((t) => t.groupType === activeGroupType);
    return tab ? tab.totalAmount : 0;
  }, [hotelTabs, activeGroupType]);

  // Filter hotel rows by groupType (tab)
  const currentHotelRows = useMemo(() => {
    if (!hotels || !hotels.length) return [];
    if (activeGroupType == null) return hotels;
    return hotels.filter((h) => h.groupType === activeGroupType);
  }, [hotels, activeGroupType]);

  // ---------- CLICK HANDLER: LOAD ROOM DETAILS & EXPAND ROW ----------
  const handleRowClick = async (hotel: ItineraryHotelRow, idx: number) => {
    const rowKey = `${hotel.groupType}-${idx}`;

    // Collapse if already open
    if (expandedRowKey === rowKey) {
      setExpandedRowKey(null);
      setRoomDetails([]);
      return;
    }

    if (!quoteId) {
      console.warn("Missing quoteId in route params");
      return;
    }

    const itineraryRouteId = (hotel as any).itineraryRouteId as
      | number
      | undefined;
    const groupType = hotel.groupType;

    setLoadingRowKey(rowKey);

    try {
      // This assumes you have a service method like:
      // ItineraryService.getHotelRoomDetails(quoteId, { itineraryRouteId, groupType })
      const resp = await ItineraryService.getHotelRoomDetails(
        quoteId
      );

      // Support both shapes: resp.rooms OR resp.data.rooms
      const rooms: HotelRoomDetail[] = Array.isArray((resp as any)?.rooms)
        ? (resp as any).rooms
        : Array.isArray((resp as any)?.data?.rooms)
        ? (resp as any).data.rooms
        : [];

      setRoomDetails(rooms);
      setExpandedRowKey(rowKey);
    } catch (err) {
      console.error("Error loading hotel room details", err);
      setRoomDetails([]);
      setExpandedRowKey(rowKey); // still expand but show "No room details"
    } finally {
      setLoadingRowKey(null);
    }
  };

  // ---------- RENDER ----------
  return (
    <Card className="border-none shadow-none bg-white">
      <CardContent className="pt-6">
        {/* Header + Display Rates toggle */}
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-lg font-semibold text-[#4a4260]">HOTEL LIST</h2>

          {/* Simple toggle using Button (no extra component imports) */}
          <Button
            variant="link"
            className="text-[#d546ab] hover:text-[#c03d9f] px-0"
            onClick={() => {
              const next = !showRates;
              setShowRates(next);
              if (onToggleHotelRates) {
                onToggleHotelRates(next);
              }
            }}
          >
            {showRates ? "Hide Rates" : "Display Rates"}
          </Button>
        </div>

        {/* Hotel Tabs – based on real backend groups */}
        <div className="flex gap-2 mb-4 overflow-x-auto">
          {hotelTabs && hotelTabs.length > 0 ? (
            hotelTabs.map((tab) => {
              const isActive = tab.groupType === activeGroupType;
              return (
                <Button
                  key={tab.groupType}
                  variant={isActive ? "default" : "outline"}
                  size="sm"
                  onClick={() => {
                    setActiveGroupType(tab.groupType);
                    setExpandedRowKey(null);
                    setRoomDetails([]);
                  }}
                  className={
                    isActive
                      ? "bg-[#d546ab] hover:bg-[#c03d9f] text-white whitespace-nowrap"
                      : "border-[#e5d9f2] text-[#4a4260] whitespace-nowrap"
                  }
                >
                  {tab.label} ({formatCurrency(tab.totalAmount)})
                </Button>
              );
            })
          ) : (
            <span className="text-sm text-gray-500">No hotel groups</span>
          )}
        </div>

        {/* Hotel Table */}
        <div className="overflow-x-auto border rounded-lg">
          <table className="w-full">
            <thead className="bg-[#f8f5fc]">
              <tr>
                <th className="px-4 py-3 text-left text-sm font-medium text-[#4a4260]">
                  DAY
                </th>
                <th className="px-4 py-3 text-left text-sm font-medium text-[#4a4260]">
                  DESTINATION
                </th>
                <th className="px-4 py-3 text-left text-sm font-medium text-[#4a4260]">
                  HOTEL NAME - CATEGORY
                </th>
                <th className="px-4 py-3 text-left text-sm font-medium text-[#4a4260]">
                  HOTEL ROOM TYPE
                </th>
                {showRates && (
                  <th className="px-4 py-3 text-left text-sm font-medium text-[#4a4260]">
                    PRICE
                  </th>
                )}
                <th className="px-4 py-3 text-left text-sm font-medium text-[#4a4260]">
                  MEAL PLAN
                </th>
              </tr>
            </thead>
            <tbody>
              {currentHotelRows.map((hotel, idx) => {
                const rowKey = `${hotel.groupType}-${idx}`;
                const isExpanded = expandedRowKey === rowKey;
                const rowTotal =
                  (hotel.totalHotelCost ?? 0) +
                  (hotel.totalHotelTaxAmount ?? 0);

                return (
                  <React.Fragment key={rowKey}>
                    {/* MAIN ROW */}
                    <tr
                      className="border-t cursor-pointer hover:bg-[#f8f5fc]"
                      onClick={() => handleRowClick(hotel, idx)}
                    >
                      <td className="px-4 py-3 text-sm text-[#6c6c6c]">
                        {hotel.day}
                      </td>
                      <td className="px-4 py-3 text-sm text-[#6c6c6c]">
                        {hotel.destination}
                      </td>
                      <td className="px-4 py-3 text-sm text-[#6c6c6c]">
                        {hotel.hotelName
                          ? hotel.category
                            ? `${hotel.hotelName} -${hotel.category}*`
                            : hotel.hotelName
                          : "-"}
                      </td>
                      <td className="px-4 py-3 text-sm text-[#6c6c6c]">
                        {hotel.roomType || "-"}
                      </td>
                      {showRates && (
                        <td className="px-4 py-3 text-sm text-[#6c6c6c]">
                          {formatCurrency(rowTotal)}
                        </td>
                      )}
                      <td className="px-4 py-3 text-sm text-[#6c6c6c]">
                        {hotel.mealPlan || "-"}
                      </td>
                    </tr>

                    {/* EXPANDED ROW WITH ROOM CARDS */}
                    {isExpanded && (
                      <tr className="bg-[#fdf6ff] border-t">
                        <td
                          colSpan={showRates ? 6 : 5}
                          className="px-4 py-3 text-sm text-[#4a4260]"
                        >
                          {loadingRowKey === rowKey ? (
                            <div className="text-center py-4 text-[#6c6c6c]">
                              Loading room details…
                            </div>
                          ) : roomDetails.length === 0 ? (
                            <div className="text-center py-4 text-[#6c6c6c]">
                              No room details available for this day.
                            </div>
                          ) : (
                            <div className="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                              {roomDetails.map((room) => (
                                <div
                                  key={
                                    room.itineraryPlanHotelRoomDetailsId ??
                                    `${room.itineraryRouteId}-${room.roomTypeName}-${room.totalAmount}`
                                  }
                                  className="bg-white rounded-lg shadow-sm border border-[#e5d9f2] p-3"
                                >
                                  <div className="text-sm font-semibold text-[#4a4260] mb-2">
                                    {room.roomTypeName || "Room"}
                                  </div>
                                  <div className="text-xs text-[#6c6c6c] space-y-1">
                                    <div>
                                      Rooms:{" "}
                                      <span className="font-medium">
                                        {room.noOfRooms ?? 0}
                                      </span>
                                    </div>
                                    <div>
                                      Adults:{" "}
                                      <span className="font-medium">
                                        {room.adultCount ?? 0}
                                      </span>
                                    </div>
                                    <div>
                                      Child (with bed):{" "}
                                      <span className="font-medium">
                                        {room.childWithBed ?? 0}
                                      </span>
                                    </div>
                                    <div>
                                      Child (w/o bed):{" "}
                                      <span className="font-medium">
                                        {room.childWithoutBed ?? 0}
                                      </span>
                                    </div>
                                    <div>
                                      Extra bed:{" "}
                                      <span className="font-medium">
                                        {room.extraBedCount ?? 0}
                                      </span>
                                    </div>
                                    <div>
                                      Per night:{" "}
                                      <span className="font-medium">
                                        {formatCurrency(room.perNightAmount)}
                                      </span>
                                    </div>
                                    <div>
                                      Tax:{" "}
                                      <span className="font-medium">
                                        {formatCurrency(room.taxAmount)}
                                      </span>
                                    </div>
                                    <div className="pt-1 border-t mt-1">
                                      Total:{" "}
                                      <span className="font-semibold">
                                        {formatCurrency(room.totalAmount)}
                                      </span>
                                    </div>
                                  </div>
                                </div>
                              ))}
                            </div>
                          )}
                        </td>
                      </tr>
                    )}
                  </React.Fragment>
                );
              })}

              {/* Hotel Total row for active group */}
              <tr className="border-t bg-[#fdf6ff]">
                <td
                  colSpan={showRates ? 4 : 3}
                  className="px-4 py-3 text-sm font-medium text-[#4a4260] text-right"
                >
                  Hotel Total :
                </td>
                <td className="px-4 py-3 text-sm font-semibold text-[#4a4260]">
                  {formatCurrency(currentTabTotal)}
                </td>
                {!showRates && (
                  <td className="px-4 py-3 text-sm font-semibold text-[#4a4260]" />
                )}
              </tr>
            </tbody>
          </table>
        </div>
      </CardContent>
    </Card>
  );
};
