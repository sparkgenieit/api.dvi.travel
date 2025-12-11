const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkPlan2Rooms() {
  const rooms = await prisma.dvi_itinerary_plan_hotel_room_details.findMany({
    where: { itinerary_plan_id: 2 },
  });

  console.log(`Plan 2 total rooms: ${rooms.length}\n`);
  console.log('First 10 rooms:');
  console.log(JSON.stringify(rooms.slice(0, 10), null, 2));

  // Group by hotel to see pattern
  const byHotel = {};
  rooms.forEach(r => {
    if (!byHotel[r.hotel_id]) byHotel[r.hotel_id] = [];
    byHotel[r.hotel_id].push(r);
  });

  console.log('\n=== BY HOTEL ===');
  Object.entries(byHotel).forEach(([hotelId, rows]) => {
    const rates = rows.map(r => r.room_rate);
    const nonZero = rates.filter(r => r > 0).length;
    console.log(`Hotel ${hotelId}: ${rows.length} rooms, ${nonZero} with rate >0`);
  });

  await prisma.$disconnect();
}

checkPlan2Rooms().catch(console.error);
