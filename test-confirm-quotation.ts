import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:4006/api/v1';

async function testConfirmQuotation() {
  const planId = 12; // The itinerary plan ID
  const quoteId = 'DVI20260110';

  console.log('\n🧪 TESTING CONFIRM QUOTATION ENDPOINT\n');
  console.log('════════════════════════════════════════════\n');
  console.log(`📍 Plan ID: ${planId}`);
  console.log(`📍 Quote ID: ${quoteId}`);
  console.log(`🔗 Endpoint: POST ${API_BASE_URL}/itineraries/confirm-quotation\n`);

  const confirmationPayload = {
    itinerary_plan_ID: planId,
    agent: 126,
    primary_guest_salutation: 'Mr',
    primary_guest_name: 'test',
    primary_guest_contact_no: '3453453',
    primary_guest_age: '',
    primary_guest_alternative_contact_no: '',
    primary_guest_email_id: '',
    adult_name: [],
    adult_age: [],
    child_name: [],
    child_age: [],
    infant_name: [],
    infant_age: [],
    arrival_date_time: '26-04-2026 12:00 AM',
    arrival_place: 'Chennai International Airport',
    arrival_flight_details: '',
    departure_date_time: '30-04-2026 12:00 AM',
    departure_place: 'Thanjavur Airport',
    departure_flight_details: '',
    price_confirmation_type: 'old',
    hotel_group_type: '1',
    hotel_bookings: [
      {
        provider: 'tbo',
        routeId: 140,
        hotelCode: '6102544',
        bookingCode: '6102544!TB!2!TB!cc4b2cde-fb42-11f0-914b-4a620032403f!TB!N!TB!AFF!',
        roomType: 'Deluxe Single Room,1 Double Bed,NonSmoking',
        checkInDate: '2026-04-26',
        checkOutDate: '2026-04-27',
        numberOfRooms: 1,
        guestNationality: 'IN',
        netAmount: 2907,
        passengers: [
          {
            title: 'Mr',
            firstName: 'test',
            lastName: 'test',
            paxType: 1,
            leadPassenger: true,
            age: 0,
            phoneNo: '3453453',
          },
        ],
      },
      {
        provider: 'tbo',
        routeId: 141,
        hotelCode: '1699253',
        bookingCode: '1699253!TB!2!TB!cc4b1b08-fb42-11f0-914b-4a620032403f!TB!N!TB!AFF!',
        roomType: 'Premier Double or Twin Room,1 King Bed,NonSmoking',
        checkInDate: '2026-04-27',
        checkOutDate: '2026-04-28',
        numberOfRooms: 1,
        guestNationality: 'IN',
        netAmount: 2575,
        passengers: [
          {
            title: 'Mr',
            firstName: 'test',
            lastName: 'test',
            paxType: 1,
            leadPassenger: true,
            age: 0,
            phoneNo: '3453453',
          },
        ],
      },
      {
        provider: 'HOBSE',
        routeId: 139,
        hotelCode: '40fec763d4c6e09e', // ✅ HOBSE UUID format
        bookingCode: '{"provider":"HOBSE","hotelId":"40fec763d4c6e09e","cityId":"19","checkInDate":"2026-04-25","checkOutDate":"2026-04-26","priceOwnerType":"2","partnerId":"4feaadb2c2277ce0","partnerTypeId":"4","tariffMode":"B2B","roomCode":"081e192a15620830","occupancyTypeCode":"a11d1e1a72a0a672","ratePlanCode":"8d4eb82eb47e4241"}',
        roomType: 'Suite',
        checkInDate: '2026-04-25',
        checkOutDate: '2026-04-26',
        numberOfRooms: 1,
        guestNationality: 'IN',
        netAmount: 3000,
        passengers: [
          {
            title: 'Mr',
            firstName: 'test',
            lastName: 'test',
            paxType: 1,
            leadPassenger: true,
            age: 0,
            phoneNo: '3453453',
          },
        ],
      },
    ],
    primaryGuest: {
      salutation: 'Mr',
      name: 'test',
      phone: '3453453',
      email: '',
    },
    endUserIp: '139.5.249.150',
  };

  try {
    console.log('📤 SENDING REQUEST...\n');
    console.log(`Hotel Bookings: ${confirmationPayload.hotel_bookings.length}`);
    confirmationPayload.hotel_bookings.forEach((h, i) => {
      console.log(`   ${i + 1}. ${h.provider} - Route ${h.routeId} (${h.hotelCode})`);
    });
    console.log();

    const response = await axios.post(
      `${API_BASE_URL}/itineraries/confirm-quotation`,
      confirmationPayload,
      { timeout: 60000 }
    );

    console.log('✅ RESPONSE RECEIVED\n');

    // Check confirmation status
    if (response.data.confirmed_itinerary_plan_ID) {
      console.log(`📋 Confirmed Plan ID: ${response.data.confirmed_itinerary_plan_ID}`);
      console.log(`✅ Quotation Status: CONFIRMED`);
    }

    // Check booking results
    if (response.data.bookingResults) {
      const results = response.data.bookingResults;
      console.log(`\n🏨 BOOKING RESULTS:`);

      if (Array.isArray(results)) {
        results.forEach((result, idx) => {
          const status = result.status === 'success' ? '✅' : '❌';
          console.log(
            `   ${status} ${result.provider} (Route ${result.routeId}): ${result.status}`
          );
          if (result.error) {
            console.log(`      Error: ${result.error}`);
          }
          if (result.bookingId) {
            console.log(`      Booking ID: ${result.bookingId}`);
          }
        });
      } else {
        console.log(`   Raw Results:`, results);
      }
    } else {
      console.log(`\n⚠️  No booking results in response`);
    }

    // Summary
    console.log(`\n📊 RESPONSE SUMMARY:`);
    console.log(`   - Quote ID: ${response.data.quoteId}`);
    console.log(`   - Plan ID: ${response.data.confirmed_itinerary_plan_ID || 'N/A'}`);
    console.log(`   - Status: ${response.data.status || 'Success'}`);

  } catch (error) {
    const msg = error instanceof Error ? error.message : String(error);
    console.error(`\n❌ ERROR: ${msg}`);
    if (error.response?.data) {
      console.error('\nResponse Data:', JSON.stringify(error.response.data, null, 2));
    }
    if (error.response?.status) {
      console.error(`Status Code: ${error.response.status}`);
    }
  }

  console.log('\n════════════════════════════════════════════\n');
}

testConfirmQuotation().catch((err) => {
  console.error('Fatal error:', err);
  process.exit(1);
});
