// REPLACE-WHOLE-FILE: src/pages/drivers/DriversPage.tsx
import React, { useEffect, useMemo, useState } from "react";
import { useNavigate, useLocation } from "react-router-dom";
import {
  Eye,
  Pencil,
  Trash2,
  Copy as CopyIcon,
  FileText,
  Download,
} from "lucide-react";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import * as XLSX from "xlsx";
import {
  Driver,
  listDrivers,
  updateDriverStatus,
  deleteDriver,
} from "@/services/drivers";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

const PAGE_SIZE = 10;

export const DriversPage: React.FC = () => {
  const navigate = useNavigate();
  const location = useLocation();

  const [drivers, setDrivers] = useState<Driver[]>([]);
  const [loading, setLoading] = useState(false);
  const [search, setSearch] = useState("");
  const [currentPage, setCurrentPage] = useState(1);
  const [sortKey, setSortKey] = useState<keyof Driver | "sno">("sno");
  const [sortDir, setSortDir] = useState<"asc" | "desc">("asc");
  const [error, setError] = useState<string | null>(null);

  // Load drivers
  useEffect(() => {
    const load = async () => {
      try {
        setLoading(true);
        setError(null);
        const data = await listDrivers();
        setDrivers(data);
      } catch (err: any) {
        console.error("Failed to load drivers", err);
        setError("Failed to load drivers");
      } finally {
        setLoading(false);
      }
    };
    load();
  }, []);

  // ✅ Dynamic Add Driver navigation:
  // - Remembers current list URL (and list UI state) so your wizard can go "Back" properly later.
  const handleAddDriver = (e?: React.MouseEvent) => {
    const from = `${location.pathname}${location.search}`;

    // Ctrl/Cmd click should open wizard in new tab (nice UX + matches browser behavior)
    const openInNewTab = Boolean(e && (e.ctrlKey || e.metaKey || e.button === 1));
    const url = `/drivers/create?from=${encodeURIComponent(from)}`;

    if (openInNewTab) {
      window.open(url, "_blank");
      return;
    }

    navigate(url, {
      state: {
        from,
        listState: { search, currentPage, sortKey, sortDir },
      },
    });
  };

  // Filter + sort
  const processedDrivers = useMemo(() => {
    let rows = drivers.map((d, index) => ({
      ...d,
      sno: index + 1,
    }));

    if (search.trim()) {
      const q = search.trim().toLowerCase();

      rows = rows.filter((d) => {
        const fields = [d.name, d.mobile, d.licenseNumber, d.licenseStatus].map(
          (v) => (v ?? "").toString().toLowerCase()
        );

        return fields.some((f) => f.includes(q));
      });
    }

    rows.sort((a: any, b: any) => {
      const aVal = a[sortKey];
      const bVal = b[sortKey];
      if (aVal == null && bVal == null) return 0;
      if (aVal == null) return sortDir === "asc" ? -1 : 1;
      if (bVal == null) return sortDir === "asc" ? 1 : -1;

      if (typeof aVal === "number" && typeof bVal === "number") {
        return sortDir === "asc" ? aVal - bVal : bVal - aVal;
      }

      const aStr = String(aVal).toLowerCase();
      const bStr = String(bVal).toLowerCase();
      if (aStr < bStr) return sortDir === "asc" ? -1 : 1;
      if (aStr > bStr) return sortDir === "asc" ? 1 : -1;
      return 0;
    });

    return rows;
  }, [drivers, search, sortKey, sortDir]);

  const totalPages = Math.max(1, Math.ceil(processedDrivers.length / PAGE_SIZE));

  const pageDrivers = useMemo(() => {
    const start = (currentPage - 1) * PAGE_SIZE;
    return processedDrivers.slice(start, start + PAGE_SIZE);
  }, [processedDrivers, currentPage]);

  const toggleSort = (key: keyof Driver | "sno") => {
    if (sortKey === key) {
      setSortDir((d) => (d === "asc" ? "desc" : "asc"));
    } else {
      setSortKey(key);
      setSortDir("asc");
    }
  };

  // ---------- Export helpers ----------

  const getExportRows = () => {
    return processedDrivers.map((d) => ({
      "S.No": d.sno,
      Name: d.name,
      Mobile: d.mobile,
      "License Number": d.licenseNumber,
      "License Status": d.licenseStatus,
      Status: d.status ? "Active" : "Inactive",
    }));
  };

  const handleCopy = async () => {
    const rows = getExportRows();
    const header = Object.keys(rows[0] || {}).join("\t");
    const body = rows.map((r) => Object.values(r).join("\t")).join("\n");
    const text = `${header}\n${body}`;
    await navigator.clipboard.writeText(text);
    alert("Driver list copied to clipboard.");
  };

  const handleExportCSV = () => {
    const rows = getExportRows();
    if (!rows.length) return;
    const header = Object.keys(rows[0]).join(",");
    const body = rows
      .map((r) =>
        Object.values(r)
          .map((v) => `"${String(v).replace(/"/g, '""')}"`)
          .join(",")
      )
      .join("\n");
    const csv = `${header}\n${body}`;
    const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "drivers.csv";
    a.click();
    URL.revokeObjectURL(url);
  };

  const handleExportExcel = () => {
    const rows = getExportRows();
    const ws = XLSX.utils.json_to_sheet(rows);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Drivers");
    XLSX.writeFile(wb, "drivers.xlsx");
  };

  const handleExportPDF = () => {
    const rows = getExportRows();
    const doc = new jsPDF("p", "pt", "a4");
    doc.setFontSize(14);
    doc.text("List of Drivers", 40, 40);

    autoTable(doc, {
      startY: 60,
      head: [Object.keys(rows[0] || {})],
      body: rows.map((r) => Object.values(r)),
    });

    doc.save("drivers.pdf");
  };

  // ---------- Actions ----------

  const handleToggleStatus = async (driver: Driver) => {
    const newStatus = !driver.status;
    // optimistic update
    setDrivers((prev) =>
      prev.map((d) => (d.id === driver.id ? { ...d, status: newStatus } : d))
    );
    try {
      await updateDriverStatus(driver.id, newStatus);
    } catch (err) {
      console.error("Failed to update status", err);
      // revert on error
      setDrivers((prev) =>
        prev.map((d) =>
          d.id === driver.id ? { ...d, status: driver.status } : d
        )
      );
      alert("Failed to update status");
    }
  };

  const handleDelete = async (driver: Driver) => {
    const ok = window.confirm(
      `Are you sure you want to delete driver "${driver.name}"?`
    );
    if (!ok) return;

    try {
      await deleteDriver(driver.id);
      setDrivers((prev) => prev.filter((d) => d.id !== driver.id));
    } catch (err) {
      console.error("Failed to delete driver", err);
      alert("Failed to delete driver");
    }
  };

  // ---------- Render ----------

  return (
    <div className="p-6">
      <div className="mb-4 flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-semibold text-slate-800">
            List of Driver
          </h1>
          <p className="text-sm text-slate-500 mt-1">List of Drivers</p>
        </div>

        <Button
          className="bg-purple-500 hover:bg-purple-600 text-white rounded-full px-5 py-2 text-sm"
          onClick={handleAddDriver}
          title="Add Driver"
        >
          + Add Driver
        </Button>
      </div>

      <div className="rounded-2xl bg-white shadow-sm border border-violet-100 p-4">
        <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
          <div className="flex items-center gap-2">
            <span className="text-sm text-slate-600 whitespace-nowrap">
              Show
            </span>
            <span className="inline-flex items-center justify-center rounded-md border border-slate-200 px-3 py-1 text-sm text-slate-700 bg-slate-50">
              10
            </span>
            <span className="text-sm text-slate-600 whitespace-nowrap">
              entries
            </span>
          </div>

          <div className="flex flex-wrap items-center gap-2">
            <div className="flex items-center gap-2 mr-4">
              <span className="text-sm text-slate-600">Search:</span>
              <Input
                className="h-8 w-48 border-slate-200 focus-visible:ring-purple-400 text-sm"
                value={search}
                onChange={(e) => {
                  setCurrentPage(1);
                  setSearch(e.target.value);
                }}
              />
            </div>

            {/* Export buttons */}
            <Button
              variant="outline"
              className="h-8 px-3 border-purple-500 text-purple-600 text-xs flex items-center gap-1 rounded-md"
              onClick={handleCopy}
            >
              <CopyIcon className="w-3 h-3" />
              Copy
            </Button>
            <Button
              variant="outline"
              className="h-8 px-3 border-green-500 text-green-600 text-xs flex items-center gap-1 rounded-md"
              onClick={handleExportExcel}
            >
              <Download className="w-3 h-3" />
              Excel
            </Button>
            <Button
              variant="outline"
              className="h-8 px-3 border-slate-400 text-slate-600 text-xs flex items-center gap-1 rounded-md"
              onClick={handleExportCSV}
            >
              <FileText className="w-3 h-3" />
              CSV
            </Button>
            <Button
              variant="outline"
              className="h-8 px-3 border-red-500 text-red-500 text-xs flex items-center gap-1 rounded-md"
              onClick={handleExportPDF}
            >
              <FileText className="w-3 h-3" />
              PDF
            </Button>
          </div>
        </div>

        {error && (
          <div className="mb-3 text-sm text-red-600 bg-red-50 px-3 py-2 rounded-md">
            {error}
          </div>
        )}

        <div className="overflow-x-auto">
          <table className="min-w-full text-sm">
            <thead>
              <tr className="text-xs uppercase text-slate-500 border-b border-slate-200">
                <th
                  className="py-2 px-3 text-left cursor-pointer"
                  onClick={() => toggleSort("sno")}
                >
                  S.No
                </th>
                <th className="py-2 px-3 text-left">Actions</th>
                <th
                  className="py-2 px-3 text-left cursor-pointer"
                  onClick={() => toggleSort("name")}
                >
                  Name
                </th>
                <th
                  className="py-2 px-3 text-left cursor-pointer"
                  onClick={() => toggleSort("mobile")}
                >
                  Mobile
                </th>
                <th
                  className="py-2 px-3 text-left cursor-pointer"
                  onClick={() => toggleSort("licenseNumber")}
                >
                  License Number
                </th>
                <th
                  className="py-2 px-3 text-left cursor-pointer"
                  onClick={() => toggleSort("licenseStatus")}
                >
                  License Status
                </th>
                <th
                  className="py-2 px-3 text-left cursor-pointer"
                  onClick={() => toggleSort("status")}
                >
                  Status
                </th>
              </tr>
            </thead>
            <tbody>
              {loading ? (
                <tr>
                  <td
                    colSpan={7}
                    className="py-6 text-center text-slate-500 text-sm"
                  >
                    Loading drivers...
                  </td>
                </tr>
              ) : pageDrivers.length === 0 ? (
                <tr>
                  <td
                    colSpan={7}
                    className="py-6 text-center text-slate-500 text-sm"
                  >
                    No drivers found.
                  </td>
                </tr>
              ) : (
                pageDrivers.map((driver, idx) => (
                  <tr
                    key={driver.id}
                    className="border-b border-slate-100 hover:bg-slate-50/60"
                  >
                    <td className="py-2 px-3 text-slate-700">
                      {(currentPage - 1) * PAGE_SIZE + idx + 1}
                    </td>
                    <td className="py-2 px-3">
                      <div className="flex items-center gap-3">
                        <button
                          type="button"
                          className="text-purple-500 hover:text-purple-600"
                          onClick={() => navigate(`/drivers/${driver.id}`)}
                          title="View"
                        >
                          <Eye className="w-4 h-4" />
                        </button>
                        <button
                          type="button"
                          className="text-indigo-500 hover:text-indigo-600"
                          onClick={() => navigate(`/drivers/${driver.id}/edit`)}
                          title="Edit"
                        >
                          <Pencil className="w-4 h-4" />
                        </button>
                        <button
                          type="button"
                          className="text-red-500 hover:text-red-600"
                          onClick={() => handleDelete(driver)}
                          title="Delete"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                    <td className="py-2 px-3 text-slate-800 font-medium">
                      {driver.name}
                    </td>
                    <td className="py-2 px-3 text-slate-700">{driver.mobile}</td>
                    <td className="py-2 px-3 text-slate-700">
                      {driver.licenseNumber}
                    </td>
                    <td className="py-2 px-3">
                      <span
                        className={`inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium ${
                          driver.licenseStatus === "Active"
                            ? "bg-emerald-50 text-emerald-700"
                            : driver.licenseStatus === "Expires Today"
                            ? "bg-amber-50 text-amber-700"
                            : "bg-slate-100 text-slate-700"
                        }`}
                      >
                        {driver.licenseStatus}
                      </span>
                    </td>
                    <td className="py-2 px-3">
                      <button
                        type="button"
                        onClick={() => handleToggleStatus(driver)}
                        className={`relative inline-flex h-5 w-9 items-center rounded-full transition-colors ${
                          driver.status ? "bg-purple-500" : "bg-slate-300"
                        }`}
                      >
                        <span
                          className={`inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform ${
                            driver.status ? "translate-x-4" : "translate-x-0"
                          }`}
                        />
                      </button>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>

        {/* Footer: showing entries + pagination */}
        <div className="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
          <p className="text-xs text-slate-500">
            Showing{" "}
            {processedDrivers.length === 0
              ? 0
              : (currentPage - 1) * PAGE_SIZE + 1}{" "}
            to {Math.min(currentPage * PAGE_SIZE, processedDrivers.length)} of{" "}
            {processedDrivers.length} entries
          </p>

          <div className="flex items-center gap-1">
            <Button
              variant="outline"
              className="h-7 px-3 text-xs rounded-full"
              disabled={currentPage === 1}
              onClick={() => setCurrentPage((p) => Math.max(1, p - 1))}
            >
              Previous
            </Button>
            {Array.from({ length: totalPages }).map((_, i) => {
              const page = i + 1;
              // compress view if many pages
              if (
                totalPages > 7 &&
                page !== 1 &&
                page !== totalPages &&
                Math.abs(page - currentPage) > 1
              ) {
                if (
                  (page === 2 && currentPage > 3) ||
                  (page === totalPages - 1 &&
                    currentPage < totalPages - 2)
                ) {
                  return (
                    <span key={page} className="px-2 text-xs text-slate-400">
                      ...
                    </span>
                  );
                }
                return null;
              }
              return (
                <button
                  key={page}
                  className={`h-7 min-w-[2rem] px-2 rounded-full text-xs border ${
                    page === currentPage
                      ? "bg-purple-500 text-white border-purple-500"
                      : "bg-white text-slate-700 border-slate-200 hover:bg-slate-50"
                  }`}
                  onClick={() => setCurrentPage(page)}
                >
                  {page}
                </button>
              );
            })}
            <Button
              variant="outline"
              className="h-7 px-3 text-xs rounded-full"
              disabled={currentPage === totalPages}
              onClick={() => setCurrentPage((p) => Math.min(totalPages, p + 1))}
            >
              Next
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default DriversPage;
