import React, { useEffect, useMemo, useRef, useState } from "react";
import {
  SimpleOption,
  createVehicle,
  fetchVendorBranches,
  fetchVendorVehicleTypes,
  fetchLocations, // live suggestions
} from "@/services/vehicle-availability";
import { ChevronDown } from "lucide-react";

type Props = {
  open: boolean;
  onClose: () => void;
  onCreated?: () => void;

  vendors: SimpleOption[];
  /** Optional global types; once vendor is chosen we fetch vendor-scoped types */
  vehicleTypes?: SimpleOption[];
  /** Optional initial origin suggestions (shown when the input is focused and empty) */
  locations?: SimpleOption[];

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

/**
 * Tiny headless Autocomplete used for "Vehicle Origin".
 * - Debounced server search via fetchLocations(q)
 * - Optional initial suggestions from props.locations when empty
 * - Keyboard navigation (↑/↓/Enter/Escape)
 * - Click outside to close
 */
function Autocomplete({
  value,
  onChange,
  placeholder,
  initialSuggestions = [],
  disabled,
  invalid,
}: {
  value: string;
  onChange: (next: string) => void;
  placeholder: string;
  initialSuggestions?: SimpleOption[];
  disabled?: boolean;
  invalid?: boolean;
}) {
  const [open, setOpen] = useState(false);
  const [query, setQuery] = useState(value);
  const [loading, setLoading] = useState(false);
  const [items, setItems] = useState<SimpleOption[]>(initialSuggestions || []);
  const [activeIndex, setActiveIndex] = useState<number>(-1);
  const rootRef = useRef<HTMLDivElement | null>(null);
  const debounceRef = useRef<number | null>(null);

  // keep internal query in sync when parent changes value externally
  useEffect(() => {
    setQuery(value);
  }, [value]);

  // close on click outside
  useEffect(() => {
    function onDocClick(e: MouseEvent) {
      if (!rootRef.current) return;
      if (!rootRef.current.contains(e.target as Node)) {
        setOpen(false);
        setActiveIndex(-1);
      }
    }
    document.addEventListener("mousedown", onDocClick);
    return () => document.removeEventListener("mousedown", onDocClick);
  }, []);

  // debounced fetch
  useEffect(() => {
    if (!open) return;
    // if empty, show initialSuggestions
    if (!query.trim()) {
      setItems(initialSuggestions || []);
      setActiveIndex(-1);
      return;
    }
    if (debounceRef.current) window.clearTimeout(debounceRef.current);
    debounceRef.current = window.setTimeout(async () => {
      setLoading(true);
      try {
        const list = await fetchLocations(query.trim());
        setItems(list || []);
        setActiveIndex(list?.length ? 0 : -1);
      } catch {
        setItems([]);
        setActiveIndex(-1);
      } finally {
        setLoading(false);
      }
    }, 300);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [query, open]);

  function choose(option: SimpleOption) {
    onChange(option.label);
    setQuery(option.label);
    setOpen(false);
    setActiveIndex(-1);
  }

  function onKeyDown(e: React.KeyboardEvent<HTMLInputElement>) {
    if (!open) return;
    if (e.key === "ArrowDown") {
      e.preventDefault();
      setActiveIndex((i) => (items.length ? (i + 1) % items.length : -1));
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      setActiveIndex((i) => (items.length ? (i - 1 + items.length) % items.length : -1));
    } else if (e.key === "Enter") {
      if (activeIndex >= 0 && activeIndex < items.length) {
        e.preventDefault();
        choose(items[activeIndex]);
      }
    } else if (e.key === "Escape") {
      setOpen(false);
      setActiveIndex(-1);
    }
  }

  return (
    <div className="relative" ref={rootRef}>
      <input
        className={[
          inputBase,
          invalid ? "border-red-400" : "border-slate-300",
          disabled ? "bg-slate-100 text-slate-400" : "",
        ].join(" ")}
        value={query}
        onChange={(e) => {
          setQuery(e.target.value);
          onChange(e.target.value);
          if (!open) setOpen(true);
        }}
        onFocus={() => setOpen(true)}
        onKeyDown={onKeyDown}
        placeholder={placeholder}
        disabled={disabled}
        aria-autocomplete="list"
        aria-expanded={open}
        aria-controls="origin-autocomplete-listbox"
      />

      {open && (
        <div
          id="origin-autocomplete-listbox"
          role="listbox"
          className="absolute z-50 mt-1 max-h-64 w-full overflow-auto rounded-md border border-slate-200 bg-white shadow-lg"
        >
          {loading ? (
            <div className="px-3 py-2 text-sm text-slate-500">Searching…</div>
          ) : items.length === 0 ? (
            <div className="px-3 py-2 text-sm text-slate-500">No results</div>
          ) : (
            items.map((opt, idx) => (
              <button
                type="button"
                key={`${opt.id}-${opt.label}`}
                role="option"
                aria-selected={idx === activeIndex}
                className={[
                  "block w-full cursor-pointer px-3 py-2 text-left text-[14px]",
                  idx === activeIndex ? "bg-slate-100" : "bg-white",
                ].join(" ")}
                onMouseEnter={() => setActiveIndex(idx)}
                onMouseDown={(e) => {
                  // mousedown to pick before input blur
                  e.preventDefault();
                  choose(opt);
                }}
              >
                {opt.label}
              </button>
            ))
          )}
        </div>
      )}
    </div>
  );
}

export function AddVehicleModal({
  open,
  onClose,
  onCreated,
  vendors,
  vehicleTypes: globalVehicleTypes = [],
  locations = [],
  defaultVendorId = "",
  defaultVehicleTypeId = "",
}: Props) {
  const [vendorId, setVendorId] = useState<number | "">("");
  const [vendorBranchId, setVendorBranchId] = useState<number | "">("");
  const [vehicleTypeId, setVehicleTypeId] = useState<number | "">("");
  const [registrationNumber, setRegistrationNumber] = useState("");

  const [vehicleOrigin, setVehicleOrigin] = useState("");
  const [vehicleExpiryDate, setVehicleExpiryDate] = useState(""); // UI label
  const [insuranceStartDate, setInsuranceStartDate] = useState("");
  const [insuranceEndDate, setInsuranceEndDate] = useState("");

  const [branches, setBranches] = useState<SimpleOption[]>([]);
  const [branchesLoading, setBranchesLoading] = useState(false);

  const [vendorVehicleTypes, setVendorVehicleTypes] = useState<SimpleOption[]>(
    [],
  );
  const [typesLoading, setTypesLoading] = useState(false);

  const [saving, setSaving] = useState(false);
  const [error, setError] = useState("");

  // Reset on open
  useEffect(() => {
    if (!open) return;

    setError("");
    setSaving(false);

    setVendorId(defaultVendorId ?? "");
    setVehicleTypeId(defaultVehicleTypeId ?? "");
    setVendorBranchId("");

    setRegistrationNumber("");
    setVehicleOrigin("");
    setVehicleExpiryDate("");
    setInsuranceStartDate("");
    setInsuranceEndDate("");

    setBranches([]);
    setBranchesLoading(false);

    // Preload vendor types for default vendor if provided
    if (defaultVendorId && Number(defaultVendorId) > 0) {
      setTypesLoading(true);
      fetchVendorVehicleTypes(Number(defaultVendorId))
        .then((opts) => setVendorVehicleTypes(opts || []))
        .catch(() => setVendorVehicleTypes([]))
        .finally(() => setTypesLoading(false));
    } else {
      setVendorVehicleTypes([]);
    }
  }, [open, defaultVendorId, defaultVehicleTypeId]);

  // Load branches when vendor changes
  useEffect(() => {
    if (!open) return;

    const vId = vendorId === "" ? null : Number(vendorId);
    if (!vId) {
      setBranches([]);
      setVendorBranchId("");
      return;
    }

    let alive = true;
    (async () => {
      setBranchesLoading(true);
      setError("");
      try {
        const res = await fetchVendorBranches(vId);
        if (!alive) return;
        setBranches(res);
        setVendorBranchId("");
      } catch (e: any) {
        if (!alive) return;
        setBranches([]);
        setVendorBranchId("");
        setError(e?.message || "Failed to load vendor branches.");
      } finally {
        if (alive) setBranchesLoading(false);
      }
    })();

    return () => {
      alive = false;
    };
  }, [vendorId, open]);

  // Load vendor-scoped vehicle types when vendor changes
  useEffect(() => {
    if (!open) return;

    if (vendorId === "") {
      setVendorVehicleTypes([]);
      setVehicleTypeId("");
      return;
    }

    let alive = true;
    setTypesLoading(true);
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
        if (alive) setTypesLoading(false);
      });

    return () => {
      alive = false;
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [vendorId]);

  // ---- derive formatted reg (NO hook here; avoids rules-of-hooks error) ----
  const regFormatted = registrationNumber.replace(/\s+/g, " ").trim().toUpperCase();

  if (!open) return null;

  const effectiveVehicleTypes =
    vendorVehicleTypes.length > 0 ? vendorVehicleTypes : globalVehicleTypes;

  const vendorInvalid = vendorId === "";
  const branchInvalid = vendorBranchId === "";
  const vehicleTypeInvalid = vehicleTypeId === "";

  async function handleSave() {
    setError("");

    if (vendorInvalid) return setError("Please choose Vendor.");
    if (branchInvalid) return setError("Please choose Vendor Branch.");
    if (vehicleTypeInvalid) return setError("Please choose Vehicle Type.");
    if (!regFormatted) return setError("Please enter Registration Number.");
    if (!vehicleOrigin.trim())
      return setError("Please choose Vehicle Origin.");
    if (!vehicleExpiryDate)
      return setError("Please choose Vehicle Expiry Date.");
    if (!insuranceStartDate)
      return setError("Please choose Insurance Start Date.");
    if (!insuranceEndDate)
      return setError("Please choose Insurance End Date.");

    try {
      setSaving(true);

      await createVehicle({
        vendorId: Number(vendorId),
        vehicleTypeId: Number(vehicleTypeId), // vendor_vehicle_type_ID
        registrationNumber: regFormatted,

        vendor_branch_id: Number(vendorBranchId),
        vehicle_origin: vehicleOrigin.trim(),
        vehicle_fc_expiry_date: vehicleExpiryDate,
        insurance_start_date: insuranceStartDate,
        insurance_end_date: insuranceEndDate,
      });

      onClose();
      onCreated?.();
    } catch (e: any) {
      setError(e?.message || "Failed to add vehicle.");
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
        className="w-full max-w-[980px] rounded-lg bg-white shadow-2xl"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Header */}
        <div className="pt-10 text-center">
          <h2 className="text-[28px] font-medium text-slate-600">
            Add New Vehicle
          </h2>
        </div>

        <div className="px-10 pb-10 pt-8">
          {error ? (
            <div className="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
              {error}
            </div>
          ) : null}

          <div className="grid grid-cols-1 gap-x-10 gap-y-7 md:grid-cols-2">
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

            {/* Vendor Branch */}
            <div>
              <div className={labelBase}>
                Vendor Branch <span className="text-red-500">*</span>
              </div>
              <div className="mt-2">
                <SelectBox
                  value={vendorBranchId === "" ? "" : String(vendorBranchId)}
                  onChange={(v) => setVendorBranchId(v ? Number(v) : "")}
                  placeholder={branchesLoading ? "Loading..." : "Choose Branch"}
                  options={branches}
                  invalid={branchInvalid}
                  disabled={vendorId === "" || branchesLoading}
                />
              </div>
            </div>

            {/* Vehicle Type */}
            <div>
              <div className={labelBase}>
                Vehicle Type <span className="text-red-500">*</span>
              </div>
              <div className="mt-2">
                <SelectBox
                  value={vehicleTypeId === "" ? "" : String(vehicleTypeId)}
                  onChange={(v) => setVehicleTypeId(v ? Number(v) : "")}
                  placeholder={
                    typesLoading ? "Loading vehicle types..." : "Choose Vehicle Type"
                  }
                  options={effectiveVehicleTypes}
                  invalid={vehicleTypeInvalid}
                  disabled={vendorId === "" || typesLoading}
                />
              </div>
            </div>

            {/* Registration Number */}
            <div>
              <div className={labelBase}>
                Registration Number <span className="text-red-500">*</span>
              </div>
              <div className="mt-2">
                <input
                  className={[inputBase, "border-slate-300"].join(" ")}
                  value={registrationNumber}
                  onChange={(e) => setRegistrationNumber(e.target.value)}
                  placeholder="Registration Number"
                />
                {registrationNumber && regFormatted !== registrationNumber && (
                  <div className="mt-1 text-xs text-slate-500">
                    Will save as: <span className="font-mono">{regFormatted}</span>
                  </div>
                )}
              </div>
            </div>

            {/* Vehicle Origin (Autocomplete) */}
            <div>
              <div className={labelBase}>
                Vehicle Origin <span className="text-red-500">*</span>
              </div>
              <div className="mt-2">
                <Autocomplete
                  value={vehicleOrigin}
                  onChange={setVehicleOrigin}
                  placeholder="Search city / origin…"
                  initialSuggestions={locations}
                  invalid={!vehicleOrigin.trim()}
                />
              </div>
            </div>

            {/* Vehicle Expiry Date */}
            <div>
              <div className={labelBase}>
                Vehicle Expiry Date <span className="text-red-500">*</span>
              </div>
              <div className="mt-2">
                <input
                  className={[inputBase, "border-slate-300"].join(" ")}
                  type="date"
                  value={vehicleExpiryDate}
                  onChange={(e) => setVehicleExpiryDate(e.target.value)}
                />
              </div>
            </div>

            {/* Insurance Start Date */}
            <div>
              <div className={labelBase}>
                Insurance Start Date <span className="text-red-500">*</span>
              </div>
              <div className="mt-2">
                <input
                  className={[inputBase, "border-slate-300"].join(" ")}
                  type="date"
                  value={insuranceStartDate}
                  onChange={(e) => setInsuranceStartDate(e.target.value)}
                />
              </div>
            </div>

            {/* Insurance End Date */}
            <div>
              <div className={labelBase}>
                Insurance End Date <span className="text-red-500">*</span>
              </div>
              <div className="mt-2">
                <input
                  className={[inputBase, "border-slate-300"].join(" ")}
                  type="date"
                  value={insuranceEndDate}
                  onChange={(e) => setInsuranceEndDate(e.target.value)}
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
