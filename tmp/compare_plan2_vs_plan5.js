const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('\n=== COMPARING PLAN 2 (PHP) vs PLAN 5 (NestJS) - Route 2 ===\n');

  // Get 2nd route for each plan
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0 },
    orderBy: { itinerary_route_ID: 'asc' }
  });

  const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5, deleted: 0 },
    orderBy: { itinerary_route_ID: 'asc' }
  });

  const phpRoute = plan2Routes[1]; // 2nd route
  const nestjsRoute = plan5Routes[1]; // 2nd route

  console.log(`PHP Plan 2 Route 2: ID ${phpRoute.itinerary_route_ID}`);
  console.log(`  Location: ${phpRoute.location_name}`);
  console.log(`  Date: ${phpRoute.itinerary_route_date.toISOString().substr(0, 10)}`);
  console.log(`  Time: ${phpRoute.route_start_time?.toISOString().substr(11, 8)} - ${phpRoute.route_end_time?.toISOString().substr(11, 8)}`);

  console.log(`\nNestJS Plan 5 Route 2: ID ${nestjsRoute.itinerary_route_ID}`);
  console.log(`  Location: ${nestjsRoute.location_name}`);
  console.log(`  Date: ${nestjsRoute.itinerary_route_date.toISOString().substr(0, 10)}`);
  console.log(`  Time: ${nestjsRoute.route_start_time?.toISOString().substr(11, 8)} - ${nestjsRoute.route_end_time?.toISOString().substr(11, 8)}`);

  // Get hotspots for both
  const phpHotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_route_ID: phpRoute.itinerary_route_ID, deleted: 0 },
    orderBy: { hotspot_order: 'asc' }
  });

  const nestjsHotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_route_ID: nestjsRoute.itinerary_route_ID, deleted: 0 },
    orderBy: { hotspot_order: 'asc' }
  });

  const phpVisits = phpHotspots.filter(h => [1, 4].includes(h.item_type)).map(h => h.hotspot_ID);
  const nestjsVisits = nestjsHotspots.filter(h => [1, 4].includes(h.item_type)).map(h => h.hotspot_ID);

  console.log(`\n=== HOTSPOT SEQUENCES ===`);
  console.log(`PHP:    [${phpVisits.join(', ')}]`);
  console.log(`NestJS: [${nestjsVisits.join(', ')}]`);

  const match = phpVisits.length === nestjsVisits.length && 
                phpVisits.every((val, idx) => val === nestjsVisits[idx]);

  if (match) {
    console.log('\n✅ PERFECT MATCH!');
  } else {
    console.log('\n❌ MISMATCH!');
    
    // Find differences
    const phpOnly = phpVisits.filter(h => !nestjsVisits.includes(h));
    const nestjsOnly = nestjsVisits.filter(h => !phpVisits.includes(h));
    
    if (phpOnly.length) console.log(`  PHP has: [${phpOnly.join(', ')}]`);
    if (nestjsOnly.length) console.log(`  NestJS has: [${nestjsOnly.join(', ')}]`);
  }

  // Detailed comparison
  console.log('\n=== DETAILED COMPARISON ===\n');

  const maxLength = Math.max(phpHotspots.length, nestjsHotspots.length);
  
  console.log('Order | PHP Hotspot        | NestJS Hotspot     | Match');
  console.log('------|--------------------|--------------------|------');

  for (let i = 0; i < maxLength; i++) {
    const php = phpHotspots[i];
    const nestjs = nestjsHotspots[i];

    if (!php) {
      console.log(`${String(i+1).padStart(5)} | -                  | H${nestjs.hotspot_ID} ${getType(nestjs.item_type).padEnd(6)} | ❌`);
    } else if (!nestjs) {
      console.log(`${String(i+1).padStart(5)} | H${php.hotspot_ID} ${getType(php.item_type).padEnd(6)} | -                  | ❌`);
    } else {
      const phpStr = `H${php.hotspot_ID} ${getType(php.item_type)}`;
      const nestjsStr = `H${nestjs.hotspot_ID} ${getType(nestjs.item_type)}`;
      const matches = php.hotspot_ID === nestjs.hotspot_ID && php.item_type === nestjs.item_type;
      console.log(`${String(i+1).padStart(5)} | ${phpStr.padEnd(18)} | ${nestjsStr.padEnd(18)} | ${matches ? '✅' : '❌'}`);
    }
  }

  // Check H4 travel time
  const phpH4Travel = phpHotspots.find(h => h.hotspot_ID === 4 && h.item_type === 3);
  const nestjsH4Travel = nestjsHotspots.find(h => h.hotspot_ID === 4 && h.item_type === 3);

  if (phpH4Travel && nestjsH4Travel) {
    const phpMins = phpH4Travel.hotspot_traveling_time.getUTCHours() * 60 + 
                    phpH4Travel.hotspot_traveling_time.getUTCMinutes();
    const nestjsMins = nestjsH4Travel.hotspot_traveling_time.getUTCHours() * 60 + 
                       nestjsH4Travel.hotspot_traveling_time.getUTCMinutes();

    console.log('\n=== H4 TRAVEL TIME ===');
    console.log(`PHP:    ${phpH4Travel.hotspot_travelling_distance} km in ${phpMins} min`);
    console.log(`NestJS: ${nestjsH4Travel.hotspot_travelling_distance} km in ${nestjsMins} min`);
    
    if (phpMins === nestjsMins) {
      console.log('✅ Travel times match!');
    } else {
      console.log(`❌ Difference: ${Math.abs(phpMins - nestjsMins)} minutes`);
    }
  }

  console.log('\n=== SUMMARY ===');
  console.log(`Total records: PHP ${phpHotspots.length}, NestJS ${nestjsHotspots.length}`);
  console.log(`Visit sequence: ${match ? '✅ MATCH' : '❌ DIFFERENT'}`);
}

function getType(itemType) {
  const types = { 1: 'VISIT', 2: 'BREAK', 3: 'TRAVEL', 4: 'VISIT', 5: 'WAIT', 6: 'LUNCH', 7: 'DINNER' };
  return types[itemType] || `TYPE${itemType}`;
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
