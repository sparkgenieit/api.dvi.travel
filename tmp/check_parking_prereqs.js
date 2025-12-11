const { PrismaClient } = require('@prisma/client');
const p = new PrismaClient();

Promise.all([
  p.$queryRaw`SELECT COUNT(*) as count FROM dvi_accounts_itinerary_vehicle_details WHERE itinerary_plan_ID = 2`,
  p.$queryRaw`SELECT COUNT(*) as count FROM dvi_accounts_itinerary_vehicle_details WHERE itinerary_plan_ID = 5`,
  p.$queryRaw`SELECT COUNT(*) as count FROM dvi_hotspot_vehicle_parking_charges WHERE deleted = 0 AND status = 1 LIMIT 5`,
]).then(([p2accounts, p5accounts, parkingMaster]) => {
  console.log('Plan 2 accounts vehicle details:', p2accounts[0].count);
  console.log('Plan 5 accounts vehicle details:', p5accounts[0].count);
  console.log('Parking charges master records:', parkingMaster[0].count);
}).finally(() => p.$disconnect());
