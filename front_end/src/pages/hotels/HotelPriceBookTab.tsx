// src/pages/hotels/HotelPriceBookTab.tsx
interface HotelPriceBookTabProps {
  hotelId: number | null;
  onNext: () => void;
}

export const HotelPriceBookTab = ({ hotelId, onNext }: HotelPriceBookTabProps) => {
  return (
    <div className="space-y-6">
      <h2 className="text-xl font-semibold">Price Book</h2>
      
      <div className="text-sm text-muted-foreground">
        {hotelId ? `Editing hotel #${hotelId}` : "No hotel selected"}
      </div>

      <div className="border border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-500">
        <p>Price book management interface will be implemented here.</p>
        <p className="text-sm mt-2">This tab allows you to manage room pricing and rates.</p>
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
