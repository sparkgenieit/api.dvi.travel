/**
 * TBO Booking Flow Test
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
  console.log(`\n🔍 Searching TBO hotels in ${cityCode}...`);
  
  try {
    const response = await axios.post(`${BACKEND_URL}/hotels/search`, {
      cityCode,
      checkInDate: '2026-02-15',
      checkOutDate: '2026-02-17',
      roomCount: 1,
      guestCount: 2,
      providers: ['tbo']
    });

    const hotels = response.data.data.hotels;
    if (hotels.length === 0) {
      console.log(`❌ No TBO hotels found in ${cityCode}`);
      return null;
    }

    const hotel = hotels[0];
    console.log(`✅ Found hotel: ${hotel.hotelName} (${hotel.hotelCode})`);
    console.log(`   Provider: ${hotel.provider}`);
    console.log(`   Price: ₹${hotel.price}`);
    console.log(`   Search Reference: ${hotel.searchReference}`);
    console.log(`   Room Types: ${hotel.roomTypes.length}`);
    if (hotel.roomTypes.length > 0) {
      console.log(`   First Room: ${hotel.roomTypes[0].roomName} (${hotel.roomTypes[0].roomCode})`);
    }

    return hotel;
  } catch (error: any) {
    console.error(`❌ Search failed: ${error.message}`);
    if (error.response?.data) {
      console.error(`   API Response:`, JSON.stringify(error.response.data, null, 2));
    }
    return null;
  }
}

async function confirmBooking(hotel: any): Promise<BookingTestResult> {
  console.log(`\n📝 Confirming TBO booking for ${hotel.hotelName}...`);
  
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
          firstName: 'Test',
          lastName: 'User',
          email: 'test.user@example.com',
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
      contactName: 'Test User',
      contactEmail: 'test.user@example.com',
      contactPhone: '+919876543210'
    };

    console.log(`📤 Booking request:`, JSON.stringify(bookingData, null, 2));

    const response = await axios.post(`${BACKEND_URL}/hotels/confirm`, bookingData);

    const confirmation = response.data.data;
    console.log(`✅ TBO Booking confirmed!`);
    console.log(`   Confirmation Ref: ${confirmation.confirmationReference}`);
    console.log(`   Provider: ${confirmation.provider}`);
    console.log(`   Hotel: ${confirmation.hotelName || hotel.hotelName}`);
    console.log(`   Check-in: ${confirmation.checkIn}`);
    console.log(`   Check-out: ${confirmation.checkOut}`);
    console.log(`   Status: ${confirmation.status}`);
    console.log(`   Total Price: ₹${confirmation.totalPrice}`);

    return {
      confirmationRef: confirmation.confirmationReference,
      status: 'success'
    };
  } catch (error: any) {
    const errorMsg = error.response?.data?.message || error.message;
    console.error(`❌ TBO Booking failed: ${errorMsg}`);
    
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
  console.log(`\n📋 Getting TBO confirmation details for ${confirmationRef}...`);
  
  try {
    const response = await axios.get(`${BACKEND_URL}/hotels/confirmation/${confirmationRef}`);

    const details = response.data.data;
    console.log(`✅ TBO Confirmation details retrieved!`);
    console.log(`   Hotel: ${details.hotelName}`);
    console.log(`   Check-in: ${details.checkIn}`);
    console.log(`   Check-out: ${details.checkOut}`);
    console.log(`   Room Count: ${details.roomCount}`);
    console.log(`   Total Price: ₹${details.totalPrice}`);
    console.log(`   Status: ${details.status}`);
    console.log(`   Cancellation Policy: ${details.cancellationPolicy}`);

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
  console.log(`\n❌ Cancelling TBO booking ${confirmationRef}...`);
  
  try {
    const response = await axios.post(`${BACKEND_URL}/hotels/cancel/${confirmationRef}`, {
      reason: 'Test cancellation - automated TBO test'
    });

    const cancellation = response.data.data;
    console.log(`✅ TBO Booking cancelled successfully!`);
    console.log(`   Cancellation Ref: ${cancellation.cancellationRef}`);
    console.log(`   Refund Amount: ₹${cancellation.refundAmount}`);
    console.log(`   Cancellation Charges: ₹${cancellation.charges}`);
    console.log(`   Refund Days: ${cancellation.refundDays} days`);

    return true;
  } catch (error: any) {
    const errorMsg = error.response?.data?.message || error.message;
    console.error(`❌ TBO Cancellation failed: ${errorMsg}`);
    
    if (error.response?.data) {
      console.error(`   API Response:`, JSON.stringify(error.response.data, null, 2));
    }

    return false;
  }
}

async function runTests() {
  console.log('='.repeat(80));
  console.log('🏨 TBO BOOKING FLOW TEST');
  console.log('='.repeat(80));

  try {
    // Test 1: Search for TBO hotels
    console.log('\n\n📍 TEST 1: Search TBO Hotels');
    console.log('-'.repeat(80));
    const hotel = await searchHotels('Rameswaram');
    
    if (!hotel) {
      console.log('\n❌ Cannot proceed with booking tests - no TBO hotels found');
      console.log('Try different city: Mumbai, Gwalior, Delhi, etc.');
      return;
    }

    // Test 2: Confirm booking
    console.log('\n\n📍 TEST 2: Confirm TBO Booking');
    console.log('-'.repeat(80));
    const bookingResult = await confirmBooking(hotel);
    
    if (bookingResult.status === 'failed') {
      console.log('\n⚠️  TBO Booking confirmation failed');
      console.log('Possible reasons:');
      console.log('  1. Backend hotel confirmation endpoint not implemented');
      console.log('  2. TBO API credentials invalid or expired');
      console.log('  3. Room availability changed between search and booking');
      console.log('  4. TBO requires payment details (not provided in test)');
      return;
    }

    const confirmationRef = bookingResult.confirmationRef!;

    // Test 3: Get confirmation details
    console.log('\n\n📍 TEST 3: Get TBO Confirmation Details');
    console.log('-'.repeat(80));
    const confirmationSuccess = await getConfirmation(confirmationRef);

    if (!confirmationSuccess) {
      console.log('\n⚠️  Get confirmation failed - skipping cancellation test');
      console.log('The booking may still exist in TBO system');
    }

    // Test 4: Cancel booking
    console.log('\n\n📍 TEST 4: Cancel TBO Booking');
    console.log('-'.repeat(80));
    const cancelSuccess = await cancelBooking(confirmationRef);

    if (cancelSuccess) {
      console.log('\n\n' + '='.repeat(80));
      console.log('✅ ALL TBO BOOKING TESTS COMPLETED SUCCESSFULLY');
      console.log('='.repeat(80));
      console.log('\n📊 Summary:');
      console.log('   ✅ Search: Working');
      console.log('   ✅ Booking: Working');
      console.log('   ✅ Get Confirmation: Working');
      console.log('   ✅ Cancellation: Working');
    } else {
      console.log('\n\n' + '='.repeat(80));
      console.log('⚠️  TBO BOOKING TESTS PARTIALLY COMPLETED');
      console.log('='.repeat(80));
      console.log('\n📊 Summary:');
      console.log('   ✅ Search: Working');
      console.log('   ✅ Booking: Working');
      console.log('   ✅ Get Confirmation: ' + (confirmationSuccess ? 'Working' : 'Failed'));
      console.log('   ❌ Cancellation: Failed');
    }

  } catch (error: any) {
    console.error('\n\n' + '='.repeat(80));
    console.error('❌ TBO TEST SUITE FAILED');
    console.error('='.repeat(80));
    console.error(`Error: ${error.message}`);
    if (error.stack) {
      console.error(error.stack);
    }
  }
}

// Run tests
console.log('Starting TBO booking flow tests...');
console.log('Make sure backend is running on http://localhost:4006\n');

runTests().catch(console.error);
