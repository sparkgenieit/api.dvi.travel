// FILE: src/pages/guide/GuideFormPage.tsx

import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { ChevronRight, Eye, EyeOff, Star, Pencil, Trash2 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Checkbox } from "@/components/ui/checkbox";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { toast } from "sonner";
import { cn } from "@/lib/utils";
import { GuideAPI } from "@/services/guideService";
import type {
  Guide,
  GuideBankDetails,
  GuidePreferredFor,
  GuidePricebook,
  GuideReview,
} from "@/types/guide";
import {
  BLOOD_GROUPS,
  GENDERS,
  ROLES,
  LANGUAGES,
  GST_TYPES,
  GST_PERCENTAGES,
  COUNTRIES,
  STATES,
  CITIES,
  GUIDE_SLOTS,
} from "@/types/guide";

const STEPS = [
  { id: 1, label: "Guide Basic Info" },
  { id: 2, label: "Pricebook" },
  { id: 3, label: "FeedBack & Review" },
  { id: 4, label: "Guide Preview" },
];

const defaultBankDetails: GuideBankDetails = {
  bankName: "",
  branchName: "",
  ifscCode: "",
  accountNumber: "",
  confirmAccountNumber: "",
};

const defaultPreferredFor: GuidePreferredFor = {
  hotspot: false,
  activity: false,
  itinerary: false,
};

const defaultPricebook: GuidePricebook = {
  startDate: "",
  endDate: "",
  pax1to5: { slot1: 0, slot2: 0, slot3: 0 },
  pax6to14: { slot1: 0, slot2: 0, slot3: 0 },
  pax15to40: { slot1: 0, slot2: 0, slot3: 0 },
};

