import { PrismaClient } from "@prisma/client";
import { HotspotEngineService } from "./src/modules/itineraries/engines/hotspot-engine.service";
import { TimelineBuilder } from "./src/modules/itineraries/engines/helpers/timeline.builder";

const prisma = new PrismaClient();

(async () => {
  try {
    console.log("\n=== Manually triggering hotspot rebuild for Plan 2 ===");

    // Run within a transaction
    await prisma.$transaction(async (tx: any) => {
      const hotspotEngine = new HotspotEngineService();
      const timelineBuilder = new TimelineBuilder();

      // Get the plan
      const plan = await tx.dvi_itinerary_plan.findUnique({
        where: { itinerary_plan_ID: 2 },
      });

      if (!plan) {
        throw new Error("Plan 2 not found");
      }

      console.log("Found plan:", plan.itinerary_plan_ID);

      // Get routes
      const routes = await tx.dvi_itinerary_route.findMany({
        where: {
          itinerary_plan_ID: 2,
          deleted: 0,
        },
      });

      console.log(`Found ${routes.length} routes`);

      // Rebuild hotspots
      await hotspotEngine.rebuildRouteHotspots(tx, 2);

      console.log("Hotspot rebuild complete!");
    });

    console.log("\n=== Verifying hotspot insertion ===");
    const hotspots =
      await prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: { itinerary_plan_ID: 2 },
        select: {
          hotspot_ID: true,
          item_type: true,
          itinerary_route_ID: true,
        },
        take: 15,
      });

    console.log(`Found ${hotspots.length} hotspot details:`);
    hotspots.forEach((h: any) => {
      console.log(
        `  Route ${h.itinerary_route_ID}: hotspot_ID=${h.hotspot_ID}, item_type=${h.item_type}`
      );
    });

    console.log("\n=== Item Type Summary ===");
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
