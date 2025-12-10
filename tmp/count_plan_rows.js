require('dotenv').config();
const { PrismaClient } = require('@prisma/client');
const p = new PrismaClient();
(async () => {
  const sql = `SELECT itinerary_plan_ID, COUNT(*) AS total,
    SUM(item_type=3) AS travel_rows,
    SUM(item_type=4) AS hotspot_rows,
    SUM(item_type=5) AS hotel_travel_rows,
    SUM(item_type=6) AS hotel_return_rows,
    SUM(item_type=7) AS final_return_rows
    FROM dvi_itinerary_route_hotspot_details
    WHERE itinerary_plan_ID IN (2,4)
    GROUP BY itinerary_plan_ID`;
  const rows = await p.$queryRawUnsafe(sql);
  const sanitized = rows.map((r) => {
    const out = {};
    for (const [k, v] of Object.entries(r)) {
      out[k] = typeof v === 'bigint' ? Number(v) : v;
    }
    return out;
  });
  console.log(JSON.stringify(sanitized, null, 2));
  await p.$disconnect();
})();
