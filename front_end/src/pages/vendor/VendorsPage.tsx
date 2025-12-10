import React, { useEffect, useMemo, useState } from "react";
import {
  Pencil,
  Trash2,
  Copy as CopyIcon,
  Download,
  FileText,
} from "lucide-react";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import { listVendors } from "@/services/vendors";
import { api } from "@/lib/api";
import { useNavigate } from "react-router-dom";

type VendorRow = {
  /** UI serial number (S.NO) */
  id: number;
  /** Backend primary key */
  backendId: string;
  /** Vendor name */
  name: string;
  /** Vendor code */
  code: string;
  /** Primary mobile */
  mobile: string;
  /** Total branches count */
  totalBranch: number;
  /** Active / Inactive */
  isActive: boolean;
};

type SortKey = "id" | "name" | "code" | "mobile" | "totalBranch";
type SortDir = "asc" | "desc";

const arrow = (active: boolean, dir: SortDir) =>
  !active ? (
    <span className="hotel-sort-placeholder">↕</span>
  ) : dir === "asc" ? (
    <span className="hotel-sort-active">▲</span>
  ) : (
    <span className="hotel-sort-active">▼</span>
  );

/** ================= Export Helpers (Copy/CSV/Excel/PDF) ================= */

type ExportColumn<T> = {
  key: keyof T | string;
  header: string;
  getValue?: (row: T) => string | number | null | undefined;
};

function normalizeCell(v: any) {
  if (v === null || v === undefined) return "";
  if (typeof v === "boolean") return v ? "Active" : "Inactive";
  return String(v);
}

function buildAOA<T>(cols: ExportColumn<T>[], rows: T[]) {
  const headers = cols.map((c) => c.header);
  const data = rows.map((row) =>
    cols.map((c) =>
      normalizeCell(c.getValue ? c.getValue(row) : (row as any)[c.key as any])
    )
  );
  return { headers, data };
}

async function copyToClipboard<T>(cols: ExportColumn<T>[], rows: T[]) {
  const { headers, data } = buildAOA(cols, rows);
  const tsv = [headers, ...data].map((r) => r.join("\t")).join("\n");
  await navigator.clipboard.writeText(tsv);
}

