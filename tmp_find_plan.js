const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
    try {
        const plan = await prisma.dvi_itinerary_plan_details.findFirst({
            where: {
                itinerary_quote_ID: 'DVI20251214'
            }
        });

        if (!plan) {
            console.log("Plan not found");
            return;
        }

        console.log(`Plan ID: ${plan.itinerary_plan_ID}`);

        const routes = await prisma.dvi_itinerary_route_details.findMany({
            where: {
                itinerary_plan_ID: plan.itinerary_plan_ID,
                deleted: 0
            },
            orderBy: {
                itinerary_route_ID: 'asc'
            }
        });

        for (const route of routes) {
            console.log(`Route ID: ${route.itinerary_route_ID}, Date: ${route.itinerary_route_date}, Location: ${route.location_name}, Next: ${route.next_visiting_location}`);
        }
    } catch (e) {
        console.error(e);
    } finally {
        await prisma.$disconnect();
    }
}

main();
