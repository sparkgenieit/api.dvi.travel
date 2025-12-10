// FILE: src/drivers/steps/DriverStepBasicInfo.tsx
import React, { useMemo, useRef, useState } from "react";
import { Calendar as CalendarIcon } from "lucide-react";

import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";

import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";
import { Calendar } from "@/components/ui/calendar";

import type { DriverBasicInfo, Option } from "@/services/drivers";

function isoToDate(iso?: string) {
  if (!iso) return undefined;
  const d = new Date(iso);
  return isNaN(d.getTime()) ? undefined : d;
}

function dateToISO(d?: Date) {
  if (!d) return "";
  // keep noon to avoid timezone shifting in UI
  return new Date(d.getFullYear(), d.getMonth(), d.getDate(), 12, 0, 0).toISOString();
}

function RequiredStar() {
  return <span className="text-red-500"> *</span>;
}

function FieldError({ msg }: { msg?: string }) {
  if (!msg) return null;
  return <div className="text-xs text-red-600 mt-1">{msg}</div>;
}

function DatePicker({
  label,
  required,
  valueISO,
  onChangeISO,
  error,
  inputId,
}: {
  label: string;
  required?: boolean;
  valueISO?: string;
  onChangeISO: (iso: string) => void;
  error?: string;
  inputId: string;
}) {
  const selected = isoToDate(valueISO);

  return (
    <div>
      <Label htmlFor={inputId} className="text-sm text-gray-700">
        {label}
        {required ? <RequiredStar /> : null}
      </Label>

      <Popover>
        <PopoverTrigger asChild>
          <button
            id={inputId}
            type="button"
            className={[
              "mt-2 w-full h-11 rounded-md border px-3 flex items-center justify-between",
              error ? "border-red-500" : "border-gray-200",
            ].join(" ")}
          >
            <span className={selected ? "text-gray-900" : "text-gray-400"}>
              {selected
                ? `${selected.getDate().toString().padStart(2, "0")}/${(selected.getMonth() + 1)
                    .toString()
                    .padStart(2, "0")}/${selected.getFullYear()}`
                : label}
            </span>
            <CalendarIcon className="h-5 w-5 text-violet-600" />
          </button>
        </PopoverTrigger>

        <PopoverContent className="w-auto p-2" align="start">
          <Calendar
            mode="single"
            selected={selected}
            onSelect={(d) => onChangeISO(dateToISO(d))}
            initialFocus
          />
        </PopoverContent>
      </Popover>

      <FieldError msg={error} />
    </div>
  );
}

