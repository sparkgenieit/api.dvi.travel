// FILE: src/pages/vendor/steps/VendorStepVehicleTypeCost.tsx

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

// ======== Types for local UI state (can be wired to API later) ========

type DriverCostRow = {
  id: number;
  vehicleType: string;
  driverBhatta: string;
  foodCost: string;
  accommodationCost: string;
  extraCost: string;
  morningCharges: string;
  eveningCharges: string;
};

type OutstationKmLimitRow = {
  id: number;
  vehicleType: string;
  title: string;
  limit: string;
  status: "Active" | "Inactive";
};

type LocalKmLimitRow = {
  id: number;
  vehicleType: string;
  title: string;
  hours: string;
  km: string;
  status: "Active" | "Inactive";
};

type ActiveTab = "driverCost" | "outstation" | "local";

// Dummy vehicle list (replace with real dropdown from API)
const VEHICLE_TYPES = [
  { id: "sedan", label: "Sedan" },
  { id: "suv", label: "SUV" },
  { id: "tempo_traveller", label: "Tempo Traveller" },
];

export const VendorStepVehicleTypeCost: React.FC<Props> = ({
  vendorId,
  onBack,
  onNext,
}) => {
  const [activeTab, setActiveTab] = useState<ActiveTab>("driverCost");

  // ---- Driver Cost state ----
  const [driverCostRows, setDriverCostRows] = useState<DriverCostRow[]>([]);
  const [driverCostSearch, setDriverCostSearch] = useState("");
  const [showDriverCostModal, setShowDriverCostModal] = useState(false);
  const [editingDriverRow, setEditingDriverRow] = useState<DriverCostRow | null>(
    null
  );

  const [driverFormVehicleType, setDriverFormVehicleType] = useState<string>("");
  const [driverFormFields, setDriverFormFields] = useState({
    driverBhatta: "",
    foodCost: "",
    accommodationCost: "",
    extraCost: "",
    morningCharges: "",
    eveningCharges: "",
  });

  // ---- Outstation KM Limit state ----
  const [outstationRows, setOutstationRows] = useState<OutstationKmLimitRow[]>(
    []
  );
  const [outstationSearch, setOutstationSearch] = useState("");
  const [showOutstationModal, setShowOutstationModal] = useState(false);
  const [editingOutstationRow, setEditingOutstationRow] =
    useState<OutstationKmLimitRow | null>(null);

  const [outstationFormVehicleType, setOutstationFormVehicleType] =
    useState<string>("");
  const [outstationFormFields, setOutstationFormFields] = useState({
    title: "",
    limit: "",
  });

  // ---- Local KM Limit state ----
  const [localRows, setLocalRows] = useState<LocalKmLimitRow[]>([]);
  const [localSearch, setLocalSearch] = useState("");
  const [showLocalModal, setShowLocalModal] = useState(false);
  const [editingLocalRow, setEditingLocalRow] =
    useState<LocalKmLimitRow | null>(null);

  const [localFormVehicleType, setLocalFormVehicleType] = useState<string>("");
  const [localFormFields, setLocalFormFields] = useState({
    title: "",
    hours: "",
    km: "",
  });

  // ====== Helpers for tables (simple client-side search) ======

  const filteredDriverCostRows = useMemo(() => {
    if (!driverCostSearch.trim()) return driverCostRows;
    const q = driverCostSearch.toLowerCase();
    return driverCostRows.filter(
      (row) =>
        row.vehicleType.toLowerCase().includes(q) ||
        row.driverBhatta.toLowerCase().includes(q)
    );
  }, [driverCostRows, driverCostSearch]);

  const filteredOutstationRows = useMemo(() => {
    if (!outstationSearch.trim()) return outstationRows;
    const q = outstationSearch.toLowerCase();
    return outstationRows.filter(
      (row) =>
        row.vehicleType.toLowerCase().includes(q) ||
        row.title.toLowerCase().includes(q)
    );
  }, [outstationRows, outstationSearch]);

  const filteredLocalRows = useMemo(() => {
    if (!localSearch.trim()) return localRows;
    const q = localSearch.toLowerCase();
    return localRows.filter(
      (row) =>
        row.vehicleType.toLowerCase().includes(q) ||
        row.title.toLowerCase().includes(q)
    );
  }, [localRows, localSearch]);

  // ============================================================
  // Driver Cost modal handlers
  // ============================================================

  const openAddDriverCost = () => {
    setEditingDriverRow(null);
    setDriverFormVehicleType("");
    setDriverFormFields({
      driverBhatta: "",
      foodCost: "",
      accommodationCost: "",
      extraCost: "",
      morningCharges: "",
      eveningCharges: "",
    });
    setShowDriverCostModal(true);
  };

  const openEditDriverCost = (row: DriverCostRow) => {
    setEditingDriverRow(row);
    setDriverFormVehicleType(row.vehicleType);
    setDriverFormFields({
      driverBhatta: row.driverBhatta,
      foodCost: row.foodCost,
      accommodationCost: row.accommodationCost,
      extraCost: row.extraCost,
      morningCharges: row.morningCharges,
      eveningCharges: row.eveningCharges,
    });
    setShowDriverCostModal(true);
  };

  const handleSaveDriverCost = () => {
    if (!driverFormVehicleType) return;

    if (editingDriverRow) {
      setDriverCostRows((prev) =>
        prev.map((row) =>
          row.id === editingDriverRow.id
            ? {
                ...row,
                vehicleType: driverFormVehicleType,
                ...driverFormFields,
              }
            : row
        )
      );
    } else {
      const nextId =
        driverCostRows.length === 0
          ? 1
          : Math.max(...driverCostRows.map((r) => r.id)) + 1;
      setDriverCostRows((prev) => [
        ...prev,
        {
          id: nextId,
          vehicleType: driverFormVehicleType,
          ...driverFormFields,
        },
      ]);
    }

    setShowDriverCostModal(false);
  };

  const handleDeleteDriverCost = (rowId: number) => {
    setDriverCostRows((prev) => prev.filter((row) => row.id !== rowId));
  };

  // ============================================================
  // Outstation KM modal handlers
  // ============================================================

  const openAddOutstation = () => {
    setEditingOutstationRow(null);
    setOutstationFormVehicleType("");
    setOutstationFormFields({ title: "", limit: "" });
    setShowOutstationModal(true);
  };

  const openEditOutstation = (row: OutstationKmLimitRow) => {
    setEditingOutstationRow(row);
    setOutstationFormVehicleType(row.vehicleType);
    setOutstationFormFields({ title: row.title, limit: row.limit });
    setShowOutstationModal(true);
  };

  const handleSaveOutstation = () => {
    if (!outstationFormVehicleType) return;

    if (editingOutstationRow) {
      setOutstationRows((prev) =>
        prev.map((row) =>
          row.id === editingOutstationRow.id
            ? {
                ...row,
                vehicleType: outstationFormVehicleType,
                title: outstationFormFields.title,
                limit: outstationFormFields.limit,
              }
            : row
        )
      );
    } else {
      const nextId =
        outstationRows.length === 0
          ? 1
          : Math.max(...outstationRows.map((r) => r.id)) + 1;
      setOutstationRows((prev) => [
        ...prev,
        {
          id: nextId,
          vehicleType: outstationFormVehicleType,
          title: outstationFormFields.title,
          limit: outstationFormFields.limit,
          status: "Active",
        },
      ]);
    }

    setShowOutstationModal(false);
  };

  const handleDeleteOutstation = (rowId: number) => {
    setOutstationRows((prev) => prev.filter((row) => row.id !== rowId));
  };

  // ============================================================
  // Local KM modal handlers
  // ============================================================

  const openAddLocal = () => {
    setEditingLocalRow(null);
    setLocalFormVehicleType("");
    setLocalFormFields({ title: "", hours: "", km: "" });
    setShowLocalModal(true);
  };

  const openEditLocal = (row: LocalKmLimitRow) => {
    setEditingLocalRow(row);
    setLocalFormVehicleType(row.vehicleType);
    setLocalFormFields({
      title: row.title,
      hours: row.hours,
      km: row.km,
    });
    setShowLocalModal(true);
  };

  const handleSaveLocal = () => {
    if (!localFormVehicleType) return;

    if (editingLocalRow) {
      setLocalRows((prev) =>
        prev.map((row) =>
          row.id === editingLocalRow.id
            ? {
                ...row,
                vehicleType: localFormVehicleType,
                title: localFormFields.title,
                hours: localFormFields.hours,
                km: localFormFields.km,
              }
            : row
        )
      );
    } else {
      const nextId =
        localRows.length === 0
          ? 1
          : Math.max(...localRows.map((r) => r.id)) + 1;
      setLocalRows((prev) => [
        ...prev,
        {
          id: nextId,
          vehicleType: localFormVehicleType,
          title: localFormFields.title,
          hours: localFormFields.hours,
          km: localFormFields.km,
          status: "Active",
        },
      ]);
    }

    setShowLocalModal(false);
  };

  const handleDeleteLocal = (rowId: number) => {
    setLocalRows((prev) => prev.filter((row) => row.id !== rowId));
  };

  // ============================================================
  // Render helpers
  // ============================================================

  const renderTopTabs = () => (
    <div className="flex border-b border-gray-200 text-sm font-medium">
      <button
        type="button"
        className={`px-4 py-3 border-b-2 transition-colors ${
          activeTab === "driverCost"
            ? "border-pink-500 text-pink-600"
            : "border-transparent text-gray-500 hover:text-pink-600"
        }`}
        onClick={() => setActiveTab("driverCost")}
      >
        Driver Cost
      </button>
      <button
        type="button"
        className={`px-4 py-3 border-b-2 transition-colors ${
          activeTab === "outstation"
            ? "border-pink-500 text-pink-600"
            : "border-transparent text-gray-500 hover:text-pink-600"
        }`}
        onClick={() => setActiveTab("outstation")}
      >
        Outstation KM Limit
      </button>
      <button
        type="button"
        className={`px-4 py-3 border-b-2 transition-colors ${
          activeTab === "local"
            ? "border-pink-500 text-pink-600"
            : "border-transparent text-gray-500 hover:text-pink-600"
        }`}
        onClick={() => setActiveTab("local")}
      >
        Local KM Limit
      </button>
    </div>
  );

  const renderTableHeader = (cols: string[]) => (
    <thead className="bg-gray-50 text-xs uppercase text-gray-500">
      <tr>
        {cols.map((col) => (
          <th
            key={col}
            className="px-4 py-3 border-b border-gray-200 font-semibold text-left"
          >
            {col}
          </th>
        ))}
      </tr>
    </thead>
  );

  // ============================================================
  // MAIN RENDER
  // ============================================================

  return (
    <Card>
      <CardHeader className="flex flex-col gap-1">
        <CardTitle className="text-pink-600 text-lg">
          Vehicle Type – Driver Cost
        </CardTitle>
        {!vendorId && (
          <p className="text-xs text-red-500">
            Save <span className="font-semibold">Basic Info</span> and{" "}
            <span className="font-semibold">Branch</span> before configuring
            driver cost.
          </p>
        )}
      </CardHeader>

      <CardContent className="space-y-6">
        {renderTopTabs()}

        {/* ---------- DRIVER COST TAB ---------- */}
        {activeTab === "driverCost" && (
          <div className="pt-6 space-y-4">
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
              <h2 className="text-base font-semibold text-gray-700">
                List of Vehicle Type - Driver Cost
              </h2>
              <Button
                type="button"
                className="bg-purple-100 text-purple-700 hover:bg-purple-200 rounded-full px-4 py-2 text-sm font-semibold"
                onClick={openAddDriverCost}
                disabled={!vendorId}
              >
                + Add Vehicle Type - Driver Cost
              </Button>
            </div>

            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
              <div className="flex items-center gap-2">
                <span>Show</span>
                <select className="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                  <option>10</option>
                  <option>25</option>
                  <option>50</option>
                </select>
                <span>entries</span>
              </div>

              <div className="flex items-center gap-2">
                <span>Search:</span>
                <Input
                  value={driverCostSearch}
                  onChange={(e) => setDriverCostSearch(e.target.value)}
                  className="h-8 w-48 text-sm"
                />
              </div>
            </div>

            <div className="border border-gray-200 rounded-lg overflow-hidden">
              <table className="min-w-full divide-y divide-gray-200 text-sm">
                {renderTableHeader([
                  "S.NO",
                  "ACTION",
                  "VEHICLE TYPE",
                  "DRIVER BHATTA(₹)",
                  "FOOD COST(₹)",
                  "ACCOMODATION COST(₹)",
                  "EXTRA COST(₹)",
                  "MORNING CHARGES(₹)",
                  "EVENING CHARGES(₹)",
                ])}
                <tbody className="bg-white">
                  {filteredDriverCostRows.length === 0 && (
                    <tr>
                      <td
                        colSpan={9}
                        className="px-4 py-6 text-center text-sm text-gray-500"
                      >
                        No data available in table
                      </td>
                    </tr>
                  )}
                  {filteredDriverCostRows.map((row, index) => (
                    <tr key={row.id} className="hover:bg-gray-50">
                      <td className="px-4 py-3 border-b border-gray-100">
                        {index + 1}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100 space-x-2">
                        <Button
                          variant="outline"
                          size="sm"
                          className="h-7 px-3 text-xs"
                          type="button"
                          onClick={() => openEditDriverCost(row)}
                        >
                          Edit
                        </Button>
                        <Button
                          variant="outline"
                          size="sm"
                          className="h-7 px-3 text-xs text-red-600 border-red-200"
                          type="button"
                          onClick={() => handleDeleteDriverCost(row.id)}
                        >
                          Delete
                        </Button>
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.vehicleType}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.driverBhatta}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.foodCost}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.accommodationCost}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.extraCost}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.morningCharges}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.eveningCharges}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-gray-500">
              <span>
                Showing 0 to {filteredDriverCostRows.length} of{" "}
                {driverCostRows.length} entries
              </span>
              <div className="flex items-center gap-2">
                <Button
                  type="button"
                  variant="outline"
                  size="sm"
                  className="h-8 px-3 text-xs"
                >
                  Copy
                </Button>
                <Button
                  type="button"
                  variant="outline"
                  size="sm"
                  className="h-8 px-3 text-xs"
                >
                  Excel
                </Button>
                <Button
                  type="button"
                  variant="outline"
                  size="sm"
                  className="h-8 px-3 text-xs"
                >
                  CSV
                </Button>
              </div>
            </div>
          </div>
        )}

        {/* ---------- OUTSTATION KM LIMIT TAB ---------- */}
        {activeTab === "outstation" && (
          <div className="pt-6 space-y-4">
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
              <h2 className="text-base font-semibold text-gray-700">
                List of Outstation KM Limit
              </h2>
              <Button
                type="button"
                className="bg-purple-100 text-purple-700 hover:bg-purple-200 rounded-full px-4 py-2 text-sm font-semibold"
                onClick={openAddOutstation}
                disabled={!vendorId}
              >
                + Add Outstation KM Limit
              </Button>
            </div>

            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
              <div className="flex items-center gap-2">
                <span>Show</span>
                <select className="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                  <option>10</option>
                  <option>25</option>
                  <option>50</option>
                </select>
                <span>entries</span>
              </div>

              <div className="flex items-center gap-2">
                <span>Search:</span>
                <Input
                  value={outstationSearch}
                  onChange={(e) => setOutstationSearch(e.target.value)}
                  className="h-8 w-48 text-sm"
                />
              </div>
            </div>

            <div className="border border-gray-200 rounded-lg overflow-hidden">
              <table className="min-w-full divide-y divide-gray-200 text-sm">
                {renderTableHeader([
                  "S.NO",
                  "ACTION",
                  "VENDOR",
                  "VEHICLE TYPE",
                  "OUTSTATION KM LIMIT TITLE",
                  "OUTSTATION KM LIMIT",
                  "STATUS",
                ])}
                <tbody className="bg-white">
                  {filteredOutstationRows.length === 0 && (
                    <tr>
                      <td
                        colSpan={7}
                        className="px-4 py-6 text-center text-sm text-gray-500"
                      >
                        No data available in table
                      </td>
                    </tr>
                  )}
                  {filteredOutstationRows.map((row, index) => (
                    <tr key={row.id} className="hover:bg-gray-50">
                      <td className="px-4 py-3 border-b border-gray-100">
                        {index + 1}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100 space-x-2">
                        <Button
                          variant="outline"
                          size="sm"
                          className="h-7 px-3 text-xs"
                          type="button"
                          onClick={() => openEditOutstation(row)}
                        >
                          Edit
                        </Button>
                        <Button
                          variant="outline"
                          size="sm"
                          className="h-7 px-3 text-xs text-red-600 border-red-200"
                          type="button"
                          onClick={() => handleDeleteOutstation(row.id)}
                        >
                          Delete
                        </Button>
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {/* Vendor column: in PHP this is vendor name; here just show current vendorId */}
                        {vendorId ?? "-"}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.vehicleType}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.title}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.limit}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.status}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-gray-500">
              <span>
                Showing 0 to {filteredOutstationRows.length} of{" "}
                {outstationRows.length} entries
              </span>
              <div className="flex items-center gap-2">
                <Button
                  type="button"
                  variant="outline"
                  size="sm"
                  className="h-8 px-3 text-xs"
                >
                  Copy
                </Button>
                <Button
                  type="button"
                  variant="outline"
                  size="sm"
                  className="h-8 px-3 text-xs"
                >
                  Excel
                </Button>
                <Button
                  type="button"
                  variant="outline"
                  size="sm"
                  className="h-8 px-3 text-xs"
                >
                  CSV
                </Button>
              </div>
            </div>
          </div>
        )}

        {/* ---------- LOCAL KM LIMIT TAB ---------- */}
        {activeTab === "local" && (
          <div className="pt-6 space-y-4">
            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
              <h2 className="text-base font-semibold text-gray-700">
                List of Local KM Limit
              </h2>
              <Button
                type="button"
                className="bg-purple-100 text-purple-700 hover:bg-purple-200 rounded-full px-4 py-2 text-sm font-semibold"
                onClick={openAddLocal}
                disabled={!vendorId}
              >
                + Add Local KM Limit
              </Button>
            </div>

            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
              <div className="flex items-center gap-2">
                <span>Show</span>
                <select className="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-pink-500">
                  <option>10</option>
                  <option>25</option>
                  <option>50</option>
                </select>
                <span>entries</span>
              </div>

              <div className="flex items-center gap-2">
                <span>Search:</span>
                <Input
                  value={localSearch}
                  onChange={(e) => setLocalSearch(e.target.value)}
                  className="h-8 w-48 text-sm"
                />
              </div>
            </div>

            <div className="border border-gray-200 rounded-lg overflow-hidden">
              <table className="min-w-full divide-y divide-gray-200 text-sm">
                {renderTableHeader([
                  "S.NO",
                  "ACTION",
                  "VENDOR",
                  "VEHICLE TYPE",
                  "TITLE",
                  "HOURS",
                  "KM",
                  "STATUS",
                ])}
                <tbody className="bg-white">
                  {filteredLocalRows.length === 0 && (
                    <tr>
                      <td
                        colSpan={8}
                        className="px-4 py-6 text-center text-sm text-gray-500"
                      >
                        No data available in table
                      </td>
                    </tr>
                  )}
                  {filteredLocalRows.map((row, index) => (
                    <tr key={row.id} className="hover:bg-gray-50">
                      <td className="px-4 py-3 border-b border-gray-100">
                        {index + 1}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100 space-x-2">
                        <Button
                          variant="outline"
                          size="sm"
                          className="h-7 px-3 text-xs"
                          type="button"
                          onClick={() => openEditLocal(row)}
                        >
                          Edit
                        </Button>
                        <Button
                          variant="outline"
                          size="sm"
                          className="h-7 px-3 text-xs text-red-600 border-red-200"
                          type="button"
                          onClick={() => handleDeleteLocal(row.id)}
                        >
                          Delete
                        </Button>
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {vendorId ?? "-"}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.vehicleType}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.title}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.hours}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.km}
                      </td>
                      <td className="px-4 py-3 border-b border-gray-100">
                        {row.status}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-gray-500">
              <span>
                Showing 0 to {filteredLocalRows.length} of {localRows.length}{" "}
                entries
              </span>
              <div className="flex items-center gap-2">
                <Button
                  type="button"
                  variant="outline"
                  size="sm"
                  className="h-8 px-3 text-xs"
                >
                  Copy
                </Button>
                <Button
                  type="button"
                  variant="outline"
                  size="sm"
                  className="h-8 px-3 text-xs"
                >
                  Excel
                </Button>
                <Button
                  type="button"
                  variant="outline"
                  size="sm"
                  className="h-8 px-3 text-xs"
                >
                  CSV
                </Button>
              </div>
            </div>
          </div>
        )}

        {/* FOOTER BUTTONS */}
        <div className="mt-6 flex justify-between">
          <Button variant="outline" type="button" onClick={onBack}>
            Back
          </Button>
          <Button
            type="button"
            onClick={onNext}
            disabled={!vendorId}
            className="bg-gradient-to-r from-pink-500 to-purple-500 text-white hover:from-pink-600 hover:to-purple-600"
          >
            Continue
          </Button>
        </div>
      </CardContent>

      {/* ============================================================
          MODALS
          ============================================================ */}

      {/* DRIVER COST MODAL */}
      <Dialog open={showDriverCostModal} onOpenChange={setShowDriverCostModal}>
        <DialogContent className="sm:max-w-xl">
          <DialogHeader>
            <DialogTitle className="text-lg font-semibold text-gray-800">
              Vehicle Type - Driver Cost
            </DialogTitle>
          </DialogHeader>

          <div className="space-y-4">
            <div className="space-y-1">
              <Label className="text-sm font-medium">
                Vehicle type <span className="text-red-500">*</span>
              </Label>
              <Select
                value={driverFormVehicleType}
                onValueChange={setDriverFormVehicleType}
              >
                <SelectTrigger className="w-full">
                  <SelectValue placeholder="Choose Any One" />
                </SelectTrigger>
                <SelectContent>
                  {VEHICLE_TYPES.map((v) => (
                    <SelectItem key={v.id} value={v.label}>
                      {v.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div className="space-y-1">
                <Label>
                  Driver Bhatta (₹) <span className="text-red-500">*</span>
                </Label>
                <Input
                  placeholder="Driver Bhatta"
                  value={driverFormFields.driverBhatta}
                  onChange={(e) =>
                    setDriverFormFields((prev) => ({
                      ...prev,
                      driverBhatta: e.target.value,
                    }))
                  }
                />
              </div>
              <div className="space-y-1">
                <Label>
                  Driver Food Cost (₹) <span className="text-red-500">*</span>
                </Label>
                <Input
                  placeholder="Food Cost"
                  value={driverFormFields.foodCost}
                  onChange={(e) =>
                    setDriverFormFields((prev) => ({
                      ...prev,
                      foodCost: e.target.value,
                    }))
                  }
                />
              </div>
              <div className="space-y-1">
                <Label>
                  Driver Accomodation Cost (₹){" "}
                  <span className="text-red-500">*</span>
                </Label>
                <Input
                  placeholder="Accomodation Cost"
                  value={driverFormFields.accommodationCost}
                  onChange={(e) =>
                    setDriverFormFields((prev) => ({
                      ...prev,
                      accommodationCost: e.target.value,
                    }))
                  }
                />
              </div>
              <div className="space-y-1">
                <Label>
                  Extra Cost (₹) <span className="text-red-500">*</span>
                </Label>
                <Input
                  placeholder="Extra Cost"
                  value={driverFormFields.extraCost}
                  onChange={(e) =>
                    setDriverFormFields((prev) => ({
                      ...prev,
                      extraCost: e.target.value,
                    }))
                  }
                />
              </div>
              <div className="space-y-1">
                <Label>
                  Early Morning Charges Per Hour (Before 6 AM) (₹){" "}
                  <span className="text-red-500">*</span>
                </Label>
                <Input
                  placeholder="Early Morning Charges"
                  value={driverFormFields.morningCharges}
                  onChange={(e) =>
                    setDriverFormFields((prev) => ({
                      ...prev,
                      morningCharges: e.target.value,
                    }))
                  }
                />
              </div>
              <div className="space-y-1">
                <Label>
                  Evening Charges Per Hour (After 8 PM) (₹){" "}
                  <span className="text-red-500">*</span>
                </Label>
                <Input
                  placeholder="Evening Charges"
                  value={driverFormFields.eveningCharges}
                  onChange={(e) =>
                    setDriverFormFields((prev) => ({
                      ...prev,
                      eveningCharges: e.target.value,
                    }))
                  }
                />
              </div>
            </div>
          </div>

          <DialogFooter className="mt-6 flex justify-between gap-3">
            <Button
              type="button"
              variant="outline"
              className="bg-gray-100 text-gray-700 px-6"
              onClick={() => setShowDriverCostModal(false)}
            >
              Cancel
            </Button>
            <Button
              type="button"
              className="bg-gradient-to-r from-pink-500 to-purple-500 text-white hover:from-pink-600 hover:to-purple-600 px-8"
              onClick={handleSaveDriverCost}
            >
              Save
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* OUTSTATION KM LIMIT MODAL */}
      <Dialog
        open={showOutstationModal}
        onOpenChange={setShowOutstationModal}
      >
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle className="text-lg font-semibold text-gray-800">
              {editingOutstationRow
                ? "Update Outstation KM Limit"
                : "Add Outstation KM Limit"}
            </DialogTitle>
          </DialogHeader>

          <div className="space-y-4">
            <div className="space-y-1">
              <Label>
                Vehicle type <span className="text-red-500">*</span>
              </Label>
              <Select
                value={outstationFormVehicleType}
                onValueChange={setOutstationFormVehicleType}
              >
                <SelectTrigger className="w-full">
                  <SelectValue placeholder="Choose Vehicle Type" />
                </SelectTrigger>
                <SelectContent>
                  {VEHICLE_TYPES.map((v) => (
                    <SelectItem key={v.id} value={v.label}>
                      {v.label}
                    </SelectItem>
                  ))}
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
                value={outstationFormFields.title}
                onChange={(e) =>
                  setOutstationFormFields((prev) => ({
                    ...prev,
                    title: e.target.value,
                  }))
                }
              />
            </div>

            <div className="space-y-1">
              <Label>
                Outstation KM Limit <span className="text-red-500">*</span>
              </Label>
              <Input
                placeholder="Outstation KM Limit"
                value={outstationFormFields.limit}
                onChange={(e) =>
                  setOutstationFormFields((prev) => ({
                    ...prev,
                    limit: e.target.value,
                  }))
                }
              />
            </div>
          </div>

          <DialogFooter className="mt-6 flex justify-between gap-3">
            <Button
              type="button"
              variant="outline"
              className="bg-gray-100 text-gray-700 px-6"
              onClick={() => setShowOutstationModal(false)}
            >
              Cancel
            </Button>
            <Button
              type="button"
              className="bg-gradient-to-r from-pink-500 to-purple-500 text-white hover:from-pink-600 hover:to-purple-600 px-8"
              onClick={handleSaveOutstation}
            >
              Save
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* LOCAL KM LIMIT MODAL */}
      <Dialog open={showLocalModal} onOpenChange={setShowLocalModal}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle className="text-lg font-semibold text-gray-800">
              {editingLocalRow ? "Update Local KM Limit" : "Add Local KM Limit"}
            </DialogTitle>
          </DialogHeader>

          <div className="space-y-4">
            <div className="space-y-1">
              <Label>
                Vehicle type <span className="text-red-500">*</span>
              </Label>
              <Select
                value={localFormVehicleType}
                onValueChange={setLocalFormVehicleType}
              >
                <SelectTrigger className="w-full">
                  <SelectValue placeholder="Choose Vehicle Type" />
                </SelectTrigger>
                <SelectContent>
                  {VEHICLE_TYPES.map((v) => (
                    <SelectItem key={v.id} value={v.label}>
                      {v.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-1">
              <Label>
                Title <span className="text-red-500">*</span>
              </Label>
              <Input
                placeholder="Enter Title"
                value={localFormFields.title}
                onChange={(e) =>
                  setLocalFormFields((prev) => ({
                    ...prev,
                    title: e.target.value,
                  }))
                }
              />
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div className="space-y-1">
                <Label>
                  Hours <span className="text-red-500">*</span>
                </Label>
                <Input
                  placeholder="Enter Hours"
                  value={localFormFields.hours}
                  onChange={(e) =>
                    setLocalFormFields((prev) => ({
                      ...prev,
                      hours: e.target.value,
                    }))
                  }
                />
              </div>
              <div className="space-y-1">
                <Label>
                  Kilometer(KM) <span className="text-red-500">*</span>
                </Label>
                <Input
                  placeholder="KM Limit"
                  value={localFormFields.km}
                  onChange={(e) =>
                    setLocalFormFields((prev) => ({
                      ...prev,
                      km: e.target.value,
                    }))
                  }
                />
              </div>
            </div>
          </div>

          <DialogFooter className="mt-6 flex justify-between gap-3">
            <Button
              type="button"
              variant="outline"
              className="bg-gray-100 text-gray-700 px-6"
              onClick={() => setShowLocalModal(false)}
            >
              Cancel
            </Button>
            <Button
              type="button"
              className="bg-gradient-to-r from-pink-500 to-purple-500 text-white hover:from-pink-600 hover:to-purple-600 px-8"
              onClick={handleSaveLocal}
            >
              Save
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </Card>
  );
};
