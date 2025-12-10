const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function comparePHPvsNestJSRoute2() {
  // PHP Plan 2 Route 2
  const php = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_plan_ID: 2, itinerary_route_ID: 179 },
    orderBy: { hotspot_order: 'asc' }
  });

  // NestJS Plan 5 Route 2
  const nestjs = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_plan_ID: 5, itinerary_route_ID: 404 },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('\n=== PHP PLAN 2 ROUTE 179 ===');
  php.filter(r => r.hotspot_ID > 0 && r.item_type === 4).forEach((r, i) => {
    const start = `${String(r.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(r.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}`;
    const end = `${String(r.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(r.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}`;
    console.log(`${i+1}. Hotspot ${r.hotspot_ID}: ${start}-${end}`);
  });

  console.log('\n=== NESTJS PLAN 5 ROUTE 404 ===');
  nestjs.filter(r => r.hotspot_ID > 0 && r.item_type === 4).forEach((r, i) => {
    const start = `${String(r.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(r.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}`;
    const end = `${String(r.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(r.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}`;
    console.log(`${i+1}. Hotspot ${r.hotspot_ID}: ${start}-${end}`);
  });

  const phpHotspots = php.filter(r => r.hotspot_ID > 0 && r.item_type === 4).map(r => r.hotspot_ID);
  const nestjsHotspots = nestjs.filter(r => r.hotspot_ID > 0 && r.item_type === 4).map(r => r.hotspot_ID);

  console.log('\n=== COMPARISON ===');
  console.log('PHP:    ', phpHotspots);
  console.log('NestJS: ', nestjsHotspots);
  console.log('\n❌ Missing in NestJS: 18 (after hotspot 4)');
  console.log('❌ Extra in NestJS: 677, 679');
  console.log('❌ Missing in NestJS: 678');

  await prisma.$disconnect();
}

comparePHPvsNestJSRoute2().catch(console.error);
