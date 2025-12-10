const http = require('http');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJpYXQiOjE3NjQ5OTg3ODIsImV4cCI6MTc2NTYwMzU4Mn0.UFtOqNeZo9JWD2loHDI8WDTr_kEVPR1v1jJwSXY0FUY';

const requestBody = {
  "plan": {
    "itinerary_plan_id": 5,
    "agent_id": 126,
    "staff_id": 0,
    "location_id": 0,
    "arrival_point": "Chennai International Airport",
    "departure_point": "Pondicherry Airport",
    "itinerary_preference": 3,
    "itinerary_type": 2,
    "preferred_hotel_category": [2],
    "hotel_facilities": [],
    "trip_start_date": "2025-12-11T11:00:00+05:30",
    "trip_end_date": "2025-12-13T12:00:00+05:30",
    "pick_up_date_and_time": "2025-12-11T11:00:00+05:30",
    "arrival_type": 1,
    "departure_type": 1,
    "no_of_nights": 2,
    "no_of_days": 3,
    "budget": 15000,
    "entry_ticket_required": 0,
    "guide_for_itinerary": 0,
    "nationality": 101,
    "food_type": 0,
    "adult_count": 2,
    "child_count": 0,
    "infant_count": 0,
    "special_instructions": ""
  },
  "routes": [
    {
      "location_name": "Chennai International Airport",
      "next_visiting_location": "Chennai",
      "itinerary_route_date": "2025-12-11T00:00:00+05:30",
      "no_of_days": 1,
      "no_of_km": "",
      "direct_to_next_visiting_place": 0,
      "via_route": ""
    },
    {
      "location_name": "Chennai",
      "next_visiting_location": "Pondicherry",
      "itinerary_route_date": "2025-12-12T00:00:00+05:30",
      "no_of_days": 2,
      "no_of_km": "",
      "direct_to_next_visiting_place": 0,
      "via_route": ""
    },
    {
      "location_name": "Pondicherry",
      "next_visiting_location": "Pondicherry Airport",
      "itinerary_route_date": "2025-12-13T00:00:00+05:30",
      "no_of_days": 3,
      "no_of_km": "",
      "direct_to_next_visiting_place": 0,
      "via_route": ""
    }
  ],
  "vehicles": [
    {
      "vehicle_type_id": 1,
      "vehicle_count": 1
    }
  ],
  "travellers": [
    {
      "room_id": 1,
      "traveller_type": 1
    },
    {
      "room_id": 1,
      "traveller_type": 1
    }
  ]
};

async function triggerOptimization() {
  console.log('\n=== TRIGGERING PLAN 5 OPTIMIZATION ===\n');
  
  // Get Plan 2 routes and hotspots
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
    select: {
      itinerary_route_ID: true,
      location_name: true,
      next_visiting_location: true,
      route_start_time: true,
      route_end_time: true,
      direct_to_next_visiting_place: true
    }
  });
  
  const plan2Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { 
      itinerary_plan_ID: 2,
      deleted: 0,
      status: 1,
      item_type: { in: [3, 4] }  // Travel & Hotspot stay
    },
    orderBy: [
      { itinerary_route_ID: 'asc' },
      { hotspot_order: 'asc' }
    ],
    select: {
      itinerary_route_ID: true,
      hotspot_ID: true,
      item_type: true,
      hotspot_order: true,
      hotspot_start_time: true,
      hotspot_end_time: true,
      dvi_hotspot_place: {
        select: {
          hotspot_name: true,
          priority: true
        }
      }
    }
  });
  
  // Get Plan 5 routes and hotspots
  const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
    select: {
      itinerary_route_ID: true,
      location_name: true,
      next_visiting_location: true,
      route_start_time: true,
      route_end_time: true,
      direct_to_next_visiting_place: true
    }
  });
  
  const plan5Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { 
      itinerary_plan_ID: 5,
      deleted: 0,
      status: 1,
      item_type: { in: [3, 4] }  // Travel & Hotspot stay
    },
    orderBy: [
      { itinerary_route_ID: 'asc' },
      { hotspot_order: 'asc' }
    ],
    select: {
      itinerary_route_ID: true,
      hotspot_ID: true,
      item_type: true,
      hotspot_order: true,
      hotspot_start_time: true,
      hotspot_end_time: true,
      dvi_hotspot_place: {
        select: {
          hotspot_name: true,
          priority: true
        }
      }
    }
  });
  
  console.log('📍 PLAN 2 (PHP) - Routes:');
  console.table(plan2Routes.map(r => ({
    route_ID: r.itinerary_route_ID,
    from: r.location_name,
    to: r.next_visiting_location,
    start_time: r.route_start_time,
    end_time: r.route_end_time,
    direct: r.direct_to_next_visiting_place
  })));
  
  console.log('\n📍 PLAN 2 (PHP) - Hotspots:');
  plan2Routes.forEach(route => {
    const routeHotspots = plan2Hotspots.filter(h => h.itinerary_route_ID === route.itinerary_route_ID);
    if (routeHotspots.length > 0) {
      console.log(`\nRoute ${route.itinerary_route_ID}: ${route.location_name} → ${route.next_visiting_location}`);
      console.table(routeHotspots.map(h => ({
        order: h.item_order,
        type: h.item_type === 3 ? 'Travel' : 'Stay',
        hotspot_ID: h.hotspot_ID,
        name: h.dvi_hotspot_place?.hotspot_name || 'N/A',
        priority: h.dvi_hotspot_place?.priority || 'N/A',
        start: h.item_start_time,
        end: h.item_end_time
      })));
    }
  });
  
  console.log('\n\n🔧 PLAN 5 (NestJS) - Routes:');
  console.table(plan5Routes.map(r => ({
    route_ID: r.itinerary_route_ID,
    from: r.location_name,
    to: r.next_visiting_location,
    start_time: r.route_start_time,
    end_time: r.route_end_time,
    direct: r.direct_to_next_visiting_place
  })));
  
  console.log('\n🔧 PLAN 5 (NestJS) - Hotspots:');
  plan5Routes.forEach(route => {
    const routeHotspots = plan5Hotspots.filter(h => h.itinerary_route_ID === route.itinerary_route_ID);
    if (routeHotspots.length > 0) {
      console.log(`\nRoute ${route.itinerary_route_ID}: ${route.location_name} → ${route.next_visiting_location}`);
      console.table(routeHotspots.map(h => ({
        order: h.item_order,
        type: h.item_type === 3 ? 'Travel' : 'Stay',
        hotspot_ID: h.hotspot_ID,
        name: h.dvi_hotspot_place?.hotspot_name || 'N/A',
        priority: h.dvi_hotspot_place?.priority || 'N/A',
        start: h.item_start_time,
        end: h.item_end_time
      })));
    }
  });
  
  // Summary comparison
  console.log('\n\n📊 SUMMARY COMPARISON:');
  console.log(`Plan 2 (PHP):    ${plan2Hotspots.length} total hotspot items (travel + stay)`);
  console.log(`Plan 5 (NestJS): ${plan5Hotspots.length} total hotspot items (travel + stay)`);
  
  const plan2UniqueHotspots = new Set(plan2Hotspots.map(h => h.hotspot_ID)).size;
  const plan5UniqueHotspots = new Set(plan5Hotspots.map(h => h.hotspot_ID)).size;
  console.log(`\nPlan 2 unique hotspots: ${plan2UniqueHotspots}`);
  console.log(`Plan 5 unique hotspots: ${plan5UniqueHotspots}`);
  
  await prisma.$disconnect();
}