export function DriverStepBasicInfo({
  values,
  onChange,
  vendors,
  vehicleTypes,
  bloodGroups,
  genders,
  onBack,
  onSaveContinue,
  saving,
}: {
  values: DriverBasicInfo;
  onChange: (patch: Partial<DriverBasicInfo>) => void;
  vendors: Option[];
  vehicleTypes: Option[];
  bloodGroups: Option[];
  genders: Option[];
  onBack: () => void;
  onSaveContinue: () => void;
  saving: boolean;
}) {
  const [errors, setErrors] = useState<Record<string, string>>({});

  const refs = useRef<Record<string, HTMLInputElement | HTMLTextAreaElement | null>>({});

  const vendorItems = useMemo(() => vendors ?? [], [vendors]);
  const vehicleTypeItems = useMemo(() => vehicleTypes ?? [], [vehicleTypes]);

  function validate() {
    const e: Record<string, string> = {};
    if (!values.vendorId) e.vendorId = "Choose Vendor is required";
    if (!values.vehicleTypeId) e.vehicleTypeId = "Choose Vehicle Type is required";
    if (!values.driverName?.trim()) e.driverName = "Driver Name is required";
    if (!values.primaryMobile?.trim())
      e.primaryMobile = "Primary Mobile Number is required";
    setErrors(e);

    const firstKey = Object.keys(e)[0];
    if (firstKey) {
      const el = refs.current[firstKey];
      el?.focus?.();
      el?.scrollIntoView?.({ behavior: "smooth", block: "center" });
      return false;
    }
    return true;
  }

  function handleSave() {
    if (!validate()) return;
    onSaveContinue();
  }

  return (
    <Card className="border-0 shadow-sm">
      <CardContent className="p-6">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-6">
          {/* Row 1 */}
          <div>
            <Label className="text-sm text-gray-700">
              Choose Vendor<RequiredStar />
            </Label>
            <div className="mt-2">
              <Select
                value={values.vendorId ? String(values.vendorId) : ""}
                onValueChange={(v) => onChange({ vendorId: v })}
              >
                <SelectTrigger className={errors.vendorId ? "border-red-500" : ""}>
                  <SelectValue placeholder="Choose Vendor" />
                </SelectTrigger>
                <SelectContent>
                  {vendorItems.map((o) => (
                    <SelectItem key={String(o.id)} value={String(o.id)}>
                      {o.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
              <FieldError msg={errors.vendorId} />
            </div>
          </div>

          <div>
            <Label className="text-sm text-gray-700">
              Choose Vehicle Type<RequiredStar />
            </Label>
            <div className="mt-2">
              <Select
                value={values.vehicleTypeId ? String(values.vehicleTypeId) : ""}
                onValueChange={(v) => onChange({ vehicleTypeId: v })}
              >
                <SelectTrigger className={errors.vehicleTypeId ? "border-red-500" : ""}>
                  <SelectValue placeholder="Choose Vehicle Type" />
                </SelectTrigger>
                <SelectContent>
                  {vehicleTypeItems.map((o) => (
                    <SelectItem key={String(o.id)} value={String(o.id)}>
                      {o.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
              <FieldError msg={errors.vehicleTypeId} />
            </div>
          </div>

          <div>
            <Label className="text-sm text-gray-700" htmlFor="driverName">
              Driver Name<RequiredStar />
            </Label>
            <Input
              id="driverName"
              className={["mt-2 h-11", errors.driverName ? "border-red-500" : ""].join(" ")}
              placeholder="Driver Name"
              value={values.driverName || ""}
              onChange={(e) => onChange({ driverName: e.target.value })}
              ref={(el) => (refs.current.driverName = el)}
            />
            <FieldError msg={errors.driverName} />
          </div>

          {/* Row 2 */}
          <div>
            <Label className="text-sm text-gray-700" htmlFor="primaryMobile">
              Primary Mobile Number<RequiredStar />
            </Label>
            <Input
              id="primaryMobile"
              className={["mt-2 h-11", errors.primaryMobile ? "border-red-500" : ""].join(" ")}
              placeholder="Primary Mobile Number"
              value={values.primaryMobile || ""}
              onChange={(e) => onChange({ primaryMobile: e.target.value })}
              ref={(el) => (refs.current.primaryMobile = el)}
            />
            <FieldError msg={errors.primaryMobile} />
          </div>

          <div>
            <Label className="text-sm text-gray-700" htmlFor="alternativeMobile">
              Alternative Mobile Number
            </Label>
            <Input
              id="alternativeMobile"
              className="mt-2 h-11"
              placeholder="Alternative Mobile Number"
              value={values.alternativeMobile || ""}
              onChange={(e) => onChange({ alternativeMobile: e.target.value })}
            />
          </div>

          <div>
            <Label className="text-sm text-gray-700" htmlFor="whatsappMobile">
              Whatsapp Mobile Number
            </Label>
            <Input
              id="whatsappMobile"
              className="mt-2 h-11"
              placeholder="Whatsapp Mobile Number"
              value={values.whatsappMobile || ""}
              onChange={(e) => onChange({ whatsappMobile: e.target.value })}
            />
          </div>

          {/* Row 3 */}
          <div>
            <Label className="text-sm text-gray-700" htmlFor="email">
              Email ID
            </Label>
            <Input
              id="email"
              className="mt-2 h-11"
              placeholder="Email ID"
              value={values.email || ""}
              onChange={(e) => onChange({ email: e.target.value })}
            />
          </div>

          <div>
            <Label className="text-sm text-gray-700" htmlFor="licenseNumber">
              License Number
            </Label>
            <Input
              id="licenseNumber"
              className="mt-2 h-11"
              placeholder="License Number Format: CH03 78678555785"
              value={values.licenseNumber || ""}
              onChange={(e) => onChange({ licenseNumber: e.target.value })}
            />
            <div className="text-xs font-semibold text-gray-700 mt-2">
              License Number Format: CH03 78678555785
            </div>
          </div>

          <DatePicker
            label="License Issue Date"
            inputId="licenseIssueDate"
            valueISO={values.licenseIssueDate}
            onChangeISO={(iso) => onChange({ licenseIssueDate: iso })}
            error={errors.licenseIssueDate}
          />

          {/* Row 4 */}
          <DatePicker
            label="License Expire Date"
            inputId="licenseExpireDate"
            valueISO={values.licenseExpireDate}
            onChangeISO={(iso) => onChange({ licenseExpireDate: iso })}
            error={errors.licenseExpireDate}
          />

          <DatePicker
            label="Date of Birth"
            inputId="dateOfBirth"
            valueISO={values.dateOfBirth}
            onChangeISO={(iso) => onChange({ dateOfBirth: iso })}
            error={errors.dateOfBirth}
          />

          <div>
            <Label className="text-sm text-gray-700">Blood Group</Label>
            <div className="mt-2">
              <Select
                value={values.bloodGroup || ""}
                onValueChange={(v) => onChange({ bloodGroup: v })}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Choose Blood Group" />
                </SelectTrigger>
                <SelectContent>
                  {bloodGroups.map((o) => (
                    <SelectItem key={String(o.id)} value={String(o.id)}>
                      {o.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
          </div>

          {/* Row 5 */}
          <div>
            <Label className="text-sm text-gray-700">Gender</Label>
            <div className="mt-2">
              <Select
                value={values.gender || ""}
                onValueChange={(v) => onChange({ gender: v })}
              >
                <SelectTrigger>
                  <SelectValue placeholder="Choose Gender" />
                </SelectTrigger>
                <SelectContent>
                  {genders.map((o) => (
                    <SelectItem key={String(o.id)} value={String(o.id)}>
                      {o.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
          </div>

          <div>
            <Label className="text-sm text-gray-700" htmlFor="aadharNumber">
              Aadhar Card Number
            </Label>
            <Input
              id="aadharNumber"
              className="mt-2 h-11"
              placeholder="Aadhar Number Format: 246884637988"
              value={values.aadharNumber || ""}
              onChange={(e) => onChange({ aadharNumber: e.target.value })}
            />
            <div className="text-xs font-semibold text-gray-700 mt-2">
              Aadhar Number Format: 246884637988
            </div>
          </div>

          <div>
            <Label className="text-sm text-gray-700" htmlFor="panNumber">
              PAN Card Number
            </Label>
            <Input
              id="panNumber"
              className="mt-2 h-11"
              placeholder="Pan Format: CNFPC5441D"
              value={values.panNumber || ""}
              onChange={(e) => onChange({ panNumber: e.target.value })}
            />
            <div className="text-xs font-semibold text-gray-700 mt-2">
              Pan Format: CNFPC5441D
            </div>
          </div>

          {/* Row 6 */}
          <div>
            <Label className="text-sm text-gray-700" htmlFor="voterId">
              Voter ID Number
            </Label>
            <Input
              id="voterId"
              className="mt-2 h-11"
              placeholder="Voter ID Number"
              value={values.voterId || ""}
              onChange={(e) => onChange({ voterId: e.target.value })}
            />
          </div>

          <div>
            <Label className="text-sm text-gray-700" htmlFor="profileFile">
              Upload Profile
            </Label>
            <Input
              id="profileFile"
              type="file"
              className="mt-2 h-11"
              onChange={(e) => onChange({ profileFile: e.target.files?.[0] ?? null })}
              ref={(el) => (refs.current.profileFile = el)}
            />
          </div>

          <div>
            <Label className="text-sm text-gray-700" htmlFor="address">
              Address
            </Label>
            <Textarea
              id="address"
              className="mt-2 min-h-[90px]"
              placeholder="Address"
              value={values.address || ""}
              onChange={(e) => onChange({ address: e.target.value })}
              ref={(el) => (refs.current.address = el)}
            />
          </div>
        </div>

        {/* Footer buttons (same placement as screenshot) */}
        <div className="mt-8 flex items-center justify-between">
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
            onClick={handleSave}
            disabled={saving}
          >
            Save &amp; Continue
          </Button>
        </div>
      </CardContent>
    </Card>
  );
}
