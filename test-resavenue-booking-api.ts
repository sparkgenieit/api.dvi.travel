/**
 * ResAvenue Hotel Booking & Cancellation API Test
 * 
 * This test file demonstrates calling ResAvenue confirm and cancel APIs
 * through NestJS endpoints.
 * 
 * Test Flow:
 * 1. Search hotels using ResAvenue provider
 * 2. Confirm booking for a hotel
 * 3. Cancel the booking
 * 
 * Prerequisites:
 * - Backend server must be running (npm run start:dev)
 * - ResAvenue credentials configured in .env
 * - Database with dvi_hotel having resavenue_hotel_code entries
 */

import axios from 'axios';
import * as dotenv from 'dotenv';

dotenv.config();

// Configuration
const BASE_URL = process.env.API_BASE_URL || 'http://localhost:4006';
const AUTH_TOKEN = process.env.TEST_AUTH_TOKEN || ''; // Add JWT token if auth is required

// Colors for console output
const colors = {
  reset: '\x1b[0m',
  green: '\x1b[32m',
  red: '\x1b[31m',
  yellow: '\x1b[33m',
  cyan: '\x1b[36m',
  blue: '\x1b[34m',
  magenta: '\x1b[35m',
};

function log(message: string, color: string = colors.reset) {
  console.log(`${color}${message}${colors.reset}`);
}

function logSection(title: string) {
  log('\n' + '═'.repeat(60), colors.yellow);
  log(title, colors.yellow);
  log('═'.repeat(60), colors.yellow);
}

function logSubSection(title: string) {
  log('\n' + '─'.repeat(60), colors.cyan);
  log(title, colors.cyan);
  log('─'.repeat(60), colors.cyan);
}

// Axios instance with auth header if needed
const api = axios.create({
  baseURL: BASE_URL,
  headers: AUTH_TOKEN ? { Authorization: `Bearer ${AUTH_TOKEN}` } : {},
  timeout: 60000,
});

/**
 * Test 1: Search Hotels with ResAvenue Provider
 */
async function searchResAvenueHotels() {
  logSubSection('TEST 1: Search ResAvenue Hotels');

  try {
    const searchCriteria = {
      cityCode: 1, // Chennai (adjust based on your database)
      checkInDate: getDateString(30), // 30 days from now
      checkOutDate: getDateString(32), // 32 days from now (2 night stay)
      rooms: [
        {
          adults: 2,
          children: 0,
        },
      ],
      providers: ['resavenue'], // Only search ResAvenue
      nationality: 'IN',
    };

    log(`\n📤 Searching ResAvenue hotels in city ${searchCriteria.cityCode}`, colors.cyan);
    log(`Check-in: ${searchCriteria.checkInDate}`, colors.blue);
    log(`Check-out: ${searchCriteria.checkOutDate}`, colors.blue);

    const response = await api.post('/api/v1/hotels/search', searchCriteria);

    log(`\n✅ Search successful`, colors.green);
    log(`Total hotels found: ${response.data.length}`, colors.green);

    if (response.data.length > 0) {
      log('\nSample Hotels:', colors.cyan);
      response.data.slice(0, 5).forEach((hotel: any, index: number) => {
        log(`  ${index + 1}. ${hotel.hotelName} (${hotel.hotelCode})`, colors.blue);
        log(`     Provider: ${hotel.provider}`, colors.blue);
        if (hotel.rooms && hotel.rooms.length > 0) {
          log(`     Rooms available: ${hotel.rooms.length}`, colors.blue);
          log(`     Price: ${hotel.rooms[0].priceInfo?.currency} ${hotel.rooms[0].priceInfo?.totalPrice}`, colors.green);
        }
      });
    }

    return response.data;
  } catch (error: any) {
    log(`\n❌ Search failed: ${error.message}`, colors.red);
    if (error.response) {
      log(`Status: ${error.response.status}`, colors.red);
      log(`Data: ${JSON.stringify(error.response.data, null, 2)}`, colors.red);
    }
    return [];
  }
}

