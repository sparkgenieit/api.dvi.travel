// FILE: src/pages/vendor/steps/VendorStepPermitCost.tsx

import React, { useMemo, useState } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

type Props = {
  vendorId?: number;
  onBack: () => void;
  onFinish: () => void;
};

type PermitRow = {
  id: number;
  vehicleType: string;
  sourceState: string;
};

const VEHICLE_TYPES = [
  { id: "sedan", label: "Sedan" },
  { id: "suv", label: "SUV" },
  { id: "tempo_traveller", label: "Tempo Traveller" },
];

const STATES = [
  { id: "tn", label: "Tamil Nadu" },
  { id: "ka", label: "Karnataka" },
  { id: "ap", label: "Andhra Pradesh" },
];

const gradientButton =
  "bg-gradient-to-r from-pink-500 to-purple-500 text-white hover:from-pink-600 hover:to-purple-600";

export const VendorStepPermitCost: React.FC<Props> = ({
  vendorId,
  onBack,
  onFinish,
}) => {
  /** ---------- TABLE + FILTER STATE (UI only for now) ---------- */
  const [rows] = useState<PermitRow[]>([]);
  const [entriesPerPage] = useState(10);
  const [searchText, setSearchText] = useState("");
  const [isAddMode, setIsAddMode] = useState(false);

  /** ---------- ADD FORM STATE ---------- */
  const [permitForm, setPermitForm] = useState({
    vehicleType: "",
    state: "",
  });

  const handleFormChange = (
    field: keyof typeof permitForm,
    value: string
  ): void => {
    setPermitForm((prev) => ({ ...prev, [field]: value }));
  };

  const filteredRows = useMemo(() => {
    if (!searchText.trim()) return rows;
    const q = searchText.toLowerCase();
    return rows.filter(
      (r) =>
        r.vehicleType.toLowerCase().includes(q) ||
        r.sourceState.toLowerCase().includes(q)
    );
  }, [rows, searchText]);

  const handleSavePermit = () => {
    // TODO: wire to backend later
    setIsAddMode(false);
  };

  /** ---------- LIST VIEW (matches PHP “Permit Details” screen) ---------- */
  const renderListView = () => (
    <>
      <h2 className="mb-4 text-lg font-semibold text-pink-600">
        Permit Details
      </h2>

      <div className="mb-4 flex flex-col items-stretch justify-between gap-3 sm:flex-row sm:items-center">
        <div className="flex items-center gap-2 text-sm">
          <span>Show</span>
          <select
            className="rounded-md border border-gray-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500"
            value={entriesPerPage}
            onChange={() => {
              /* static UI only */
            }}
          >
            <option value={10}>10</option>
            <option value={25}>25</option>
            <option value={50}>50</option>
          </select>
          <span>entries</span>
        </div>

        <div className="flex items-center gap-4">
          <Button
            type="button"
            className="bg-purple-100 px-5 text-sm font-semibold text-purple-700 hover:bg-purple-200"
            onClick={() => setIsAddMode(true)}
            disabled={!vendorId}
          >
            + Add Permit Cost
          </Button>

          <div className="flex items-center gap-2 text-sm">
            <span>Search:</span>
            <Input
              className="h-9 w-56 text-sm"
              value={searchText}
              onChange={(e) => setSearchText(e.target.value)}
            />
          </div>
        </div>
      </div>

      <div className="overflow-hidden rounded-lg border border-gray-200">
        <table className="min-w-full divide-y divide-gray-200 text-sm">
          <thead className="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
              <th className="px-4 py-3 text-left font-semibold">S.NO</th>
              <th className="px-4 py-3 text-left font-semibold">
                VIEW&amp;EDIT PERMITCOST
              </th>
              <th className="px-4 py-3 text-left font-semibold">
                VEHICLE TYPE
              </th>
              <th className="px-4 py-3 text-left font-semibold">
                SOURCE STATE
              </th>
            </tr>
          </thead>
          <tbody className="bg-white">
            {filteredRows.length === 0 ? (
              <tr>
                <td
                  colSpan={4}
                  className="px-4 py-6 text-center text-sm text-gray-500"
                >
                  No data available in table
                </td>
              </tr>
            ) : (
              filteredRows.map((row, idx) => (
                <tr key={row.id} className="hover:bg-gray-50">
                  <td className="border-b border-gray-100 px-4 py-3">
                    {idx + 1}
                  </td>
                  <td className="border-b border-gray-100 px-4 py-3">
                    {/* view/edit button can be wired later */}
                    <Button
                      type="button"
                      variant="outline"
                      size="sm"
                      className="h-8 px-3 text-xs"
                    >
                      View &amp; Edit
                    </Button>
                  </td>
                  <td className="border-b border-gray-100 px-4 py-3">
                    {row.vehicleType}
                  </td>
                  <td className="border-b border-gray-100 px-4 py-3">
                    {row.sourceState}
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      <div className="mt-3 flex flex-col items-start justify-between gap-3 text-xs text-gray-500 sm:flex-row sm:items-center">
        <span>
          Showing 0 to {filteredRows.length} of {rows.length} entries
        </span>
        <div className="flex gap-2">
          <Button
            type="button"
            variant="outline"
            size="sm"
            className="h-8 px-4 text-xs"
          >
            Previous
          </Button>
          <Button
            type="button"
            variant="outline"
            size="sm"
            className="h-8 px-4 text-xs"
          >
            Next
          </Button>
        </div>
      </div>

      {/* Bottom navigation (Back + Submit) */}
      <div className="mt-8 flex justify-between">
        <Button
          variant="outline"
          type="button"
          className="bg-gray-100 px-8"
          onClick={onBack}
        >
          Back
        </Button>
        <Button
          type="button"
          onClick={onFinish}
          disabled={!vendorId}
          className={`${gradientButton} px-10`}
        >
          Submit
        </Button>
      </div>
    </>
  );

  /** ---------- ADD FORM VIEW (matches PHP “Add Permit Cost”) ---------- */
  const renderAddView = () => (
    <>
      <div className="mb-6 flex items-center justify-between">
        <h2 className="text-lg font-semibold text-pink-600">
          Add Permit Cost
        </h2>
        <Button
          type="button"
          variant="outline"
          className="bg-gray-100 px-6"
          onClick={() => setIsAddMode(false)}
        >
          Back To List
        </Button>
      </div>

      <div className="grid gap-6 md:grid-cols-2">
        <div className="space-y-1">
          <Label>
            Vehicle Type <span className="text-red-500">*</span>
          </Label>
          <Select
            value={permitForm.vehicleType}
            onValueChange={(v) => handleFormChange("vehicleType", v)}
          >
            <SelectTrigger>
              <SelectValue placeholder="Choose Vehicle Type" />
            </SelectTrigger>
            <SelectContent>
              {VEHICLE_TYPES.map((v) => (
                <SelectItem key={v.id} value={v.id}>
                  {v.label}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        <div className="space-y-1">
          <Label>
            State <span className="text-red-500">*</span>
          </Label>
          <Select
            value={permitForm.state}
            onValueChange={(v) => handleFormChange("state", v)}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select Any One" />
            </SelectTrigger>
            <SelectContent>
              {STATES.map((s) => (
                <SelectItem key={s.id} value={s.id}>
                  {s.label}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
      </div>

      <div className="mt-10 flex justify-between">
        <Button
          type="button"
          variant="outline"
          className="bg-gray-100 px-8"
          onClick={() => setIsAddMode(false)}
        >
          Back To List
        </Button>
        <Button
          type="button"
          className={`${gradientButton} px-10`}
          onClick={handleSavePermit}
          disabled={!vendorId}
        >
          Save
        </Button>
      </div>
    </>
  );

  return (
    <Card>
      <CardHeader>
        <CardTitle className="text-pink-600">Permit Cost</CardTitle>
      </CardHeader>
      <CardContent className="space-y-4">
        {!vendorId && (
          <p className="text-sm text-red-500">
            Save Basic Info first before configuring permit cost.
          </p>
        )}

        {!isAddMode ? renderListView() : renderAddView()}
      </CardContent>
    </Card>
  );
};
