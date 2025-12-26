const { PrismaClient } = require("@prisma/client");

const prisma = new PrismaClient();

async function main() {
  try {
    // Get unique hotspot IDs from cache
    const cacheEntries = await prisma.hotspotDistanceCache.findMany({
      select: { fromHotspotId: true, toHotspotId: true },
      distinct: ["fromHotspotId", "toHotspotId"],
      take: 10,
    });

    const hotspotIds = new Set();
    cacheEntries.forEach((entry) => {
      hotspotIds.add(entry.fromHotspotId);
      hotspotIds.add(entry.toHotspotId);
    });

    console.log(`Found ${hotspotIds.size} unique hotspot IDs in cache`);
    console.log("IDs:", Array.from(hotspotIds).sort());

    // Check if these hotspots exist and have names
    const hotspots = await prisma.dvi_hotspot_place.findMany({
      where: {
        hotspot_ID: { in: Array.from(hotspotIds) },
      },
      select: {
        hotspot_ID: true,
        hotspot_name: true,
      },
    });

    console.log("\nHotspots found in database:");
    hotspots.forEach((hs) => {
      console.log(`  ID ${hs.hotspot_ID}: "${hs.hotspot_name}"`);
    });

    // Check DB schema
    const cacheSchema = await prisma.$queryRaw`
      SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE 
      FROM INFORMATION_SCHEMA.COLUMNS 
      WHERE TABLE_NAME = 'dvi_hotspot_distance_cache' 
      AND COLUMN_NAME LIKE '%hotspot_name%'
    `;

    console.log("\nCache table columns with 'hotspot_name':");
    console.table(cacheSchema);
  } catch (error) {
    console.error("Error:", error.message);
  } finally {
    await prisma.$disconnect();
  }
}

main();
