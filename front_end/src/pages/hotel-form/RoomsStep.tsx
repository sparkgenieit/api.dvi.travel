// FILE: src/pages/hotel-form/RoomsStep.tsx
import React, { useEffect, useMemo, useRef, useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import type { RoomForm } from "./HotelForm";
import { API_BASE_URL, getToken } from "../../lib/api";

/* ========= API ctx from parent ========= */
type ApiCtx = {
  apiGet: (p: string) => Promise<any>;
  apiPost: (p: string, b: any) => Promise<any>;
  apiGetFirst: (ps: string[]) => Promise<any>;
};

/* ========= Helpers ========= */
// DB expects 1 (Included) / 2 (Excluded)
const toGstNum = (v: any): 1 | 2 => {
  if (v === 1 || v === "1") return 1;
  if (v === 2 || v === "2") return 2;
  const s = String(v ?? "").toLowerCase();
  if (s.includes("include")) return 1;
  if (s.includes("exclude")) return 2;
  return 1;
};

// "12:00 PM" → "12:00"
const to24h = (val: string): string => {
  if (!val) return "";
  const ampm = val.trim().match(/^(\d{1,2}):(\d{2})\s*([AaPp][Mm])$/);
  if (!ampm) {
    return /^\d{2}:\d{2}$/.test(val) ? val : "";
  }
  let h = parseInt(ampm[1], 10);
  const m = ampm[2];
  const p = ampm[3].toUpperCase();
  if (p === "AM") h = h === 12 ? 0 : h;
  else h = h === 12 ? 12 : h + 12;
  return `${String(h).padStart(2, "0")}:${m}`;
};

// "12:00" → "12:00 PM"
const to12h = (val: string): string => {
  if (!val) return "";
  const m = val.match(/^(\d{2}):(\d{2})$/);
  if (!m) return val;
  let h = parseInt(m[1], 10);
  const min = m[2];
  const suf = h >= 12 ? "PM" : "AM";
  h = h % 12 || 12;
  return `${String(h).padStart(2, "0")}:${min} ${suf}`;
};

// Defensive getter for time fields that might arrive as {} / Date / string
const getTimeString = (t: any): string => {
  if (!t) return "";
  if (typeof t === "string") return t;
  try {
    const d = new Date(t as any);
    if (!isNaN(d.getTime())) {
      const hh = String(d.getHours()).padStart(2, "0");
      const mm = String(d.getMinutes()).padStart(2, "0");
      return `${hh}:${mm}`;
    }
  } catch {}
  return "";
};

/** Generate a non-null room_ref_code if backend didn't give one */
const generateRoomRefCode = (hotelId: string | number, rowIndex: number) => {
  const prefix = "DVIR"; // matches existing style like DVIRDEL...
  const hidPart = String(hotelId || "")
    .replace(/\D/g, "")
    .padStart(3, "0")
    .slice(-3);
  const idxPart = String(rowIndex + 1).padStart(2, "0");
  const rand = Math.floor(100000 + Math.random() * 900000); // 6 digits
  return `${prefix}${hidPart}${idxPart}${rand}`;
};

/* ========= Lightweight chip multi-select (no external lib) ========= */
type Opt = { id: number | string; name: string };
function AmenityPicker({
  options,
  value,
  onChange,
  placeholder = "Choose amenities",
}: {
  options: Opt[];
  value: (number | string)[];
  onChange: (ids: (number | string)[]) => void;
  placeholder?: string;
}) {
  const [open, setOpen] = useState(false);
  const [q, setQ] = useState("");
  const ref = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const sub = (e: MouseEvent) => {
      if (!ref.current?.contains(e.target as Node)) setOpen(false);
    };
    document.addEventListener("mousedown", sub);
    return () => document.removeEventListener("mousedown", sub);
  }, []);

  const filtered = useMemo(() => {
    const s = q.trim().toLowerCase();
    return s ? options.filter((o) => o.name.toLowerCase().includes(s)) : options;
  }, [q, options]);

  const add = (id: number | string) => {
    if (!value.includes(id)) onChange([...value, id]);
    setQ("");
    setOpen(false);
  };
  const remove = (id: number | string) =>
    onChange(value.filter((v) => String(v) !== String(id)));

  return (
    <div ref={ref} className="relative">
      <div
        className="min-h-[38px] w-full border rounded-lg px-2 py-1 flex flex-wrap gap-1 cursor-text"
        onClick={() => setOpen(true)}
      >
        {value.length === 0 && (
          <span className="text-gray-400 text-sm px-1 py-1">{placeholder}</span>
        )}
        {value.map((id) => {
          const o = options.find((x) => String(x.id) === String(id));
          return (
            <span
              key={String(id)}
              className="inline-flex items-center gap-1 text-xs border rounded-full px-2 py-1 bg-gray-50"
            >
              {o?.name ?? id}
              <button
                type="button"
                className="leading-none text-gray-500 hover:text-gray-700"
                onClick={() => remove(id)}
              >
                ×
              </button>
            </span>
          );
        })}
        <input
          className="flex-1 outline-none text-sm px-1 py-1"
          value={q}
          onChange={(e) => setQ(e.target.value)}
          onFocus={() => setOpen(true)}
        />
      </div>

      {open && (
        <div className="absolute z-10 mt-1 w-full max-h-48 overflow-auto border rounded-lg bg-white shadow">
          {filtered.length === 0 ? (
            <div className="px-3 py-2 text-sm text-gray-500">No results</div>
          ) : (
            filtered.map((o) => (
              <button
                key={String(o.id)}
                type="button"
                onClick={() => add(o.id)}
                className="block w-full text-left px-3 py-2 hover:bg-purple-50"
              >
                {o.name}
              </button>
            ))
          )}
        </div>
      )}
    </div>
  );
}

