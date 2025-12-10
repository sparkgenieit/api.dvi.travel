// FILE: src/pages/CreateItinerary/SaveRouteConfirmDialog.tsx

import React from "react";

type Props = {
  open: boolean;
  isSaving: boolean;
  onClose: () => void;
  onSaveSameRoute: () => void;
  onOptimizeRoute: () => void;
};

export const SaveRouteConfirmDialog: React.FC<Props> = ({
  open,
  isSaving,
  onClose,
  onSaveSameRoute,
  onOptimizeRoute,
}) => {
  if (!open) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
      <div className="relative w-full max-w-lg rounded-2xl bg-white p-8 text-center shadow-2xl">
        <button
          type="button"
          onClick={onClose}
          className="absolute right-4 top-4 text-slate-400 hover:text-slate-600"
        >
          ×
        </button>

        <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#ffe9d6]">
          <span className="text-3xl">🧭</span>
        </div>

        <p className="text-sm text-slate-600">
          Do you really want to proceed with the{" "}
          <span className="font-semibold text-slate-900">same route</span>{" "}
          details?
        </p>

        <div className="mt-8 flex items-center justify-center gap-4">
          <button
            type="button"
            onClick={onSaveSameRoute}
            disabled={isSaving}
            className="min-w-[170px] rounded-md bg-[#19b96b] px-6 py-2 text-sm font-semibold text-white shadow hover:bg-[#12a05b] disabled:opacity-60"
          >
            {isSaving ? "Saving..." : "Proceed with same Route"}
          </button>

          <button
            type="button"
            onClick={onOptimizeRoute}
            disabled={isSaving}
            className="min-w-[170px] rounded-md bg-[#e0e0e0] px-6 py-2 text-sm font-semibold text-slate-700 hover:bg-[#d4d4d4] disabled:opacity-60"
          >
            {isSaving ? "Saving..." : "Optimize route"}
          </button>
        </div>
      </div>
    </div>
  );
};
