const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkOperatingHours() {
  try {
    console.log('=== CHECKING HOTSPOT OPERATING HOURS (Tuesday/dow=2) ===\n');
    
    const timing = await prisma.dvi_hotspot_timing.findMany({
      where: {
        hotspot_ID: { in: [4, 5, 544] },
        day_of_week: 2  // Tuesday (PHP uses 1=Sunday, 2=Monday... wait, or is it 0-based?)
      },
      select: {
        hotspot_ID: true,
        day_of_week: true,
        opening_time: true,
        closing_time: true,
        status: true
      }
    });
    
    console.log(`Found ${timing.length} timing records for Tuesday (dow=2)\n`);
    timing.forEach(t => {
      console.log(`Hotspot ${t.hotspot_ID}: ${t.opening_time} - ${t.closing_time} (status=${t.status})`);
    });
    
    // Let's also try dow=3 (might be Wednesday if 1-based with Sunday=1)
    console.log('\n=== ALSO CHECKING dow=3 ===\n');
    const timing3 = await prisma.dvi_hotspot_timing.findMany({
      where: {
        hotspot_ID: { in: [4, 5, 544] },
        day_of_week: 3
      },
      select: {
        hotspot_ID: true,
        day_of_week: true,
        opening_time: true,
        closing_time: true,
        status: true
      }
    });
    
    console.log(`Found ${timing3.length} timing records for dow=3\n`);
    timing3.forEach(t => {
      console.log(`Hotspot ${t.hotspot_ID}: ${t.opening_time} - ${t.closing_time} (status=${t.status})`);
    });
    
    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

checkOperatingHours();
