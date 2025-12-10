// FILE: src/pages/HotelForm.tsx
import React, { useEffect, useState } from "react";
import { useQueryClient } from "@tanstack/react-query";
import { useParams, useNavigate, useLocation } from "react-router-dom";
import { hotelFormApi, getHotelBackendRow } from "@/services/hotels";

/* ========= Types ========= */
export type HotelForm = {
  hotel_name: string;
  hotel_place: string;
  hotel_status: number | string;
  hotel_mobile_no: string;
  hotel_email_id: string;
  hotel_category: number | string;
  hotel_powerbackup: number | string;
  hotel_country: number | string;
  hotel_state: number | string;
  hotel_city: number | string;
  hotel_postal_code: string;
  hotel_code: string;
  hotel_margin: number | string;
  hotel_margin_gst_type: number | string;
  hotel_margin_gst_percentage: number | string;
  hotel_latitude?: string;
  hotel_longitude?: string;
  hotel_hotspot_status: number | string;
  hotel_address: string;
};
export type RoomForm = {
  room_type: string;
  room_title: string;
  preferred_for: string;
  no_of_rooms: number | string;
  ac_availability: number | string;
  status: number | string;
  max_adult: number | string;
  max_children: number | string;
  check_in_time: string;
  check_out_time: string;
  gst_type: string;
  gst_percentage: number | string;
  amenities: (string | number)[];
  food_breakfast: boolean;
  food_lunch: boolean;
  food_dinner: boolean;
  gallery?: FileList | null;
};
export type AmenityRow = {
  id?: number | string;
  amenities_title: string;
  amenities_qty: number | string;
  availability_type: number | string;
  available_start_time?: string;
  available_end_time?: string;
  status: number | string;
  amenities_code?: string;
};
export type PricebookRow = {
  id?: number | string;
  plan_title: string;
  room_type?: string;
  meal_plan: string;
  occupancy_type: string;
  base_price: number | string;
  extra_adult_price: number | string;
  extra_child_price: number | string;
  status: number | string;
};
export type ReviewForm = {
  hotel_rating: number | string;
  review_description: string;
};

/* Export helpers for steps (context shape) */
export const api = hotelFormApi;

/* ===== Tabs mapping via ?tab=... ===== */
const tabToStep: Record<string, number> = {
  basic: 1,
  rooms: 2,
  amenities: 3,
  pricebook: 4,
  reviews: 5,
  preview: 6,
};
const stepToTab = [
  "",
  "basic",
  "rooms",
  "amenities",
  "pricebook",
  "reviews",
  "preview",
] as const;

/* ===== Sub-steps ===== */
import BasicStep from "./BasicStep";
import RoomsStep from "./RoomsStep";
import AmenitiesStep from "./AmenitiesStep";
import PriceBookStep from "./PriceBookStep";
import ReviewStep from "./ReviewStep";
import PreviewStep from "./PreviewStep";

export default function HotelFormOrchestrator() {
  const qc = useQueryClient();
  const nav = useNavigate();
  const location = useLocation();
  const params = useParams<{ id?: string }>();
  const hotelId = params.id;
  const isEdit = Boolean(hotelId);

  const [hotelRow, setHotelRow] = useState<any | null>(null);

  const qs = new URLSearchParams(location.search);
  const currentTab = (qs.get("tab") || "basic").toLowerCase();
  const activeStep = tabToStep[currentTab] ?? 1;

  const stepEditPath = (id: string | number, tab: string) =>
    `/hotels/${id}/edit?tab=${tab}`;
  const goToTab = (tab: string, id?: number | string) => {
    const targetId = id ?? hotelId;
    if (!targetId) return;
    nav(stepEditPath(targetId, tab));
  };
  const goToRooms = (id?: number | string) => goToTab("rooms", id);
  const goToAmenities = (id?: number | string) => goToTab("amenities", id);
  const goToPriceBook = (id?: number | string) => goToTab("pricebook", id);
  const goToReviews = (id?: number | string) => goToTab("reviews", id);
  const goToPreview = (id?: number | string) => goToTab("preview", id);

  /* Load existing hotel in edit mode (for Preview & defaults) */
  useEffect(() => {
    if (!isEdit || !hotelId) return;
    let alive = true;
    getHotelBackendRow(hotelId)
      .then((row) => {
        if (!alive || !row) return;
        setHotelRow(row);
      })
      .catch(() => {});
    return () => {
      alive = false;
    };
  }, [isEdit, hotelId]);

  const steps = [
    { n: 1, label: "Basic Info" },
    { n: 2, label: "Rooms" },
    { n: 3, label: "Amenities" },
    { n: 4, label: "Price Book" },
    { n: 5, label: "Review & Feedback" },
    { n: 6, label: "Preview" },
  ];

  const isClickable = (tab: string) => isEdit && tab !== "basic";

  return (
    <div className="p-4">
      {/* Stepper */}
      <div className="mb-4 flex justify-center gap-3">
        {steps.map((s, i) => {
          const isActive = s.n === activeStep;
          const tab = stepToTab[s.n];
          const canClick = isClickable(tab);
          return (
            <div key={s.n} className="flex items-center">
              <button
                type="button"
                onClick={() => canClick && goToTab(tab)}
                className={`w-9 h-9 rounded-full flex items-center justify-center ${
                  isActive ? "bg-purple-600 text-white" : "bg-gray-300 text-white"
                } ${canClick ? "cursor-pointer" : "cursor-default"}`}
                title={s.label}
              >
                {s.n}
              </button>
              <button
                type="button"
                onClick={() => canClick && goToTab(tab)}
                className={`ml-2 text-sm ${
                  isActive ? "text-purple-600" : "text-gray-400"
                } ${canClick ? "hover:underline" : ""}`}
              >
                {s.label}
              </button>
              {i < steps.length - 1 && (
                <div className="mx-3 text-gray-400">{">"}</div>
              )}
            </div>
          );
        })}
      </div>

      {/* Card */}
      <div className="bg-white rounded-2xl shadow p-6">
        {activeStep === 1 && (
          <BasicStep
            api={api}
            isEdit={isEdit}
            hotelId={hotelId}
            onNext={(newId) => goToRooms(newId)}
          />
        )}

        {activeStep === 2 && hotelId && (
          <RoomsStep
            api={api}
            hotelId={hotelId}
            onPrev={() => goToTab("basic")}
            onNext={() => goToAmenities(hotelId)}
          />
        )}

        {activeStep === 3 && hotelId && (
          <AmenitiesStep
            api={api}
            hotelId={hotelId}
            onPrev={() => goToRooms(hotelId)}
            onNext={() => goToPriceBook(hotelId)}
          />
        )}

        {activeStep === 4 && hotelId && (
          <PriceBookStep
            api={api}
            hotelId={hotelId}
            onPrev={() => goToAmenities(hotelId)}
            onNext={() => goToReviews(hotelId)}
          />
        )}

        {activeStep === 5 && hotelId && (
          <ReviewStep
            api={api}
            hotelId={hotelId}
            onPrev={() => goToPriceBook(hotelId)}
            onNext={() => goToPreview(hotelId)}
          />
        )}

        {activeStep === 6 && hotelId && (
          <PreviewStep
            api={api}
            hotelId={hotelId}
            hotelData={hotelRow}
            onPrev={() => goToReviews(hotelId)}
          />
        )}
      </div>
    </div>
  );
}