function downloadCSV<T>(cols: ExportColumn<T>[], rows: T[], filename: string) {
  const { headers, data } = buildAOA(cols, rows);
  const lines = [headers, ...data].map((r) =>
    r
      .map((cell) => {
        const needsQuote = /[",\n]/.test(cell);
        let out = cell.replace(/"/g, '""');
        return needsQuote ? `"${out}"` : out;
      })
      .join(",")
  );
  const csv = "\uFEFF" + lines.join("\n"); // BOM for Excel compatibility
  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = filename;
  a.click();
  URL.revokeObjectURL(url);
}

async function downloadExcel<T>(
  cols: ExportColumn<T>[],
  rows: T[],
  filename: string,
  sheetName = "Vendors"
) {
  try {
    const XLSX = await import("xlsx");
    const { headers, data } = buildAOA(cols, rows);
    const aoa = [headers, ...data];
    const ws = XLSX.utils.aoa_to_sheet(aoa);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, sheetName);
    XLSX.writeFile(wb, filename);
  } catch {
    const safeName = filename.toLowerCase().endsWith(".xlsx")
      ? filename.replace(/\.xlsx$/i, ".csv")
      : filename + ".csv";
    downloadCSV(cols, rows, safeName);
  }
}

function downloadPDF<T>(cols: ExportColumn<T>[], rows: T[], filename: string) {
  const { headers, data } = buildAOA(cols, rows);
  const doc = new jsPDF("p", "pt", "a4");

  autoTable(doc, {
    head: [headers],
    body: data,
    styles: { fontSize: 8 },
    headStyles: { fillColor: [110, 94, 254] },
    margin: { top: 30, left: 30, right: 30 },
  });

  doc.save(filename);
}

function todaySuffix() {
  return new Date().toISOString().slice(0, 10);
}

/** ================= Page ================= */

const VendorsPage: React.FC = () => {
  const navigate = useNavigate();

  // toolbar / paging
  const [entries, setEntries] = useState(10);
  const [search, setSearch] = useState("");
  const [page, setPage] = useState(1);

  // sorting
  const [sortKey, setSortKey] = useState<SortKey>("id");
  const [sortDir, setSortDir] = useState<SortDir>("asc");

  // data
  const [rows, setRows] = useState<VendorRow[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  // ===== Fetch vendor list once =====
    useEffect(() => {
    let aborted = false;

    (async () => {
        try {
        setLoading(true);
        setError(null);

        // use normalized service
        const { items } = await listVendors();

        if (aborted) return;

        const mapped: VendorRow[] = items.map((v, ix) => ({
            id: ix + 1,            // UI S.NO
            backendId: v.id,       // already string in service
            name: v.name || "—",
            code: v.code || "—",
            mobile: v.mobile || "—",
            totalBranch: v.totalBranch ?? 0,
            isActive: v.isActive,
        }));

        setRows(mapped);
        } catch (e: any) {
        console.error("Failed to load vendors", e);
        if (!aborted) {
            setError(
            e?.message || "Unable to load vendors. Please try again later."
            );
        }
        } finally {
        if (!aborted) setLoading(false);
        }
    })();

    return () => {
        aborted = true;
    };
    }, []);

  /** ===== Filtering, sorting, paging (client-side like DataTables) ===== */

  const filtered = useMemo(() => {
    const q = search.trim().toLowerCase();
    if (!q) return rows;
    return rows.filter((r) => {
      const fields = [
        String(r.id),
        r.name,
        r.code,
        r.mobile,
        String(r.totalBranch),
        r.isActive ? "active" : "inactive",
      ];
      return fields.some((v) =>
        (v ?? "").toString().toLowerCase().includes(q)
      );
    });
  }, [rows, search]);

  const sorted = useMemo(() => {
    const list = filtered.slice();
    list.sort((a, b) => {
      const va = a[sortKey];
      const vb = b[sortKey];

      if (typeof va === "number" && typeof vb === "number") {
        return sortDir === "asc" ? va - vb : vb - va;
      }

      const sa = String(va ?? "").toLowerCase();
      const sb = String(vb ?? "").toLowerCase();
      if (sa < sb) return sortDir === "asc" ? -1 : 1;
      if (sa > sb) return sortDir === "asc" ? 1 : -1;
      return 0;
    });
    return list;
  }, [filtered, sortKey, sortDir]);

  const total = sorted.length;
  const totalPages = Math.max(1, Math.ceil(total / entries));
  const safePage = Math.min(page, totalPages);

  useEffect(() => {
    if (page > totalPages) setPage(totalPages);
  }, [page, totalPages]);

  const startIndex = (safePage - 1) * entries;
  const endIndex = startIndex + entries;
  const pageRows = sorted.slice(startIndex, endIndex);

  const startItem = total === 0 ? 0 : startIndex + 1;
  const endItem = Math.min(endIndex, total);

  const windowSize = 5;
  const startPage = Math.max(
    1,
    Math.min(
      safePage - Math.floor(windowSize / 2),
      Math.max(1, totalPages - windowSize + 1)
    )
  );
  const endPage = Math.min(totalPages, startPage + windowSize - 1);

  // sorting handler
  const handleSort = (key: SortKey) => {
    if (key === sortKey) setSortDir((d) => (d === "asc" ? "desc" : "asc"));
    else {
      setSortKey(key);
      setSortDir("asc");
    }
    setPage(1);
  };

  /** ===== Export columns ===== */
  const exportCols: ExportColumn<VendorRow>[] = [
    { key: "id", header: "S.NO" },
    { key: "name", header: "Vendor Name" },
    { key: "code", header: "Vendor Code" },
    { key: "mobile", header: "Vendor Mobile" },
    { key: "totalBranch", header: "Total Branch" },
    {
      key: "isActive",
      header: "Status",
      getValue: (r) => (r.isActive ? "Active" : "Inactive"),
    },
  ];

  // EXPORT handlers — always use the filtered+sorted data (like DataTables)
  const handleCopy = async () => {
    try {
      await copyToClipboard(exportCols, sorted);
    } catch {
      // ignore
    }
  };

  const handleCSV = () => {
    downloadCSV(exportCols, sorted, `vendors_${todaySuffix()}.csv`);
  };

  const handleExcel = () => {
    downloadExcel(exportCols, sorted, `vendors_${todaySuffix()}.xlsx`, "Vendors");
  };

  const handlePDF = () => {
    downloadPDF(exportCols, sorted, `vendors_${todaySuffix()}.pdf`);
  };

  // Delete a vendor with confirmation, then update UI
  const handleDeleteVendor = async (row: VendorRow) => {
    const ok = window.confirm(
      `Are you sure you want to delete vendor "${row.name}"?`
    );
    if (!ok) return;

    try {
      // Assumes your NestJS controller exposes DELETE /vendors/:id
      await api(`/vendors/${row.backendId}`, { method: "DELETE" });

      // Remove from current list
      setRows((prev) => prev.filter((r) => r.backendId !== row.backendId));
    } catch (err: any) {
      console.error("Failed to delete vendor", err);
      alert(
        err?.message || "Failed to delete vendor. Please try again later."
      );
    }
  };

  /** ================= RENDER (1:1 with Hotel layout) ================= */

  return (
    <div className="hotel-page-wrapper" style={{ padding: 0 }}>
      <div className="hotel-card">
        {/* Header: title + Add Vendor button, like PHP page */}
        <div className="hotel-card-head">
          <h2 className="hotel-card-title">List of Vendor</h2>
          <div className="hotel-head-actions">
            <button
                className="hotel-add-btn"
                type="button"
                onClick={() => {
                  navigate("/vendor/new");
                }}
                >
                + Add vendor
              </button>
          </div>
        </div>

        {/* Toolbar: Show entries + Copy / Excel / CSV / PDF + Search */}
        <div className="hotel-toolbar">
          <div className="hotel-show-entries">
            <span>Show</span>
            <select
              value={entries}
              onChange={(e) => {
                setEntries(Number(e.target.value) || 10);
                setPage(1);
              }}
            >
              <option value={10}>10</option>
              <option value={25}>25</option>
              <option value={50}>50</option>
              <option value={100}>100</option>
            </select>
            <span>entries</span>
          </div>

          <div className="hotel-right-tools">
            <div className="hotel-export-group">
              <button
                onClick={handleCopy}
                className="hotel-copy-btn"
                title="Copy"
                type="button"
              >
                <CopyIcon className="w-4 h-4" />
                <span>Copy</span>
              </button>
              <button
                onClick={handleExcel}
                className="hotel-excel-btn"
                title="Excel"
                type="button"
              >
                <Download className="w-4 h-4" />
                <span>Excel</span>
              </button>
              <button
                onClick={handleCSV}
                className="hotel-csv-btn"
                title="CSV"
                type="button"
              >
                <Download className="w-4 h-4" />
                <span>CSV</span>
              </button>
              <button
                onClick={handlePDF}
                className="hotel-pdf-btn"
                title="PDF"
                type="button"
              >
                <FileText className="w-4 h-4" />
                <span>PDF</span>
              </button>
            </div>

            <div className="hotel-search-box">
              <label>Search:</label>
              <input
                value={search}
                onChange={(e) => {
                  setSearch(e.target.value);
                  setPage(1);
                }}
              />
            </div>
          </div>
        </div>

        {/* Table */}
        <div className="hotel-table-wrap">
          <table className="hotel-table">
            <thead>
              <tr>
                <th onClick={() => handleSort("id")}>
                  <div className="hotel-th-inner">
                    <span>S.NO</span>
                    {arrow(sortKey === "id", sortDir)}
                  </div>
                </th>
                <th>
                  <span>Action</span>
                </th>
                <th onClick={() => handleSort("name")}>
                  <div className="hotel-th-inner">
                    <span>Vendor Name</span>
                    {arrow(sortKey === "name", sortDir)}
                  </div>
                </th>
                <th onClick={() => handleSort("code")}>
                  <div className="hotel-th-inner">
                    <span>Vendor Code</span>
                    {arrow(sortKey === "code", sortDir)}
                  </div>
                </th>
                <th onClick={() => handleSort("mobile")}>
                  <div className="hotel-th-inner">
                    <span>Vendor Mobile</span>
                    {arrow(sortKey === "mobile", sortDir)}
                  </div>
                </th>
                <th onClick={() => handleSort("totalBranch")}>
                  <div className="hotel-th-inner">
                    <span>Total Branch</span>
                    {arrow(sortKey === "totalBranch", sortDir)}
                  </div>
                </th>
                <th>
                  <div className="hotel-th-inner">
                    <span>Status</span>
                    <span className="hotel-sort-placeholder">↕</span>
                  </div>
                </th>
              </tr>
            </thead>

            <tbody>
              {loading ? (
                <tr>
                  <td colSpan={7} className="hotel-empty">
                    Loading...
                  </td>
                </tr>
              ) : error ? (
                <tr>
                  <td colSpan={7} className="hotel-empty hotel-error">
                    {error}
                  </td>
                </tr>
              ) : pageRows.length === 0 ? (
                <tr>
                  <td colSpan={7} className="hotel-empty">
                    No vendors found
                  </td>
                </tr>
              ) : (
                pageRows.map((row, idx) => (
                  <tr key={`${row.backendId}-${row.id}-${idx}`}>
                    <td>{startIndex + idx + 1}</td>
                    <td>
                      <div className="hotel-action-col">
                        <button
                          className="hotel-action-circle edit"
                          title="Edit"
                          type="button"
                          onClick={() => {
                            navigate(`/vendor/${row.backendId}`);
                          }}
                          >
                          <Pencil className="w-4 h-4" />
                        </button>
                        <button
                          className="hotel-action-circle del"
                          title="Delete"
                          type="button"
                          onClick={() => handleDeleteVendor(row)}
                          >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                    <td className="text-uppercase">{row.name}</td>
                    <td>{row.code}</td>
                    <td>{row.mobile}</td>
                    <td>{row.totalBranch}</td>
                    <td>
                      <button
                        aria-pressed={row.isActive}
                        className={`hotel-toggle ${
                          row.isActive ? "active" : "off"
                        }`}
                        title={row.isActive ? "Active" : "Inactive"}
                        type="button"
                      >
                        <span className="hotel-toggle-knob" />
                      </button>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>

        {/* Footer: "Showing 1 to 10 of 18 entries" + pagination */}
        <div className="hotel-footer">
          <p>
            Showing <strong>{total === 0 ? 0 : startItem}</strong> to{" "}
            <strong>{endItem}</strong> of <strong>{total}</strong> entries
          </p>

          <div className="hotel-pagination">
            <button
              onClick={() => setPage((p) => Math.max(1, p - 1))}
              disabled={safePage === 1}
              type="button"
            >
              Previous
            </button>
            {Array.from(
              { length: endPage - startPage + 1 },
              (_, i) => startPage + i
            ).map((pageNum) => (
              <button
                key={pageNum}
                onClick={() => setPage(pageNum)}
                className={safePage === pageNum ? "active" : ""}
                type="button"
              >
                {pageNum}
              </button>
            ))}
            <button
              onClick={() => setPage((p) => p + 1)}
              disabled={safePage >= totalPages}
              type="button"
            >
              Next
            </button>
          </div>
        </div>

        {/* Small footer note (optional, like Hotels) */}
        <div className="hotel-footer-note">DVI Holidays @ 2025</div>
      </div>

      {/* Reuse the same Hotel styles so Vendor page looks 1:1 */}
      <style>{`
        .hotel-page-wrapper { position: relative; z-index: 10; }

        .hotel-card {
          max-width: none !important;
          width: 100% !important;
          overflow: visible;
          padding-left: 1rem;
          padding-right: 1rem;
        }
        @media (min-width: 1024px) {
          .hotel-card { padding-left: 1.5rem; padding-right: 1.5rem; }
        }

        .hotel-card-head {
          display:flex;
          align-items:center;
          justify-content:space-between;
          padding:.75rem .5rem 0.25rem .5rem;
        }
        .hotel-card-title {
          font-size:1.15rem;
          font-weight:600;
          margin:0;
        }
        .hotel-head-actions {
          display:flex;
          align-items:center;
          gap:.5rem;
        }
        .hotel-add-btn {
          border-radius:.75rem;
          padding:.4rem 1.1rem;
          font-size:.85rem;
          font-weight:600;
          background:linear-gradient(90deg,#e11d8c,#6366f1);
          border:none;
          color:white;
          box-shadow:0 8px 18px rgba(99,102,241,.3);
          cursor:pointer;
        }
        .hotel-add-btn:hover {
          filter:brightness(1.05);
          box-shadow:0 10px 22px rgba(99,102,241,.35);
        }

        .hotel-toolbar {
          display:flex;
          align-items:center;
          justify-content:space-between;
          gap:1rem;
          flex-wrap:wrap;
          padding:.5rem .5rem .75rem .5rem;
        }
        .hotel-show-entries {
          display:flex;
          align-items:center;
          gap:.35rem;
          font-size:.85rem;
        }
        .hotel-show-entries select {
          border-radius:.5rem;
          padding:.15rem .4rem;
          border:1px solid #e5e7eb;
          font-size:.8rem;
        }

        .hotel-right-tools {
          display:flex;
          align-items:center;
          gap:.75rem;
          flex-wrap:wrap;
          justify-content:flex-end;
        }

        .hotel-export-group {
          display:flex;
          align-items:center;
          gap:.4rem;
          flex-wrap:wrap;
        }
        .hotel-copy-btn,
        .hotel-excel-btn,
        .hotel-csv-btn,
        .hotel-pdf-btn {
          display:inline-flex;
          align-items:center;
          gap:.35rem;
          border-radius:.6rem;
          padding:.25rem .7rem;
          font-size:.8rem;
          font-weight:500;
          border:1px solid transparent;
          background:#ffffff;
          cursor:pointer;
          transition:all .15s ease;
        }
        .hotel-copy-btn {
          color:#6366f1;
          border-color:#c7d2fe;
        }
        .hotel-copy-btn:hover {
          color:#4f46e5;
          background:#eef2ff;
          border-color:#a5b4fc;
        }
        .hotel-excel-btn {
          color:#16a34a;
          border-color:#bbf7d0;
          background:#f0fdf4;
        }
        .hotel-excel-btn:hover {
          background:#dcfce7;
          border-color:#86efac;
        }
        .hotel-csv-btn {
          color:#0f766e;
          border-color:#99f6e4;
          background:#ecfeff;
        }
        .hotel-csv-btn:hover {
          background:#ccfbf1;
          border-color:#5eead4;
        }
        .hotel-pdf-btn {
          color:#dc2626;
          border-color:#fecaca;
          background:#fef2f2;
        }
        .hotel-pdf-btn:hover {
          background:#fee2e2;
          border-color:#fca5a5;
        }

        .hotel-search-box {
          display:flex;
          align-items:center;
          gap:.4rem;
          font-size:.85rem;
        }
        .hotel-search-box label {
          white-space:nowrap;
        }
        .hotel-search-box input {
          border-radius:.5rem;
          padding:.2rem .55rem;
          border:1px solid #e5e7eb;
          font-size:.8rem;
          min-width:160px;
        }

        .hotel-table-wrap {
          padding-left:.25rem;
          padding-right:.25rem;
          overflow-x:auto;
          overflow-y:visible;
          max-width:100%;
        }
        .hotel-table {
          width:100%;
          min-width:900px;
          table-layout:auto;
          border-collapse:separate;
          border-spacing:0;
          font-size:.85rem;
        }
        .hotel-table thead tr th {
          padding:.55rem .6rem;
          background:#f9fafb;
          border-bottom:1px solid #e5e7eb;
          font-weight:600;
          color:#4b5563;
          white-space:nowrap;
        }
        .hotel-table tbody tr td {
          padding:.5rem .6rem;
          border-bottom:1px solid #f1f5f9;
          color:#111827;
        }
        .hotel-table tbody tr:hover {
          background:#f9fafb;
        }
        .hotel-th-inner {
          display:flex;
          align-items:center;
          justify-content:space-between;
          gap:.35rem;
        }
        .hotel-sort-placeholder {
          opacity:.35;
          font-size:.7rem;
        }
        .hotel-sort-active {
          color:#6366f1;
          font-size:.7rem;
        }
        .hotel-empty {
          text-align:center;
          padding:1.75rem .5rem;
          color:#6b7280;
        }
        .hotel-error {
          color:#b91c1c;
        }

        .hotel-action-col {
          display:flex;
          align-items:center;
          gap:.35rem;
        }
        .hotel-action-circle {
          width:28px;
          height:28px;
          border-radius:9999px;
          display:inline-flex;
          align-items:center;
          justify-content:center;
          border:1px solid #e5e7eb;
          background:#ffffff;
          cursor:pointer;
          transition:all .15s ease;
        }
        .hotel-action-circle.edit {
          color:#2563eb;
          border-color:#bfdbfe;
          background:#eff6ff;
        }
        .hotel-action-circle.edit:hover {
          background:#dbeafe;
          border-color:#93c5fd;
        }
        .hotel-action-circle.del {
          color:#dc2626;
          border-color:#fecaca;
          background:#fef2f2;
        }
        .hotel-action-circle.del:hover {
          background:#fee2e2;
          border-color:#fca5a5;
        }

        .hotel-toggle {
          position:relative;
          width:40px;
          height:22px;
          border-radius:9999px;
          display:inline-flex;
          align-items:center;
          padding:0;
          border:2px solid #8b5cf6;
          background:#ffffff;
          cursor:pointer;
          transition:background .18s ease, border-color .18s ease, opacity .2s ease;
        }
        .hotel-toggle[disabled] {
          opacity:.6;
          cursor:not-allowed;
        }
        .hotel-toggle.active {
          background:#8b5cf6;
          border-color:#8b5cf6;
        }
        .hotel-toggle-knob {
          position:absolute;
          width:18px;
          height:18px;
          border-radius:9999px;
          box-shadow:0 1px 2px rgba(0,0,0,.15);
          transition:transform .18s ease-in-out, background-color .18s ease-in-out;
          left:2px;
          transform:translateX(0);
        }
        .hotel-toggle.off .hotel-toggle-knob {
          background:#cbd5e1;
          border:1px solid #94a3b8;
        }
        .hotel-toggle.active .hotel-toggle-knob {
          background:#ffffff;
          transform:translateX(18px);
        }

        .hotel-footer {
          display:flex;
          align-items:center;
          justify-content:space-between;
          gap:1rem;
          flex-wrap:wrap;
          padding:.75rem .75rem;
          font-size:.82rem;
        }
        .hotel-footer > p { margin:0; }

        .hotel-pagination {
          display:flex;
          flex-wrap:wrap;
          gap:.5rem;
          align-items:center;
          justify-content:flex-start;
          width:100%;
        }
        .hotel-pagination button {
          min-width:2rem;
          height:2rem;
          padding:0 .6rem;
          border-radius:.75rem;
          border:1px solid #e5e7eb;
          background:#ffffff;
          font-size:.8rem;
          cursor:pointer;
        }
        .hotel-pagination button.active {
          background:linear-gradient(90deg,#6366f1,#e11d8c);
          color:#ffffff;
          border-color:transparent;
          font-weight:600;
        }
        .hotel-pagination button:disabled {
          opacity:.5;
          cursor:not-allowed;
        }

        .hotel-footer-note {
          padding:0 .75rem 1rem .75rem;
          font-size:.7rem;
          color:#9ca3af;
        }
      `}</style>
    </div>
  );
};

export default VendorsPage;
