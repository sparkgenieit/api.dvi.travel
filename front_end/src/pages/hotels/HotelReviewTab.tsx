// src/pages/hotels/HotelReviewTab.tsx
interface HotelReviewTabProps {
  hotelId: number | null;
  onNext: () => void;
}

export const HotelReviewTab = ({ hotelId, onNext }: HotelReviewTabProps) => {
  return (
    <div className="space-y-6">
      <h2 className="text-xl font-semibold">Review & Feedback</h2>
      
      <div className="text-sm text-muted-foreground">
        {hotelId ? `Editing hotel #${hotelId}` : "No hotel selected"}
      </div>

      <div className="border border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-500">
        <p>Review and feedback interface will be implemented here.</p>
        <p className="text-sm mt-2">This tab allows you to review all hotel information before publishing.</p>
      </div>

      <div className="flex justify-end pt-4">
        <button
          onClick={onNext}
          className="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90"
        >
          Save & Continue
        </button>
      </div>
    </div>
  );
};
