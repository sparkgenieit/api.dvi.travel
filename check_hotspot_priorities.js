const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspotPriorities() {
  const hotspotIds = [4, 544, 18, 21, 19, 17, 678, 679];
  
  const hotspots = await prisma.dvi_hotspot_place.findMany({
    where: { hotspot_ID: { in: hotspotIds } },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true,
      hotspot_location: true,
      hotspot_latitude: true,
      hotspot_longitude: true
    },
    orderBy: { hotspot_ID: 'asc' }
  });
  
  console.log('HOTSPOT PRIORITIES AND LOCATIONS:');
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
  console.log('ID\tPriority\tName\t\t\t\tLocation');
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
  
  hotspots.forEach(h => {
    const name = (h.hotspot_name || '').padEnd(30);
    console.log(`${h.hotspot_ID}\t${h.hotspot_priority || 0}\t\t${name}\t${h.hotspot_location}`);
  });
  
  console.log('\n\nPHP Route 179 Expected Order: 4, 18, 21, 19, 17, 678');
  console.log('NestJS Route 380 Actual Order: 4, 544, 18, 21, 19, 679\n');
  
  // Calculate distances from Chennai (stored_location)
  const chennaiCoords = { lat: 12.9811068, lon: 80.159623 }; // From logs
  
  console.log('\nDISTANCES FROM CHENNAI:');
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
  
  const distances = hotspots.map(h => {
    const lat = parseFloat(h.hotspot_latitude);
    const lon = parseFloat(h.hotspot_longitude);
    
    if (!lat || !lon) return { id: h.hotspot_ID, dist: 0 };
    
    const earthRadius = 6371;
    const dLat = ((lat - chennaiCoords.lat) * Math.PI) / 180;
    const dLon = ((lon - chennaiCoords.lon) * Math.PI) / 180;
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos((chennaiCoords.lat * Math.PI) / 180) *
        Math.cos((lat * Math.PI) / 180) *
        Math.sin(dLon / 2) *
        Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = earthRadius * c * 1.5;
    
    return {
      id: h.hotspot_ID,
      name: h.hotspot_name,
      priority: h.hotspot_priority || 0,
      dist: distance.toFixed(2)
    };
  });
  
  distances.sort((a, b) => {
    if (a.priority === 0 && b.priority !== 0) return 1;
    if (a.priority !== 0 && b.priority === 0) return -1;
    if (a.priority === b.priority) return parseFloat(a.dist) - parseFloat(b.dist);
    return a.priority - b.priority;
  });
  
  console.log('ID\tPriority\tDistance(km)\tName');
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
  distances.forEach(d => {
    console.log(`${d.id}\t${d.priority}\t\t${d.dist}\t\t${d.name}`);
  });
  
  await prisma.$disconnect();
}

checkHotspotPriorities().catch(console.error);
