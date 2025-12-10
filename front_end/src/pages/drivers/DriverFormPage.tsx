// REPLACE-WHOLE-FILE: src/pages/drivers/DriverFormPage.tsx

import React, { useEffect, useMemo, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";

import { DriverStepBasicInfo } from "../drivers/steps/DriverStepBasicInfo";
import { DriverStepCostDetails } from "../drivers/steps/DriverStepCostDetails";
import { DriverStepUploadDocuments } from "../drivers/steps/DriverStepUploadDocuments";
import { DriverStepFeedbackReview } from "../drivers/steps/DriverStepFeedbackReview";
import { DriverStepPreview } from "../drivers/steps/DriverStepPreview";

import {
  createOrUpdateDriverBasic,
  fetchDriverLookups,
  getDriver,
  updateDriverCost,
  listDriverDocuments,
  listDriverReviews,
  type DriverBasicInfo,
  type DriverCostDetails,
  type DriverDocument,
  type DriverReview,
  type Id,
  type Option,
} from "@/services/drivers";

import { useToast } from "@/components/ui/use-toast";

const wizardSteps = [
  "Basic Info",
  "Cost Details",
  "Upload Document",
  "Feedback & Review",
  "Preview",
];

const TOTAL_STEPS = wizardSteps.length; // 5

function clampStepIndex(n: number) {
  if (!Number.isFinite(n)) return 0;
  return Math.min(TOTAL_STEPS - 1, Math.max(0, Math.floor(n)));
}

type StepperProps = {
  activeIndex: number;
  canVisit: (index: number) => boolean;
  onStepClick: (index: number) => void;
};

function DriverFormStepper({ activeIndex, canVisit, onStepClick }: StepperProps) {
  return (
    <div className="bg-white rounded-xl border px-4 py-3">
      <div className="flex flex-wrap items-center gap-3">
        {wizardSteps.map((label, idx) => {
          const active = idx === activeIndex;
          const done = idx < activeIndex;
          const enabled = canVisit(idx);

          return (
            <React.Fragment key={label}>
              <button
                type="button"
                disabled={!enabled}
                onClick={() => enabled && onStepClick(idx)}
                className={[
                  "flex items-center gap-3 px-2 py-1 rounded-md",
                  enabled
                    ? "cursor-pointer"
                    : "cursor-not-allowed opacity-60",
                ].join(" ")}
              >
                <div
                  className={[
                    "w-8 h-8 rounded-md flex items-center justify-center text-sm font-semibold",
                    active
                      ? "bg-violet-600 text-white"
                      : done
                      ? "bg-violet-100 text-violet-700"
                      : "bg-gray-100 text-gray-500",
                  ].join(" ")}
                >
                  {idx + 1}
                </div>
                <div
                  className={[
                    "text-sm font-medium",
                    active ? "text-gray-900" : "text-gray-500",
                  ].join(" ")}
                >
                  {label}
                </div>
              </button>

              {idx !== wizardSteps.length - 1 && (
                <div className="text-gray-300 px-2 select-none">{">"}</div>
              )}
            </React.Fragment>
          );
        })}
      </div>
    </div>
  );
}

const emptyBasic: DriverBasicInfo = {
  vendorId: "",
  vehicleTypeId: "",
  driverName: "",
  primaryMobile: "",
  alternativeMobile: "",
  whatsappMobile: "",
  email: "",
  licenseNumber: "",
  licenseIssueDate: "",
  licenseExpireDate: "",
  dateOfBirth: "",
  bloodGroup: "",
  gender: "",
  aadharNumber: "",
  panNumber: "",
  voterId: "",
  address: "",
  profileFile: null,
  profileUrl: "",
};

const emptyCost: DriverCostDetails = {
  driverSalary: "",
  foodCost: "",
  accommodationCost: "",
  bhattaCost: "",
  earlyMorningCharges: "",
  eveningCharges: "",
};

export default function DriverFormPage() {
  const { toast } = useToast();
  const nav = useNavigate();
  const params = useParams<{ id: string }>();

  const editingIdParam = params.id ?? null;
  const isEdit = !!editingIdParam;

  const [step, setStep] = useState<number>(0); // 0..4

  const [driverId, setDriverId] = useState<Id | null>(
    isEdit ? (editingIdParam as unknown as Id) : null
  );

  const [basic, setBasic] = useState<DriverBasicInfo>(emptyBasic);
  const [cost, setCost] = useState<DriverCostDetails>(emptyCost);

  const [docs, setDocs] = useState<DriverDocument[]>([]);
  const [reviews, setReviews] = useState<DriverReview[]>([]);

  const [vendors, setVendors] = useState<Option[]>([]);
  const [vehicleTypes, setVehicleTypes] = useState<Option[]>([]);
  const [bloodGroups, setBloodGroups] = useState<Option[]>([]);
  const [genders, setGenders] = useState<Option[]>([]);
  const [documentTypes, setDocumentTypes] = useState<Option[]>([]);

  const [loading, setLoading] = useState(true);
  const [savingBasic, setSavingBasic] = useState(false);
  const [savingCost, setSavingCost] = useState(false);

  const title = useMemo(
    () => (isEdit ? "Edit Driver" : "Add Driver"),
    [isEdit]
  );

  // In edit mode, once driverId is known, all steps should be clickable.
  // In add mode, user can only go forward step-by-step.
  const canVisit = (idx: number) => {
    if (idx === 0) return true; // Basic Info always enabled

    if (!driverId) return false; // cannot go beyond basic without a driver

    if (isEdit) return true; // edit: free navigation across all steps

    // add mode: cannot jump ahead beyond current step
    return idx <= step;
  };

  async function refreshDocsAndReviews() {
    if (!driverId) return;
    try {
      const [d, r] = await Promise.all([
        listDriverDocuments(driverId).catch(() => []),
        listDriverReviews(driverId).catch(() => []),
      ]);
      setDocs(d || []);
      setReviews(r || []);
    } catch {
      // ignore
    }
  }

  const goToStep = async (nextIdx: number) => {
    const idx = clampStepIndex(nextIdx);

    // ADD mode: keep existing behavior (don’t allow jumping forward)
    if (!isEdit && idx > step) return;

    // Safety: steps > 0 require driverId
    if (idx > 0 && !driverId) {
      toast({
        variant: "destructive",
        title: "Missing Driver",
        description: "Please complete Basic Info first.",
      });
      return;
    }

    // If going to Preview, refresh docs/reviews so it shows latest
    if (idx === 4) {
      await refreshDocsAndReviews();
    }

    setStep(idx);
  };

  async function loadAll() {
    setLoading(true);
    try {
      const lookups = await fetchDriverLookups();
      setVendors(lookups.vendors);
      setVehicleTypes(lookups.vehicleTypes);
      setBloodGroups(lookups.bloodGroups);
      setGenders(lookups.genders);
      setDocumentTypes(lookups.documentTypes);

      if (editingIdParam) {
        const d = await getDriver(editingIdParam);
        setDriverId(d.id);
        setBasic((prev) => ({ ...prev, ...(d.basicInfo || {}) }));
        setCost((prev) => ({ ...prev, ...(d.costDetails || {}) }));
        setDocs(d.documents || []);
        setReviews(d.reviews || []);
        setStep(0); // start on Basic in edit mode (like vendor)
      }
    } catch (e: any) {
      console.error("Failed to load driver form", e);
      toast({
        variant: "destructive",
        title: "Failed to load driver form",
        description: e?.message || "Please check API and try again.",
      });
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    loadAll();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  async function saveBasicAndNext() {
    setSavingBasic(true);
    try {
      const res = await createOrUpdateDriverBasic(driverId, basic);
      setDriverId(res.id);
      toast({
        title: "Saved",
        description: "Basic info saved successfully.",
      });
      await goToStep(1); // Cost Details
    } catch (e: any) {
      console.error("Failed to save basic info", e);
      toast({
        variant: "destructive",
        title: "Save failed",
        description: e?.message || "Unable to save basic info",
      });
    } finally {
      setSavingBasic(false);
    }
  }

  async function saveCostAndNext() {
    if (!driverId) {
      toast({
        variant: "destructive",
        title: "Missing Driver",
        description: "Please complete Basic Info first.",
      });
      return;
    }
    setSavingCost(true);
    try {
      await updateDriverCost(driverId, cost);
      toast({
        title: "Updated",
        description: "Cost details updated.",
      });
      await goToStep(2); // Upload Document
    } catch (e: any) {
      console.error("Failed to save cost details", e);
      toast({
        variant: "destructive",
        title: "Update failed",
        description: e?.message || "Unable to save cost details",
      });
    } finally {
      setSavingCost(false);
    }
  }

  function back() {
    if (step === 0) {
      nav("/drivers");
      return;
    }
    setStep((s) => clampStepIndex(s - 1));
  }

  async function goPreview() {
    await refreshDocsAndReviews();
    await goToStep(4);
  }

  if (loading) {
    return (
      <div className="p-6 bg-violet-50 min-h-screen">
        <div className="max-w-6xl mx-auto">
          <div className="bg-white rounded-xl border p-6 text-gray-600">
            Loading...
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="p-6 bg-violet-50 min-h-screen">
      <div className="max-w-6xl mx-auto space-y-5">
        {/* Stepper – behaves like Vendor module tabs.
            - Edit mode: can click any tab (once data loaded)
            - Add mode: can only go forward step-by-step */}
        <DriverFormStepper
          activeIndex={step}
          canVisit={canVisit}
          onStepClick={(i) => {
            void goToStep(i);
          }}
        />

        <div className="text-lg font-semibold text-gray-700">{title}</div>

        {step === 0 && (
          <DriverStepBasicInfo
            values={basic}
            onChange={(patch) =>
              setBasic((p) => ({
                ...p,
                ...patch,
              }))
            }
            vendors={vendors}
            vehicleTypes={vehicleTypes}
            bloodGroups={bloodGroups}
            genders={genders}
            onBack={back}
            onSaveContinue={saveBasicAndNext}
            saving={savingBasic}
          />
        )}

        {step === 1 && (
          <DriverStepCostDetails
            values={cost}
            onChange={(patch) =>
              setCost((p) => ({
                ...p,
                ...patch,
              }))
            }
            onBack={back}
            onUpdateContinue={saveCostAndNext}
            saving={savingCost}
          />
        )}

        {step === 2 && (
          <DriverStepUploadDocuments
            driverId={driverId}
            documentTypes={documentTypes}
            onBack={back}
            onSkipContinue={() => {
              void goToStep(3);
            }}
          />
        )}

        {step === 3 && (
          <DriverStepFeedbackReview
            driverId={driverId}
            onBack={back}
            onUpdateContinue={goPreview}
          />
        )}

        {step === 4 && (
          <DriverStepPreview
            basic={basic}
            cost={cost}
            docs={docs}
            reviews={reviews}
            vendors={vendors}
            vehicleTypes={vehicleTypes}
            onBack={back}
            onFinish={() => nav("/drivers")}
          />
        )}
      </div>
    </div>
  );
}
