// FILE: src/pages/CreateItinerary/RouteDetailsBlock.tsx

import { useEffect, useState } from "react";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  AutoSuggestSelect,
  AutoSuggestOption,
} from "@/components/AutoSuggestSelect";
import {
  LocationOption,
  fetchLocations,
} from "@/services/itineraryDropdownsMock";

type RouteDetailRow = {
  day: number;
  date: string;
  source: string;
  next: string;
  via: string;
  directVisit: string;
};

type ValidationErrors = {
  [key: string]: string;
};

type RouteDetailsBlockProps = {
  routeDetails: RouteDetailRow[];
  setRouteDetails: React.Dispatch<React.SetStateAction<RouteDetailRow[]>>;
  locations: LocationOption[];

  // optional hooks from parent
  onOpenViaRoutes?: (row: RouteDetailRow) => void;
  addDay?: () => void;

  // optional validation from parent
  validationErrors?: ValidationErrors;
};

export const RouteDetailsBlock = ({
  routeDetails,
  setRouteDetails,
  locations,
  onOpenViaRoutes,
  addDay,
  validationErrors,
}: RouteDetailsBlockProps) => {
  // Global fallback options (like PHP selectize list)
  const globalLocationOptions: AutoSuggestOption[] = locations.map((loc) => ({
    value: loc.name,
    label: loc.name,
  }));

  // Row-specific NEXT DESTINATION options (per source)
  const [destinationOptionsMap, setDestinationOptionsMap] = useState<
    Record<number, AutoSuggestOption[]>
  >({});
  const [loadedSources, setLoadedSources] = useState<Record<number, string>>(
    {}
  );

  // For each row that has a source, load destination list if we haven't yet
  useEffect(() => {
    routeDetails.forEach((row, idx) => {
      if (!row.source) return;

      const alreadyLoadedForThisSource =
        loadedSources[idx] && loadedSources[idx] === row.source;
      if (alreadyLoadedForThisSource) return;

      (async () => {
        try {
          const destLocations = await fetchLocations("destination", row.source);
          const opts: AutoSuggestOption[] = destLocations.map((loc) => ({
            value: loc.name,
            label: loc.name,
          }));
          setDestinationOptionsMap((prev) => ({
            ...prev,
            [idx]: opts,
          }));
          setLoadedSources((prev) => ({
            ...prev,
            [idx]: row.source,
          }));
        } catch (err) {
          console.error(
            "Failed to load destination locations for",
            row.source,
            err
          );
        }
      })();
    });
  }, [routeDetails, loadedSources]);

  const handleAddDay = () => {
    if (addDay) {
      addDay();
      return;
    }
    // default behaviour if parent doesn't supply addDay
    setRouteDetails((prev) => {
      const nextDay = prev.length ? prev[prev.length - 1].day + 1 : 1;
      return [
        ...prev,
        {
          day: nextDay,
          date: "",
          source: "",
          next: "",
          via: "",
          directVisit: "Yes",
        },
      ];
    });
  };

  const firstRouteSourceError = validationErrors?.firstRouteSource;
  const firstRouteNextError = validationErrors?.firstRouteNext;

  return (
    <Card className="border border-[#efdef8] rounded-lg bg-white shadow-none">
      <CardHeader className="pb-2">
        <CardTitle className="text-base font-semibold text-[#4a4260]">
          Route Details
        </CardTitle>
      </CardHeader>
      <CardContent className="pt-0">
        <Table>
          <TableHeader>
            <TableRow className="bg-[#faf1ff]">
              <TableHead className="text-xs text-[#4a4260]">DAY</TableHead>
              <TableHead className="text-xs text-[#4a4260]">DATE</TableHead>
              <TableHead className="text-xs text-[#4a4260]">
                SOURCE DESTINATION
              </TableHead>
              <TableHead className="text-xs text-[#4a4260]">
                NEXT DESTINATION
              </TableHead>
              <TableHead className="text-xs text-[#4a4260] text-center">
                VIA ROUTE
              </TableHead>
              <TableHead className="text-xs text-[#4a4260] text-center">
                DIRECT DESTINATION VISIT
              </TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {routeDetails.map((row, idx) => {
              const rowSpecificOptions =
                destinationOptionsMap[idx] &&
                destinationOptionsMap[idx]!.length > 0
                  ? destinationOptionsMap[idx]!
                  : globalLocationOptions;

              const isFirstRow = idx === 0;

              return (
                <TableRow key={idx}>
                  <TableCell>{`DAY ${row.day}`}</TableCell>

                  {/* DATE – read-only from selected range */}
                  <TableCell>
                    <Input
                      readOnly
                      placeholder="DD/MM/YYYY"
                      value={row.date}
                      className="h-8 rounded-md border-[#e5d7f6] bg-[#f9f4ff] cursor-not-allowed text-xs"
                    />
                  </TableCell>

                  {/* SOURCE DESTINATION – read only, but validated for first row */}
                  <TableCell
                    data-field={isFirstRow ? "firstRouteSource" : undefined}
                    className={isFirstRow && firstRouteSourceError ? "align-top" : ""}
                  >
                    <div
                      className={
                        isFirstRow && firstRouteSourceError
                          ? "border border-red-500 rounded-md p-1"
                          : ""
                      }
                    >
                      <Input
                        readOnly
                        placeholder="Source Location"
                        value={row.source}
                        className="h-8 rounded-md border-[#e5d7f6] bg-[#f9f4ff] cursor-not-allowed"
                      />
                    </div>
                    {isFirstRow && firstRouteSourceError && (
                      <p className="mt-1 text-xs text-red-500">
                        {firstRouteSourceError}
                      </p>
                    )}
                  </TableCell>

                  {/* NEXT DESTINATION – autosuggest, chained to next row source */}
                  <TableCell
                    data-field={isFirstRow ? "firstRouteNext" : undefined}
                    className={isFirstRow && firstRouteNextError ? "align-top" : ""}
                  >
                    <div
                      className={
                        isFirstRow && firstRouteNextError
                          ? "border border-red-500 rounded-md p-1"
                          : ""
                      }
                    >
                      <AutoSuggestSelect
                        mode="single"
                        value={row.next}
                        onChange={(val) =>
                          setRouteDetails((prev) => {
                            const updated = [...prev];
                            const chosen = (val as string) || "";

                            updated[idx] = {
                              ...updated[idx],
                              next: chosen,
                            };

                            // PHP behaviour: selected NEXT becomes SOURCE of next day
                            if (idx + 1 < updated.length) {
                              updated[idx + 1] = {
                                ...updated[idx + 1],
                                source: chosen,
                              };
                            }

                            return updated;
                          })
                        }
                        options={rowSpecificOptions}
                        placeholder="Next Destination"
                      />
                    </div>
                    {isFirstRow && firstRouteNextError && (
                      <p className="mt-1 text-xs text-red-500">
                        {firstRouteNextError}
                      </p>
                    )}
                  </TableCell>

                  {/* VIA ROUTE – icon button opens popup */}
                  <TableCell className="text-center">
                    <button
                      type="button"
                      onClick={() => onOpenViaRoutes?.(row)}
                      className="inline-flex items-center justify-center h-8 w-8 rounded-md border border-[#e5d7f6] bg-white hover:bg-[#f5e8ff]"
                    >
                      <i className="ti ti-route ti-tada-hover text-[#c53fb0]" />
                    </button>
                  </TableCell>

                  {/* DIRECT DESTINATION VISIT – toggle switch */}
                  <TableCell className="text-center">
                    <label className="switch switch-sm">
                      <input
                        type="checkbox"
                        className="switch-input"
                        checked={row.directVisit === "Yes"}
                        onChange={(e) =>
                          setRouteDetails((prev) =>
                            prev.map((r, i) =>
                              i === idx
                                ? {
                                    ...r,
                                    directVisit: e.target.checked ? "Yes" : "",
                                  }
                                : r
                            )
                          )
                        }
                      />
                      <span className="switch-toggle-slider">
                        <span className="switch-on">
                          <i className="ti ti-check" />
                        </span>
                        <span className="switch-off">
                          <i className="ti ti-x" />
                        </span>
                      </span>
                    </label>
                  </TableCell>
                </TableRow>
              );
            })}
          </TableBody>
        </Table>
        <Button
          onClick={handleAddDay}
          className="mt-4 bg-[#f054b5] hover:bg-[#e249a9]"
        >
          + Add Day
        </Button>
      </CardContent>
    </Card>
  );
};
