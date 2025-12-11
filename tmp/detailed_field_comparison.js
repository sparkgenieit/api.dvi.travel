const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

function formatTime(date) {
  if (!date) return 'NULL';
  if (typeof date === 'string') return date;
  const h = String(date.getUTCHours()).padStart(2, '0');
  const m = String(date.getUTCMinutes()).padStart(2, '0');
  const s = String(date.getUTCSeconds()).padStart(2, '0');
  return `${h}:${m}:${s}`;
}

async function compareFieldValues() {
  console.log('\n=== DETAILED FIELD VALUE COMPARISON ===\n');
  
  // Get Route 2 details (Chennai → Pondicherry) - the one with differences
  const plan2Route2 = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_plan_ID: 2, location_name: 'Chennai' }
  });
  
  const plan5Route2 = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_plan_ID: 5, location_name: 'Chennai' }
  });
  
  console.log('=== ROUTE 2: Chennai → Pondicherry ===');
  console.log(`Plan 2 Route ID: ${plan2Route2.itinerary_route_ID}`);
  console.log(`Plan 5 Route ID: ${plan5Route2.itinerary_route_ID}\n`);
  
  const plan2Rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: plan2Route2.itinerary_route_ID,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const plan5Rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: plan5Route2.itinerary_route_ID,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const typeNames = {1: 'Refresh', 3: 'Travel', 4: 'Visit', 5: 'ToHotel', 6: 'AtHotel', 7: 'Return'};
  
  console.log(`Plan 2: ${plan2Rows.length} rows`);
  console.log(`Plan 5: ${plan5Rows.length} rows\n`);
  
  // Compare matching rows by order and type
  for (let i = 0; i < Math.max(plan2Rows.length, plan5Rows.length); i++) {
    const p2 = plan2Rows[i];
    const p5 = plan5Rows[i];
    
    if (!p2 && p5) {
      console.log(`\n❌ EXTRA ROW in Plan 5:`);
      console.log(`   Order ${p5.hotspot_order}: Type ${p5.item_type} (${typeNames[p5.item_type]}), Hotspot ${p5.hotspot_ID}`);
      continue;
    }
    
    if (p2 && !p5) {
      console.log(`\n❌ MISSING ROW in Plan 5:`);
      console.log(`   Order ${p2.hotspot_order}: Type ${p2.item_type} (${typeNames[p2.item_type]}), Hotspot ${p2.hotspot_ID}`);
      continue;
    }
    
    const match = p2.hotspot_order === p5.hotspot_order && 
                  p2.item_type === p5.item_type && 
                  p2.hotspot_ID === p5.hotspot_ID;
    
    if (!match) {
      console.log(`\n⚠️  ROW MISMATCH at index ${i}:`);
      console.log(`   Plan 2: Order ${p2.hotspot_order}, Type ${p2.item_type} (${typeNames[p2.item_type]}), Hotspot ${p2.hotspot_ID}`);
      console.log(`   Plan 5: Order ${p5.hotspot_order}, Type ${p5.item_type} (${typeNames[p5.item_type]}), Hotspot ${p5.hotspot_ID}`);
      continue;
    }
    
    // Compare field values for matching rows
    const diffs = [];
    
    const p2_travel_time = formatTime(p2.hotspot_traveling_time);
    const p5_travel_time = formatTime(p5.hotspot_traveling_time);
    if (p2_travel_time !== p5_travel_time) {
      diffs.push(`travel_time: ${p2_travel_time} vs ${p5_travel_time}`);
    }
    
    const p2_buffer = formatTime(p2.itinerary_travel_type_buffer_time);
    const p5_buffer = formatTime(p5.itinerary_travel_type_buffer_time);
    if (p2_buffer !== p5_buffer) {
      diffs.push(`buffer_time: ${p2_buffer} vs ${p5_buffer}`);
    }
    
    const p2_dist = p2.hotspot_travelling_distance;
    const p5_dist = p5.hotspot_travelling_distance;
    if (p2_dist !== p5_dist) {
      diffs.push(`distance: ${p2_dist}km vs ${p5_dist}km`);
    }
    
    const p2_start = formatTime(p2.hotspot_start_time);
    const p5_start = formatTime(p5.hotspot_start_time);
    if (p2_start !== p5_start) {
      diffs.push(`start_time: ${p2_start} vs ${p5_start}`);
    }
    
    const p2_end = formatTime(p2.hotspot_end_time);
    const p5_end = formatTime(p5.hotspot_end_time);
    if (p2_end !== p5_end) {
      diffs.push(`end_time: ${p2_end} vs ${p5_end}`);
    }
    
    if (diffs.length > 0) {
      console.log(`\n⚠️  Order ${p2.hotspot_order}, Type ${typeNames[p2.item_type]}, Hotspot ${p2.hotspot_ID}:`);
      diffs.forEach(d => console.log(`   ${d}`));
    } else {
      console.log(`✅ Order ${p2.hotspot_order}, Type ${typeNames[p2.item_type]}, Hotspot ${p2.hotspot_ID}: Perfect match`);
    }
  }
  
  // Also check first few rows in detail
  console.log('\n\n=== DETAILED ROW ANALYSIS (First 4 rows) ===\n');
  
  for (let i = 0; i < Math.min(4, plan2Rows.length); i++) {
    const p2 = plan2Rows[i];
    const p5 = plan5Rows[i];
    
    if (!p2 || !p5) continue;
    
    console.log(`\n--- Row ${i + 1}: Order ${p2.hotspot_order}, Type ${typeNames[p2.item_type]} ---`);
    console.log('Plan 2:');
    console.log(`  Hotspot ID: ${p2.hotspot_ID}`);
    console.log(`  Travel Time: ${formatTime(p2.hotspot_traveling_time)}`);
    console.log(`  Buffer Time: ${formatTime(p2.itinerary_travel_type_buffer_time)}`);
    console.log(`  Distance: ${p2.hotspot_travelling_distance}km`);
    console.log(`  Start: ${formatTime(p2.hotspot_start_time)}`);
    console.log(`  End: ${formatTime(p2.hotspot_end_time)}`);
    
    console.log('Plan 5:');
    console.log(`  Hotspot ID: ${p5.hotspot_ID}`);
    console.log(`  Travel Time: ${formatTime(p5.hotspot_traveling_time)}`);
    console.log(`  Buffer Time: ${formatTime(p5.itinerary_travel_type_buffer_time)}`);
    console.log(`  Distance: ${p5.hotspot_travelling_distance}km`);
    console.log(`  Start: ${formatTime(p5.hotspot_start_time)}`);
    console.log(`  End: ${formatTime(p5.hotspot_end_time)}`);
  }
  
  await prisma.$disconnect();
}

compareFieldValues();
