// FILE: src/pages/CreateItinerary/RoomsBlock.tsx

import { useEffect, useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Trash2 } from "lucide-react";
import { toast } from "@/components/ui/use-toast";

export type RoomRow = {
  id: number;
  adults: number;
  children: number; // count
  infants: number;
  roomCount: number;
};

type RoomsBlockProps = {
  itineraryPreference: "vehicle" | "hotel" | "both";
  rooms: RoomRow[];
  setRooms: React.Dispatch<React.SetStateAction<RoomRow[]>>;
  addRoom: () => void;
  removeRoom: (id: number) => void;
};

// exact combinations from PHP `validCombinations`
const VALID_COMBINATIONS: Array<{ adult: number; child: number; infant: number }> = [
  { adult: 1, child: 0, infant: 0 },
  { adult: 1, child: 0, infant: 1 },
  { adult: 1, child: 0, infant: 2 },
  { adult: 1, child: 0, infant: 3 },
  { adult: 1, child: 0, infant: 4 },
  { adult: 1, child: 1, infant: 0 },
  { adult: 1, child: 1, infant: 1 },
  { adult: 1, child: 1, infant: 2 },
  { adult: 1, child: 1, infant: 3 },
  { adult: 1, child: 2, infant: 0 },
  { adult: 1, child: 2, infant: 1 },
  { adult: 1, child: 2, infant: 2 },
  { adult: 2, child: 0, infant: 0 },
  { adult: 2, child: 0, infant: 1 },
  { adult: 2, child: 0, infant: 2 },
  { adult: 2, child: 0, infant: 3 },
  { adult: 2, child: 1, infant: 0 },
  { adult: 2, child: 1, infant: 1 },
  { adult: 2, child: 1, infant: 2 },
  { adult: 2, child: 2, infant: 0 },
  { adult: 2, child: 2, infant: 1 },
  { adult: 3, child: 0, infant: 0 },
  { adult: 3, child: 0, infant: 1 },
  { adult: 3, child: 0, infant: 2 },
  { adult: 3, child: 1, infant: 0 },
  { adult: 3, child: 1, infant: 1 },
  { adult: 3, child: 2, infant: 0 },
];

const MAX_ADULTS_PER_ROOM = 3;

type ChildDetail = {
  id: number;
  age: string;
  bedType: "Without Bed" | "With Bed";
};

type ChildrenDetailsMap = Record<string, ChildDetail[]>;

