const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkPricebookRates() {
  // Check what dates Plan 5 routes have
  console.log('=== PLAN 5 ROUTE DATES ===\n');
  const routes = await prisma.dvi_itinerary_route.findMany({
    where: { itinerary_plan_id: 5 },
    select: {
      itinerary_route_ID: true,
      itinerary_route_date: true,
      location_name: true,
    },
  });
  console.log(JSON.stringify(routes, null, 2));

  // Check hotel 168 pricebook for January 2025
  console.log('\n=== HOTEL 168 ROOM PRICEBOOK (Jan 2025) ===\n');
  const pricebook = await prisma.dvi_hotel_room_price_book.findMany({
    where: {
      hotel_id: 168,
      year: '2025',
      month: 'Jan',
    },
    select: {
      room_id: true,
      day_1: true,
      day_2: true,
      day_3: true,
      day_15: true,
      day_20: true,
    },
    take: 10,
  });
  console.log(JSON.stringify(pricebook, null, 2));

  // Check hotel 677 which DOES have rates
  console.log('\n=== HOTEL 677 ROOM PRICEBOOK (Jan 2025) ===\n');
  const pricebook677 = await prisma.dvi_hotel_room_price_book.findMany({
    where: {
      hotel_id: 677,
      year: '2025',
      month: 'Jan',
    },
    select: {
      room_id: true,
      day_1: true,
      day_2: true,
      day_3: true,
      day_15: true,
      day_20: true,
    },
    take: 10,
  });
  console.log(JSON.stringify(pricebook677, null, 2));

  await prisma.$disconnect();
}

checkPricebookRates().catch(console.error);
