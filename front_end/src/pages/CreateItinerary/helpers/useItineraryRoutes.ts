// FILE: src/pages/CreateItinerary/useItineraryRoutes.ts

import { useEffect, useState } from "react";
import {
  fetchViaRouteForm,
  SimpleOption,
} from "@/services/itineraryDropdownsMock";
import { api } from "@/lib/api";
import {
  splitViaString,
  toDDMMYYYY,
} from "./itineraryUtils";

export type RouteRow = {
  id: number;
  day: number;
  date: string;
  source: string;
  next: string;
  via: string; // comma separated hotspots for this segment
  directVisit: "Yes" | "No";
};

type ToastFn = (opts: {
  title?: string;
  description?: string;
  variant?: "default" | "destructive";
}) => void;

type UseItineraryRoutesArgs = {
  tripStartDate: string;
  tripEndDate: string;
  arrivalLocation: string;
  departureLocation: string;
  itineraryPlanId: number | null;
  itinerarySessionId: string;
  toast: ToastFn;
};

export function useItineraryRoutes({
  tripStartDate,
  tripEndDate,
  arrivalLocation,
  departureLocation,
  itineraryPlanId,
  itinerarySessionId,
  toast,
}: UseItineraryRoutesArgs) {
  const [routeDetails, setRouteDetails] = useState<RouteRow[]>([
    {
      id: 1,
      day: 1,
      date: "",
      source: "",
      next: "",
      via: "",
      directVisit: "Yes",
    },
  ]);

  const [viaDialogOpen, setViaDialogOpen] = useState(false);
  const [activeViaRouteRow, setActiveViaRouteRow] = useState<RouteRow | null>(
    null
  );
  const [viaRoutes, setViaRoutes] = useState<SimpleOption[]>([]);
  const [viaRoutesLoading, setViaRoutesLoading] = useState(false);
  const [activeViaRouteIds, setActiveViaRouteIds] = useState<string[]>([]);

  // ----------------- auto-generate routes from dates -----------------

  useEffect(() => {
    if (!tripStartDate || !tripEndDate) return;

    const parse = (value: string): Date | null => {
      const [d, m, y] = value.split("/").map(Number);
      if (!d || !m || !y) return null;
      return new Date(y, m - 1, d);
    };

    const start = parse(tripStartDate);
    const end = parse(tripEndDate);

    if (!start || !end || end < start) return;

    const ONE_DAY = 24 * 60 * 60 * 1000;
    const totalDays =
      Math.floor((end.getTime() - start.getTime()) / ONE_DAY) + 1;

    setRouteDetails((prev) => {
      const nextRoutes: RouteRow[] = [];

      for (let i = 0; i < totalDays; i++) {
        const currentDate = new Date(start.getTime() + i * ONE_DAY);
        const existing = prev[i];

        nextRoutes.push({
          id: existing?.id ?? i + 1,
          day: i + 1,
          date: toDDMMYYYY(currentDate),
          source: existing?.source ?? "",
          next: existing?.next ?? "",
          via: existing?.via ?? "",
          directVisit: existing?.directVisit ?? "Yes",
        });
      }

      // Prefill DAY 1 source from Arrival
      if (arrivalLocation && nextRoutes.length) {
        nextRoutes[0] = {
          ...nextRoutes[0],
          source: arrivalLocation,
        };
      }

      // Prefill LAST DAY next destination from Departure
      if (departureLocation && nextRoutes.length) {
        const lastIndex = nextRoutes.length - 1;
        nextRoutes[lastIndex] = {
          ...nextRoutes[lastIndex],
          next: departureLocation,
        };
      }

      return nextRoutes;
    });
  }, [tripStartDate, tripEndDate, arrivalLocation, departureLocation]);

  // ----------------- handlers: Via Route popup -----------------

  const openViaRoutes = async (row: RouteRow) => {
    if (!row.source || !row.next) {
      toast({
        title: "Select locations first",
        description:
          "Please choose both Source Location and Next Visiting Place before adding via routes.",
        variant: "destructive",
      });
      return;
    }

    // reset IDs for the newly opened row
    setActiveViaRouteIds([]);
    setActiveViaRouteRow(row);
    setViaDialogOpen(true);

    try {
      setViaRoutesLoading(true);

      const form = await fetchViaRouteForm({
        dayNo: row.day,
        source: row.source,
        next: row.next,
        date: row.date || "",
        itineraryPlanId,
        itinerarySessionId,
      });

      setViaRoutes(form.options);
      setActiveViaRouteIds(form.existingIds ?? []);

      // Prefer what backend says; fall back to whatever was already in the row
      const viaLabels =
        form.existingLabels && form.existingLabels.length
          ? form.existingLabels
          : splitViaString(row.via);

      if (viaLabels.length) {
        const viaText = viaLabels.join(", ");

        setRouteDetails((prev) =>
          prev.map((r) => (r.id === row.id ? { ...r, via: viaText } : r))
        );

        setActiveViaRouteRow((prev) =>
          prev && prev.id === row.id ? { ...prev, via: viaText } : prev
        );
      }
    } catch (err) {
      console.error("Failed to open via routes form", err);
      setViaRoutes([]);
      setActiveViaRouteIds([]);
    } finally {
      setViaRoutesLoading(false);
    }
  };

  const handleViaDialogSubmit = async (selectedOptions: SimpleOption[]) => {
    if (!activeViaRouteRow) {
      setViaDialogOpen(false);
      return;
    }

    try {
      const viaRouteIds = selectedOptions.map((o) => o.id);

      // CASE 1: User removed all via routes → just clear them in DB
      if (!viaRouteIds.length) {
        const clearBody = {
          via_route_location: [], // IMPORTANT: empty array means "delete only"
          hidden_route_date: activeViaRouteRow.date, // DD/MM/YYYY
          hidden_source_location: activeViaRouteRow.source,
          hidden_destination_location: activeViaRouteRow.next,
          hidden_itineary_via_route_id: [],
          itinerary_route_ID: null,
          itinerary_plan_ID: itineraryPlanId,
          itinerary_session_id: itinerarySessionId,
        };

        const clearData = await api("/itinerary-via-routes/add", {
          method: "POST",
          body: clearBody,
        });

        if (!clearData?.success) {
          const msg =
            clearData?.errors?.result_error || "Unable to clear Via Route.";
          toast({
            title: "Via Route Error",
            description: msg,
            variant: "destructive",
          });
          return;
        }

        // Local UI: clear VIA column
        setRouteDetails((prev) =>
          prev.map((r) =>
            r.id === activeViaRouteRow.id ? { ...r, via: "" } : r
          )
        );

        setViaDialogOpen(false);
        setActiveViaRouteRow(null);
        setActiveViaRouteIds([]);

        toast({
          description: "Via Route removed for this day.",
        });

        return;
      }

      // CASE 2: We have via routes selected → check distance limit then save
      const checkBody = {
        source: activeViaRouteRow.source,
        destination: activeViaRouteRow.next,
        via_routes: viaRouteIds,
      };

      const checkData = await api(
        "/itinerary-via-routes/check-distance-limit",
        {
          method: "POST",
          body: checkBody,
        }
      );

      if (!checkData?.success) {
        const msg =
          checkData?.errors?.result_error || "Distance KM Limit Exceeded !!!";
        toast({
          title: "Via Route Limit",
          description: msg,
          variant: "destructive",
        });
        return;
      }

      const addBody = {
        via_route_location: viaRouteIds,
        hidden_route_date: activeViaRouteRow.date, // DD/MM/YYYY
        hidden_source_location: activeViaRouteRow.source,
        hidden_destination_location: activeViaRouteRow.next,
        hidden_itineary_via_route_id: [], // full replace on backend
        itinerary_route_ID: null,
        itinerary_plan_ID: itineraryPlanId,
        itinerary_session_id: itinerarySessionId,
      };

      const addData = await api("/itinerary-via-routes/add", {
        method: "POST",
        body: addBody,
      });

      if (!addData?.success) {
        const msg =
          addData?.errors?.result_error || "Unable to add Via Route.";
        toast({
          title: "Via Route Error",
          description: msg,
          variant: "destructive",
        });
        return;
      }

      // Local UI update – VIA column
      const viaText = selectedOptions.map((o) => o.label).join(", ");

      setRouteDetails((prev) =>
        prev.map((r) =>
          r.id === activeViaRouteRow.id ? { ...r, via: viaText } : r
        )
      );

      setViaDialogOpen(false);
      setActiveViaRouteRow(null);
      setActiveViaRouteIds([]);

      toast({
        description: "Via Route Added Successfully",
      });
    } catch (err) {
      console.error("Via route submit failed", err);
      toast({
        title: "Via Route Error",
        description: "Something went wrong while saving via route.",
        variant: "destructive",
      });
    }
  };

  const handleViaDialogOpenChange = (isOpen: boolean) => {
    setViaDialogOpen(isOpen);
    if (!isOpen) {
      setActiveViaRouteRow(null);
      setActiveViaRouteIds([]);
    }
  };

  return {
    routeDetails,
    setRouteDetails,
    viaDialogOpen,
    viaRoutes,
    viaRoutesLoading,
    activeViaRouteRow,
    activeViaRouteIds,
    openViaRoutes,
    handleViaDialogSubmit,
    handleViaDialogOpenChange,
  };
}
