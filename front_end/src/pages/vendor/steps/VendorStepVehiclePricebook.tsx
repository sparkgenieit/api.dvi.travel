// FILE: src/pages/vendor/steps/VendorStepVehiclePricebook.tsx

import React, { useState } from "react";
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
import {
  Dialog,
  DialogContent,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";

type Props = {
  vendorId?: number;
  onBack: () => void;
  onNext: () => void;
};

const gstTypes = [
  { id: "included", label: "Included" },
  { id: "excluded", label: "Excluded" },
];

const gstPercentageOptions = [
  { id: "0", label: "0 % GST - %0" },
  { id: "5", label: "5 % GST - %5" },
  { id: "12", label: "12 % GST - %12" },
  { id: "18", label: "18 % GST - %18" },
];

export const VendorStepVehiclePricebook: React.FC<Props> = ({
  vendorId,
  onBack,
  onNext,
}) => {
  // ----- Vendor margin state (UI only) -----
  const [vendorMarginPercent, setVendorMarginPercent] = useState<string>("5");
  const [vendorMarginGstType, setVendorMarginGstType] =
    useState<string>("included");
  const [vendorMarginGstPercent, setVendorMarginGstPercent] =
    useState<string>("5");

  // ----- Local KM Limit modal -----
  const [localKmOpen, setLocalKmOpen] = useState(false);
  const [localKmForm, setLocalKmForm] = useState({
    vehicleType: "",
    title: "",
    hours: "",
    kmLimit: "",
  });

  // ----- Outstation KM Limit modal -----
  const [outKmOpen, setOutKmOpen] = useState(false);
  const [outKmForm, setOutKmForm] = useState({
    vehicleType: "",
    title: "",
    kmLimit: "",
  });

  const branchLabel = "Branch #1 - Unjhb"; // matches screenshot, can be wired later

  const gradientButton =
    "bg-gradient-to-r from-pink-500 to-purple-500 text-white hover:from-pink-600 hover:to-purple-600";

  const handleLocalKmChange = (
    field: keyof typeof localKmForm,
    value: string
  ) => {
    setLocalKmForm((prev) => ({ ...prev, [field]: value }));
  };

  const handleOutKmChange = (field: keyof typeof outKmForm, value: string) => {
    setOutKmForm((prev) => ({ ...prev, [field]: value }));
  };

  const handleLocalKmSave = () => {
    // TODO: wire to backend
    setLocalKmOpen(false);
  };

  const handleOutKmSave = () => {
    // TODO: wire to backend
    setOutKmOpen(false);
  };

  return (
    <>
      <Card>
        <CardHeader>
          <CardTitle className="text-pink-600">Vehicle Pricebook</CardTitle>
        </CardHeader>
        <CardContent className="space-y-8">
          {!vendorId && (
            <p className="text-sm text-red-500">
              Save Basic Info first before editing pricebook.
            </p>
          )}

          {/* ========== Vendor Margin Details ========== */}
          <section className="rounded-2xl border border-gray-200 bg-white px-6 py-5 shadow-sm">
            <div className="mb-4 flex items-center justify-between">
              <h2 className="text-lg font-semibold text-gray-800">
                Vendor Margin Details
              </h2>
              <Button
                type="button"
                className={`${gradientButton} px-6`}
                disabled={!vendorId}
              >
                Update
              </Button>
            </div>

            <div className="grid gap-4 md:grid-cols-3">
              <div className="space-y-1">
                <Label>Vendor Margin %</Label>
                <Input
                  value={vendorMarginPercent}
                  onChange={(e) => setVendorMarginPercent(e.target.value)}
                />
              </div>

              <div className="space-y-1">
                <Label>Vendor Margin GST Type</Label>
                <Select
                  value={vendorMarginGstType}
                  onValueChange={setVendorMarginGstType}
                >
                  <SelectTrigger>
                    <SelectValue placeholder="Choose GST Type" />
                  </SelectTrigger>
                  <SelectContent>
                    {gstTypes.map((opt) => (
                      <SelectItem key={opt.id} value={opt.id}>
                        {opt.label}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>

              <div className="space-y-1">
                <Label>Vendor Margin GST Percentage</Label>
                <Select
                  value={vendorMarginGstPercent}
                  onValueChange={setVendorMarginGstPercent}
                >
                  <SelectTrigger>
                    <SelectValue placeholder="Choose GST %" />
                  </SelectTrigger>
                  <SelectContent>
                    {gstPercentageOptions.map((opt) => (
                      <SelectItem key={opt.id} value={opt.id}>
                        {opt.label}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
            </div>
          </section>

          {/* ========== Driver Cost Details ========== */}
          <section className="rounded-2xl border border-gray-200 bg-white px-6 py-5 shadow-sm">
            <div className="mb-4 flex items-center justify-between">
              <h2 className="text-lg font-semibold text-gray-800">
                Driver Cost Details
              </h2>
              <Button
                type="button"
                className={`${gradientButton} px-6`}
                disabled={!vendorId}
              >
                Update
              </Button>
            </div>

            <div className="overflow-hidden rounded-lg border border-gray-200">
              <table className="min-w-full divide-y divide-gray-200 text-sm">
                <thead className="bg-gray-50 text-xs uppercase text-gray-500">
                  <tr>
                    <th className="px-4 py-3 text-left font-semibold">
                      VEHICLE TYPE
                    </th>
                    <th className="px-4 py-3 text-left font-semibold">
                      DRIVER COST(₹)
                    </th>
                    <th className="px-4 py-3 text-left font-semibold">
                      FOOD COST(₹)
                    </th>
                    <th className="px-4 py-3 text-left font-semibold">
                      ACCOMMODATION COST(₹)
                    </th>
                    <th className="px-4 py-3 text-left font-semibold">
                      EXTRA COST(₹)
                    </th>
                    <th className="px-4 py-3 text-left font-semibold">
                      MORNING CHARGE(₹)
                    </th>
                    <th className="px-4 py-3 text-left font-semibold">
                      EVENING CHARGE(₹)
                    </th>
                  </tr>
                </thead>
                <tbody className="bg-white">
                  <tr>
                    <td
                      colSpan={7}
                      className="px-4 py-6 text-center text-sm text-gray-500"
                    >
                      No more records found.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>

          {/* ========== Vehicle Extra Cost Details ========== */}
          <section className="rounded-2xl border border-gray-200 bg-white px-6 py-5 shadow-sm">
            <div className="mb-4 flex items-center justify-between">
              <h2 className="text-lg font-semibold text-gray-800">
                Vehicle Extra Cost Details
              </h2>
              <Button
                type="button"
                className={`${gradientButton} px-6`}
                disabled={!vendorId}
              >
                Update
              </Button>
            </div>

            <p className="mb-2 text-sm font-semibold text-pink-600">
              {branchLabel}
            </p>
            <p className="text-sm text-gray-500">
              No vehicles found for this branch.
            </p>
          </section>

          {/* ========== Local Pricebook ========== */}
          <section className="rounded-2xl border border-gray-200 bg-white px-6 py-5 shadow-sm">
            <div className="mb-4 flex items-center justify-between">
              <h2 className="text-lg font-semibold text-gray-800">
                Vehicle Rental Cost Details | Local Pricebook
              </h2>
              <div className="flex items-center gap-3">
                <Button
                  type="button"
                  className="bg-purple-100 px-6 text-sm font-semibold text-purple-700 hover:bg-purple-200"
                  onClick={() => setLocalKmOpen(true)}
                  disabled={!vendorId}
                >
                  + Add KM Limit
                </Button>
                <div className="hidden items-center gap-2 sm:flex">
                  <Input
                    type="date"
                    placeholder="Start Date"
                    className="h-10 w-32 text-xs"
                  />
                  <Input
                    type="date"
                    placeholder="End Date"
                    className="h-10 w-32 text-xs"
                  />
                </div>
                <Button
                  type="button"
                  className={`${gradientButton} px-6`}
                  disabled={!vendorId}
                >
                  Update
                </Button>
              </div>
            </div>

            <p className="mb-2 text-sm font-semibold text-pink-600">
              {branchLabel}
            </p>
            <p className="text-sm text-gray-500">
              No vehicles found for this branch.
            </p>
          </section>

          {/* ========== Outstation Pricebook ========== */}
          <section className="rounded-2xl border border-gray-200 bg-white px-6 py-5 shadow-sm">
            <div className="mb-4 flex items-center justify-between">
              <h2 className="text-lg font-semibold text-gray-800">
                Vehicle Rental Cost Details | Outstation Pricebook
              </h2>
              <div className="flex items-center gap-3">
                <Button
                  type="button"
                  className="bg-purple-100 px-6 text-sm font-semibold text-purple-700 hover:bg-purple-200"
                  onClick={() => setOutKmOpen(true)}
                  disabled={!vendorId}
                >
                  + Add KM Limit
                </Button>
                <div className="hidden items-center gap-2 sm:flex">
                  <Input
                    type="date"
                    placeholder="Start Date"
                    className="h-10 w-32 text-xs"
                  />
                  <Input
                    type="date"
                    placeholder="End Date"
                    className="h-10 w-32 text-xs"
                  />
                </div>
                <Button
                  type="button"
                  className={`${gradientButton} px-6`}
                  disabled={!vendorId}
                >
                  Update
                </Button>
              </div>
            </div>

            <p className="mb-2 text-sm font-semibold text-pink-600">
              {branchLabel}
            </p>
            <p className="text-sm text-gray-500">
              No vehicles found for this branch.
            </p>
          </section>

          {/* Wizard navigation */}
          <div className="mt-4 flex justify-between">
            <Button variant="outline" type="button" onClick={onBack}>
              Back
            </Button>
            <Button
              type="button"
              onClick={onNext}
              disabled={!vendorId}
              className={gradientButton}
            >
              Skip &amp; Continue
            </Button>
          </div>
        </CardContent>
      </Card>

      {/* ========== Local KM Limit Modal ========== */}
      <Dialog open={localKmOpen} onOpenChange={setLocalKmOpen}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>Add Local KM Limit</DialogTitle>
          </DialogHeader>

          <div className="mt-2 space-y-4">
            <div className="space-y-1">
              <Label>
                Vehicle type <span className="text-red-500">*</span>
              </Label>
              <Select
                value={localKmForm.vehicleType}
                onValueChange={(v) => handleLocalKmChange("vehicleType", v)}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Choose Vehicle Type" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="sedan">Sedan</SelectItem>
                  <SelectItem value="suv">SUV</SelectItem>
                  <SelectItem value="tempo_traveller">Tempo Traveller</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-1">
              <Label>
                Title <span className="text-red-500">*</span>
              </Label>
              <Input
                placeholder="Enter Title"
                value={localKmForm.title}
                onChange={(e) => handleLocalKmChange("title", e.target.value)}
              />
            </div>

            <div className="grid gap-4 md:grid-cols-2">
              <div className="space-y-1">
                <Label>
                  Hours <span className="text-red-500">*</span>
                </Label>
                <Input
                  placeholder="Enter Hours"
                  value={localKmForm.hours}
                  onChange={(e) =>
                    handleLocalKmChange("hours", e.target.value)
                  }
                />
              </div>
              <div className="space-y-1">
                <Label>
                  Kilometer(KM) <span className="text-red-500">*</span>
                </Label>
                <Input
                  placeholder="KM Limit"
                  value={localKmForm.kmLimit}
                  onChange={(e) =>
                    handleLocalKmChange("kmLimit", e.target.value)
                  }
                />
              </div>
            </div>
          </div>

          <DialogFooter className="mt-4 flex justify-between">
            <Button
              type="button"
              variant="outline"
              className="px-6"
              onClick={() => setLocalKmOpen(false)}
            >
              Cancel
            </Button>
            <Button
              type="button"
              className={`${gradientButton} px-8`}
              onClick={handleLocalKmSave}
              disabled={!vendorId}
            >
              Save
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* ========== Outstation KM Limit Modal ========== */}
      <Dialog open={outKmOpen} onOpenChange={setOutKmOpen}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>Update Outstation KM Limit</DialogTitle>
          </DialogHeader>

          <div className="mt-2 space-y-4">
            <div className="space-y-1">
              <Label>
                Vehicle type <span className="text-red-500">*</span>
              </Label>
              <Select
                value={outKmForm.vehicleType}
                onValueChange={(v) => handleOutKmChange("vehicleType", v)}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Choose Vehicle Type" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="sedan">Sedan</SelectItem>
                  <SelectItem value="suv">SUV</SelectItem>
                  <SelectItem value="tempo_traveller">Tempo Traveller</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-1">
              <Label>
                Outstation KM Limit Title{" "}
                <span className="text-red-500">*</span>
              </Label>
              <Input
                placeholder="Outstation KM Limit Title"
                value={outKmForm.title}
                onChange={(e) => handleOutKmChange("title", e.target.value)}
              />
            </div>

            <div className="space-y-1">
              <Label>
                Outstation KM Limit <span className="text-red-500">*</span>
              </Label>
              <Input
                placeholder="Outstation KM Limit"
                value={outKmForm.kmLimit}
                onChange={(e) =>
                  handleOutKmChange("kmLimit", e.target.value)
                }
              />
            </div>
          </div>

          <DialogFooter className="mt-4 flex justify-between">
            <Button
              type="button"
              variant="outline"
              className="px-6"
              onClick={() => setOutKmOpen(false)}
            >
              Cancel
            </Button>
            <Button
              type="button"
              className={`${gradientButton} px-8`}
              onClick={handleOutKmSave}
              disabled={!vendorId}
            >
              Save
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
};
