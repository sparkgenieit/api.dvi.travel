require('dotenv').config();
const { PrismaClient } = require('@prisma/client');
const p = new PrismaClient();
(async () => {
  const rows = await p.$queryRawUnsafe(`
    SELECT itinerary_route_ID,
           SUM(item_type=3) AS travel_rows,
           SUM(item_type=4) AS hotspot_rows,
           COUNT(*) AS total
    FROM dvi_itinerary_route_hotspot_details
    WHERE itinerary_plan_ID=4
    GROUP BY itinerary_route_ID
    ORDER BY itinerary_route_ID;
  `);
  console.log(rows);
  await p.$disconnect();
})();
