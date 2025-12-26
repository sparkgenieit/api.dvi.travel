const { PrismaClient } = require('@prisma/client');

const prisma = new PrismaClient();

async function main() {
  try {
    console.log('=== HotspotDistanceCache Verification ===\n');
    
    const count = await prisma.hotspotDistanceCache.count();
    console.log(`✅ Total cache rows: ${count}\n`);
    
    // Check for bidirectional entries
    const all = await prisma.hotspotDistanceCache.findMany({
      orderBy: { createdAt: 'desc' }
    });
    
    console.log('Sample cache entries:');
    const sampleSize = Math.min(10, all.length);
    for (let i = 0; i < sampleSize; i++) {
      const row = all[i];
      console.log(`  ${row.fromHotspotId} → ${row.toHotspotId} (type ${row.travelLocationType})`);
      console.log(`    Distance: ${row.distanceKm} km, Travel time: ${formatTime(row.travelTime)}, Speed: ${row.speedKmph} km/h`);
      console.log(`    Haversine: ${row.haversineKm} km, Correction: ${row.correctionFactor}`);
    }
    
    // Verify bidirectional entries exist
    console.log('\n=== Bidirectional Verification ===');
    const first = all[0];
    const reverse = await prisma.hotspotDistanceCache.findUnique({
      where: {
        fromHotspotId_toHotspotId_travelLocationType: {
          fromHotspotId: first.toHotspotId,
          toHotspotId: first.fromHotspotId,
          travelLocationType: first.travelLocationType
        }
      }
    });
    
    if (reverse) {
      console.log(`✅ Reverse direction found for ${first.fromHotspotId} → ${first.toHotspotId}`);
      console.log(`   Forward:  ${first.distanceKm} km`);
      console.log(`   Reverse:  ${reverse.distanceKm} km`);
      console.log(`   Match: ${Number(first.distanceKm) === Number(reverse.distanceKm) ? '✅ YES' : '❌ NO'}`);
    } else {
      console.log(`❌ Reverse direction NOT found`);
    }
    
  } catch (err) {
    console.error('Error:', err.message);
  } finally {
    await prisma.$disconnect();
  }
}

function formatTime(dateOrNull) {
  if (!dateOrNull) return '00:00:00';
  if (dateOrNull instanceof Date) {
    const h = String(dateOrNull.getUTCHours()).padStart(2, '0');
    const m = String(dateOrNull.getUTCMinutes()).padStart(2, '0');
    const s = String(dateOrNull.getUTCSeconds()).padStart(2, '0');
    return `${h}:${m}:${s}`;
  }
  return String(dateOrNull);
}

main();
