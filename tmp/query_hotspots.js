const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

prisma.dvi_hotspot_place.findMany({
  where: { 
    hotspot_ID: { in: [4, 5, 294] },
    deleted: 0,
    status: 1
  },
  select: { 
    hotspot_ID: true,
    hotspot_name: true,
    hotspot_location: true
  }
}).then(h => {
  console.table(h);
  prisma.$disconnect();
}).catch(e => {
  console.error(e);
  process.exit(1);
});
