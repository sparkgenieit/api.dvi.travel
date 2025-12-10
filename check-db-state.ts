import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();

(async () => {
  try {
    console.log("\n=== Checking database state ===");

    // Check if plan 2 exists
    const plan = await prisma.dvi_itinerary_plan.findUnique({
      where: { itinerary_plan_ID: 2 },
    });

    if (!plan) {
      console.log("ERROR: Plan 2 not found in database");
      return;
    }

    console.log("Plan 2 found successfully");

    // Check routes for plan 2
    const routes = await prisma.dvi_itinerary_route.findMany({
      where: {
        itinerary_plan_ID: 2,
        deleted: 0,
      },
      select: { itinerary_route_ID: true, location_name: true },
    });

    console.log(`\nFound ${routes.length} routes for plan 2:`);
    routes.forEach((r: any) => {
      console.log(
        `  Route ${r.itinerary_route_ID}: ${r.location_name}`
      );
    });

    // Check current hotspot data
    const hotspots =
      await prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: { itinerary_plan_ID: 2 },
        select: {
          hotspot_ID: true,
          item_type: true,
          itinerary_route_ID: true,
        },
        take: 20,
      });

    console.log(
      `\nFound ${hotspots.length} existing hotspot details for plan 2`
    );
    if (hotspots.length > 0) {
      console.log("Sample hotspots:");
      hotspots.forEach((h: any) => {
        console.log(
          `  Route ${h.itinerary_route_ID}: hotspot_ID=${h.hotspot_ID}, item_type=${h.item_type}`
        );
      });
    }
  } catch (error) {
    console.error("Error:", error);
  } finally {
    await prisma.$disconnect();
  }
})();
