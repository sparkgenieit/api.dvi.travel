const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Get global settings buffer times
  const gs = await prisma.dvi_global_settings.findFirst({
    where: { deleted: 0, status: 1 },
    select: {
      itinerary_common_buffer_time: true,
      itinerary_travel_by_flight_buffer_time: true,
      itinerary_travel_by_train_buffer_time: true,
      itinerary_travel_by_road_buffer_time: true,
    }
  });

  console.log('\n=== Global Buffer Times ===');
  console.log('Common:', gs.itinerary_common_buffer_time);
  console.log('Flight:', gs.itinerary_travel_by_flight_buffer_time);
  console.log('Train:', gs.itinerary_travel_by_train_buffer_time);
  console.log('Road:', gs.itinerary_travel_by_road_buffer_time);

  // Extract minutes from road buffer (most likely for local travel)
  if (gs.itinerary_travel_by_road_buffer_time) {
    const bufferDate = gs.itinerary_travel_by_road_buffer_time;
    const hours = bufferDate.getUTCHours();
    const minutes = bufferDate.getUTCMinutes();
    const totalMin = hours * 60 + minutes;
    
    console.log(`\nRoad buffer: ${hours}h ${minutes}m = ${totalMin} minutes`);
    console.log(`\nNestJS: 13 min travel + ${totalMin} min buffer = ${13 + totalMin} min`);
    console.log(`PHP: 34 min total`);
    console.log(13 + totalMin === 34 ? '✅ MATCH!' : `❌ Difference: ${34 - (13 + totalMin)} min`);
  }
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
