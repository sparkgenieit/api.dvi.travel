const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeHotspotDetails() {
  console.log('\n=== DETAILED HOTSPOT ANALYSIS ===\n');
  
  // Analyze Route 2 - why hotspot 24 is selected in NestJS but not PHP
  console.log('=== ROUTE 2 ANALYSIS: Chennai → Pondicherry ===');
  
  const route2 = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_plan_ID: 2, location_name: 'Chennai' }
  });
  
  console.log(`\nRoute 2 ID: ${route2.itinerary_route_ID}`);
  console.log(`Location: ${route2.location_name} → ${route2.next_visiting_location}`);
  
  // Get all hotspots for Chennai and Pondicherry
  const chennaiHotspots = await prisma.dvi_hotspot_place.findMany({
    where: {
      hotspot_location: 'Chennai',
      deleted: 0,
      status: 1
    },
    orderBy: { hotspot_priority: 'asc' }
  });
  
  const pondicherryHotspots = await prisma.dvi_hotspot_place.findMany({
    where: {
      hotspot_location: 'Pondicherry',
      deleted: 0,
      status: 1
    },
    orderBy: { hotspot_priority: 'asc' }
  });
  
  console.log(`\nChennai hotspots (${chennaiHotspots.length}):`);
  chennaiHotspots.forEach(h => {
    console.log(`  ID ${h.hotspot_ID}: ${h.hotspot_name} - Priority: ${h.hotspot_priority || 0}`);
  });
  
  console.log(`\nPondicherry hotspots (${pondicherryHotspots.length}):`);
  pondicherryHotspots.forEach(h => {
    console.log(`  ID ${h.hotspot_ID}: ${h.hotspot_name} - Priority: ${h.hotspot_priority || 0}`);
  });
  
  // Check which hotspots were selected by PHP (plan 2) and NestJS (plan 5)
  const plan2Route2Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: route2.itinerary_route_ID,
      item_type: 4,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const plan5Route2 = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_plan_ID: 5, location_name: 'Chennai' }
  });
  
  const plan5Route2Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: plan5Route2.itinerary_route_ID,
      item_type: 4,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  console.log(`\nPHP (Plan 2) selected:`);
  for (const h of plan2Route2Hotspots) {
    const hotspot = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: h.hotspot_ID }
    });
    console.log(`  Order ${h.hotspot_order}: ID ${h.hotspot_ID} - ${hotspot?.hotspot_name} (${hotspot?.hotspot_location}) - Priority: ${hotspot?.hotspot_priority || 0}`);
  }
  
  console.log(`\nNestJS (Plan 5) selected:`);
  for (const h of plan5Route2Hotspots) {
    const hotspot = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: h.hotspot_ID }
    });
    console.log(`  Order ${h.hotspot_order}: ID ${h.hotspot_ID} - ${hotspot?.hotspot_name} (${hotspot?.hotspot_location}) - Priority: ${hotspot?.hotspot_priority || 0}`);
  }
  
  // Analyze Route 3
  console.log('\n\n=== ROUTE 3 ANALYSIS: Pondicherry → Pondicherry Airport ===');
  
  const route3 = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_plan_ID: 2, location_name: 'Pondicherry' }
  });
  
  console.log(`\nRoute 3 ID: ${route3.itinerary_route_ID}`);
  console.log(`Location: ${route3.location_name} → ${route3.next_visiting_location}`);
  
  const plan2Route3Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: route3.itinerary_route_ID,
      item_type: 4,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const plan5Route3 = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_plan_ID: 5, location_name: 'Pondicherry' }
  });
  
  const plan5Route3Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: plan5Route3.itinerary_route_ID,
      item_type: 4,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  console.log(`\nPHP (Plan 2) selected:`);
  for (const h of plan2Route3Hotspots) {
    const hotspot = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: h.hotspot_ID }
    });
    console.log(`  Order ${h.hotspot_order}: ID ${h.hotspot_ID} - ${hotspot?.hotspot_name} (${hotspot?.hotspot_location}) - Priority: ${hotspot?.hotspot_priority || 0}`);
  }
  
  console.log(`\nNestJS (Plan 5) selected:`);
  for (const h of plan5Route3Hotspots) {
    const hotspot = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: h.hotspot_ID }
    });
    console.log(`  Order ${h.hotspot_order}: ID ${h.hotspot_ID} - ${hotspot?.hotspot_name} (${hotspot?.hotspot_location}) - Priority: ${hotspot?.hotspot_priority || 0}`);
  }
  
  // Check all row types for Route 3
  console.log(`\nRoute 3 All Rows (Plan 2 vs Plan 5):`);
  
  const plan2Route3All = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: route3.itinerary_route_ID,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const plan5Route3All = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: plan5Route3.itinerary_route_ID,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const typeNames = {1: 'Refresh', 3: 'Travel', 4: 'Visit', 5: 'ToHotel', 6: 'AtHotel', 7: 'Return'};
  
  console.log(`\nPlan 2 (PHP) - ${plan2Route3All.length} rows:`);
  plan2Route3All.forEach(r => {
    console.log(`  Order ${r.hotspot_order}: Type ${r.item_type} (${typeNames[r.item_type]})`);
  });
  
  console.log(`\nPlan 5 (NestJS) - ${plan5Route3All.length} rows:`);
  plan5Route3All.forEach(r => {
    console.log(`  Order ${r.hotspot_order}: Type ${r.item_type} (${typeNames[r.item_type]})`);
  });
  
  await prisma.$disconnect();
}

analyzeHotspotDetails();
