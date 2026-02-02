import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function check() {
  try {
    console.log('\n🔍 HOTSPOT 41 DETAILS IN ROUTES\n');

    const route347 = await (prisma as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_route_ID: 347,
        hotspot_ID: 41,
        deleted: 0,
        status: 1,
      },
    });

    const route348 = await (prisma as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_route_ID: 348,
        hotspot_ID: 41,
        deleted: 0,
        status: 1,
      },
    });

    console.log('📍 Route 347:');
    if (route347.length > 0) {
      for (const r of route347) {
        console.log(`  - itinerary_route_hotspot_ID: ${r.itinerary_route_hotspot_ID}`);
        console.log(`    item_type: ${r.item_type}`);
        console.log(`    hotspot_plan_own_way: ${r.hotspot_plan_own_way}`);
        console.log(`    status: ${r.status}, deleted: ${r.deleted}`);
      }
    } else {
      console.log('  No hotspot 41 records');
    }

    console.log('\n📍 Route 348:');
    if (route348.length > 0) {
      for (const r of route348) {
        console.log(`  - itinerary_route_hotspot_ID: ${r.itinerary_route_hotspot_ID}`);
        console.log(`    item_type: ${r.item_type}`);
        console.log(`    hotspot_plan_own_way: ${r.hotspot_plan_own_way}`);
        console.log(`    status: ${r.status}, deleted: ${r.deleted}`);
      }
    } else {
      console.log('  No hotspot 41 records');
    }

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

check();
