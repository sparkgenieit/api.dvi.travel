// FILE: src/pages/hotspots/HotspotForm.tsx

import { useEffect, useMemo, useRef, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Switch } from "@/components/ui/switch";
import { hotspotService, HotspotFormData } from "@/services/hotspotService";
import { toast } from "sonner";

/* -------------------------------------------------------------------------- */
/*                             Time-picker helpers                             */
/* -------------------------------------------------------------------------- */

type OpeningSlot = { start: string; end: string }; // "hh:mm AM" (12-hr)
type OpeningDay = {
  is24Hours?: boolean;
  closed24Hours?: boolean;
  timeSlots: OpeningSlot[];
};

const HOURS_12 = Array.from({ length: 12 }, (_, i) => String(i + 1));
const MINUTES = Array.from({ length: 60 }, (_, i) => String(i).padStart(2, "0"));

function fmt12(h: number, m: number, ap: "AM" | "PM") {
  const hh = Math.max(1, Math.min(12, h));
  const mm = Math.max(0, Math.min(59, m));
  return `${String(hh).padStart(2, "0")}:${String(mm).padStart(2, "0")} ${ap}`;
}
function parse12(s: string) {
  const m = /^\s*(\d{1,2}):(\d{2})\s*(AM|PM)\s*$/i.exec(s || "");
  if (!m) return { h: 12, mm: 0, ap: "AM" as "AM" | "PM" };
  let h = Math.max(1, Math.min(12, Number(m[1])));
  let mm = Math.max(0, Math.min(59, Number(m[2])));
  const ap = m[3].toUpperCase() as "AM" | "PM";
  return { h, mm, ap };
}

