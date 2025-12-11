const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspotTable() {
  try {
    // Try querying with different table names
    const result = await prisma.$queryRaw`
      SELECT hotspot_ID, hotspot_name, hotspot_location, hotspot_opening_time, hotspot_closing_time, hotspot_priority, hotspot_type
      FROM dvi_hotspot
      WHERE hotspot_ID IN (16, 18, 20, 23, 24, 25, 676, 669)
      ORDER BY hotspot_ID
    `;
    console.log('Hotspot data:');
    console.log(result);
  } catch (e) {
    console.log('Error:', e.message);
  } finally {
    await prisma.$disconnect();
  }
}

checkHotspotTable();
