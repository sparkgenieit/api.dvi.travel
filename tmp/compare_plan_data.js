const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function compareTableData(tableName, planIdField = 'plan_id', idField = 'id') {
  console.log(`\n${'='.repeat(80)}`);
  console.log(`Comparing table: ${tableName}`);
  console.log('='.repeat(80));

  try {
    const plan2Data = await prisma.$queryRawUnsafe(
      `SELECT * FROM ${tableName} WHERE ${planIdField} = 2 ORDER BY ${idField}`
    );
    const plan5Data = await prisma.$queryRawUnsafe(
      `SELECT * FROM ${tableName} WHERE ${planIdField} = 5 ORDER BY ${idField}`
    );

    console.log(`Plan 2 rows: ${plan2Data.length}`);
    console.log(`Plan 5 rows: ${plan5Data.length}`);

    if (plan2Data.length !== plan5Data.length) {
      console.log(`⚠️  ROW COUNT MISMATCH! Plan 2 has ${plan2Data.length} rows, Plan 5 has ${plan5Data.length} rows`);
    }

    // Compare each row
    const maxRows = Math.max(plan2Data.length, plan5Data.length);
    
    for (let i = 0; i < maxRows; i++) {
      const row2 = plan2Data[i];
      const row5 = plan5Data[i];

      if (!row2) {
        console.log(`\n❌ Row ${i + 1}: Missing in Plan 2, exists in Plan 5`);
        console.log('Plan 5 data:', row5);
        continue;
      }

      if (!row5) {
        console.log(`\n❌ Row ${i + 1}: Exists in Plan 2, missing in Plan 5`);
        console.log('Plan 2 data:', row2);
        continue;
      }

      // Compare all fields except id and auto-generated timestamps
      const differences = [];
      const excludeFields = ['id', 'created_at', 'updated_at'];
      
      const allKeys = new Set([...Object.keys(row2), ...Object.keys(row5)]);
      
      for (const key of allKeys) {
        if (excludeFields.includes(key)) continue;

        const val2 = row2[key];
        const val5 = row5[key];

        // Handle different types of comparisons
        if (val2 instanceof Date && val5 instanceof Date) {
          if (val2.getTime() !== val5.getTime()) {
            differences.push({
              field: key,
              plan2: val2.toISOString(),
              plan5: val5.toISOString()
            });
          }
        } else if (typeof val2 === 'number' && typeof val5 === 'number') {
          // Handle floating point comparison
          if (Math.abs(val2 - val5) > 0.0001) {
            differences.push({
              field: key,
              plan2: val2,
              plan5: val5
            });
          }
        } else if (val2 !== val5) {
          differences.push({
            field: key,
            plan2: val2,
            plan5: val5
          });
        }
      }

      if (differences.length > 0) {
        console.log(`\n⚠️  Row ${i + 1} (Plan 2 ID: ${row2[idField]}, Plan 5 ID: ${row5[idField]}) has differences:`);
        differences.forEach(diff => {
          console.log(`  Field: ${diff.field}`);
          console.log(`    Plan 2: ${JSON.stringify(diff.plan2)}`);
          console.log(`    Plan 5: ${JSON.stringify(diff.plan5)}`);
        });
      } else {
        console.log(`✅ Row ${i + 1} matches`);
      }
    }

    // Show sample data from each plan for reference
    if (plan2Data.length > 0) {
      console.log('\n--- Sample Plan 2 Row (first row) ---');
      console.log(JSON.stringify(plan2Data[0], null, 2));
    }
    if (plan5Data.length > 0) {
      console.log('\n--- Sample Plan 5 Row (first row) ---');
      console.log(JSON.stringify(plan5Data[0], null, 2));
    }

  } catch (error) {
    console.error(`Error comparing ${tableName}:`, error.message);
  }
}

async function main() {
  console.log('Starting comparison between Plan ID 2 (PHP) and Plan ID 5 (NestJS)');
  console.log('Timestamp:', new Date().toISOString());

  const tables = [
    { name: 'dvi_itinerary_traveller_details', field: 'itinerary_plan_ID', id: 'traveller_details_ID' },
    { name: 'dvi_itinerary_plan_hotel_details', field: 'itinerary_plan_id', id: 'itinerary_plan_hotel_details_ID' },
    { name: 'dvi_itinerary_plan_hotel_room_details', field: 'itinerary_plan_id', id: 'itinerary_plan_hotel_room_details_ID' },
    { name: 'dvi_itinerary_plan_vehicle_details', field: 'itinerary_plan_id', id: 'vehicle_details_ID' },
    { name: 'dvi_itinerary_plan_vendor_eligible_list', field: 'itinerary_plan_id', id: 'itinerary_plan_vendor_eligible_ID' },
    { name: 'dvi_itinerary_plan_vendor_vehicle_details', field: 'itinerary_plan_id', id: 'itinerary_plan_vendor_vehicle_details_ID' },
  ];

  for (const table of tables) {
    await compareTableData(table.name, table.field, table.id);
  }

  console.log('\n' + '='.repeat(80));
  console.log('Comparison complete');
  console.log('='.repeat(80));
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