/**
 * Test 2: Direct ResAvenue Provider Booking Test
 * Tests the provider's confirmBooking method directly
 */
async function testResAvenueProviderBooking() {
  logSubSection('TEST 2: Direct ResAvenue Provider Booking');

  try {
    const bookingData = {
      hotelCode: 'RSAV_TEST_HOTEL_001', // Replace with actual ResAvenue hotel code
      checkInDate: getDateString(30),
      checkOutDate: getDateString(32),
      rooms: [
        {
          roomCode: 'INV001-RATE001', // Format: InvCode-RateCode
          guestCount: 2,
          quantity: 1,
        },
      ],
      guests: [
        {
          firstName: 'John',
          lastName: 'Doe',
          email: 'john.doe@test.com',
          phone: '+919876543210',
        },
        {
          firstName: 'Jane',
          lastName: 'Doe',
          email: 'jane.doe@test.com',
          phone: '+919876543211',
        },
      ],
    };

    log(`\n📤 Booking ResAvenue hotel: ${bookingData.hotelCode}`, colors.cyan);
    log(`Guests: ${bookingData.guests.length}`, colors.blue);
    log(`Rooms: ${bookingData.rooms.length}`, colors.blue);
    log(`Request: ${JSON.stringify(bookingData, null, 2)}`, colors.magenta);

    // Note: This would need a dedicated test endpoint in your backend
    // For now, this shows the expected request structure
    log(`\n⚠️  Direct provider test requires backend test endpoint`, colors.yellow);
    log(`Expected endpoint: POST /api/v1/hotels/resavenue/test-booking`, colors.yellow);

    return null;
  } catch (error: any) {
    log(`\n❌ Booking test failed: ${error.message}`, colors.red);
    return null;
  }
}

/**
 * Test 3: Book Hotel through Itinerary Confirmation
 * This uses the actual production endpoint
 */
async function confirmItineraryWithResAvenue() {
  logSubSection('TEST 3: Confirm Itinerary with ResAvenue Hotel');

  try {
    // First, create a test itinerary (this would need existing itinerary ID)
    const itineraryPlanId = process.env.TEST_ITINERARY_PLAN_ID || 1;

    const confirmationData = {
      itineraryPlanId: parseInt(itineraryPlanId as string),
      customerName: 'Test Customer',
      customerEmail: 'test@example.com',
      customerPhone: '+919876543210',
      hotels: [
        {
          routeId: 1, // Replace with actual route ID
          hotelCode: 'RSAV_TEST_HOTEL_001', // Replace with actual ResAvenue hotel code
          provider: 'resavenue',
          bookingCode: 'INV001-RATE001', // ResAvenue InvCode-RateCode
          roomType: 'Deluxe Room',
          checkInDate: getDateString(30),
          checkOutDate: getDateString(32),
          numberOfRooms: 1,
          guestNationality: 'IN',
          netAmount: 5000,
          guests: [
            {
              firstName: 'John',
              lastName: 'Doe',
              email: 'john.doe@test.com',
              phone: '+919876543210',
            },
            {
              firstName: 'Jane',
              lastName: 'Doe',
              email: 'jane.doe@test.com',
              phone: '+919876543211',
            },
          ],
        },
      ],
    };

    log(`\n📤 Confirming itinerary with ResAvenue hotel`, colors.cyan);
    log(`Itinerary Plan ID: ${confirmationData.itineraryPlanId}`, colors.blue);
    log(`Request: ${JSON.stringify(confirmationData, null, 2)}`, colors.magenta);

    const response = await api.post('/api/v1/itineraries/confirm-quotation', confirmationData);

    log(`\n✅ Itinerary confirmed successfully`, colors.green);
    log(`Confirmed Plan ID: ${response.data.confirmedPlanId}`, colors.green);
    log(`Response: ${JSON.stringify(response.data, null, 2)}`, colors.green);

    return response.data;
  } catch (error: any) {
    log(`\n❌ Itinerary confirmation failed: ${error.message}`, colors.red);
    if (error.response) {
      log(`Status: ${error.response.status}`, colors.red);
      log(`Data: ${JSON.stringify(error.response.data, null, 2)}`, colors.red);
    }
    return null;
  }
}