/* ======= RoomTypeAutocomplete (search + highlight + keyboard nav) ======= */
function highlightMatch(label: string, q: string) {
  if (!q) return [label];
  const i = label.toLowerCase().indexOf(q.toLowerCase());
  if (i === -1) return [label];
  return [label.slice(0, i), label.slice(i, i + q.length), label.slice(i + q.length)];
}

function RoomTypeAutocomplete({
  value,
  onChange,
  options,
  placeholder = "Type room type…",
  dropdownId,
}: {
  value: string;
  onChange: (v: string) => void;
  options: { id: number | string; name: string }[];
  placeholder?: string;
  dropdownId: string;
}) {
  const [open, setOpen] = React.useState(false);
  const [q, setQ] = React.useState(value ?? "");
  const [active, setActive] = React.useState(0);
  const wrapRef = React.useRef<HTMLDivElement>(null);

  React.useEffect(() => {
    const onDoc = (e: MouseEvent) => {
      if (!wrapRef.current?.contains(e.target as Node)) setOpen(false);
    };
    document.addEventListener("mousedown", onDoc);
    return () => document.removeEventListener("mousedown", onDoc);
  }, []);

  React.useEffect(() => setQ(value ?? ""), [value]);

  const filtered = React.useMemo(() => {
    const s = q.trim().toLowerCase();
    const arr = s ? options.filter((o) => o.name.toLowerCase().includes(s)) : options;
    return arr.slice(0, 8);
  }, [q, options]);

  const selectVal = (name: string) => {
    onChange(name);
    setOpen(false);
  };

  return (
    <div ref={wrapRef} className="relative">
      <input
        className="mt-1 w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-400 outline-none"
        placeholder={placeholder}
        value={q}
        onChange={(e) => {
          setQ(e.target.value);
          onChange(e.target.value);
          setOpen(true);
        }}
        onFocus={() => setOpen(true)}
        onKeyDown={(e) => {
          if (!open && (e.key === "ArrowDown" || e.key === "ArrowUp")) {
            setOpen(true);
            return;
          }
          if (!filtered.length) return;
          if (e.key === "ArrowDown") {
            e.preventDefault();
            setActive((p) => (p + 1) % filtered.length);
          } else if (e.key === "ArrowUp") {
            e.preventDefault();
            setActive((p) => (p - 1 + filtered.length) % filtered.length);
          } else if (e.key === "Enter") {
            e.preventDefault();
            selectVal(filtered[active].name);
          } else if (e.key === "Escape") {
            setOpen(false);
          }
        }}
        aria-controls={dropdownId}
        aria-expanded={open}
        aria-autocomplete="list"
        role="combobox"
      />

      {open && filtered.length > 0 && (
        <div
          id={dropdownId}
          className="absolute z-20 mt-1 w-full max-h-64 overflow-auto border rounded-lg bg-white shadow"
          role="listbox"
        >
          {filtered.map((o, i) => {
            const parts = highlightMatch(o.name, q);
            return (
              <button
                key={String(o.id)}
                type="button"
                role="option"
                aria-selected={i === active}
                className={`w-full text-left px-3 py-2 text-sm hover:bg-purple-50 ${
                  i === active ? "bg-purple-50" : ""
                }`}
                onMouseEnter={() => setActive(i)}
                onClick={() => selectVal(o.name)}
              >
                {parts.map((p, idx) =>
                  q && p.toLowerCase() === q.toLowerCase() ? (
                    <span key={idx} className="font-semibold">
                      {p}
                    </span>
                  ) : (
                    <span key={idx}>{p}</span>
                  )
                )}
              </button>
            );
          })}
        </div>
      )}
    </div>
  );
}

