const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkAllPriorities() {
  const hotspotIds = [16, 18, 20, 23, 24, 25, 676, 669];
  
  const hotspots = await prisma.dvi_hotspot_place.findMany({
    where: { hotspot_ID: { in: hotspotIds } },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true,
      hotspot_rating: true,
      hotspot_location: true
    },
    orderBy: { hotspot_priority: 'asc' }  // Sort by priority
  });

  console.log('\n=== ALL ROUTE 3 HOTSPOTS (SORTED BY PRIORITY) ===\n');
  for (const h of hotspots) {
    const inPlan2 = [18, 25, 16, 23, 20, 676, 669].includes(h.hotspot_ID);
    const inPlan5 = [18, 25, 20, 16, 23, 24, 676, 669].includes(h.hotspot_ID);
    const plan2Pos = [18, 25, 16, 23, 20, 676, 669].indexOf(h.hotspot_ID);
    const plan5Pos = [18, 25, 20, 16, 23, 24, 676, 669].indexOf(h.hotspot_ID);
    
    console.log(`${inPlan2 ? '✅' : '❌'} | ${inPlan5 ? '✅' : '❌'} | Priority ${h.hotspot_priority} | ID ${h.hotspot_ID}: ${h.hotspot_name}`);
    console.log(`     Location: ${h.hotspot_location}`);
    if (inPlan2) console.log(`     Plan 2 order: ${plan2Pos + 1}/7`);
    if (inPlan5) console.log(`     Plan 5 order: ${plan5Pos + 1}/8`);
    console.log();
  }

  await prisma.$disconnect();
}

checkAllPriorities();
