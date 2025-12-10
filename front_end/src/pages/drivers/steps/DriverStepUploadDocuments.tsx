// FILE: src/drivers/steps/DriverStepUploadDocuments.tsx
import React, { useEffect, useMemo, useState } from "react";
import { Upload, FileText } from "lucide-react";

import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from "@/components/ui/dialog";

import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

import type { DriverDocument, Id, Option } from "@/services/drivers";
import {
  listDriverDocuments,
  uploadDriverDocument,
} from "@/services/drivers";

function RequiredStar() {
  return <span className="text-red-500"> *</span>;
}

export function DriverStepUploadDocuments({
  driverId,
  documentTypes,
  onBack,
  onSkipContinue,
}: {
  driverId: Id | null;
  documentTypes: Option[];
  onBack: () => void;
  onSkipContinue: () => void;
}) {
  const [open, setOpen] = useState(false);
  const [docType, setDocType] = useState("");
  const [file, setFile] = useState<File | null>(null);
  const [err, setErr] = useState<{ docType?: string; file?: string }>({});
  const [saving, setSaving] = useState(false);

  const [docs, setDocs] = useState<DriverDocument[]>([]);
  const [loading, setLoading] = useState(false);

  const docTypeItems = useMemo(() => documentTypes ?? [], [documentTypes]);

  async function loadDocs() {
    if (!driverId) return;
    setLoading(true);
    try {
      const d = await listDriverDocuments(driverId);
      setDocs(d || []);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    loadDocs();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [driverId]);

  function validateModal() {
    const e: any = {};
    if (!docType) e.docType = "Document Type is required";
    if (!file) e.file = "Upload Document is required";
    setErr(e);
    return Object.keys(e).length === 0;
  }

  async function handleSaveDoc() {
    if (!driverId) {
      setErr({ file: "Please save Basic Info first (create driver) before upload." });
      return;
    }
    if (!validateModal()) return;

    setSaving(true);
    try {
      await uploadDriverDocument(driverId, docType, file!);
      setOpen(false);
      setDocType("");
      setFile(null);
      setErr({});
      await loadDocs();
    } catch (e: any) {
      setErr({ file: e?.message || "Upload failed" });
    } finally {
      setSaving(false);
    }
  }

  return (
    <Card className="border-0 shadow-sm">
      <CardContent className="p-6">
        <div className="border border-dashed rounded-xl p-10 md:p-14 bg-white">
          <div className="flex items-start justify-between">
            <div>
              <div className="text-2xl font-semibold text-gray-800">
                Upload Documents
              </div>
            </div>

            <Button
              type="button"
              className="bg-gradient-to-r from-violet-600 to-pink-500 text-white hover:opacity-95"
              onClick={() => setOpen(true)}
            >
              + Upload
            </Button>
          </div>

          <div className="mt-10 flex items-center justify-center">
            <div className="flex flex-col items-center justify-center text-gray-200">
              <FileText className="h-24 w-24" />
              <Upload className="h-12 w-12 -mt-8" />
            </div>
          </div>

          <div className="mt-8">
            <div className="text-sm text-gray-600 mb-2">
              {loading ? "Loading documents..." : " "}
            </div>

            {docs.length > 0 && (
              <div className="overflow-auto border rounded-lg">
                <table className="min-w-full text-sm">
                  <thead className="bg-gray-50 text-gray-600">
                    <tr>
                      <th className="p-3 text-left">TYPE</th>
                      <th className="p-3 text-left">FILE</th>
                      <th className="p-3 text-left">ACTION</th>
                    </tr>
                  </thead>
                  <tbody>
                    {docs.map((d) => (
                      <tr key={String(d.id)} className="border-t">
                        <td className="p-3">{d.documentType}</td>
                        <td className="p-3">{d.fileName}</td>
                        <td className="p-3">
                          <a
                            className="text-violet-600 hover:underline"
                            href={d.fileUrl}
                            target="_blank"
                            rel="noreferrer"
                          >
                            View
                          </a>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}

            {!loading && docs.length === 0 && (
              <div className="text-sm text-gray-500 mt-2">
                No documents uploaded.
              </div>
            )}
          </div>
        </div>

        <div className="mt-10 flex items-center justify-between">
          <Button
            type="button"
            variant="secondary"
            className="h-11 px-10 bg-gray-300 text-white hover:bg-gray-400"
            onClick={onBack}
          >
            Back
          </Button>

          <Button
            type="button"
            className="h-11 px-10 bg-gradient-to-r from-violet-600 to-pink-500 text-white hover:opacity-95"
            onClick={onSkipContinue}
          >
            Skip &amp; Continue
          </Button>
        </div>

        {/* Modal (matches screenshot) */}
        <Dialog open={open} onOpenChange={setOpen}>
          <DialogContent className="sm:max-w-[520px]">
            <DialogHeader>
              <DialogTitle className="text-center text-2xl">
                Document Upload
              </DialogTitle>
            </DialogHeader>

            <div className="space-y-5 pt-2">
              <div>
                <Label className="text-sm text-gray-700">
                  Document Type<RequiredStar />
                </Label>
                <div className="mt-2">
                  <Select value={docType} onValueChange={setDocType}>
                    <SelectTrigger className={err.docType ? "border-red-500" : ""}>
                      <SelectValue placeholder="Choose Document Type" />
                    </SelectTrigger>
                    <SelectContent>
                      {docTypeItems.map((o) => (
                        <SelectItem key={String(o.id)} value={String(o.id)}>
                          {o.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  {err.docType && (
                    <div className="text-xs text-red-600 mt-1">{err.docType}</div>
                  )}
                </div>
              </div>

              <div>
                <Label className="text-sm text-gray-700">
                  Upload Document<RequiredStar />
                </Label>
                <Input
                  type="file"
                  className={["mt-2", err.file ? "border-red-500" : ""].join(" ")}
                  onChange={(e) => setFile(e.target.files?.[0] ?? null)}
                />
                {err.file && (
                  <div className="text-xs text-red-600 mt-1">{err.file}</div>
                )}
              </div>
            </div>

            <DialogFooter className="pt-4">
              <div className="w-full flex items-center justify-center gap-4">
                <Button
                  type="button"
                  variant="outline"
                  className="px-8"
                  onClick={() => setOpen(false)}
                >
                  Close
                </Button>
                <Button
                  type="button"
                  className="px-10 bg-gradient-to-r from-violet-600 to-pink-500 text-white hover:opacity-95"
                  onClick={handleSaveDoc}
                  disabled={saving}
                >
                  Save
                </Button>
              </div>
            </DialogFooter>
          </DialogContent>
        </Dialog>
      </CardContent>
    </Card>
  );
}
