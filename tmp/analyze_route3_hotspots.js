const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeRoute3Hotspots() {
  // Get all hotspots with "Pondicherry Airport" or "Pondicherry" in location
  const allHotspots = await prisma.dvi_hotspot_place.findMany({
    where: {
      OR: [
        { hotspot_location: { contains: 'Pondicherry Airport' } },
        { hotspot_location: { contains: 'Pondicherry' } }
      ],
      deleted: 0
    },
    orderBy: { hotspot_ID: 'asc' }
  });

  console.log('\n=== ALL PONDICHERRY/PONDICHERRY AIRPORT HOTSPOTS ===\n');
  
  const relevantIds = [16, 18, 20, 23, 24, 25, 676, 669];
  const relevantHotspots = allHotspots.filter(h => relevantIds.includes(h.hotspot_ID));
  
  for (const h of relevantHotspots) {
    const inPlan2 = [18, 25, 16, 23, 20, 676, 669].includes(h.hotspot_ID);
    const inPlan5 = [18, 25, 20, 16, 23, 24, 676, 669].includes(h.hotspot_ID);
    const plan2Order = [18, 25, 16, 23, 20, 676, 669].indexOf(h.hotspot_ID) + 1;
    const plan5Order = [18, 25, 20, 16, 23, 24, 676, 669].indexOf(h.hotspot_ID) + 1;
    
    console.log(`ID ${h.hotspot_ID}: ${h.hotspot_name}`);
    console.log(`  Location: ${h.hotspot_location}`);
    console.log(`  Rating (Priority): ${h.hotspot_rating}`);
    console.log(`  Type: ${h.hotspot_type}`);
    console.log(`  Lat/Lng: ${h.hotspot_latitude}, ${h.hotspot_longitude}`);
    console.log(`  Plan 2: ${inPlan2 ? `✅ Order ${plan2Order}` : '❌ Not selected'}`);
    console.log(`  Plan 5: ${inPlan5 ? `✅ Order ${plan5Order}` : '❌ Not selected'}`);
    console.log();
  }

  // Calculate distances from Pondicherry location
  console.log('\n=== CALCULATING DISTANCES FROM PONDICHERRY (11.9139, 79.8145) ===\n');
  const pondicherryLat = 11.9139;
  const pondicherryLng = 79.8145;
  
  function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Earth radius in km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
      Math.sin(dLat/2) * Math.sin(dLat/2) +
      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
      Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
  }
  
  const hotspotsWithDistance = relevantHotspots.map(h => {
    const distance = calculateDistance(
      pondicherryLat,
      pondicherryLng,
      Number(h.hotspot_latitude),
      Number(h.hotspot_longitude)
    );
    return { ...h, distance };
  }).sort((a, b) => a.distance - b.distance);
  
  for (const h of hotspotsWithDistance) {
    console.log(`${h.hotspot_ID}: ${h.hotspot_name} - ${h.distance.toFixed(2)} km (Priority: ${h.hotspot_rating})`);
  }

  await prisma.$disconnect();
}

analyzeRoute3Hotspots();
