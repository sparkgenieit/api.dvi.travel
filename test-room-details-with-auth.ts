import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:4006/api/v1';
const TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJhZ2VudElkIjowLCJzdGFmZklkIjowLCJndWlkZUlkIjowLCJpYXQiOjE3NjcyNDkzMjAsImV4cCI6MTc2Nzg1NDEyMH0.0-AJW4SWm1NFTzJFjEAe69-byHfu0X1sFmGwP_fTOmw';

async function testHotelRoomDetails() {
  const quoteId = 'DVI2026011';

  console.log('\n🛏️ TESTING: /itineraries/hotel_room_details/' + quoteId + '\n');
  console.log('════════════════════════════════════════════\n');

  try {
    const response = await axios.get(
      `${API_BASE_URL}/itineraries/hotel_room_details/${quoteId}`,
      { 
        timeout: 30000,
        headers: {
          'Authorization': `Bearer ${TOKEN}`,
        }
      }
    );

    console.log(`✅ Response Status: ${response.status}\n`);
    console.log(`📊 Response Summary:`);
    console.log(`   - Plan ID: ${response.data.planId}`);
    console.log(`   - Quote ID: ${response.data.quoteId}`);
    console.log(`   - Total Rooms: ${response.data.rooms?.length || 0}\n`);

    if (response.data.rooms && response.data.rooms.length > 0) {
      console.log(`🏨 Sample Rooms (first 3):\n`);
      response.data.rooms.slice(0, 3).forEach((room: any, idx: number) => {
        console.log(`${idx + 1}. Hotel: "${room.hotelName || 'undefined'}"`);
        console.log(`   Destination: ${room.destination}`);
        console.log(`   Category: ${room.category}`);
        console.log(`   Room Type: ${room.roomType}`);
        console.log(`   Meal Plan: ${room.mealPlan}`);
        console.log(`   Price: ${room.totalRoomCost}`);
        console.log();
      });

      // Check for "No Hotels Available"
      const noHotelsCount = response.data.rooms.filter((r: any) => r.hotelName === 'No Hotels Available').length;
      const realHotelsCount = response.data.rooms.length - noHotelsCount;
      
      console.log(`📈 Summary:`);
      console.log(`   - Real Hotels: ${realHotelsCount}`);
      console.log(`   - "No Hotels Available": ${noHotelsCount}`);
    } else {
      console.log(`⚠️  No rooms found`);
    }

  } catch (error: any) {
    console.error(`❌ Error: ${error.message}`);
    if (error.response?.data) {
      console.error(`Response: ${JSON.stringify(error.response.data)}`);
    }
  }

  console.log('\n════════════════════════════════════════════\n');
}

testHotelRoomDetails().catch(err => {
  console.error('Fatal error:', err.message);
  process.exit(1);
});
