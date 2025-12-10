// FILE: src/pages/hotel-form/PriceBookStep.tsx
import React, { useEffect, useMemo, useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import type { PricebookRow } from "./HotelForm";

type ApiCtx = {
  apiGetFirst: (ps: string[]) => Promise<any>;
  apiPost: (p: string, b: any) => Promise<any>;
};

type AmenityOption = { id: number; name: string };
type RoomRow = {
  room_ID: number;
  room_title?: string | null;
  room_ref_code?: string | null;
  room_type_id?: number | null;
};

export default function PriceBookStep({
  api,
  hotelId,
  onPrev,
  onNext,
}: {
  api: ApiCtx;
  hotelId: string;
  onPrev: () => void;
  onNext: () => void;
}) {
  const qc = useQueryClient();

  // ---------- helpers ----------
  const toNum = (v: any) => {
    const n = Number(v);
    return Number.isFinite(n) ? n : 0;
  };
  const toMaybeNum = (v: any) => {
    if (v === "" || v === null || v === undefined) return undefined;
    const n = Number(v);
    return Number.isFinite(n) ? n : undefined;
  };
  const ymd = (d: string) => (d ? new Date(d).toISOString().slice(0, 10) : "");

  /* -----------------------------------------------------------
   *  STATE: (kept) Margin & Meal section state (unchanged)
   * --------------------------------------------------------- */
  const [hotelMargin, setHotelMargin] = useState<string>("");
  const [hotelMarginGstType, setHotelMarginGstType] = useState<string>("");
  const [hotelMarginGstPercentage, setHotelMarginGstPercentage] =
    useState<string>("");

  const [breakfastCost, setBreakfastCost] = useState<string>("");
  const [lunchCost, setLunchCost] = useState<string>("");
  const [dinnerCost, setDinnerCost] = useState<string>("");

  const [mealStartDate, setMealStartDate] = useState<string>("");
  const [mealEndDate, setMealEndDate] = useState<string>("");

  /* -----------------------------------------------------------
   *  NEW: Amenities Details section state
   * --------------------------------------------------------- */
  const [amenitiesStartDate, setAmenitiesStartDate] = useState<string>("");
  const [amenitiesEndDate, setAmenitiesEndDate] = useState<string>("");
  const [amenityId, setAmenityId] = useState<number | "">("");
  const [hoursCharge, setHoursCharge] = useState<string>("");
  const [dayCharge, setDayCharge] = useState<string>("");
  const [amenityPickerOpen, setAmenityPickerOpen] = useState(false);

  /* -----------------------------------------------------------
   *  NEW: Room Details section state
   * --------------------------------------------------------- */
  type RoomInput = {
    roomPrice?: string;
    extraBed?: string;
    childWithBed?: string;
    childWithoutBed?: string;
    gstType?: "Included" | "Excluded" | "";
    gstPct?: string; // "0", "5", "12", "18", "28"
    startDate?: string;
    endDate?: string;
  };
  const [roomInputs, setRoomInputs] = useState<Record<number, RoomInput>>({});

  /* ================= Static dropdowns ================= */
  const gstTypes = [
    { id: "Included", name: "Included" },
    { id: "Excluded", name: "Excluded" },
  ];

  const gstPercentages = [
    { id: "0", name: "0% GST - %0" },
    { id: "5", name: "5% GST - %5" },
    { id: "12", name: "12% GST - %12" },
    { id: "18", name: "18% GST - %18" },
    { id: "28", name: "28% GST - %28" },
  ];

  /* ================= Load: Basic Info to prefill margin ================= */
  const { data: basicInfoRaw } = useQuery({
    queryKey: ["hotel-basic-info-for-pricebook", hotelId],
    enabled: !!hotelId,
    queryFn: () =>
      api
        .apiGetFirst([
          `/api/v1/hotels/${hotelId}`,
          `/api/v1/hotels/${hotelId}/basic`,
          `/api/v1/hotels/basic?hotelId=${hotelId}`,
        ])
        .catch(() => null),
  });

  const toUiGstType = (val: any): "Included" | "Excluded" => {
    const s = String(val ?? "").toLowerCase();
    if (val === 1 || s.includes("include") || s === "incl" || s === "included")
      return "Included";
    if (val === 2 || s.includes("exclude") || s === "excl" || s === "exclusive")
      return "Excluded";
    return "Included";
  };

  useEffect(() => {
    if (!basicInfoRaw) return;

    const row = Array.isArray(basicInfoRaw)
      ? basicInfoRaw[0] ?? null
      : basicInfoRaw;

    if (!row) return;

    const margin = row.hotel_margin ?? row.margin ?? row.hotelMargin ?? "";

    const gstTypeUi = toUiGstType(
      row.hotel_margin_gst_type ??
        row.margin_gst_type ??
        row.gst_type ??
        row.hotel_gst_type ??
        1
    );

    const gstPctRaw =
      row.hotel_margin_gst_percentage ??
      row.margin_gst_percentage ??
      row.gst_percentage ??
      row.hotel_gst_percentage ??
      "";
    const gstPctId =
      gstPctRaw === "" || gstPctRaw === null || gstPctRaw === undefined
        ? ""
        : String(Number(gstPctRaw));

    setHotelMargin(String(margin ?? ""));
    setHotelMarginGstType(gstTypeUi);
    setHotelMarginGstPercentage(gstPctId);
  }, [basicInfoRaw]);

  /* ================= Load: Amenities list ================= */
  const { data: amenityOptions = [] as AmenityOption[] } = useQuery({
    queryKey: ["hotel-amenities", hotelId],
    enabled: !!hotelId,
    queryFn: async () => {
      const raw = await api
        .apiGetFirst([
          `/api/v1/hotels/${hotelId}/amenities`,
          `/api/v1/hotel-amenities?hotelId=${hotelId}`,
          `/api/v1/hotels/amenities?hotelId=${hotelId}`,
        ])
        .catch(() => []);
      const rows = Array.isArray(raw)
        ? raw
        : raw?.items ?? raw?.data ?? raw?.rows ?? [];
      return rows.map((r: any) => ({
        id:
          r.hotel_amenities_id ??
          r.amenity_id ??
          r.id ??
          Number(r.value) ??
          0,
        name: r.amenities_title ?? r.name ?? r.title ?? "Amenity",
      }));
    },
  });

  useEffect(() => {
    if (amenityOptions.length && amenityId === "") {
      setAmenityId(amenityOptions[0].id);
    }
  }, [amenityOptions, amenityId]);

  const selectedAmenity = useMemo(
    () => amenityOptions.find((a) => a.id === amenityId) ?? null,
    [amenityOptions, amenityId]
  );

  /* ================= Load: Rooms list ================= */
  const { data: rooms = [] as RoomRow[] } = useQuery({
    queryKey: ["hotel-rooms-for-pricebook", hotelId],
    enabled: !!hotelId,
    queryFn: async () => {
      const raw = await api
        .apiGetFirst([
          `/api/v1/hotels/${hotelId}/rooms`,
          `/api/v1/rooms?hotelId=${hotelId}`,
          `/api/v1/hotel-rooms?hotelId=${hotelId}`,
        ])
        .catch(() => []);
      const rows = Array.isArray(raw)
        ? raw
        : raw?.items ?? raw?.data ?? raw?.rows ?? [];
      return rows.map((r: any) => ({
        room_ID: Number(r.room_ID ?? r.room_id ?? r.id),
        room_title: r.room_title ?? r.title ?? null,
        room_ref_code: r.room_ref_code ?? r.room_type ?? null,
        room_type_id: r.room_type_id ?? null,
      }));
    },
  });

  /* -----------------------------------------------------------
   *  MUTATIONS
   * --------------------------------------------------------- */

  // Meal details save (kept)
  const mealMut = useMutation({
    mutationFn: async (body: {
      startDate: string;
      endDate: string;
      breakfastCost?: number;
      lunchCost?: number;
      dinnerCost?: number;
    }) => {
      const payload: any = {
        startDate: body.startDate,
        endDate: body.endDate,
      };
      if (Number.isFinite(body.breakfastCost as number))
        payload.breakfastCost = body.breakfastCost;
      if (Number.isFinite(body.lunchCost as number))
        payload.lunchCost = body.lunchCost;
      if (Number.isFinite(body.dinnerCost as number))
        payload.dinnerCost = body.dinnerCost;

      const paths = [
        `/api/v1/hotels/${hotelId}/meal-pricebook`,
        `/api/v1/hotel-meal-pricebook?hotelId=${hotelId}`,
        `/api/v1/hotels/${hotelId}/meal-price-book`,
      ];
      let lastErr: any;
      for (const p of paths) {
        try {
          return await api.apiPost(p, payload);
        } catch (e) {
          lastErr = e;
        }
      }
      throw lastErr || new Error("No meal pricebook endpoint available");
    },
    onSuccess: () => {
      alert("✅ Meal details saved");
    },
    onError: (e: any) => {
      alert(`Meal save failed: ${e?.message || "Unknown error"}`);
    },
  });

  // ---------- Amenities price book: match your controller + service exactly ----------
  const amenityMut = useMutation({
    mutationFn: async () => {
      if (!amenityId || !amenitiesStartDate || !amenitiesEndDate) {
        throw new Error("Amenity, Start Date and End Date are required");
      }

      const startDate = ymd(amenitiesStartDate);
      const endDate = ymd(amenitiesEndDate);

      // Exact DTO your controller expects:
      // UpsertAmenityPricebookDto { startDate, endDate, hoursCharge?, dayCharge? }
      const payload = {
        startDate,
        endDate,
        hoursCharge: toMaybeNum(hoursCharge),
        dayCharge: toMaybeNum(dayCharge),
      };

      // Primary route from HotelsController:
      const primary = `/api/v1/hotels/${hotelId}/amenities/${amenityId}/pricebook`;
      // Alias from PreviewAliasesController (requires *both* hotelId & amenityId):
      const alias = `/api/v1/hotel-amenities-pricebook?hotelId=${hotelId}&amenityId=${amenityId}`;

      let err: any;
      try {
        return await api.apiPost(primary, payload);
      } catch (e) {
        err = e;
      }
      return api.apiPost(alias, payload).catch((e) => {
        throw err || e;
      });
    },
    onSuccess: () => {
      alert("✅ Amenities price book saved");
      qc.invalidateQueries({ queryKey: ["hotel-amenities", hotelId] });
    },
    onError: (e: any) => {
      alert(
        `Amenities save failed: ${e?.message || "Unknown error"}\n` +
          `Tip: ensure amenityId is selected (the alias requires it in the query).`
      );
    },
  });

  // Room price book (bulk) — kept
  const roomMut = useMutation({
    mutationFn: async () => {
      if (!rooms.length) throw new Error("No rooms to update");

      const items = rooms
        .map((r) => {
          const v = roomInputs[r.room_ID] || {};
          const hasAny =
            v.roomPrice ||
            v.extraBed ||
            v.childWithBed ||
            v.childWithoutBed ||
            v.gstType ||
            v.gstPct ||
            v.startDate ||
            v.endDate;
          if (!hasAny) return null;

          return {
            hotel_id: toNum(hotelId),
            room_id: r.room_ID,
            startDate: v.startDate || "",
            endDate: v.endDate || "",
            roomPrice: toMaybeNum(v.roomPrice),
            extraBed: toMaybeNum(v.extraBed),
            childWithBed: toMaybeNum(v.childWithBed),
            childWithoutBed: toMaybeNum(v.childWithoutBed),
            gstType: v.gstType || undefined,
            gstPercentage: toMaybeNum(v.gstPct),
            status: 1, // harmless; backend may ignore
          };
        })
        .filter(Boolean) as any[];

      if (!items.length) throw new Error("Nothing to save");

      const payload = { items, status: 1 };

      const paths = [
        `/api/v1/hotels/${hotelId}/rooms/pricebook/bulk`,
        `/api/v1/hotel-room-pricebook/bulk?hotelId=${hotelId}`,
        `/api/v1/hotels/${hotelId}/room-price-book/bulk`,
      ];
      let lastErr: any;
      for (const p of paths) {
        try {
          return await api.apiPost(p, payload);
        } catch (e) {
          lastErr = e;
        }
      }
      throw lastErr || new Error("No room pricebook endpoint available");
    },
    onSuccess: () => {
      alert("✅ Room price book saved");
    },
    onError: (e: any) => {
      alert(`Room pricebook failed: ${e?.message || "Unknown error"}`);
    },
  });

  /* -----------------------------------------------------------
   *  Derived state & helpers
   * --------------------------------------------------------- */
  const canSaveMeals =
    !!mealStartDate &&
    !!mealEndDate &&
    (breakfastCost !== "" || lunchCost !== "" || dinnerCost !== "");

  const canSaveAmenities =
    !!amenityId && !!amenitiesStartDate && !!amenitiesEndDate;

  const setRoomField = (
    roomId: number,
    key: keyof RoomInput,
    value: string
  ) => {
    setRoomInputs((prev) => ({
      ...prev,
      [roomId]: { ...(prev[roomId] || {}), [key]: value },
    }));
  };

  const roomCardHeader = (r: RoomRow, index: number) => {
    const title =
      r.room_title ||
      r.room_ref_code ||
      (r.room_type_id ? `Room Type #${r.room_type_id}` : `Room #${r.room_ID}`);
    return `#${index + 1} - ${title} ${
      r.room_ref_code ? `| [${r.room_ref_code}]` : ""
    }`;
  };

  return (
    <>
      <h3 className="text-pink-600 font-semibold mb-4">Hotel Price Book</h3>

      {/* ====== Hotel Margin ====== */}
      <div className="border rounded-xl bg-white shadow-sm mb-4 p-4">
        <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b pb-3 mb-3">
          <h5 className="font-semibold text-gray-800 text-sm md:text-base">
            Hotel Margin
          </h5>
        </div>

        <div className="grid grid-cols-12 gap-3">
          <div className="col-span-12 md:col-span-4">
            <label className="block text-xs font-medium mb-1">
              Hotel Margin (%)
            </label>
            <input
              type="number"
              className="w-full border rounded-lg px-3 py-2 text-sm"
              placeholder="Enter Hotel Margin"
              value={hotelMargin}
              onChange={(e) => setHotelMargin(e.target.value)}
            />
          </div>

          <div className="col-span-12 md:col-span-4">
            <label className="block text-xs font-medium mb-1">
              Margin GST Type
            </label>
            <select
              className="w-full border rounded-lg px-3 py-2 text-sm"
              value={hotelMarginGstType}
              onChange={(e) =>
                setHotelMarginGstType(e.target.value as "Included" | "Excluded")
              }
            >
              <option value="">Select GST Type</option>
              <option value="Included">Included</option>
              <option value="Excluded">Excluded</option>
            </select>
          </div>

          <div className="col-span-12 md:col-span-4">
            <label className="block text-xs font-medium mb-1">
              Margin GST Percentage
            </label>
            <select
              className="w-full border rounded-lg px-3 py-2 text-sm"
              value={hotelMarginGstPercentage}
              onChange={(e) => setHotelMarginGstPercentage(e.target.value)}
            >
              <option value="">Select GST %</option>
              {["0", "5", "12", "18", "28"].map((p) => (
                <option key={p} value={p}>
                  {p}%
                </option>
              ))}
            </select>
          </div>
        </div>
      </div>

      {/* ====== Meal Details ====== */}
      <div className="border rounded-xl bg-white shadow-sm mb-4 p-4">
        <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b pb-3 mb-3">
          <h5 className="font-semibold text-gray-800 text-sm md:text/base">
            Meal Details
          </h5>

          <div className="flex items-center gap-2">
            <input
              type="date"
              className="border rounded-lg px-3 py-2 text-sm"
              placeholder="Start Date"
              value={mealStartDate}
              onChange={(e) => setMealStartDate(e.target.value)}
            />
            <input
              type="date"
              className="border rounded-lg px-3 py-2 text-sm"
              placeholder="End Date"
              value={mealEndDate}
              onChange={(e) => setMealEndDate(e.target.value)}
            />
            <button
              type="button"
              onClick={() =>
                mealMut.mutate({
                  startDate: mealStartDate,
                  endDate: mealEndDate,
                  breakfastCost: toMaybeNum(breakfastCost),
                  lunchCost: toMaybeNum(lunchCost),
                  dinnerCost: toMaybeNum(dinnerCost),
                } as any)
              }
              disabled={!canSaveMeals || mealMut.isPending}
              className={`px-4 py-2 rounded-lg text-white text-sm
                bg-gradient-to-r from-pink-500 to-purple-600
                disabled:opacity-50`}
            >
              {mealMut.isPending ? "Saving..." : "Update"}
            </button>
          </div>
        </div>

        <div className="grid grid-cols-12 gap-3">
          <div className="col-span-12 md:col-span-4">
            <label className="block text-xs font-medium mb-1">
              Breakfast Cost (₹)
            </label>
            <input
              type="number"
              className="w-full border rounded-lg px-3 py-2 text-sm"
              placeholder="Enter Breakfast Cost"
              value={breakfastCost}
              onChange={(e) => setBreakfastCost(e.target.value)}
            />
          </div>

          <div className="col-span-12 md:col-span-4">
            <label className="block text-xs font-medium mb-1">
              Lunch Cost (₹)
            </label>
            <input
              type="number"
              className="w-full border rounded-lg px-3 py-2 text-sm"
              placeholder="Enter Lunch Cost"
              value={lunchCost}
              onChange={(e) => setLunchCost(e.target.value)}
            />
          </div>

          <div className="col-span-12 md:col-span-4">
            <label className="block text-xs font-medium mb-1">
              Dinner Cost (₹)
            </label>
            <input
              type="number"
              className="w-full border rounded-lg px-3 py-2 text-sm"
              placeholder="Enter Dinner Cost"
              value={dinnerCost}
              onChange={(e) => setDinnerCost(e.target.value)}
            />
          </div>
        </div>
      </div>

      {/* ====== Amenities Details ====== */}
      <div className="border rounded-xl bg-white shadow-sm mb-4 p-4">
        <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b pb-3 mb-3">
          <h5 className="font-semibold text-gray-800 text-sm md:text-base">
            Amenities Details
          </h5>

          <div className="flex items-center gap-2">
            <input
              type="date"
              className="border rounded-lg px-3 py-2 text-sm"
              placeholder="Start Date"
              value={amenitiesStartDate}
              onChange={(e) => setAmenitiesStartDate(e.target.value)}
            />
            <input
              type="date"
              className="border rounded-lg px-3 py-2 text-sm"
              placeholder="End Date"
              value={amenitiesEndDate}
              onChange={(e) => setAmenitiesEndDate(e.target.value)}
            />
            <button
              type="button"
              onClick={() => amenityMut.mutate()}
              disabled={!canSaveAmenities || amenityMut.isPending}
              className={`px-4 py-2 rounded-lg text-white text-sm
                bg-gradient-to-r from-pink-500 to-purple-600
                disabled:opacity-50`}
            >
              {amenityMut.isPending ? "Saving..." : "Update"}
            </button>
          </div>
        </div>

        <div className="grid grid-cols-12 gap-3">
          {/* Amenity Title (read-only, click to change) */}
          <div className="col-span-12 md:col-span-4">
            <label className="block text-xs font-medium mb-1">
              Amenities Title
            </label>

            {!amenityPickerOpen ? (
              <button
                type="button"
                className="text-pink-600 font-semibold text-base hover:underline"
                onClick={() => {
                  if (amenityOptions.length > 1) setAmenityPickerOpen(true);
                }}
                title={
                  amenityOptions.length > 1
                    ? "Click to change amenity"
                    : undefined
                }
              >
                {selectedAmenity?.name || "—"}
              </button>
            ) : (
              <select
                className="w-full border rounded-lg px-3 py-2 text-sm"
                value={amenityId}
                onChange={(e) => {
                  const v = e.target.value === "" ? "" : Number(e.target.value);
                  setAmenityId(v as number | "");
                  setAmenityPickerOpen(false);
                }}
              >
                {amenityOptions.map((a) => (
                  <option key={a.id} value={a.id}>
                    {a.name}
                  </option>
                ))}
              </select>
            )}
          </div>

          <div className="col-span-12 md:col-span-4">
            <label className="block text-xs font-medium mb-1">Hours Charge (₹)</label>
            <input
              type="number"
              className="w-full border rounded-lg px-3 py-2 text-sm"
              placeholder="Hours Charge"
              value={hoursCharge}
              onChange={(e) => setHoursCharge(e.target.value)}
            />
          </div>

          <div className="col-span-12 md:col-span-4">
            <label className="block text-xs font-medium mb-1">Day Charge (₹)</label>
            <input
              type="number"
              className="w-full border rounded-lg px-3 py-2 text-sm"
              placeholder="Day Charge"
              value={dayCharge}
              onChange={(e) => setDayCharge(e.target.value)}
            />
          </div>
        </div>
      </div>

      {/* ====== Room Details ====== */}
      <div className="border rounded-xl bg-white shadow-sm mb-4 p-4">
        <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3 border-b pb-3 mb-3">
          <h5 className="font-semibold text-gray-800 text-sm md:text-base">
            Room Details
          </h5>

        <div className="flex items-center gap-2">
            <button
              type="button"
              onClick={() => roomMut.mutate()}
              className="px-4 py-2 rounded-lg text-white text-sm bg-gradient-to-r from-pink-500 to-purple-600"
            >
              Update
            </button>
          </div>
        </div>

        {rooms.length === 0 ? (
          <div className="text-sm text-gray-500">No rooms found.</div>
        ) : (
          <div className="grid grid-cols-12 gap-4">
            {rooms.map((r, idx) => {
              const v = roomInputs[r.room_ID] || {};
              return (
                <div
                  key={r.room_ID}
                  className="col-span-12 border-b border-dashed pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0"
                >
                  <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">
                    <h6 className="font-semibold text-sm text-gray-700">
                      {roomCardHeader(r, idx)}
                    </h6>

                    <div className="flex gap-2">
                      <input
                        type="date"
                        className="border rounded-lg px-3 py-2 text-sm"
                        placeholder="Start Date"
                        value={v.startDate || ""}
                        onChange={(e) =>
                          setRoomField(r.room_ID, "startDate", e.target.value)
                        }
                      />
                      <input
                        type="date"
                        className="border rounded-lg px-3 py-2 text-sm"
                        placeholder="End Date"
                        value={v.endDate || ""}
                        onChange={(e) =>
                          setRoomField(r.room_ID, "endDate", e.target.value)
                        }
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-12 gap-3">
                    <div className="col-span-12 md:col-span-3">
                      <label className="block text-xs font-medium">
                        Room Price (₹)
                      </label>
                      <input
                        className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                        placeholder="Enter the Room Price"
                        value={v.roomPrice || ""}
                        onChange={(e) =>
                          setRoomField(r.room_ID, "roomPrice", e.target.value)
                        }
                        type="number"
                      />
                    </div>

                    <div className="col-span-12 md:col-span-3">
                      <label className="block text-xs font-medium">
                        Extra Bed Charge (₹)
                      </label>
                      <input
                        className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                        placeholder="Enter the Extra Bed Charge"
                        value={v.extraBed || ""}
                        onChange={(e) =>
                          setRoomField(r.room_ID, "extraBed", e.target.value)
                        }
                        type="number"
                      />
                    </div>

                    <div className="col-span-12 md:col-span-3">
                      <label className="block text-xs font-medium">
                        Child with Bed (₹)
                      </label>
                      <input
                        className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                        placeholder="Enter the Child with Bed"
                        value={v.childWithBed || ""}
                        onChange={(e) =>
                          setRoomField(
                            r.room_ID,
                            "childWithBed",
                            e.target.value
                          )
                        }
                        type="number"
                      />
                    </div>

                    <div className="col-span-12 md:col-span-3">
                      <label className="block text-xs font-medium">
                        Child Without Bed (₹)
                      </label>
                      <input
                        className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                        placeholder="Enter the Child Without Bed"
                        value={v.childWithoutBed || ""}
                        onChange={(e) =>
                          setRoomField(
                            r.room_ID,
                            "childWithoutBed",
                            e.target.value
                          )
                        }
                        type="number"
                      />
                    </div>

                    <div className="col-span-12 md:col-span-3">
                      <label className="block text-xs font-medium">
                        GST Type
                      </label>
                      <select
                        className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                        value={v.gstType || ""}
                        onChange={(e) =>
                          setRoomField(
                            r.room_ID,
                            "gstType",
                            e.target.value as any
                          )
                        }
                      >
                        <option value="">Select</option>
                        <option value="Included">Included</option>
                        <option value="Excluded">Excluded</option>
                      </select>
                    </div>

                    <div className="col-span-12 md:col-span-3">
                      <label className="block text-xs font-medium">
                        GST Percentage
                      </label>
                      <select
                        className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                        value={v.gstPct || "0"}
                        onChange={(e) =>
                          setRoomField(r.room_ID, "gstPct", e.target.value)
                        }
                      >
                        {gstPercentages.map((g) => (
                          <option key={g.id} value={g.id}>
                            {g.name}
                          </option>
                        ))}
                      </select>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </div>

      {/* ====== Footer ====== */}
      <div className="flex items-center justify-between mt-6">
        <button
          type="button"
          onClick={onPrev}
          className="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm"
        >
          Back
        </button>
        <button
          type="button"
          onClick={onNext}
          className="px-5 py-2 rounded-lg bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm"
        >
          Continue
        </button>
      </div>
    </>
  );
}
