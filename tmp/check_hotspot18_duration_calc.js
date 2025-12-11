const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Fetch hotspot 18
  const hotspot = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 18 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_duration: true,
    }
  });

  console.log('\nHotspot 18 Data:');
  console.log(JSON.stringify(hotspot, null, 2));
  
  if (hotspot?.hotspot_duration) {
    const durDate = hotspot.hotspot_duration;
    console.log('\nDuration as Date:', durDate);
    console.log('Type:', typeof durDate);
    
    const h = durDate.getUTCHours();
    const m = durDate.getUTCMinutes();
    const s = durDate.getUTCSeconds();
    
    console.log(`UTC extraction: ${h}h ${m}m ${s}s`);
    
    const timeString = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    console.log('Time string:', timeString);
    
    // Calculate seconds
    const totalSeconds = h * 3600 + m * 60 + s;
    console.log('Total seconds:', totalSeconds);
    console.log('Hours:', totalSeconds / 3600);
  }
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
