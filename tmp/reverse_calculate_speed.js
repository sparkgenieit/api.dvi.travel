const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Get global settings
  const settings = await prisma.dvi_global_settings.findFirst({
    where: { deleted: 0 },
    select: {
      itinerary_local_speed_limit: true,
      itinerary_outstation_speed_limit: true,
      itinerary_distance_limit: true,
      allowed_km_limit_per_day: true
    }
  });

  console.log('\n=== GLOBAL SETTINGS ===\n');
  console.log(JSON.stringify(settings, null, 2));

  // Reverse-engineer PHP's speed from the stored values
  console.log('\n=== REVERSE CALCULATION ===\n');
  
  const phpDistance = 8.42; // km
  const phpTravelMinutes = 34; // minutes
  const phpSpeed = (phpDistance / (phpTravelMinutes / 60)).toFixed(2);
  
  console.log(`PHP stored: ${phpDistance} km in ${phpTravelMinutes} minutes`);
  console.log(`Implied speed: ${phpSpeed} km/h`);
  console.log(`\nSettings say: ${settings.itinerary_local_speed_limit} km/h (local)`);
  console.log(`\nDifference: ${(settings.itinerary_local_speed_limit - phpSpeed).toFixed(2)} km/h`);
  
  // What if PHP was using outstation speed?
  const outstationMinutes = (phpDistance / settings.itinerary_outstation_speed_limit * 60).toFixed(2);
  console.log(`\nIf using outstation speed (${settings.itinerary_outstation_speed_limit} km/h): ${outstationMinutes} minutes`);
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
