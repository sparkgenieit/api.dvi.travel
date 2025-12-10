// REPLACE-WHOLE-FILE
// FILE: src/pages/VehicleAvailability/modals/AddDriverModal.tsx

import React, { useEffect, useState } from "react";
import {
  SimpleOption,
  createDriver,
  fetchVendorVehicleTypes,
} from "@/services/vehicle-availability";
import { ChevronDown } from "lucide-react";

type Props = {
  open: boolean;
  onClose: () => void;
  onCreated?: () => void;

  vendors: SimpleOption[];
  vehicleTypes?: SimpleOption[]; // optional fallback (we fetch per vendor)
  defaultVendorId?: number | "";
  defaultVehicleTypeId?: number | "";
};

const inputBase =
  "h-[44px] w-full rounded-md border bg-white px-4 text-[15px] text-slate-700 placeholder:text-slate-400 focus:outline-none";
const labelBase = "text-[15px] text-slate-600";

function SelectBox({
  value,
  onChange,
  placeholder,
  options,
  invalid,
  disabled,
}: {
  value: string;
  onChange: (v: string) => void;
  placeholder: string;
  options: SimpleOption[];
  invalid?: boolean;
  disabled?: boolean;
}) {
  return (
    <div className="relative">
      <select
        className={[
          inputBase,
          "appearance-none pr-10",
          invalid ? "border-red-400" : "border-slate-300",
          disabled ? "bg-slate-100 text-slate-400" : "",
        ].join(" ")}
        value={value}
        onChange={(e) => onChange(e.target.value)}
        disabled={disabled}
      >
        <option value="">{placeholder}</option>
        {options.map((o) => (
          <option key={o.id} value={String(o.id)}>
            {o.label}
          </option>
        ))}
      </select>
      <ChevronDown
        size={18}
        className="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-500"
      />
    </div>
  );
}

