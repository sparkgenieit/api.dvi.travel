import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:4006/api/v1';

async function testBothEndpoints() {
  const quoteId = 'DVI2026011';

  console.log('\n📊 TESTING BOTH ENDPOINTS\n');
  console.log('════════════════════════════════════════════\n');

  // Test 1: hotel_details
  console.log('1️⃣ TESTING: /itineraries/hotel_details/' + quoteId);
  console.log('─────────────────────────────────────────────\n');
  try {
    const response1 = await axios.get(
      `${API_BASE_URL}/itineraries/hotel_details/${quoteId}`,
      { timeout: 30000 }
    );

    console.log(`✅ Response received`);
    console.log(`   - Hotel Tabs: ${response1.data.hotelTabs?.length || 0}`);
    console.log(`   - Total Hotels: ${response1.data.hotels?.length || 0}`);
    
    const hasRealHotels = response1.data.hotels?.some((h: any) => h.hotelName !== 'No Hotels Available');
    console.log(`   - Has Real Hotels: ${hasRealHotels ? 'YES' : 'NO'}`);
    
    if (hasRealHotels) {
      const sample = response1.data.hotels?.find((h: any) => h.hotelName !== 'No Hotels Available');
      if (sample) {
        console.log(`   - Sample Hotel: "${sample.hotelName}"`);
        console.log(`   - Price: ${sample.totalHotelCost}`);
      }
    }
  } catch (error: any) {
    console.error(`❌ Error: ${error.message.substring(0, 100)}`);
  }

  console.log('\n\n2️⃣ TESTING: /itineraries/hotel_room_details/' + quoteId);
  console.log('─────────────────────────────────────────────\n');
  try {
    const response2 = await axios.get(
      `${API_BASE_URL}/itineraries/hotel_room_details/${quoteId}`,
      { timeout: 30000 }
    );

    console.log(`✅ Response received`);
    console.log(`   - Rooms: ${response2.data.rooms?.length || 0}`);
    
    if (response2.data.rooms && response2.data.rooms.length > 0) {
      const sample = response2.data.rooms[0];
      console.log(`   - Sample Room:`);
      console.log(`     - Hotel Name: "${sample.hotelName || 'undefined'}"`);
      console.log(`     - Destination: "${sample.destination || 'undefined'}"`);
      console.log(`     - Category: "${sample.category || 'undefined'}"`);
      console.log(`     - Price: ${sample.totalRoomCost || 0}`);
    }
  } catch (error: any) {
    console.error(`❌ Error: ${error.message.substring(0, 100)}`);
  }

  console.log('\n════════════════════════════════════════════\n');

  console.log('📌 COMPARISON:\n');
  console.log('hotel_details:');
  console.log('  - Source: TBO API (real-time)');
  console.log('  - Purpose: Generate/preview packages');
  console.log();
  console.log('hotel_room_details:');
  console.log('  - Source: Database (dvi_itinerary_plan_hotel_details)');
  console.log('  - Purpose: View saved selections');
  console.log();
}

testBothEndpoints().catch(err => {
  console.error('Fatal error:', err.message);
  process.exit(1);
});
