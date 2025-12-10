// src/pages/hotels/HotelRoomsTab.tsx
import { Plus } from "lucide-react";

interface HotelRoomsTabProps {
  hotelId: number | null;
  onNext: () => void;
}

export const HotelRoomsTab = ({ hotelId, onNext }: HotelRoomsTabProps) => {
  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h2 className="text-xl font-semibold">Rooms</h2>
        <button className="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">
          <Plus className="w-4 h-4" />
          Add Rooms
        </button>
      </div>
      
      <div className="text-sm text-muted-foreground">
        {hotelId ? `Editing hotel #${hotelId}` : "No hotel selected"}
      </div>

      <div className="border border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-500">
        <p>Room management interface will be implemented here.</p>
        <p className="text-sm mt-2">This tab allows you to add and manage different room types.</p>
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
