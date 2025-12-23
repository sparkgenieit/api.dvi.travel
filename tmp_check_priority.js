const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
    try {
        const names = ['Kapaleeshwarar Temple', 'Marina Beach', 'Parthasarathy Temple'];
        const hotspots = await prisma.dvi_hotspot_place.findMany({
            where: {
                hotspot_name: {
                    in: names
                },
                deleted: 0
            },
            select: {
                hotspot_ID: true,
                hotspot_name: true,
                hotspot_priority: true,
                hotspot_location: true
            }
        });

        console.log(JSON.stringify(hotspots, null, 2));
    } catch (e) {
        console.error(e);
    } finally {
        await prisma.$disconnect();
    }
}

main();
