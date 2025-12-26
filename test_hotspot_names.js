const { PrismaClient } = require("@prisma/client");

const prisma = new PrismaClient();

async function main() {
  try {
    console.log("Fetching cache entries with hotspot names...\n");

    // Get a sample of cache entries with names
    const entries = await prisma.hotspotDistanceCache.findMany({
      select: {
        id: true,
        fromHotspotId: true,
        fromHotspotName: true,
        toHotspotId: true,
        toHotspotName: true,
        distanceKm: true,
        travelTime: true,
        travelLocationType: true,
      },
      take: 10,
      orderBy: { createdAt: "desc" },
    });

    console.log("Cache entries with hotspot names:");
    entries.forEach((entry) => {
      console.log(
        `  ${entry.fromHotspotId} (${entry.fromHotspotName}) → ${entry.toHotspotId} (${entry.toHotspotName})`
      );
      console.log(
        `    Distance: ${entry.distanceKm} km, Travel Time: ${entry.travelTime}, Type: ${entry.travelLocationType}`
      );
    });

    console.log("\n✅ Hotspot names successfully stored in cache!");
  } catch (error) {
    console.error("Error:", error);
  } finally {
    await prisma.$disconnect();
  }
}

main();
