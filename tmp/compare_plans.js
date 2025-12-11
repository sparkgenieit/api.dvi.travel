const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function comparePlans() {
  try {
    console.log('\n=== COMPARING PLAN 2 AND PLAN 5 ===\n');
    
    // Get data for plan 2 (PHP generated)
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
    
    // Get data for plan 5 (NestJS generated)
    const plan5Data = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 5,
        deleted: 0
      },
      orderBy: [
        { itinerary_route_ID: 'asc' },
        { hotspot_order: 'asc' }
      ]
    });
    
    console.log(`Plan 2 (PHP) has ${plan2Data.length} rows`);
    console.log(`Plan 5 (NestJS) has ${plan5Data.length} rows\n`);
    
    // Display Plan 2 data
    console.log('=== PLAN 2 DATA (PHP Generated - Reference) ===');
    plan2Data.forEach((row, index) => {
      console.log(`\nRow ${index + 1}:`);
      console.log(JSON.stringify(row, (key, value) => {
        if (value instanceof Date) {
          return value.toISOString();
        }
        return value;
      }, 2));
    });
    
    console.log('\n\n=== PLAN 5 DATA (NestJS Generated - To Fix) ===');
    plan5Data.forEach((row, index) => {
      console.log(`\nRow ${index + 1}:`);
      console.log(JSON.stringify(row, (key, value) => {
        if (value instanceof Date) {
          return value.toISOString();
        }
        return value;
      }, 2));
    });
    
    // Compare fields
    console.log('\n\n=== FIELD DIFFERENCES ===\n');
    
    const maxRows = Math.max(plan2Data.length, plan5Data.length);
    
    for (let i = 0; i < maxRows; i++) {
      const p2 = plan2Data[i];
      const p5 = plan5Data[i];
      
      if (!p2) {
        console.log(`Row ${i + 1}: Missing in Plan 2`);
        continue;
      }
      
      if (!p5) {
        console.log(`Row ${i + 1}: Missing in Plan 5`);
        continue;
      }
      
      const differences = [];
      
      // Compare each field
      Object.keys(p2).forEach(key => {
        if (key === 'route_hotspot_ID' || key === 'itinerary_plan_ID' || key === 'createdon' || key === 'updatedon') {
          return; // Skip ID and timestamp fields
        }
        
        let val2 = p2[key];
        let val5 = p5[key];
        
        // Convert dates to comparable format
        if (val2 instanceof Date) val2 = val2.toISOString();
        if (val5 instanceof Date) val5 = val5.toISOString();
        
        // Convert BigInt to string for comparison
        if (typeof val2 === 'bigint') val2 = val2.toString();
        if (typeof val5 === 'bigint') val5 = val5.toString();
        
        if (JSON.stringify(val2) !== JSON.stringify(val5)) {
          differences.push({
            field: key,
            plan2: val2,
            plan5: val5
          });
        }
      });
      
      if (differences.length > 0) {
        console.log(`\nRow ${i + 1} (route_hotspot_ID - P2: ${p2.route_hotspot_ID}, P5: ${p5.route_hotspot_ID}):`);
        differences.forEach(diff => {
          console.log(`  ${diff.field}:`);
          console.log(`    Plan 2: ${diff.plan2}`);
          console.log(`    Plan 5: ${diff.plan5}`);
        });
      }
    }
    
  } catch (error) {
    console.error('Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

comparePlans();
