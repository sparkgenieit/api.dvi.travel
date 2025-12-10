// FILE: src/pages/daily-moment-tracker/DailyMomentDayView.tsx

import React, { useEffect, useMemo, useState } from "react";
import { useLocation, useNavigate, useParams } from "react-router-dom";
import { ArrowLeft, CarIcon, Star, Clock } from "lucide-react";

import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from "@/components/ui/dialog";
import { Card, CardContent } from "@/components/ui/card";

import { getToken } from "@/lib/api";
import {
  DailyMomentCharge,
  fetchDailyMomentCharges,
  upsertDailyMomentCharge,
} from "@/services/dailyMomentTracker";

/* ========================================================================
 * Types matching backend DTOs
 * ===================================================================== */

type DriverRatingRow = {
  count: number;
  itinerary_plan_ID: number;
  itinerary_route_ID: number;
  route_date: string;
  location_name: string | null;
  next_visiting_location: string | null;
  customer_rating: number | null;
  feedback_description: string | null;
  modify: number;
};

type GuideRatingRow = {
  count: number;
  itinerary_plan_ID: number;
  itinerary_route_ID: number;
  route_date: string;
  location_name: string | null;
  next_visiting_location: string | null;
  guide_rating: number | null;
  guide_description: string | null;
  modify: number;
};

type DailyMomentListRowForHeader = {
  itineraryPlanId?: number;
  itineraryRouteId?: number;

  // Guest
  guestName: string;
  guestMobile?: string | null;
  guestEmail?: string | null;

  // Travel expert
  travelExpert: string;
  travelExpertMobile?: string | null;
  travelExpertEmail?: string | null;

  quoteId: string;
  routeDate: Date;
  type: string;
  fromLocation: string;
  toLocation: string;
  hotel: string;
  vendor: string;
  vehicle: string;
  vehicleNo: string;
  driverName: string;
  driverMobile: string;
  agent: string;
};


/**
 * Route hotspot row (for the DAY timeline cards).
 * Keep this shape loose so we don't break if backend DTO changes.
 */
type RouteHotspotRow = {
  [key: string]: any;
};

/* ========================================================================
 * API helpers
 * ===================================================================== */

const API_BASE_URL = import.meta.env.VITE_API_URL;

function getAuthHeaders(): Record<string, string> {
  const token = getToken();
  return token ? { Authorization: `Bearer ${token}` } : {};
}

async function safeReadText(res: Response): Promise<string> {
  try {
    return await res.text();
  } catch {
    return "";
  }
}

async function fetchDriverRatings(
  itineraryPlanId: number,
  itineraryRouteId?: number
): Promise<DriverRatingRow[]> {
  const params = new URLSearchParams();
  params.set("itineraryPlanId", String(itineraryPlanId));
  if (itineraryRouteId && itineraryRouteId > 0) {
    params.set("itineraryRouteId", String(itineraryRouteId));
  }

  const url = `${API_BASE_URL}/api/v1/daily-moment-tracker/driver-ratings?${params.toString()}`;

  const res = await fetch(url, {
    headers: getAuthHeaders(),
  });

  if (!res.ok) {
    console.error(
      "Failed to load driver ratings",
      res.status,
      await safeReadText(res)
    );
    throw new Error("Failed to load driver ratings");
  }

  const json = await res.json();
  if (Array.isArray(json)) return json as DriverRatingRow[];
  if (json && Array.isArray(json.data)) return json.data as DriverRatingRow[];
  return [];
}

async function fetchGuideRatings(
  itineraryPlanId: number,
  itineraryRouteId?: number
): Promise<GuideRatingRow[]> {
  const params = new URLSearchParams();
  params.set("itineraryPlanId", String(itineraryPlanId));
  if (itineraryRouteId && itineraryRouteId > 0) {
    params.set("itineraryRouteId", String(itineraryRouteId));
  }

  const url = `${API_BASE_URL}/api/v1/daily-moment-tracker/guide-ratings?${params.toString()}`;

  const res = await fetch(url, {
    headers: getAuthHeaders(),
  });

  if (!res.ok) {
    console.error(
      "Failed to load guide ratings",
      res.status,
      await safeReadText(res)
    );
    throw new Error("Failed to load guide ratings");
  }

  const json = await res.json();
  if (Array.isArray(json)) return json as GuideRatingRow[];
  if (json && Array.isArray(json.data)) return json.data as GuideRatingRow[];
  return [];
}

/**
 * Route hotspots for the DAY cards (Visited / Not-Visited).
 * GET /daily-moment-tracker/route-hotspots?itineraryPlanId=&itineraryRouteId=
 */
async function fetchRouteHotspots(
  itineraryPlanId: number,
  itineraryRouteId?: number
): Promise<RouteHotspotRow[]> {
  const params = new URLSearchParams();
  params.set("itineraryPlanId", String(itineraryPlanId));
  if (itineraryRouteId && itineraryRouteId > 0) {
    params.set("itineraryRouteId", String(itineraryRouteId));
  }

  const url = `${API_BASE_URL}/api/v1/daily-moment-tracker/route-hotspots?${params.toString()}`;

  const res = await fetch(url, {
    headers: getAuthHeaders(),
  });

  if (!res.ok) {
    console.error(
      "Failed to load route hotspots",
      res.status,
      await safeReadText(res)
    );
    throw new Error("Failed to load route hotspots");
  }

  const json = await res.json();
  if (Array.isArray(json)) return json as RouteHotspotRow[];
  if (json && Array.isArray(json.data)) return json.data as RouteHotspotRow[];
  return [];
}