export default function GuideFormPage() {
  const navigate = useNavigate();
  const { id } = useParams<{ id: string }>();
  const isEdit = Boolean(id);

  const [currentStep, setCurrentStep] = useState(1);
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  // Basic Info state
  const [name, setName] = useState("");
  const [dateOfBirth, setDateOfBirth] = useState("");
  const [bloodGroup, setBloodGroup] = useState("");
  const [gender, setGender] = useState("");
  const [primaryMobile, setPrimaryMobile] = useState("");
  const [alternativeMobile, setAlternativeMobile] = useState("");
  const [email, setEmail] = useState("");
  const [emergencyMobile, setEmergencyMobile] = useState("");
  const [password, setPassword] = useState("");
  const [role, setRole] = useState("");
  const [experience, setExperience] = useState<number>(0);
  const [aadharCardNo, setAadharCardNo] = useState("");
  const [languageProficiency, setLanguageProficiency] = useState("");
  const [country, setCountry] = useState("");
  const [state, setState] = useState("");
  const [city, setCity] = useState("");
  const [gstType, setGstType] = useState("");
  const [gstPercentage, setGstPercentage] = useState("");
  const [availableSlots, setAvailableSlots] = useState<string[]>([]);
  const [bankDetails, setBankDetails] = useState<GuideBankDetails>(defaultBankDetails);
  const [preferredFor, setPreferredFor] = useState<GuidePreferredFor>(defaultPreferredFor);

  // Pricebook state
  const [pricebook, setPricebook] = useState<GuidePricebook>(defaultPricebook);

  // Reviews state
  const [reviews, setReviews] = useState<GuideReview[]>([]);
  const [newRating, setNewRating] = useState<number>(0);
  const [newFeedback, setNewFeedback] = useState("");

  // Load guide if editing
  useEffect(() => {
    if (isEdit && id) {
      (async () => {
        setLoading(true);
        try {
          const guide = await GuideAPI.get(Number(id));
          if (guide) {
            setName(guide.name);
            setDateOfBirth(guide.dateOfBirth);
            setBloodGroup(guide.bloodGroup);
            setGender(guide.gender);
            setPrimaryMobile(guide.primaryMobile);
            setAlternativeMobile(guide.alternativeMobile);
            setEmail(guide.email);
            setEmergencyMobile(guide.emergencyMobile);
            setPassword(guide.password);
            setRole(guide.role);
            setExperience(guide.experience);
            setAadharCardNo(guide.aadharCardNo);
            setLanguageProficiency(guide.languageProficiency);
            setCountry(guide.country);
            setState(guide.state);
            setCity(guide.city);
            setGstType(guide.gstType);
            setGstPercentage(guide.gstPercentage);
            setAvailableSlots(guide.availableSlots);
            setBankDetails(guide.bankDetails);
            setPreferredFor(guide.preferredFor);
            setPricebook(guide.pricebook);
            setReviews(guide.reviews);
          }
        } catch {
          toast.error("Failed to load guide");
        } finally {
          setLoading(false);
        }
      })();
    }
  }, [id, isEdit]);

  const handleSlotToggle = (slotId: string) => {
    setAvailableSlots((prev) =>
      prev.includes(slotId) ? prev.filter((s) => s !== slotId) : [...prev, slotId]
    );
  };

  const handleSaveBasicInfo = async () => {
    if (!name || !primaryMobile || !email) {
      toast.error("Please fill required fields");
      return;
    }

    setLoading(true);
    try {
      const guideData = {
        name,
        dateOfBirth,
        bloodGroup,
        gender,
        primaryMobile,
        alternativeMobile,
        email,
        emergencyMobile,
        password,
        role,
        experience,
        aadharCardNo,
        languageProficiency,
        country,
        state,
        city,
        gstType,
        gstPercentage,
        availableSlots,
        bankDetails,
        preferredFor,
        pricebook,
        reviews,
        status: 1 as const,
      };

      if (isEdit && id) {
        await GuideAPI.update(Number(id), guideData);
        toast.success("Guide updated successfully");
      } else {
        const created = await GuideAPI.create(guideData);
        // Navigate to edit mode
        navigate(`/guide/${created.id}/edit`, { replace: true });
        toast.success("Guide created successfully");
      }
      setCurrentStep(2);
    } catch {
      toast.error("Failed to save guide");
    } finally {
      setLoading(false);
    }
  };

  const handleUpdatePricebook = async () => {
    if (!id) {
      toast.error("Please save basic info first");
      return;
    }
    setLoading(true);
    try {
      await GuideAPI.updatePricebook(Number(id), pricebook);
      toast.success("Pricebook updated successfully");
      setCurrentStep(3);
    } catch {
      toast.error("Failed to update pricebook");
    } finally {
      setLoading(false);
    }
  };

  const handleAddReview = async () => {
    if (!newRating || !newFeedback.trim()) {
      toast.error("Please select rating and enter feedback");
      return;
    }
    if (!id) {
      toast.error("Please save guide first");
      return;
    }

    try {
      const now = new Date();
      const createdOn = now.toLocaleString("en-GB", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
      });

      const review = await GuideAPI.addReview(Number(id), {
        rating: newRating,
        description: newFeedback,
        createdOn,
      });
      setReviews((prev) => [...prev, review]);
      setNewRating(0);
      setNewFeedback("");
      toast.success("Feedback Details Created Successfully");
    } catch {
      toast.error("Failed to add review");
    }
  };

  const handleDeleteReview = async (reviewId: string) => {
    if (!id) return;
    try {
      await GuideAPI.deleteReview(Number(id), reviewId);
      setReviews((prev) => prev.filter((r) => r.id !== reviewId));
      toast.success("Review deleted");
    } catch {
      toast.error("Failed to delete review");
    }
  };

  const handleConfirm = async () => {
    toast.success("Guide saved successfully");
    navigate("/guide");
  };

  const stateOptions = country ? STATES[country] || [] : [];
  const cityOptions = state ? CITIES[state] || [] : [];

  const renderStars = (count: number) => {
    return Array.from({ length: 5 }, (_, i) => (
      <Star
        key={i}
        className={cn(
          "h-4 w-4",
          i < count ? "fill-purple-500 text-purple-500" : "text-gray-300"
        )}
      />
    ));
  };

  if (loading && isEdit && currentStep === 1) {
    return (
      <div className="p-6">
        <p>Loading...</p>
      </div>
    );
  }

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-primary">
          {isEdit ? `Edit Guide » ${name}` : "Add Guide"}
        </h1>
        <div className="text-sm text-muted-foreground">
          Dashboard &gt; Guide &gt; {isEdit ? "Edit Guide" : "Add Guide"}
        </div>
      </div>

      {/* Card */}
      <div className="bg-white rounded-lg border shadow-sm">
        {/* Tabs */}
        <div className="flex items-center gap-2 p-4 border-b overflow-x-auto">
          {STEPS.map((step, idx) => (
            <div key={step.id} className="flex items-center">
              <button
                type="button"
                onClick={() => setCurrentStep(step.id)}
                disabled={!isEdit && step.id > 1}
                className={cn(
                  "flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-colors",
                  currentStep === step.id
                    ? "bg-purple-600 text-white"
                    : "text-gray-500 hover:bg-gray-100",
                  !isEdit && step.id > 1 && "opacity-50 cursor-not-allowed"
                )}
              >
                <span
                  className={cn(
                    "w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold",
                    currentStep === step.id
                      ? "bg-white text-purple-600"
                      : "bg-gray-200 text-gray-600"
                  )}
                >
                  {step.id}
                </span>
                <span className="whitespace-nowrap">{step.label}</span>
              </button>
              {idx < STEPS.length - 1 && (
                <ChevronRight className="h-4 w-4 text-gray-400 mx-1" />
              )}
            </div>
          ))}
        </div>

        {/* Content */}
        <div className="p-6">
          {/* STEP 1: Basic Info */}
          {currentStep === 1 && (
            <div className="space-y-8">
              {/* Basic Info Fields */}
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <Label>Guide Name *</Label>
                  <Input value={name} onChange={(e) => setName(e.target.value)} />
                </div>
                <div>
                  <Label>Date of Birth</Label>
                  <Input
                    type="date"
                    value={dateOfBirth}
                    onChange={(e) => setDateOfBirth(e.target.value)}
                  />
                </div>
                <div>
                  <Label>Blood Group *</Label>
                  <Select value={bloodGroup} onValueChange={setBloodGroup}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select Blood Group" />
                    </SelectTrigger>
                    <SelectContent>
                      {BLOOD_GROUPS.map((bg) => (
                        <SelectItem key={bg} value={bg}>
                          {bg}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>

                <div>
                  <Label>Gender *</Label>
                  <Select value={gender} onValueChange={setGender}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select Gender" />
                    </SelectTrigger>
                    <SelectContent>
                      {GENDERS.map((g) => (
                        <SelectItem key={g} value={g}>
                          {g}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label>Primary Mobile Number *</Label>
                  <Input
                    value={primaryMobile}
                    onChange={(e) => setPrimaryMobile(e.target.value)}
                  />
                </div>
                <div>
                  <Label>Alternative Mobile Number</Label>
                  <Input
                    value={alternativeMobile}
                    onChange={(e) => setAlternativeMobile(e.target.value)}
                  />
                </div>

                <div>
                  <Label>Email ID *</Label>
                  <Input
                    type="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                  />
                </div>
                <div>
                  <Label>Emergency Mobile Number</Label>
                  <Input
                    value={emergencyMobile}
                    onChange={(e) => setEmergencyMobile(e.target.value)}
                  />
                </div>
                <div>
                  <Label>Password *</Label>
                  <div className="relative">
                    <Input
                      type={showPassword ? "text" : "password"}
                      value={password}
                      onChange={(e) => setPassword(e.target.value)}
                    />
                    <button
                      type="button"
                      className="absolute right-3 top-1/2 -translate-y-1/2"
                      onClick={() => setShowPassword(!showPassword)}
                    >
                      {showPassword ? (
                        <EyeOff className="h-4 w-4 text-gray-500" />
                      ) : (
                        <Eye className="h-4 w-4 text-gray-500" />
                      )}
                    </button>
                  </div>
                </div>

                <div>
                  <Label>Role *</Label>
                  <Select value={role} onValueChange={setRole}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select Role" />
                    </SelectTrigger>
                    <SelectContent>
                      {ROLES.map((r) => (
                        <SelectItem key={r} value={r}>
                          {r}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label>Aadhar Card No</Label>
                  <Input
                    value={aadharCardNo}
                    onChange={(e) => setAadharCardNo(e.target.value)}
                  />
                </div>
                <div>
                  <Label>Language Proficiency *</Label>
                  <Select value={languageProficiency} onValueChange={setLanguageProficiency}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select Language" />
                    </SelectTrigger>
                    <SelectContent>
                      {LANGUAGES.map((l) => (
                        <SelectItem key={l} value={l}>
                          {l}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>

                <div>
                  <Label>Experience</Label>
                  <Input
                    type="number"
                    value={experience}
                    onChange={(e) => setExperience(Number(e.target.value))}
                  />
                </div>
                <div>
                  <Label>Country</Label>
                  <Select value={country} onValueChange={(v) => { setCountry(v); setState(""); setCity(""); }}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select Country" />
                    </SelectTrigger>
                    <SelectContent>
                      {COUNTRIES.map((c) => (
                        <SelectItem key={c} value={c}>
                          {c}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label>State</Label>
                  <Select value={state} onValueChange={(v) => { setState(v); setCity(""); }}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select State" />
                    </SelectTrigger>
                    <SelectContent>
                      {stateOptions.map((s) => (
                        <SelectItem key={s} value={s}>
                          {s}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>

                <div>
                  <Label>City</Label>
                  <Select value={city} onValueChange={setCity}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select City" />
                    </SelectTrigger>
                    <SelectContent>
                      {cityOptions.map((c) => (
                        <SelectItem key={c} value={c}>
                          {c}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label>GST Type *</Label>
                  <Select value={gstType} onValueChange={setGstType}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select GST Type" />
                    </SelectTrigger>
                    <SelectContent>
                      {GST_TYPES.map((g) => (
                        <SelectItem key={g} value={g}>
                          {g}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label>GST% *</Label>
                  <Select value={gstPercentage} onValueChange={setGstPercentage}>
                    <SelectTrigger>
                      <SelectValue placeholder="Select GST%" />
                    </SelectTrigger>
                    <SelectContent>
                      {GST_PERCENTAGES.map((g) => (
                        <SelectItem key={g} value={g}>
                          {g}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              </div>

              {/* Available Slots */}
              <div>
                <Label className="mb-2 block">Guide Available Slots *</Label>
                <div className="border rounded-lg p-4 flex flex-wrap gap-2">
                  {GUIDE_SLOTS.map((slot) => (
                    <button
                      key={slot.id}
                      type="button"
                      onClick={() => handleSlotToggle(slot.id)}
                      className={cn(
                        "px-3 py-1 rounded-full text-sm border transition-colors",
                        availableSlots.includes(slot.id)
                          ? "bg-gray-100 border-gray-400"
                          : "bg-white border-gray-200 hover:bg-gray-50"
                      )}
                    >
                      {slot.label}
                    </button>
                  ))}
                </div>
              </div>

              {/* Divider with star */}
              <div className="flex items-center justify-center">
                <div className="flex-1 border-t border-dashed border-gray-300" />
                <Star className="mx-4 h-5 w-5 text-gray-300" />
                <div className="flex-1 border-t border-dashed border-gray-300" />
              </div>

              {/* Bank Details */}
              <div>
                <h3 className="text-lg font-semibold text-pink-500 mb-4">Bank Details</h3>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <Label>Bank Name</Label>
                    <Input
                      value={bankDetails.bankName}
                      onChange={(e) =>
                        setBankDetails((prev) => ({ ...prev, bankName: e.target.value }))
                      }
                    />
                  </div>
                  <div>
                    <Label>Branch Name</Label>
                    <Input
                      value={bankDetails.branchName}
                      onChange={(e) =>
                        setBankDetails((prev) => ({ ...prev, branchName: e.target.value }))
                      }
                    />
                  </div>
                  <div>
                    <Label>IFSC Code</Label>
                    <Input
                      value={bankDetails.ifscCode}
                      onChange={(e) =>
                        setBankDetails((prev) => ({ ...prev, ifscCode: e.target.value }))
                      }
                    />
                  </div>
                  <div>
                    <Label>Account Number</Label>
                    <Input
                      value={bankDetails.accountNumber}
                      onChange={(e) =>
                        setBankDetails((prev) => ({ ...prev, accountNumber: e.target.value }))
                      }
                    />
                  </div>
                  <div>
                    <Label>Confirm Account Number</Label>
                    <Input
                      value={bankDetails.confirmAccountNumber}
                      onChange={(e) =>
                        setBankDetails((prev) => ({
                          ...prev,
                          confirmAccountNumber: e.target.value,
                        }))
                      }
                    />
                  </div>
                </div>
              </div>

              {/* Divider with star */}
              <div className="flex items-center justify-center">
                <div className="flex-1 border-t border-dashed border-gray-300" />
                <Star className="mx-4 h-5 w-5 text-gray-300" />
                <div className="flex-1 border-t border-dashed border-gray-300" />
              </div>

              {/* Guide Preferred For */}
              <div>
                <h3 className="text-lg font-semibold text-pink-500 mb-4">Guide Prefered For</h3>
                <div className="flex items-center gap-6">
                  <div className="flex items-center gap-2">
                    <Checkbox
                      id="hotspot"
                      checked={preferredFor.hotspot}
                      onCheckedChange={(v) =>
                        setPreferredFor((prev) => ({ ...prev, hotspot: Boolean(v) }))
                      }
                    />
                    <Label htmlFor="hotspot">Hotspot</Label>
                  </div>
                  <div className="flex items-center gap-2">
                    <Checkbox
                      id="activity"
                      checked={preferredFor.activity}
                      onCheckedChange={(v) =>
                        setPreferredFor((prev) => ({ ...prev, activity: Boolean(v) }))
                      }
                    />
                    <Label htmlFor="activity">Activity</Label>
                  </div>
                  <div className="flex items-center gap-2">
                    <Checkbox
                      id="itinerary"
                      checked={preferredFor.itinerary}
                      onCheckedChange={(v) =>
                        setPreferredFor((prev) => ({ ...prev, itinerary: Boolean(v) }))
                      }
                    />
                    <Label htmlFor="itinerary">Itinerary</Label>
                  </div>
                </div>

                <div className="mt-4 bg-pink-50 border border-pink-200 rounded-lg p-4 text-pink-600 text-sm">
                  From the beginning to the end of each day, the itinerary and all the hotspots
                  serve as a guide for the entire journey.
                </div>
              </div>

              {/* Buttons */}
              <div className="flex justify-between pt-4">
                <Button variant="secondary" onClick={() => navigate("/guide")}>
                  Back
                </Button>
                <Button
                  onClick={handleSaveBasicInfo}
                  disabled={loading}
                  className="bg-gradient-to-r from-primary to-pink-500"
                >
                  {loading ? "Saving..." : "Update & Continue"}
                </Button>
              </div>
            </div>
          )}

          {/* STEP 2: Pricebook */}
          {currentStep === 2 && (
            <div className="space-y-6">
              <div className="flex items-center justify-between">
                <h3 className="text-lg font-semibold">Guide Cost Details</h3>
                <div className="flex items-center gap-3">
                  <Input
                    type="date"
                    placeholder="Start Date"
                    value={pricebook.startDate}
                    onChange={(e) =>
                      setPricebook((prev) => ({ ...prev, startDate: e.target.value }))
                    }
                    className="w-36"
                  />
                  <Input
                    type="date"
                    placeholder="End date"
                    value={pricebook.endDate}
                    onChange={(e) =>
                      setPricebook((prev) => ({ ...prev, endDate: e.target.value }))
                    }
                    className="w-36"
                  />
                  <Button
                    onClick={handleUpdatePricebook}
                    disabled={loading}
                    className="bg-gradient-to-r from-primary to-pink-500"
                  >
                    Update
                  </Button>
                </div>
              </div>

              {/* Price Grid */}
              <div className="space-y-6">
                {/* 1-5 Pax */}
                <div className="grid grid-cols-4 gap-4 items-end">
                  <div>
                    <p className="text-sm text-gray-500">Pax Count</p>
                    <p className="font-semibold">1-5 Pax</p>
                  </div>
                  <div>
                    <p className="text-sm text-pink-500">Slot 1: 9 AM to 1 PM</p>
                    <Input
                      placeholder="Enter Price"
                      type="number"
                      value={pricebook.pax1to5.slot1 || ""}
                      onChange={(e) =>
                        setPricebook((prev) => ({
                          ...prev,
                          pax1to5: { ...prev.pax1to5, slot1: Number(e.target.value) },
                        }))
                      }
                    />
                  </div>
                  <div>
                    <p className="text-sm text-pink-500">Slot 2: 9 AM to 4 PM</p>
                    <Input
                      placeholder="Enter Price"
                      type="number"
                      value={pricebook.pax1to5.slot2 || ""}
                      onChange={(e) =>
                        setPricebook((prev) => ({
                          ...prev,
                          pax1to5: { ...prev.pax1to5, slot2: Number(e.target.value) },
                        }))
                      }
                    />
                  </div>
                  <div>
                    <p className="text-sm text-pink-500">Slot 3: 6 PM to 9 PM</p>
                    <Input
                      placeholder="Enter Price"
                      type="number"
                      value={pricebook.pax1to5.slot3 || ""}
                      onChange={(e) =>
                        setPricebook((prev) => ({
                          ...prev,
                          pax1to5: { ...prev.pax1to5, slot3: Number(e.target.value) },
                        }))
                      }
                    />
                  </div>
                </div>

                {/* 6-14 Pax */}
                <div className="grid grid-cols-4 gap-4 items-end">
                  <div>
                    <p className="text-sm text-gray-500">Pax Count</p>
                    <p className="font-semibold">6-14 Pax</p>
                  </div>
                  <div>
                    <p className="text-sm text-pink-500">Slot 1: 9 AM to 1 PM</p>
                    <Input
                      placeholder="Enter Price"
                      type="number"
                      value={pricebook.pax6to14.slot1 || ""}
                      onChange={(e) =>
                        setPricebook((prev) => ({
                          ...prev,
                          pax6to14: { ...prev.pax6to14, slot1: Number(e.target.value) },
                        }))
                      }
                    />
                  </div>
                  <div>
                    <p className="text-sm text-pink-500">Slot 2: 9 AM to 4 PM</p>
                    <Input
                      placeholder="Enter Price"
                      type="number"
                      value={pricebook.pax6to14.slot2 || ""}
                      onChange={(e) =>
                        setPricebook((prev) => ({
                          ...prev,
                          pax6to14: { ...prev.pax6to14, slot2: Number(e.target.value) },
                        }))
                      }
                    />
                  </div>
                  <div>
                    <p className="text-sm text-pink-500">Slot 3: 6 PM to 9 PM</p>
                    <Input
                      placeholder="Enter Price"
                      type="number"
                      value={pricebook.pax6to14.slot3 || ""}
                      onChange={(e) =>
                        setPricebook((prev) => ({
                          ...prev,
                          pax6to14: { ...prev.pax6to14, slot3: Number(e.target.value) },
                        }))
                      }
                    />
                  </div>
                </div>

                {/* 15-40 Pax */}
                <div className="grid grid-cols-4 gap-4 items-end">
                  <div>
                    <p className="text-sm text-gray-500">Pax Count</p>
                    <p className="font-semibold">15-40 Pax</p>
                  </div>
                  <div>
                    <p className="text-sm text-pink-500">Slot 1: 9 AM to 1 PM</p>
                    <Input
                      placeholder="Enter Price"
                      type="number"
                      value={pricebook.pax15to40.slot1 || ""}
                      onChange={(e) =>
                        setPricebook((prev) => ({
                          ...prev,
                          pax15to40: { ...prev.pax15to40, slot1: Number(e.target.value) },
                        }))
                      }
                    />
                  </div>
                  <div>
                    <p className="text-sm text-pink-500">Slot 2: 9 AM to 4 PM</p>
                    <Input
                      placeholder="Enter Price"
                      type="number"
                      value={pricebook.pax15to40.slot2 || ""}
                      onChange={(e) =>
                        setPricebook((prev) => ({
                          ...prev,
                          pax15to40: { ...prev.pax15to40, slot2: Number(e.target.value) },
                        }))
                      }
                    />
                  </div>
                  <div>
                    <p className="text-sm text-pink-500">Slot 3: 6 PM to 9 PM</p>
                    <Input
                      placeholder="Enter Price"
                      type="number"
                      value={pricebook.pax15to40.slot3 || ""}
                      onChange={(e) =>
                        setPricebook((prev) => ({
                          ...prev,
                          pax15to40: { ...prev.pax15to40, slot3: Number(e.target.value) },
                        }))
                      }
                    />
                  </div>
                </div>
              </div>

              <div className="flex justify-between pt-4">
                <Button variant="secondary" onClick={() => setCurrentStep(1)}>
                  Back
                </Button>
                <Button
                  onClick={handleUpdatePricebook}
                  disabled={loading}
                  className="bg-gradient-to-r from-primary to-pink-500"
                >
                  {loading ? "Saving..." : "Update & Continue"}
                </Button>
              </div>
            </div>
          )}

          {/* STEP 3: Feedback & Review */}
          {currentStep === 3 && (
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
              {/* Left: Add Review */}
              <div className="bg-white border rounded-lg p-6 space-y-4">
                <h3 className="text-lg font-semibold text-pink-500">Rating</h3>
                <Select value={String(newRating)} onValueChange={(v) => setNewRating(Number(v))}>
                  <SelectTrigger>
                    <SelectValue placeholder="Select Rating" />
                  </SelectTrigger>
                  <SelectContent>
                    {[1, 2, 3, 4, 5].map((r) => (
                      <SelectItem key={r} value={String(r)}>
                        {r} Star{r > 1 ? "s" : ""}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>

                <p className="text-sm text-gray-500">All reviews are from genuine customers</p>

                <div>
                  <Label>Feedback *</Label>
                  <textarea
                    className="w-full border rounded-lg p-3 min-h-[120px]"
                    value={newFeedback}
                    onChange={(e) => setNewFeedback(e.target.value)}
                    placeholder="Enter feedback..."
                  />
                </div>

                <Button
                  onClick={handleAddReview}
                  className="bg-gradient-to-r from-primary to-pink-500"
                >
                  Save
                </Button>
              </div>

              {/* Right: Reviews List */}
              <div className="bg-white border rounded-lg p-6 space-y-4">
                <h3 className="text-lg font-semibold">List of Reviews</h3>

                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>S.NO</TableHead>
                      <TableHead>RATING</TableHead>
                      <TableHead>DESCRIPTION</TableHead>
                      <TableHead>CREATED ON</TableHead>
                      <TableHead>ACTION</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {reviews.length === 0 ? (
                      <TableRow>
                        <TableCell colSpan={5} className="text-center">
                          No reviews yet
                        </TableCell>
                      </TableRow>
                    ) : (
                      reviews.map((review, idx) => (
                        <TableRow key={review.id}>
                          <TableCell>{idx + 1}</TableCell>
                          <TableCell>
                            <div className="flex">{renderStars(review.rating)}</div>
                          </TableCell>
                          <TableCell>{review.description}</TableCell>
                          <TableCell>{review.createdOn}</TableCell>
                          <TableCell>
                            <div className="flex gap-1">
                              <Button size="sm" variant="ghost">
                                <Pencil className="h-4 w-4" />
                              </Button>
                              <Button
                                size="sm"
                                variant="ghost"
                                onClick={() => handleDeleteReview(review.id)}
                              >
                                <Trash2 className="h-4 w-4 text-red-600" />
                              </Button>
                            </div>
                          </TableCell>
                        </TableRow>
                      ))
                    )}
                  </TableBody>
                </Table>
              </div>

              {/* Buttons */}
              <div className="col-span-full flex justify-between pt-4">
                <Button variant="secondary" onClick={() => setCurrentStep(2)}>
                  Back
                </Button>
                <Button
                  onClick={() => setCurrentStep(4)}
                  className="bg-gradient-to-r from-primary to-pink-500"
                >
                  Skip and Continue
                </Button>
              </div>
            </div>
          )}

          {/* STEP 4: Preview */}
          {currentStep === 4 && (
            <div className="space-y-8">
              {/* Basic Info Preview */}
              <div>
                <h3 className="text-lg font-semibold text-pink-500 mb-4">Basic Info</h3>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                  <div>
                    <p className="text-gray-500">Guide Name</p>
                    <p className="font-medium">{name}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Date of Birth</p>
                    <p className="font-medium">{dateOfBirth || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Blood Group</p>
                    <p className="font-medium">{bloodGroup || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Gender</p>
                    <p className="font-medium">{gender || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Primary Mobile Number</p>
                    <p className="font-medium">{primaryMobile}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Alternative Mobile Number</p>
                    <p className="font-medium">{alternativeMobile || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Email ID</p>
                    <p className="font-medium">{email}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Emergency Mobile Number</p>
                    <p className="font-medium">{emergencyMobile || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Aadhar Card Number</p>
                    <p className="font-medium">{aadharCardNo || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Language Preference</p>
                    <p className="font-medium">{languageProficiency || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Experience</p>
                    <p className="font-medium">{experience}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Country</p>
                    <p className="font-medium">{country || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">State</p>
                    <p className="font-medium">{state || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">City</p>
                    <p className="font-medium">{city || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">GST%</p>
                    <p className="font-medium">{gstPercentage ? gstPercentage.split(" ")[0] : "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Guide Available Slots</p>
                    <p className="font-medium">
                      {availableSlots
                        .map((s) => GUIDE_SLOTS.find((slot) => slot.id === s)?.label)
                        .filter(Boolean)
                        .join(", ") || "-"}
                    </p>
                  </div>
                </div>
              </div>

              {/* Divider */}
              <div className="flex items-center justify-center">
                <div className="flex-1 border-t border-dashed border-gray-300" />
                <Star className="mx-4 h-5 w-5 text-gray-300" />
                <div className="flex-1 border-t border-dashed border-gray-300" />
              </div>

              {/* Bank Details Preview */}
              <div>
                <h3 className="text-lg font-semibold text-pink-500 mb-4">Bank Details</h3>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                  <div>
                    <p className="text-gray-500">Bank Name</p>
                    <p className="font-medium">{bankDetails.bankName || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Branch Name</p>
                    <p className="font-medium">{bankDetails.branchName || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">IFSC Code</p>
                    <p className="font-medium">{bankDetails.ifscCode || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Account Number</p>
                    <p className="font-medium">{bankDetails.accountNumber || "-"}</p>
                  </div>
                  <div>
                    <p className="text-gray-500">Confirm Account Number</p>
                    <p className="font-medium">{bankDetails.confirmAccountNumber || "-"}</p>
                  </div>
                </div>
              </div>

              {/* Divider */}
              <div className="flex items-center justify-center">
                <div className="flex-1 border-t border-dashed border-gray-300" />
                <Star className="mx-4 h-5 w-5 text-gray-300" />
                <div className="flex-1 border-t border-dashed border-gray-300" />
              </div>

              {/* Preferred For Preview */}
              <div>
                <h3 className="text-lg font-semibold text-pink-500 mb-4">Guide Prefered For</h3>
                <div className="flex items-center gap-6">
                  {preferredFor.hotspot && (
                    <div className="flex items-center gap-2">
                      <Checkbox checked disabled />
                      <span>Hotspot</span>
                    </div>
                  )}
                  {preferredFor.activity && (
                    <div className="flex items-center gap-2">
                      <Checkbox checked disabled />
                      <span>Activity</span>
                    </div>
                  )}
                  {preferredFor.itinerary && (
                    <div className="flex items-center gap-2">
                      <Checkbox checked disabled />
                      <span>Itinerary</span>
                    </div>
                  )}
                </div>

                <div className="mt-4 bg-pink-50 border border-pink-200 rounded-lg p-4 text-pink-600 text-sm">
                  From the beginning to the end of each day, the itinerary and all the hotspots
                  serve as a guide for the entire journey.
                </div>
              </div>

              {/* Divider */}
              <div className="flex items-center justify-center">
                <div className="flex-1 border-t border-dashed border-gray-300" />
                <Star className="mx-4 h-5 w-5 text-gray-300" />
                <div className="flex-1 border-t border-dashed border-gray-300" />
              </div>

              {/* Reviews Preview */}
              <div>
                <h3 className="text-lg font-semibold text-pink-500 mb-4">Feedback & Review</h3>
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>S.NO</TableHead>
                      <TableHead>RATING</TableHead>
                      <TableHead>DESCRIPTION</TableHead>
                      <TableHead>CREATED ON</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {reviews.length === 0 ? (
                      <TableRow>
                        <TableCell colSpan={4} className="text-center">
                          No reviews
                        </TableCell>
                      </TableRow>
                    ) : (
                      reviews.map((review, idx) => (
                        <TableRow key={review.id}>
                          <TableCell>{idx + 1}</TableCell>
                          <TableCell>{review.rating} STARS</TableCell>
                          <TableCell>{review.description}</TableCell>
                          <TableCell>{review.createdOn}</TableCell>
                        </TableRow>
                      ))
                    )}
                  </TableBody>
                </Table>
              </div>

              {/* Buttons */}
              <div className="flex justify-between pt-4">
                <Button variant="secondary" onClick={() => setCurrentStep(3)}>
                  Back
                </Button>
                <Button
                  onClick={handleConfirm}
                  className="bg-gradient-to-r from-primary to-pink-500"
                >
                  Confirm
                </Button>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
