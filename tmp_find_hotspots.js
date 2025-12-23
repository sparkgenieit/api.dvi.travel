const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
    try {
        const hotspots = await prisma.dvi_hotspot_place.findMany({
            where: {
                hotspot_location: {
                    contains: 'Mahabalipuram'
                },
                status: 1,
                deleted: 0
            }
        });

        for (const h of hotspots) {
            console.log(`ID: ${h.hotspot_ID}, Name: ${h.hotspot_name}, Location: ${h.hotspot_location}`);
        }
    } catch (e) {
        console.error(e);
    } finally {
        await prisma.$disconnect();
    }
}

main();
