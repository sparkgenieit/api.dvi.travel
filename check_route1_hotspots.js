const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute1() {
  try {
    // Get Plan 2 routes
    const routes = await prisma.itinerary_routes.findMany({
      where: { plan_id: 2 },
      orderBy: { route_order: 'asc' }
    });

    console.log('\n=== PHP PLAN 2 ROUTE STRUCTURE ===\n');
    routes.forEach((r, i) => {
      console.log(`Route ${i+1} (ID ${r.route_id}): ${r.visiting_place} → ${r.next_visiting_place}`);
    });

    // Get Route 1 hotspots
    const route1Timeline = await prisma.itinerary_timeline.findMany({
      where: { route_id: routes[0].route_id },
      orderBy: { timeline_order: 'asc' }
    });

    const route1Hotspots = route1Timeline.filter(t => t.item_type === 4).map(t => t.hotspot_id);
    console.log(`\nRoute 1 Hotspots: [${route1Hotspots.join(', ')}]`);

    // Get Route 2 hotspots
    const route2Timeline = await prisma.itinerary_timeline.findMany({
      where: { route_id: routes[1].route_id },
      orderBy: { timeline_order: 'asc' }
    });

    const route2Hotspots = route2Timeline.filter(t => t.item_type === 4).map(t => t.hotspot_id);
    console.log(`Route 2 Hotspots: [${route2Hotspots.join(', ')}]`);

    // Check overlaps
    const overlap = route1Hotspots.filter(id => route2Hotspots.includes(id));
    
    console.log('\n=== ANALYSIS ===\n');
    if (overlap.length > 0) {
      console.log(`✅ PHP ALLOWS HOTSPOT REUSE! Shared hotspots: [${overlap.join(', ')}]`);
      console.log('\nThis means NestJS duplicate prevention is TOO STRICT.');
      console.log('We need to allow hotspots to be used across different routes.');
    } else {
      console.log('❌ No shared hotspots between Route 1 and Route 2');
      console.log('PHP also prevents duplicate hotspots across routes');
    }

    if (route1Hotspots.includes(4)) {
      console.log('\n🔴 Hotspot 4 IS in Route 1');
    }
    if (route2Hotspots.includes(4)) {
      console.log('🔴 Hotspot 4 IS in Route 2');
    }

  } finally {
    await prisma.$disconnect();
  }
}

checkRoute1().catch(console.error);