export const RoomsBlock = ({
  itineraryPreference,
  rooms,
  setRooms,
  addRoom,
  removeRoom,
}: RoomsBlockProps) => {
  if (!(itineraryPreference === "hotel" || itineraryPreference === "both")) {
    return null;
  }

  const [childrenDetailsMap, setChildrenDetailsMap] =
    useState<ChildrenDetailsMap>({});

  const totalRooms = rooms.length || 1;

  const validateCombination = (
    adult: number,
    child: number,
    infant: number
  ): boolean => {
      if (adult > MAX_ADULTS_PER_ROOM) {
        toast({
          title: "Maximum of 3 adults only allowed per room",
          variant: "destructive",
        });
        return false;
      }

      const ok = VALID_COMBINATIONS.some(
        (c) =>
          c.adult === adult && c.child === child && c.infant === infant
      );

      if (ok) return true;

      toast({
        title: "Reached the maximum of allowed room counts",
        variant: "destructive",
      });

      return false;
  };

  const updateRoom = (
    roomId: number,
    patch: Partial<Omit<RoomRow, "id">>
  ) => {
    setRooms((prev) =>
      prev.map((r) => (r.id === roomId ? { ...r, ...patch } : r))
    );
  };

  const tryUpdateCounts = (
    room: RoomRow,
    nextAdults: number,
    nextChildren: number,
    nextInfants: number,
    opts?: { skipValidate?: boolean }
  ) => {
    if (!opts?.skipValidate) {
      if (!validateCombination(nextAdults, nextChildren, nextInfants)) {
        return;
      }
    }
    updateRoom(room.id, {
      adults: nextAdults,
      children: nextChildren,
      infants: nextInfants,
    });
  };

  // sync childrenDetailsMap with children count
  useEffect(() => {
    setChildrenDetailsMap((prev) => {
      const next: ChildrenDetailsMap = { ...prev };

      rooms.forEach((room) => {
        const key = String(room.id);
        const existing = next[key] || [];
        const desired = room.children;

        if (existing.length < desired) {
          const arr = [...existing];
          let lastId = arr.length ? arr[arr.length - 1].id : 0;
          const toAdd = desired - existing.length;
          for (let i = 0; i < toAdd; i++) {
            lastId += 1;
            arr.push({
              id: lastId,
              age: "",
              bedType: "Without Bed",
            });
          }
          next[key] = arr;
        } else if (existing.length > desired) {
          next[key] = existing.slice(0, desired);
        } else {
          next[key] = existing;
        }
      });

      Object.keys(next).forEach((key) => {
        const roomId = Number(key);
        if (!rooms.some((r) => r.id === roomId)) {
          delete next[key];
        }
      });

      return next;
    });
  }, [rooms]);

  const handleTotalRoomsChange = (value: number) => {
    if (!Number.isFinite(value) || value < 1) value = 1;

    setRooms((prev) => {
      const current = [...prev];

      if (value === current.length) return current;

      if (value > current.length) {
        let lastId = current.length ? current[current.length - 1].id : 0;
        const toAdd = value - current.length;
        for (let i = 0; i < toAdd; i++) {
          lastId += 1;
          current.push({
            id: lastId,
            adults: 2,
            children: 0,
            infants: 0,
            roomCount: 1,
          });
        }
        return current;
      }

      if (value < current.length) {
        current.length = value;
        return current;
      }

      return current;
    });
  };

  const handleChildAgeChange = (
    roomId: number,
    childId: number,
    value: string
  ) => {
    const key = String(roomId);
    setChildrenDetailsMap((prev) => {
      const roomChildren = prev[key] ? [...prev[key]] : [];
      const idx = roomChildren.findIndex((c) => c.id === childId);
      if (idx !== -1) {
        roomChildren[idx] = { ...roomChildren[idx], age: value };
      }
      return { ...prev, [key]: roomChildren };
    });
  };

  const handleChildBedTypeChange = (
    roomId: number,
    childId: number,
    bedType: "Without Bed" | "With Bed"
  ) => {
    const key = String(roomId);
    setChildrenDetailsMap((prev) => {
      const roomChildren = prev[key] ? [...prev[key]] : [];
      const idx = roomChildren.findIndex((c) => c.id === childId);
      if (idx !== -1) {
        roomChildren[idx] = { ...roomChildren[idx], bedType };
      }
      return { ...prev, [key]: roomChildren };
    });
  };

  return (
    <div className="border border-dashed border-[#c985d7] rounded-lg bg-[#fff9ff] p-3">
      {rooms.map((room, idx) => {
        const key = String(room.id);
        const childDetails = childrenDetailsMap[key] || [];

        return (
          <div
            key={room.id}
            className={idx > 0 ? "mt-3 pt-3 border-t border-[#ead1f2]" : ""}
          >
            {/* header */}
            <div className="flex items-center justify-between mb-2">
              <div className="flex flex-wrap items-center gap-2">
                <p className="text-sm font-medium text-[#4a4260] mb-0">
                  #Room {idx + 1}
                </p>
                <div className="flex flex-wrap items-center gap-3 text-[11px] text-[#4a4260]">
                  <span className="flex items-center gap-1">
                    [ Adult{" "}
                    <span className="text-[#6c6f82] flex items-center gap-1">
                      <i className="ti ti-info-circle ms-1" />
                      <small>Age: Above 11,</small>
                    </span>
                  </span>
                  <span className="flex items-center gap-1">
                    Child{" "}
                    <span className="text-[#6c6f82] flex items-center gap-1">
                      <i className="ti ti-info-circle ms-1" />
                      <small>Age: 5 to 10,</small>
                    </span>
                  </span>
                  <span className="flex items-center gap-1">
                    Infant{" "}
                    <span className="text-[#6c6f82] flex items-center gap-1">
                      <i className="ti ti-info-circle ms-1" />
                      <small>Age: Below 5</small>
                    </span>{" "}
                    ]
                  </span>
                </div>
              </div>

              {rooms.length > 1 && (
                <Button
                  variant="ghost"
                  size="icon"
                  onClick={() => removeRoom(room.id)}
                  className="h-7 w-7 text-[#d03265]"
                >
                  <Trash2 className="h-4 w-4" />
                </Button>
              )}
            </div>

            {/* counters row */}
            <div className="flex flex-wrap items-start gap-4 mb-2">
              {/* Adults */}
              <div className="flex flex-col items-start gap-1">
                <div className="flex items-center border rounded-md bg-white">
                  <Button
                    type="button"
                    variant="ghost"
                    className="h-7 px-2"
                    onClick={() =>
                      tryUpdateCounts(
                        room,
                        Math.max(room.adults - 1, 1),
                        room.children,
                        room.infants
                      )
                    }
                  >
                    -
                  </Button>
                  <span className="px-3 text-sm select-none">
                    {room.adults}
                  </span>
                  <Button
                    type="button"
                    variant="ghost"
                    className="h-7 px-2"
                    onClick={() =>
                      tryUpdateCounts(
                        room,
                        room.adults + 1,
                        room.children,
                        room.infants
                      )
                    }
                  >
                    +
                  </Button>
                </div>
              </div>

              {/* Children */}
              <div className="flex flex-col items-start gap-1">
                <div className="flex items-center border rounded-md bg-white">
                  <Button
                    type="button"
                    variant="ghost"
                    className="h-7 px-2"
                    onClick={() =>
                      tryUpdateCounts(
                        room,
                        room.adults,
                        Math.max(room.children - 1, 0),
                        room.infants
                      )
                    }
                  >
                    -
                  </Button>
                  <span className="px-3 text-sm select-none">
                    {room.children}
                  </span>
                  <Button
                    type="button"
                    variant="ghost"
                    className="h-7 px-2"
                    onClick={() =>
                      tryUpdateCounts(
                        room,
                        room.adults,
                        room.children + 1,
                        room.infants
                      )
                    }
                  >
                    +
                  </Button>
                </div>
              </div>

              {/* Infant */}
              <div className="flex flex-col items-start gap-1">
                {room.infants === 0 ? (
                  <Button
                    type="button"
                    variant="outline"
                    className="h-7 text-xs border-[#d39ce8]"
                    onClick={() =>
                      tryUpdateCounts(room, room.adults, room.children, 1)
                    }
                  >
                    + Add Infant
                  </Button>
                ) : (
                  <div className="flex items-center border rounded-md bg-white">
                    <Button
                      type="button"
                      variant="ghost"
                      className="h-7 px-2"
                      onClick={() =>
                        // PHP: infant minus doesn't validate, just decreases
                        tryUpdateCounts(
                          room,
                          room.adults,
                          room.children,
                          Math.max(room.infants - 1, 0),
                          { skipValidate: true }
                        )
                      }
                    >
                      -
                    </Button>
                    <span className="px-3 text-sm select-none">
                      {room.infants}
                    </span>
                    <Button
                      type="button"
                      variant="ghost"
                      className="h-7 px-2"
                      onClick={() =>
                        tryUpdateCounts(
                          room,
                          room.adults,
                          room.children,
                          room.infants + 1
                        )
                      }
                    >
                      +
                    </Button>
                  </div>
                )}
              </div>
            </div>

            {/* child age + bed type chips */}
            {childDetails.length > 0 && (
              <div className="flex flex-wrap gap-4 mt-1">
                {childDetails.map((child, cIdx) => (
                  <div
                    key={child.id}
                    className="py-2 flex flex-col items-start gap-1"
                  >
                    <div className="flex items-center gap-1">
                      <Input
                        type="number"
                        min={5}
                        max={10}
                        placeholder="Age 5-10"
                        value={child.age}
                        onChange={(e) =>
                          handleChildAgeChange(
                            room.id,
                            child.id,
                            e.target.value
                          )
                        }
                        className="w-[80px] h-8 text-center px-1 py-1"
                      />
                      <select
                        className="h-8 text-xs border border-[#dee0ee] rounded px-2"
                        value={child.bedType}
                        onChange={(e) =>
                          handleChildBedTypeChange(
                            room.id,
                            child.id,
                            e.target.value as "Without Bed" | "With Bed"
                          )
                        }
                      >
                        <option value="Without Bed">Without Bed</option>
                        <option value="With Bed">With Bed</option>
                      </select>
                    </div>
                    <div className="text-[11px] text-center text-[#4a4260] ml-1">
                      Children #{cIdx + 1}
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        );
      })}

      {/* total + add rooms */}
      <div className="mt-3 flex items-center gap-3">
        <span className="text-xs text-muted-foreground">Total</span>
        <Input
          type="number"
          min={1}
          className="w-16 h-8"
          value={totalRooms}
          onChange={(e) =>
            handleTotalRoomsChange(Number(e.target.value) || 1)
          }
        />
        <Button
          type="button"
          variant="link"
          className="h-8 px-0 text-primary"
          onClick={() => handleTotalRoomsChange(totalRooms + 1)}
        >
          <span className="inline-flex items-center text-sm">
            <span className="mr-1">+</span> Add Rooms
          </span>
        </Button>
      </div>
    </div>
  );
};
