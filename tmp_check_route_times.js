const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
    try {
        const route = await prisma.dvi_itinerary_route_details.findUnique({
            where: {
                itinerary_route_ID: 207271
            }
        });

        console.log(`Start: ${route.route_start_time}, End: ${route.route_end_time}`);
    } catch (e) {
        console.error(e);
    } finally {
        await prisma.$disconnect();
    }
}

main();