/** Small, dependency-free 12-hour picker */
function TimePickerField({
  value,
  onChange,
  placeholder = "hh:mm",
  disabled,
}: {
  value: string;
  onChange: (v: string) => void;
  placeholder?: string;
  disabled?: boolean;
}) {
  const [open, setOpen] = useState(false);
  const { h, mm, ap } = parse12(value);
  const [H, setH] = useState(h);
  const [M, setM] = useState(mm);
  const [AP, setAP] = useState<"AM" | "PM">(ap);
  const wrapperRef = useRef<HTMLDivElement | null>(null);

  // Keep internal picker state in sync when parent `value` changes
  useEffect(() => {
    const parsed = parse12(value);
    setH(parsed.h);
    setM(parsed.mm);
    setAP(parsed.ap);
  }, [value]);

  // ❌ Removed outside-click close handler because shadcn <Select>
  // uses a portal and clicks inside the dropdown were treated as "outside",
  // which immediately closed the picker and prevented value from sticking.

  // Commit immediately on any change (user selection persists even without pressing "Set")
  const commit = (nh = H, nm = M, nap = AP) => {
    onChange(fmt12(nh, nm, nap));
  };

  return (
    <div ref={wrapperRef} className="relative">
      <Input
        value={value || ""}
        onFocus={() => !disabled && setOpen(true)}
        onClick={() => !disabled && setOpen(true)}
        onChange={() => {
          /* prevent manual typing from desyncing; picker controls value */
        }}
        placeholder={placeholder}
        disabled={disabled}
        className="w-[140px]"
      />
      {open && !disabled && (
        <div className="absolute z-20 mt-2 rounded-md border bg-white p-3 shadow-lg w-[320px]">
          <div className="flex items-center gap-3">
            {/* Hour */}
            <Select
              value={String(H)}
              onValueChange={(v) => {
                const nh = Math.max(1, Math.min(12, Number(v)));
                setH(nh);
                commit(nh, M, AP);
              }}
            >
              <SelectTrigger className="w-[80px]" aria-label="Hour">
                <SelectValue />
              </SelectTrigger>
              <SelectContent className="max-h-56">
                {HOURS_12.map((hh) => (
                  <SelectItem key={hh} value={hh}>
                    {hh}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>

            <span className="text-xl leading-none">:</span>

            {/* Minute */}
            <Select
              value={String(M).padStart(2, "0")}
              onValueChange={(v) => {
                const nm = Math.max(0, Math.min(59, Number(v)));
                setM(nm);
                commit(H, nm, AP);
              }}
            >
              <SelectTrigger className="w-[80px]" aria-label="Minute">
                <SelectValue />
              </SelectTrigger>
              <SelectContent className="max-h-56">
                {MINUTES.map((mm) => (
                  <SelectItem key={mm} value={mm}>
                    {mm}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>

            {/* AM/PM */}
            <Select
              value={AP}
              onValueChange={(v) => {
                const nap = (v as "AM" | "PM") ?? "AM";
                setAP(nap);
                commit(H, M, nap);
              }}
            >
              <SelectTrigger className="w-[80px]" aria-label="AM/PM">
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="AM">AM</SelectItem>
                <SelectItem value="PM">PM</SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div className="mt-3 flex justify-end gap-2">
            <Button
              type="button"
              variant="secondary"
              onClick={() => {
                // keep already-committed value; just close
                setOpen(false);
              }}
            >
              Close
            </Button>
          </div>
        </div>
      )}
    </div>
  );
}

/* -------------------------------------------------------------------------- */

const DAYS = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];

export default function HotspotForm() {
  const navigate = useNavigate();
  const { id } = useParams();
  const isEdit = !!id;

  const [form, setForm] = useState<Partial<HotspotFormData>>({
    name: "",
    type: null,
    priority: 0,
    description: "",
    landmark: "",
    address: "",
    adultCost: 0,
    childCost: 0,
    infantCost: 0,
    foreignAdultCost: 0,
    foreignChildCost: 0,
    foreignInfantCost: 0,
    rating: 0,
    duration: "01:00",
    latitude: "",
    longitude: "",
    videoUrl: "",
    locations: [],
    galleryImages: [],
    parkingCharges: {},
    openingHours: DAYS.reduce(
      (acc, d) => ({ ...acc, [d]: { is24Hours: false, closed24Hours: false, timeSlots: [] } }),
      {} as Record<string, OpeningDay>
    ),
  });

  const [options, setOptions] = useState<{
    types: string[];
    locations: string[];
    vehicleTypes: Array<{ id: number; name: string }>;
  }>({ types: [], locations: [], vehicleTypes: [] });

  const [loading, setLoading] = useState(false);

  useEffect(() => {
    loadOptions();
  }, []);
  useEffect(() => {
    if (isEdit && id) loadEdit(id);
  }, [id, isEdit]);

  async function loadOptions() {
    try {
      const j = await hotspotService.getFormOptions();
      setOptions({
        types: j.hotspotTypes || [],
        locations: j.locations || [],
        vehicleTypes: j.vehicleTypes || [],
      });
    } catch {
      toast.error("Failed to load options");
    }
  }

  async function loadEdit(hotspotId: string) {
    try {
      setLoading(true);
      const j = await hotspotService.getHotspotForm(hotspotId);
      const p = j.payload;
      setForm({
        id: p.id,
        name: p.hotspot_name,
        type: p.hotspot_type,
        priority: p.hotspot_priority ?? 0,
        description: p.hotspot_description ?? "",
        landmark: p.hotspot_landmark ?? "",
        address: p.hotspot_address ?? "",
        adultCost: Number(p.hotspot_adult_entry_cost ?? 0),
        childCost: Number(p.hotspot_child_entry_cost ?? 0),
        infantCost: Number(p.hotspot_infant_entry_cost ?? 0),
        foreignAdultCost: Number(p.hotspot_foreign_adult_entry_cost ?? 0),
        foreignChildCost: Number(p.hotspot_foreign_child_entry_cost ?? 0),
        foreignInfantCost: Number(p.hotspot_foreign_infant_entry_cost ?? 0),
        rating: Number(p.hotspot_rating ?? 0),
        latitude: p.hotspot_latitude ?? "",
        longitude: p.hotspot_longitude ?? "",
        videoUrl: p.hotspot_video_url ?? "",
        locations: p.hotspot_location_list ?? [],
        galleryImages: (p.gallery || []).map(
          (g) => `${hotspotService.fileBase()}/uploads/hotspot_gallery/${g.name}`
        ),
        parkingCharges: Object.fromEntries(
          (p.parkingCharges || []).map((pc) => [String(pc.vehicleTypeId), Number(pc.charge)])
        ),
        openingHours: Object.fromEntries(
          Object.entries(p.operatingHours || {}).map(([k, v]: any) => [
            k,
            {
              is24Hours: !!v.open24hrs,
              closed24Hours: !!v.closed24hrs,
              timeSlots: (v.slots || []).map((s: any) => ({ start: s.start, end: s.end })),
            } as OpeningDay,
          ])
        ),
      });
    } catch {
      toast.error("Failed to load hotspot");
    } finally {
      setLoading(false);
    }
  }

  async function onUploadFiles(files: FileList | null) {
    if (!files || !files.length) return;
    if (!form.id) {
      toast.message("Images will be uploaded after you save the hotspot.");
      return;
    }
    try {
      const uploads = await Promise.all(
        Array.from(files).map((f) => hotspotService.uploadGallery(form.id!, f))
      );
      setForm((prev) => ({
        ...prev,
        galleryImages: [
          ...(prev.galleryImages || []),
          // support both { url } and { name } shapes
          ...uploads.map((u: any) =>
            u?.url
              ? u.url
              : `${hotspotService.fileBase()}/uploads/hotspot_gallery/${u.name}`
          ),
        ],
      }));
    } catch {
      toast.error("Failed to upload images");
    }
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    try {
      setLoading(true);

      // Convert parkingCharges object { [vehicleTypeId]: charge } -> array [{vehicleTypeId, charge}]
      const parkingChargesArray = Object.entries(form.parkingCharges ?? {}).map(
        ([vehicleTypeId, charge]) => ({
          vehicleTypeId: Number(vehicleTypeId),
          charge: Number(charge ?? 0),
        })
      );

      // Build payload aligned to backend
      const payload = {
        ...form,
        parkingCharges: parkingChargesArray,
        // ensure operatingHours stays as is
      };

      await hotspotService.saveHotspot(payload as any);
      toast.success("Hotspot saved successfully");
      navigate("/hotspots");
    } catch {
      toast.error("Failed to save hotspot");
    } finally {
      setLoading(false);
    }
  }

  const vehNamesById = useMemo(
    () => Object.fromEntries(options.vehicleTypes.map((v) => [String(v.id), v.name])),
    [options]
  );

  return (
    <div className="p-6">
      <form onSubmit={handleSubmit} className="space-y-6">
        {/* ----------------------------- Basic Info ---------------------------- */}
        <div className="bg-white rounded-lg border p-6 space-y-4" data-section="basic-info">
          <h2 className="text-lg font-semibold text-primary">Basic Info</h2>

          {/* Row 1 */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <Label htmlFor="name">Hotspot Name *</Label>
              <Input
                id="name"
                value={form.name || ""}
                onChange={(e) => setForm({ ...form, name: e.target.value })}
                required
                placeholder="Hotspot Name"
              />
            </div>
            <div>
              <Label htmlFor="type">Hotspot Type *</Label>
              <Select value={form.type ?? ""} onValueChange={(v) => setForm({ ...form, type: v })}>
                <SelectTrigger>
                  <SelectValue placeholder="Choose Hotspot Type" />
                </SelectTrigger>
                <SelectContent>
                  {options.types.map((t) => (
                    <SelectItem key={t} value={t}>
                      {t}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div>
              <Label htmlFor="adultCost">Adult Entry Cost (₹)*</Label>
              <Input
                id="adultCost"
                type="number"
                value={form.adultCost ?? 0}
                onChange={(e) => setForm({ ...form, adultCost: Number(e.target.value) })}
                placeholder="Adult Entry Cost"
                required
              />
            </div>
          </div>

          {/* Row 2 */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <Label htmlFor="childCost">Child Entry Cost (₹)*</Label>
              <Input
                id="childCost"
                type="number"
                value={form.childCost ?? 0}
                onChange={(e) => setForm({ ...form, childCost: Number(e.target.value) })}
                placeholder="Child Entry Cost"
                required
              />
            </div>
            <div>
              <Label htmlFor="infantCost">Infant Entry Cost (₹)*</Label>
              <Input
                id="infantCost"
                type="number"
                value={form.infantCost ?? 0}
                onChange={(e) => setForm({ ...form, infantCost: Number(e.target.value) })}
                placeholder="Infant Entry Cost"
                required
              />
            </div>
            <div>
              <Label htmlFor="foreignAdultCost">Foreign Adult Entry Cost (₹)*</Label>
              <Input
                id="foreignAdultCost"
                type="number"
                value={form.foreignAdultCost ?? 0}
                onChange={(e) =>
                  setForm({ ...form, foreignAdultCost: Number(e.target.value) })
                }
                placeholder="Foreign Adult Entry Cost"
                required
              />
            </div>
          </div>

          {/* Row 3 */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <Label htmlFor="foreignChildCost">Foreign Child Entry Cost (₹)*</Label>
              <Input
                id="foreignChildCost"
                type="number"
                value={form.foreignChildCost ?? 0}
                onChange={(e) =>
                  setForm({ ...form, foreignChildCost: Number(e.target.value) })
                }
                placeholder="Foreign Child Entry Cost"
                required
              />
            </div>
            <div>
              <Label htmlFor="foreignInfantCost">Foreign Infant Entry Cost (₹)*</Label>
              <Input
                id="foreignInfantCost"
                type="number"
                value={form.foreignInfantCost ?? 0}
                onChange={(e) =>
                  setForm({ ...form, foreignInfantCost: Number(e.target.value) })
                }
                placeholder="Foreign Infant Entry Cost"
                required
              />
            </div>
            <div>
              <Label htmlFor="rating">Rating *</Label>
              <Input
                id="rating"
                type="number"
                step={0.1}
                value={form.rating ?? 0}
                onChange={(e) => setForm({ ...form, rating: Number(e.target.value) })}
                placeholder="Rating"
                required
              />
            </div>
          </div>

          {/* Row 4 */}
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <Label htmlFor="priority">Hotspot Priority *</Label>
              <Input
                id="priority"
                type="number"
                value={form.priority ?? 0}
                onChange={(e) => setForm({ ...form, priority: Number(e.target.value) })}
                placeholder="Hotspot Priority"
                required
              />
            </div>
            <div>
              <Label htmlFor="latitude">Latitude *</Label>
              <Input
                id="latitude"
                value={form.latitude || ""}
                onChange={(e) => setForm({ ...form, latitude: e.target.value })}
                placeholder="Latitude"
                required
              />
            </div>
            <div>
              <Label htmlFor="longitude">Longitude *</Label>
              <Input
                id="longitude"
                value={form.longitude || ""}
                onChange={(e) => setForm({ ...form, longitude: e.target.value })}
                placeholder="Longitude"
                required
              />
            </div>
            <div>
              <Label htmlFor="duration">Duration *</Label>
              <Input
                id="duration"
                type="time"
                step={60}
                value={form.duration || "01:00"}
                onChange={(e) =>
                  setForm({ ...form, duration: (e.target as HTMLInputElement).value })
                }
                onBlur={(e) => {
                  const v = (e.target as HTMLInputElement).value || "01:00";
                  const [h = "01", m = "00"] = v.split(":");
                  const hh = String(Math.max(0, Math.min(23, Number(h)))).padStart(2, "0");
                  const mm = String(Math.max(0, Math.min(59, Number(m)))).padStart(2, "0");
                  if (`${hh}:${mm}` !== form.duration) {
                    setForm((f) => ({ ...f, duration: `${hh}:${mm}` }));
                  }
                }}
                placeholder="Duration"
                required
              />
            </div>
          </div>

          {/* Row 5 */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <Label htmlFor="landmark">Hotspot Landmark *</Label>
              <Textarea
                id="landmark"
                value={form.landmark || ""}
                onChange={(e) => setForm({ ...form, landmark: e.target.value })}
                placeholder="Hotspot Landmark"
                rows={3}
                required
              />
            </div>
            <div>
              <Label htmlFor="address">Address *</Label>
              <Textarea
                id="address"
                value={form.address || ""}
                onChange={(e) => setForm({ ...form, address: e.target.value })}
                placeholder="Address"
                rows={3}
                required
              />
            </div>
            <div>
              <Label htmlFor="description">Hotspot Description *</Label>
              <Textarea
                id="description"
                value={form.description || ""}
                onChange={(e) => setForm({ ...form, description: e.target.value })}
                placeholder="Hotspot Description"
                rows={3}
                required
              />
            </div>
          </div>

          {/* Row 6: Hotspot Location (single-select) */}
          <div className="space-y-2">
            <Label htmlFor="hotspotLocation">Hotspot Location *</Label>
            <Select
              value={(form.locations?.[0] ?? "") as string}
              onValueChange={(v) => setForm({ ...form, locations: v ? [v] : [] })}
            >
              <SelectTrigger id="hotspotLocation" className="w-full">
                <SelectValue placeholder="Choose Location" />
              </SelectTrigger>
              <SelectContent>
                {options.locations.map((loc) => (
                  <SelectItem key={loc} value={loc}>
                    {loc}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
            {(form.locations?.length ?? 0) === 0 && (
              <p className="text-xs text-destructive mt-1">Location is required.</p>
            )}
          </div>

          {/* Row 7: Gallery | Video URL */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <Label>Hotspot Gallery*</Label>
              <Input
                type="file"
                multiple
                accept="image/*"
                onChange={(e) => onUploadFiles(e.target.files)}
              />
            </div>
            <div>
              <Label htmlFor="videoUrl">Hotspot Video URL *</Label>
              <Input
                id="videoUrl"
                value={form.videoUrl || ""}
                onChange={(e) => setForm({ ...form, videoUrl: e.target.value })}
                placeholder="Hotspot Video URL"
                required
              />
            </div>
          </div>

          {/* Thumbnails preview */}
          <div className="mt-2">
            <p className="text-sm font-medium mb-2">Uploaded hotspot Gallery</p>
            <div className="flex gap-2 flex-wrap">
              {(form.galleryImages || []).map((img, i) => (
                <img key={i} src={img} alt="" className="h-20 w-20 object-cover rounded" />
              ))}
            </div>
          </div>
        </div>

        {/* ---------------------- Vehicle Parking Charge Details --------------------- */}
        <div className="bg-white rounded-lg border p-6 space-y-4">
          <h2 className="text-lg font-semibold text-primary">Vehicle Parking Charge Details</h2>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            {options.vehicleTypes.map((v) => (
              <div key={v.id}>
                <Label>{v.name}</Label>
                <Input
                  type="number"
                  value={Number(form.parkingCharges?.[String(v.id)] ?? 0)}
                  onChange={(e) =>
                    setForm({
                      ...form,
                      parkingCharges: {
                        ...(form.parkingCharges || {}),
                        [String(v.id)]: Number(e.target.value),
                      },
                    })
                  }
                />
              </div>
            ))}
          </div>
        </div>

        {/* ----------------------------- Opening Hours ------------------------------ */}
        <div className="bg-white rounded-lg border p-6 space-y-4">
          <h2 className="text-lg font-semibold text-primary">Opening Hours</h2>

          {/* header row */}
          <div className="grid grid-cols-12 font-medium text-muted-foreground bg-muted/40 rounded-md px-4 py-2">
            <div className="col-span-3">DAY</div>
            <div className="col-span-2 text-center">OPENS 24 HOURS</div>
            <div className="col-span-2 text-center">CLOSES 24 HOURS</div>
            <div className="col-span-4 text-center">NEW TIMINGS</div>
            <div className="col-span-1 text-center">ACTION</div>
          </div>

          <div className="space-y-2">
            {DAYS.map((day) => {
              const current =
                (form.openingHours as Record<string, any> | undefined)?.[day] ??
                ({ is24Hours: false, closed24Hours: false, timeSlots: [] } as OpeningDay);

              const setDay = (next: any) =>
                setForm((prev) => ({
                  ...prev,
                  openingHours: {
                    ...((prev.openingHours as Record<string, any> | undefined) ?? {}),
                    [day]: next,
                  },
                }));

              const addSlot = () => {
                const next = {
                  ...current,
                  timeSlots: [...(current.timeSlots || []), { start: "", end: "" }],
                };
                setDay(next);
              };

              const removeSlot = (idx: number) => {
                const next = {
                  ...current,
                  timeSlots: (current.timeSlots || []).filter((_: any, i: number) => i !== idx),
                };
                setDay(next);
              };

              // materialize the first slot so edits persist
              const updateSlot = (idx: number, key: "start" | "end", val: string) => {
                const base =
                  current.timeSlots && current.timeSlots.length > 0
                    ? [...current.timeSlots]
                    : [{ start: "", end: "" }];

                if (!base[idx]) base[idx] = { start: "", end: "" };
                base[idx] = { ...base[idx], [key]: val };
                setDay({ ...current, timeSlots: base });
              };

              const disabled = !!current.is24Hours || !!current.closed24Hours;

              return (
                <div
                  key={day}
                  className="grid grid-cols-12 items-center border rounded-md px-4 py-3 gap-3"
                >
                  {/* Day */}
                  <div className="col-span-3 capitalize font-medium">{day}</div>

                  {/* Opens 24 Hours */}
                  <div className="col-span-2 flex justify-center">
                    <Switch
                      checked={!!current.is24Hours}
                      disabled={!!current.closed24Hours}
                      onCheckedChange={(checked) => {
                        setDay({
                          ...current,
                          is24Hours: checked,
                          timeSlots: checked ? [] : current.timeSlots ?? [],
                          closed24Hours: checked ? false : current.closed24Hours ?? false,
                        });
                      }}
                    />
                  </div>

                  {/* Closes 24 Hours */}
                  <div className="col-span-2 flex justify-center">
                    <Switch
                      checked={!!current.closed24Hours}
                      disabled={!!current.is24Hours}
                      onCheckedChange={(checked) => {
                        setDay({
                          ...current,
                          closed24Hours: checked,
                          timeSlots: checked ? [] : current.timeSlots ?? [],
                          is24Hours: checked ? false : current.is24Hours ?? false,
                        });
                      }}
                    />
                  </div>

                  {/* New Timings */}
                  <div className="col-span-4">
                    {current.is24Hours ? (
                      <span className="inline-block text-xs px-3 py-1 rounded-md bg-pink-50 text-pink-600 font-semibold">
                        Opens 24 Hours
                      </span>
                    ) : current.closed24Hours ? (
                      <span className="inline-block text-xs px-3 py-1 rounded-md bg-pink-50 text-pink-600 font-semibold">
                        Closed
                      </span>
                    ) : (
                      <div className="space-y-2">
                        {(current.timeSlots?.length ? current.timeSlots : [{ start: "", end: "" }]).map(
                          (slot: OpeningSlot, idx: number) => (
                            <div key={idx} className="flex items-center gap-3">
                              <TimePickerField
                                value={slot.start || ""}
                                onChange={(v) => updateSlot(idx, "start", v)}
                                disabled={disabled}
                              />
                              <span className="text-muted-foreground">TO</span>
                              <TimePickerField
                                value={slot.end || ""}
                                onChange={(v) => updateSlot(idx, "end", v)}
                                disabled={disabled}
                              />
                              <Button
                                type="button"
                                variant="secondary"
                                size="sm"
                                onClick={() => removeSlot(idx)}
                                disabled={disabled || (current.timeSlots?.length ?? 0) === 0}
                              >
                                Remove
                              </Button>
                            </div>
                          )
                        )}
                      </div>
                    )}
                  </div>

                  {/* Action */}
                  <div className="col-span-1 flex justify-center">
                    <Button
                      type="button"
                      size="sm"
                      onClick={addSlot}
                      disabled={!!current.is24Hours || !!current.closed24Hours}
                      className="bg-gradient-to-r from-fuchsia-500 to-pink-500 text-white hover:opacity-90"
                    >
                      Add More
                    </Button>
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        <div className="flex justify-between">
          <Button type="button" variant="outline" onClick={() => navigate("/hotspots")}>
            Back
          </Button>
          <Button type="submit" disabled={loading}>
            {isEdit ? "Update" : "Save"}
          </Button>
        </div>
      </form>
    </div>
  );
}
