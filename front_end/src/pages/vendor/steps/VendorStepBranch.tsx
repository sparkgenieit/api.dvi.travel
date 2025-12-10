// FILE: src/pages/vendor/steps/VendorStepBranch.tsx
// REPLACE-WHOLE-FILE

import React, { useState } from "react";
import { BranchForm, Option } from "../vendorFormTypes";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import { api } from "@/lib/api";

type Props = {
  branches: BranchForm[];
  setBranches: React.Dispatch<React.SetStateAction<BranchForm[]>>;
  countryOptions: Option[];
  gstTypeOptions: Option[];
  gstPercentOptions: Option[];
  saving: boolean;
  onBack: () => void;
  onSaveAndNext: () => void;
  onDeleteBranch: (index: number) => void;
};

type BranchDropdownState = {
  states: Option[];
  cities: Option[];
};

export const VendorStepBranch: React.FC<Props> = ({
  branches,
  setBranches,
  countryOptions,
  gstTypeOptions,
  gstPercentOptions,
  saving,
  onBack,
  onSaveAndNext,
  onDeleteBranch,
}) => {
  const [dropdowns, setDropdowns] = useState<Record<number, BranchDropdownState>>(
    {}
  );

  const ensureDropdownState = (index: number) => {
    if (!dropdowns[index]) {
      setDropdowns((prev) => ({
        ...prev,
        [index]: { states: [], cities: [] },
      }));
    }
  };

  const loadStates = async (index: number, countryId: string) => {
    ensureDropdownState(index);
    if (!countryId) return;
    try {
      const states = await api(`/dropdowns/states?countryId=${countryId}`);
      const options: Option[] = (states || []).map((s: any) => ({
        id: String(s.id ?? s.state_id),
        label: s.name ?? s.state_name,
      }));
      setDropdowns((prev) => ({
        ...prev,
        [index]: { ...(prev[index] || { cities: [] }), states: options },
      }));
    } catch (e) {
      console.error("Failed to load branch states", e);
    }
  };

  const loadCities = async (index: number, stateId: string) => {
    ensureDropdownState(index);
    if (!stateId) return;
    try {
      const cities = await api(`/dropdowns/cities?stateId=${stateId}`);
      const options: Option[] = (cities || []).map((c: any) => ({
        id: String(c.id ?? c.city_id),
        label: c.name ?? c.city_name,
      }));
      setDropdowns((prev) => ({
        ...prev,
        [index]: { ...(prev[index] || { states: [] }), cities: options },
      }));
    } catch (e) {
      console.error("Failed to load branch cities", e);
    }
  };

  const updateBranch = (index: number, patch: Partial<BranchForm>) => {
    setBranches((prev) => {
      const copy = [...prev];
      copy[index] = { ...copy[index], ...patch };
      return copy;
    });
  };

  return (
    <Card>
      <CardHeader className="flex items-center justify-between">
        <CardTitle className="text-pink-600">Branch Details</CardTitle>
        <Button
          type="button"
          onClick={() =>
            setBranches((prev) => [
              ...prev,
              {
                name: "",
                location: "",
                email: "",
                primaryMobile: "",
                altMobile: "",
                countryId: "",
                stateId: "",
                cityId: "",
                pincode: "",
                gstType: "included",
                gstPercent: "",
                address: "",
              },
            ])
          }
        >
          + Add Branch
        </Button>
      </CardHeader>
      <CardContent className="space-y-6">
        {branches.map((b, idx) => {
          const dd = dropdowns[idx] || { states: [], cities: [] };
          return (
            <div
              key={idx}
              className="rounded-lg border border-gray-200 p-4 space-y-4"
            >
              <div className="flex items-center justify-between">
                <h3 className="font-semibold text-purple-700">
                  Branch #{idx + 1}
                </h3>
                <Button
                  type="button"
                  variant="ghost"
                  className="text-red-500 hover:text-red-600"
                  onClick={() => onDeleteBranch(idx)}
                >
                  ✕ Delete
                </Button>
              </div>

              <div className="grid gap-4 md:grid-cols-4">
                <div>
                  <Label>Branch Name *</Label>
                  <Input
                    value={b.name}
                    onChange={(e) =>
                      updateBranch(idx, { name: e.target.value })
                    }
                    placeholder="Branch Name"
                  />
                </div>
                <div>
                  <Label>Branch Location *</Label>
                  <Input
                    value={b.location}
                    onChange={(e) =>
                      updateBranch(idx, { location: e.target.value })
                    }
                    placeholder="Branch Location"
                  />
                </div>
                <div>
                  <Label>Email ID *</Label>
                  <Input
                    value={b.email}
                    onChange={(e) =>
                      updateBranch(idx, { email: e.target.value })
                    }
                    placeholder="Email ID"
                  />
                </div>
                <div>
                  <Label>Primary Mobile Number *</Label>
                  <Input
                    value={b.primaryMobile}
                    onChange={(e) =>
                      updateBranch(idx, { primaryMobile: e.target.value })
                    }
                    placeholder="Primary Mobile Number"
                  />
                </div>
              </div>

              <div className="grid gap-4 md:grid-cols-4">
                <div>
                  <Label>Alternative Mobile Number *</Label>
                  <Input
                    value={b.altMobile}
                    onChange={(e) =>
                      updateBranch(idx, { altMobile: e.target.value })
                    }
                    placeholder="Alternative Mobile Number"
                  />
                </div>
                <div>
                  <Label>Country *</Label>
                  <Select
                    value={b.countryId}
                    onValueChange={(val) => {
                      updateBranch(idx, {
                        countryId: val,
                        stateId: "",
                        cityId: "",
                      });
                      loadStates(idx, val);
                    }}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Choose Country" />
                    </SelectTrigger>
                    <SelectContent>
                      {countryOptions.map((c) => (
                        <SelectItem key={c.id} value={c.id}>
                          {c.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label>State *</Label>
                  <Select
                    value={b.stateId}
                    onValueChange={(val) => {
                      updateBranch(idx, { stateId: val, cityId: "" });
                      loadCities(idx, val);
                    }}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Choose State" />
                    </SelectTrigger>
                    <SelectContent>
                      {dd.states.map((s) => (
                        <SelectItem key={s.id} value={s.id}>
                          {s.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label>City *</Label>
                  <Select
                    value={b.cityId}
                    onValueChange={(val) =>
                      updateBranch(idx, { cityId: val })
                    }
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Choose City" />
                    </SelectTrigger>
                    <SelectContent>
                      {dd.cities.map((c) => (
                        <SelectItem key={c.id} value={c.id}>
                          {c.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              </div>

              <div className="grid gap-4 md:grid-cols-4">
                <div>
                  <Label>Pincode *</Label>
                  <Input
                    value={b.pincode}
                    onChange={(e) =>
                      updateBranch(idx, { pincode: e.target.value })
                    }
                    placeholder="Pincode"
                  />
                </div>
                <div>
                  <Label>GST Type *</Label>
                  <Select
                    value={b.gstType}
                    onValueChange={(val) =>
                      updateBranch(idx, { gstType: val })
                    }
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Included / Excluded" />
                    </SelectTrigger>
                    <SelectContent>
                      {gstTypeOptions.map((g) => (
                        <SelectItem key={g.id} value={g.id}>
                          {g.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label>GST% *</Label>
                  <Select
                    value={b.gstPercent}
                    onValueChange={(val) =>
                      updateBranch(idx, { gstPercent: val })
                    }
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="GST%" />
                    </SelectTrigger>
                    <SelectContent>
                      {gstPercentOptions.map((g) => (
                        <SelectItem key={g.id} value={g.id}>
                          {g.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label>Address *</Label>
                  <Input
                    value={b.address}
                    onChange={(e) =>
                      updateBranch(idx, { address: e.target.value })
                    }
                    placeholder="Address"
                  />
                </div>
              </div>
            </div>
          );
        })}

        <div className="mt-6 flex justify-between">
          <Button variant="outline" type="button" onClick={onBack}>
            Back
          </Button>
          <Button type="button" onClick={onSaveAndNext} disabled={saving}>
            Save &amp; Continue
          </Button>
        </div>
      </CardContent>
    </Card>
  );
};
