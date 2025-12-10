// FILE: src/pages/Hotels.tsx
import React, { useEffect, useMemo, useRef, useState } from "react";
import {
  Eye,
  Pencil,
  Trash2,
  Copy as CopyIcon,
  Download,
  ChevronDown,
  ChevronUp,
} from "lucide-react";
import {
  listHotels,
  updateHotel,
  deleteHotel,
  type Hotel,
  listStatesMeta,
  listCitiesMeta,
} from "@/services/hotels";
import { useNavigate } from "react-router-dom";

/** ================= UI Types ================= */
type HotelRow = {
  id: number; // UI running serial number only (not backend id)
  backendId: string; // backend PK
  name: string;
  code: string;

  // Raw IDs from DB (may be string/number)
  stateId?: string | number | null;
  cityId?: string | number | null;

  // Derived names via maps
  stateName: string;
  cityName: string;

  mobile: string;
  isActive: boolean;
};

type SortKey = "id" | "name" | "code" | "stateName" | "cityName" | "mobile";
type SortDir = "asc" | "desc";

const arrow = (active: boolean, dir: SortDir) =>
  !active ? (
    <span className="hotel-sort-placeholder">↕</span>
  ) : dir === "asc" ? (
    <span className="hotel-sort-active">▲</span>
  ) : (
    <span className="hotel-sort-active">▼</span>
  );

/** ================= Export Helpers (Copy/CSV/Excel) ================= */
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
  sheetName = "Hotels"
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

/** filename helper */
function todaySuffix() {
  return new Date().toISOString().slice(0, 10);
}

