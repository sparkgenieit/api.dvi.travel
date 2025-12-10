import { useEffect } from "react";
import { useForm, useFieldArray } from "react-hook-form";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { ActivitiesAPI } from "@/services/activities";
import { useNavigate, useParams } from "react-router-dom";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectTrigger,
  SelectValue,
  SelectContent,
  SelectItem,
} from "@/components/ui/select";
import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { toast } from "sonner";

/* ------------------- styling helpers (keep consistent with PHP) ------------------- */
const panel = "rounded-xl border border-[#eadcfb] bg-[#fcf7ff]";
const borderInput =
  "h-10 rounded-lg border border-[#eadcfb] bg-white/90 placeholder:text-[#a3a3b0] focus-visible:ring-2 focus-visible:ring-offset-0 focus-visible:ring-[#8b5cf6]";
const labelTxt = "text-[13px] font-medium text-[#6b7280]";
const subNote = "text-xs text-[#a3a3b0]";
const gradTxt =
  "bg-clip-text text-transparent bg-[linear-gradient(90deg,#8b5cf6_0%,#ec4899_60%,#f472b6_100%)]";
const gradBtn =
  "bg-gradient-to-r from-[#8b5cf6] to-[#ec4899] text-white hover:opacity-95";
const softBtn =
  "bg-[#f3e8ff] text-[#6b21a8] hover:bg-[#e9d5ff] border border-[#eadcfb]";
const stepPill =
  "flex h-8 w-8 items-center justify-center rounded-lg bg-[#8b5cf6] text-white text-sm font-semibold";

function SectionTitle({ children }: { children: React.ReactNode }) {
  return (
    <h3 className={`mb-4 text-[20px] font-semibold ${gradTxt}`}>{children}</h3>
  );
}

/* --------------------------------- schema --------------------------------- */
const basicSchema = z.object({
  activity_title: z.string().min(2),
  hotspot_id: z.number().int().positive(),
  max_allowed_person_count: z.number().int().positive(),
  activity_duration: z
    .string()
    .regex(/^\d{2}:\d{2}(:\d{2})?$/, "Use HH:MM or HH:MM:SS"),
  activity_description: z.string().min(1),
  imageNames: z.array(z.string()).optional(),
  defaultSlots: z
    .array(z.object({ start_time: z.string(), end_time: z.string() }))
    .optional(),
  specialEnabled: z.boolean().optional(),
  specialSlots: z
    .array(
      z.object({ date: z.string(), start_time: z.string(), end_time: z.string() })
    )
    .optional(),
});
type BasicForm = z.infer<typeof basicSchema>;

