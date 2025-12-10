// FILE: src/pages/CreateItinerary/ViaRouteDialog.tsx

import { useEffect, useState } from "react";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { SimpleOption } from "@/services/itineraryDropdownsMock";
import { Label } from "@/components/ui/label";

type ActiveRouteInfo = {
  day: number;
  date: string; // DD/MM/YYYY
  source: string;
  next: string;
  initialSelected: string[]; // saved via route NAMES for this segment
};

type ViaRouteDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;

  routes: SimpleOption[];
  loading: boolean;

  activeRoute: ActiveRouteInfo | null;
  maxRoutes?: number; // default 2

  /**
   * Exact via_route_location IDs (as strings) returned by backend
   * for this leg – we prefer these to preselect options.
   */
  initialIds?: string[];

  // called with final selected options when user clicks Submit
  onSubmit: (selectedOptions: SimpleOption[]) => void | Promise<void>;
};

type ViaRow = {
  id: number;
  selectedId: string; // SimpleOption.id
};

export const ViaRouteDialog = ({
  open,
  onOpenChange,
  routes,
  loading,
  activeRoute,
  maxRoutes = 2,
  onSubmit,
  initialIds = [],
}: ViaRouteDialogProps) => {
  // Build initial rows from existing selection + current options
// REPLACE this whole function in ViaRouteDialog.tsx
const buildInitialRows = (): ViaRow[] => {
  // 1) Prefer IDs from backend – they exactly match option values
  if (initialIds && initialIds.length) {
    // Deduplicate IDs so that the same via location does not appear multiple times
    const uniqueIds = Array.from(
      new Set(
        initialIds
          .map((v) => (v == null ? "" : String(v).trim()))
          .filter((v) => v !== "")
      )
    );

    const mappedById: ViaRow[] = uniqueIds.map((id, idx) => ({
      id: idx + 1,
      selectedId: id,
    }));

    return mappedById.length ? mappedById : [{ id: 1, selectedId: "" }];
  }

  // 2) Fallback: map by labels (for safety / older data)
  const labels = activeRoute?.initialSelected ?? [];
  if (!labels.length) {
    // No existing selection at all → start with a single empty row
    return [{ id: 1, selectedId: "" }];
  }

  const mapped = labels
    .map((label, idx) => {
      const opt = routes.find((r) => r.label === label);
      return {
        id: idx + 1,
        selectedId: opt ? String(opt.id) : "",
      };
    })
    .filter((r) => r.selectedId !== "");

  return mapped.length ? mapped : [{ id: 1, selectedId: "" }];
};


  const [rows, setRows] = useState<ViaRow[]>(buildInitialRows);

  // Reset rows whenever dialog opens for a leg, routes arrive, or IDs change
  useEffect(() => {
    if (open) {
      setRows(buildInitialRows());
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [
    open,
    activeRoute?.day,
    activeRoute?.date,
    routes.length,
    initialIds.join("|"),
  ]);

  const handleChangeRowValue = (rowId: number, newId: string) => {
    setRows((prev) =>
      prev.map((r) =>
        r.id === rowId ? { ...r, selectedId: newId } : r
      )
    );
  };

  const handleAddRow = () => {
    setRows((prev) => {
      if (prev.length >= maxRoutes) return prev;
      const nextId = prev.length ? prev[prev.length - 1].id + 1 : 1;
      return [...prev, { id: nextId, selectedId: "" }];
    });
  };

  const handleDeleteRow = (rowId: number) => {
    setRows((prev) => {
      const filtered = prev.filter((r) => r.id !== rowId);
      return filtered.length ? filtered : [{ id: 1, selectedId: "" }];
    });
  };

  const handleSubmit = () => {
    // Map selected IDs back to SimpleOption[] and remove duplicates
    const seen = new Set<string>();
    const selectedOptions: SimpleOption[] = [];

    rows.forEach((row) => {
      if (!row.selectedId) return;
      const opt = routes.find(
        (o) => String(o.id) === String(row.selectedId)
      );
      if (!opt) return;
      if (seen.has(String(opt.id))) return;
      seen.add(String(opt.id));
      selectedOptions.push(opt);
    });

    const limited = selectedOptions.slice(0, maxRoutes);
    onSubmit(limited);
  };

  const titleText =
    activeRoute && activeRoute.day && activeRoute.date
      ? `Day ${activeRoute.day} | ${activeRoute.date} | Via Route`
      : "Via Route";

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-2xl">
        <DialogHeader>
          <DialogTitle className="text-base font-semibold text-[#4a4260]">
            {titleText}
          </DialogTitle>
        </DialogHeader>

        {/* Source / Next visiting like PHP screen */}
        {activeRoute && (
          <div className="mt-2 mb-4 grid grid-cols-1 md:grid-cols-2 gap-6 border-b pb-4 border-[#ece2fb]">
            <div>
              <div className="text-xs uppercase tracking-wide text-[#a093c0]">
                Source Location
              </div>
              <div className="mt-1 text-sm font-medium text-[#4a4260]">
                {activeRoute.source || "-"}
              </div>
            </div>
            <div>
              <div className="text-xs uppercase tracking-wide text-[#a093c0]">
                Next Visiting Place
              </div>
              <div className="mt-1 text-sm font-medium text-[#4a4260]">
                {activeRoute.next || "-"}
              </div>
            </div>
          </div>
        )}

        {/* Via Route rows – behaves like PHP Choose Routes + + / delete */}
        <div className="space-y-3">
          <Label className="text-sm text-[#4a4260]">Via Routes</Label>

          {loading && (
            <div className="text-xs text-muted-foreground">
              Loading routes…
            </div>
          )}

          {!loading &&
            rows.map((row, index) => (
              <div
                key={row.id}
                className="flex items-center gap-2 w-full"
              >
                <select
                  className="flex-1 h-10 rounded-md border border-[#e5d7f6] bg-white px-3 text-sm text-[#4a4260] shadow-sm focus:outline-none focus:ring-1 focus:ring-[#c09bff]"
                  value={row.selectedId}
                  onChange={(e) =>
                    handleChangeRowValue(row.id, e.target.value)
                  }
                >
                  <option value="">Choose Routes</option>
                  {routes.map((opt) => (
                    <option key={opt.id} value={opt.id}>
                      {opt.label}
                    </option>
                  ))}
                </select>

                {/* + button – only on last row and only if below maxRoutes */}
                {index === rows.length - 1 && rows.length < maxRoutes && (
                  <Button
                    type="button"
                    size="icon"
                    className="h-9 w-9 rounded-md bg-gradient-to-r from-[#ff68b4] to-[#9b5cff]"
                    onClick={handleAddRow}
                    title="Add another via route"
                  >
                    +
                  </Button>
                )}

                {/* Delete button – for rows after the first, like PHP bin icon */}
                {rows.length > 1 && index > 0 && (
                  <Button
                    type="button"
                    size="icon"
                    variant="outline"
                    className="h-9 w-9 border-[#f1c4d0] text-[#c0392b]"
                    onClick={() => handleDeleteRow(row.id)}
                    title="Remove this via route"
                  >
                    🗑
                  </Button>
                )}
              </div>
            ))}
        </div>

        <DialogFooter className="mt-6 flex justify-end gap-2">
          <Button
            type="button"
            variant="outline"
            size="sm"
            onClick={() => onOpenChange(false)}
          >
            Cancel
          </Button>
          <Button type="button" size="sm" onClick={handleSubmit}>
            Submit
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
};
