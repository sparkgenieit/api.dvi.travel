
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkPlan() {
  const planId = 33977;
  console.log(`Checking segments for Plan ID: ${planId}`);

  const segments = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_plan_ID: planId },
    orderBy: [
      { itinerary_route_ID: 'asc' },
      { hotspot_order: 'asc' }
    ]
  });

  const routeIds = [...new Set(segments.map(s => s.itinerary_route_ID))];
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_route_ID: { in: routeIds } },
    orderBy: { itinerary_route_ID: 'asc' }
  });

  const hotspotIds = [...new Set(segments.map(s => s.hotspot_ID).filter(id => id > 0))];
  const hotspots = await prisma.dvi_hotspot_place.findMany({
    where: { hotspot_ID: { in: hotspotIds } }
  });

  const routeToDay = new Map(routes.map((r, index) => [r.itinerary_route_ID, index + 1]));
  const routeMap = new Map(routes.map(r => [r.itinerary_route_ID, r]));
  const hotspotMap = new Map(hotspots.map(h => [h.hotspot_ID, h]));

  console.log("Day | Order | Type | Location/Hotspot | Start | End | Dist | Travel Time");
  console.log("--------------------------------------------------------------------------------");
  segments.forEach(s => {
    const day = routeToDay.get(s.itinerary_route_ID);
    const route = routeMap.get(s.itinerary_route_ID);
    const hotspot = hotspotMap.get(s.hotspot_ID);
    const name = s.item_type === 7 ? `Return to ${route?.next_visiting_location}` : (hotspot?.hotspot_name || "Unknown");
    
    const formatTime = (date: Date) => {
      return date.toISOString().split('T')[1].substring(0, 5);
    };

    console.log(
      `${String(day).padStart(3)} | ` +
      `${String(s.hotspot_order).padStart(5)} | ` +
      `${String(s.item_type).padStart(4)} | ` +
      `${name.substring(0, 30).padEnd(30)} | ` +
      `${formatTime(s.hotspot_start_time)} | ` +
      `${formatTime(s.hotspot_end_time)} | ` +
      `${String(s.hotspot_travelling_distance || 0).padStart(5)} | ` +
      `${formatTime(s.hotspot_traveling_time)}`
    );
  });
}

checkPlan()
  .catch(e => console.error(e))
  .finally(() => prisma.$disconnect());
