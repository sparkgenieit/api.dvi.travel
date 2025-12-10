// FILE: src/drivers/steps/DriverStepPreview.tsx
import React from "react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import type { DriverBasicInfo, DriverCostDetails, DriverDocument, DriverReview, Option } from "@/services/drivers";

function val(x: any) {
  if (x === undefined || x === null || x === "") return "--";
  return String(x);
}

function findLabel(list: Option[], id: any) {
  const s = String(id ?? "");
  const found = list.find((o) => String(o.id) === s);
  return found?.label || "--";
}

export function DriverStepPreview({
  basic,
  cost,
  docs,
  reviews,
  vendors,
  vehicleTypes,
  onBack,
  onFinish,
}: {
  basic: DriverBasicInfo;
  cost: DriverCostDetails;
  docs: DriverDocument[];
  reviews: DriverReview[];
  vendors: Option[];
  vehicleTypes: Option[];
  onBack: () => void;
  onFinish: () => void;
}) {
  return (
    <Card className="border-0 shadow-sm">
      <CardContent className="p-6">
        <div className="text-3xl font-semibold text-violet-600 mb-6">
          Basic Info
        </div>

        <div className="grid grid-cols-1 md:grid-cols-4 gap-x-10 gap-y-8 text-sm">
          <div>
            <div className="text-gray-500">Vendor Name</div>
            <div className="text-gray-800 mt-1">{findLabel(vendors, basic.vendorId)}</div>
          </div>

          <div>
            <div className="text-gray-500">Vehicle Type</div>
            <div className="text-gray-800 mt-1">
              {basic.vehicleTypeId ? findLabel(vehicleTypes, basic.vehicleTypeId) : "No Vehicle Found !!!"}
            </div>
          </div>

          <div>
            <div className="text-gray-500">Driver Name</div>
            <div className="text-gray-800 mt-1">{val(basic.driverName)}</div>
          </div>

          <div>
            <div className="text-gray-500">Date Of Birth</div>
            <div className="text-gray-800 mt-1">
              {basic.dateOfBirth ? new Date(basic.dateOfBirth).toLocaleDateString() : "--"}
            </div>
          </div>

          <div>
            <div className="text-gray-500">Blood Group</div>
            <div className="text-gray-800 mt-1">{val(basic.bloodGroup)}</div>
          </div>

          <div>
            <div className="text-gray-500">Gender</div>
            <div className="text-gray-800 mt-1">{val(basic.gender)}</div>
          </div>

          <div>
            <div className="text-gray-500">Primary Mobile Number</div>
            <div className="text-gray-800 mt-1">{val(basic.primaryMobile)}</div>
          </div>

          <div>
            <div className="text-gray-500">Aadhar Card Number</div>
            <div className="text-gray-800 mt-1">{val(basic.aadharNumber)}</div>
          </div>

          <div>
            <div className="text-gray-500">Voter ID Number</div>
            <div className="text-gray-800 mt-1">{val(basic.voterId)}</div>
          </div>

          <div>
            <div className="text-gray-500">License Number</div>
            <div className="text-gray-800 mt-1">{val(basic.licenseNumber)}</div>
          </div>

          <div>
            <div className="text-gray-500">License Issue Date</div>
            <div className="text-gray-800 mt-1">
              {basic.licenseIssueDate ? new Date(basic.licenseIssueDate).toLocaleDateString() : "--"}
            </div>
          </div>

          <div>
            <div className="text-gray-500">License Expire Date</div>
            <div className="text-gray-800 mt-1">
              {basic.licenseExpireDate ? new Date(basic.licenseExpireDate).toLocaleDateString() : "--"}
            </div>
          </div>

          <div className="md:col-span-4">
            <div className="text-gray-500">Address</div>
            <div className="text-gray-800 mt-1">{val(basic.address)}</div>
          </div>
        </div>

        <div className="mt-10 text-2xl font-semibold text-gray-800">
          Cost Details
        </div>

        <div className="grid grid-cols-1 md:grid-cols-4 gap-x-10 gap-y-6 text-sm mt-4">
          <div><div className="text-gray-500">Driver Salary</div><div className="mt-1">{val(cost.driverSalary)}</div></div>
          <div><div className="text-gray-500">Food Cost</div><div className="mt-1">{val(cost.foodCost)}</div></div>
          <div><div className="text-gray-500">Accommodation Cost</div><div className="mt-1">{val(cost.accommodationCost)}</div></div>
          <div><div className="text-gray-500">Bhatta Cost</div><div className="mt-1">{val(cost.bhattaCost)}</div></div>
          <div><div className="text-gray-500">Early Morning Charges</div><div className="mt-1">{val(cost.earlyMorningCharges)}</div></div>
          <div><div className="text-gray-500">Evening Charges</div><div className="mt-1">{val(cost.eveningCharges)}</div></div>
        </div>

        <div className="mt-10 text-2xl font-semibold text-gray-800">
          Documents
        </div>

        <div className="mt-4 text-sm text-gray-700">
          {docs?.length ? (
            <ul className="list-disc pl-6 space-y-1">
              {docs.map((d) => (
                <li key={String(d.id)}>
                  {d.documentType} -{" "}
                  <a className="text-violet-600 hover:underline" href={d.fileUrl} target="_blank" rel="noreferrer">
                    {d.fileName}
                  </a>
                </li>
              ))}
            </ul>
          ) : (
            <div className="text-gray-500">--</div>
          )}
        </div>

        <div className="mt-10 text-2xl font-semibold text-gray-800">
          Feedback & Reviews
        </div>

        <div className="mt-4 text-sm text-gray-700">
          {reviews?.length ? (
            <ul className="space-y-2">
              {reviews.map((r) => (
                <li key={String(r.id)} className="border rounded-md p-3">
                  <div className="font-semibold">Rating: {r.rating}</div>
                  <div className="text-gray-700 mt-1">{r.description}</div>
                  <div className="text-xs text-gray-500 mt-2">
                    {r.createdAt ? new Date(r.createdAt).toLocaleString() : ""}
                  </div>
                </li>
              ))}
            </ul>
          ) : (
            <div className="text-gray-500">--</div>
          )}
        </div>

        <div className="mt-12 flex items-center justify-between">
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
            onClick={onFinish}
          >
            Finish
          </Button>
        </div>
      </CardContent>
    </Card>
  );
}
