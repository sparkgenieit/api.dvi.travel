// FILE: src/pages/itineraries/ItineraryDetails.tsx

import React, { useEffect, useState } from "react";
import { useParams, Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { ArrowLeft, Clock, MapPin, Car, Calendar, Plus } from "lucide-react";
import { ItineraryService } from "@/services/itinerary";
import { VehicleList } from "./VehicleList";
import { HotelList } from "./HotelList";

// --------- Types aligned with CURRENT API RESPONSE ---------

type StartSegment = {
  type: "start";
  title: string;
  timeRange: string; // "12:00 AM - 12:00 AM"
};

type TravelSegment = {
  type: "travel";
  from: string;
  to: string;
  timeRange: string; // "06:30 AM - 06:45 AM"
  distance: string;
  duration: string; // "15 Min"
  note?: string | null;
};

type AttractionSegment = {
  type: "attraction";
  name: string;
  description: string;
  visitTime: string; // "06:45 AM - 08:45 AM"
  duration: string; // "2 Hours"
  image: string | null;
};

type HotspotSegment = {
  type: "hotspot";
  text: string;
};

type ReturnSegment = {
  type: "return";
  time: string; // "08:00 PM"
  note?: string | null;
};

type ItinerarySegment =
  | StartSegment
  | TravelSegment
  | AttractionSegment
  | HotspotSegment
  | ReturnSegment;

type ItineraryDay = {
  id: number;
  dayNumber: number;
  date: string; // ISO
  departure: string | null;
  arrival: string | null;
  distance: string;
  startTime: string; // "12:00 PM"
  endTime: string; // "08:00 PM"
  segments: ItinerarySegment[];
};

// --------- HOTELS (matches backend DTO) ---------

export type ItineraryHotelRow = {
  groupType: number;
  day: string;
  destination: string;
  hotelName: string;
  category: number | string;
  roomType: string;
  mealPlan: string;
  totalHotelCost: number;
  totalHotelTaxAmount: number;
};

export type ItineraryHotelTab = {
  groupType: number;
  label: string;
  totalAmount: number;
};

// --------- VEHICLES ---------

type VehicleCostBreakdownItem = {
  label: string;
  amount: string | number;
};

export type ItineraryVehicleRow = {
  vendorName: string | null;
  branchName: string | null;
  vehicleOrigin: string | null;
  totalQty: string;
  totalAmount: string;

  // per-vehicle charges (optional; fill from API)
  rentalCharges?: number | string;
  tollCharges?: number | string;
  parkingCharges?: number | string;
  driverCharges?: number | string;
  permitCharges?: number | string;
  before6amDriver?: number | string;
  before6amVendor?: number | string;
  after8pmDriver?: number | string;
  after8pmVendor?: number | string;
  breakdown?: VehicleCostBreakdownItem[];

  // UI fields for the image + distance row
  dayLabel?: string; // "Day-1 | 28 Nov 2025 | Outstation"
  fromLabel?: string; // "CHENNAI INTERNATIONAL AIRPORT"
  toLabel?: string; // "CHENNAI"
  packageLabel?: string; // "Outstation - 250KM"
  col1Distance?: string; // "30.22 KM"
  col1Duration?: string; // "0 Min"
  col2Distance?: string; // "0.00 KM"
  col2Duration?: string; // "0 Min"
  col3Distance?: string; // "30.22 KM"
  col3Duration?: string; // "0 Min"
  imageUrl?: string | null; // vehicle image if you ever have it
};

type PackageIncludes = {
  description: string | null;
  houseBoatNote: string | null;
  rateNote: string | null;
};

type CostBreakdown = {
  totalVehicleCost: number | null;
  totalVehicleAmount: number | null;
  additionalMargin: number | null;
  totalAmount: number | null;
  couponDiscount: number | null;
  agentMargin: number | null;
  totalRoundOff: number | null;
  netPayable: number | null;
  companyName: string | null;
};

// ----------------- Main API response types -----------------

type ItineraryDetailsResponse = {
  // planId for routing back to create-itinerary
  planId?: number;
  quoteId: string;
  dateRange: string;
  roomCount: number;
  extraBed: number;
  childWithBed: number;
  childWithoutBed: number;
  adults: number;
  children: number;
  infants: number;
  overallCost: string | number; // API is giving "15000.00"

  days: ItineraryDay[];

  // VEHICLES
  vehicles: ItineraryVehicleRow[];

  packageIncludes: PackageIncludes;
  costBreakdown: CostBreakdown;
};

// response shape from /itineraries/hotel_details/:quoteId
type ItineraryHotelDetailsResponse = {
  hotelRatesVisible: boolean;
  hotelTabs: ItineraryHotelTab[];
  hotels: ItineraryHotelRow[];
};

// ----------------- Helper functions -----------------

const formatHeaderDate = (iso: string) => {
  const d = new Date(iso);
  if (Number.isNaN(d.getTime())) return iso;
  return d.toLocaleDateString("en-GB", {
    weekday: "short",
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
};

// ----------------- Main Component -----------------

export const ItineraryDetails: React.FC = () => {
  const { id: quoteId } = useParams();
  const [itinerary, setItinerary] = useState<ItineraryDetailsResponse | null>(
    null
  );
  const [hotelDetails, setHotelDetails] =
    useState<ItineraryHotelDetailsResponse | null>(null);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!quoteId) {
      setError("Missing quote id in URL");
      setLoading(false);
      return;
    }

    const fetchDetails = async () => {
      try {
        setLoading(true);
      setError(null);

        const [detailsRes, hotelRes] = await Promise.all([
          ItineraryService.getDetails(quoteId),
          ItineraryService.getHotelDetails(quoteId),
        ]);

        setItinerary(detailsRes as ItineraryDetailsResponse);
        setHotelDetails(hotelRes as ItineraryHotelDetailsResponse);
      } catch (e: any) {
        console.error("Failed to load itinerary details", e);
        setError("Failed to load itinerary details");
      } finally {
        setLoading(false);
      }
    };

    fetchDetails();
  }, [quoteId]);

  if (loading) {
    return (
      <div className="w-full max-w-full flex justify-center items-center py-16">
        <p className="text-sm text-[#6c6c6c]">Loading itinerary details…</p>
      </div>
    );
  }

  if (error || !itinerary) {
    return (
      <div className="w-full max-w-full flex flex-col items-center py-16 gap-4">
        <p className="text-sm text-red-600">
          {error || "Itinerary details not found"}
        </p>
        <Link to="/latest-itinerary">
          <Button
            variant="outline"
            className="border-[#d546ab] text-[#d546ab] hover:bg-[#fdf6ff]"
          >
            <ArrowLeft className="mr-2 h-4 w-4" />
            Back to Route List
          </Button>
        </Link>
      </div>
    );
  }

  const backToRouteHref = itinerary.planId
    ? `/create-itinerary?id=${itinerary.planId}`
    : "/latest-itinerary";

  return (
    <div className="w-full max-w-full space-y-6 pb-8">
      {/* Header Card */}
      <Card className="border-none shadow-none bg-white">
        <CardContent className="pt-6">
          <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <h1 className="text-xl font-semibold text-[#4a4260]">
              Tour Itinerary Plan
            </h1>
            <Link to={backToRouteHref}>
              <Button
                variant="outline"
                className="border-[#d546ab] text-[#d546ab] hover:bg-[#fdf6ff]"
              >
                <ArrowLeft className="mr-2 h-4 w-4" />
                Back to Route List
              </Button>
            </Link>
          </div>

          {/* Quote Info */}
          <div className="flex flex-col lg:flex-row justify-between gap-4 mb-4">
            <div className="flex flex-wrap items-center gap-4 text-sm">
              <div className="flex items-center gap-2">
                <Calendar className="h-4 w-4 text-[#6c6c6c]" />
                <span className="font-medium text-[#4a4260]">
                  {itinerary.quoteId}
                </span>
              </div>
              <div className="flex items-center gap-2">
                <Clock className="h-4 w-4 text-[#6c6c6c]" />
                <span className="text-[#6c6c6c]">{itinerary.dateRange}</span>
              </div>
            </div>
            <div className="text-right">
              <p className="text-sm text-[#6c6c6c]">Overall Trip Cost :</p>
              <p className="text-2xl font-bold text-[#d546ab]">
                ₹ {itinerary.overallCost}
              </p>
            </div>
          </div>

          {/* Trip Details */}
          <div className="flex flex-wrap gap-4 text-sm text-[#6c6c6c]">
            <span>Room Count: {itinerary.roomCount}</span>
            <span>Extra Bed: {itinerary.extraBed}</span>
            <span>Child with bed: {itinerary.childWithBed}</span>
            <span>Child without bed: {itinerary.childWithoutBed}</span>
            <div className="ml-auto flex gap-4">
              <span>Adults: {itinerary.adults}</span>
              <span>Child: {itinerary.children}</span>
              <span>Infants: {itinerary.infants}</span>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Daily Itinerary */}
      {itinerary.days.map((day) => (
        <Card key={day.id} className="border border-[#e5d9f2] bg-white">
          <CardContent className="pt-4">
            {/* Day Header */}
            <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-4 p-3 bg-[#f8f5fc] rounded-lg border border-[#e5d9f2]">
              <div className="flex items-center gap-3">
                <Calendar className="h-5 w-5 text-[#d546ab]" />
                <div>
                  <h3 className="font-semibold text-[#4a4260]">
                    DAY {day.dayNumber} - {formatHeaderDate(day.date)}
                  </h3>
                  <p className="text-sm text-[#6c6c6c]">
                    {day.departure}{" "}
                    <MapPin className="inline h-3 w-3 mx-1" /> {day.arrival}
                  </p>
                </div>
              </div>
              <div className="flex items-center gap-2 text-sm">
                <span className="bg-[#d546ab] text-white px-3 py-1 rounded-full">
                  {day.distance}
                </span>
              </div>
            </div>

            {/* Time Range */}
            <div className="flex items-center gap-4 mb-6 ml-2">
              <span className="text-[#d546ab] font-semibold">
                {day.startTime}
              </span>
              <div className="h-px flex-1 bg-gradient-to-r from-[#d546ab] to-transparent" />
              <span className="text-[#6c6c6c]">{day.endTime}</span>
            </div>

            {/* Segments */}
            <div className="space-y-4">
              {day.segments.map((segment, idx) => (
                <div key={idx} className="ml-8">
                  {segment.type === "start" && (
                    <div className="flex items-center gap-3 mb-4">
                      <Car className="h-5 w-5 text-[#6c6c6c]" />
                      <div>
                        <p className="font-medium text-[#4a4260]">
                          {segment.title}
                        </p>
                        <p className="text-sm text-[#6c6c6c]">
                          <Clock className="inline h-3 w-3 mr-1" />
                          {segment.timeRange}
                        </p>
                      </div>
                    </div>
                  )}

                  {segment.type === "travel" && (
                    <div className="bg-[#e8f9fd] rounded-lg p-3 mb-3">
                      <div className="flex items-start gap-3">
                        <Car className="h-5 w-5 text-[#4ba3c3] mt-1" />
                        <div className="flex-1">
                          <p className="text-sm text-[#4a4260]">
                            <span className="font-medium">Travelling from</span>{" "}
                            <span className="text-[#d546ab]">
                              {segment.from}
                            </span>{" "}
                            <span className="font-medium">to</span>{" "}
                            <span className="text-[#d546ab]">{segment.to}</span>
                          </p>
                          <div className="flex flex-wrap gap-4 mt-2 text-xs text-[#6c6c6c]">
                            <span>
                              <Clock className="inline h-3 w-3 mr-1" />
                              {segment.timeRange}
                            </span>
                            <span>
                              <MapPin className="inline h-3 w-3 mr-1" />
                              {segment.distance}
                            </span>
                            <span>⏱ {segment.duration}</span>
                          </div>
                          {segment.note && (
                            <p className="text-xs text-[#6c6c6c] mt-2">
                              <Clock className="inline h-3 w-3 mr-1" />
                              {segment.note}
                            </p>
                          )}
                        </div>
                      </div>
                    </div>
                  )}

                  {segment.type === "attraction" && (
                    <div className="bg-gradient-to-r from-[#faf5ff] to-[#f3e8ff] rounded-lg p-4 mb-3 border border-[#e5d9f2]">
                      <div className="flex flex-col sm:flex-row gap-4">
                        <img
                          src={
                            segment.image ||
                            "https://placehold.co/120x120/e9d5f7/4a4260?text=Spot"
                          }
                          alt={segment.name}
                          className="w-full sm:w-32 h-32 object-cover rounded-lg"
                        />
                        <div className="flex-1">
                          <h4 className="font-semibold text-[#4a4260] mb-2">
                            {segment.name}
                          </h4>
                          <p className="text-sm text-[#6c6c6c] mb-3">
                            {segment.description}
                          </p>
                          <div className="flex flex-wrap gap-4 text-xs text-[#6c6c6c]">
                            <span>
                              <Clock className="inline h-3 w-3 mr-1" />
                              {segment.visitTime}
                            </span>
                            <span>
                              <Clock className="inline h-3 w-3 mr-1" />
                              {segment.duration}
                            </span>
                          </div>
                        </div>
                        <div className="flex flex-col gap-2 sm:ml-auto">
                          <Button
                            size="icon"
                            variant="ghost"
                            className="h-8 w-8"
                          >
                            📷
                          </Button>
                          <Button
                            size="icon"
                            variant="ghost"
                            className="h-8 w-8"
                          >
                            🖼️
                          </Button>
                        </div>
                      </div>
                    </div>
                  )}

                  {segment.type === "hotspot" && (
                    <div className="flex items-center gap-2 mb-3 text-[#d546ab]">
                      <Plus className="h-4 w-4" />
                      <button className="text-sm hover:underline">
                        {segment.text}
                      </button>
                    </div>
                  )}

                  {segment.type === "return" && (
                    <div className="flex items-center gap-3 text-sm text-[#6c6c6c]">
                      <Car className="h-5 w-5" />
                      <div>
                        <p className="font-medium text-[#4a4260]">
                          Return to Origin and Relax
                        </p>
                        <p>
                          <Clock className="inline h-3 w-3 mr-1" />
                          {segment.time}
                          {segment.note && (
                            <span className="ml-2">🔘 {segment.note}</span>
                          )}
                        </p>
                      </div>
                    </div>
                  )}
                </div>
              ))}

              {/* Add Guide Button */}
              <div className="flex justify-end mt-4">
                <Button
                  variant="outline"
                  size="sm"
                  className="text-[#d546ab] border-[#d546ab] hover:bg-[#fdf6ff]"
                >
                  <Plus className="h-4 w-4 mr-2" />
                  Add Guide
                </Button>
              </div>
            </div>
          </CardContent>
        </Card>
      ))}

      {/* Hotel List (separate component) */}
      {hotelDetails && (
        <HotelList
          hotels={hotelDetails.hotels}
          hotelTabs={hotelDetails.hotelTabs}
          hotelRatesVisible={hotelDetails.hotelRatesVisible}
        />
      )}

      {/* Vehicle List (separate component) */}
      <VehicleList
        vehicleTypeLabel="Sedan"
        vehicles={itinerary.vehicles}
        itineraryPlanId={itinerary.planId}
      />

      {/* Package Includes & Overall Cost */}
      <div className="grid lg:grid-cols-2 gap-6">
        {/* Package Includes */}
        <Card className="border-none shadow-none bg-white">
          <CardContent className="pt-6">
            <h2 className="text-lg font-semibold text-[#4a4260] mb-4">
              Package Includes
            </h2>
            <div className="space-y-3 text-sm text-[#6c6c6c]">
              <div>
                <p className="font-medium text-[#4a4260] mb-1">
                  Package Includes: (Inclusion)
                </p>
                <p>{itinerary.packageIncludes.description}</p>
              </div>
              <div>
                <p className="font-medium text-[#4a4260] mb-1">
                  If staying in the House boat At Alleppey/Kumarakom
                </p>
                <p className="whitespace-pre-line">
                  {itinerary.packageIncludes.houseBoatNote}
                </p>
              </div>
              <div>
                <p className="font-medium text-[#4a4260]">
                  {itinerary.packageIncludes.rateNote}
                </p>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Overall Cost */}
        <Card className="border-none shadow-none bg-gradient-to-br from-[#faf5ff] to-white">
          <CardContent className="pt-6">
            <h2 className="text-lg font-semibold text-[#4a4260] mb-4">
              OVERALL COST
            </h2>
            <div className="space-y-2 text-sm">
              <div className="flex justify-between">
                <span className="text-[#6c6c6c]">Total Vehicle cost (₹)</span>
                <span className="text-[#4a4260]">
                  {itinerary.costBreakdown.totalVehicleCost ?? "-"}
                </span>
              </div>
              <div className="flex justify-between">
                <span className="text-[#6c6c6c]">Total Vehicle Amount</span>
                <span className="text-[#4a4260]">
                  {itinerary.costBreakdown.totalVehicleAmount ?? "-"}
                </span>
              </div>
              <div className="flex justify-between">
                <span className="text-[#6c6c6c]">
                  Total Additional Margin (10%)
                </span>
                <span className="text-[#4a4260]">
                  {itinerary.costBreakdown.additionalMargin ?? "-"}
                </span>
              </div>
              <div className="flex justify-between font-semibold">
                <span className="text-[#4a4260]">Total Amount</span>
                <span className="text-[#4a4260]">
                  {itinerary.costBreakdown.totalAmount ?? "-"}
                </span>
              </div>
              <div className="flex justify-between text-[#d546ab]">
                <span>Coupon Discount</span>
                <span>{itinerary.costBreakdown.couponDiscount ?? "-"}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-[#6c6c6c]">Agent Margin</span>
                <span className="text-[#4a4260]">
                  {itinerary.costBreakdown.agentMargin ?? "-"}
                </span>
              </div>
              <div className="flex justify-between">
                <span className="text-[#6c6c6c]">Total Round Off</span>
                <span className="text-[#4a4260]">
                  {itinerary.costBreakdown.totalRoundOff ?? "-"}
                </span>
              </div>
              <div className="border-t-2 border-[#d546ab] pt-2 mt-2">
                <div className="flex justify-between text-lg font-bold">
                  <span className="text-[#4a4260]">
                    Net Payable to {itinerary.costBreakdown.companyName || "-"}
                  </span>
                  <span className="text-[#d546ab]">
                    {itinerary.costBreakdown.netPayable ?? "-"}
                  </span>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Action Buttons */}
      <div className="flex flex-wrap gap-3 justify-center">
        <Button className="bg-[#8b43d1] hover:bg-[#7c37c1]">Clipboard</Button>
        <Button className="bg-[#28a745] hover:bg-[#218838]">
          Create Itinerary
        </Button>
        <Button className="bg-[#d546ab] hover:bg-[#c03d9f]">
          Confirm Quotation
        </Button>
        <Button className="bg-[#17a2b8] hover:bg-[#138496]">Share</Button>
      </div>
    </div>
  );
};
