// FILE: src/drivers/steps/DriverStepCostDetails.tsx
import React, { useRef, useState } from "react";

import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

import type { DriverCostDetails } from "@/services/drivers";

function RequiredStar() {
  return <span className="text-red-500"> *</span>;
}

function FieldError({ msg }: { msg?: string }) {
  if (!msg) return null;
  return <div className="text-xs text-red-600 mt-1">{msg}</div>;
}

export function DriverStepCostDetails({
  values,
  onChange,
  onBack,
  onUpdateContinue,
  saving,
}: {
  values: DriverCostDetails;
  onChange: (patch: Partial<DriverCostDetails>) => void;
  onBack: () => void;
  onUpdateContinue: () => void;
  saving: boolean;
}) {
  const [errors, setErrors] = useState<Record<string, string>>({});
  const refs = useRef<Record<string, HTMLInputElement | null>>({});

  function validate() {
    const e: Record<string, string> = {};
    const req = (k: keyof DriverCostDetails, label: string) => {
      const v = values[k];
      if (v === "" || v === null || v === undefined) e[String(k)] = `${label} is required`;
    };

    req("driverSalary", "Driver Salary");
    req("foodCost", "Food Cost");
    req("accommodationCost", "Accommodation Cost");
    req("bhattaCost", "Bhatta Cost");
    req("earlyMorningCharges", "Early Morning Charges");
    req("eveningCharges", "Evening Charges");

    setErrors(e);
    const firstKey = Object.keys(e)[0];
    if (firstKey) {
      refs.current[firstKey]?.focus?.();
      refs.current[firstKey]?.scrollIntoView?.({ behavior: "smooth", block: "center" });
      return false;
    }
    return true;
  }

  function handleNext() {
    if (!validate()) return;
    onUpdateContinue();
  }

  return (
    <Card className="border-0 shadow-sm">
      <CardContent className="p-6">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-x-8 gap-y-6">
          <div>
            <Label className="text-sm text-gray-700">
              Driver Salary ₹<RequiredStar />
            </Label>
            <Input
              type="number"
              className={["mt-2 h-11", errors.driverSalary ? "border-red-500" : ""].join(" ")}
              placeholder="Driver Salary"
              value={values.driverSalary}
              onChange={(e) => onChange({ driverSalary: e.target.value === "" ? "" : Number(e.target.value) })}
              ref={(el) => (refs.current.driverSalary = el)}
            />
            <FieldError msg={errors.driverSalary} />
          </div>

          <div>
            <Label className="text-sm text-gray-700">
              Food Cost ₹<RequiredStar />
            </Label>
            <Input
              type="number"
              className={["mt-2 h-11", errors.foodCost ? "border-red-500" : ""].join(" ")}
              placeholder="Food Cost"
              value={values.foodCost}
              onChange={(e) => onChange({ foodCost: e.target.value === "" ? "" : Number(e.target.value) })}
              ref={(el) => (refs.current.foodCost = el)}
            />
            <FieldError msg={errors.foodCost} />
          </div>

          <div>
            <Label className="text-sm text-gray-700">
              Accommodation Cost ₹<RequiredStar />
            </Label>
            <Input
              type="number"
              className={["mt-2 h-11", errors.accommodationCost ? "border-red-500" : ""].join(" ")}
              placeholder="Accommodation Cost"
              value={values.accommodationCost}
              onChange={(e) =>
                onChange({ accommodationCost: e.target.value === "" ? "" : Number(e.target.value) })
              }
              ref={(el) => (refs.current.accommodationCost = el)}
            />
            <FieldError msg={errors.accommodationCost} />
          </div>

          <div>
            <Label className="text-sm text-gray-700">
              Bhatta Cost ₹<RequiredStar />
            </Label>
            <Input
              type="number"
              className={["mt-2 h-11", errors.bhattaCost ? "border-red-500" : ""].join(" ")}
              placeholder="Bhatta Cost"
              value={values.bhattaCost}
              onChange={(e) => onChange({ bhattaCost: e.target.value === "" ? "" : Number(e.target.value) })}
              ref={(el) => (refs.current.bhattaCost = el)}
            />
            <FieldError msg={errors.bhattaCost} />
          </div>

          <div>
            <Label className="text-sm text-gray-700">
              Early Morning Charges(₹)(Before 6 AM)<RequiredStar />
            </Label>
            <Input
              type="number"
              className={["mt-2 h-11", errors.earlyMorningCharges ? "border-red-500" : ""].join(" ")}
              placeholder="Early Morning Charges"
              value={values.earlyMorningCharges}
              onChange={(e) =>
                onChange({ earlyMorningCharges: e.target.value === "" ? "" : Number(e.target.value) })
              }
              ref={(el) => (refs.current.earlyMorningCharges = el)}
            />
            <FieldError msg={errors.earlyMorningCharges} />
          </div>

          <div>
            <Label className="text-sm text-gray-700">
              Evening Charges (₹)(After 6 PM)<RequiredStar />
            </Label>
            <Input
              type="number"
              className={["mt-2 h-11", errors.eveningCharges ? "border-red-500" : ""].join(" ")}
              placeholder="Evening Charges"
              value={values.eveningCharges}
              onChange={(e) =>
                onChange({ eveningCharges: e.target.value === "" ? "" : Number(e.target.value) })
              }
              ref={(el) => (refs.current.eveningCharges = el)}
            />
            <FieldError msg={errors.eveningCharges} />
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
            onClick={handleNext}
            disabled={saving}
          >
            Update &amp; Continue
          </Button>
        </div>
      </CardContent>
    </Card>
  );
}
