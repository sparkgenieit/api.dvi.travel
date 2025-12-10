// FILE: src/pages/hotspots/HotspotPreview.tsx

import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { hotspotService } from "@/services/hotspotService";

const DAYS = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];

type Slot = { start?: string; end?: string };
type DayOH = { open24hrs?: boolean; closed24hrs?: boolean; slots?: Slot[] };

function hasAmPm(s: string) {
  return /am|pm/i.test(s);
}
/** Accepts "09:00", "9:00", "09:00 AM", "9:00 pm" → returns "9:00 AM" */
function to12h(t?: string): string {
  if (!t) return "";
  const raw = t.trim();
  if (!raw) return "";
  if (hasAmPm(raw)) {
    const m = raw.match(/^(\d{1,2})\s*[:.]\s*(\d{2})\s*(am|pm)$/i);
    if (m) {
      let hh = parseInt(m[1], 10);
      const mm = m[2];
      const ap = m[3].toUpperCase();
      if (hh === 0) hh = 12;
      if (hh > 12) hh = hh - 12;
      return `${hh}:${mm} ${ap}`;
    }
    return raw.replace(/\s*(am|pm)\s*$/i, (_, ap) => ` ${String(ap).toUpperCase()}`);
  }
  const m = raw.match(/^(\d{1,2})\s*[:.]\s*(\d{2})$/);
  if (!m) return raw;
  let hh = parseInt(m[1], 10);
  const mm = m[2];
  const ap = hh >= 12 ? "PM" : "AM";
  if (hh === 0) hh = 12;
  else if (hh > 12) hh = hh - 12;
  return `${hh}:${mm} ${ap}`;
}

// ---- helpers for map/embed
function hasLatLng(lat?: any, lng?: any) {
  const ok = (v: any) =>
    v !== null &&
    v !== undefined &&
    String(v).trim() !== "" &&
    !Number.isNaN(Number(v));
  return ok(lat) && ok(lng);
}
function mapEmbedSrc(lat?: any, lng?: any, address?: string | null) {
  if (hasLatLng(lat, lng)) {
    const qs = encodeURIComponent(`${lat},${lng}`);
    return `https://www.google.com/maps?q=${qs}&hl=en&z=14&output=embed`;
    }
  if (address && address.trim()) {
    const qs = encodeURIComponent(address.trim());
    return `https://www.google.com/maps?q=${qs}&hl=en&z=14&output=embed`;
  }
  return "";
}
function mapViewHref(lat?: any, lng?: any, address?: string | null) {
  if (hasLatLng(lat, lng)) {
    const q = encodeURIComponent(`${lat},${lng}`);
    return `https://www.google.com/maps?q=${q}`;
  }
  if (address && address.trim()) {
    return `https://www.google.com/maps?q=${encodeURIComponent(address.trim())}`;
  }
  return "";
}

