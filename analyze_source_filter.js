const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzePHPSourceFiltering() {
  console.log('\n=== ANALYZING PHP SOURCE HOTSPOT FILTERING ===\n');
  
  // Get Route 2 from Plan 2 (PHP)
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
  });
  
  const route2 = plan2Routes[1];
  console.log('Route 2 (PHP Plan 2):');
  console.log(`  Route ID: ${route2.itinerary_route_ID}`);
  console.log(`  ${route2.target_location} → ${route2.next_location}`);
  console.log(`  direct_to_next_visiting_place: ${route2.direct_to_next_visiting_place}`);
  
  // Get ALL active hotspots
  const allHotspots = await prisma.dvi_hotspot_place.findMany({
    where: { deleted: 0, status: 1 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true,
      hotspot_location: true,
    }
  });
  
  // Filter for Chennai hotspots (hotspot_location contains "Chennai")
  const chennaiHotspots = allHotspots.filter(h => {
    const loc = (h.hotspot_location || '').toLowerCase();
    return loc.split('|').map(p => p.trim()).includes('chennai');
  });
  
  // Filter for Pondicherry hotspots
  const pondyHotspots = allHotspots.filter(h => {
    const loc = (h.hotspot_location || '').toLowerCase();
    return loc.split('|').map(p => p.trim()).includes('pondicherry');
  });
  
  console.log(`Found ${chennaiHotspots.length} Chennai hotspots`);
  console.log(`Found ${pondyHotspots.length} Pondicherry hotspots`);
  
  // Get actually selected hotspots from timeline
  const timeline = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { 
      itinerary_plan_ID: 2,
      itinerary_route_ID: route2.itinerary_route_ID 
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const selectedHotspotIDs = timeline
    .filter(t => t.hotspot_ID)
    .map(t => Number(t.hotspot_ID));
  
  console.log('\n=== ALL CHENNAI HOTSPOTS (Source Location) ===');
  chennaiHotspots.forEach(h => {
    const isSelected = selectedHotspotIDs.includes(Number(h.hotspot_ID));
    const priority = Number(h.hotspot_priority) || 0;
    console.log(`  ID=${String(h.hotspot_ID).padStart(3)} Priority=${String(priority).padStart(2)} "${h.hotspot_name}" ${isSelected ? '✅ SELECTED' : '❌ NOT SELECTED'}`);
  });
  
  console.log('\n=== ALL PONDICHERRY HOTSPOTS (Destination Location) ===');
  pondyHotspots.forEach(h => {
    const isSelected = selectedHotspotIDs.includes(Number(h.hotspot_ID));
    const priority = Number(h.hotspot_priority) || 0;
    console.log(`  ID=${String(h.hotspot_ID).padStart(3)} Priority=${String(priority).padStart(2)} "${h.hotspot_name}" ${isSelected ? '✅ SELECTED' : '❌ NOT SELECTED'}`);
  });
  
  // Analysis: Count SOURCE vs DEST hotspots
  const sourceSelected = selectedHotspotIDs
    .map(id => chennaiHotspots.find(h => Number(h.hotspot_ID) === id))
    .filter(h => h);
  
  const destSelected = selectedHotspotIDs
    .map(id => pondyHotspots.find(h => Number(h.hotspot_ID) === id))
    .filter(h => h);
  
  console.log('\n=== PHP SOURCE/DEST SELECTION ANALYSIS ===');
  console.log(`SOURCE (Chennai) hotspots available: ${chennaiHotspots.length}`);
  console.log(`SOURCE (Chennai) hotspots selected: ${sourceSelected.length}`);
  sourceSelected.forEach(h => {
    const priority = Number(h.hotspot_priority) || 0;
    console.log(`  - ID=${h.hotspot_ID} Priority=${priority} "${h.hotspot_name}"`);
  });
  
  console.log(`\nDESTINATION (Pondicherry) hotspots available: ${pondyHotspots.length}`);
  console.log(`DESTINATION (Pondicherry) hotspots selected: ${destSelected.length}`);
  destSelected.forEach(h => {
    const priority = Number(h.hotspot_priority) || 0;
    console.log(`  - ID=${h.hotspot_ID} Priority=${priority} "${h.hotspot_name}"`);
  });
  
  // Check priority 0 filtering
  const chennaiPriority0 = chennaiHotspots.filter(h => (Number(h.hotspot_priority) || 0) === 0);
  const sourcePriority0Selected = sourceSelected.filter(h => (Number(h.hotspot_priority) || 0) === 0);
  
  console.log('\n=== PRIORITY 0 FILTERING ANALYSIS ===');
  console.log(`Chennai has ${chennaiPriority0.length} priority-0 hotspots:`);
  chennaiPriority0.forEach(h => {
    const isSelected = selectedHotspotIDs.includes(Number(h.hotspot_ID));
    console.log(`  - ID=${h.hotspot_ID} "${h.hotspot_name}" ${isSelected ? '✅ SELECTED' : '❌ FILTERED OUT'}`);
  });
  
  console.log(`\nPHP selected ${sourcePriority0Selected.length} priority-0 SOURCE hotspots`);
  
  if (chennaiPriority0.length > 0 && sourcePriority0Selected.length === 0) {
    console.log('\n✅ CONCLUSION: PHP FILTERS OUT ALL priority-0 SOURCE hotspots for direct=0 routes!');
    console.log('   NestJS should apply: sourceLocationHotspots.filter(h => (h.hotspot_priority || 0) > 0)');
  } else if (sourceSelected.length < chennaiHotspots.filter(h => (Number(h.hotspot_priority) || 0) > 0).length) {
    console.log('\n⚠️  CONCLUSION: PHP selects SOME but not all priority>0 SOURCE hotspots');
    console.log(`   Available priority>0: ${chennaiHotspots.filter(h => (Number(h.hotspot_priority) || 0) > 0).length}`);
    console.log(`   Selected: ${sourceSelected.length}`);
  } else {
    console.log('\n❌ Priority-0 filtering alone is NOT the issue');
  }
  
  await prisma.$disconnect();
}

analyzePHPSourceFiltering().catch(console.error);
