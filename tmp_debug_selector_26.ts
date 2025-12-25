import { PrismaClient } from '@prisma/client';
import { TimelinePrefetcher } from './src/modules/itineraries/engines/helpers/timeline.prefetch';
import { HotspotSelector } from './src/modules/itineraries/engines/helpers/timeline.hotspot-selector';

const prisma = new PrismaClient();

async function main() {
  const planId = 33977;
  const prefetcher = new TimelinePrefetcher();
  const selector = new HotspotSelector();

  const ctx = await prefetcher.fetchAll(prisma as any, planId);
  console.log('Total Hotspots fetched:', ctx.allHotspots.length);

  const id26 = ctx.allHotspots.find(h => Number(h.hotspot_ID) === 26);
  console.log('ID 26 in allHotspots:', id26);

  for (const route of ctx.routes) {
    console.log(`\nChecking Route ${route.itinerary_route_ID} (${route.location_name} -> ${route.next_visiting_location})`);
    const selected = await selector.selectForRoute(prisma as any, ctx.plan!, route, ctx, []);
    const has26 = selected.some(s => s.hotspot_ID === 26);
    console.log('Has ID 26:', has26);
    if (has26) {
        const s26 = selected.find(s => s.hotspot_ID === 26);
        console.log('Selection details for 26:', s26);
    }
  }
}

main().catch(console.error).finally(() => prisma.$disconnect());
