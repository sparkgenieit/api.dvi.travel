// src/pages/hotels/HotelPreviewTab.tsx
interface HotelPreviewTabProps {
  hotelId: number | null;
  onFinish: () => void;
}

export const HotelPreviewTab = ({ hotelId, onFinish }: HotelPreviewTabProps) => {
  return (
    <div className="space-y-6">
      <h2 className="text-xl font-semibold">Preview</h2>
      
      <div className="text-sm text-muted-foreground">
        {hotelId ? `Previewing hotel #${hotelId}` : "No hotel selected"}
      </div>

      <div className="border border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-500">
        <p>Hotel preview will be displayed here.</p>
        <p className="text-sm mt-2">This tab shows a preview of how the hotel will appear to customers.</p>
      </div>

      <div className="flex justify-end pt-4">
        <button
          onClick={onFinish}
          className="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90"
        >
          Finish
        </button>
      </div>
    </div>
  );
};
