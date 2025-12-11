const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const phpRow = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 179,
      hotspot_ID: 4,
      item_type: 3, // Travel
      deleted: 0,
      status: 1,
    },
    select: {
      hotspot_traveling_time: true,
      itinerary_travel_type_buffer_time: true,
      hotspot_start_time: true,
      hotspot_end_time: true,
    }
  });

  console.log('\n=== PHP Route 179 Travel to Hotspot 4 ===');
  console.log('Travel duration:', phpRow.hotspot_traveling_time);
  console.log('Buffer time:', phpRow.itinerary_travel_type_buffer_time);
  
  const travelDate = phpRow.hotspot_traveling_time;
  const bufferDate = phpRow.itinerary_travel_type_buffer_time;
  
  const travelMin = travelDate.getUTCHours() * 60 + travelDate.getUTCMinutes();
  const bufferMin = bufferDate.getUTCHours() * 60 + bufferDate.getUTCMinutes();
  const totalMin = travelMin + bufferMin;
  
  console.log(`\nTravel: ${travelMin} minutes`);
  console.log(`Buffer: ${bufferMin} minutes`);
  console.log(`Total: ${totalMin} minutes`);
  
  const start = phpRow.hotspot_start_time;
  const end = phpRow.hotspot_end_time;
  const actualMin = (end - start) / 1000 / 60;
  console.log(`\nActual (start→end): ${actualMin} minutes`);
  console.log(actualMin === totalMin ? '✅ MATCH!' : `❌ Different by ${actualMin - totalMin} min`);
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
