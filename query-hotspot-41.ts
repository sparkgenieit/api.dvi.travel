#!/usr/bin/env node

/**
 * Database Query Script - Hotspot 41 Functionality
 * Queries all related data for hotspot preview functionality
 */

import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  console.log('\n' + '='.repeat(80));
  console.log('🔍 HOTSPOT 41 - DATABASE QUERIES & OUTPUT');
  console.log('='.repeat(80) + '\n');

  try {
    // ============================================================================
    // QUERY 1: Route 348 Details
    // ============================================================================
    console.log('1️⃣  ROUTE DETAILS (Route 348)');
    console.log('-'.repeat(80));
    console.log('SQL Query:');
    console.log(`
      SELECT 
        itinerary_route_ID,
        location_name,
        next_visiting_location,
        itinerary_route_date,
        route_start_time,
        route_end_time,
        direct_to_next_visiting_place
      FROM dvi_itinerary_route_details
      WHERE itinerary_plan_ID = 17 AND itinerary_route_ID = 348;
    `);
    console.log('-'.repeat(80));

    const route348 = await (prisma as any).dvi_itinerary_route_details.findFirst({
      where: { itinerary_plan_ID: 17, itinerary_route_ID: 348 },
      select: {
        itinerary_route_ID: true,
        location_name: true,
        next_visiting_location: true,
        itinerary_route_date: true,
        route_start_time: true,
        route_end_time: true,
        direct_to_next_visiting_place: true,
      },
    });

    console.log('OUTPUT:');
    console.log(JSON.stringify(route348, null, 2));
    console.log('\n');

    // ============================================================================
    // QUERY 2: Hotspot 41 Master Data
    // ============================================================================
    console.log('2️⃣  HOTSPOT 41 - MASTER DATA');
    console.log('-'.repeat(80));
    console.log('SQL Query:');
    console.log(`
      SELECT 
        hotspot_ID,
        hotspot_name,
        hotspot_location,
        hotspot_latitude,
        hotspot_longitude,
        hotspot_duration,
        status,
        deleted
      FROM dvi_hotspot_place
      WHERE hotspot_ID = 41;
    `);
    console.log('-'.repeat(80));

    const hotspot41 = await (prisma as any).dvi_hotspot_place.findFirst({
      where: { hotspot_ID: 41 },
      select: {
        hotspot_ID: true,
        hotspot_name: true,
        hotspot_location: true,
        hotspot_latitude: true,
        hotspot_longitude: true,
        hotspot_duration: true,
        status: true,
        deleted: true,
      },
    });

    console.log('OUTPUT:');
    console.log(JSON.stringify(hotspot41, null, 2));
    console.log('\n');

    // ============================================================================
    // QUERY 3: All Hotspots Assigned to Route 348
    // ============================================================================
    console.log('3️⃣  HOTSPOTS ASSIGNED TO ROUTE 348');
    console.log('-'.repeat(80));
    console.log('SQL Query:');
    console.log(`
      SELECT 
        route_hotspot_ID,
        itinerary_plan_ID,
        itinerary_route_ID,
        hotspot_ID,
        item_type,
        hotspot_plan_own_way,
        \`order\`,
        start_time,
        end_time,
        duration,
        status,
        deleted
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = 17 
        AND itinerary_route_ID = 348
        AND deleted = 0
        AND status = 1
      ORDER BY \`order\`;
    `);
    console.log('-'.repeat(80));

    const route348Hotspots = await (prisma as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 17,
        itinerary_route_ID: 348,
        deleted: 0,
        status: 1,
      },
      select: {
        route_hotspot_ID: true,
        itinerary_plan_ID: true,
        itinerary_route_ID: true,
        hotspot_ID: true,
        item_type: true,
        hotspot_plan_own_way: true,
        hotspot_order: true,
        hotspot_start_time: true,
        hotspot_end_time: true,
        hotspot_traveling_time: true,
        status: true,
        deleted: true,
      },
      orderBy: { hotspot_order: 'asc' },
    });

    console.log(`OUTPUT (${route348Hotspots.length} records):`);
    console.log(JSON.stringify(route348Hotspots, null, 2));
    console.log('\n');

    // ============================================================================
    // QUERY 4: Check if Hotspot 41 is Assigned to Route 348
    // ============================================================================
    console.log('4️⃣  HOTSPOT 41 ASSIGNMENT CHECK - ROUTE 348');
    console.log('-'.repeat(80));
    console.log('SQL Query:');
    console.log(`
      SELECT 
        route_hotspot_ID,
        hotspot_ID,
        item_type,
        hotspot_plan_own_way,
        \`order\`,
        start_time,
        end_time,
        status,
        deleted
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = 17 
        AND itinerary_route_ID = 348
        AND hotspot_ID = 41;
    `);
    console.log('-'.repeat(80));

    const hs41Assignment = await (prisma as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 17,
        itinerary_route_ID: 348,
        hotspot_ID: 41,
      },
      select: {
        route_hotspot_ID: true,
        hotspot_ID: true,
        item_type: true,
        hotspot_plan_own_way: true,
        hotspot_order: true,
        hotspot_start_time: true,
        hotspot_end_time: true,
        status: true,
        deleted: true,
      },
    });

    if (hs41Assignment.length > 0) {
      console.log(`✅ OUTPUT (${hs41Assignment.length} records found):`);
      console.log(JSON.stringify(hs41Assignment, null, 2));
    } else {
      console.log('❌ OUTPUT: No records found for Hotspot 41 in Route 348');
    }
    console.log('\n');

    // ============================================================================
    // QUERY 5: Attractions Only (item_type = 4) in Route 348
    // ============================================================================
    console.log('5️⃣  ATTRACTIONS ONLY (item_type = 4) IN ROUTE 348');
    console.log('-'.repeat(80));
    console.log('SQL Query:');
    console.log(`
      SELECT 
        route_hotspot_ID,
        hotspot_ID,
        item_type,
        hotspot_plan_own_way,
        \`order\`
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = 17 
        AND itinerary_route_ID = 348
        AND deleted = 0
        AND status = 1
        AND item_type = 4
      ORDER BY \`order\`;
    `);
    console.log('-'.repeat(80));

    const attractions = await (prisma as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 17,
        itinerary_route_ID: 348,
        deleted: 0,
        status: 1,
        item_type: 4,
      },
      select: {
        route_hotspot_ID: true,
        hotspot_ID: true,
        item_type: true,
        hotspot_plan_own_way: true,
        hotspot_order: true,
      },
      orderBy: { hotspot_order: 'asc' },
    });

    console.log(`OUTPUT (${attractions.length} attractions):`);
    console.log(JSON.stringify(attractions, null, 2));
    
    // Check if 41 is in the list
    const hasHotspot41 = attractions.some(a => a.hotspot_ID === 41);
    console.log(`\n${hasHotspot41 ? '✅ Hotspot 41 FOUND' : '❌ Hotspot 41 NOT FOUND'}`);
    console.log('\n');

    // ============================================================================
    // QUERY 6: All Routes in Plan 17
    // ============================================================================
    console.log('6️⃣  ALL ROUTES IN PLAN 17');
    console.log('-'.repeat(80));
    console.log('SQL Query:');
    console.log(`
      SELECT 
        itinerary_route_ID,
        location_name,
        next_visiting_location,
        itinerary_route_date
      FROM dvi_itinerary_route_details
      WHERE itinerary_plan_ID = 17
      ORDER BY itinerary_route_date ASC;
    `);
    console.log('-'.repeat(80));

    const allRoutes = await (prisma as any).dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: 17 },
      select: {
        itinerary_route_ID: true,
        location_name: true,
        next_visiting_location: true,
        itinerary_route_date: true,
      },
      orderBy: { itinerary_route_date: 'asc' },
    });

    console.log(`OUTPUT (${allRoutes.length} routes):`);
    console.log(JSON.stringify(allRoutes, null, 2));
    console.log('\n');

    // ============================================================================
    // QUERY 7: All Hotspots in Plan 17
    // ============================================================================
    console.log('7️⃣  ALL HOTSPOTS IN PLAN 17 (item_type = 4)');
    console.log('-'.repeat(80));
    console.log('SQL Query:');
    console.log(`
      SELECT 
        itinerary_route_ID,
        hotspot_ID,
        hotspot_plan_own_way,
        \`order\`,
        COUNT(*) as count
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = 17 
        AND deleted = 0
        AND status = 1
        AND item_type = 4
      GROUP BY itinerary_route_ID, hotspot_ID
      ORDER BY itinerary_route_ID, \`order\`;
    `);
    console.log('-'.repeat(80));

    const allPlanHotspots = await (prisma as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 17,
        deleted: 0,
        status: 1,
        item_type: 4,
      },
      select: {
        itinerary_route_ID: true,
        hotspot_ID: true,
        hotspot_plan_own_way: true,
        hotspot_order: true,
      },
      orderBy: [{ itinerary_route_ID: 'asc' }, { hotspot_order: 'asc' }],
    });

    console.log(`OUTPUT (${allPlanHotspots.length} hotspot assignments):`);
    
    // Group by route for better readability
    const groupedByRoute = allPlanHotspots.reduce((acc: any, h: any) => {
      if (!acc[h.itinerary_route_ID]) {
        acc[h.itinerary_route_ID] = [];
      }
      acc[h.itinerary_route_ID].push(h);
      return acc;
    }, {});

    Object.entries(groupedByRoute).forEach(([routeId, hotspots]) => {
      console.log(`\n  Route ${routeId}:`);
      (hotspots as any[]).forEach(h => {
        const type = h.hotspot_plan_own_way === 1 ? '[MANUAL]' : '[AUTO]';
        console.log(`    - Hotspot ${h.hotspot_ID} ${type} Order: ${h.hotspot_order}`);
      });
    });
    console.log('\n');

    // ============================================================================
    // SUMMARY
    // ============================================================================
    console.log('='.repeat(80));
    console.log('📊 SUMMARY');
    console.log('='.repeat(80));
    console.log(`
Plan ID: 17
Route ID: 348 (${route348?.location_name} → ${route348?.next_visiting_location})
Hotspot ID: 41 (${hotspot41?.hotspot_name})

✅ Route 348 exists: ${route348 ? 'YES' : 'NO'}
✅ Hotspot 41 master record exists: ${hotspot41 ? 'YES' : 'NO'}
✅ Hotspot 41 assigned to Route 348: ${hs41Assignment.length > 0 ? `YES (${hs41Assignment.length} record(s))` : 'NO'}
✅ Hotspot 41 in attractions list: ${hasHotspot41 ? 'YES' : 'NO'}
✅ Total attractions in Route 348: ${attractions.length}
✅ Total hotspot assignments in Route 348: ${route348Hotspots.length}

Hotspot 41 Details:
  - Name: ${hotspot41?.hotspot_name}
  - Location: ${hotspot41?.hotspot_location}
  - Latitude: ${hotspot41?.hotspot_latitude}
  - Longitude: ${hotspot41?.hotspot_longitude}
  - Duration: ${hotspot41?.hotspot_duration}
  - Status: ${hotspot41?.status} (1=Active)
  - Deleted: ${hotspot41?.deleted} (0=Not Deleted)
    `);
    console.log('='.repeat(80) + '\n');

  } catch (error) {
    console.error('❌ Database Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

main();
