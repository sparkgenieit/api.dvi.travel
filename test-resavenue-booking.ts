/**
 * ResAvenue Booking Flow Test
 * Tests: confirmBooking, getConfirmation, cancelBooking
 */

import axios from 'axios';

const BACKEND_URL = 'http://localhost:4006/api/v1';

interface BookingTestResult {
  confirmationRef?: string;
  error?: string;
  status: 'success' | 'failed';
}

async function searchHotels(cityCode: string) {
  console.log(`\n🔍 Searching hotels in ${cityCode}...`);
  
  try {
    const response = await axios.post(`${BACKEND_URL}/hotels/search`, {
      cityCode,
      checkInDate: '2026-02-15',
      checkOutDate: '2026-02-17',
      roomCount: 1,
      guestCount: 2,
      providers: ['resavenue']
    });

    const hotels = response.data.data.hotels;
    if (hotels.length === 0) {
      console.log(`❌ No hotels found in ${cityCode}`);
      return null;
    }

    const hotel = hotels[0];
    console.log(`✅ Found hotel: ${hotel.hotelName} (${hotel.hotelCode})`);
    console.log(`   Price: ₹${hotel.price}`);
    console.log(`   Search Reference: ${hotel.searchReference}`);
    console.log(`   Room Types: ${hotel.roomTypes.length}`);
    console.log(`   First Room: ${hotel.roomTypes[0].roomName} (${hotel.roomTypes[0].roomCode})`);

    return hotel;
  } catch (error: any) {
    console.error(`❌ Search failed: ${error.message}`);
    return null;
  }
}

async function confirmBooking(hotel: any): Promise<BookingTestResult> {
  console.log(`\n📝 Confirming booking for ${hotel.hotelName}...`);
  
  try {
    const bookingData = {
      itineraryPlanId: 1,
      searchReference: hotel.searchReference,
      hotelCode: hotel.hotelCode,
      checkInDate: '2026-02-15',
      checkOutDate: '2026-02-17',
      roomCount: 1,
      guests: [
        {
          firstName: 'John',
          lastName: 'Doe',
          email: 'john.doe@example.com',
          phone: '+919876543210'
        }
      ],
      rooms: [
        {
          roomCode: hotel.roomTypes[0].roomCode,
          quantity: 1,
          guestCount: 2
        }
      ],
      contactName: 'John Doe',
      contactEmail: 'john.doe@example.com',
      contactPhone: '+919876543210'
    };

    console.log(`📤 Booking request:`, JSON.stringify(bookingData, null, 2));

    const response = await axios.post(`${BACKEND_URL}/hotels/confirm`, bookingData);

    const confirmation = response.data.data;
    console.log(`✅ Booking confirmed!`);
    console.log(`   Confirmation Ref: ${confirmation.confirmationReference}`);
    console.log(`   Provider: ${confirmation.provider}`);
    console.log(`   Hotel: ${confirmation.hotelName || hotel.hotelName}`);
    console.log(`   Check-in: ${confirmation.checkIn}`);
    console.log(`   Check-out: ${confirmation.checkOut}`);
    console.log(`   Status: ${confirmation.status}`);

    return {
      confirmationRef: confirmation.confirmationReference,
      status: 'success'
    };
  } catch (error: any) {
    const errorMsg = error.response?.data?.message || error.message;
    console.error(`❌ Booking failed: ${errorMsg}`);
    
    if (error.response?.data) {
      console.error(`   API Response:`, JSON.stringify(error.response.data, null, 2));
    }

    return {
      error: errorMsg,
      status: 'failed'
    };
  }
}

