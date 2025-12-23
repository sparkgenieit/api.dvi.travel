const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
    try {
        const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
            where: {
                itinerary_route_ID: 207271,
                deleted: 0,
                item_type: 4
            }
        });

        for (const h of hotspots) {
            console.log(`ID: ${h.hotspot_ID}, Order: ${h.hotspot_order}, Manual: ${h.hotspot_plan_own_way}`);
        }
    } catch (e) {
        console.error(e);
    } finally {
        await prisma.$disconnect();
    }
}

main();
