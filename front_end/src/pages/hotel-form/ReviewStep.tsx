// FILE: src/pages/hotel-form/ReviewStep.tsx
import React, { useMemo, useState } from "react";
import { useForm } from "react-hook-form";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import type { ReviewForm } from "./HotelForm";
import { Copy, FileSpreadsheet, FileText, Pencil, Trash2 } from "lucide-react";

type ApiCtx = {
  apiGetFirst: (ps: string[]) => Promise<any>;
  apiPost: (p: string, b: any) => Promise<any>;
  // Optional extras if available in your app (used as fallbacks for PATCH/DELETE)
  apiPatch?: (p: string, b: any) => Promise<any>;
  apiDelete?: (p: string) => Promise<any>;
  API_BASE_URL?: string;
  token?: () => string;
};

function normalizeCell(v: any) {
  if (v === null || v === undefined) return "";
  return String(v);
}
function buildAOA(headers: string[], rows: string[][]) {
  return [headers, ...rows];
}
async function copyToClipboard(headers: string[], rows: string[][]) {
  const tsv = buildAOA(headers, rows).map((r) => r.join("\t")).join("\n");
  await navigator.clipboard.writeText(tsv);
}
function downloadCSV(headers: string[], rows: string[][], filename: string) {
  const lines = buildAOA(headers, rows).map((r) =>
    r
      .map((cell) => {
        const needsQuote = /[",\n]/.test(cell);
        let out = cell.replace(/"/g, '""');
        return needsQuote ? `"${out}"` : out;
      })
      .join(",")
  );
  const csv = "\uFEFF" + lines.join("\n");
  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = filename;
  a.click();
  URL.revokeObjectURL(url);
}
async function downloadExcel(headers: string[], rows: string[][], filename: string) {
  try {
    const XLSX = await import("xlsx");
    const aoa = buildAOA(headers, rows);
    const ws = XLSX.utils.aoa_to_sheet(aoa);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Reviews");
    XLSX.writeFile(wb, filename);
  } catch {
    const fn = filename.toLowerCase().endsWith(".xlsx")
      ? filename.replace(/\.xlsx$/i, ".csv")
      : filename + ".csv";
    downloadCSV(headers, rows, fn);
  }
}
function today() {
  return new Date().toISOString().slice(0, 10);
}
function fmtDate(d: any) {
  const raw = d ?? "";
  if (!raw) return "-";
  try {
    const dt = new Date(raw);
    if (isNaN(dt.getTime())) return String(raw);
    return dt.toLocaleDateString();
  } catch {
    return String(raw);
  }
}

// Generic HTTP fallback when apiPatch/apiDelete are not provided
async function httpFallback(
  method: "PATCH" | "DELETE",
  path: string,
  body: any,
  ctx?: ApiCtx
) {
  const base = ctx?.API_BASE_URL || "";
  const tok = ctx?.token?.() || "";
  const r = await fetch(`${base}${path}`, {
    method,
    headers: {
      "Content-Type": "application/json",
      ...(tok ? { Authorization: `Bearer ${tok}` } : {}),
    },
    body: method === "PATCH" ? JSON.stringify(body) : undefined,
  });
  if (!r.ok) throw new Error(await r.text().catch(() => `${method} failed`));
  return r.status === 204 ? null : r.json().catch(() => null);
}

export default function ReviewStep({
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

  const {
    register,
    handleSubmit,
    reset,
    setValue,
    formState: { isSubmitting },
  } = useForm<ReviewForm>({
    defaultValues: { hotel_rating: "", review_description: "" },
  });

  const { data: reviewsRaw = [], refetch, isFetching } = useQuery({
    queryKey: ["hotel-reviews", hotelId],
    enabled: !!hotelId,
    queryFn: () =>
      api
        .apiGetFirst([
          `/api/v1/hotels/${hotelId}/reviews`,
          `/api/v1/hotels/${hotelId}/feedback`,
          `/api/v1/hotels/reviews?hotelId=${hotelId}`,
        ])
        .catch(() => []),
  });

  // === EDIT/DELETE state ===
  const [editingId, setEditingId] = useState<number | null>(null);

  const saveMut = useMutation({
    mutationFn: async (data: ReviewForm) => {
      // 🔧 IMPORTANT: status must be 1 (active). We send it explicitly.
      const payload = {
        hotel_id: Number(hotelId),
        rating: Number(data.hotel_rating || 0),
        description: data.review_description,
        status: 1, // <-- fixed (was implicitly 0 on server default)
      };

      // If editing → PATCH the review by id, else create
      if (editingId != null) {
        const body = payload;
        const patchPaths = [
          `/api/v1/hotels/${hotelId}/reviews/${editingId}`,
          `/api/v1/hotels/${hotelId}/feedback/${editingId}`,
          `/api/v1/hotels/reviews/${editingId}`,
        ];
        let lastErr: any;
        for (const p of patchPaths) {
          try {
            if (api.apiPatch) return await api.apiPatch(p, body);
            return await httpFallback("PATCH", p, body, api);
          } catch (e) {
            lastErr = e;
          }
        }
        throw lastErr || new Error("No review update endpoint available");
      }

      // Create (POST)
      const createPaths = [
        `/api/v1/hotels/${hotelId}/reviews`,
        `/api/v1/hotels/${hotelId}/feedback`,
        `/api/v1/hotels/reviews`,
      ];
      let lastErr: any;
      for (const p of createPaths) {
        try {
          return await api.apiPost(p, payload);
        } catch (e) {
          lastErr = e;
        }
      }
      throw lastErr || new Error("No review create endpoint available");
    },
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ["hotel-reviews", hotelId] });
      reset({ hotel_rating: "", review_description: "" });
      setEditingId(null);
      refetch();
    },
    onError: (e: any) => alert(`Failed: ${e?.message || "Unknown error"}`),
  });

  const deleteMut = useMutation({
    mutationFn: async (reviewId: number) => {
      const delPaths = [
        `/api/v1/hotels/${hotelId}/reviews/${reviewId}`,
        `/api/v1/hotels/${hotelId}/feedback/${reviewId}`,
        `/api/v1/hotels/reviews/${reviewId}`,
      ];
      let lastErr: any;
      for (const p of delPaths) {
        try {
          if (api.apiDelete) return await api.apiDelete(p);
          return await httpFallback("DELETE", p, null, api);
        } catch (e) {
          lastErr = e;
        }
      }
      throw lastErr || new Error("No review delete endpoint available");
    },
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ["hotel-reviews", hotelId] });
      refetch();
    },
    onError: (e: any) => alert(`Delete failed: ${e?.message || "Unknown error"}`),
  });

  const onSubmit = (data: ReviewForm) => saveMut.mutate(data);
  const ratingOptions = [5, 4, 3, 2, 1];

  /** ===== Table UX (client-side like screenshot) ===== */
  const [entries, setEntries] = useState(10);
  const [search, setSearch] = useState("");
  const [page, setPage] = useState(1);

  type Row = {
    id: number;            // running S.NO
    reviewId: number;      // real primary id
    rating: string;
    description: string;
    createdOn: string;
    _raw?: any;            // original object (for edit prefill)
  };

  // 🔧 Map actual API fields (hotel_review_id, hotel_rating, hotel_description, createdon)
  const tableRows: Row[] = useMemo(() => {
    const arr = (reviewsRaw || []) as any[];
    const mapped = arr.map((r: any, i: number) => {
      const reviewId =
        r.hotel_review_id ?? r.review_id ?? r.id ?? r.pk ?? (i + 1);
      const rating =
        r.hotel_rating ?? r.rating ?? r.rate ?? r.stars ?? r.score ?? "-";
      const description =
        r.hotel_description ??
        r.description ??
        r.review_description ??
        r.comment ??
        "-";
      const created =
        r.createdon ??
        r.created_on ??
        r.createdAt ??
        r.created_at ??
        r.createdDate ??
        r.updatedon ??
        r.updated_on ??
        r.updatedAt ??
        r.updated_at ??
        null;

      return {
        id: i + 1,
        reviewId: Number(reviewId),
        rating: normalizeCell(rating),
        description: normalizeCell(description),
        createdOn: fmtDate(created),
        _raw: r,
      };
    });

    const q = search.trim().toLowerCase();
    const filtered = q
      ? mapped.filter((r) =>
          [String(r.id), r.rating, r.description, r.createdOn]
            .join(" ")
            .toLowerCase()
            .includes(q)
        )
      : mapped;

    return filtered;
  }, [reviewsRaw, search]);

  const total = tableRows.length;
  const totalPages = Math.max(1, Math.ceil(total / entries));
  const currentPage = Math.min(page, totalPages);
  const start = (currentPage - 1) * entries;
  const visible = tableRows.slice(start, start + entries);

  const headers = ["S.NO", "RATING", "DESCRIPTION", "CREATED ON"];
  const exportRows = tableRows.map((r) => [String(r.id), r.rating, r.description, r.createdOn]);

  const copyDisabled = total === 0;
  const excelDisabled = total === 0;
  const csvDisabled = total === 0;

  // === Actions (Edit/Delete) ===
  function onEditRow(row: Row) {
    // Prefill the form (mimics show_RATING_FORM)
    const raw = row._raw || {};
    const rating =
      raw.hotel_rating ?? raw.rating ?? raw.rate ?? raw.stars ?? raw.score ?? "";
    const desc =
      raw.hotel_description ??
      raw.description ??
      raw.review_description ??
      raw.comment ??
      "";
    setValue("hotel_rating", String(rating ?? ""));
    setValue("review_description", String(desc ?? ""));
    setEditingId(row.reviewId);
    try {
      document.querySelector(".rv-card .rv-form")?.scrollIntoView({ behavior: "smooth", block: "center" });
    } catch {}
  }

  function onDeleteRow(row: Row) {
    if (deleteMut.isPending) return;
    const ok = confirm("Delete this review?");
    if (!ok) return;
    deleteMut.mutate(row.reviewId);
  }

  return (
    <>
      {/* Wizard step title */}
      <div className="rv-step-title">Review & Feedback</div>

      <div className="rv-grid">
        {/* Left: Rating + Feedback form */}
        <div className="rv-card">
          <div className="rv-card-title">
            {editingId == null ? "Rating" : `Edit Review #${editingId}`}
          </div>

          <form onSubmit={handleSubmit(onSubmit)} className="rv-form">
            <div className="rv-field">
              <select
                id="hotel_rating"
                className="rv-select"
                {...register("hotel_rating", { required: true })}
              >
                <option value="">Select Rating</option>
                {ratingOptions.map((r) => (
                  <option key={r} value={r}>
                    {r}
                  </option>
                ))}
              </select>
              <div className="rv-help">All reviews are from genuine customers</div>
            </div>

            <div className="rv-field">
              <label htmlFor="review_description" className="rv-label">
                Feedback <span className="rv-required">*</span>
              </label>
              <textarea
                id="review_description"
                rows={6}
                className="rv-textarea"
                {...register("review_description", { required: true })}
              />
            </div>

            <div className="rv-form-actions">
              <button
                type="button"
                className="rv-btn rv-btn-muted"
                disabled={saveMut.isPending || isSubmitting}
                onClick={() => {
                  reset({ hotel_rating: "", review_description: "" });
                  setEditingId(null);
                }}
              >
                {editingId == null ? "Cancel" : "Cancel Edit"}
              </button>

              <button
                type="submit"
                disabled={saveMut.isPending || isSubmitting}
                className="rv-btn rv-btn-gradient"
              >
                {editingId == null ? "Save" : "Update"}
              </button>
            </div>
          </form>
        </div>

        {/* Right: List of Reviews table */}
        <div className="rv-card">
          <div className="rv-card-title">List of Reviews</div>

          {/* toolbar */}
          <div className="rv-toolbar">
            <div className="rv-show">
              <span>Show</span>
              <select
                value={entries}
                onChange={(e) => {
                  setEntries(Number(e.target.value));
                  setPage(1);
                }}
              >
                <option value={10}>10</option>
                <option value={25}>25</option>
                <option value={50}>50</option>
              </select>
              <span>entries</span>
            </div>

            <div className="rv-actions">
              <div className="rv-search">
                <label>Search:</label>
                <input
                  value={search}
                  onChange={(e) => {
                    setSearch(e.target.value);
                    setPage(1);
                  }}
                />
              </div>

              <div className="rv-export">
                <button
                  className={`rv-btn rv-btn-copy ${copyDisabled ? "rv-btn-disabled" : ""}`}
                  onClick={() => copyToClipboard(headers, exportRows)}
                  disabled={copyDisabled}
                  title="Copy"
                  type="button"
                >
                  <Copy className="w-4 h-4" />
                  <span>Copy</span>
                </button>

                <button
                  className={`rv-btn rv-btn-excel ${excelDisabled ? "rv-btn-disabled" : ""}`}
                  onClick={() => downloadExcel(headers, exportRows, `reviews_${today()}.xlsx`)}
                  disabled={excelDisabled}
                  title="Excel"
                  type="button"
                >
                  <FileSpreadsheet className="w-4 h-4" />
                  <span>Excel</span>
                </button>

                <button
                  className={`rv-btn rv-btn-csv ${csvDisabled ? "rv-btn-disabled" : ""}`}
                  onClick={() => downloadCSV(headers, exportRows, `reviews_${today()}.csv`)}
                  disabled={csvDisabled}
                  title="CSV"
                  type="button"
                >
                  <FileText className="w-4 h-4" />
                  <span>CSV</span>
                </button>
              </div>
            </div>
          </div>

          {/* table */}
          <div className="rv-table-wrap">
            <table className="rv-table">
              <thead>
                <tr>
                  <th>
                    <div className="rv-th">
                      <span>S.NO</span>
                      <span className="rv-sort">↕</span>
                    </div>
                  </th>
                  <th>
                    <div className="rv-th">
                      <span>RATING</span>
                      <span className="rv-sort">↕</span>
                    </div>
                  </th>
                  <th>
                    <div className="rv-th">
                      <span>DESCRIPTION</span>
                      <span className="rv-sort">↕</span>
                    </div>
                  </th>
                  <th>
                    <div className="rv-th">
                      <span>CREATED ON</span>
                      <span className="rv-sort">↕</span>
                    </div>
                  </th>
                  <th>
                    <div className="rv-th">
                      <span>ACTIONS</span>
                      <span className="rv-sort">↕</span>
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody>
                {isFetching ? (
                  <tr>
                    <td colSpan={5} className="rv-empty">
                      Loading...
                    </td>
                  </tr>
                ) : visible.length === 0 ? (
                  <tr>
                    <td colSpan={5} className="rv-empty">
                      No data available in table
                    </td>
                  </tr>
                ) : (
                  visible.map((r, i) => (
                    <tr key={`${r.reviewId}-${i}`}>
                      <td>{start + i + 1}</td>
                      <td>{r.rating ? `${r.rating} ★` : "-"}</td>
                      <td>{r.description}</td>
                      <td>{r.createdOn}</td>
                      <td>
                        <div className="rv-action-icons">
                          <button
                            type="button"
                            className="rv-icon-btn rv-edit"
                            title="Edit"
                            onClick={() => onEditRow(r)}
                          >
                            <Pencil className="w-4 h-4" />
                          </button>
                          <button
                            type="button"
                            className="rv-icon-btn rv-del"
                            title="Delete"
                            onClick={() => onDeleteRow(r)}
                            disabled={deleteMut.isPending}
                          >
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>

          {/* footer: showing + pager */}
          <div className="rv-footer">
            <div className="rv-showing">
              Showing{" "}
              <strong>{total === 0 ? 0 : start + 1}</strong> to{" "}
              <strong>{Math.min(start + entries, total)}</strong> of{" "}
              <strong>{total}</strong> entries
            </div>
            <div className="rv-pager">
              <button
                type="button"
                onClick={() => setPage((p) => Math.max(1, p - 1))}
                disabled={currentPage === 1}
              >
                Previous
              </button>
              <button
                type="button"
                onClick={() => setPage((p) => Math.min(totalPages, p + 1))}
                disabled={currentPage >= totalPages}
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* bottom nav buttons */}
      <div className="rv-bottom">
        <button type="button" onClick={onPrev} className="rv-btn rv-btn-back">
          Back
        </button>
        <button type="button" onClick={onNext} className="rv-btn rv-btn-continue">
          Update & Continue
        </button>
      </div>

      {/* ====== Styles ====== */}
      <style>{`
        :root{
          --rv-bg:#fdf5ff;
          --rv-card:#ffffff;
          --rv-border:#f0e7ff;
          --rv-text:#3b3b4f;
          --rv-muted:#8c8aa6;
          --rv-primary:#9c27b0;
          --rv-indigo:#6366f1;
          --rv-green:#22c55e;
          --rv-gray:#9ca3af;
          --rv-gradient-from:#ec4899;
          --rv-gradient-to:#8b5cf6;
        }

        .rv-step-title{ color: var(--rv-primary); font-weight: 600; margin-bottom: .75rem; }

        .rv-grid{ display:grid; grid-template-columns: 1fr; gap: 1.25rem; }
        @media(min-width: 1024px){ .rv-grid{ grid-template-columns: .9fr 1.1fr; } }

        .rv-card{ background: var(--rv-card); border: 1px solid var(--rv-border); border-radius: 14px; padding: 16px 18px; box-shadow: 0 4px 18px rgba(139,92,246,.08); }
        .rv-card-title{ font-size: 18px; font-weight: 600; color: var(--rv-text); margin-bottom: 14px; }

        .rv-form{ display:flex; flex-direction:column; gap: 14px; }
        .rv-field{ display:flex; flex-direction:column; gap:8px; }
        .rv-label{ font-size:13px; color:var(--rv-text); font-weight:600; }
        .rv-required{ color:#ef4444; }
        .rv-select, .rv-textarea, .rv-input { width:100%; border:1px solid var(--rv-border); border-radius: 10px; padding: 10px 12px; font-size: 14px; outline: none; background: #fff; }
        .rv-select:focus, .rv-textarea:focus, .rv-input:focus{ box-shadow: 0 0 0 3px rgba(139,92,246,.14); border-color:#d5c7ff; }
        .rv-textarea{ resize: vertical; }
        .rv-help{ font-size:12px; color:var(--rv-muted); }

        .rv-form-actions{ display:flex; gap:10px; justify-content:flex-end; margin-top:8px; }
        .rv-btn{ display:inline-flex; align-items:center; gap:.45rem; border-radius: 10px; padding: .55rem 1rem; font-weight:600; border: 1px solid transparent; cursor:pointer; transition: all .15s ease-in-out; }
        .rv-btn-muted{ background:#f3f4f6; color:#6b7280; border-color:#e5e7eb; }
        .rv-btn-muted:hover{ background:#e5e7eb; }
        .rv-btn-gradient{ background: linear-gradient(90deg, var(--rv-gradient-from), var(--rv-gradient-to)); color:#fff; border-color:transparent; }

        .rv-toolbar{ display:flex; align-items:center; justify-content:space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 10px; }
        .rv-show{ display:flex; align-items:center; gap:8px; }
        .rv-show select{ border:1px solid var(--rv-border); border-radius:8px; padding:6px 10px; background:#fff; }
        .rv-actions{ display:flex; align-items:center; gap:14px; }
        .rv-search{ display:flex; align-items:center; gap:8px; }
        .rv-search input{ border:1px solid var(--rv-border); border-radius:8px; padding:6px 10px; width:210px; }

        .rv-export{ display:flex; align-items:center; gap:10px; }
        .rv-btn-copy{ color:#4f46e5; background:#fff; border-color:#c7d2fe; }
        .rv-btn-copy:hover{ color:#4338ca; background:#eef2ff; border-color:#a5b4fc; }
        .rv-btn-excel{ color:#16a34a; background:#fff; border-color:#86efac; }
        .rv-btn-excel:hover{ color:#15803d; background:#ecfdf5; border-color:#6ee7b7; }
        .rv-btn-csv{ color:#6b7280; background:#fff; border-color:#e5e7eb; }
        .rv-btn-csv:hover{ color:#374151; background:#f3f4f6; border-color:#d1d5db; }
        .rv-btn-disabled{ opacity:.5; cursor:not-allowed; }

        .rv-table-wrap{ border:1px solid var(--rv-border); border-radius:12px; overflow:hidden; }
        .rv-table{ width:100%; border-collapse:collapse; }
        .rv-table thead tr{ background:#faf7ff; }
        .rv-table th, .rv-table td{ padding:10px 12px; font-size:13px; color:var(--rv-text); border-bottom:1px solid var(--rv-border); vertical-align:top; }
        .rv-th{ display:flex; align-items:center; gap:6px; }
        .rv-sort{ color:#b5acd9; font-size:12px; }
        .rv-empty{ text-align:center; color:#9aa0b4; padding:18px 10px; }
        .rv-action-icons{ display:flex; gap:8px; align-items:center; }
        .rv-icon-btn{ display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:8px; border:1px solid var(--rv-border); background:#fff; cursor:pointer; }
        .rv-icon-btn:hover{ background:#faf7ff; }
        .rv-icon-btn.rv-edit:hover{ border-color:#c7d2fe; }
        .rv-icon-btn.rv-del:hover{ border-color:#fecaca; }

        .rv-footer{ display:flex; align-items:center; justify-content:space-between; padding: 10px 4px; color:#6b7280; font-size:13px; }
        .rv-pager{ display:flex; gap:8px; }
        .rv-pager button{ background:#f3f4f6; color:#6b7280; border:1px solid #e5e7eb; padding:6px 12px; border-radius:10px; cursor:pointer; }
        .rv-pager button[disabled]{ opacity:.5; cursor:not-allowed; }
        .rv-pager button:hover:not([disabled]){ background:#e5e7eb; }

        .rv-bottom{ display:flex; align-items:center; justify-content:space-between; margin-top:16px; }
        .rv-btn-back{ background:#9ca3af; color:#fff; border-color:#9ca3af; padding:.7rem 1.4rem; border-radius:12px; }
        .rv-btn-back:hover{ background:#6b7280; }
        .rv-btn-continue{ background: linear-gradient(90deg, var(--rv-gradient-from), var(--rv-gradient-to)); color:#fff; padding:.75rem 1.6rem; border-radius:12px; }
      `}</style>
    </>
  );
}
