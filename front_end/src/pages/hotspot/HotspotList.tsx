// FILE: src/pages/hotspots/HotspotList.tsx
// REPLACE-WHOLE-FILE

import { useEffect, useMemo, useState } from "react";
import { useNavigate } from "react-router-dom";
import {
  Eye, Pencil, Trash2, Plus, Upload,
  Copy as CopyIcon, FileSpreadsheet, FileText
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from "@/components/ui/table";
import {
  Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from "@/components/ui/select";
import { DeleteModal } from "@/components/hotspot/DeleteModal";
import { hotspotService, HotspotListItem } from "@/services/hotspotService";
import { toast } from "sonner";

// ----- helpers to build export payloads -----
function to2D(rows: HotspotListItem[]) {
  const headers = [
    "S.NO", "HOTSPOT NAME", "HOTSPOT PRIORITY", "HOTSPOT PLACE (first 3)",
    "LOCAL (Adult/Child/Infant)", "FOREIGN (Adult/Child/Infant)"
  ];
  const data = rows.map((r, i) => [
    String(i + 1),
    r.name,
    String(r.priority ?? ""),
    r.places.slice(0, 3).join(" | "),
    // strip html tags from the HTML snippets
    (r.localHtml || "").replace(/<[^>]*>/g, " ").replace(/\s+/g, " ").trim(),
    (r.foreignHtml || "").replace(/<[^>]*>/g, " ").replace(/\s+/g, " ").trim(),
  ]);
  return { headers, data };
}
function toCSV({ headers, data }: { headers: string[]; data: string[][] }) {
  const esc = (v: string) =>
    /[",\n]/.test(v) ? `"${v.replace(/"/g, '""')}"` : v;
  return [headers.map(esc).join(","), ...data.map(row => row.map(esc).join(","))].join("\n");
}
function toHTMLTable({ headers, data }: { headers: string[]; data: string[][] }) {
  const th = headers.map(h => `<th style="background:#f3f4f6;border:1px solid #e5e7eb;padding:6px 8px;text-align:left;">${h}</th>`).join("");
  const trs = data.map(row =>
    `<tr>${row.map(v => `<td style="border:1px solid #e5e7eb;padding:6px 8px;">${v}</td>`).join("")}</tr>`
  ).join("");
  return `<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Hotspots</title></head>
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

export default function HotspotList() {
  const navigate = useNavigate();
  const [rows, setRows] = useState<HotspotListItem[]>([]);
  const [filtered, setFiltered] = useState<HotspotListItem[]>([]);
  const [search, setSearch] = useState("");
  const [pageSize, setPageSize] = useState(25);
  const [currentPage, setCurrentPage] = useState(1);
  const [deleteId, setDeleteId] = useState<string | null>(null);

  useEffect(() => { load(); }, []);
  useEffect(() => {
    const q = search.toLowerCase();
    setFiltered(
      rows.filter(r =>
        r.name.toLowerCase().includes(q) ||
        r.places.some(p => p.toLowerCase().includes(q))
      )
    );
    setCurrentPage(1);
  }, [search, rows]);

  async function load() {
    try {
      const data = await hotspotService.listHotspots();
      setRows(data);
      setFiltered(data);
    } catch {
      toast.error("Failed to load hotspots");
    }
  }

  const handleDelete = async () => {
    if (!deleteId) return;
    try {
      await hotspotService.deleteHotspot(deleteId);
      toast.success("Hotspot deleted successfully");
      setDeleteId(null);
      load();
    } catch {
      toast.error("Failed to delete hotspot");
    }
  };

  const handlePriorityChange = async (id: string, priority: number) => {
    try {
      await hotspotService.updatePriority(id, priority);
      await load();
    } catch {
      toast.error("Failed to update priority");
    }
  };

  const paginated = useMemo(
    () => filtered.slice((currentPage - 1) * pageSize, currentPage * pageSize),
    [filtered, currentPage, pageSize]
  );
  const totalPages = Math.ceil(filtered.length / pageSize);

  // ----- export handlers (use full filtered set, not just paginated) -----
  const canExport = filtered.length > 0;
  const dataset = useMemo(() => to2D(filtered), [filtered]);

  const onCopy = async () => {
    if (!canExport) return;
    const csv = toCSV(dataset);
    try {
      await navigator.clipboard.writeText(csv);
      toast.success("Copied table (filtered) to clipboard as CSV");
    } catch {
      toast.error("Copy failed");
    }
  };
  const onCSV = () => {
    if (!canExport) return;
    const csv = toCSV(dataset);
    downloadBlob("hotspots.csv", "text/csv;charset=utf-8;", csv);
  };
  const onExcel = () => {
    if (!canExport) return;
    const html = toHTMLTable(dataset);
    // .xls with HTML table is Excel-compatible without extra deps
    downloadBlob("hotspots.xls", "application/vnd.ms-excel", html);
  };

  return (
    <div className="p-6 space-y-6">
      {/* Header with title + soft lavender buttons */}
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-primary">List of Hotspot</h1>

        <div className="flex items-center gap-3">
          <button
            type="button"
            className="inline-flex items-center rounded-md px-4 py-2 text-sm font-semibold
                       bg-violet-50 text-violet-700 hover:bg-violet-100 border border-transparent
                       transition-colors"
            onClick={() => navigate("/hotspots/new")}
          >
            <Plus className="mr-2 h-4 w-4" />
            Add Hotspot
          </button>

          <button
            type="button"
            className="inline-flex items-center rounded-md px-4 py-2 text-sm font-semibold
                       bg-violet-50 text-violet-700 hover:bg-violet-100 border border-transparent
                       transition-colors"
            onClick={() => navigate("/parking-charge-bulk-import")}
          >
            <Upload className="mr-2 h-4 w-4" />
            Parking charges
          </button>
        </div>
      </div>

      <div className="bg-white rounded-lg border p-4 space-y-4">
        {/* Top toolbar */}
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-2">
            <span className="text-sm">Show</span>
            <Select value={String(pageSize)} onValueChange={(v) => setPageSize(Number(v))}>
              <SelectTrigger className="w-20"><SelectValue /></SelectTrigger>
              <SelectContent>
                <SelectItem value="5">5</SelectItem>
                <SelectItem value="10">10</SelectItem>
                <SelectItem value="25">25</SelectItem>
                <SelectItem value="50">50</SelectItem>
              </SelectContent>
            </Select>
            <span className="text-sm">entries</span>
          </div>

          <div className="flex items-center gap-3">
            <div className="flex items-center gap-2">
              <span className="text-sm">Search:</span>
              <Input className="w-64" value={search} onChange={(e) => setSearch(e.target.value)} />
            </div>

            {/* Export buttons (icons + dynamic enable) */}
            <button
              type="button"
              aria-label="Copy"
              className={`inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold
                         border ${canExport ? "border-violet-300 text-violet-700 hover:bg-violet-50"
                                             : "border-gray-200 text-gray-300 cursor-not-allowed"}`}
              onClick={onCopy}
              disabled={!canExport}
            >
              <CopyIcon className="mr-2 h-4 w-4" />
              Copy
            </button>

            <button
              type="button"
              aria-label="Excel"
              className={`inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold
                         ${canExport ? "bg-emerald-500 text-white hover:bg-emerald-600"
                                     : "bg-emerald-200 text-white cursor-not-allowed"}`}
              onClick={onExcel}
              disabled={!canExport}
            >
              <FileSpreadsheet className="mr-2 h-4 w-4" />
              Excel
            </button>

            <button
              type="button"
              aria-label="CSV"
              className={`inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold
                         ${canExport ? "bg-gray-200 text-gray-700 hover:bg-gray-300"
                                     : "bg-gray-200 text-gray-400 cursor-not-allowed"}`}
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
              <TableHead>HOTSPOT IMAGE</TableHead>
              <TableHead>HOTSPOT NAME</TableHead>
              <TableHead>HOTSPOT PRIORITY</TableHead>
              <TableHead>HOTSPOT PLACE</TableHead>
              <TableHead>LOCAL PERSON</TableHead>
              <TableHead>FOREIGN PERSON</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {paginated.map((r, index) => (
              <TableRow key={r.id}>
                <TableCell>{(currentPage - 1) * pageSize + index + 1}</TableCell>
                <TableCell>
                  <div className="flex gap-1">
                    <Button size="sm" variant="ghost" onClick={() => navigate(`/hotspots/${r.id}/preview`)}>
                      <Eye className="h-4 w-4" />
                    </Button>
                    <Button size="sm" variant="ghost" onClick={() => navigate(`/hotspots/${r.id}/edit`)}>
                      <Pencil className="h-4 w-4" />
                    </Button>
                    <Button size="sm" variant="ghost" onClick={() => setDeleteId(r.id)}>
                      <Trash2 className="h-4 w-4 text-red-600" />
                    </Button>
                  </div>
                </TableCell>
                <TableCell>
                  <img src={r.imageUrl || "/placeholder.svg"} alt={r.name} className="h-12 w-16 object-cover rounded" />
                </TableCell>
                <TableCell>{r.name}</TableCell>
                <TableCell>
                  <Input
                    type="number"
                    value={r.priority}
                    onChange={(e) => handlePriorityChange(r.id, Number(e.target.value))}
                    className="w-20"
                  />
                </TableCell>
                <TableCell>
                  <div className="text-sm space-y-1">
                    {r.places.slice(0, 3).map((p, i) => <div key={i}>{p}</div>)}
                  </div>
                </TableCell>
                <TableCell>
                  <div className="text-sm" dangerouslySetInnerHTML={{ __html: r.localHtml }} />
                </TableCell>
                <TableCell>
                  <div className="text-sm" dangerouslySetInnerHTML={{ __html: r.foreignHtml }} />
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>

        {/* Pagination */}
        <div className="flex items-center justify-between">
          <div className="text-sm text-muted-foreground">
            Showing {(currentPage - 1) * pageSize + 1} to {Math.min(currentPage * pageSize, filtered.length)} of {filtered.length} entries
          </div>
          <div className="flex gap-1">
            <Button size="sm" variant="outline" disabled={currentPage === 1} onClick={() => setCurrentPage(currentPage - 1)}>
              Previous
            </Button>
            {Array.from({ length: Math.min(5, totalPages) }, (_, i) => (
              <Button key={i + 1} size="sm" variant={currentPage === i + 1 ? "default" : "outline"} onClick={() => setCurrentPage(i + 1)}>
                {i + 1}
              </Button>
            ))}
            {totalPages > 5 && <span className="px-2">...</span>}
            <Button size="sm" variant="outline" disabled={currentPage === totalPages} onClick={() => setCurrentPage(currentPage + 1)}>
              Next
            </Button>
          </div>
        </div>
      </div>

      <DeleteModal open={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} />
    </div>
  );
}
