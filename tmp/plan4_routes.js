require('dotenv').config();
const { PrismaClient } = require('@prisma/client');
const p = new PrismaClient();
(async () => {
  const routes = await p.$queryRawUnsafe(`
    SELECT itinerary_route_ID, location_name, next_visiting_location
    FROM dvi_itinerary_route_details
    WHERE itinerary_plan_ID=4 AND deleted=0 AND status=1
    ORDER BY itinerary_route_ID`);
  console.log(routes);
  await p.$disconnect();
})();
