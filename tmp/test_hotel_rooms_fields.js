const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function testHotelRoomsFields() {
  console.log('\n=== Testing dvi_hotel_rooms field names ===\n');
  
  // Get a sample room to check field names
  const sampleRoom = await prisma.dvi_hotel_rooms.findFirst({
    take: 1
  });
  
  if (sampleRoom) {
    console.log('Available fields in dvi_hotel_rooms:');
    console.log(Object.keys(sampleRoom));
    
    // Check specific fields we need
    console.log('\nChecking specific fields:');
    console.log('room_ID:', sampleRoom.room_ID);
    console.log('room_type_id:', sampleRoom.room_type_id);
    console.log('hotel_id:', sampleRoom.hotel_id);
  } else {
    console.log('No rooms found in table');
  }
  
  await prisma.$disconnect();
}

testHotelRoomsFields().catch(console.error);
