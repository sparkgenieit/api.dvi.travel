import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function check() {
  try {
    console.log('\n🔍 HOTSPOT 41 DETAILED ANALYSIS\n');

    // 1. Check master hotspot
    const hotspot = await (prisma as any).dvi_hotspot_place.findUnique({
      where: { hotspot_ID: 41 },
      select: { hotspot_name: true, hotspot_location: true },
    });

    console.log('✅ Master Hotspot 41:');
    console.log(`   Name: ${hotspot?.hotspot_name}`);
    console.log(`   Location: ${hotspot?.hotspot_location}`);

    // 2. Check route 347 and 348
    const route347 = await (prisma as any).dvi_itinerary_route_details.findUnique({
      where: { itinerary_route_ID: 347 },
      select: {
        itinerary_route_ID: true,
        location_name: true,
        next_visiting_location: true,
        itinerary_plan_ID: true,
      },
    });

    const route348 = await (prisma as any).dvi_itinerary_route_details.findUnique({
      where: { itinerary_route_ID: 348 },
      select: {
        itinerary_route_ID: true,
        location_name: true,
        next_visiting_location: true,
        itinerary_plan_ID: true,
      },
    });

    console.log('\n📍 Route 347:', route347?.location_name, '→', route347?.next_visiting_location);
    console.log('📍 Route 348:', route348?.location_name, '→', route348?.next_visiting_location);

    // 3. Check if hotspot 41 is in route 347
    const inRoute347 = await (prisma as any).dvi_itinerary_route_hotspot_details.findFirst({
      where: {
        itinerary_route_ID: 347,
        hotspot_ID: 41,
        deleted: 0,
        status: 1,
      },
      select: { hotspot_ID: true, status: true, deleted: true },
    });

    console.log('\n❓ Is hotspot 41 in route 347?', inRoute347 ? '✅ YES' : '❌ NO');

    // 4. Check if hotspot 41 should be in route 348
    const inRoute348 = await (prisma as any).dvi_itinerary_route_hotspot_details.findFirst({
      where: {
        itinerary_route_ID: 348,
        hotspot_ID: 41,
        deleted: 0,
        status: 1,
      },
      select: { hotspot_ID: true, status: true, deleted: true },
    });

    console.log('❓ Is hotspot 41 in route 348?', inRoute348 ? '✅ YES' : '❌ NO');

    // 5. Show the location names and match
    console.log('\n🎯 LOCATION MATCHING ANALYSIS:');
    console.log(`   Hotspot 41 location: "${hotspot?.hotspot_location}"`);
    console.log(`   Route 347 location: "${route347?.location_name}"`);
    console.log(`   Route 348 location: "${route348?.location_name}"`);

    const match347 = hotspot?.hotspot_location?.includes(route347?.location_name!);
    const match348 = hotspot?.hotspot_location?.includes(route348?.location_name!);

    console.log(`\n   Hotspot location matches Route 347? ${match347 ? '✅ YES' : '❌ NO'}`);
    console.log(`   Hotspot location matches Route 348? ${match348 ? '✅ YES' : '❌ NO'}`);

    console.log('\n💡 SOLUTION:');
    if (match348 && !match347) {
      console.log('   ⚠️  Add hotspot 41 to Route 348 instead of Route 347');
      console.log('   Route 347 is travel day (Madurai Airport → Rameswaram)');
      console.log('   Route 348 is sightseeing day (Rameswaram → Kanyakumari)');
      console.log('   Hotspot 41 is in Rameswaram, so it belongs to Route 348');
    }

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

check();
