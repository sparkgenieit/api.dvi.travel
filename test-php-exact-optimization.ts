/**
 * TEST SCRIPT: PHP-Exact Route Optimization
 * 
 * Sample Data:
 * Madurai Airport → Kanyakumari → Rameswaram → Kanyakumari → Trivandrum → Trivandrum Airport
 * 
 * Expected routes with 6 locations = 5 route segments
 * Routes to optimize:
 *   Route[0]: Madurai Airport → Kanyakumari
 *   Route[1]: Kanyakumari → Rameswaram        (optimize)
 *   Route[2]: Rameswaram → Kanyakumari         (optimize) ← duplicate location!
 *   Route[3]: Kanyakumari → Trivandrum         (optimize) ← duplicate start!
 *   Route[4]: Trivandrum → Trivandrum Airport
 * 
 * middleLocations = [Kanyakumari, Rameswaram, Kanyakumari, Trivandrum] (4 locations, with duplicates)
 * start = Kanyakumari
 * end = Trivandrum Airport
 */

const testData = {
  plan: {
    itinerary_plan_ID: 999,
    plan_name: "Test Route Optimization",
    itinerary_preference: 1,
    agent_id: 1,
    staff_id: 1,
    arrival_location_ID: 1,
    departure_location_ID: 2,
    itinerary_type: 1,
    entry_ticket_required: 0,
  },
  routes: [
    {
      location_name: "Madurai Airport",
      next_visiting_location: "Kanyakumari",
      itinerary_route_date: "2025-02-01",
      no_of_days: 1,
      via_routes: [],
      hotspots: [],
      activities: [],
      guides: [],
      hotels: [],
      vehicles: [],
    },
    {
      location_name: "Kanyakumari",
      next_visiting_location: "Rameswaram",
      itinerary_route_date: "2025-02-02",
      no_of_days: 2,
      via_routes: [],
      hotspots: [],
      activities: [],
      guides: [],
      hotels: [],
      vehicles: [],
    },
    {
      location_name: "Rameswaram",
      next_visiting_location: "Kanyakumari",
      itinerary_route_date: "2025-02-03",
      no_of_days: 3,
      via_routes: [],
      hotspots: [],
      activities: [],
      guides: [],
      hotels: [],
      vehicles: [],
    },
    {
      location_name: "Kanyakumari",
      next_visiting_location: "Trivandrum",
      itinerary_route_date: "2025-02-04",
      no_of_days: 4,
      via_routes: [],
      hotspots: [],
      activities: [],
      guides: [],
      hotels: [],
      vehicles: [],
    },
    {
      location_name: "Trivandrum",
      next_visiting_location: "Trivandrum Airport",
      itinerary_route_date: "2025-02-05",
      no_of_days: 5,
      via_routes: [],
      hotspots: [],
      activities: [],
      guides: [],
      hotels: [],
      vehicles: [],
    },
  ],
};

export default testData;

/**
 * RUNNING THE TEST:
 * 
 * curl -X POST http://localhost:3000/api/v1/itineraries?type=itineary_basic_info_with_optimized_route \
 *   -H "Content-Type: application/json" \
 *   -d @test-php-exact-optimization.json
 * 
 * EXPECTED RESULTS:
 * ✅ Routes ≤10: Uses exhaustive permutation
 * ✅ All 4! = 24 permutations tested
 * ✅ Best permutation selected based on minimum total distance
 * ✅ Route locations rebuilt from [start, ...bestPerm, end]
 * ✅ All other route fields preserved
 * ✅ Dates shifted sequentially from first route date
 * ✅ Log file written to: logs/route-optimization.log
 */
