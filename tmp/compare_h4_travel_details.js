const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const h4Travel = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 425,
      hotspot_ID: 4,
      item_type: 3, // Travel
      deleted: 0,
      status: 1,
    },
    select: {
      hotspot_traveling_time: true,
      hotspot_travelling_distance: true,
      hotspot_start_time: true,
      hotspot_end_time: true,
    }
  });

  console.log('\n=== NestJS Route 425 Travel to Hotspot 4 ===');
  console.log('Stored duration:', h4Travel.hotspot_traveling_time);
  console.log('Distance:', h4Travel.hotspot_travelling_distance, 'km');
  console.log('Start:', h4Travel.hotspot_start_time);
  console.log('End:', h4Travel.hotspot_end_time);

  // Calculate actual time difference
  const start = new Date(h4Travel.hotspot_start_time);
  const end = new Date(h4Travel.hotspot_end_time);
  const diffMs = end - start;
  const diffMin = diffMs / 1000 / 60;
  console.log('Actual difference:', diffMin, 'minutes');

  // Compare with PHP
  const phpH4Travel = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 179,
      hotspot_ID: 4,
      item_type: 3,
      deleted: 0,
      status: 1,
    },
    select: {
      hotspot_traveling_time: true,
      hotspot_travelling_distance: true,
      hotspot_start_time: true,
      hotspot_end_time: true,
    }
  });

  console.log('\n=== PHP Route 179 Travel to Hotspot 4 ===');
  console.log('Stored duration:', phpH4Travel.hotspot_traveling_time);
  console.log('Distance:', phpH4Travel.hotspot_travelling_distance, 'km');
  console.log('Start:', phpH4Travel.hotspot_start_time);
  console.log('End:', phpH4Travel.hotspot_end_time);

  const phpStart = new Date(phpH4Travel.hotspot_start_time);
  const phpEnd = new Date(phpH4Travel.hotspot_end_time);
  const phpDiffMs = phpEnd - phpStart;
  const phpDiffMin = phpDiffMs / 1000 / 60;
  console.log('Actual difference:', phpDiffMin, 'minutes');

  console.log('\n=== Speed Comparison ===');
  const nestSpeed = h4Travel.hotspot_travelling_distance / (diffMin / 60);
  const phpSpeed = phpH4Travel.hotspot_travelling_distance / (phpDiffMin / 60);
  console.log(`NestJS: ${h4Travel.hotspot_travelling_distance}km in ${diffMin}min = ${nestSpeed.toFixed(2)} km/h`);
  console.log(`PHP: ${phpH4Travel.hotspot_travelling_distance}km in ${phpDiffMin}min = ${phpSpeed.toFixed(2)} km/h`);
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