/**
 * Test 4: Cancel Itinerary with ResAvenue Bookings
 */
async function cancelItineraryWithResAvenue() {
  logSubSection('TEST 4: Cancel Itinerary with ResAvenue Hotels');

  try {
    const itineraryPlanId = process.env.TEST_ITINERARY_PLAN_ID || 1;
    const reason = 'Testing ResAvenue cancellation API';

    const cancellationData = {
      itineraryPlanId: parseInt(itineraryPlanId as string),
      reason: reason,
    };

    log(`\n📤 Cancelling itinerary: ${cancellationData.itineraryPlanId}`, colors.cyan);
    log(`Reason: ${cancellationData.reason}`, colors.blue);

    const response = await api.post('/api/v1/itineraries/cancel', cancellationData);

    log(`\n✅ Itinerary cancelled successfully`, colors.green);
    log(`Response: ${JSON.stringify(response.data, null, 2)}`, colors.green);

    // Show cancellation details
    if (response.data.hotelCancellations && response.data.hotelCancellations.length > 0) {
      log(`\n📋 Hotel Cancellations:`, colors.cyan);
      response.data.hotelCancellations.forEach((cancel: any) => {
        log(`  - Booking Ref: ${cancel.resavenueBookingRef}`, colors.blue);
        log(`    Status: ${cancel.status}`, colors.blue);
        log(`    Refund Amount: ${cancel.refundAmount || 0}`, colors.green);
        log(`    Charges: ${cancel.charges || 0}`, colors.yellow);
      });
    }

    return response.data;
  } catch (error: any) {
    log(`\n❌ Cancellation failed: ${error.message}`, colors.red);
    if (error.response) {
      log(`Status: ${error.response.status}`, colors.red);
      log(`Data: ${JSON.stringify(error.response.data, null, 2)}`, colors.red);
    }
    return null;
  }
}

/**
 * Test 5: Direct ResAvenue Cancellation Test
 */
async function testResAvenueProviderCancellation() {
  logSubSection('TEST 5: Direct ResAvenue Provider Cancellation');

  try {
    const bookingRef = 'DVI-1234567890'; // Replace with actual booking reference
    const reason = 'Testing cancellation';

    log(`\n📤 Cancelling ResAvenue booking: ${bookingRef}`, colors.cyan);
    log(`Reason: ${reason}`, colors.blue);

    // Note: This would need a dedicated test endpoint in your backend
    log(`\n⚠️  Direct provider test requires backend test endpoint`, colors.yellow);
    log(`Expected endpoint: POST /api/v1/hotels/resavenue/test-cancellation`, colors.yellow);
    log(`Expected body: { "bookingRef": "${bookingRef}", "reason": "${reason}" }`, colors.yellow);

    return null;
  } catch (error: any) {
    log(`\n❌ Cancellation test failed: ${error.message}`, colors.red);
    return null;
  }
}

/**
 * Utility: Get date string for X days from now
 */
function getDateString(daysFromNow: number): string {
  const date = new Date();
  date.setDate(date.getDate() + daysFromNow);
  return date.toISOString().split('T')[0];
}

/**
 * Main test runner
 */
