const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

// Helper to get day column
function dayCol(date) {
  const day = date.getDate();
  return `day_${day}`;
}

// Helper to get month name
function monthName(date) {
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  return months[date.getMonth()];
}

async function testHotel168() {
  // Plan 5 route 650 is on what date?
  const route = await prisma.dvi_itinerary_routes.findFirst({
    where: { itinerary_route_ID: 650 },
    select: {
      itinerary_route_date: true,
      location_name: true,
    },
  });

  console.log('=== ROUTE 650 ===');
  console.log(JSON.stringify(route, null, 2));

  const onDate = new Date(route.itinerary_route_date);
  const dc = dayCol(onDate);
  const y = String(onDate.getFullYear());
  const m = monthName(onDate);

  console.log(`\nLooking for hotel 168 rates: year=${y}, month=${m}, dayCol=${dc}\n`);

  // Check hotel 168 price book
  const rows = await prisma.dvi_hotel_room_price_book.findMany({
    where: { 
      hotel_id: 168, 
      year: y, 
      month: m 
    },
    select: { 
      room_id: true, 
      [dc]: true 
    },
    take: 10,
  });

  console.log('=== HOTEL 168 ROOM RATES ===');
  rows.forEach(r => {
    console.log(`Room ${r.room_id}: rate = ${r[dc] || 0}`);
  });

  // Check hotel 677 (which DOES work)
  console.log('\n=== HOTEL 677 ROOM RATES (for comparison) ===');
  const rows677 = await prisma.dvi_hotel_room_price_book.findMany({
    where: { 
      hotel_id: 677, 
      year: y, 
      month: m 
    },
    select: { 
      room_id: true, 
      [dc]: true 
    },
    take: 10,
  });

  rows677.forEach(r => {
    console.log(`Room ${r.room_id}: rate = ${r[dc] || 0}`);
  });

  await prisma.$disconnect();
}

testHotel168().catch(console.error);
