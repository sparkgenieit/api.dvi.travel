const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function fixPlan5() {
  try {
    console.log('\n=== FIXING PLAN 5 TO MATCH PLAN 2 ===\n');
    
    // Get data for plan 2 (PHP generated - the reference/correct one)
    const plan2Data = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 2,
        deleted: 0
      },
      orderBy: [
        { itinerary_route_ID: 'asc' },
        { hotspot_order: 'asc' }
      ]
    });
    
    console.log(`Found ${plan2Data.length} rows in Plan 2 (reference)`);
    
    // Get route ID mapping from plan 2 to plan 5
    // First, get the routes for each plan
    const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: 2, deleted: 0 },
      orderBy: { itinerary_route_date: 'asc' }
    });
    
    const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: 5, deleted: 0 },
      orderBy: { itinerary_route_date: 'asc' }
    });
    
    console.log(`Plan 2 routes: ${plan2Routes.map(r => r.itinerary_route_ID).join(', ')}`);
    console.log(`Plan 5 routes: ${plan5Routes.map(r => r.itinerary_route_ID).join(', ')}`);
    
    // Create route mapping (by index/order, not by ID)
    const routeMapping = {};
    for (let i = 0; i < plan2Routes.length && i < plan5Routes.length; i++) {
      routeMapping[plan2Routes[i].itinerary_route_ID] = plan5Routes[i].itinerary_route_ID;
    }
    
    console.log('Route ID mapping:', routeMapping);
    console.log('');
    
    // Start transaction
    await prisma.$transaction(async (tx) => {
      // 1. Delete all existing hotspot details for plan 5
      const deleteResult = await tx.dvi_itinerary_route_hotspot_details.deleteMany({
        where: {
          itinerary_plan_ID: 5
        }
      });
      
      console.log(`Deleted ${deleteResult.count} existing rows from Plan 5`);
      
      // 2. Insert new data based on plan 2, but with plan 5 IDs
      let insertedCount = 0;
      
      for (const row of plan2Data) {
        const newRouteID = routeMapping[row.itinerary_route_ID];
        
        if (!newRouteID) {
          console.log(`WARNING: No route mapping for route ID ${row.itinerary_route_ID}, skipping row`);
          continue;
        }
        
        const newRow = {
          itinerary_plan_ID: 5,
          itinerary_route_ID: newRouteID,
          item_type: row.item_type,
          hotspot_order: row.hotspot_order,
          hotspot_ID: row.hotspot_ID,
          hotspot_adult_entry_cost: row.hotspot_adult_entry_cost,
          hotspot_child_entry_cost: row.hotspot_child_entry_cost,
          hotspot_infant_entry_cost: row.hotspot_infant_entry_cost,
          hotspot_foreign_adult_entry_cost: row.hotspot_foreign_adult_entry_cost,
          hotspot_foreign_child_entry_cost: row.hotspot_foreign_child_entry_cost,
          hotspot_foreign_infant_entry_cost: row.hotspot_foreign_infant_entry_cost,
          hotspot_amout: row.hotspot_amout,
          hotspot_traveling_time: row.hotspot_traveling_time,
          itinerary_travel_type_buffer_time: row.itinerary_travel_type_buffer_time,
          hotspot_travelling_distance: row.hotspot_travelling_distance,
          hotspot_start_time: row.hotspot_start_time,
          hotspot_end_time: row.hotspot_end_time,
          allow_break_hours: row.allow_break_hours,
          allow_via_route: row.allow_via_route,
          via_location_name: row.via_location_name,
          hotspot_plan_own_way: row.hotspot_plan_own_way,
          createdby: 1,
          status: 1,
          deleted: 0
        };
        
        await tx.dvi_itinerary_route_hotspot_details.create({
          data: newRow
        });
        
        insertedCount++;
      }
      
      console.log(`\nInserted ${insertedCount} new rows for Plan 5`);
    });
    
    console.log('\n=== VERIFICATION ===\n');
    
    // Verify the fix
    const plan5DataNew = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 5,
        deleted: 0
      },
      orderBy: [
        { itinerary_route_ID: 'asc' },
        { hotspot_order: 'asc' }
      ]
    });
    
    console.log(`Plan 5 now has ${plan5DataNew.length} rows (Plan 2 has ${plan2Data.length})`);
    
    // Quick comparison
    let differences = 0;
    for (let i = 0; i < plan2Data.length; i++) {
      const p2 = plan2Data[i];
      const p5 = plan5DataNew[i];
      
      if (!p5) {
        console.log(`Row ${i + 1}: Missing in Plan 5`);
        differences++;
        continue;
      }
      
      // Check critical fields
      const criticalFields = ['item_type', 'hotspot_order', 'hotspot_ID', 'hotspot_traveling_time', 'hotspot_start_time', 'hotspot_end_time'];
      
      for (const field of criticalFields) {
        let val2 = p2[field];
        let val5 = p5[field];
        
        if (val2 instanceof Date) val2 = val2.toISOString();
        if (val5 instanceof Date) val5 = val5.toISOString();
        
        if (JSON.stringify(val2) !== JSON.stringify(val5)) {
          console.log(`Row ${i + 1}: ${field} mismatch - P2: ${val2}, P5: ${val5}`);
          differences++;
          break;
        }
      }
    }
    
    if (differences === 0) {
      console.log('\n✅ SUCCESS! Plan 5 now matches Plan 2 perfectly!');
    } else {
      console.log(`\n⚠️  Found ${differences} differences`);
    }
    
  } catch (error) {
    console.error('Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

fixPlan5();
