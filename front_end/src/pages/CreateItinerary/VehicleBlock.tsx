// FILE: src/pages/CreateItinerary/VehicleBlock.tsx

import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Trash2 } from "lucide-react";
import { SimpleOption } from "@/services/itineraryDropdownsMock";
import { useToast } from "@/components/ui/use-toast";

type VehicleRow = {
  id: number;
  type: string; // vehicle_type_id from DB as string
  count: number;
};

type ValidationErrors = {
  [key: string]: string;
};

type VehicleBlockProps = {
  vehicleTypes: SimpleOption[]; // fetched via fetchVehicleTypes()
  vehicles: VehicleRow[];
  setVehicles: React.Dispatch<React.SetStateAction<VehicleRow[]>>;

  // now optional so CreateItinerary can pass only what it has
  itineraryPreference?: "vehicle" | "hotel" | "both";
  addVehicle?: () => void;
  removeVehicle?: (id: number) => void;

  // optional validation
  validationErrors?: ValidationErrors;
};

export const VehicleBlock = ({
  itineraryPreference,
  vehicles,
  setVehicles,
  vehicleTypes,
  addVehicle,
  removeVehicle,
  validationErrors,
}: VehicleBlockProps) => {
  const { toast } = useToast();

  const pref = itineraryPreference ?? "both";

  // Only show block when itineraryPreference allows vehicles
  if (!(pref === "vehicle" || pref === "both")) {
    return null;
  }

  const hasVehicleTypes = vehicleTypes && vehicleTypes.length > 0;

  const internalAddVehicle = () => {
    setVehicles((prev) => [
      ...prev,
      {
        id: prev.length ? prev[prev.length - 1].id + 1 : 1,
        type: "",
        count: 1,
      },
    ]);
  };

  const internalRemoveVehicle = (id: number) => {
    setVehicles((prev) => prev.filter((v) => v.id !== id));
  };

  const vehicleTypeError = validationErrors?.vehicleType;

  return (
    <Card className="border border-[#efdef8] rounded-lg bg-white shadow-none">
      <CardHeader className="pb-2">
        <CardTitle className="text-base font-semibold text-[#4a4260]">
          Vehicle
        </CardTitle>
      </CardHeader>

      <CardContent className="space-y-4">
        {vehicles.map((vehicle, idx) => (
          <div
            key={vehicle.id}
            className="border border-[#f1d8ff] rounded-md p-3"
          >
            <div className="flex items-center justify-between mb-3">
              <p className="text-sm font-medium text-[#4a4260]">
                Vehicle #{idx + 1}
              </p>
              {vehicles.length > 1 && (
                <Button
                  variant="ghost"
                  size="icon"
                  onClick={() =>
                    (removeVehicle ?? internalRemoveVehicle)(vehicle.id)
                  }
                  className="h-7 w-7 text-[#e63963]"
                >
                  <Trash2 className="h-4 w-4" />
                </Button>
              )}
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
              {/* Vehicle Type */}
              <div
                className={
                  vehicleTypeError
                    ? "border border-red-500 rounded-md p-2"
                    : ""
                }
                data-field="vehicleType"
              >
                <Label className="text-sm block mb-1">
                  Vehicle Type <span className="text-red-500">*</span>
                </Label>

                <Select
                  value={vehicle.type}
                  onValueChange={(val) =>
                    setVehicles((prev) =>
                      prev.map((v) =>
                        v.id === vehicle.id ? { ...v, type: val } : v
                      )
                    )
                  }
                  disabled={!hasVehicleTypes}
                  onOpenChange={(open) => {
                    if (open && !hasVehicleTypes) {
                      toast({
                        variant: "destructive",
                        title: "Please fill Route Details first",
                        description:
                          "Vehicle types will be available once you add at least one day in Route Details.",
                      });
                    }
                  }}
                >
                  <SelectTrigger className="h-9 border-[#e5d7f6]">
                    <SelectValue
                      placeholder={
                        hasVehicleTypes
                          ? "Select Vehicle Type"
                          : "Please Fill up the Route Details First"
                      }
                    />
                  </SelectTrigger>
                  <SelectContent
                    position="popper"
                    side="bottom"
                    align="start"
                    className="max-h-56 overflow-y-auto"
                  >
                    {vehicleTypes.map((item) => (
                      <SelectItem key={item.id} value={String(item.id)}>
                        {item.label}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>

                {vehicleTypeError && (
                  <p className="mt-1 text-xs text-red-500">
                    {vehicleTypeError}
                  </p>
                )}
              </div>

              {/* Vehicle Count */}
              <div>
                <Label className="text-sm block mb-1">
                  Vehicle Count <span className="text-red-500">*</span>
                </Label>
                <Input
                  value={vehicle.count}
                  onChange={(e) =>
                    setVehicles((prev) =>
                      prev.map((v) =>
                        v.id === vehicle.id
                          ? {
                              ...v,
                              count: Math.max(
                                1,
                                Number.isNaN(Number(e.target.value))
                                  ? 1
                                  : Number(e.target.value)
                              ),
                            }
                          : v
                      )
                    )
                  }
                  type="number"
                  min={1}
                  className="h-9 border-[#e5d7f6]"
                />
              </div>
            </div>
          </div>
        ))}

        <Button
          onClick={() => {
            if (!hasVehicleTypes) {
              toast({
                variant: "destructive",
                title: "Please fill Route Details first",
                description:
                  "You can add vehicles only after filling the Route Details section.",
              });
              return;
            }
            (addVehicle ?? internalAddVehicle)();
          }}
          className="bg-[#f054b5] hover:bg-[#e249a9]"
        >
          + Add Vehicle
        </Button>
      </CardContent>
    </Card>
  );
};
