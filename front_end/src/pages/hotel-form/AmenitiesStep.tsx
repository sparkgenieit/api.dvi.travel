// FILE: src/pages/hotel-form/AmenitiesStep.tsx
import React, { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import type { AmenityRow } from "./HotelForm";

type ApiCtx = {
  apiGetFirst: (ps: string[]) => Promise<any>;
  apiPost: (p: string, b: any) => Promise<any>;
};

export default function AmenitiesStep({
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
  const [rows, setRows] = useState<AmenityRow[]>([]);

  const availabilityOptions = [
    { id: 1, name: "24/7" },
    { id: 2, name: "Duration" },
  ];
  const statusOptions = [
    { id: 1, name: "Active" },
    { id: 0, name: "In-Active" },
  ];

  const defaultRow: AmenityRow = {
    amenities_title: "",
    amenities_qty: 1,
    availability_type: 1,
    available_start_time: "",
    available_end_time: "",
    status: 1,
    amenities_code: "",
  };

  useQuery({
    queryKey: ["hotel-amenities", hotelId],
    enabled: !!hotelId,
    queryFn: async () => {
      const raw = await api.apiGetFirst([
        `/api/v1/hotel-amenities?hotelId=${hotelId}`,
        `/api/v1/hotel-amenities/list?hotelId=${hotelId}`,
        `/api/v1/hotel-amenities/${hotelId}`,
        `/api/v1/hotels/${hotelId}/amenities`,
        `/api/v1/hotels/${hotelId}/amenity`,
      ]).catch(() => []);
      const data = Array.isArray(raw) ? raw : (raw?.items ?? raw?.data ?? raw?.rows ?? []);
      const mapped: AmenityRow[] = (data as any[]).map((r) => ({
        id: r.id ?? r.hotel_amenities_id,
        amenities_title: r.amenities_title ?? r.title ?? "",
        amenities_qty: r.quantity ?? r.amenities_qty ?? 1,
        availability_type: r.availability_type ?? 1,
        available_start_time: r.start_time ?? r.available_start_time ?? "",
        available_end_time: r.end_time ?? r.available_end_time ?? "",
        status: r.status ?? 1,
        amenities_code: r.amenities_code ?? r.code ?? "",
      }));
      setRows(mapped.length ? mapped : [{ ...defaultRow }]);
      return mapped;
    },
  });

  const handleChange = (index: number, field: keyof AmenityRow, value: any) => {
    setRows((prev) => {
      const clone = [...prev];
      clone[index] = { ...clone[index], [field]: value };
      return clone;
    });
  };

  const addRow = () => setRows((prev) => [...prev, { ...defaultRow }]);
  const removeRow = (index: number) =>
    setRows((prev) => (prev.length === 1 ? prev : prev.filter((_, i) => i !== index)));

  async function postFirstAmenity(paths: string[], payloadArray: any[]) {
    const bodies = [{ items: payloadArray }, payloadArray];
    let lastErr: any;
    for (const url of paths) {
      for (const body of bodies) {
        try { return await api.apiPost(url, body); } catch (e) { lastErr = e; }
      }
    }
    throw lastErr || new Error("No amenities endpoint available");
  }

  const saveMut = useMutation({
    mutationFn: async (items: AmenityRow[]) => {
      const payload = items.map((r) => ({
        id: r.id ?? undefined,
        hotel_id: Number(hotelId),
        amenities_title: r.amenities_title,
        quantity: Number(r.amenities_qty || 0),
        availability_type: Number(r.availability_type || 1),
        start_time: r.available_start_time || null,
        end_time: r.available_end_time || null,
        status: Number(r.status || 1),
        amenities_code: r.amenities_code || null,
      }));
      const candidatePaths = [
        `/api/v1/hotel-amenities/bulk`,
        `/api/v1/hotel-amenities`,
        `/api/v1/hotels/${hotelId}/amenities/bulk`,
        `/api/v1/hotels/${hotelId}/amenities`,
      ];
      return await postFirstAmenity(candidatePaths, payload);
    },
    onSuccess: () => {
      qc.invalidateQueries();
      alert("✅ Amenities saved");
      onNext();
    },
    onError: (e: any) => {
      alert(`Failed saving amenities: ${e?.message || "Unknown error"}`);
    },
  });

  return (
    <>
      <h3 className="text-pink-600 font-semibold mb-4">Amenities</h3>

      <div className="grid grid-cols-12 gap-4">
        {rows.map((row, idx) => {
          const isTimeBased = Number(row.availability_type) === 2;
          return (
            <div key={row.id ?? idx} className="col-span-12 border-b border-dashed pb-4 mb-4">
              <div className="flex justify-between items-center mb-2">
                <h6 className="font-semibold text-sm">Amenities #{idx + 1}</h6>
                <button
                  type="button"
                  onClick={() => removeRow(idx)}
                  className="text-xs px-2 py-1 rounded border border-red-200 text-red-600 disabled:opacity-50"
                  disabled={rows.length === 1}
                >
                  Delete
                </button>
              </div>

              <div className="grid grid-cols-12 gap-3">
                <div className="col-span-12 md:col-span-3">
                  <label className="block text-xs font-medium">Amenities Title *</label>
                  <input
                    className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                    placeholder="Enter Amenities Title"
                    value={row.amenities_title}
                    onChange={(e) => handleChange(idx, "amenities_title", e.target.value)}
                  />
                </div>

                <div className="col-span-12 md:col-span-1">
                  <label className="block text-xs font-medium">Quantity *</label>
                  <input
                    className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                    value={row.amenities_qty}
                    onChange={(e) => handleChange(idx, "amenities_qty", e.target.value)}
                  />
                </div>

                <div className="col-span-12 md:col-span-2">
                  <label className="block text-xs font-medium">Availability Type *</label>
                  <select
                    className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                    value={row.availability_type}
                    onChange={(e) => handleChange(idx, "availability_type", Number(e.target.value))}
                  >
                    {availabilityOptions.map((o) => (
                      <option key={o.id} value={o.id}>{o.name}</option>
                    ))}
                  </select>
                </div>

                {isTimeBased && (
                  <>
                    <div className="col-span-12 md:col-span-3">
                      <label className="block text-xs font-medium">Available Start Time *</label>
                      <input
                        type="time"
                        className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                        value={row.available_start_time || ""}
                        onChange={(e) => handleChange(idx, "available_start_time", e.target.value)}
                      />
                    </div>

                    <div className="col-span-12 md:col-span-3">
                      <label className="block text-xs font-medium">Available End Time *</label>
                      <input
                        type="time"
                        className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                        value={row.available_end_time || ""}
                        onChange={(e) => handleChange(idx, "available_end_time", e.target.value)}
                      />
                    </div>
                  </>
                )}

                <div className="col-span-12 md:col-span-2">
                  <label className="block text-xs font-medium">Status *</label>
                  <select
                    className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                    value={row.status}
                    onChange={(e) => handleChange(idx, "status", Number(e.target.value))}
                  >
                    {statusOptions.map((s) => (
                      <option key={s.id} value={s.id}>{s.name}</option>
                    ))}
                  </select>
                </div>

                {row.amenities_code !== undefined && (
                  <div className="col-span-12 md:col-span-2">
                    <label className="block text-xs font-medium">Amenities Code</label>
                    <input className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                      value={row.amenities_code || ""} readOnly />
                  </div>
                )}
              </div>
            </div>
          );
        })}
      </div>

      <div className="flex justify-between items-center mt-4">
        <button
          type="button"
          onClick={addRow}
          className="px-4 py-2 rounded-lg border border-dashed border-purple-300 text-purple-700 text-sm"
        >
          + Add Amenities
        </button>
      </div>

      <div className="flex items-center justify-between mt-8">
        <button type="button" onClick={onPrev} className="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
          Back
        </button>

        <button
          type="button"
          onClick={() => saveMut.mutate(rows)}
          className="px-5 py-2 rounded-lg bg-gradient-to-r from-pink-500 to-purple-600 text-white"
        >
          Update & Continue
        </button>
      </div>
    </>
  );
}
