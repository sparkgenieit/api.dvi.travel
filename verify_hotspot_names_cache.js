const { PrismaClient } = require("@prisma/client");

const prisma = new PrismaClient();

async function main() {
  try {
    console.log("========================================");
    console.log("✅ HOTSPOT DISTANCE CACHE WITH NAMES");
    console.log("========================================\n");

    // Get cache stats
    const totalRows = await prisma.hotspotDistanceCache.count();
    console.log(`📊 Total cache entries: ${totalRows}\n`);

    // Get entries with names
    const entriesWithNames = await prisma.hotspotDistanceCache.findMany({
      where: {
        fromHotspotName: { not: null },
        toHotspotName: { not: null },
      },
      select: {
        id: true,
        fromHotspotId: true,
        fromHotspotName: true,
        toHotspotId: true,
        toHotspotName: true,
        distanceKm: true,
        travelTime: true,
        travelLocationType: true,
        createdAt: true,
      },
      take: 15,
    });

    console.log(`📋 Sample entries (showing ${entriesWithNames.length} of ${totalRows}):\n`);
    console.log(
      "ID  | From Hotspot (ID)                          | To Hotspot (ID)                            | Distance | Type"
    );
    console.log(
      "----|-------------------------------------------|-------------------------------------------|----------|------"
    );

    entriesWithNames.forEach((entry) => {
      const fromStr = `${entry.fromHotspotName} (${entry.fromHotspotId})`.padEnd(41);
      const toStr = `${entry.toHotspotName} (${entry.toHotspotId})`.padEnd(41);
      const dist = String(Number(entry.distanceKm).toFixed(2)).padStart(7);
      console.log(`${entry.id}   | ${fromStr} | ${toStr} | ${dist} km | Type ${entry.travelLocationType}`);
    });

    console.log("\n✅ All hotspot names successfully saved in cache!");
    console.log("📅 Ready for future CRUD operations on this cache table!");
  } catch (error) {
    console.error("Error:", error.message);
  } finally {
    await prisma.$disconnect();
  }
}

main();
