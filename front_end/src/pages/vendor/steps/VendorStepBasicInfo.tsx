// FILE: src/pages/vendor/steps/VendorStepBasicInfo.tsx

import React from "react";
import { BasicInfoForm, Option } from "../vendorFormTypes";
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
import { Textarea } from "@/components/ui/textarea";
import { Button } from "@/components/ui/button";

type Props = {
  basicInfo: BasicInfoForm;
  setBasicInfo: React.Dispatch<React.SetStateAction<BasicInfoForm>>;
  countryOptions: Option[];
  stateOptions: Option[];
  cityOptions: Option[];
  roleOptions: Option[];
  gstTypeOptions: Option[];
  gstPercentOptions: Option[];
  saving: boolean;
  isEdit: boolean;
  onBack: () => void;
  onSaveAndNext: () => void;
};

export const VendorStepBasicInfo: React.FC<Props> = ({
  basicInfo,
  setBasicInfo,
  countryOptions,
  stateOptions,
  cityOptions,
  roleOptions,
  gstTypeOptions,
  gstPercentOptions,
  saving,
  isEdit,
  onBack,
  onSaveAndNext,
}) => {
  return (
    <Card>
      <CardHeader>
        <CardTitle className="text-pink-600">Basic Details</CardTitle>
      </CardHeader>
      <CardContent className="space-y-6">
        <div className="grid gap-4 md:grid-cols-4">
          <div>
            <Label>Vendor Name *</Label>
            <Input
              value={basicInfo.vendorName}
              onChange={(e) =>
                setBasicInfo((p) => ({ ...p, vendorName: e.target.value }))
              }
              placeholder="Vendor Name"
            />
          </div>
          <div>
            <Label>Email ID *</Label>
            <Input
              type="email"
              value={basicInfo.email}
              onChange={(e) =>
                setBasicInfo((p) => ({ ...p, email: e.target.value }))
              }
              placeholder="Email ID"
            />
          </div>
          <div>
            <Label>Primary Mobile Number *</Label>
            <Input
              value={basicInfo.primaryMobile}
              onChange={(e) =>
                setBasicInfo((p) => ({ ...p, primaryMobile: e.target.value }))
              }
              placeholder="Primary Mobile Number"
            />
          </div>
          <div>
            <Label>Alternative Mobile Number *</Label>
            <Input
              value={basicInfo.altMobile}
              onChange={(e) =>
                setBasicInfo((p) => ({ ...p, altMobile: e.target.value }))
              }
              placeholder="Alternative Mobile Number"
            />
          </div>
        </div>

        <div className="grid gap-4 md:grid-cols-4">
          <div>
            <Label>Country *</Label>
            <Select
              value={basicInfo.countryId}
              onValueChange={(val) =>
                setBasicInfo((p) => ({ ...p, countryId: val, stateId: "", cityId: "" }))
              }
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
              value={basicInfo.stateId}
              onValueChange={(val) =>
                setBasicInfo((p) => ({ ...p, stateId: val, cityId: "" }))
              }
            >
              <SelectTrigger>
                <SelectValue placeholder="Choose State" />
              </SelectTrigger>
              <SelectContent>
                {stateOptions.map((s) => (
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
              value={basicInfo.cityId}
              onValueChange={(val) =>
                setBasicInfo((p) => ({ ...p, cityId: val }))
              }
            >
              <SelectTrigger>
                <SelectValue placeholder="Choose City" />
              </SelectTrigger>
              <SelectContent>
                {cityOptions.map((c) => (
                  <SelectItem key={c.id} value={c.id}>
                    {c.label}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
          <div>
            <Label>Pincode *</Label>
            <Input
              value={basicInfo.pincode}
              onChange={(e) =>
                setBasicInfo((p) => ({ ...p, pincode: e.target.value }))
              }
              placeholder="Pincode"
            />
          </div>
        </div>

        <div className="grid gap-4 md:grid-cols-4">
          <div>
            <Label>Other Number</Label>
            <Input
              value={basicInfo.otherNumber}
              onChange={(e) =>
                setBasicInfo((p) => ({ ...p, otherNumber: e.target.value }))
              }
              placeholder="Other Number"
            />
          </div>
          <div>
            <Label>Username *</Label>
            <Input
              value={basicInfo.username}
              onChange={(e) =>
                setBasicInfo((p) => ({ ...p, username: e.target.value }))
              }
              placeholder="Username"
            />
          </div>
          <div>
            <Label>Password {isEdit ? "(leave blank to keep)" : "*"}</Label>
            <Input
              type="password"
              value={basicInfo.password}
              onChange={(e) =>
                setBasicInfo((p) => ({ ...p, password: e.target.value }))
              }
              placeholder="Password"
            />
          </div>
          <div>
            <Label>Role *</Label>
            <Select
              value={basicInfo.roleId}
              onValueChange={(val) =>
                setBasicInfo((p) => ({ ...p, roleId: val }))
              }
            >
              <SelectTrigger>
                <SelectValue placeholder="Choose Role" />
              </SelectTrigger>
              <SelectContent>
                {roleOptions.map((r) => (
                  <SelectItem key={r.id} value={r.id}>
                    {r.label}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        </div>

        <div className="grid gap-4 md:grid-cols-3">
          <div>
            <Label>Vendor Margin % *</Label>
            <Input
              value={basicInfo.marginPercent}
              onChange={(e) =>
                setBasicInfo((p) => ({ ...p, marginPercent: e.target.value }))
              }
              placeholder="Vendor Margin"
            />
          </div>
          <div>
            <Label>Vendor Margin GST Type *</Label>
            <Select
              value={basicInfo.marginGstType}
              onValueChange={(val) =>
                setBasicInfo((p) => ({ ...p, marginGstType: val }))
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
            <Label>Vendor Margin GST Percentage *</Label>
            <Select
              value={basicInfo.marginGstPercent}
              onValueChange={(val) =>
                setBasicInfo((p) => ({ ...p, marginGstPercent: val }))
              }
            >
              <SelectTrigger>
                <SelectValue placeholder="Choose GST %" />
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
        </div>

        <div>
          <Label>Address *</Label>
          <Textarea
            value={basicInfo.address}
            onChange={(e) =>
              setBasicInfo((p) => ({ ...p, address: e.target.value }))
            }
            placeholder="Address"
          />
        </div>

        <div className="border-t pt-6 mt-4">
          <h3 className="mb-4 text-lg font-semibold text-pink-600">
            Invoice Details
          </h3>

          {/* Row 1: Company Name, Address, Pincode */}
          <div className="grid gap-4 md:grid-cols-3">
            <div>
              <Label>Company Name *</Label>
              <Input
                value={basicInfo.invoiceCompanyName}
                onChange={(e) =>
                  setBasicInfo((p) => ({
                    ...p,
                    invoiceCompanyName: e.target.value,
                  }))
                }
                placeholder="Company Name"
              />
            </div>
            <div>
              <Label>Address *</Label>
              <Input
                value={basicInfo.invoiceAddress}
                onChange={(e) =>
                  setBasicInfo((p) => ({
                    ...p,
                    invoiceAddress: e.target.value,
                  }))
                }
                placeholder="Address"
              />
            </div>
            <div>
              <Label>Pincode *</Label>
              <Input
                value={basicInfo.invoicePincode}
                onChange={(e) =>
                  setBasicInfo((p) => ({
                    ...p,
                    invoicePincode: e.target.value,
                  }))
                }
                placeholder="Pincode"
              />
            </div>
          </div>

          {/* Row 2: GSTIN, PAN, Contact No. */}
          <div className="grid gap-4 md:grid-cols-3 mt-4">
            <div>
              <Label>GSTIN Number *</Label>
              <Input
                value={basicInfo.invoiceGstin}
                onChange={(e) =>
                  setBasicInfo((p) => ({
                    ...p,
                    invoiceGstin: e.target.value,
                  }))
                }
                placeholder="GSTIN FORMAT: 10AABCU9603R1Z5"
              />
            </div>
            <div>
              <Label>PAN Number *</Label>
              <Input
                value={(basicInfo as any).invoicePan ?? ""}
                onChange={(e) =>
                  setBasicInfo((p) =>
                    ({
                      ...(p as any),
                      invoicePan: e.target.value,
                    } as BasicInfoForm)
                  )
                }
                placeholder="PAN Format: CNFPC5441D"
              />
            </div>
            <div>
              <Label>Contact No. *</Label>
              <Input
                value={basicInfo.invoiceContactNo}
                onChange={(e) =>
                  setBasicInfo((p) => ({
                    ...p,
                    invoiceContactNo: e.target.value,
                  }))
                }
                placeholder="Contact No."
              />
            </div>
          </div>

          {/* Row 3: Email + Logo */}
          <div className="grid gap-4 md:grid-cols-3 mt-4">
            <div>
              <Label>Email ID *</Label>
              <Input
                type="email"
                value={basicInfo.invoiceEmail}
                onChange={(e) =>
                  setBasicInfo((p) => ({
                    ...p,
                    invoiceEmail: e.target.value,
                  }))
                }
                placeholder="Company Email ID"
              />
            </div>
            <div>
              <Label>Logo</Label>
              <Input
                type="file"
                accept="image/*"
                onChange={(e) => {
                  const file = e.target.files?.[0] ?? null;
                  setBasicInfo((p) =>
                    ({
                      ...(p as any),
                      invoiceLogoFile: file,
                    } as BasicInfoForm)
                  );
                }}
              />
              {(basicInfo as any).invoiceLogoFile && (
                <p className="mt-1 text-xs text-gray-500">
                  {(basicInfo as any).invoiceLogoFile.name}
                </p>
              )}
            </div>
          </div>
        </div>

        <div className="mt-6 flex justify-between">
          <Button variant="outline" type="button" onClick={onBack}>
            Back
          </Button>
          <Button type="button" onClick={onSaveAndNext} disabled={saving}>
            update &amp; Continue
          </Button>
        </div>
      </CardContent>
    </Card>
  );
};
