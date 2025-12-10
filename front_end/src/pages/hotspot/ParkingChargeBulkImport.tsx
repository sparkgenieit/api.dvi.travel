// FILE: src/pages/hotspots/ParkingChargeBulkImport.tsx

import React, { useEffect, useMemo, useRef, useState } from "react";
import { hotspotService } from "@/services/hotspotService";

type TempRow = {
  id: number;
  hotspot_name: string;
  hotspot_location: string;
  vehicle_type_title: string;
  parking_charge: number;
};

function downloadSample() {
  const rows = [
    ["hotspot_name", "hotspot_location", "vehicle_type_title", "parking_charge"],
    ["Marina Beach", "Chennai", "Sedan", "80"],
    ["Brihadeeswarar Temple", "Thanjavur", "SUV", "120"],
  ];
  const csv = rows.map(r => r.map(s => {
    const v = String(s ?? "");
    return /[",\n]/.test(v) ? `"${v.replace(/"/g, '""')}"` : v;
  }).join(",")).join("\n");
  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url; a.download = "parking_charges_sample.csv";
  document.body.appendChild(a); a.click(); a.remove();
  URL.revokeObjectURL(url);
}

const Page: React.FC = () => {
  const [file, setFile] = useState<File | null>(null);
  const fileRef = useRef<HTMLInputElement | null>(null);
  const [busy, setBusy] = useState(false);
  const [sessionId, setSessionId] = useState<string>("");
  const [rows, setRows] = useState<TempRow[]>([]);
  const [selected, setSelected] = useState<Record<number, boolean>>({});

  const selectedIds = useMemo(
    () => Object.entries(selected).filter(([, v]) => v).map(([k]) => Number(k)),
    [selected]
  );
  const allChecked = useMemo(
    () => rows.length > 0 && rows.every(r => selected[r.id]),
    [rows, selected]
  );
  const someChecked = useMemo(
    () => rows.some(r => selected[r.id]),
    [rows, selected]
  );

  const refreshTemplist = async (id = sessionId) => {
    if (!id) return;
    const res = await hotspotService.getParkingTempList(id);
    setRows(res.rows || []);
    const next: Record<number, boolean> = {};
    (res.rows || []).forEach(r => (next[r.id] = true));
    setSelected(next);
  };

  const onUpload = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!file) return alert("Choose a CSV first");
    setBusy(true);
    try {
      const out = await hotspotService.uploadParkingCsv(file);
      setSessionId(out.sessionId);
      await refreshTemplist(out.sessionId);
      alert(`Uploaded. Staged ${out.stagedCount} row(s).`);
    } catch (err: any) {
      alert(err?.message || "Upload failed");
    } finally {
      setBusy(false);
      if (fileRef.current) fileRef.current.value = "";
      setFile(null);
    }
  };

  const onConfirm = async () => {
    if (!sessionId) return alert("Upload first");
    if (selectedIds.length === 0) return alert("Select at least one row");
    setBusy(true);
    try {
      const res = await hotspotService.confirmParkingImport(sessionId, selectedIds);
      alert(`Imported ${res.imported}/${res.total}. Failed: ${res.failed}.`);
      await refreshTemplist();
    } catch (e: any) {
      alert(e?.message || "Confirm failed");
    } finally {
      setBusy(false);
    }
  };

  useEffect(() => {
    if (sessionId) refreshTemplist(sessionId);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [sessionId]);

  return (
    <div className="px-8 py-6">
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-xl font-semibold text-[#5e3a82]">Vehicle Parking Charge Bulk import</h1>
        <nav className="text-xs text-gray-500 space-x-1">
          <span>Dashboard</span><span>&gt;</span><span>Hotspot Parking Charge</span><span>&gt;</span>
          <span className="text-primary">Vehicle Parking Charge Bulk import</span>
        </nav>
      </div>

      <div className="bg-white rounded-2xl shadow-sm border border-[#f0dafb]">
        <div className="px-8 py-10">
          <form onSubmit={onUpload} className="flex flex-col items-center">
            <div className="w-full border-2 border-dashed border-[#e5c7ff] rounded-2xl py-16 flex flex-col items-center justify-center mb-10 bg-[#fff9ff]">
              <div className="flex flex-col items-center gap-4">
                <div className="h-20 w-20 rounded-full border border-[#f0dafb] flex items-center justify-center">
                  <span className="text-4xl opacity-30">📄</span>
                </div>
                <div className="flex flex-col items-center gap-4">
                  <label className="inline-flex items-center justify-center px-6 py-2 rounded-full border border-gray-300 bg-white text-sm cursor-pointer shadow-sm hover:shadow-md transition">
                    <span>{file ? file.name : "Choose File"}</span>
                    <input type="file" accept=".csv,text/csv" className="hidden" onChange={(e) => setFile(e.target.files?.[0] ?? null)} ref={fileRef} disabled={busy}/>
                  </label>
                  <button type="submit" disabled={!file || busy} className="px-8 py-2 rounded-full text-sm font-medium text-white bg-gradient-to-r from-primary to-pink-500 shadow hover:shadow-md transition disabled:opacity-60">
                    {busy ? "Uploading..." : "Upload"}
                  </button>
                </div>
              </div>
            </div>

            <div className="flex flex-col items-center gap-2 mb-8">
              <button type="button" onClick={downloadSample} className="text-sm text-primary underline-offset-2 hover:underline">
                Download Sample CSV
              </button>
              <p className="text-[11px] text-gray-500"><span className="text-xs">ℹ️</span> Only CSV files are supported.</p>
            </div>

            <div className="w-full">
              <div className="flex items-center justify-between mb-3">
                <div className="text-sm text-gray-600">
                  {sessionId ? <>Session: <span className="font-mono">{sessionId}</span> • {rows.length} staged</> : "Upload a CSV to start staging rows"}
                </div>
                <div className="flex items-center gap-2">
                  <button type="button" onClick={() => refreshTemplist()} disabled={!sessionId || busy} className="px-4 py-2 rounded-full text-sm font-medium border border-gray-300 bg-white hover:bg-gray-50 transition disabled:opacity-60">
                    Refresh
                  </button>
                  <button type="button" onClick={onConfirm} disabled={!sessionId || busy || selectedIds.length === 0} className="px-4 py-2 rounded-full text-sm font-medium text-white bg-gradient-to-r from-primary to-pink-500 shadow hover:shadow-md transition disabled:opacity-60">
                    Confirm Import ({selectedIds.length})
                  </button>
                </div>
              </div>

              <div className="overflow-x-auto rounded-xl border border-[#f0dafb]">
                <table className="min-w-full divide-y divide-gray-200 text-sm">
                  <thead className="bg-gray-50">
                    <tr>
                      <th className="px-4 py-2 text-left">
                        <label className="inline-flex items-center gap-2 cursor-pointer select-none">
                          <input
                            type="checkbox"
                            checked={allChecked}
                            ref={(el) => { if (el) el.indeterminate = !allChecked && someChecked; }}
                            onChange={() => {
                              const next: Record<number, boolean> = {};
                              const v = !allChecked; rows.forEach(r => (next[r.id] = v)); setSelected(next);
                            }}
                          />
                          <span>Select</span>
                        </label>
                      </th>
                      <th className="px-4 py-2 text-left">Hotspot</th>
                      <th className="px-4 py-2 text-left">Location (token)</th>
                      <th className="px-4 py-2 text-left">Vehicle Type</th>
                      <th className="px-4 py-2 text-right">Parking Charge</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-gray-100">
                    {rows.length === 0 ? (
                      <tr><td className="px-4 py-8 text-center text-gray-500" colSpan={5}>No staged rows.</td></tr>
                    ) : (
                      rows.map((r) => (
                        <tr key={r.id} className="hover:bg-gray-50">
                          <td className="px-4 py-2">
                            <input type="checkbox" checked={!!selected[r.id]} onChange={(e) => setSelected(p => ({ ...p, [r.id]: e.target.checked }))}/>
                          </td>
                          <td className="px-4 py-2 font-medium">{r.hotspot_name}</td>
                          <td className="px-4 py-2">{r.hotspot_location}</td>
                          <td className="px-4 py-2">{r.vehicle_type_title}</td>
                          <td className="px-4 py-2 text-right">{r.parking_charge}</td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default Page;