async function runTests() {
  logSection('🚀 RESAVENUE BOOKING & CANCELLATION API TESTS');

  log('\n🔧 Configuration:', colors.cyan);
  log(`Backend URL: ${BASE_URL}`, colors.blue);
  log(`Auth Token: ${AUTH_TOKEN ? '✅ Set' : '❌ Not set (may need for protected endpoints)'}`, colors.blue);

  let testsPassed = 0;
  let testsFailed = 0;

  // Test 1: Search Hotels
  try {
    const hotels = await searchResAvenueHotels();
    if (hotels && hotels.length > 0) {
      testsPassed++;
      log('\n✅ TEST 1 PASSED - Hotels found', colors.green);
    } else {
      testsFailed++;
      log('\n⚠️  TEST 1 WARNING - No hotels found (may be expected)', colors.yellow);
    }
  } catch (error) {
    testsFailed++;
    log('\n❌ TEST 1 FAILED', colors.red);
  }

  // Test 2: Direct Provider Booking (informational)
  await testResAvenueProviderBooking();

  // Test 3: Itinerary Confirmation
  try {
    log('\n⚠️  TEST 3 requires valid itinerary plan ID and hotel data', colors.yellow);
    log('Set TEST_ITINERARY_PLAN_ID in .env to test', colors.yellow);
    
    if (process.env.TEST_ITINERARY_PLAN_ID) {
      const confirmed = await confirmItineraryWithResAvenue();
      if (confirmed) {
        testsPassed++;
        log('\n✅ TEST 3 PASSED - Itinerary confirmed', colors.green);
      } else {
        testsFailed++;
        log('\n❌ TEST 3 FAILED', colors.red);
      }
    } else {
      log('\n⏭️  TEST 3 SKIPPED - No itinerary ID provided', colors.yellow);
    }
  } catch (error) {
    testsFailed++;
    log('\n❌ TEST 3 FAILED', colors.red);
  }

  // Test 4: Itinerary Cancellation
  try {
    log('\n⚠️  TEST 4 requires confirmed itinerary with ResAvenue bookings', colors.yellow);
    
    if (process.env.TEST_ITINERARY_PLAN_ID) {
      const cancelled = await cancelItineraryWithResAvenue();
      if (cancelled) {
        testsPassed++;
        log('\n✅ TEST 4 PASSED - Itinerary cancelled', colors.green);
      } else {
        testsFailed++;
        log('\n❌ TEST 4 FAILED', colors.red);
      }
    } else {
      log('\n⏭️  TEST 4 SKIPPED - No itinerary ID provided', colors.yellow);
    }
  } catch (error) {
    testsFailed++;
    log('\n❌ TEST 4 FAILED', colors.red);
  }

  // Test 5: Direct Provider Cancellation (informational)
  await testResAvenueProviderCancellation();

  // Summary
  logSection('📊 TEST SUMMARY');
  log(`Total Tests Run: ${testsPassed + testsFailed}`, colors.cyan);
  log(`✅ Passed: ${testsPassed}`, colors.green);
  log(`❌ Failed: ${testsFailed}`, colors.red);

  if (testsFailed === 0 && testsPassed > 0) {
    log('\n🎉 All tests passed!', colors.green);
  } else {
    log('\n⚠️  Some tests failed or were skipped. Check logs above.', colors.yellow);
  }

  // Instructions
  logSection('📝 SETUP INSTRUCTIONS');
  log('\nTo run full tests:', colors.cyan);
  log('1. Ensure backend is running: npm run start:dev', colors.blue);
  log('2. Add to .env file:', colors.blue);
  log('   TEST_ITINERARY_PLAN_ID=<your-test-itinerary-id>', colors.blue);
  log('   TEST_AUTH_TOKEN=<jwt-token-if-needed>', colors.blue);
  log('3. Update hotel codes with actual ResAvenue hotels from your database', colors.blue);
  log('4. Run: npx tsx test-resavenue-booking-api.ts', colors.blue);

  log('\n💡 ResAvenue Provider Details:', colors.cyan);
  log('- Base URL: http://203.109.97.241:8080/ChannelController', colors.blue);
  log('- Endpoint: /PropertyDetails', colors.blue);
  log('- Booking format: OTA_HotelResNotifRQ with ResStatus="Confirm"', colors.blue);
  log('- Cancellation format: OTA_HotelResNotifRQ with ResStatus="Cancel"', colors.blue);
  log('- Room code format: InvCode-RateCode (e.g., "INV001-RATE001")', colors.blue);
}

// Run tests
runTests().catch((error) => {
  log(`\n❌ Fatal error: ${error}`, colors.red);
  process.exit(1);
});
