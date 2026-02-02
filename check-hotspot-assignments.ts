import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function check() {
  try {
    console.log('\n🔍 HOTSPOT ASSIGNMENTS FOR PLAN 17\n');

    const hotspots = await (prisma as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 17,
        deleted: 0,
        status: 1,
      },
      select: {
        itinerary_route_ID: true,
        hotspot_ID: true,
      },
      orderBy: { itinerary_route_ID: 'asc' },
    });

    console.log('All hotspots in Plan 17:');
    const byRoute: any = {};
    for (const h of hotspots) {
      if (!byRoute[h.itinerary_route_ID]) {
        byRoute[h.itinerary_route_ID] = [];
      }
      byRoute[h.itinerary_route_ID].push(h.hotspot_ID);
    }

    for (const [routeId, ids] of Object.entries(byRoute)) {
      console.log(`  Route ${routeId}: [${(ids as number[]).join(', ')}]`);
    }

    console.log('\n📍 Key finding:');
    const route347Hotspots = byRoute[347] || [];
    const route348Hotspots = byRoute[348] || [];

    console.log(`  Route 347 has ${route347Hotspots.length} hotspots:`, route347Hotspots.includes(41) ? '(includes 41)' : '(NO 41)');
    console.log(`  Route 348 has ${route348Hotspots.length} hotspots:`, route348Hotspots.includes(41) ? '(includes 41)' : '(NO 41)');

    console.log('\n💡 ISSUE:');
    if (route347Hotspots.includes(41) && !route348Hotspots.includes(41)) {
      console.log('  Hotspot 41 is ONLY in Route 347 (wrong route!)');
      console.log('  It was inserted there by the preview-add but should be in Route 348');
      console.log('\n  ACTION: Delete hotspot 41 from Route 347, add it to Route 348 instead');
    }

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

check();
