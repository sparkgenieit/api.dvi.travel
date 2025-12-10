// FILE: src/pages/CreateItinerary/useRoomsAndTravellers.ts

import { useState } from "react";

export type RoomRow = {
  id: number;
  roomCount: number;
  adults: number;
  children: number;
  infants: number;
  childrenDetails: {
    age: number | "";
    bedType: string;
  }[];
};

export type TravellersResult = {
  totalAdults: number;
  totalChildren: number;
  totalInfants: number;
  travellerRows: {
    room_id: number;
    traveller_type: 1 | 2 | 3;
    traveller_age?: string;
    child_bed_type?: number;
  }[];
};

export function useRoomsAndTravellers() {
  const [rooms, setRooms] = useState<RoomRow[]>([
    {
      id: 1,
      roomCount: 1,
      adults: 2,
      children: 0,
      infants: 0,
      childrenDetails: [],
    },
  ]);

  const addRoom = () => {
    setRooms((prev) => {
      const last = prev[prev.length - 1];
      return [
        ...prev,
        {
          id: last.id + 1,
          roomCount: 1,
          adults: 2,
          children: 0,
          infants: 0,
          childrenDetails: [],
        },
      ];
    });
  };

  const removeRoom = (idToRemove: number) => {
    setRooms((prev) => prev.filter((r) => r.id !== idToRemove));
  };

  const buildTravellers = (): TravellersResult => {
    let totalAdults = 0;
    let totalChildren = 0;
    let totalInfants = 0;

    const travellerRows: TravellersResult["travellerRows"] = [];

    for (const room of rooms) {
      const adults = room.adults ?? 0;
      const children = room.children ?? 0;
      const infants = room.infants ?? 0;

      totalAdults += adults;
      totalChildren += children;
      totalInfants += infants;

      // Adults
      for (let i = 0; i < adults; i++) {
        travellerRows.push({
          room_id: room.id,
          traveller_type: 1, // Adult
        });
      }

      // Children
      for (let i = 0; i < children; i++) {
        const childInfo = room.childrenDetails?.[i];
        travellerRows.push({
          room_id: room.id,
          traveller_type: 2, // Child
          traveller_age:
            childInfo && childInfo.age !== ""
              ? String(childInfo.age)
              : undefined,
          child_bed_type:
            childInfo &&
            childInfo.bedType &&
            !Number.isNaN(Number(childInfo.bedType))
              ? Number(childInfo.bedType)
              : 0,
        });
      }

      // Infants
      for (let i = 0; i < infants; i++) {
        travellerRows.push({
          room_id: room.id,
          traveller_type: 3, // Infant
        });
      }
    }

    return {
      totalAdults,
      totalChildren,
      totalInfants,
      travellerRows,
    };
  };

  return {
    rooms,
    setRooms,
    addRoom,
    removeRoom,
    buildTravellers,
  };
}
