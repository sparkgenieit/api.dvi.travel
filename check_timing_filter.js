const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkTimingFilter() {
  try {
    console.log('=== CHECKING HOTSPOT TIMING FILTER ===\n');
    
    // Check timing for hotspots 4, 5, 544 on day 2 (Tuesday)
    console.log('Checking timing records for hotspots 4, 5, 544 on day 2:\n');
    
    const timing = await prisma.dvi_hotspot_timing.findMany({
      where: {
        hotspot_ID: { in: [4, 5, 544] },
        hotspot_timing_day: 2
      },
      select: {
        hotspot_ID: true,
        hotspot_timing_day: true,
        hotspot_start_time: true,
        hotspot_end_time: true,
        hotspot_closed: true,
        hotspot_open_all_time: true,
        status: true,
        deleted: true
      }
    });
    
    console.log(`Found ${timing.length} timing records for day 2:\n`);
    timing.forEach(t => {
      console.log(`Hotspot ${t.hotspot_ID}:`);
      console.log(`  Day: ${t.hotspot_timing_day}`);
      console.log(`  Start: ${t.hotspot_start_time}`);
      console.log(`  End: ${t.hotspot_end_time}`);
      console.log(`  Closed: ${t.hotspot_closed}`);
      console.log(`  Open All Time: ${t.hotspot_open_all_time}`);
      console.log(`  Status: ${t.status}, Deleted: ${t.deleted}`);
      console.log('');
    });
    
    // Now check which hotspots would be returned by PHP's query
    console.log('=== PHP QUERY SIMULATION ===\n');
    console.log('PHP uses: LEFT JOIN dvi_hotspot_timing WHERE hotspot_timing_day = 2');
    console.log('This means only hotspots WITH timing records for day 2 are returned.\n');
    
    const phpStyleQuery = await prisma.$queryRaw`
      SELECT DISTINCT HOTSPOT_PLACE.hotspot_ID, HOTSPOT_PLACE.hotspot_name, HOTSPOT_PLACE.hotspot_priority
      FROM dvi_hotspot_place HOTSPOT_PLACE
      LEFT JOIN dvi_hotspot_timing HOTSPOT_TIMING ON HOTSPOT_TIMING.hotspot_ID = HOTSPOT_PLACE.hotspot_ID
      WHERE HOTSPOT_PLACE.deleted = 0 
        AND HOTSPOT_PLACE.status = 1
        AND HOTSPOT_TIMING.hotspot_timing_day = 2
        AND HOTSPOT_PLACE.hotspot_ID IN (4, 5, 544)
      ORDER BY CASE WHEN HOTSPOT_PLACE.hotspot_priority = 0 THEN 1 ELSE 0 END, HOTSPOT_PLACE.hotspot_priority ASC
    `;
    
    console.log('Hotspots returned by PHP query (with timing filter):');
    console.table(phpStyleQuery);
    
    console.log('\n=== ANALYSIS ===');
    console.log('If Kapaleeshwarar (4) has NO timing record for day 2, PHP filters it out.');
    console.log('If Marina Beach (5) HAS timing record for day 2, PHP includes it.');
    console.log('This explains why PHP has 1 hotspot while NestJS has 2!\n');
    
    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

checkTimingFilter();
