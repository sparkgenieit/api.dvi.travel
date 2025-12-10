import { PrismaClient } from '@prisma/client';

async function main() {
  const prisma = new PrismaClient();

  try {
    // Get plan 4 routes with hotspot count
    const routes = await prisma.$queryRaw`
      SELECT 
        id,
        from_location,
        to_location,
        distance,
        duration_in_minutes
      FROM dvi_itinerary_routes
      WHERE itinerary_id = 4
      ORDER BY id ASC
    `;

    console.log('Plan 4 Routes:');
    console.log('─'.repeat(100));
    (routes as any[]).forEach(r => {
      console.log(
        `Route ${r.id}: ${r.from_location} → ${r.to_location} | ` +
        `Distance: ${r.distance}km | Duration: ${r.duration_in_minutes}min`
      );
    });

    // Get itinerary details
    const itinerary = await prisma.$queryRaw`
      SELECT 
        id,
        start_date,
        end_date,
        total_days,
        start_time,
        end_time
      FROM dvi_itineraries
      WHERE id = 4
    `;

    console.log('\nPlan 4 Itinerary Details:');
    console.log('─'.repeat(100));
    if ((itinerary as any[]).length > 0) {
      const itin = (itinerary as any[])[0];
      console.log(`Start: ${itin.start_date} at ${itin.start_time}`);
      console.log(`End: ${itin.end_date} at ${itin.end_time}`);
      console.log(`Total Days: ${itin.total_days}`);
    }

    // Check route_hotspot_details current state
    const details = await prisma.$queryRaw`
      SELECT 
        route_id,
        COUNT(*) as total_items,
        SUM(CASE WHEN item_type IN (3, 4) THEN 1 ELSE 0 END) as hotspot_count,
        SUM(CASE WHEN item_type = 1 THEN 1 ELSE 0 END) as travel_count
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_id = 4
      GROUP BY route_id
      ORDER BY route_id
    `;

    console.log('\nCurrent Route Details (Plan 4):');
    console.log('─'.repeat(100));
    (details as any[]).forEach(d => {
      console.log(
        `Route ${d.route_id}: Total=${d.total_items}, Travel=${d.travel_count}, Hotspots=${d.hotspot_count}`
      );
    });

  } finally {
    await prisma.$disconnect();
  }
}

main().catch(console.error);