export default function HotspotPreview() {
  const navigate = useNavigate();
  const { id } = useParams();
  const [data, setData] = useState<null | Awaited<ReturnType<typeof hotspotService.getHotspotForm>>>(null);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (id) load(id);
  }, [id]);

  async function load(hotspotId: string) {
    try {
      setLoading(true);
      const j = await hotspotService.getHotspotForm(hotspotId);
      setData(j);
    } finally {
      setLoading(false);
    }
  }

  if (loading || !data) return <div className="p-6">Loading...</div>;

  const p = data.payload;
  const gallery = (p.gallery || []).map(
    (g) => `${hotspotService.fileBase()}/uploads/hotspot_gallery/${g.name}`
  );

  const embedSrc = mapEmbedSrc(p.hotspot_latitude, p.hotspot_longitude, p.hotspot_address);
  const viewHref = mapViewHref(p.hotspot_latitude, p.hotspot_longitude, p.hotspot_address);

  return (
    <div className="p-6 space-y-6">
      {/* Hotspot Details */}
      <div className="bg-white rounded-lg border p-6 space-y-6">
        <h2 className="text-lg font-semibold text-primary">Hotspot Details</h2>

        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div className="md:col-span-3 space-y-4">
            <div className="grid grid-cols-2 gap-4">
              <div>
                <p className="text-sm text-muted-foreground">Hotspot Type</p>
                <p className="font-medium">{p.hotspot_type ?? "-"}</p>
              </div>
              <div>
                <p className="text-sm text-muted-foreground">Hotspot Name</p>
                <p className="font-medium">{p.hotspot_name}</p>
              </div>

              <div className="col-span-2">
                <p className="text-sm text-muted-foreground">Hotspot Place</p>
                <p className="font-medium">{(p.hotspot_location_list || []).join(", ")}</p>
              </div>

              <div className="col-span-2">
                <p className="text-sm text-muted-foreground">Address</p>
                <p className="font-medium break-words">{p.hotspot_address ?? "-"}</p>
              </div>

              <div className="col-span-2">
                <p className="text-sm text-muted-foreground">Description</p>
                <p className="font-medium whitespace-pre-wrap">
                  {p.hotspot_description ?? "-"}
                </p>
              </div>

              <div>
                <p className="text-sm text-muted-foreground">Landmark</p>
                <p className="font-medium">{p.hotspot_landmark ?? "-"}</p>
              </div>

              <div>
                <p className="text-sm text-muted-foreground">Adult Entry Cost</p>
                <p className="font-medium">{Number(p.hotspot_adult_entry_cost ?? 0).toFixed(2)}</p>
              </div>
              <div>
                <p className="text-sm text-muted-foreground">Child Entry Cost</p>
                <p className="font-medium">{Number(p.hotspot_child_entry_cost ?? 0).toFixed(2)}</p>
              </div>
              <div>
                <p className="text-sm text-muted-foreground">Infant Entry Cost</p>
                <p className="font-medium">{Number(p.hotspot_infant_entry_cost ?? 0).toFixed(2)}</p>
              </div>
              <div>
                <p className="text-sm text-muted-foreground">Foreigner Adult Entry Cost</p>
                <p className="font-medium">{Number(p.hotspot_foreign_adult_entry_cost ?? 0).toFixed(2)}</p>
              </div>
              <div>
                <p className="text-sm text-muted-foreground">Foreigner Child Entry Cost</p>
                <p className="font-medium">{Number(p.hotspot_foreign_child_entry_cost ?? 0).toFixed(2)}</p>
              </div>
              <div>
                <p className="text-sm text-muted-foreground">Foreigner Infant Entry Cost</p>
                <p className="font-medium">{Number(p.hotspot_foreign_infant_entry_cost ?? 0).toFixed(2)}</p>
              </div>

              <div>
                <p className="text-sm text-muted-foreground">Rating</p>
                <p className="font-medium">{Number(p.hotspot_rating ?? 0)}</p>
              </div>

              <div className="col-span-2">
                <p className="text-sm text-muted-foreground">Hotspot Video URL</p>
                <p className="font-medium text-blue-600 break-all">
                  {p.hotspot_video_url ?? "-"}
                </p>
              </div>
            </div>
          </div>

          <div>
            <p className="text-sm text-muted-foreground mb-2">Hotspot Images</p>
            <div className="space-y-2">
              {gallery.map((img, i) => (
                <img key={i} src={img} alt="" className="w-full rounded border object-cover" />
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Vehicle Parking Charge Details (restored) */}
      <div className="bg-white rounded-lg border p-6 space-y-4">
        <h2 className="text-lg font-semibold text-primary">Vehicle Parking Charge Details</h2>
        {!(p.parkingCharges && p.parkingCharges.length) ? (
          <p className="text-sm text-muted-foreground">No parking charges configured.</p>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            {(p.parkingCharges || []).map((row: any, i: number) => (
              <div key={i}>
                <p className="text-sm text-muted-foreground">
                  VehicleType #{row.vehicleTypeId}
                </p>
                <p className="font-medium">{Number(row.charge ?? 0).toFixed(2)}</p>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Location + Opening Hours side-by-side */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Location */}
        <div className="bg-white rounded-lg border p-6 space-y-4">
          <h2 className="text-lg font-semibold text-primary">Location</h2>
          {embedSrc ? (
            <div className="w-full h-[340px] rounded-md overflow-hidden border">
              <iframe
                title="map"
                src={embedSrc}
                className="w-full h-full"
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
              />
            </div>
          ) : (
            <p className="text-sm text-muted-foreground">No coordinates/address available.</p>
          )}
          {viewHref && (
            <a
              className="text-sm text-blue-600 underline"
              href={viewHref}
              target="_blank"
              rel="noreferrer"
            >
              View larger map
            </a>
          )}
        </div>

        {/* Opening Hours */}
        <div className="bg-white rounded-lg border p-6 space-y-4">
          <h2 className="text-lg font-semibold text-primary">Opening Hours</h2>

          {/* header */}
          <div className="grid grid-cols-12 font-medium text-muted-foreground bg-muted/40 rounded-md px-4 py-2">
            <div className="col-span-4">DAY</div>
            <div className="col-span-8 text-center">OPERATING HOURS</div>
          </div>

          <div className="space-y-2">
            {DAYS.map((day) => {
              const key = day.toLowerCase();
              const oh = ((p as any).operatingHours?.[key] ?? {}) as DayOH;

              let content: JSX.Element | string = "—";
              if (oh.open24hrs) {
                content = (
                  <span className="inline-block text-xs px-3 py-1 rounded-md bg-pink-50 text-pink-600 font-semibold">
                    Open 24 Hours
                  </span>
                );
              } else if (oh.closed24hrs) {
                content = (
                  <span className="inline-block text-xs px-3 py-1 rounded-md bg-pink-50 text-pink-600 font-semibold">
                    Closed
                  </span>
                );
              } else if (oh.slots && oh.slots.length) {
                const labels = oh.slots
                  .filter((s) => s.start && s.end)
                  .map((s) => `${to12h(s.start)}-${to12h(s.end)}`);
                content = (
                  <div className="flex flex-wrap gap-2 justify-center">
                    {labels.map((lbl, i) => (
                      <span
                        key={i}
                        className="inline-block text-xs px-3 py-1 rounded-md bg-pink-50 text-pink-600 font-semibold"
                      >
                        {lbl}
                      </span>
                    ))}
                  </div>
                );
              }

              return (
                <div key={day} className="grid grid-cols-12 items-center border rounded-md px-4 py-3">
                  <div className="col-span-4 font-medium">{day}</div>
                  <div className="col-span-8 flex justify-center">{content}</div>
                </div>
              );
            })}
          </div>
        </div>
      </div>

      <div className="flex justify-end">
        <Button onClick={() => navigate("/hotspots")}>Back</Button>
      </div>
    </div>
  );
}
