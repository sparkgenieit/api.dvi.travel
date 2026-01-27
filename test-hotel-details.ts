import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:4006/api/v1';

async function testHotelDetails() {
  const quoteId = 'DVI20260110';

  console.log('\n🧪 TESTING HOTEL DETAILS ENDPOINT\n');
  console.log('════════════════════════════════════════════\n');
  console.log(`📍 Quote ID: ${quoteId}`);
  console.log(`🔗 Endpoint: GET ${API_BASE_URL}/itineraries/hotel_details/${quoteId}\n`);

  try {
    const response = await axios.get(
      `${API_BASE_URL}/itineraries/hotel_details/${quoteId}`,
      { timeout: 60000 }
    );

    console.log('✅ RESPONSE RECEIVED\n');
    console.log(`📊 Response Summary:`);
    console.log(`   - Plan ID: ${response.data.planId}`);
    console.log(`   - Hotel Tabs: ${response.data.hotelTabs?.length || 0}`);
    console.log(`   - Total Hotels: ${response.data.hotels?.length || 0}`);
    console.log(`   - Total Room Count: ${response.data.totalRoomCount}`);

    // Check for "No Hotels Available"
    const noHotelsCount = response.data.hotels?.filter(h => h.hotelName === 'No Hotels Available').length || 0;
    console.log(`   - "No Hotels Available": ${noHotelsCount}`);

    // Check for real hotels
    const realHotels = response.data.hotels?.filter(h => h.hotelName !== 'No Hotels Available') || [];
    console.log(`   - Real Hotels: ${realHotels.length}`);

    if (realHotels.length > 0) {
      console.log(`\n🏨 Sample Real Hotels:`);
      realHotels.slice(0, 3).forEach((hotel, idx) => {
        console.log(`   ${idx + 1}. ${hotel.hotelName}`);
        console.log(`      - Destination: ${hotel.destination}`);
        console.log(`      - Category: ${hotel.category || 'N/A'}`);
        console.log(`      - Cost: ${hotel.totalHotelCost}`);
      });
    } else {
      console.log(`\n⚠️  WARNING: Still showing "No Hotels Available"!`);
    }

    // Show first hotel tab prices
    console.log(`\n📈 Hotel Tab Totals:`);
    response.data.hotelTabs?.forEach(tab => {
      console.log(`   - ${tab.label}: ${tab.totalAmount}`);
    });

  } catch (error) {
    const msg = error instanceof Error ? error.message : String(error);
    console.error(`\n❌ ERROR: ${msg}`);
    if (error.response?.data) {
      console.error('Response:', error.response.data);
    }
  }

  console.log('\n════════════════════════════════════════════\n');
}

testHotelDetails().catch(err => {
  console.error('Fatal error:', err);
  process.exit(1);
});
