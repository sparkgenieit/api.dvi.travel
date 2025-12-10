// FILE: src/pages/activity/PreviewPieces.tsx
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import type { PreviewPayload } from "@/services/activities";

/* ---------- helpers, safe formatters ---------- */
function fmtHMS(input: unknown): string {
  if (!input && input !== 0) return "";
  if (input instanceof Date) {
    const hh = input.getHours().toString().padStart(2, "0");
    const mm = input.getMinutes().toString().padStart(2, "0");
    const ss = input.getSeconds().toString().padStart(2, "0");
    return `${hh}:${mm}:${ss}`;
  }
  if (typeof input === "number") {
    const d = new Date(input);
    if (!Number.isNaN(d.getTime())) return fmtHMS(d);
  }
  const s = String(input);
  const [hh = "00", mm = "00", ss = "00"] = s.split(":");
  return `${hh.padStart(2, "0")}:${mm.padStart(2, "0")}:${(ss ?? "00").padStart(2, "0")}`;
}

function to12h(hms: unknown): string {
  const [H, M] = fmtHMS(hms).split(":");
  const hNum = Number(H);
  if (Number.isNaN(hNum)) return "";
  const suffix = hNum >= 12 ? "PM" : "AM";
  const hr = ((hNum + 11) % 12) + 1;
  return `${String(hr).padStart(2, "0")}:${M} ${suffix}`;
}

