import React, { useState } from "react";
import {
  AccountsRow,
  PaymentModeOption,
  postPayment,
} from "@/services/accountsManagerApi";
import { Button } from "@/components/ui/button";

interface PayNowModalProps {
  row: AccountsRow;
  paymentModes: PaymentModeOption[];
  onClose: () => void;
  onSuccess: () => void;
}

const formatINR = (v: number) =>
  new Intl.NumberFormat("en-IN", {
    style: "currency",
    currency: "INR",
    minimumFractionDigits: 2,
  }).format(v);

export const PayNowModal: React.FC<PayNowModalProps> = ({
  row,
  paymentModes,
  onClose,
  onSuccess,
}) => {
  const [amount, setAmount] = useState(row.payable); // default to full balance
  const [modeOfPaymentId, setModeOfPaymentId] = useState<number | undefined>(
    undefined,
  );
  const [utrNumber, setUtrNumber] = useState("");
  const [processedBy, setProcessedBy] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [screenshotFileName, setScreenshotFileName] = useState<string>("");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);
    try {
      await postPayment({
        componentType: row.componentType as any, // already correct type
        accountsItineraryDetailsId: row.headerId,
        componentDetailId: row.id,
        routeDate: row.routeDate,
        amount: Number(amount),
        modeOfPaymentId,
        utrNumber: utrNumber || undefined,
        processedBy: processedBy || undefined,
        // NOTE: screenshot not yet sent to backend – UI only, functionality unchanged
      });
      onSuccess();
    } catch (err: any) {
      setError(err.message || "Payment failed");
    } finally {
      setSubmitting(false);
    }
  };

  const inhandAmount = row.payout ?? 0;
  const payableAmount = row.payable ?? 0;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
      {/* Centered card */}
      <div className="relative w-full max-w-xl rounded-2xl bg-white px-6 py-6 shadow-2xl md:px-10 md:py-8">
        {/* Close (X) button */}
        <button
          type="button"
          onClick={onClose}
          disabled={submitting}
          className="absolute right-4 top-4 text-xl text-gray-400 hover:text-gray-600"
        >
          ×
        </button>

        {/* Header */}
        <h2 className="mb-1 text-center text-xl font-semibold text-[#4a4260]">
          Add Payment
        </h2>
        <p className="mb-4 text-center text-xs text-[#8a7da5]">
          Quote ID:{" "}
          <span className="font-semibold text-[#e2349f]">{row.quoteId}</span>
          {" · "}
          Vendor:{" "}
          <span className="font-semibold text-[#4a4260]">{row.hotelName}</span>
        </p>

        {/* Small summary row (optional like background info) */}
        <div className="mb-6 grid gap-2 text-xs text-[#8a7da5] md:grid-cols-3">
          <div>
            <span className="font-semibold text-[#4a4260]">Component: </span>
            {row.componentType}
          </div>
          <div>
            <span className="font-semibold text-[#4a4260]">Agent: </span>
            {row.agent || "-"}
          </div>
          <div className="text-right">
            <span className="font-semibold text-[#4a4260]">Balance: </span>
            {formatINR(payableAmount)}
          </div>
        </div>

        {/* Form */}
        <form onSubmit={handleSubmit} className="space-y-4">
          {/* Processed By */}
          <div className="space-y-1">
            <label className="block text-sm font-medium text-[#4a4260]">
              Processed By <span className="text-pink-500">*</span>
            </label>
            <input
              className="h-10 w-full rounded-md border border-[#e4ddf3] px-3 text-sm outline-none focus:border-[#f057b8] focus:ring-2 focus:ring-[#fbc3e6]"
              placeholder="Processed By"
              value={processedBy}
              onChange={(e) => setProcessedBy(e.target.value)}
            />
          </div>

          {/* Payment Amount + chips */}
          <div className="space-y-1">
            <label className="block text-sm font-medium text-[#4a4260]">
              Payment Amount <span className="text-pink-500">*</span>
            </label>
            <input
              type="number"
              step="0.01"
              min={0}
              max={payableAmount}
              className="h-10 w-full rounded-md border border-[#e4ddf3] px-3 text-sm outline-none focus:border-[#f057b8] focus:ring-2 focus:ring-[#fbc3e6]"
              placeholder="Enter Payment Amount"
              value={amount}
              onChange={(e) => setAmount(Number(e.target.value))}
            />
            <div className="mt-2 flex flex-wrap gap-2 text-xs">
              <span className="rounded-full bg-[#ffe7f0] px-3 py-1 font-medium text-[#d12d80]">
                Inhand Amount: {formatINR(inhandAmount)}
              </span>
              <span className="rounded-full bg-[#e5fff1] px-3 py-1 font-medium text-[#0f9c34]">
                Payable Amount: {formatINR(payableAmount)}
              </span>
            </div>
          </div>

          {/* Mode of Payment */}
          <div className="space-y-1">
            <label className="block text-sm font-medium text-[#4a4260]">
                Mode of Payment <span className="text-pink-500">*</span>
                </label>
                <select
                className="h-10 w-full rounded-md border border-[#e4ddf3] bg-white px-3 text-sm outline-none focus:border-[#f057b8] focus:ring-2 focus:ring-[#fbc3e6]"
                value={modeOfPaymentId ?? ""}
                onChange={(e) =>
                    setModeOfPaymentId(
                    e.target.value ? Number(e.target.value) : undefined,
                    )
                }
                >
                <option value="">Select Payment Method</option>
                {paymentModes.map((m) => (
                    <option key={m.id} value={m.id}>
                    {m.label}
                    </option>
                ))}
                </select>
          </div>

          {/* UTR Number */}
          <div className="space-y-1">
            <label className="block text-sm font-medium text-[#4a4260]">
              UTR Number <span className="text-pink-500">*</span>
            </label>
            <input
              className="h-10 w-full rounded-md border border-[#e4ddf3] px-3 text-sm outline-none focus:border-[#f057b8] focus:ring-2 focus:ring-[#fbc3e6]"
              placeholder="UTR Number"
              value={utrNumber}
              onChange={(e) => setUtrNumber(e.target.value)}
            />
          </div>

          {/* Payment Screenshot (UI only for now) */}
          <div className="space-y-1">
            <label className="block text-sm font-medium text-[#4a4260]">
              Payment Screenshot
            </label>
            <label className="flex h-10 cursor-pointer items-center justify-between rounded-md border border-dashed border-[#e4ddf3] bg-[#faf7ff] px-3 text-xs text-[#8a7da5] hover:bg-[#f2e9ff]">
              <span>{screenshotFileName || "Choose File"}</span>
              <span className="rounded-full bg-white px-3 py-1 text-[11px] font-medium text-[#f057b8] shadow-sm">
                Browse
              </span>
              <input
                type="file"
                accept="image/*"
                className="hidden"
                onChange={(e) => {
                  const file = e.target.files?.[0];
                  setScreenshotFileName(file ? file.name : "");
                }}
              />
            </label>
          </div>

          {error && (
            <p className="pt-1 text-xs text-red-600 whitespace-pre-wrap">
              {error}
            </p>
          )}

          {/* Buttons */}
          <div className="mt-4 flex items-center justify-end gap-3">
            <Button
              type="button"
              variant="outline"
              onClick={onClose}
              disabled={submitting}
              className="h-9 rounded-full border-[#e0d4ff] bg-white px-6 text-xs font-medium text-[#7a6c96] hover:bg-[#f5f0ff]"
            >
              Cancel
            </Button>
            <Button
              type="submit"
              disabled={submitting || amount <= 0 || !modeOfPaymentId}
              className="h-9 rounded-full bg-gradient-to-r from-[#f057b8] to-[#a44fe8] px-8 text-xs font-semibold text-white shadow-md hover:from-[#e348aa] hover:to-[#9240d8]"
            >
              {submitting ? "Saving…" : "Save"}
            </Button>
          </div>
        </form>
      </div>
    </div>
  );
};
