import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();

(async () => {
  try {
    console.log("\n=== Checking Hotspot Details for Plan 2 ===");
    const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany(
      {
        where: { itinerary_plan_ID: 2 },
        select: {
          hotspot_ID: true,
          item_type: true,
          itinerary_route_ID: true,
        },
        take: 10,
      }
    );
    console.log(`Found ${hotspots.length} hotspot details:`);
    hotspots.forEach((h: any) => {
      console.log(
        `  Route ${h.itinerary_route_ID}: hotspot_ID=${h.hotspot_ID}, item_type=${h.item_type}`
      );
    });

    console.log("\n=== Checking for item_type 3 and 4 ===");
    const itemTypes = await prisma.dvi_itinerary_route_hotspot_details.groupBy(
      {
        by: ["item_type"],
        where: { itinerary_plan_ID: 2 },
        _count: {
          item_type: true,
        },
      }
    );
    console.log("Item types present:");
    itemTypes.forEach((t: any) => {
      console.log(`  item_type ${t.item_type}: ${t._count.item_type} rows`);
    });
  } catch (error) {
    console.error("Error:", error);
  } finally {
    await prisma.$disconnect();
  }
})();