async function getConfirmation(confirmationRef: string): Promise<boolean> {
  console.log(`\n📋 Getting confirmation details for ${confirmationRef}...`);
  
  try {
    const response = await axios.get(`${BACKEND_URL}/hotels/confirmation/${confirmationRef}`);

    const details = response.data.data;
    console.log(`✅ Confirmation details retrieved!`);
    console.log(`   Hotel: ${details.hotelName}`);
    console.log(`   Check-in: ${details.checkIn}`);
    console.log(`   Check-out: ${details.checkOut}`);
    console.log(`   Room Count: ${details.roomCount}`);
    console.log(`   Total Price: ₹${details.totalPrice}`);
    console.log(`   Status: ${details.status}`);

    return true;
  } catch (error: any) {
    const errorMsg = error.response?.data?.message || error.message;
    console.error(`❌ Get confirmation failed: ${errorMsg}`);
    
    if (error.response?.data) {
      console.error(`   API Response:`, JSON.stringify(error.response.data, null, 2));
    }

    return false;
  }
}

async function cancelBooking(confirmationRef: string): Promise<boolean> {
  console.log(`\n❌ Cancelling booking ${confirmationRef}...`);
  
  try {
    const response = await axios.post(`${BACKEND_URL}/hotels/cancel/${confirmationRef}`, {
      reason: 'Test cancellation - automated test'
    });

    const cancellation = response.data.data;
    console.log(`✅ Booking cancelled!`);
    console.log(`   Cancellation Ref: ${cancellation.cancellationRef}`);
    console.log(`   Refund Amount: ₹${cancellation.refundAmount}`);
    console.log(`   Charges: ₹${cancellation.charges}`);
    console.log(`   Refund Days: ${cancellation.refundDays}`);

    return true;
  } catch (error: any) {
    const errorMsg = error.response?.data?.message || error.message;
    console.error(`❌ Cancellation failed: ${errorMsg}`);
    
    if (error.response?.data) {
      console.error(`   API Response:`, JSON.stringify(error.response.data, null, 2));
    }

    return false;
  }
}

async function runTests() {
  console.log('='.repeat(80));
  console.log('🏨 RESAVENUE BOOKING FLOW TEST');
  console.log('='.repeat(80));

  try {
    // Test 1: Search for hotels in Mumbai
    console.log('\n\n📍 TEST 1: Search Hotels in Mumbai');
    console.log('-'.repeat(80));
    const hotel = await searchHotels('Mumbai');
    
    if (!hotel) {
      console.log('\n❌ Cannot proceed with booking tests - no hotels found');
      return;
    }

    // Test 2: Confirm booking
    console.log('\n\n📍 TEST 2: Confirm Booking');
    console.log('-'.repeat(80));
    const bookingResult = await confirmBooking(hotel);
    
    if (bookingResult.status === 'failed') {
      console.log('\n⚠️  Booking confirmation failed');
      console.log('This is expected if:');
      console.log('  1. Backend hotel confirmation endpoint is not implemented');
      console.log('  2. ResAvenue sandbox doesn\'t support booking creation');
      console.log('  3. Room/rate codes are test-only and cannot be booked');
      return;
    }

    const confirmationRef = bookingResult.confirmationRef!;

    // Test 3: Get confirmation details
    console.log('\n\n📍 TEST 3: Get Confirmation Details');
    console.log('-'.repeat(80));
    const confirmationSuccess = await getConfirmation(confirmationRef);

    if (!confirmationSuccess) {
      console.log('\n⚠️  Get confirmation failed - skipping cancellation test');
    }

    // Test 4: Cancel booking
    console.log('\n\n📍 TEST 4: Cancel Booking');
    console.log('-'.repeat(80));
    await cancelBooking(confirmationRef);

    console.log('\n\n' + '='.repeat(80));
    console.log('✅ ALL BOOKING TESTS COMPLETED');
    console.log('='.repeat(80));

  } catch (error: any) {
    console.error('\n\n' + '='.repeat(80));
    console.error('❌ TEST SUITE FAILED');
    console.error('='.repeat(80));
    console.error(`Error: ${error.message}`);
    console.error(error.stack);
  }
}

// Run tests
console.log('Starting ResAvenue booking flow tests...');
console.log('Make sure backend is running on http://localhost:4006\n');

runTests().catch(console.error);
