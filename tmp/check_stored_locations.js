const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeStoredLocations() {
  console.log('\n=== STORED LOCATIONS ANALYSIS ===\n');
  
  // Check key locations for Route 2
  const locations = [
    { from: 'Chennai', to: 'Pondicherry' },
    { from: 'Chennai', to: 'Pondicherry Airport' },
    { from: 'Pondicherry', to: 'Pondicherry Airport' }
  ];
  
  for (const loc of locations) {
    const stored = await prisma.dvi_stored_locations.findFirst({
      where: {
        source_location: loc.from,
        destination_location: loc.to,
        deleted: 0,
        status: 1
      }
    });
    
    if (stored) {
      console.log(`${loc.from} → ${loc.to}:`);
      console.log(`  Distance: ${stored.distance}km`);
      console.log(`  Duration: ${stored.duration}`);
      console.log(`  Source coords: (${stored.source_location_lattitude}, ${stored.source_location_longitude})`);
      console.log(`  Dest coords: (${stored.destination_location_lattitude}, ${stored.destination_location_longitude})\n`);
    } else {
      console.log(`${loc.from} → ${loc.to}: NOT FOUND\n`);
    }
  }
  
  // Check global settings for buffer times
  const settings = await prisma.dvi_global_settings.findFirst({
    where: { status: 1, deleted: 0 }
  });
  
  if (settings) {
    console.log('=== GLOBAL SETTINGS ===');
    console.log(`Common buffer time: ${settings.itinerary_common_buffer_time}`);
    console.log(`Local buffer time: ${settings.itinerary_local_buffer_time}`);
    console.log(`Outstation buffer time: ${settings.itinerary_outstation_buffer_time}\n`);
  }
  
  await prisma.$disconnect();
}

analyzeStoredLocations();
