import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:4006/api/v1';

async function testHobseBookingOnly() {
  const planId = 12;
  
  // Use dates that are further in the future
  const today = new Date();
  const checkInDate = new Date(today);
  checkInDate.setDate(checkInDate.getDate() + 30);
  const checkInStr = checkInDate.toISOString().split('T')[0]; // YYYY-MM-DD
  
  const checkOutDate = new Date(checkInDate);
  checkOutDate.setDate(checkOutDate.getDate() + 1);
  const checkOutStr = checkOutDate.toISOString().split('T')[0];

  console.log('\n🧪 TESTING HOBSE BOOKING ONLY\n');
  console.log('════════════════════════════════════════════\n');

  const confirmationPayload = {
    itinerary_plan_ID: planId,
    agent: 126,
    primary_guest_salutation: 'Mr',
    primary_guest_name: 'Test User',
    primary_guest_contact_no: '9876543210',
    primary_guest_age: '35',
    primary_guest_alternative_contact_no: '',
    primary_guest_email_id: 'test@example.com',
    adult_name: [],
    adult_age: [],
    child_name: [],
    child_age: [],
    infant_name: [],
    infant_age: [],
    arrival_date_time: '26-04-2026 12:00 AM',
    arrival_place: 'Chennai International Airport',
    arrival_flight_details: 'AI123',
    departure_date_time: '30-04-2026 12:00 AM',
    departure_place: 'Thanjavur Airport',
    departure_flight_details: 'AI124',
    price_confirmation_type: 'old',
    hotel_group_type: '1',
    hotel_bookings: [
      {
        provider: 'HOBSE',
        routeId: 139,
        hotelCode: '40fec763d4c6e09e',
        bookingCode: '{"provider":"HOBSE","hotelId":"40fec763d4c6e09e","cityId":"19"}',
        roomType: 'Suite',
        checkInDate: checkInStr,
        checkOutDate: checkOutStr,
        numberOfRooms: 1,
        guestNationality: 'IN',
        netAmount: 3000,
        passengers: [
          {
            title: 'Mr',
            firstName: 'Test',
            lastName: 'User',
            paxType: 1,
            leadPassenger: true,
            age: 35,
            phoneNo: '9876543210',
          },
        ],
      },
    ],
    primaryGuest: {
      salutation: 'Mr',
      name: 'Test User',
      phone: '9876543210',
      email: 'test@example.com',
    },
    endUserIp: '127.0.0.1',
  };

  try {
    console.log('📤 SENDING HOBSE BOOKING REQUEST...\n');
    console.log(`   Check-in: ${checkInStr}`);
    console.log(`   Check-out: ${checkOutStr}\n`);

    const response = await axios.post(
      `${API_BASE_URL}/itineraries/confirm-quotation`,
      confirmationPayload,
      { timeout: 90000 }
    );

    console.log('✅ RESPONSE RECEIVED\n');

    // Check confirmation status
    if (response.data.confirmed_itinerary_plan_ID) {
      console.log(`✅ Confirmed Plan ID: ${response.data.confirmed_itinerary_plan_ID}`);
    }

    // Check booking results
    if (response.data.bookingResults && Array.isArray(response.data.bookingResults)) {
      const hobseResult = response.data.bookingResults[0];
      console.log(`\n🏨 HOBSE BOOKING RESULT:`);
      console.log(`   Status: ${hobseResult.status}`);
      console.log(`   Provider: ${hobseResult.provider}`);
      console.log(`   Route ID: ${hobseResult.routeId}`);
      console.log(`   Hotel ID: ${hobseResult.hotelId}`);
      
      if (hobseResult.status === 'success') {
        console.log(`   ✅ Channel Booking ID: ${hobseResult.channelBookingId}`);
        console.log(`   ✅ DB ID: ${hobseResult.dbId}`);
      } else {
        console.log(`   ❌ Error: ${hobseResult.error}`);
      }
    }

  } catch (error) {
    const msg = error instanceof Error ? error.message : String(error);
    console.error(`\n❌ ERROR: ${msg}`);
    if (error.response?.data) {
      console.error('\nResponse Data:');
      console.error(JSON.stringify(error.response.data, null, 2));
    }
  }

  console.log('\n════════════════════════════════════════════\n');
}

testHobseBookingOnly().catch((err) => {
  console.error('Fatal error:', err);
  process.exit(1);
});