/**
 * Image upload stub – wire to real API later
 */
async function uploadDailyMomentImageStub(params: {
  itineraryPlanId: number;
  itineraryRouteId?: number;
  file: File;
}): Promise<void> {
  // TODO: POST FormData to your real upload endpoint.
  console.log("uploadDailyMomentImageStub called", params);
}

/* ========================================================================
 * Utils
 * ===================================================================== */

function formatAmount(value: number | null | undefined) {
  if (value == null || Number.isNaN(value)) return "0.00";
  return Number(value).toFixed(2);
}

function formatRating(value: number | null | undefined) {
  if (value == null || Number.isNaN(value)) return "--";
  const formatted = Number(value).toFixed(1);
  return formatted.endsWith(".0") ? formatted.slice(0, -2) : formatted;
}

function cleanText(value?: string | null) {
  if (!value) return "";
  // remove &nbsp; or &amp;nbsp; and trim spaces
  return value.replace(/&(?:amp;)?nbsp;/gi, " ").trim();
}

/* ========================================================================
 * Component
 * ===================================================================== */

export const DailyMomentDayView: React.FC = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const params = useParams<Record<string, string | undefined>>();

  const headerRow =
    (location.state as { row?: DailyMomentListRowForHeader } | undefined)
      ?.row ?? null;

  const normalizeId = (raw: unknown): number => {
    if (typeof raw === "number") return Number.isFinite(raw) ? raw : 0;
    if (typeof raw === "string") {
      const trimmed = raw.trim();
      if (!trimmed || trimmed === "undefined" || trimmed === "null") return 0;
      const n = Number(trimmed);
      return Number.isFinite(n) ? n : 0;
    }
    return 0;
  };

  const rawPlanFromParams =
    params.itineraryPlanId ?? params.id ?? params.itinerary_plan_ID ?? undefined;

  const rawRouteFromParams =
    params.itineraryRouteId ??
    params.routeId ??
    params.itinerary_route_ID ??
    undefined;

  const rawPlanFromState =
    (headerRow as any)?.itineraryPlanId ??
    (location.state as any)?.itineraryPlanId;

  const rawRouteFromState =
    (headerRow as any)?.itineraryRouteId ??
    (location.state as any)?.itineraryRouteId;

  const itineraryPlanId = normalizeId(rawPlanFromParams ?? rawPlanFromState);
  const itineraryRouteId = normalizeId(
    rawRouteFromParams ?? rawRouteFromState
  );

  // ---- Contact details (phone / email) for PHP-style header ----
  const travelExpertPhone = headerRow?.travelExpertMobile || "";
  const travelExpertEmail = headerRow?.travelExpertEmail || "";
  const guestPhone = headerRow?.guestMobile || "";
  const guestEmail = headerRow?.guestEmail || "";

  const travelExpertContact = `${travelExpertPhone || "--"} / ${
    travelExpertEmail || "--"
  }`;

  const guestContact = `${guestPhone || "--"} / ${guestEmail || "--"}`;

  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  const [charges, setCharges] = useState<DailyMomentCharge[]>([]);
  const [driverRatings, setDriverRatings] = useState<DriverRatingRow[]>([]);
  const [guideRatings, setGuideRatings] = useState<GuideRatingRow[]>([]);
  const [hotspots, setHotspots] = useState<RouteHotspotRow[]>([]);

  // dialogs
  const [chargeDialogOpen, setChargeDialogOpen] = useState(false);
  const [chargeType, setChargeType] = useState("");
  const [chargeAmount, setChargeAmount] = useState("");
  const [chargeSaving, setChargeSaving] = useState(false);
  const [chargeError, setChargeError] = useState<string | null>(null);

  const [driverDialogOpen, setDriverDialogOpen] = useState(false);
  const [driverRatingValue, setDriverRatingValue] = useState<number>(0);
  const [driverFeedback, setDriverFeedback] = useState("");
  const [driverSaving, setDriverSaving] = useState(false);
  const [driverError, setDriverError] = useState<string | null>(null);

  const [guideDialogOpen, setGuideDialogOpen] = useState(false);
  const [guideRatingValue, setGuideRatingValue] = useState<number>(0);
  const [guideFeedback, setGuideFeedback] = useState("");
  const [guideSaving, setGuideSaving] = useState(false);
  const [guideError, setGuideError] = useState<string | null>(null);

  const [uploadDialogOpen, setUploadDialogOpen] = useState(false);
  const [uploadFile, setUploadFile] = useState<File | null>(null);
  const [uploadSaving, setUploadSaving] = useState(false);
  const [uploadError, setUploadError] = useState<string | null>(null);

    // -------------------------------------------------------------------
  // DAYWISE SUMMARY HELPERS (KM + tables)
  // -------------------------------------------------------------------
  const [chargeSearch, setChargeSearch] = useState("");
  const [ratingSearch, setRatingSearch] = useState("");

  // TODO: wire this with backend overall KM summary when API is ready.
  const totalRunningKm = 0;

  const filteredCharges = useMemo(() => {
    if (!chargeSearch.trim()) return charges;
    const q = chargeSearch.toLowerCase();

    return charges.filter((charge) => {
      const c: any = charge;
      const haystack = [
        c.charge_type,
        c.source,
        c.destination,
        c.charge_title,
        c.charge_amount,
      ]
        .filter(Boolean)
        .join(" ")
        .toString()
        .toLowerCase();

      return haystack.includes(q);
    });
  }, [charges, chargeSearch]);

  type RatingListRow = {
    key: string;
    day: string | null;
    source: string | null;
    destination: string | null;
    rating: number | null;
    description: string | null;
  };

  const ratingRows = useMemo<RatingListRow[]>(
    () => [
      // Driver ratings
      ...driverRatings.map((row) => ({
        key: `driver-${row.count}`,
        day: row.route_date,
        source: row.location_name,
        destination: row.next_visiting_location,
        rating: row.customer_rating,
        description: row.feedback_description,
      })),
      // Guide ratings
      ...guideRatings.map((row) => ({
        key: `guide-${row.count}`,
        day: row.route_date,
        source: row.location_name,
        destination: row.next_visiting_location,
        rating: row.guide_rating,
        description: row.guide_description,
      })),
    ],
    [driverRatings, guideRatings]
  );

  const filteredRatingRows = useMemo(() => {
    if (!ratingSearch.trim()) return ratingRows;
    const q = ratingSearch.toLowerCase();

    return ratingRows.filter((row) => {
      const haystack = [
        row.day,
        row.source,
        row.destination,
        row.rating?.toString() ?? "",
        row.description,
      ]
        .filter(Boolean)
        .join(" ")
        .toLowerCase();

      return haystack.includes(q);
    });
  }, [ratingRows, ratingSearch]);

  useEffect(() => {
    if (!itineraryPlanId) {
      setError("Invalid Daily Moment context (missing itinerary plan ID).");
      setLoading(false);
      return;
    }

    let cancelled = false;

    async function load() {
      try {
        setLoading(true);
        setError(null);

        const chargesPromise: Promise<DailyMomentCharge[]> =
          itineraryRouteId && itineraryRouteId > 0
            ? fetchDailyMomentCharges(itineraryPlanId, itineraryRouteId)
            : Promise.resolve<DailyMomentCharge[]>([]);

        const hotspotsPromise: Promise<RouteHotspotRow[]> =
          itineraryRouteId && itineraryRouteId > 0
            ? fetchRouteHotspots(itineraryPlanId, itineraryRouteId)
            : Promise.resolve<RouteHotspotRow[]>([]);

        const [chargesRes, driverRes, guideRes, hotspotsRes] =
          await Promise.all([
            chargesPromise,
            fetchDriverRatings(itineraryPlanId, itineraryRouteId || undefined),
            fetchGuideRatings(itineraryPlanId, itineraryRouteId || undefined),
            hotspotsPromise,
          ]);

        if (cancelled) return;

        setCharges(chargesRes);
        setDriverRatings(driverRes);
        setGuideRatings(guideRes);
        setHotspots(hotspotsRes);
      } catch (e: any) {
        console.error(e);
        if (!cancelled) {
          setError(e?.message || "Failed to load day view data.");
        }
      } finally {
        if (!cancelled) setLoading(false);
      }
    }

    load();
    return () => {
      cancelled = true;
    };
  }, [itineraryPlanId, itineraryRouteId]);

  const headerTitle = useMemo(() => {
    if (!headerRow) {
      if (!itineraryPlanId) return "Daily Moment – Day View";
      if (itineraryRouteId) {
        return `Itinerary #${itineraryPlanId} / Route #${itineraryRouteId}`;
      }
      return `Itinerary #${itineraryPlanId}`;
    }
    return `${headerRow.guestName || "--"} (Quote: ${
      headerRow.quoteId || "--"
    })`;
  }, [headerRow, itineraryPlanId, itineraryRouteId]);

  const headerSubTitle = useMemo(() => {
    if (!headerRow) return "";
    return [
      headerRow.type ? `${headerRow.type}` : "",
      headerRow.fromLocation && headerRow.toLocation
        ? `${headerRow.fromLocation} → ${headerRow.toLocation}`
        : "",
      headerRow.hotel ? `Hotel: ${headerRow.hotel}` : "",
      headerRow.vendor ? `Vendor: ${headerRow.vendor}` : "",
    ]
      .filter(Boolean)
      .join(" • ");
  }, [headerRow]);

  // handlers
  const handleOpenChargeDialog = () => {
    setChargeType("");
    setChargeAmount("");
    setChargeError(null);
    setChargeDialogOpen(true);
  };

  const handleSaveCharge = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!itineraryPlanId) return;

    const amountNum = Number(chargeAmount);
    if (!chargeType.trim() || Number.isNaN(amountNum) || amountNum <= 0) {
      setChargeError("Please enter a valid charge type and positive amount.");
      return;
    }

    try {
      setChargeSaving(true);
      setChargeError(null);

      await upsertDailyMomentCharge({
        itineraryPlanId,
        itineraryRouteId: itineraryRouteId || 0,
        chargeType: chargeType.trim(),
        chargeAmount: amountNum,
      });

      const refreshed =
        itineraryRouteId && itineraryRouteId > 0
          ? await fetchDailyMomentCharges(itineraryPlanId, itineraryRouteId)
          : [];

      setCharges(refreshed);
      setChargeDialogOpen(false);
    } catch (e: any) {
      console.error(e);
      setChargeError(e?.message || "Failed to save charge.");
    } finally {
      setChargeSaving(false);
    }
  };

  const handleOpenDriverDialog = () => {
    setDriverRatingValue(0);
    setDriverFeedback("");
    setDriverError(null);
    setDriverDialogOpen(true);
  };

  const handleSaveDriverRating = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!itineraryPlanId) return;

    if (!driverRatingValue || driverRatingValue < 1 || driverRatingValue > 5) {
      setDriverError("Please select a rating between 1 and 5.");
      return;
    }

    try {
      setDriverSaving(true);
      setDriverError(null);

      await upsertDriverRating({
        itineraryPlanId,
        itineraryRouteId: itineraryRouteId || undefined,
        customerRating: driverRatingValue,
        feedbackDescription: driverFeedback.trim(),
      });

      const refreshed = await fetchDriverRatings(
        itineraryPlanId,
        itineraryRouteId || undefined
      );
      setDriverRatings(refreshed);
      setDriverDialogOpen(false);
    } catch (e: any) {
      console.error(e);
      setDriverError(e?.message || "Failed to save driver rating.");
    } finally {
      setDriverSaving(false);
    }
  };

  const handleOpenGuideDialog = () => {
    setGuideRatingValue(0);
    setGuideFeedback("");
    setGuideError(null);
    setGuideDialogOpen(true);
  };

  const handleSaveGuideRating = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!itineraryPlanId) return;

    if (!guideRatingValue || guideRatingValue < 1 || guideRatingValue > 5) {
      setGuideError("Please select a rating between 1 and 5.");
      return;
    }

    try {
      setGuideSaving(true);
      setGuideError(null);

      await upsertGuideRating({
        itineraryPlanId,
        itineraryRouteId: itineraryRouteId || undefined,
        guideRating: guideRatingValue,
        guideDescription: guideFeedback.trim(),
      });

      const refreshed = await fetchGuideRatings(
        itineraryPlanId,
        itineraryRouteId || undefined
      );
      setGuideRatings(refreshed);
      setGuideDialogOpen(false);
    } catch (e: any) {
      console.error(e);
      setGuideError(e?.message || "Failed to save guide rating.");
    } finally {
      setGuideSaving(false);
    }
  };

  const handleOpenUploadDialog = () => {
    setUploadFile(null);
    setUploadError(null);
    setUploadDialogOpen(true);
  };

  const handleUploadImage = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!itineraryPlanId) return;

    if (!uploadFile) {
      setUploadError("Please choose an image to upload.");
      return;
    }

    try {
      setUploadSaving(true);
      setUploadError(null);

      await uploadDailyMomentImageStub({
        itineraryPlanId,
        itineraryRouteId: itineraryRouteId || undefined,
        file: uploadFile,
      });

      setUploadDialogOpen(false);
    } catch (e: any) {
      console.error(e);
      setUploadError(
        e?.message ||
          "Upload API is not wired yet. Please hook this to your real endpoint."
      );
    } finally {
      setUploadSaving(false);
    }
  };

  // =====================================================================
  // Render
  // =====================================================================

  if (loading) {
    return (
      <div className="w-full min-h-screen bg-[#ffe9f4] p-4 md:p-6 flex items-center justify-center">
        <p className="text-sm text-[#4a4260]">
          Loading Daily Moment Day View…
        </p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="w-full min-h-screen bg-[#ffe9f4] p-4 md:p-6 flex flex-col items-center justify-center gap-3">
        <p className="text-sm text-[#f4008f]">{error}</p>
        <Button
          variant="outline"
          className="rounded-md bg-white border border-[#f0d8ff] text-[#4a4260]"
          onClick={() => navigate(-1)}
        >
          <ArrowLeft className="h-4 w-4 mr-2" />
          Back
        </Button>
      </div>
    );
  }

  return (
    <div className="w-full min-h-screen bg-[#ffe9f4] p-4 md:p-6 space-y-4">
      {/* TOP STRIP */}
      <div className="bg-[#fdddf7] border border-[#f6c5f0] rounded-xl px-4 md:px-6 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div className="text-xs md:text-sm text-[#4a4260]">
          <p className="font-semibold">
            {headerRow?.quoteId || `DVI${String(itineraryPlanId || "")}`}
          </p>
          <p className="mt-0.5">
            {headerRow?.routeDate
              ? headerRow.routeDate.toLocaleDateString("en-GB")
              : "Date not available"}
            {headerRow?.type ? ` (${headerRow.type})` : ""}
          </p>
          <p className="mt-0.5">
            {headerRow?.fromLocation || "Cochin Airport"}{" "}
            <span className="mx-1">⇢</span>
            {headerRow?.toLocation || "Cochin Airport"}
          </p>
        </div>

        <div className="flex items-center gap-2 md:gap-3 self-end md:self-auto">
          <Button
            size="sm"
            className="h-8 px-4 rounded-full bg-[#00b66a] hover:bg-[#00a25f] text-white text-xs font-semibold"
          >
            Download PDF
          </Button>
          <Button
            size="sm"
            variant="outline"
            className="h-8 px-4 rounded-full border border-[#e3c8ff] bg-white text-xs text-[#4a4260] flex items-center gap-1"
            onClick={() => navigate(-1)}
          >
            <ArrowLeft className="h-3 w-3" />
            Back to List
          </Button>
        </div>
      </div>

          {/* TRAVEL EXPERT + GUEST */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        {/* Travel Expert */}
        <div className="bg-white rounded-xl border border-[#f6dfff] px-5 py-4 flex items-center gap-3">
          <div className="h-12 w-12 rounded-xl bg-[#f8f0ff] flex items-center justify-center text-2xl">
            🌍
          </div>
          <div className="text-xs md:text-sm text-[#4a4260]">
            <p className="text-[11px] uppercase tracking-wide text-[#a08ac5]">
              Travel Expert
            </p>
            <p className="font-semibold text-sm">
              {headerRow?.travelExpert || "--"}
            </p>
            {/* Phone / Email like PHP UI: 9919911948 / ops1@dvi.co.in */}
            <p className="text-[11px] mt-1">{travelExpertContact}</p>
          </div>
        </div>

        {/* Guest */}
        <div className="bg-white rounded-xl border border-[#f6dfff] px-5 py-4 flex items-center gap-3">
          <div className="h-12 w-12 rounded-xl bg-[#f8f0ff] flex items-center justify-center text-2xl">
            🎒
          </div>
          <div className="text-xs md:text-sm text-[#4a4260]">
            <p className="text-[11px] uppercase tracking-wide text-[#a08ac5]">
              Guest
            </p>
            <p className="font-semibold text-sm">
              {cleanText(headerRow?.guestName) || "--"}
            </p>
            {/* Phone / Email like PHP UI: 9845420090 / -- */}
            <p className="text-[11px] mt-1">{guestContact}</p>
          </div>
        </div>
      </div>

      {/* DAY HEADER + BUTTONS */}
      <Card className="shadow-none border border-[#f6dfff] bg-white">
        <CardContent className="px-4 md:px-6 py-4">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div className="text-xs md:text-sm text-[#4a4260]">
              <p className="font-semibold">
                DAY 1
                {headerRow?.routeDate
                  ? ` - ${headerRow.routeDate.toLocaleDateString("en-GB")}`
                  : ""}
              </p>
              <p className="mt-0.5 text-[11px]">
                {headerRow?.fromLocation || "Cochin Airport"}{" "}
                <span className="mx-1">⇢</span>
                {headerRow?.toLocation || "Cochin"}
              </p>
            </div>

            <div className="flex flex-wrap gap-2">
              <Button
                size="sm"
                className="h-8 px-4 rounded-full bg-white text-xs text-[#f68c2b] border border-[#ffd4a8] shadow-none"
                onClick={handleOpenDriverDialog}
              >
                ★ Review
              </Button>
              <Button
                size="sm"
                className="h-8 px-4 rounded-full bg-white text-xs text-[#d94a8c] border border-[#ffc4e3] shadow-none"
                onClick={handleOpenUploadDialog}
              >
                + Upload Image
              </Button>
              <Button
                size="sm"
                variant="outline"
                className="h-8 px-4 rounded-full bg-white text-xs font-semibold text-[#7c3aed] border border-[#d9c3ff] hover:bg-[#f3e8ff]"
                onClick={handleOpenChargeDialog}
              >
                + Add Charge
              </Button>
            </div>
          </div>

          {/* KM STRIP */}
          <div className="mt-4 rounded-xl bg-[#e7f9e4] px-4 md:px-6 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-2 text-xs md:text-sm text-[#325c37]">
            <div className="flex items-center gap-2">
              <div className="h-8 w-8 rounded-full bg-white flex items-center justify-center border border-[#d6f0d1]">
                <CarIcon className="h-4 w-4" />
              </div>
              <p>
                <span className="font-semibold">Starting KM</span>{" "}
                <span className="font-bold">- KM</span>
              </p>
            </div>
            <p>
              <span className="font-semibold">Closing KM</span>{" "}
              <span className="font-bold">- KM</span>
            </p>
            <p>
              <span className="font-semibold">Running KM</span>{" "}
              <span className="font-bold">0KM</span>
            </p>
          </div>

          {/* DAY HOTSPOT CARDS (Visited / Not-Visited) */}
          <div className="mt-4 space-y-3">
            {hotspots.length === 0 ? (
              <div className="rounded-xl bg-[#fdf2ff] border border-[#f5d7ff] px-4 py-3 text-xs text-[#7b6f9a]">
                No route hotspots defined for this day.
              </div>
            ) : (
              hotspots.map((spot, index) => {
                const title =
                  spot.activity_title ||
                  spot.activityName ||
                  spot.location_name ||
                  "N/A";

                const timeFrom =
                  spot.start_time ||
                  spot.activity_from_time ||
                  spot.fromTime ||
                  "";
                const timeTo =
                  spot.end_time ||
                  spot.activity_to_time ||
                  spot.toTime ||
                  "";
                const durationLabel =
                  spot.duration_label ||
                  spot.duration ||
                  spot.totalDuration ||
                  "";

                const visitedFlag =
                  spot.visited === 1 ||
                  spot.visit_status === 1 ||
                  spot.is_visited === 1 ||
                  spot.visited === true;

                return (
                  <div
                    key={spot.id ?? `${itineraryPlanId}-${itineraryRouteId}-${index}`}
                    className="relative bg-[#ffe9f7] rounded-xl px-4 md:px-6 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3"
                  >
                    {/* Left dot / index */}
                    <div className="flex items-center gap-3">
                      <div className="h-9 w-9 rounded-full bg-white flex items-center justify-center text-sm font-semibold text-[#f268b7] shadow-sm">
                        {index + 1}
                      </div>
                      <div className="text-xs md:text-sm text-[#4a4260]">
                        <p className="font-semibold">{title}</p>
                        <div className="flex flex-wrap items-center gap-2 mt-1 text-[11px] text-[#7b6f9a]">
                          <span className="flex items-center gap-1">
                            <Clock className="h-3 w-3" />
                            <span>
                              {timeFrom && timeTo
                                ? `${timeFrom} - ${timeTo}`
                                : "N/A"}
                            </span>
                          </span>
                          {durationLabel && (
                            <span className="flex items-center gap-1">
                              <span className="text-[13px]">⏱</span>
                              <span>{durationLabel}</span>
                            </span>
                          )}
                        </div>
                      </div>
                    </div>

                    {/* Visited / Not-Visited buttons (display only) */}
                    <div className="flex items-center gap-2 md:gap-3">
                      <button
                        type="button"
                        className={`h-8 px-4 rounded-full text-[11px] font-semibold flex items-center gap-1 border ${
                          visitedFlag
                            ? "bg-[#16a34a] border-[#16a34a] text-white"
                            : "bg-white border-[#d1fadf] text-[#15803d]"
                        }`}
                        disabled
                      >
                        ✓ Visited
                      </button>
                      <button
                        type="button"
                        className={`h-8 px-4 rounded-full text-[11px] font-semibold flex items-center gap-1 border ${
                          visitedFlag
                            ? "bg-white border-[#fecaca] text-[#b91c1c]"
                            : "bg-[#f97373] border-[#f97373] text-white"
                        }`}
                        disabled
                      >
                        ✕ Not-Visited
                      </button>
                    </div>
                  </div>
                );
              })
            )}
          </div>
        </CardContent>
      </Card>

            {/* OVERALL KM SUMMARY (PHP STYLE) */}
      <Card className="shadow-none border border-[#f6dfff] bg-white mt-2">
        <CardContent className="px-4 md:px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
          <p className="text-sm md:text-base font-semibold text-[#4a4260]">
            OVERALL KILOMETER SUMMARY
          </p>
          <p className="text-sm md:text-base font-semibold text-[#4a4260]">
            Total Running KM -{" "}
            <span className="text-[#a448ff]">
              {totalRunningKm.toLocaleString()} KM
            </span>
          </p>
        </CardContent>
      </Card>

      {/* LIST OF CHARGE DETAILS (PHP TABLE) */}
      <Card className="shadow-none border border-[#f6dfff] bg-white mt-4">
        <CardContent className="px-4 md:px-6 py-4 space-y-4">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <p className="text-sm md:text-base font-semibold text-[#4a4260]">
              List of Charge Details
            </p>

            <div className="flex flex-col sm:flex-row sm:items-center gap-2">
              <div className="flex items-center gap-2">
                <span className="text-xs text-[#4a4260]">Search:</span>
                <Input
                  value={chargeSearch}
                  onChange={(e) => setChargeSearch(e.target.value)}
                  className="h-8 w-40 md:w-56 text-xs"
                  placeholder="Search…"
                />
              </div>

              <div className="flex items-center gap-2">
                <Button
                  type="button"
                  size="sm"
                  variant="outline"
                  className="h-8 px-3 rounded-md border border-[#d2c5ff] bg-white text-[11px] text-[#6b4bd8]"
                >
                  Copy
                </Button>
                <Button
                  type="button"
                  size="sm"
                  variant="outline"
                  className="h-8 px-3 rounded-md border border-[#b7f7d9] bg-[#e5fff1] text-[11px] text-[#0f9c34]"
                >
                  Excel
                </Button>
                <Button
                  type="button"
                  size="sm"
                  variant="outline"
                  className="h-8 px-3 rounded-md border border-[#e4e4e7] bg-white text-[11px] text-[#4a4260]"
                >
                  CSV
                </Button>
              </div>
            </div>
          </div>

          <div className="border border-[#f3e0ff] rounded-lg overflow-x-auto">
            <table className="min-w-full text-[11px]">
              <thead className="bg-[#fbf2ff]">
                <tr>
                  <th className="px-3 py-2 text-left text-[#4a4260]">S.NO</th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">ACTION</th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">DAY</th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">
                    SOURCE
                  </th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">
                    DESTINATION
                  </th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">
                    CHARGE TITLE
                  </th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">
                    CHARGE AMOUNT
                  </th>
                </tr>
              </thead>
              <tbody>
                {filteredCharges.length === 0 ? (
                  <tr>
                    <td
                      colSpan={7}
                      className="px-3 py-6 text-center text-[#7b6f9a]"
                    >
                      No data available in table
                    </td>
                  </tr>
                ) : (
                  filteredCharges.map((charge, index) => {
                    const c: any = charge;
                    return (
                      <tr
                        key={`${c.charge_type || "charge"}-${index}`}
                        className="odd:bg-white even:bg-[#fff8ff]"
                      >
                        <td className="px-3 py-2 text-[#4a4260]">
                          {index + 1}
                        </td>
                        <td className="px-3 py-2 text-[#4a4260]">--</td>
                        <td className="px-3 py-2 text-[#4a4260]">
                          {c.day || "--"}
                        </td>
                        <td className="px-3 py-2 text-[#4a4260]">
                          {c.source || "--"}
                        </td>
                        <td className="px-3 py-2 text-[#4a4260]">
                          {c.destination || "--"}
                        </td>
                        <td className="px-3 py-2 text-[#4a4260]">
                          {c.charge_type || c.charge_title || "--"}
                        </td>
                        <td className="px-3 py-2 text-[#4a4260]">
                          {formatAmount(
                            (c.charge_amount ??
                              c.amount ??
                              0) as number | null | undefined
                          )}
                        </td>
                      </tr>
                    );
                  })
                )}
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>

      {/* LIST OF RATING DETAILS (PHP TABLE) */}
      <Card className="shadow-none border border-[#f6dfff] bg-white mt-4">
        <CardContent className="px-4 md:px-6 py-4 space-y-4">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <p className="text-sm md:text-base font-semibold text-[#4a4260]">
              List of Rating Details
            </p>

            <div className="flex flex-col sm:flex-row sm:items-center gap-2">
              <div className="flex items-center gap-2">
                <span className="text-xs text-[#4a4260]">Search:</span>
                <Input
                  value={ratingSearch}
                  onChange={(e) => setRatingSearch(e.target.value)}
                  className="h-8 w-40 md:w-56 text-xs"
                  placeholder="Search…"
                />
              </div>

              <div className="flex items-center gap-2">
                <Button
                  type="button"
                  size="sm"
                  variant="outline"
                  className="h-8 px-3 rounded-md border border-[#d2c5ff] bg-white text-[11px] text-[#6b4bd8]"
                >
                  Copy
                </Button>
                <Button
                  type="button"
                  size="sm"
                  variant="outline"
                  className="h-8 px-3 rounded-md border border-[#b7f7d9] bg-[#e5fff1] text-[11px] text-[#0f9c34]"
                >
                  Excel
                </Button>
                <Button
                  type="button"
                  size="sm"
                  variant="outline"
                  className="h-8 px-3 rounded-md border border-[#e4e4e7] bg-white text-[11px] text-[#4a4260]"
                >
                  CSV
                </Button>
              </div>
            </div>
          </div>

          <div className="border border-[#f3e0ff] rounded-lg overflow-x-auto">
            <table className="min-w-full text-[11px]">
              <thead className="bg-[#fbf2ff]">
                <tr>
                  <th className="px-3 py-2 text-left text-[#4a4260]">S.NO</th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">ACTION</th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">DAY</th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">
                    SOURCE
                  </th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">
                    DESTINATION
                  </th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">
                    RATING
                  </th>
                  <th className="px-3 py-2 text-left text-[#4a4260]">
                    DESCRIPTION
                  </th>
                </tr>
              </thead>
              <tbody>
                {filteredRatingRows.length === 0 ? (
                  <tr>
                    <td
                      colSpan={7}
                      className="px-3 py-6 text-center text-[#7b6f9a]"
                    >
                      No data available in table
                    </td>
                  </tr>
                ) : (
                  filteredRatingRows.map((row, index) => (
                    <tr
                      key={row.key}
                      className="odd:bg-white even:bg-[#fff8ff]"
                    >
                      <td className="px-3 py-2 text-[#4a4260]">
                        {index + 1}
                      </td>
                      <td className="px-3 py-2 text-[#4a4260]">--</td>
                      <td className="px-3 py-2 text-[#4a4260]">
                        {row.day || "--"}
                      </td>
                      <td className="px-3 py-2 text-[#4a4260]">
                        {row.source || "--"}
                      </td>
                      <td className="px-3 py-2 text-[#4a4260]">
                        {row.destination || "--"}
                      </td>
                      <td className="px-3 py-2 text-[#4a4260]">
                        {formatRating(row.rating)}
                      </td>
                      <td className="px-3 py-2 text-[#4a4260]">
                        {row.description || "--"}
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>

      <div className="py-3 text-center text-[11px] text-[#a593c7] border-t border-[#f6dfff] mt-4">
        DVI Holidays @ 2025
      </div>

      {/* MODALS */}

      {/* Add Charge Dialog */}
      <Dialog open={chargeDialogOpen} onOpenChange={setChargeDialogOpen}>
        <DialogContent className="max-w-md">
          <DialogHeader>
            <DialogTitle className="text-base font-semibold">
              Add Charges
            </DialogTitle>
          </DialogHeader>

          {chargeError && (
            <div className="text-xs text-red-600 bg-red-50 border border-red-100 rounded-md px-3 py-2 mb-3">
              {chargeError}
            </div>
          )}

          <form className="space-y-3" onSubmit={handleSaveCharge}>
            <div className="space-y-1">
              <Label className="text-xs text-[#4a4260]">
                Charge Type<span className="text-red-500">*</span>
              </Label>
              <Input
                value={chargeType}
                onChange={(e) => setChargeType(e.target.value)}
                className="h-10 text-sm"
                placeholder="Enter the Charge"
              />
            </div>
            <div className="space-y-1">
              <Label className="text-xs text-[#4a4260]">
                Charge Amount<span className="text-red-500">*</span>
              </Label>
              <Input
                type="number"
                step="0.01"
                min="0"
                value={chargeAmount}
                onChange={(e) => setChargeAmount(e.target.value)}
                className="h-10 text-sm"
                placeholder="Enter the Charge"
              />
            </div>

            <DialogFooter className="mt-4 flex justify-between gap-3">
              <Button
                type="button"
                variant="outline"
                size="sm"
                className="h-10 px-6 rounded-md bg-[#f1f2f4] text-[#555]"
                onClick={() => setChargeDialogOpen(false)}
              >
                Cancel
              </Button>
              <Button
                type="submit"
                size="sm"
                className="h-10 px-6 rounded-md bg-gradient-to-r from-[#f763c6] to-[#a347ff] text-white"
                disabled={chargeSaving}
              >
                {chargeSaving ? "Saving…" : "Save"}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Driver Rating Dialog */}
      <Dialog open={driverDialogOpen} onOpenChange={setDriverDialogOpen}>
        <DialogContent className="max-w-md">
          <DialogHeader>
            <DialogTitle className="text-base font-semibold">
              Add Rating Modal
            </DialogTitle>
          </DialogHeader>

          {driverError && (
            <div className="text-xs text-red-600 bg-red-50 border border-red-100 rounded-md px-3 py-2 mb-3">
              {driverError}
            </div>
          )}

          <form className="space-y-3" onSubmit={handleSaveDriverRating}>
            <div className="space-y-1">
              <Label className="text-xs text-[#4a4260]">
                Rating<span className="text-red-500">*</span>
              </Label>
              <div className="flex items-center gap-1">
                {[1, 2, 3, 4, 5].map((value) => (
                  <button
                    key={value}
                    type="button"
                    onClick={() => setDriverRatingValue(value)}
                    className={`h-8 w-8 rounded-full flex items-center justify-center border ${
                      driverRatingValue >= value
                        ? "bg-[#ffc107] border-[#e0a800]"
                        : "bg-white border-[#e3d4ff]"
                    }`}
                  >
                    <Star
                      className={`h-4 w-4 ${
                        driverRatingValue >= value
                          ? "text-[#4a4260]"
                          : "text-[#7b6f9a]"
                      }`}
                      fill={driverRatingValue >= value ? "#ffc107" : "none"}
                    />
                  </button>
                ))}
              </div>
            </div>

            <div className="space-y-1">
              <Label className="text-xs text-[#4a4260]">
                Notes<span className="text-red-500">*</span>
              </Label>
              <Textarea
                value={driverFeedback}
                onChange={(e) => setDriverFeedback(e.target.value)}
                className="text-sm"
                rows={4}
                placeholder="Enter the Notes"
              />
            </div>

            <DialogFooter className="mt-4 flex justify-between gap-3">
              <Button
                type="button"
                variant="outline"
                size="sm"
                className="h-10 px-6 rounded-md bg-[#f1f2f4] text-[#555]"
                onClick={() => setDriverDialogOpen(false)}
              >
                Cancel
              </Button>
              <Button
                type="submit"
                size="sm"
                className="h-10 px-6 rounded-md bg-gradient-to-r from-[#f763c6] to-[#a347ff] text-white"
                disabled={driverSaving}
              >
                {driverSaving ? "Saving…" : "Save"}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Guide Rating Dialog */}
      <Dialog open={guideDialogOpen} onOpenChange={setGuideDialogOpen}>
        <DialogContent className="max-w-md">
          <DialogHeader>
            <DialogTitle className="text-base font-semibold">
              Guide Rating – Daily Moment
            </DialogTitle>
          </DialogHeader>

          {guideError && (
            <div className="text-xs text-red-600 bg-red-50 border border-red-100 rounded-md px-3 py-2 mb-3">
              {guideError}
            </div>
          )}

          <form className="space-y-3" onSubmit={handleSaveGuideRating}>
            <div className="space-y-1">
              <Label className="text-xs text-[#4a4260]">
                Rating<span className="text-red-500">*</span>
              </Label>
              <div className="flex items-center gap-1">
                {[1, 2, 3, 4, 5].map((value) => (
                  <button
                    key={value}
                    type="button"
                    onClick={() => setGuideRatingValue(value)}
                    className={`h-8 w-8 rounded-full flex items-center justify-center border ${
                      guideRatingValue >= value
                        ? "bg-[#ffc107] border-[#e0a800]"
                        : "bg-white border-[#e3d4ff]"
                    }`}
                  >
                    <Star
                      className={`h-4 w-4 ${
                        guideRatingValue >= value
                          ? "text-[#4a4260]"
                          : "text-[#7b6f9a]"
                      }`}
                      fill={guideRatingValue >= value ? "#ffc107" : "none"}
                    />
                  </button>
                ))}
              </div>
            </div>

            <div className="space-y-1">
              <Label className="text-xs text-[#4a4260]">
                Feedback<span className="text-red-500">*</span>
              </Label>
              <Textarea
                value={guideFeedback}
                onChange={(e) => setGuideFeedback(e.target.value)}
                className="text-sm"
                rows={4}
                placeholder="Write your feedback about the guide's performance…"
              />
            </div>

            <DialogFooter className="mt-4 flex justify-between gap-3">
              <Button
                type="button"
                variant="outline"
                size="sm"
                className="h-10 px-6 rounded-md bg-[#f1f2f4] text-[#555]"
                onClick={() => setGuideDialogOpen(false)}
              >
                Cancel
              </Button>
              <Button
                type="submit"
                size="sm"
                className="h-10 px-6 rounded-md bg-gradient-to-r from-[#f763c6] to-[#a347ff] text-white"
                disabled={guideSaving}
              >
                {guideSaving ? "Saving…" : "Save"}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Upload Image Dialog */}
      <Dialog open={uploadDialogOpen} onOpenChange={setUploadDialogOpen}>
        <DialogContent className="max-w-md">
          <DialogHeader>
            <DialogTitle className="text-base font-semibold">
              Upload Image
            </DialogTitle>
          </DialogHeader>

          {uploadError && (
            <div className="text-xs text-red-600 bg-red-50 border border-red-100 rounded-md px-3 py-2 mb-3">
              {uploadError}
            </div>
          )}

          <form className="space-y-3" onSubmit={handleUploadImage}>
            <div className="space-y-1">
              <Label className="text-xs text-[#4a4260]">Upload Image</Label>
              <Input
                type="file"
                accept="image/*"
                className="h-10 text-sm"
                onChange={(e) => {
                  const file = e.target.files?.[0];
                  setUploadFile(file ?? null);
                }}
              />
            </div>

            <DialogFooter className="mt-4 flex justify-between gap-3">
              <Button
                type="button"
                variant="outline"
                size="sm"
                className="h-10 px-6 rounded-md bg-[#f1f2f4] text-[#555]"
                onClick={() => setUploadDialogOpen(false)}
              >
                Cancel
              </Button>
              <Button
                type="submit"
                size="sm"
                className="h-10 px-6 rounded-md bg-gradient-to-r from-[#f763c6] to-[#a347ff] text-white"
                disabled={uploadSaving}
              >
                {uploadSaving ? "Uploading…" : "Save"}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
};

export default DailyMomentDayView;