/* =============================================================================
   PAGE
============================================================================= */
export default function ActivityEditPage() {
  const { id } = useParams();
  const editing = !!id;
  const navigate = useNavigate();
  const qc = useQueryClient();

  /* hotspots for dropdown */
  const { data: hotspots } = useQuery({
    queryKey: ["activities", "hotspots"],
    queryFn: () => ActivitiesAPI.hotspots(),
  });

  /* preload when editing */
  const { data: details } = useQuery({
    enabled: editing,
    queryKey: ["activities", "details", id],
    queryFn: () => ActivitiesAPI.details(Number(id)),
  });

  const form = useForm<BasicForm>({
    resolver: zodResolver(basicSchema),
    defaultValues: {
      activity_title: "",
      hotspot_id: undefined as any,
      max_allowed_person_count: 1,
      activity_duration: "00:30:00",
      activity_description: "",
      imageNames: [],
      defaultSlots: [{ start_time: "13:00", end_time: "15:00" }],
      specialEnabled: false,
      specialSlots: [],
    },
  });

  const {
    fields: defaultSlots,
    append: addDefaultSlot,
    remove: removeDefault,
  } = useFieldArray({ control: form.control, name: "defaultSlots" });

  const {
    fields: specialSlots,
    append: addSpecialSlot,
    remove: removeSpecial,
  } = useFieldArray({ control: form.control, name: "specialSlots" });

  useEffect(() => {
    if (details && editing) {
      form.reset({
        activity_title: details.activity_title ?? "",
        hotspot_id: Number(details.hotspot_id ?? 0),
        max_allowed_person_count: Number(details.max_allowed_person_count ?? 1),
        activity_duration: details.activity_duration ?? "00:30:00",
        activity_description: details.activity_description ?? "",
        imageNames: [],
        defaultSlots: [],
        specialEnabled: false,
        specialSlots: [],
      });
    }
  }, [details, editing]); // eslint-disable-line

  /* ------------------- mutations ------------------- */
  const createMut = useMutation({
    mutationFn: (body: BasicForm) => ActivitiesAPI.create(body),
    onSuccess: (created) => {
      toast.success("Activity created");
      qc.invalidateQueries({ queryKey: ["activities", "list"] });
      navigate(`/activities/${created.activity_id}/edit?tab=pricebook`, {
        replace: true,
      });
    },
  });

  const updateMut = useMutation({
    mutationFn: (body: Partial<BasicForm>) =>
      ActivitiesAPI.update(Number(id), body),
    onSuccess: () => {
      toast.success("Activity saved");
      qc.invalidateQueries({ queryKey: ["activities", "details", id] });
    },
  });

  const timeSlotMut = useMutation({
    mutationFn: (
      body: Pick<BasicForm, "defaultSlots" | "specialEnabled" | "specialSlots"> & {
        createdby?: number;
      }
    ) => ActivitiesAPI.saveTimeSlots(Number(id), body),
    onSuccess: () => toast.success("Time slots saved"),
  });

  const pricebookMut = useMutation({
    mutationFn: (body: any) => ActivitiesAPI.savePriceBook(Number(id), body),
    onSuccess: () => toast.success("Activity Pricebook Created Successfully"),
  });

  const addReviewMut = useMutation({
    mutationFn: (body: any) => ActivitiesAPI.addReview(Number(id), body),
    onSuccess: () => toast.success("Review added"),
  });

  const onSubmitBasic = (values: BasicForm) => {
    if (editing) updateMut.mutate(values);
    else createMut.mutate(values);
  };

  /* --------------------------------- UI --------------------------------- */
  return (
    <div className="p-6">
      {/* Steps Tabs like PHP with numbered pills */}
      <Tabs
        defaultValue={
          new URLSearchParams(location.search).get("tab") ?? "basic"
        }
        onValueChange={(v) => history.replaceState(null, "", `?tab=${v}`)}
      >
        <TabsList
          className={`${panel} mb-6 flex w-full gap-2 rounded-2xl p-2`}
        >
          <TabsTrigger
            value="basic"
            className="group flex flex-1 items-center gap-3 rounded-lg px-3 py-2 data-[state=active]:bg-white data-[state=active]:shadow-sm"
          >
            <span className={stepPill}>1</span>
            <span className="text-sm text-[#6b7280] group-data-[state=active]:text-[#374151]">
              Activity Basic Details
            </span>
          </TabsTrigger>

          <TabsTrigger
            value="pricebook"
            disabled={!editing}
            className="group flex flex-1 items-center gap-3 rounded-lg px-3 py-2 data-[state=active]:bg-white data-[state=active]:shadow-sm"
          >
            <span className={`${stepPill} bg-[#c4b5fd]`}>2</span>
            <span className="text-sm text-[#6b7280] group-data-[state=active]:text-[#374151]">
              Price Book
            </span>
          </TabsTrigger>

          <TabsTrigger
            value="reviews"
            disabled={!editing}
            className="group flex flex-1 items-center gap-3 rounded-lg px-3 py-2 data-[state=active]:bg-white data-[state=active]:shadow-sm"
          >
            <span className={`${stepPill} bg-[#d4d4d8]`}>3</span>
            <span className="text-sm text-[#6b7280] group-data-[state=active]:text-[#374151]">
              FeedBack & Review
            </span>
          </TabsTrigger>

          <TabsTrigger
            value="preview"
            disabled={!editing}
            className="group flex flex-1 items-center gap-3 rounded-lg px-3 py-2 data-[state=active]:bg-white data-[state=active]:shadow-sm"
          >
            <span className={`${stepPill} bg-[#e5e7eb]`}>4</span>
            <span className="text-sm text-[#6b7280] group-data-[state=active]:text-[#374151]">
              Preview
            </span>
          </TabsTrigger>
        </TabsList>

        {/* TAB 1: BASIC */}
        <TabsContent value="basic">
          <Card className={panel}>
            <CardContent className="p-6">
              <SectionTitle>Activity Basic Details</SectionTitle>

              <form
                onSubmit={form.handleSubmit(onSubmitBasic)}
                className="space-y-8"
              >
                <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                  <div>
                    <Label className={labelTxt}>
                      Activity Title <span className="text-rose-500">*</span>
                    </Label>
                    <Input className={borderInput} {...form.register("activity_title")} />
                    <p className="mt-1 text-xs text-rose-500">
                      {form.formState.errors.activity_title?.message}
                    </p>
                  </div>

                  <div>
                    <Label className={labelTxt}>
                      Hotspot Places <span className="text-rose-500">*</span>
                    </Label>
                    <Select
                      onValueChange={(v) =>
                        form.setValue("hotspot_id", Number(v))
                      }
                      value={String(form.watch("hotspot_id") ?? "")}
                    >
                      <SelectTrigger className={borderInput}>
                        <SelectValue placeholder="Choose Hotspot" />
                      </SelectTrigger>
                      <SelectContent>
                        {hotspots?.map((h) => (
                          <SelectItem key={h.id} value={String(h.id)}>
                            {h.label}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                    <p className="mt-1 text-xs text-rose-500">
                      {form.formState.errors.hotspot_id?.message as any}
                    </p>
                  </div>

                  <div>
                    <Label className={labelTxt}>
                      Max Allowed Person Count{" "}
                      <span className="text-rose-500">*</span>
                    </Label>
                    <Input
                      type="number"
                      className={borderInput}
                      {...form.register("max_allowed_person_count", {
                        valueAsNumber: true,
                      })}
                    />
                  </div>

                  <div>
                    <Label className={labelTxt}>
                      Duration <span className="text-rose-500">*</span>
                    </Label>
                    <Input
                      placeholder="HH:MM:SS"
                      className={borderInput}
                      {...form.register("activity_duration")}
                    />
                  </div>

                  {/* Upload (placeholder – wire your uploader, push names to imageNames) */}
                  <div className="md:col-span-2">
                    <Label className={labelTxt}>
                      Upload Images <span className="text-rose-500">*</span>
                    </Label>
                    <div className="flex gap-2">
                      <Input
                        type="file"
                        multiple
                        className={`${borderInput} file:mr-3 file:rounded-md file:border-0 file:bg-[#8b5cf6] file:px-3 file:py-2 file:text-white`}
                        onChange={(e) => {
                          const files = Array.from(e.target.files ?? []);
                          const names = files.map((f) => f.name);
                          form.setValue("imageNames", names);
                        }}
                      />
                    </div>
                  </div>
                </div>

                <div>
                  <Label className={labelTxt}>
                    Description <span className="text-rose-500">*</span>
                  </Label>
                  <Textarea rows={4} className={borderInput} {...form.register("activity_description")} />
                </div>

                {/* Default Available Time */}
                <div>
                  <SectionTitle>Default Available Time</SectionTitle>
                  <div className="grid gap-3">
                    {defaultSlots.map((f, i) => (
                      <div
                        className="grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_auto]"
                        key={f.id}
                      >
                        <Input
                          placeholder="Start (HH:MM)"
                          className={borderInput}
                          {...form.register(
                            `defaultSlots.${i}.start_time` as const
                          )}
                        />
                        <Input
                          placeholder="End (HH:MM)"
                          className={borderInput}
                          {...form.register(
                            `defaultSlots.${i}.end_time` as const
                          )}
                        />
                        <Button
                          type="button"
                          variant="outline"
                          className={softBtn}
                          onClick={() => removeDefault(i)}
                        >
                          ✕ Delete
                        </Button>
                      </div>
                    ))}

                    <div className="pt-2">
                      <Button
                        type="button"
                        className={softBtn}
                        onClick={() =>
                          addDefaultSlot({ start_time: "13:00", end_time: "15:00" })
                        }
                      >
                        + Add Default Time
                      </Button>
                    </div>
                  </div>
                </div>

                {/* Special Available Time */}
                <div>
                  <SectionTitle>Special Available Time</SectionTitle>
                  <div className="grid gap-3">
                    {specialSlots.map((f, i) => (
                      <div
                        className="grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_1fr_auto]"
                        key={f.id}
                      >
                        <Input
                          placeholder="Enter Date (YYYY-MM-DD)"
                          className={borderInput}
                          {...form.register(`specialSlots.${i}.date` as const)}
                        />
                        <Input
                          placeholder="Start (HH:MM)"
                          className={borderInput}
                          {...form.register(
                            `specialSlots.${i}.start_time` as const
                          )}
                        />
                        <Input
                          placeholder="End (HH:MM)"
                          className={borderInput}
                          {...form.register(
                            `specialSlots.${i}.end_time` as const
                          )}
                        />
                        <Button
                          type="button"
                          variant="outline"
                          className={softBtn}
                          onClick={() => removeSpecial(i)}
                        >
                          ✕ Delete
                        </Button>
                      </div>
                    ))}

                    <div className="flex flex-wrap items-center justify-between gap-3 pt-2">
                      <Button
                        type="button"
                        className={softBtn}
                        onClick={() =>
                          addSpecialSlot({
                            date: "2025-12-31",
                            start_time: "10:00",
                            end_time: "12:00",
                          })
                        }
                      >
                        + Add Days
                      </Button>
                      <div className="text-right text-sm">
                        <span className={subNote}>
                          Toggle/validation can be added to mimic PHP exactly.
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div className="flex items-center justify-between">
                  <Button
                    type="button"
                    variant="outline"
                    className="rounded-lg border border-[#e5e7eb] bg-[#f3f4f6] text-[#6b7280] hover:bg-[#e5e7eb]"
                    onClick={() => navigate("/activities")}
                  >
                    Back
                  </Button>

                  <Button type="submit" className={`${gradBtn} rounded-lg px-5`}>
                    {editing ? "Update & Continue" : "Save & Continue"}
                  </Button>
                </div>
              </form>
            </CardContent>
          </Card>
        </TabsContent>

        {/* TAB 2: PRICEBOOK */}
        <TabsContent value="pricebook">
          <PriceBookTab
            onSave={(payload) => pricebookMut.mutate(payload)}
            activityId={Number(id)}
          />
        </TabsContent>

        {/* TAB 3: FEEDBACK & REVIEW */}
        <TabsContent value="reviews">
          <ReviewsTab onSave={(payload) => addReviewMut.mutate(payload)} />
        </TabsContent>

        {/* TAB 4: PREVIEW */}
        <TabsContent value="preview">
          <PreviewEmbed activityId={Number(id)} />
        </TabsContent>
      </Tabs>
    </div>
  );
}

/* ================================ PriceBook tab ================================ */
function PriceBookTab({
  onSave,
  activityId,
}: {
  onSave: (payload: any) => void;
  activityId: number;
}) {
  const form = useForm<{
    start_date: string;
    end_date: string;
    indian_adult?: string;
    indian_child?: string;
    indian_infant?: string;
    foreign_adult?: string;
    foreign_child?: string;
    foreign_infant?: string;
    hotspot_id: number;
  }>({ defaultValues: { start_date: "", end_date: "", hotspot_id: 0 } });

  const { data: hotspots } = useQuery({
    queryKey: ["activities", "hotspots"],
    queryFn: () => ActivitiesAPI.hotspots(),
  });

  const submit = (v: any) => {
    if (!activityId) return;
    onSave({
      hotspot_id: Number(v.hotspot_id),
      start_date: v.start_date,
      end_date: v.end_date,
      indian: {
        adult_cost: v.indian_adult,
        child_cost: v.indian_child,
        infant_cost: v.indian_infant,
      },
      nonindian: {
        adult_cost: v.foreign_adult,
        child_cost: v.foreign_child,
        infant_cost: v.foreign_infant,
      },
    });
  };

  return (
    <Card className={panel}>
      <CardContent className="p-6">
        <SectionTitle>Activity Cost Details</SectionTitle>

        <form onSubmit={form.handleSubmit(submit)} className="space-y-6">
          <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div>
              <Label className={labelTxt}>Hotspot *</Label>
              <Select
                onValueChange={(v) => form.setValue("hotspot_id", Number(v))}
              >
                <SelectTrigger className={borderInput}>
                  <SelectValue placeholder="Select Hotspot" />
                </SelectTrigger>
                <SelectContent>
                  {hotspots?.map((h) => (
                    <SelectItem key={h.id} value={String(h.id)}>
                      {h.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div>
              <Label className={labelTxt}>Start Date *</Label>
              <Input type="date" className={borderInput} {...form.register("start_date")} />
            </div>
            <div>
              <Label className={labelTxt}>End Date *</Label>
              <Input type="date" className={borderInput} {...form.register("end_date")} />
            </div>
          </div>

          <div className="grid grid-cols-1 gap-6 border-t pt-4 md:grid-cols-3">
            <div>
              <Label className="text-[#d946ef]">Indian — Adult</Label>
              <Input type="number" step="0.01" className={borderInput} {...form.register("indian_adult")} />
            </div>
            <div>
              <Label className="text-[#d946ef]">Indian — Children</Label>
              <Input type="number" step="0.01" className={borderInput} {...form.register("indian_child")} />
            </div>
            <div>
              <Label className="text-[#d946ef]">Indian — Infant</Label>
              <Input type="number" step="0.01" className={borderInput} {...form.register("indian_infant")} />
            </div>
          </div>

          <div className="grid grid-cols-1 gap-6 border-t pt-4 md:grid-cols-3">
            <div>
              <Label className="text-[#6b21a8]">Non-Indian — Adult</Label>
              <Input type="number" step="0.01" className={borderInput} {...form.register("foreign_adult")} />
            </div>
            <div>
              <Label className="text-[#6b21a8]">Non-Indian — Children</Label>
              <Input type="number" step="0.01" className={borderInput} {...form.register("foreign_child")} />
            </div>
            <div>
              <Label className="text-[#6b21a8]">Non-Indian — Infant</Label>
              <Input type="number" step="0.01" className={borderInput} {...form.register("foreign_infant")} />
            </div>
          </div>

          <div className="flex items-center justify-end">
            <Button type="submit" className={`${gradBtn} rounded-lg px-5`}>
              Update
            </Button>
          </div>
        </form>
      </CardContent>
    </Card>
  );
}

/* ================================ Reviews tab ================================ */
function ReviewsTab({ onSave }: { onSave: (payload: any) => void }) {
  const form = useForm<{ activity_rating: string; activity_description?: string }>(
    { defaultValues: { activity_rating: "", activity_description: "" } }
  );

  const submit = (v: any) => {
    if (!v.activity_rating) return;
    if (v.activity_description && v.activity_description.length > 20) {
      alert("Feedback length must be ≤ 20 characters.");
      return;
    }
    onSave(v);
    form.reset();
  };

  return (
    <Card className={panel}>
      <CardContent className="p-6 space-y-6">
        <SectionTitle>Feedback & Review</SectionTitle>

        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
          <form onSubmit={form.handleSubmit(submit)} className="space-y-4">
            <div>
              <Label className={labelTxt}>Rating *</Label>
              <Select
                value={form.watch("activity_rating")}
                onValueChange={(v) => form.setValue("activity_rating", v)}
              >
                <SelectTrigger className={borderInput}>
                  <SelectValue placeholder="Select Rating" />
                </SelectTrigger>
                <SelectContent>
                  {["1", "2", "3", "4", "5"].map((r) => (
                    <SelectItem key={r} value={r}>
                      {r}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <div>
              <Label className={labelTxt}>Feedback *</Label>
              <Textarea
                rows={4}
                maxLength={20}
                className={borderInput}
                {...form.register("activity_description")}
              />
              <p className="mt-1 text-xs text-[#9ca3af]">Max 20 characters</p>
            </div>

            <Button type="submit" className={`${gradBtn} rounded-lg px-5`}>
              Save
            </Button>
          </form>

          <div>
            <h4 className={`mb-3 font-medium ${gradTxt}`}>List of Reviews</h4>
            <div className="rounded-lg border border-[#eadcfb] bg-white p-6 text-center text-sm text-[#9ca3af]">
              No data available in table
            </div>
          </div>
        </div>

        <div className="flex justify-end">
          <Button
            variant="outline"
            className="rounded-lg border border-[#e5e7eb] bg-[#f3f4f6] text-[#6b7280] hover:bg-[#e5e7eb]"
            onClick={() => history.back()}
          >
            Back
          </Button>
          <div className="w-2" />
          <Button className={`${gradBtn} rounded-lg px-5`}>Update & Continue</Button>
        </div>
      </CardContent>
    </Card>
  );
}

/* ================================ Preview embed ================================ */
function PreviewEmbed({ activityId }: { activityId: number }) {
  const { data, isLoading } = useQuery({
    enabled: !!activityId,
    queryKey: ["activities", "preview", activityId],
    queryFn: () => ActivitiesAPI.preview(activityId),
  });
  if (isLoading) return <div className="p-6">Loading…</div>;
  if (!data) return null;

  return (
    <Card className={panel}>
      <CardContent className="p-6 space-y-6">
        <h3 className={`text-lg font-semibold ${gradTxt}`}>Preview</h3>
        <BasicPreview data={data} />

        <div className="flex justify-end">
          <Button
            variant="outline"
            className="rounded-lg border border-[#e5e7eb] bg-[#f3f4f6] text-[#6b7280] hover:bg-[#e5e7eb]"
            onClick={() => history.back()}
          >
            Back
          </Button>
          <div className="w-2" />
          <Button className={`${gradBtn} rounded-lg px-5`}>Submit</Button>
        </div>
      </CardContent>
    </Card>
  );
}

/* ----------------------------- minimal preview block ----------------------------- */
function BasicPreview({ data }: { data: any }) {
  const b = data.basic;
  return (
    <div className="space-y-6">
      <section>
        <h4 className={`mb-3 font-medium ${gradTxt}`}>Basic Info</h4>
        <div className="grid grid-cols-1 gap-6 text-sm md:grid-cols-4">
          <div>
            <div className="text-[#6b7280]">Activity Title</div>
            <div className="font-medium text-[#1f2937]">{b.activity_title}</div>
          </div>
          <div>
            <div className="text-[#6b7280]">Hotspot Places</div>
            <div className="font-medium text-[#1f2937]">
              {data.hotspot?.hotspot_name ?? "--"}
            </div>
          </div>
          <div>
            <div className="text-[#6b7280]">Max Allowed Person Count</div>
            <div className="font-medium text-[#1f2937]">
              {b.max_allowed_person_count ?? "--"}
            </div>
          </div>
          <div>
            <div className="text-[#6b7280]">Duration</div>
            <div className="font-medium text-[#1f2937]">
              {b.activity_duration ?? "--"}
            </div>
          </div>
        </div>
        {b.activity_description && (
          <div className="mt-4">
            <div className="text-sm text-[#6b7280]">Description</div>
            <div className="text-sm text-[#1f2937]">
              {b.activity_description}
            </div>
          </div>
        )}
      </section>
    </div>
  );
}