export function AddDriverModal({
  open,
  onClose,
  onCreated,
  vendors,
  vehicleTypes: legacyVehicleTypes = [],
  defaultVendorId = "",
  defaultVehicleTypeId = "",
}: Props) {
  const [vendorId, setVendorId] = useState<number | "">("");
  const [vehicleTypeId, setVehicleTypeId] = useState<number | "">("");
  const [driverName, setDriverName] = useState("");
  const [mobile, setMobile] = useState("");

  const [vendorVehicleTypes, setVendorVehicleTypes] = useState<SimpleOption[]>(
    [],
  );
  const [loadingTypes, setLoadingTypes] = useState(false);

  const [saving, setSaving] = useState(false);
  const [error, setError] = useState("");

  // init on open
  useEffect(() => {
    if (!open) return;
    setError("");
    setSaving(false);

    setVendorId(defaultVendorId ?? "");
    setVehicleTypeId(defaultVehicleTypeId ?? "");
    setDriverName("");
    setMobile("");

    // preload types for default vendor if provided
    const v = Number(defaultVendorId);
    if (defaultVendorId !== "" && Number.isFinite(v) && v > 0) {
      setLoadingTypes(true);
      fetchVendorVehicleTypes(v)
        .then((opts) => setVendorVehicleTypes(opts || []))
        .catch(() => setVendorVehicleTypes([]))
        .finally(() => setLoadingTypes(false));
    } else {
      setVendorVehicleTypes([]);
    }
  }, [open, defaultVendorId, defaultVehicleTypeId]);

  // fetch vendor-specific types when vendor changes
  useEffect(() => {
    if (!open) return;
    if (vendorId === "") {
      setVendorVehicleTypes([]);
      setVehicleTypeId("");
      return;
    }
    let alive = true;
    setLoadingTypes(true);
    fetchVendorVehicleTypes(Number(vendorId))
      .then((opts) => {
        if (!alive) return;
        setVendorVehicleTypes(opts || []);
        if (
          vehicleTypeId !== "" &&
          !(opts || []).some((o) => String(o.id) === String(vehicleTypeId))
        ) {
          setVehicleTypeId("");
        }
      })
      .catch(() => {
        if (!alive) return;
        setVendorVehicleTypes([]);
        setVehicleTypeId("");
      })
      .finally(() => {
        if (alive) setLoadingTypes(false);
      });
    return () => {
      alive = false;
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [vendorId]);

  // ---- derived values (no hooks after conditional return) ----
  const effectiveVehicleTypes =
    vendorVehicleTypes.length > 0 ? vendorVehicleTypes : legacyVehicleTypes;

  const vendorInvalid = vendorId === "";
  const vehicleTypeInvalid = vehicleTypeId === "";

  // simple derived boolean (no useMemo so hooks order is safe)
  const mobileValid = (() => {
    const s = mobile.trim();
    if (!s.length) return false;
    if (!/^[0-9+\-\s()]+$/.test(s)) return false;
    const onlyDigits = s.replace(/[^\d]/g, "");
    return onlyDigits.length >= 10 && onlyDigits.length <= 15;
  })();

  if (!open) return null;

  async function handleSave() {
    setError("");
    if (vendorInvalid) return setError("Please choose Vendor.");
    if (vehicleTypeInvalid) return setError("Please choose Vehicle Type.");
    if (!driverName.trim()) return setError("Please enter Driver Name.");
    if (!mobileValid) return setError("Please enter a valid Primary Mobile Number.");

    try {
      setSaving(true);
      await createDriver({
        vendorId: Number(vendorId),
        vehicleTypeId: Number(vehicleTypeId), // vendor_vehicle_type_ID
        driverName: driverName.trim(),
        mobile: mobile.trim(),
      });
      onClose();
      onCreated?.();
    } catch (e: any) {
      setError(e?.message || "Failed to add driver.");
    } finally {
      setSaving(false);
    }
  }

  return (
    <div
      className="fixed inset-0 z-[70] flex items-center justify-center bg-black/35 px-4 py-8"
      onClick={onClose}
    >
      <div
        className="w-full max-w-[760px] rounded-lg bg-white shadow-2xl"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="pt-10 text-center">
          <h2 className="text-[28px] font-medium text-slate-600">
            Add New Driver
          </h2>
        </div>

        <div className="px-10 pb-10 pt-8">
          {error ? (
            <div className="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
              {error}
            </div>
          ) : null}

          <div className="space-y-6">
            {/* Vendor */}
            <div>
              <div className={labelBase}>
                Vendor <span className="text-red-500">*</span>
              </div>
              <div className="mt-2">
                <SelectBox
                  value={vendorId === "" ? "" : String(vendorId)}
                  onChange={(v) => setVendorId(v ? Number(v) : "")}
                  placeholder="Choose Vendor"
                  options={vendors}
                  invalid={vendorInvalid}
                />
              </div>
            </div>

            {/* Vehicle Type (vendor-scoped) */}
            <div>
              <div className={labelBase}>
                Vehicle Type <span className="text-red-500">*</span>
              </div>
              <div className="mt-2">
                <SelectBox
                  value={vehicleTypeId === "" ? "" : String(vehicleTypeId)}
                  onChange={(v) => setVehicleTypeId(v ? Number(v) : "")}
                  placeholder={
                    loadingTypes ? "Loading vehicle types..." : "Choose Vehicle Type"
                  }
                  options={effectiveVehicleTypes}
                  invalid={vehicleTypeInvalid}
                  disabled={vendorId === "" || loadingTypes}
                />
                {vendorId !== "" && !loadingTypes && effectiveVehicleTypes.length === 0 ? (
                  <div className="mt-1 text-xs text-slate-400">
                    No vehicle types found for this vendor.
                  </div>
                ) : null}
              </div>
            </div>

            {/* Driver Name */}
            <div>
              <div className={labelBase}>
                Driver Name <span className="text-red-500">*</span>
              </div>
              <div className="mt-2">
                <input
                  className={[inputBase, "border-slate-300"].join(" ")}
                  value={driverName}
                  onChange={(e) => setDriverName(e.target.value)}
                  placeholder="Driver Name"
                />
              </div>
            </div>

            {/* Primary Mobile */}
            <div>
              <div className={labelBase}>
                Primary Mobile Number <span className="text-red-500">*</span>
              </div>
              <div className="mt-2">
                <input
                  className={[
                    inputBase,
                    mobile.trim().length === 0
                      ? "border-slate-300"
                      : mobileValid
                      ? "border-green-500"
                      : "border-red-400",
                  ].join(" ")}
                  value={mobile}
                  onChange={(e) => setMobile(e.target.value)}
                  placeholder="Primary Mobile Number"
                  inputMode="tel"
                />
              </div>
            </div>
          </div>

          {/* Footer */}
          <div className="mt-12 flex items-center justify-between">
            <button
              type="button"
              onClick={onClose}
              disabled={saving}
              className="h-[44px] w-[130px] rounded-md bg-slate-400 text-[16px] font-semibold text-white hover:bg-slate-500 disabled:opacity-60"
            >
              Cancel
            </button>

            <button
              type="button"
              onClick={handleSave}
              disabled={saving}
              className="h-[44px] w-[100px] rounded-md bg-gradient-to-r from-purple-600 to-pink-500 text-[16px] font-semibold text-white hover:opacity-95 disabled:opacity-60"
            >
              {saving ? "Saving" : "Save"}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