export default function RoomsStep({
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
  const [rows, setRows] = useState<RoomForm[]>([]);

  const defaultRow: RoomForm = {
    room_type: "",
    room_title: "",
    preferred_for: [] as (number | string)[],
    no_of_rooms: 1,
    ac_availability: 1,
    status: 1,
    max_adult: 2,
    max_children: 0,
    check_in_time: "12:00",
    check_out_time: "11:00",
    // @ts-ignore numeric 1/2 for DB
    gst_type: 1,
    gst_percentage: 5,
    amenities: [],
    food_breakfast: false,
    food_lunch: false,
    food_dinner: false,
    gallery: null,
  };

  /* ========= Meta (GST types) ========= */
  const gstTypes = [
    { id: 1 as const, name: "Included" },
    { id: 2 as const, name: "Excluded" },
  ];

  /* ========= GST Percentages ========= */
  const { data: gstPercentsRaw = [] } = useQuery({
    queryKey: ["gstPercentages-room"],
    queryFn: () =>
      api
        .apiGetFirst([
          "/api/v1/meta/gst/percentages",
          "/api/v1/gst/percentages",
          "/api/v1/meta/gst/percents",
        ])
        .catch(() => [
          { id: 4, name: "5%", value: 5 },
          { id: 2, name: "18%", value: 18 },
          { id: 1, name: "12%", value: 12 },
          { id: 7, name: "0%", value: 0 },
        ]),
  });

  const gstPercentOptions = useMemo(() => {
    const raw = (gstPercentsRaw as any[]).map((p) => {
      const val = Number(p?.value ?? p?.id ?? p);
      const v = Number.isFinite(val) ? val : 0;
      return { id: v, label: `${v} % GST - %${v}` };
    });
    const PRIORITY = [5, 18, 12, 0, 28];
    raw.sort((a, b) => {
      const ai = PRIORITY.indexOf(a.id);
      const bi = PRIORITY.indexOf(b.id);
      if (ai !== -1 || bi !== -1)
        return (ai === -1 ? 999 : ai) - (bi === -1 ? 999 : bi);
      return a.id - b.id;
    });
    return raw;
  }, [gstPercentsRaw]);

  /* ========= Preferred For (static) ========= */
  const preferredForOptions = [
    { id: 1, name: "Family" },
    { id: 2, name: "Couple" },
    { id: 3, name: "Business" },
    { id: 4, name: "Group" },
  ];

  /* ========= Room Types ========= */
  const staticRoomTypesFallback = useMemo(
    () => [
      { id: 1, room_type: "Deluxe Room" },
      { id: 2, room_type: "Executive Room" },
      { id: 3, room_type: "Suite" },
    ],
    []
  );

  const useStaticOnly =
    typeof window !== "undefined" &&
    window.localStorage.getItem("USE_ROOM_TYPE_STATIC") === "1";

  const hid = useMemo(() => {
    const n = Number(hotelId);
    return Number.isFinite(n) ? n : null;
  }, [hotelId]);

  const { data: roomTypesRaw = [] } = useQuery({
    queryKey: ["hotel-room-types", hid, useStaticOnly],
    enabled: !useStaticOnly,
    queryFn: async () => {
      const endpoints: string[] = [];
      if (hid !== null) {
        endpoints.push(
          `/api/v1/hotels/${hid}/roomtypes`,
          `/api/v1/hotels/${hid}/room-types`
        );
        endpoints.push(
          `/api/v1/hotels/roomtypes?hotelId=${hid}`,
          `/api/v1/hotels/room-types?hotelId=${hid}`
        );
      }
      endpoints.push(`/api/v1/room-types`);
      try {
        const data = await api.apiGetFirst(endpoints);
        return data;
      } catch {
        console.warn(
          "[RoomsStep] Room types endpoint(s) unavailable; using static fallback."
        );
        return staticRoomTypesFallback;
      }
    },
  });

  const roomTypeOptions: Opt[] = useMemo(() => {
    const src = useStaticOnly ? staticRoomTypesFallback : (roomTypesRaw as any[]);
    return src.map((r: any) => ({
      id: r?.id ?? r?.roomtype_id ?? r?.room_type_id ?? r?.value ?? r,
      name: r?.room_type ?? r?.name ?? r?.title ?? String(r),
    }));
  }, [roomTypesRaw, useStaticOnly, staticRoomTypesFallback]);

  const roomTypeNameToId = useMemo(() => {
    const map = new Map<string, number | string>();
    roomTypeOptions.forEach((o) => map.set(o.name.toLowerCase(), o.id));
    return map;
  }, [roomTypeOptions]);

  /* ========= Inbuilt Amenities ========= */
  const { data: inbuiltAmenities = [] } = useQuery({
    queryKey: ["inbuilt-amenities-room"],
    queryFn: () =>
      api
        .apiGetFirst([
          "/api/v1/hotels/inbuilt-amenities",
          "/api/v1/meta/inbuilt-amenities",
          "/api/v1/inbuilt-amenities",
        ])
        .catch(() => []),
  });

  const amenityOpts: Opt[] = useMemo(
    () =>
      (inbuiltAmenities as any[]).map((a) => ({
        id:
          a?.id ??
          a?.inbuilt_amenity_type_id ??
          a?.amenity_type_id ??
          a?.value ??
          a,
        name:
          a?.inbuilt_amenity_title ??
          a?.inbuilt_amenties_title ??
          a?.title ??
          a?.name ??
          String(a),
      })),
    [inbuiltAmenities]
  );

  /* ========= Load existing rooms ========= */
  useQuery({
    queryKey: ["hotel-rooms", hotelId],
    enabled: !!hotelId,
    queryFn: async () => {
      const raw = await api
        .apiGetFirst([
          `/api/v1/hotels/${hotelId}/rooms`,
          `/api/v1/hotels/rooms?hotelId=${hotelId}`,
          `/api/v1/rooms?hotelId=${hotelId}`,
        ])
        .catch(() => []);
      const data = Array.isArray(raw) ? raw : raw?.items ?? raw?.data ?? raw?.rows ?? [];

      const mapped = (data as any[]).map((r, index) => {
        // preferred_for: "1,2" | ["1","2"] | "Family" → array
        const prefArr = Array.isArray(r?.preferred_for)
          ? (r.preferred_for as any[])
              .map((x) => String(x).trim())
              .filter(Boolean)
          : String(r?.preferred_for ?? "")
              .split(",")
              .map((s) => s.trim())
              .filter(Boolean);

        // string list "1,5,7" → [1,5,7]
        const amenitiesFromString = String(r?.inbuilt_amenities ?? "")
          .split(",")
          .map((s) => s.trim())
          .filter((s) => s.length > 0)
          .map((s) => (Number.isFinite(Number(s)) ? Number(s) : s));

        const ci = to24h(getTimeString(r?.check_in_time)) || "12:00";
        const co = to24h(getTimeString(r?.check_out_time)) || "11:00";

        const base: any = {
          room_type: r.room_type_id ?? r.roomtype_id ?? r.room_type ?? "",
          room_title: r.room_title ?? r.title ?? "",
          preferred_for: prefArr,
          no_of_rooms: r.no_of_rooms ?? r.no_of_rooms_available ?? r.count ?? 1,
          ac_availability:
            r.ac_availability ??
            r.air_conditioner_availability ??
            (r.ac ? 1 : 0),
          status: r.status ?? 1,
          max_adult: r.max_adult ?? r.total_max_adults ?? 2,
          max_children: r.max_children ?? r.total_max_childrens ?? 0,
          check_in_time: ci,
          check_out_time: co,
          // @ts-ignore keep numeric 1/2
          gst_type: toGstNum(r.gst_type ?? 1),
          gst_percentage: Number(r.gst_percentage ?? 5),
          amenities: Array.isArray(r.amenities)
            ? r.amenities
                .map(
                  (a: any) =>
                    a?.id ?? a?.inbuilt_amenity_type_id ?? a?.amenity_type_id ?? a
                )
                .filter((x: any) => x !== null && x !== undefined)
            : amenitiesFromString,
          food_breakfast:
            (r.food_breakfast ?? (r.breakfast_included === 1)) || false,
          food_lunch: (r.food_lunch ?? (r.lunch_included === 1)) || false,
          food_dinner: (r.food_dinner ?? (r.dinner_included === 1)) || false,
          gallery: null,
        };

        // preserve existing room_ref_code if present; otherwise generate one
        base.room_ref_code =
          r.room_ref_code ||
          generateRoomRefCode(hotelId ?? hid ?? "", index);

        return base as RoomForm;
      });

      setRows(mapped.length ? mapped : [defaultRow]);
      return mapped;
    },
  });

  /* ========= Handlers ========= */
  const handleChange = (i: number, field: keyof RoomForm, value: any) => {
    setRows((prev) => {
      const copy = [...prev] as any[];
      if (field === "gst_type") value = toGstNum(value);
      if (field === "gst_percentage") value = Number(value ?? 0);
      copy[i] = { ...copy[i], [field]: value };
      return copy as RoomForm[];
    });
  };

  const addRow = () =>
    setRows((p) => {
      const nextIndex = p.length;
      const refCode = generateRoomRefCode(hotelId ?? "", nextIndex);
      return [
        ...p,
        { ...(defaultRow as any), room_ref_code: refCode } as RoomForm,
      ];
    });

  const removeRow = (i: number) =>
    setRows((p) => (p.length === 1 ? p : p.filter((_, idx) => idx !== i)));

  /* ========= Save ========= */
  const saveMut = useMutation({
    mutationFn: async (items: RoomForm[]) => {
      const hotelIdNum = Number(hotelId);

      // ✅ Map UI → dvi_hotel_rooms column names & types
      const payload = items.map((r, index) => {
        // Resolve room_type_id from typed text or numeric value
        const rawType = (r as any).room_type;
        let room_type_id: number | null = null;
        const asNum = Number(rawType);
        if (Number.isFinite(asNum) && String(rawType).trim() !== "") {
          room_type_id = asNum;
        } else {
          const typed = String(rawType ?? "").trim().toLowerCase();
          if (typed && roomTypeNameToId.has(typed)) {
            const id = roomTypeNameToId.get(typed)!;
            const n = Number(id);
            room_type_id = Number.isFinite(n) ? n : null;
          }
        }

        const preferred_for = Array.isArray(r.preferred_for)
          ? (r.preferred_for as any[])
              .map(String)
              .filter(Boolean)
              .join(",")
          : (r as any).preferred_for || null;

        const inbuilt_amenitiesStr = (r.amenities ?? [])
          .map((id) => Number(id))
          .filter((n) => Number.isFinite(n))
          .join(",");

        // keep existing room_ref_code if present; otherwise generate a new one
        const room_ref_code =
          (r as any).room_ref_code ||
          generateRoomRefCode(hotelIdNum || hotelId, index);

        return {
          hotel_id: hotelIdNum,
          room_type_id,
          room_title: r.room_title || null,
          preferred_for,
          no_of_rooms_available: Number(r.no_of_rooms || 1),
          air_conditioner_availability: Number(r.ac_availability || 0),
          status: Number(r.status || 1),
          total_max_adults: Number(r.max_adult || 0),
          total_max_childrens: Number(r.max_children || 0),
          check_in_time:
            to12h((r as any).check_in_time) || (r as any).check_in_time || null,
          check_out_time:
            to12h((r as any).check_out_time) ||
            (r as any).check_out_time ||
            null,
          gst_type: toGstNum((r as any).gst_type),
          gst_percentage: Number(r.gst_percentage || 0),
          inbuilt_amenities: inbuilt_amenitiesStr || null,
          breakfast_included: r.food_breakfast ? 1 : 0,
          lunch_included: r.food_lunch ? 1 : 0,
          dinner_included: r.food_dinner ? 1 : 0,
          room_ref_code,
          // createdby/createdon/updatedon/deleted handled server-side
        };
      });

      const endpoints = [
        `/api/v1/hotels/${hotelId}/rooms/bulk`,
        `/api/v1/hotels/${hotelId}/rooms`,
        `/api/v1/rooms/bulk`,
      ];
      let lastErr: any;
      let jsonResult: any;

      // 1) Save room rows as before
      for (const p of endpoints) {
        try {
          try {
            jsonResult = await api.apiPost(p, { items: payload });
          } catch {
            jsonResult = await api.apiPost(p, payload);
          }
          break;
        } catch (e) {
          lastErr = e;
        }
      }
      if (!jsonResult) {
        throw lastErr || new Error("No rooms endpoint available");
      }

      // 2) NEW: upload room gallery files (non-blocking for main flow)
      try {
        // reload rooms to map room_ref_code → room_ID
        const rawAfter = await api
          .apiGetFirst([
            `/api/v1/hotels/${hotelId}/rooms`,
            `/api/v1/hotels/rooms?hotelId=${hotelId}`,
            `/api/v1/rooms?hotelId=${hotelId}`,
          ])
          .catch(() => []);
        const roomsAfter = Array.isArray(rawAfter)
          ? rawAfter
          : rawAfter?.items ?? rawAfter?.data ?? rawAfter?.rows ?? [];

        const byRef = new Map<string, any>();
        (roomsAfter as any[]).forEach((r: any) => {
          if (r?.room_ref_code) {
            byRef.set(String(r.room_ref_code), r);
          }
        });

        const uploadPromises: Promise<any>[] = [];

        items.forEach((row, index) => {
          const filesLike: any = (row as any).gallery;
          if (!filesLike || (filesLike as FileList).length === 0) return;

          const roomRefCode = (payload[index] as any).room_ref_code as string;
          if (!roomRefCode) return;

          const match = byRef.get(String(roomRefCode));
          const roomId = match?.room_ID ?? match?.room_id;
          if (!roomId) return;

          const fd = new FormData();
          Array.from(filesLike as FileList).forEach((f) => {
            fd.append("files", f);
          });
          fd.append("room_ref_code", roomRefCode);

          const base = API_BASE_URL.replace(/\/+$/, "");
          const url = `${base}/hotels/${hotelIdNum}/rooms/${roomId}/gallery`;

          const token = getToken();
          const headers: Record<string, string> = {};
          if (token) headers["Authorization"] = `Bearer ${token}`;

          uploadPromises.push(
            fetch(url, {
              method: "POST",
              headers,
              body: fd,
            }).then(async (res) => {
              if (!res.ok) {
                const text = await res.text().catch(() => "");
                console.error(
                  "[RoomsStep] Room gallery upload failed",
                  res.status,
                  text
                );
              }
            })
          );
        });

        if (uploadPromises.length) {
          await Promise.all(uploadPromises);
        }
      } catch (err) {
        console.error("[RoomsStep] Room gallery upload error", err);
      }

      return jsonResult;
    },
    onSuccess: () => {
      qc.invalidateQueries();
      alert("✅ Rooms saved");
      onNext();
    },
    onError: (e: any) => alert(`Failed: ${e?.message || "Unknown error"}`),
  });

  return (
    <>
      <h3 className="text-pink-600 font-semibold mb-4">Rooms</h3>

      <div className="grid grid-cols-12 gap-4">
        {rows.map((row, idx) => (
          <div key={idx} className="col-span-12 border-b border-dashed pb-4 mb-4">
            <div className="flex justify-between items-center mb-2">
              <h6 className="font-semibold text-sm">Room #{idx + 1}</h6>
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
              {/* Room Type (custom autocomplete) */}
              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">Room Type *</label>
                <RoomTypeAutocomplete
                  value={String(row.room_type ?? "")}
                  onChange={(val) => handleChange(idx, "room_type", val)}
                  options={roomTypeOptions}
                  dropdownId={`roomtype-dd-${idx}`}
                />
              </div>

              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">Room Title *</label>
                <input
                  className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                  value={row.room_title}
                  onChange={(e) => handleChange(idx, "room_title", e.target.value)}
                  placeholder="Enter the Room Title"
                />
              </div>

              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">Prefered For *</label>
                <AmenityPicker
                  options={preferredForOptions}
                  value={
                    Array.isArray((row as any).preferred_for)
                      ? ((row as any).preferred_for as any[])
                      : []
                  }
                  onChange={(ids) =>
                    handleChange(idx, "preferred_for" as any, ids)
                  }
                  placeholder="Select audience (multi)"
                />
              </div>

              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">
                  No of Rooms Availability *
                </label>
                <input
                  className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                  value={row.no_of_rooms}
                  onChange={(e) => handleChange(idx, "no_of_rooms", e.target.value)}
                  placeholder="Enter the No. of Rooms Available"
                />
              </div>

              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">AC Availability *</label>
                <select
                  className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                  value={row.ac_availability}
                  onChange={(e) =>
                    handleChange(idx, "ac_availability", Number(e.target.value))
                  }
                >
                  <option value={1}>Yes</option>
                  <option value={0}>No</option>
                </select>
              </div>

              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">Status *</label>
                <select
                  className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                  value={row.status}
                  onChange={(e) => handleChange(idx, "status", Number(e.target.value))}
                >
                  <option value={1}>Active</option>
                  <option value={0}>In-Active</option>
                </select>
              </div>

              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">Max Adult *</label>
                <input
                  className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                  value={row.max_adult}
                  onChange={(e) => handleChange(idx, "max_adult", e.target.value)}
                  placeholder="Enter the Max Adult"
                />
              </div>

              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">Max Children *</label>
                <input
                  className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                  value={row.max_children}
                  onChange={(e) =>
                    handleChange(idx, "max_children", e.target.value)
                  }
                  placeholder="Enter the total children"
                />
              </div>

              {/* Proper time pickers */}
              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">Check-In Time *</label>
                <input
                  type="time"
                  step={60}
                  className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                  value={(row as any).check_in_time}
                  onChange={(e) =>
                    handleChange(idx, "check_in_time" as any, e.target.value)
                  }
                />
                <p className="text-[10px] text-gray-500 mt-1">
                  Saved as {to12h((row as any).check_in_time)}
                </p>
              </div>

              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">Check-Out Time *</label>
                <input
                  type="time"
                  step={60}
                  className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                  value={(row as any).check_out_time}
                  onChange={(e) =>
                    handleChange(idx, "check_out_time" as any, e.target.value)
                  }
                />
                <p className="text-[10px] text-gray-500 mt-1">
                  Saved as {to12h((row as any).check_out_time)}
                </p>
              </div>

              {/* GST */}
              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">GST Type *</label>
                <select
                  className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                  value={(row as any).gst_type}
                  onChange={(e) =>
                    handleChange(idx, "gst_type" as any, Number(e.target.value))
                  }
                >
                  {gstTypes.map((g) => (
                    <option key={g.id} value={g.id}>
                      {g.name}
                    </option>
                  ))}
                </select>
              </div>

              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">
                  GST Percentage *
                </label>
                <select
                  className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                  value={row.gst_percentage}
                  onChange={(e) =>
                    handleChange(idx, "gst_percentage", Number(e.target.value))
                  }
                >
                  {gstPercentOptions.map((opt) => (
                    <option key={opt.id} value={opt.id}>
                      {opt.label}
                    </option>
                  ))}
                </select>
              </div>

              {/* Amenities */}
              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">
                  Inbuilt Amenities *
                </label>
                <AmenityPicker
                  options={amenityOpts}
                  value={(row.amenities as any[]) ?? []}
                  onChange={(ids) => handleChange(idx, "amenities", ids as any[])}
                  placeholder="Choose amenities"
                />
              </div>

              <div className="col-span-12 md:col-span-3">
                <label className="block text-xs font-medium">Room Gallery *</label>
                <input
                  type="file"
                  multiple
                  className="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                  onChange={(e) =>
                    handleChange(
                      idx,
                      "gallery" as any,
                      (e.target as HTMLInputElement).files
                    )
                  }
                />
              </div>

              {/* Food Included? */}
              <div className="col-span-12">
                <div className="text-xs font-medium mb-2">
                  Food Included? (Optional)
                </div>
                <div className="flex items-center gap-6">
                  <label className="inline-flex items-center gap-2 text-sm">
                    <input
                      type="checkbox"
                      checked={row.food_breakfast}
                      onChange={(e) =>
                        handleChange(idx, "food_breakfast", e.target.checked)
                      }
                    />
                    Breakfast
                  </label>
                  <label className="inline-flex items-center gap-2 text-sm">
                    <input
                      type="checkbox"
                      checked={row.food_lunch}
                      onChange={(e) =>
                        handleChange(idx, "food_lunch", e.target.checked)
                      }
                    />
                    Lunch
                  </label>
                  <label className="inline-flex items-center gap-2 text-sm">
                    <input
                      type="checkbox"
                      checked={row.food_dinner}
                      onChange={(e) =>
                        handleChange(idx, "food_dinner", e.target.checked)
                      }
                    />
                    Dinner
                  </label>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className="flex justify-between items-center mt-4">
        <button
          type="button"
          onClick={addRow}
          className="px-4 py-2 rounded-lg border border-dashed border-purple-300 text-purple-700 text-sm"
        >
          + Add Room
        </button>
      </div>

      <div className="flex items-center justify-between mt-8">
        <button
          type="button"
          onClick={onPrev}
          className="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300"
        >
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