function fmtDate(d: unknown): string {
  if (!d) return "";
  const dt = d instanceof Date ? d : new Date(String(d));
  if (Number.isNaN(dt.getTime())) return String(d);
  return dt.toLocaleDateString("en-GB", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
}

function DividerStar() {
  return (
    <div className="px-6">
      <div className="relative my-6 h-px bg-[#eadcfb]">
        <div className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-[#fcf7ff] px-3 text-[#a3a3b0]">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" className="opacity-70">
            <path
              d="M12 2l2.39 4.84L20 8l-4 3.9L17 18l-5-2.6L7 18l1-6.1L4 8l5.61-1.16L12 2z"
              stroke="#a3a3b0"
              fill="none"
            />
          </svg>
        </div>
      </div>
    </div>
  );
}

function LabelValue({ label, value }: { label: string; value?: string | number | null }) {
  return (
    <div className="space-y-1">
      <div className="text-sm tracking-wide text-[#6b7280]">{label}</div>
      <div className="text-[18px] text-[#1f2937]">{value ?? ""}</div>
    </div>
  );
}

/* ---------- bordered table wrapper (PHP look) ---------- */
function BorderedTable({
  headers,
  children,
}: {
  headers: string[];
  children: React.ReactNode;
}) {
  // Every TH/TD gets a 1px #eadcfb border
  return (
    <div className="overflow-hidden rounded-md border border-[#eadcfb]">
      <Table className="w-full border-collapse [&_th]:border [&_th]:border-[#eadcfb] [&_td]:border [&_td]:border-[#eadcfb]">
        <TableHeader>
          <TableRow className="bg-[#f7f4ff]">
            {headers.map((h) => (
              <TableHead
                key={h}
                className="text-left uppercase tracking-wide text-[12px] text-[#374151] px-4 py-3"
              >
                {h}
              </TableHead>
            ))}
          </TableRow>
        </TableHeader>
        <TableBody className="[&>tr>td]:px-4 [&>tr>td]:py-3 text-[#111827]">{children}</TableBody>
      </Table>
    </div>
  );
}

/* ---------- props: support BOTH shapes ---------- */
type PropsData = { data: PreviewPayload };

type PropsSplit = {
  basic: PreviewPayload["basic"];
  hotspot?: PreviewPayload["hotspot"];
  images?: PreviewPayload["images"];
  defaultSlots?: PreviewPayload["defaultSlots"];
  specialSlots?: PreviewPayload["specialSlots"];
  reviews?: PreviewPayload["reviews"];
};

type Props = PropsData | PropsSplit;

/* ---------- main preview ---------- */
export default function BasicPreview(props: Props) {
  const payload: PreviewPayload =
    "data" in props
      ? props.data
      : {
          basic: props.basic,
          hotspot: props.hotspot,
          images: props.images ?? [],
          defaultSlots: props.defaultSlots ?? [],
          specialSlots: props.specialSlots ?? [],
          reviews: props.reviews ?? [],
        };

  const {
    basic,
    hotspot,
    images = [],
    defaultSlots = [],
    specialSlots = [],
    reviews = [],
  } = payload;

  return (
    <div className="rounded-xl border border-[#eadcfb] bg-[#fcf7ff] p-6">
      {/* Basic Info */}
      <h2 className="text-2xl font-semibold bg-gradient-to-r from-[#6b21a8] via-[#d946ef] to-[#ec4899] bg-clip-text text-transparent">
        Basic Info
      </h2>

      <div className="grid grid-cols-1 gap-y-8 gap-x-16 px-1 md:grid-cols-2 lg:grid-cols-4">
        <LabelValue label="Activity Title" value={basic?.activity_title ?? ""} />
        <LabelValue label="Hotspot Places" value={(hotspot as any)?.hotspot_name ?? ""} />
        <LabelValue
          label="Max Allowed Person Count"
          value={basic?.max_allowed_person_count ?? ""}
        />
        <LabelValue label="Duration" value={fmtHMS(basic?.activity_duration)} />
        <div className="md:col-span-4">
          <LabelValue label="Description" value={basic?.activity_description ?? ""} />
        </div>
      </div>

      <DividerStar />

      {/* Images */}
      <h3 className="px-1 pb-3 text-[22px] font-semibold bg-gradient-to-r from-[#6b21a8] via-[#d946ef] to-[#ec4899] bg-clip-text text-transparent">
        Images
      </h3>
      <div className="px-1">
        {images.length ? (
          <div className="flex flex-wrap gap-4">
            {images.map((img) => (
              <img
                key={
                  img.activity_image_gallery_details_id ??
                  `${img.activity_image_gallery_name}-${Math.random()}`
                }
                src={
                  img.activity_image_gallery_name
                    ? `/uploads/activity_gallery/${img.activity_image_gallery_name}`
                    : "/placeholder.svg"
                }
                alt="activity"
                className="h-[84px] w-[120px] rounded-md object-cover border border-[#eadcfb] bg-white"
              />
            ))}
          </div>
        ) : (
          <div className="text-sm text-[#6b7280]">No Images</div>
        )}
      </div>

      <DividerStar />

      {/* Default Available Time */}
      <h3 className="px-1 pb-3 text-[22px] font-semibold bg-gradient-to-r from-[#6b21a8] via-[#d946ef] to-[#ec4899] bg-clip-text text-transparent">
        Default Available Time
      </h3>
      <div className="px-1">
        <BorderedTable headers={["S.NO", "START TIME", "END TIME"]}>
          {defaultSlots.length ? (
            defaultSlots.map((s, i) => (
              <TableRow key={`${String(s.start_time)}-${String(s.end_time)}-${i}`}>
                <TableCell>{i + 1}</TableCell>
                <TableCell>{to12h(s.start_time)}</TableCell>
                <TableCell>{to12h(s.end_time)}</TableCell>
              </TableRow>
            ))
          ) : (
            <TableRow>
              <TableCell colSpan={3} className="text-center text-sm text-[#6b7280] py-6">
                No Default Time Found !!!
              </TableCell>
            </TableRow>
          )}
        </BorderedTable>
      </div>

      <DividerStar />

      {/* Special Day */}
      <h3 className="px-1 pb-3 text-[22px] font-semibold bg-gradient-to-r from-[#6b21a8] via-[#d946ef] to-[#ec4899] bg-clip-text text-transparent">
        Special Day
      </h3>
      <div className="px-1">
        <BorderedTable headers={["S.NO", "DATE", "START TIME", "END TIME"]}>
          {specialSlots.length ? (
            specialSlots.map((s, i) => (
              <TableRow key={`${String((s as any).special_date)}-${i}`}>
                <TableCell>{i + 1}</TableCell>
                <TableCell>{fmtDate((s as any).special_date)}</TableCell>
                <TableCell>{to12h(s.start_time)}</TableCell>
                <TableCell>{to12h(s.end_time)}</TableCell>
              </TableRow>
            ))
          ) : (
            <TableRow>
              <TableCell colSpan={4} className="text-center text-sm text-[#6b7280] py-6">
                No Special Time Found !!!
              </TableCell>
            </TableRow>
          )}
        </BorderedTable>
      </div>

      <DividerStar />

      {/* Review */}
      <h3 className="px-1 pb-3 text-[22px] font-semibold bg-gradient-to-r from-[#6b21a8] via-[#d946ef] to-[#ec4899] bg-clip-text text-transparent">
        Review
      </h3>
      <div className="px-1">
        <BorderedTable headers={["S.NO", "RATING", "DESCRIPTION", "CREATED ON"]}>
          {reviews.length ? (
            reviews.map((r, i) => (
              <TableRow key={r.activity_review_id ?? i}>
                <TableCell>{i + 1}</TableCell>
                <TableCell>{r.activity_rating}</TableCell>
                <TableCell>{r.activity_description ?? ""}</TableCell>
                <TableCell>{fmtDate(r.createdon)}</TableCell>
              </TableRow>
            ))
          ) : (
            <TableRow>
              <TableCell colSpan={4} className="text-center text-sm text-[#6b7280] py-6">
                No Reviews Found !!!
              </TableCell>
            </TableRow>
          )}
        </BorderedTable>
      </div>
    </div>
  );
}
