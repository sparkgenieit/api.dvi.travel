const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

function dayCol(date) {
  const dayNum = date.getDate();
  return `d${dayNum}`;
}

function monthName(date) {
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  return months[date.getMonth()];
}

(async () => {
  const hotelId = 522;
  const testDate = new Date('2025-01-25'); // Route date from plan 5
  
  const dc = dayCol(testDate);
  const y = String(testDate.getFullYear());
  const m = monthName(testDate);
  
  console.log(`Testing hotel ${hotelId} on ${testDate.toISOString()}`);
  console.log(`  Looking for year='${y}', month='${m}', column='${dc}'`);
  
  const rows = await prisma.dvi_hotel_room_price_book.findMany({
    where: { hotel_id: hotelId, year: y, month: m },
    take: 3
  });
  
  console.log(`\nFound ${rows.length} rows in price book`);
  if (rows.length > 0) {
    console.log('\nSample row:');
    console.log('room_id:', rows[0].room_id);
    console.log(`${dc}:`, rows[0][dc]);
    console.log('Full row keys:', Object.keys(rows[0]));
  }
  
  await prisma.$disconnect();
})();
