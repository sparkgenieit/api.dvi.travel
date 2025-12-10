require('dotenv').config();
const { PrismaClient } = require('@prisma/client');
const p = new PrismaClient();
(async () => {
  const rows = await p.$queryRawUnsafe(`SELECT COUNT(*) c FROM dvi_hotspot_timing WHERE deleted=0 AND status=1`);
  console.log(rows);
  await p.$disconnect();
})();