/** ================= Page ================= */
const HotelPage: React.FC = () => {
  const navigate = useNavigate();

  // toolbar / filters / paging
  const [showFilter, setShowFilter] = useState(false);
  const [entries, setEntries] = useState(10);
  const [search, setSearch] = useState("");
  const [page, setPage] = useState(1);

  const [filterState, setFilterState] = useState(""); // filter by stateName
  const [filterCity, setFilterCity] = useState(""); // filter by cityName

  const [sortKey, setSortKey] = useState<SortKey>("id");
  const [sortDir, setSortDir] = useState<SortDir>("asc");

  // data
  const [rows, setRows] = useState<HotelRow[]>([]);
  /** Unfiltered rows (used to build fast, lightweight filter options) */
  const [allRows, setAllRows] = useState<HotelRow[]>([]);
  const [total, setTotal] = useState(0);
  const [loading, setLoading] = useState(false);

  // in-flight flags
  const [savingId, setSavingId] = useState<string | null>(null);
  const [deletingId, setDeletingId] = useState<string | null>(null);

  // ===== Meta lookups =====
  const [stateMap, setStateMap] = useState<Record<string, string>>({});
  const [cityMap, setCityMap] = useState<Record<string, string>>({});

  // ===== Price Book dropdown =====
  const [pbOpen, setPbOpen] = useState(false);
  const pbRef = useRef<HTMLDivElement | null>(null);
  useEffect(() => {
    function onDocClick(e: MouseEvent) {
      if (!pbRef.current) return;
      if (!pbRef.current.contains(e.target as Node)) setPbOpen(false);
    }
    function onEsc(e: KeyboardEvent) {
      if (e.key === "Escape") setPbOpen(false);
    }
    document.addEventListener("click", onDocClick);
    document.addEventListener("keydown", onEsc);
    return () => {
      document.removeEventListener("click", onDocClick);
      document.removeEventListener("keydown", onEsc);
    };
  }, []);

  // Fetch states & cities once (FROM SERVICE, not TSX HTTP)
  useEffect(() => {
    let aborted = false;
    (async () => {
      try {
        const [states, cities] = await Promise.all([
          listStatesMeta().catch(() => []),
          listCitiesMeta().catch(() => []),
        ]);

        if (aborted) return;

        const normStates: Array<{ id: string; name: string }> = (states || [])
          .map((s: any) => ({
            id: String(s.id ?? s.state_id ?? s.stateId ?? ""),
            name: String(s.name ?? s.state_name ?? s.stateName ?? "").trim(),
          }))
          .filter((x) => x.id);

        const normCities: Array<{ id: string; name: string; state_id?: string }> =
          (cities || [])
            .map((c: any) => ({
              id: String(c.id ?? c.city_id ?? c.cityId ?? ""),
              name: String(c.name ?? c.city_name ?? c.cityName ?? "").trim(),
              state_id:
                c.state_id != null
                  ? String(c.state_id)
                  : c.stateId != null
                  ? String(c.stateId)
                  : undefined,
            }))
            .filter((x) => x.id);

        const sMap: Record<string, string> = {};
        for (const s of normStates) sMap[s.id] = s.name || s.id;

        const cMap: Record<string, string> = {};
        for (const c of normCities) cMap[c.id] = c.name || c.id;

        setStateMap(sMap);
        setCityMap(cMap);
      } catch {
        // keep maps empty
      }
    })();
    return () => {
      aborted = true;
    };
  }, []);

  // Adapter: API Hotel → UI row (without names yet)
  const toRowBase = (h: Hotel, ix: number): Omit<HotelRow, "stateName" | "cityName"> => ({
    id: (page - 1) * entries + ix + 1,
    backendId: String((h as any).id ?? (h as any).hotel_id ?? ""),
    name: (h as any).name ?? (h as any).hotel_name ?? "",
    code: (h as any).code ?? (h as any).hotel_code ?? "",
    stateId: (h as any).hotel_state ?? (h as any).state ?? null,
    cityId: (h as any).hotel_city ?? (h as any).city ?? null,
    mobile:
      (h as any).phone ??
      (h as any).hotel_mobile ??
      (h as any).hotel_mobile_no ??
      "",
    isActive:
      (h as any).isActive !== undefined
        ? !!(h as any).isActive
        : ((h as any).status ?? (h as any).hotel_status ?? 1) == 1,
  });

  // Helper: attach names using current maps
  const withNames = (r: ReturnType<typeof toRowBase>): HotelRow => {
    const stateKey = r.stateId != null ? String(r.stateId) : "";
    const cityKey = r.cityId != null ? String(r.cityId) : "";
    return {
      ...r,
      stateName: stateMap[stateKey] ?? (stateKey || ""),
      cityName: cityMap[cityKey] ?? (cityKey || ""),
    };
  };

  // Fetch hotel list whenever deps change
  useEffect(() => {
    let aborted = false;
    (async () => {
      try {
        setLoading(true);
        const resp = await listHotels({ search, page, limit: entries });

        // `listHotels` already normalizes backend shapes to { page, total, items }
        const source: Hotel[] = resp.items ?? [];
        const mapped: HotelRow[] = source.map((h: Hotel, ix: number) =>
          withNames(toRowBase(h, ix))
        );

        if (!aborted) setAllRows(mapped);

        let list = mapped.slice();

        if (filterState) list = list.filter((r) => r.stateName === filterState);
        if (filterCity) list = list.filter((r) => r.cityName === filterCity);

        const q = (search || "").trim().toLowerCase();
        if (q) {
          list = list.filter((r) => {
            const fields = [
              String(r.id),
              r.name,
              r.code,
              r.stateName,
              r.cityName,
              r.mobile,
              r.isActive ? "active" : "inactive",
            ];
            return fields.some((v) =>
              (v ?? "").toString().toLowerCase().includes(q)
            );
          });
        }

        list = list.slice().sort((a, b) => {
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

        if (!aborted) {
          setRows(list);
          const anyClientFilter = !!filterState || !!filterCity || !!q;
          const totalFromApi = Number(resp.total ?? source.length);
          setTotal(anyClientFilter ? list.length : totalFromApi);
        }
      } finally {
        if (!aborted) setLoading(false);
      }
    })();
    return () => {
      aborted = true;
    };
  }, [
    page,
    entries,
    search,
    filterState,
    filterCity,
    sortKey,
    sortDir,
    stateMap,
    cityMap,
  ]);

  // sorting
  const handleSort = (key: SortKey) => {
    if (key === sortKey) setSortDir((d) => (d === "asc" ? "desc" : "asc"));
    else {
      setSortKey(key);
      setSortDir("asc");
    }
    setPage(1);
  };

  /** ===== Export columns (clean headers & values) ===== */
  const exportCols: ExportColumn<HotelRow>[] = [
    { key: "id", header: "S.NO" },
    { key: "name", header: "Hotel Name" },
    { key: "code", header: "Hotel Code" },
    { key: "stateName", header: "Hotel State" },
    { key: "cityName", header: "Hotel City" },
    { key: "mobile", header: "Hotel Mobile" },
    {
      key: "isActive",
      header: "Hotel Status",
      getValue: (r) => (r.isActive ? "Active" : "Inactive"),
    },
  ];

  // EXPORT handlers
  const handleCopy = async () => {
    try {
      await copyToClipboard(exportCols, rows);
    } catch {}
  };
  const handleCSV = () => {
    downloadCSV(exportCols, rows, `hotels_${todaySuffix()}.csv`);
  };
  const handleExcel = () => {
    downloadExcel(exportCols, rows, `hotels_${todaySuffix()}.xlsx`, "Hotels");
  };

  // actions
  const toggleStatus = async (row: HotelRow) => {
    const next = !row.isActive;
    try {
      setSavingId(row.backendId);
      await updateHotel(row.backendId, { isActive: next });
      setRows((prev) =>
        prev.map((r) =>
          r.backendId === row.backendId ? { ...r, isActive: next } : r
        )
      );
    } finally {
      setSavingId(null);
    }
  };
  const handleView = (row: HotelRow) => {
    navigate(`/hotels/${row.backendId}/preview`);
  };
  const handleEdit = (row: HotelRow) => {
    navigate(`/hotels/${row.backendId}/edit`);
  };
  const handleDelete = async (row: HotelRow) => {
    if (deletingId) return;
    const ok = window.confirm(
      `Delete hotel "${row.name}" (ID: ${row.backendId})?\nThis will remove all related records for this hotel.`
    );
    if (!ok) return;

    setDeletingId(row.backendId);
    const prev = rows;
    setRows((p) => p.filter((r) => r.backendId !== row.backendId));
    setTotal((t) => Math.max(0, t - 1));

    try {
      await deleteHotel(row.backendId);
    } catch (e) {
      setRows(prev);
      setTotal(prev.length);
      alert(
        (e as any)?.message ??
          "Failed to delete hotel. Please try again or check server logs."
      );
    } finally {
      setDeletingId(null);
    }
  };

  // pagination calc
  const totalPages = Math.ceil(total / entries) || 1;
  const safePage = Math.min(page, totalPages);
  const startItem = total === 0 ? 0 : (safePage - 1) * entries + 1;
  const endItem = Math.min(safePage * entries, total);
  const windowSize = 5;
  const startPage = Math.max(
    1,
    Math.min(
      page - Math.floor(windowSize / 2),
      Math.max(1, totalPages - windowSize + 1)
    )
  );
  const endPage = Math.min(totalPages, startPage + windowSize - 1);

  // fast filter options (from current list only)
  const stateOptions = useMemo(() => {
    const set = new Set<string>();
    for (const r of allRows) if (r.stateName) set.add(r.stateName);
    return Array.from(set).sort((a, b) => a.localeCompare(b));
  }, [allRows]);

  const cityOptions = useMemo(() => {
    const set = new Set<string>();
    for (const r of allRows) {
      if (filterState && r.stateName !== filterState) continue;
      if (r.cityName) set.add(r.cityName);
    }
    return Array.from(set).sort((a, b) => a.localeCompare(b));
  }, [allRows, filterState]);

  // PRICE BOOK item handlers
  const goRoomsPriceBook = () => {
    navigate("/pricebook/hotels/rooms/import");
    setPbOpen(false);
  };
  const goAmenitiesPriceBook = () => {
    navigate("/pricebook/hotels/amenities/import");
    setPbOpen(false);
  };

  return (
    <div className="hotel-page-wrapper" style={{ padding: 0 }}>
      <div className="hotel-card">
        {/* header */}
        <div className="hotel-card-head">
          <h2 className="hotel-card-title">List of Hotel</h2>
          <div className="hotel-head-actions">
            <button
              type="button"
              onClick={() => setShowFilter((p) => !p)}
              className={`hotel-filter-btn ${
                showFilter ? "hotel-filter-btn-active" : ""
              }`}
            >
              <span>Filter</span>
              {showFilter ? (
                <ChevronUp className="w-4 h-4" />
              ) : (
                <ChevronDown className="w-4 h-4" />
              )}
            </button>

            <button
              className="hotel-add-btn"
              onClick={() => navigate("/hotels/new")}
            >
              + Add Hotel
            </button>

            {/* PRICE BOOK split button + dropdown */}
            <div className="hotel-pricebook" ref={pbRef}>
              <button
                type="button"
                className="hotel-pricebook-btn"
                aria-haspopup="menu"
                aria-expanded={pbOpen}
                onClick={() => setPbOpen((v) => !v)}
              >
                <span>Price Book</span>
                <ChevronDown className="w-4 h-4" />
              </button>

              {pbOpen && (
                <div className="hotel-pricebook-menu" role="menu" tabIndex={-1}>
                  <button
                    role="menuitem"
                    className="hotel-pricebook-item"
                    onClick={goRoomsPriceBook}
                  >
                    Rooms Price Book (Import)
                  </button>
                  <button
                    role="menuitem"
                    className="hotel-pricebook-item"
                    onClick={goAmenitiesPriceBook}
                  >
                    Amenities Price Book (Import)
                  </button>
                </div>
              )}
            </div>
          </div>
        </div>

        {/* filter */}
        {showFilter && (
          <div className="hotel-filter-box">
            <div className="hotel-filter-item">
              <label className="hotel-filter-label">State *</label>
              <select
                value={filterState}
                onChange={(e) => {
                  setFilterState(e.target.value);
                  setFilterCity("");
                  setPage(1);
                }}
                className="hotel-filter-select"
              >
                <option value="">Choose State</option>
                {stateOptions.map((name) => (
                  <option key={name} value={name}>
                    {name}
                  </option>
                ))}
              </select>
            </div>
            <div className="hotel-filter-item">
              <label className="hotel-filter-label">City *</label>
              <select
                value={filterCity}
                onChange={(e) => {
                  setFilterCity(e.target.value);
                  setPage(1);
                }}
                className="hotel-filter-select"
              >
                <option value="">Please Choose City</option>
                {cityOptions.map((c) => (
                  <option key={c} value={c}>
                    {c}
                  </option>
                ))}
              </select>
            </div>
            <div className="hotel-filter-actions">
              <button
                type="button"
                onClick={() => {
                  setFilterState("");
                  setFilterCity("");
                  setPage(1);
                }}
                className="hotel-filter-clear"
              >
                Clear
              </button>
            </div>
          </div>
        )}

        {/* toolbar */}
        <div className="hotel-toolbar">
          <div className="hotel-show-entries">
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

          <div className="hotel-right-tools">
            <div className="hotel-export-group">
              <button
                onClick={handleCopy}
                className="hotel-copy-btn"
                title="Copy"
              >
                <CopyIcon className="w-4 h-4" />
                <span>Copy</span>
              </button>
              <button
                onClick={handleExcel}
                className="hotel-excel-btn"
                title="Excel"
              >
                <Download className="w-4 h-4" />
                <span>Excel</span>
              </button>
              <button
                onClick={handleCSV}
                className="hotel-csv-btn"
                title="CSV"
              >
                <Download className="w-4 h-4" />
                <span>CSV</span>
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

        {/* table */}
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
                    <span>Hotel Name</span>
                    {arrow(sortKey === "name", sortDir)}
                  </div>
                </th>
                <th onClick={() => handleSort("code")}>
                  <div className="hotel-th-inner">
                    <span>Hotel Code</span>
                    {arrow(sortKey === "code", sortDir)}
                  </div>
                </th>
                <th onClick={() => handleSort("stateName")}>
                  <div className="hotel-th-inner">
                    <span>Hotel State</span>
                    {arrow(sortKey === "stateName", sortDir)}
                  </div>
                </th>
                <th onClick={() => handleSort("cityName")}>
                  <div className="hotel-th-inner">
                    <span>Hotel City</span>
                    {arrow(sortKey === "cityName", sortDir)}
                  </div>
                </th>
                <th onClick={() => handleSort("mobile")}>
                  <div className="hotel-th-inner">
                    <span>Hotel Mobile</span>
                    {arrow(sortKey === "mobile", sortDir)}
                  </div>
                </th>
                <th>
                  <div className="hotel-th-inner">
                    <span>Hotel Status</span>
                    <span className="hotel-sort-placeholder">↕</span>
                  </div>
                </th>
              </tr>
            </thead>

            <tbody>
              {loading ? (
                <tr>
                  <td colSpan={8} className="hotel-empty">
                    Loading...
                  </td>
                </tr>
              ) : rows.length === 0 ? (
                <tr>
                  <td colSpan={8} className="hotel-empty">
                    No data found
                  </td>
                </tr>
              ) : (
                rows.map((row) => (
                  <tr key={`${row.backendId}-${row.id}`}>
                    <td>{row.id}</td>
                    <td>
                      <div className="hotel-action-col">
                        <button
                          className="hotel-action-circle view"
                          title="Preview"
                          onClick={() => handleView(row)}
                        >
                          <Eye className="w-4 h-4" />
                        </button>
                        <button
                          className="hotel-action-circle edit"
                          title="Edit"
                          onClick={() => handleEdit(row)}
                        >
                          <Pencil className="w-4 h-4" />
                        </button>
                        <button
                          className="hotel-action-circle del"
                          title="Delete"
                          onClick={() => handleDelete(row)}
                          disabled={deletingId === row.backendId}
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                    <td>{row.name}</td>
                    <td>{row.code}</td>
                    <td>{row.stateName}</td>
                    <td>{row.cityName}</td>
                    <td>{row.mobile}</td>
                    <td>
                      <button
                        aria-pressed={row.isActive}
                        onClick={() => toggleStatus(row)}
                        disabled={savingId === row.backendId}
                        className={`hotel-toggle ${
                          row.isActive ? "active" : "off"
                        }`}
                        title={row.isActive ? "Active" : "Inactive"}
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

        {/* footer */}
        <div className="hotel-footer">
          <p>
            Showing <strong>{total === 0 ? 0 : startItem}</strong> to{" "}
            <strong>{endItem}</strong> of <strong>{total}</strong> entries
          </p>

          <div className="hotel-pagination">
            <button
              onClick={() => setPage((p) => Math.max(1, p - 1))}
              disabled={page === 1}
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
                className={page === pageNum ? "active" : ""}
              >
                {pageNum}
              </button>
            ))}
            <button
              onClick={() => setPage((p) => p + 1)}
              disabled={page >= (Math.ceil(total / entries) || 1)}
            >
              Next
            </button>
          </div>
        </div>

        <div className="hotel-footer-note">DVI Holidays @ 2025</div>
      </div>

      {/* Styles: toggle + layout + pricebook menu */}
      <style>{`
        .hotel-toggle {
          position: relative; width: 40px; height: 22px; border-radius: 9999px;
          display: inline-flex; align-items: center; padding: 0;
          border: 2px solid #8b5cf6; background: #ffffff; cursor: pointer;
          transition: background .18s ease, border-color .18s ease, opacity .2s ease;
        }
        .hotel-toggle[disabled]{ opacity:.6; cursor:not-allowed; }
        .hotel-toggle.active{ background:#8b5cf6; border-color:#8b5cf6; }
        .hotel-toggle-knob{
          position:absolute; width:18px; height:18px; border-radius:9999px;
          box-shadow:0 1px 2px rgba(0,0,0,.15);
          transition: transform .18s ease-in-out, background-color .18s ease-in-out;
          left:2px; transform: translateX(0);
        }
        .hotel-toggle.off .hotel-toggle-knob{ background:#cbd5e1; border:1px solid #94a3b8; }
        .hotel-toggle.active .hotel-toggle-knob{ background:#ffffff; transform: translateX(18px); }

        .hotel-page-wrapper { position: relative; z-index: 10; }
        .hotel-card { max-width: none !important; width: 100% !important; overflow: visible; padding-left: 1rem; padding-right: 1rem; }
        @media (min-width: 1024px) { .hotel-card { padding-left: 1.5rem; padding-right: 1.5rem; } }
        .hotel-card-head, .hotel-toolbar { padding-left:.5rem; padding-right:.5rem; max-width:100%; overflow:hidden; }
        .hotel-table-wrap{ padding-left:.25rem; padding-right:.25rem; overflow-x:auto; overflow-y:visible; max-width:100%; }
        .hotel-table{ width:100%; min-width:1100px; table-layout:auto; }
        .hotel-footer{ display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; padding:.75rem .75rem; }
        .hotel-footer > p{ margin:0; }
        .hotel-pagination{ display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; justify-content:flex-start; width:100%; }
        .hotel-pagination button{ min-width:2rem; height:2rem; padding:0 .6rem; border-radius:.75rem; line-height:2rem; white-space:nowrap; }
        .hotel-pagination button.active{ font-weight:600; }

        .hotel-pricebook { position: relative; }
        .hotel-pricebook-btn {
          display:inline-flex; align-items:center; gap:.35rem;
          border-radius:.6rem; padding:.4rem .7rem; font-weight:600;
          background:#1f2937; color:#fff; border:1px solid #111827;
        }
        .hotel-pricebook-btn:hover { background:#111827; }
        .hotel-pricebook-menu {
          position:absolute; right:0; top:calc(100% + 6px);
          background:#ffffff; border:1px solid #e5e7eb; border-radius:.6rem;
          box-shadow:0 10px 30px rgba(0,0,0,.12);
          min-width:260px; z-index:50; padding:.35rem;
        }
        .hotel-pricebook-item {
          width:100%; text-align:left; border:0; background:transparent;
          padding:.6rem .7rem; border-radius:.5rem; font-weight:500; cursor:pointer;
        }
        .hotel-pricebook-item:hover { background:#f3f4f6; }

        .hotel-action-circle.del { 
          color: #ef4444;
          border-color: #fecaca;
          background: #ffffff;
        }
        .hotel-action-circle.del:hover {
          color: #dc2626;
          background: #fee2e2;
          border-color: #fca5a5;
        }
        .hotel-action-circle.edit {
          color: #2563eb;
          border-color: #93c5fd;
          background: #dbeafe;
        }
        .hotel-action-circle.edit:hover {
          color: #2563eb;
          background: #dbeafe;
          border-color: #93c5fd;
        }
        .hotel-copy-btn{
          color:#6366f1;
          background:#ffffff;
          border:1px solid #c7d2fe;
          border-radius:.6rem;
        }
        .hotel-copy-btn:hover{
          color:#4f46e5;
          background:#eef2ff;
          border-color:#a5b4fc;
        }
      `}</style>
    </div>
  );
};

export default HotelPage;
