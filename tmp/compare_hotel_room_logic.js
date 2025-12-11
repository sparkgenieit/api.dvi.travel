const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function compareHotelRoomLogic() {
  console.log('\n=== PLAN 2 (PHP) HOTEL ROOM DETAILS ===\n');
  
  const plan2Rooms = await prisma.dvi_itinerary_plan_hotel_room_details.findMany({
    where: { itinerary_plan_id: 2 },
    orderBy: [
      { itinerary_route_id: 'asc' },
      { group_type: 'asc' }
    ],
    select: {
      itinerary_route_id: true,
      itinerary_route_date: true,
      group_type: true,
      hotel_id: true,
      room_id: true,
      room_rate: true,
      room_qty: true,
      total_room_cost: true,
      breakfast_cost_per_person: true,
      total_breafast_cost: true
    }
  });
  
  console.log('Total rows:', plan2Rooms.length);
  console.log('\nFirst 10 rows:');
  console.log(JSON.stringify(plan2Rooms.slice(0, 10), null, 2));
  
  // Group by route
  const byRoute = {};
  plan2Rooms.forEach(r => {
    if (!byRoute[r.itinerary_route_id]) {
      byRoute[r.itinerary_route_id] = [];
    }
    byRoute[r.itinerary_route_id].push(r);
  });
  
  console.log('\n=== PATTERN ANALYSIS ===\n');
  Object.keys(byRoute).forEach(routeId => {
    const rooms = byRoute[routeId];
    const uniqueHotels = [...new Set(rooms.map(r => r.hotel_id))];
    const uniqueGroupTypes = [...new Set(rooms.map(r => r.group_type))];
    
    console.log(`Route ${routeId}:`);
    console.log(`  - ${rooms.length} room records`);
    console.log(`  - Group types: ${uniqueGroupTypes.join(', ')}`);
    console.log(`  - Hotels: ${uniqueHotels.join(', ')}`);
    console.log(`  - Date: ${rooms[0].itinerary_route_date}`);
  });
  
  console.log('\n=== PLAN 5 (NestJS) HOTEL ROOM DETAILS ===\n');
  
  const plan5Rooms = await prisma.dvi_itinerary_plan_hotel_room_details.findMany({
    where: { itinerary_plan_id: 5 },
    orderBy: [
      { itinerary_route_id: 'asc' },
      { group_type: 'asc' }
    ],
    select: {
      itinerary_route_id: true,
      itinerary_route_date: true,
      group_type: true,
      hotel_id: true,
      room_id: true,
      room_rate: true,
      room_qty: true,
      total_room_cost: true,
      breakfast_cost_per_person: true,
      total_breafast_cost: true
    }
  });
  
  console.log('Total rows:', plan5Rooms.length);
  console.log('\nFirst 10 rows:');
  console.log(JSON.stringify(plan5Rooms.slice(0, 10), null, 2));
  
  await prisma.$disconnect();
}

compareHotelRoomLogic().catch(console.error);
