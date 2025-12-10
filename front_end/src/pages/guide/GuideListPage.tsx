// FILE: src/pages/guide/GuideListPage.tsx

import { useEffect, useMemo, useState, useCallback } from "react";
import { useNavigate } from "react-router-dom";
import {
  Eye,
  Pencil,
  Trash2,
  Plus,
  Copy as CopyIcon,
  FileSpreadsheet,
  FileText,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Switch } from "@/components/ui/switch";
import { toast } from "sonner";
import { GuideAPI } from "@/services/guideService";
import type { GuideListRow } from "@/types/guide";

/* CSV/Excel helpers */
function to2D(rows: GuideListRow[]) {
  const headers = ["S.NO", "GUIDE NAME", "MOBILE NUMBER", "EMAIL ID", "STATUS"];
  const data = rows.map((r, i) => [
    String(i + 1),
    r.name,
    r.mobileNumber,
    r.email,
    r.status === 1 ? "Active" : "Inactive",
  ]);
  return { headers, data };
}

function toCSV({ headers, data }: { headers: string[]; data: string[][] }) {
  const esc = (v: string) => (/[",\n]/.test(v) ? `"${v.replace(/"/g, '""')}"` : v);
  return [headers.map(esc).join(","), ...data.map((row) => row.map(esc).join(","))].join("\n");
}

function toHTMLTable({ headers, data }: { headers: string[]; data: string[][] }) {
  const th = headers
    .map(
      (h) =>
        `<th style="background:#f3f4f6;border:1px solid #e5e7eb;padding:6px 8px;text-align:left;">${h}</th>`
    )
    .join("");
  const trs = data
    .map(
      (row) =>
        `<tr>${row
          .map((v) => `<td style="border:1px solid #e5e7eb;padding:6px 8px;">${v}</td>`)
          .join("")}</tr>`
    )
    .join("");
  return `<!DOCTYPE html><html><head><meta charset="utf-8"><title>Guides</title></head>
<body>
<table style="border-collapse:collapse;font-family:Arial,Helvetica,sans-serif;font-size:12px;">
<thead><tr>${th}</tr></thead><tbody>${trs}</tbody>
</table>
</body></html>`;
}

function downloadBlob(name: string, mime: string, content: string) {
  const blob = new Blob([content], { type: mime });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = name;
  document.body.appendChild(a);
  a.click();
  a.remove();
  URL.revokeObjectURL(url);
}

export default function GuideListPage() {
  const navigate = useNavigate();

  const [rows, setRows] = useState<GuideListRow[]>([]);
  const [filtered, setFiltered] = useState<GuideListRow[]>([]);
  const [search, setSearch] = useState("");
  const [pageSize, setPageSize] = useState(10);
  const [currentPage, setCurrentPage] = useState(1);
  const [loading, setLoading] = useState(false);
  const [busyIds, setBusyIds] = useState<Set<number>>(new Set());
  const [deletingId, setDeletingId] = useState<number | null>(null);

  const load = useCallback(async () => {
    try {
      setLoading(true);
      const data = await GuideAPI.list();
      setRows(data);
      setFiltered(data);
    } catch {
      toast.error("Failed to load guides");
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void load();
  }, [load]);

  useEffect(() => {
    const q = search.toLowerCase().trim();
    setFiltered(
      rows.filter(
        (r) =>
          r.name.toLowerCase().includes(q) ||
          r.mobileNumber.toLowerCase().includes(q) ||
          r.email.toLowerCase().includes(q)
      )
    );
    setCurrentPage(1);
  }, [search, rows]);

  async function toggleStatus(row: GuideListRow, nextOn: boolean) {
    const id = row.id;
    const nextVal: 0 | 1 = nextOn ? 1 : 0;

    setRows((prev) => prev.map((r) => (r.id === id ? { ...r, status: nextVal } : r)));
    setFiltered((prev) => prev.map((r) => (r.id === id ? { ...r, status: nextVal } : r)));
    setBusyIds((s) => new Set(s).add(id));

    try {
      await GuideAPI.toggleStatus(id, nextVal);
      toast.success(`Status ${nextOn ? "enabled" : "disabled"}`);
    } catch {
      setRows((prev) => prev.map((r) => (r.id === id ? { ...r, status: nextOn ? 0 : 1 } : r)));
      setFiltered((prev) => prev.map((r) => (r.id === id ? { ...r, status: nextOn ? 0 : 1 } : r)));
      toast.error("Failed to update status");
    } finally {
      setBusyIds((s) => {
        const n = new Set(s);
        n.delete(id);
        return n;
      });
    }
  }

  const handleDelete = useCallback(
    async (id: number) => {
      if (deletingId) return;
      const ok = window.confirm("Delete this guide? This action cannot be undone.");
      if (!ok) return;

      setDeletingId(id);

      const prevRows = rows;
      const prevFiltered = filtered;

      setRows((r) => r.filter((x) => x.id !== id));
      setFiltered((r) => r.filter((x) => x.id !== id));

      try {
        await GuideAPI.delete(id);
        toast.success("Guide deleted");
        const totalAfter = prevFiltered.filter((x) => x.id !== id).length;
        const lastPage = Math.max(1, Math.ceil(totalAfter / pageSize));
        if (currentPage > lastPage) setCurrentPage(lastPage);
      } catch {
        setRows(prevRows);
        setFiltered(prevFiltered);
        toast.error("Failed to delete guide");
      } finally {
        setDeletingId(null);
      }
    },
    [rows, filtered, pageSize, currentPage, deletingId]
  );

  const paginated = useMemo(
    () => filtered.slice((currentPage - 1) * pageSize, currentPage * pageSize),
    [filtered, currentPage, pageSize]
  );
  const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));

  const canExport = filtered.length > 0;
  const dataset = useMemo(() => to2D(filtered), [filtered]);

  const onCopy = async () => {
    if (!canExport) return;
    try {
      await navigator.clipboard.writeText(toCSV(dataset));
      toast.success("Copied table as CSV");
    } catch {
      toast.error("Copy failed");
    }
  };

  const onCSV = () => {
    if (!canExport) return;
    downloadBlob("guides.csv", "text/csv;charset=utf-8;", toCSV(dataset));
  };

  const onExcel = () => {
    if (!canExport) return;
    downloadBlob("guides.xls", "application/vnd.ms-excel", toHTMLTable(dataset));
  };

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-primary">Guide</h1>
        <div className="text-sm text-muted-foreground">
          Dashboard &gt; Guide
        </div>
      </div>

      {/* Card */}
      <div className="bg-white rounded-lg border shadow-sm p-6 space-y-4">
        {/* Title + Add Button */}
        <div className="flex items-center justify-between">
          <h2 className="text-lg font-semibold">List of Guide</h2>
          <Button
            onClick={() => navigate("/guide/add")}
            className="bg-gradient-to-r from-primary to-pink-500 hover:from-primary/90 hover:to-pink-500/90"
          >
            <Plus className="mr-2 h-4 w-4" />
            Add Guide
          </Button>
        </div>

        {/* Toolbar */}
        <div className="flex items-center justify-between flex-wrap gap-4">
          <div className="flex items-center gap-2">
            <span className="text-sm">Show</span>
            <Select value={String(pageSize)} onValueChange={(v) => setPageSize(Number(v))}>
              <SelectTrigger className="w-20">
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="10">10</SelectItem>
                <SelectItem value="25">25</SelectItem>
                <SelectItem value="50">50</SelectItem>
              </SelectContent>
            </Select>
            <span className="text-sm">entries</span>
          </div>

          <div className="flex items-center gap-3 flex-wrap">
            <div className="flex items-center gap-2">
              <span className="text-sm">Search:</span>
              <Input
                className="w-64"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
            </div>

            <button
              type="button"
              className={`inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold border ${
                canExport
                  ? "border-violet-300 text-violet-700 hover:bg-violet-50"
                  : "border-gray-200 text-gray-300 cursor-not-allowed"
              }`}
              onClick={onCopy}
              disabled={!canExport}
            >
              <CopyIcon className="mr-2 h-4 w-4" />
              Copy
            </button>

            <button
              type="button"
              className={`inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold ${
                canExport
                  ? "bg-emerald-500 text-white hover:bg-emerald-600"
                  : "bg-emerald-200 text-white cursor-not-allowed"
              }`}
              onClick={onExcel}
              disabled={!canExport}
            >
              <FileSpreadsheet className="mr-2 h-4 w-4" />
              Excel
            </button>

            <button
              type="button"
              className={`inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold ${
                canExport
                  ? "bg-gray-200 text-gray-700 hover:bg-gray-300"
                  : "bg-gray-200 text-gray-400 cursor-not-allowed"
              }`}
              onClick={onCSV}
              disabled={!canExport}
            >
              <FileText className="mr-2 h-4 w-4" />
              CSV
            </button>
          </div>
        </div>

        {/* Table */}
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>S.NO</TableHead>
              <TableHead>ACTION</TableHead>
              <TableHead>GUIDE NAME</TableHead>
              <TableHead>MOBILE NUMBER</TableHead>
              <TableHead>EMAIL ID</TableHead>
              <TableHead className="text-right pr-6">STATUS</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {loading ? (
              <TableRow>
                <TableCell colSpan={6}>Loading…</TableCell>
              </TableRow>
            ) : paginated.length === 0 ? (
              <TableRow>
                <TableCell colSpan={6}>No guides found</TableCell>
              </TableRow>
            ) : (
              paginated.map((r, idx) => {
                const checked = r.status === 1;
                const disabled = busyIds.has(r.id);
                const isDeleting = deletingId === r.id;

                return (
                  <TableRow key={r.id}>
                    <TableCell>{(currentPage - 1) * pageSize + idx + 1}</TableCell>

                    <TableCell>
                      <div className="flex gap-1">
                        <Button
                          size="sm"
                          variant="ghost"
                          onClick={() => navigate(`/guide/${r.id}/preview`)}
                        >
                          <Eye className="h-4 w-4" />
                        </Button>
                        <Button
                          size="sm"
                          variant="ghost"
                          onClick={() => navigate(`/guide/${r.id}/edit`)}
                        >
                          <Pencil className="h-4 w-4" />
                        </Button>
                        <Button
                          size="sm"
                          variant="ghost"
                          disabled={isDeleting}
                          onClick={() => handleDelete(r.id)}
                          title="Delete"
                        >
                          <Trash2
                            className={`h-4 w-4 ${isDeleting ? "opacity-50" : "text-red-600"}`}
                          />
                        </Button>
                      </div>
                    </TableCell>

                    <TableCell className="font-medium">{r.name}</TableCell>
                    <TableCell>{r.mobileNumber}</TableCell>
                    <TableCell>{r.email}</TableCell>

                    <TableCell className="text-right pr-6">
                      <Switch
                        checked={checked}
                        disabled={disabled}
                        onCheckedChange={(next) => toggleStatus(r, next)}
                        className="data-[state=checked]:bg-violet-500 data-[state=unchecked]:bg-violet-300"
                      />
                    </TableCell>
                  </TableRow>
                );
              })
            )}
          </TableBody>
        </Table>

        {/* Pagination */}
        <div className="flex items-center justify-between">
          <div className="text-sm text-muted-foreground">
            {filtered.length > 0 ? (
              <>
                Showing {(currentPage - 1) * pageSize + 1} to{" "}
                {Math.min(currentPage * pageSize, filtered.length)} of {filtered.length} entries
              </>
            ) : (
              "No entries"
            )}
          </div>
          <div className="flex gap-1">
            <Button
              size="sm"
              variant="outline"
              disabled={currentPage === 1}
              onClick={() => setCurrentPage((p) => Math.max(1, p - 1))}
            >
              Previous
            </Button>
            {Array.from({ length: Math.min(5, totalPages) }, (_, i) => (
              <Button
                key={i + 1}
                size="sm"
                variant={currentPage === i + 1 ? "default" : "outline"}
                onClick={() => setCurrentPage(i + 1)}
              >
                {i + 1}
              </Button>
            ))}
            {totalPages > 5 && <span className="px-2">…</span>}
            <Button
              size="sm"
              variant="outline"
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
}
