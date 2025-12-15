const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkOperatingHours() {
  try {
    const hours = await prisma.dvi_hotspot_timing.findMany({
      where: { hotspot_ID: 26, deleted: 0 },
    });

    console.log('\n=== Meenakshi Amman Temple (ID: 26) Operating Hours ===\n');
    
    if (hours.length === 0) {
      console.log('❌ No operating hours defined!');
      console.log('This means the temple is treated as always open, but may be filtered by other logic.');
    } else {
      const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
      hours.forEach(h => {
        console.log(`${days[h.hotspot_timing_day]}: ${h.hotspot_start_time} - ${h.hotspot_end_time}`);
        console.log(`  Open all time: ${h.hotspot_open_all_time}, Closed: ${h.hotspot_closed}, Status: ${h.status}`);
      });
    }

    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

checkOperatingHours();
