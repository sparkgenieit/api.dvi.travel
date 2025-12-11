const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const settings = await prisma.dvi_global_settings.findFirst({
    where: { deleted: 0, status: 1 },
    select: {
      itinerary_local_speed_limit: true,
      itinerary_outstation_speed_limit: true,
    }
  });

  console.log('\n=== Global Speed Settings ===');
  console.log(JSON.stringify(settings, null, 2));
  
  // Calculate what 8.42 km would take at different speeds
  const distance = 8.42;
  const correctedDistance = distance * 1.5;
  
  console.log(`\n=== Travel Time for ${distance} km (corrected to ${correctedDistance.toFixed(2)} km) ===`);
  
  const localSpeed = Number(settings.itinerary_local_speed_limit);
  const outstationSpeed = Number(settings.itinerary_outstation_speed_limit);
  
  const localMinutes = Math.round((correctedDistance / localSpeed) * 60);
  const outstationMinutes = Math.round((correctedDistance / outstationSpeed) * 60);
  
  console.log(`At local speed (${localSpeed} km/h): ${localMinutes} minutes`);
  console.log(`At outstation speed (${outstationSpeed} km/h): ${outstationMinutes} minutes`);
  
  console.log('\n=== What speed gives 34 minutes? ===');
  const targetMinutes = 34;
  const requiredSpeed = (correctedDistance / (targetMinutes / 60));
  console.log(`Required speed for 34 min: ${requiredSpeed.toFixed(2)} km/h`);
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
