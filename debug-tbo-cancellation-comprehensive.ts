/**
 * TBO Cancellation Fix & Debug Script
 * 
 * This script diagnoses and fixes the TBO 400 error on cancellation.
 * 
 * The issue: SendChangeRequest returns 400 Bad Request
 * Possible causes:
 * 1. BookingId format (should be numeric ID from Book response, not BookingRefNo)
 * 2. Missing required fields in cancellation request
 * 3. Incorrect endpoint or parameters
 * 4. TokenId expiration or validation issue
 */

import axios, { AxiosError } from 'axios';

interface DebugInfo {
  bookingId: number;
  bookingRefNo: string;
  scenario: string;
  request: any;
  response?: any;
  error?: string;
  status?: number;
}

const TBO_API_URL = 'https://sharedapi.tektravels.com/SharedData.svc/rest';
const TBO_BOOKING_API_URL = 'https://sharedapi.tektravels.com';

// Sample data from the logs
const SAMPLE_BOOKING = {
  bookingId: 31,  // This is from tbo_hotel_booking_confirmation table (the primary key we stored)
  tboBookingRef: '669667240173025',  // This is the reference returned by TBO API
};

const debugResults: DebugInfo[] = [];

async function authenticateTBO(): Promise<string> {
  console.log('\n🔐 Authenticating with TBO...');
  try {
    const response = await axios.post(
      `${TBO_API_URL}/Authenticate`,
      {
        UserName: 'Doview',
        Password: process.env.TBO_PASSWORD || 'Doview@12345',
        EndUserIp: '192.168.1.1',
      },
      { timeout: 30000 }
    );

    const tokenId = response.data.TokenId;
    console.log(`✅ Auth successful - TokenId: ${tokenId.substring(0, 8)}...`);
    return tokenId;
  } catch (error: any) {
    console.error('❌ Authentication failed:', error.response?.data?.Error?.ErrorMessage || error.message);
    throw error;
  }
}

async function testCancellationScenario(
  scenario: string,
  bookingId: any,
  tokenId: string,
  remarks: string,
  additionalFields?: any
): Promise<DebugInfo> {
  console.log(`\n📋 Testing: ${scenario}`);
  
  const request = {
    BookingMode: 5,
    RequestType: 4, // HotelCancel
    Remarks: remarks,
    BookingId: bookingId,
    EndUserIp: '192.168.1.1',
    TokenId: tokenId,
    ...additionalFields,
  };

  console.log(`   BookingId Type: ${typeof bookingId} (Value: ${bookingId})`);
  console.log(`   Payload:`, JSON.stringify(request, null, 2));

  const debugInfo: DebugInfo = {
    bookingId: typeof bookingId === 'number' ? bookingId : parseInt(bookingId),
    bookingRefNo: SAMPLE_BOOKING.tboBookingRef,
    scenario,
    request,
  };

  try {
    const response = await axios.post(
      `${TBO_BOOKING_API_URL}/hotelservice.svc/rest/SendChangeRequest`,
      request,
      {
        headers: {
          'Content-Type': 'application/json',
        },
        timeout: 30000,
      }
    );

    console.log(`✅ Success!`);
    console.log(`   Response:`, JSON.stringify(response.data, null, 2));
    debugInfo.response = response.data;
    debugInfo.status = 200;
  } catch (error: any) {
    const axiosError = error as AxiosError;
    debugInfo.status = axiosError.response?.status;
    debugInfo.error = axiosError.response?.data ? JSON.stringify(axiosError.response.data) : axiosError.message;

    console.error(`❌ Failed`);
    console.error(`   Status: ${axiosError.response?.status}`);
    console.error(`   Error: ${JSON.stringify(axiosError.response?.data, null, 2)}`);

    // Check for specific error patterns
    if (axiosError.response?.status === 400) {
      console.log('\n💡 400 Bad Request - Possible causes:');
      console.log('   • BookingId format is incorrect');
      console.log('   • Missing or invalid required fields');
      console.log('   • Invalid BookingMode or RequestType combination');
    }
  }

  debugResults.push(debugInfo);
  return debugInfo;
}

async function main() {
  console.log('\n' + '='.repeat(70));
  console.log('🚀 TBO CANCELLATION ISSUE DIAGNOSIS & FIX');
  console.log('='.repeat(70));

  try {
    // Get authentication token
    const tokenId = await authenticateTBO();

    console.log('\n' + '='.repeat(70));
    console.log('Testing Different Cancellation Request Formats');
    console.log('='.repeat(70));

    // Scenario 1: Current implementation (failing according to logs)
    await testCancellationScenario(
      'Scenario 1: Current Format (from code - FAILING)',
      SAMPLE_BOOKING.bookingId, // Using ID 31 from confirmation table
      tokenId,
      'Hotel cancelled via voucher'
    );

    // Scenario 2: Using the TBO reference number directly
    await testCancellationScenario(
      'Scenario 2: Using TBO Reference Number as string',
      SAMPLE_BOOKING.tboBookingRef,
      tokenId,
      'Hotel cancelled via voucher'
    );

    // Scenario 3: Try with explicit string conversion
    await testCancellationScenario(
      'Scenario 3: TBO Reference as numeric string parsed to int',
      parseInt(SAMPLE_BOOKING.tboBookingRef),
      tokenId,
      'Hotel cancelled via voucher'
    );

    // Scenario 4: Try without remarks (in case it's causing issues)
    await testCancellationScenario(
      'Scenario 4: Without Remarks field',
      SAMPLE_BOOKING.bookingId,
      tokenId,
      '',
      { Remarks: undefined }
    );

    // Scenario 5: Try with additional TBO required fields
    await testCancellationScenario(
      'Scenario 5: With additional TBO API fields',
      SAMPLE_BOOKING.bookingId,
      tokenId,
      'Hotel cancelled via voucher',
      {
        IsPartialCancellation: false,
        CancellationPolicy: 0,
      }
    );

    // Scenario 6: Check if we need to use different endpoint or method
    console.log('\n📍 Checking Alternative Endpoints...');
    const endpoints = [
      '/hotelservice.svc/rest/SendChangeRequest',
      '/hotelservice.svc/rest/CancelBooking',
      '/hotelservice.svc/rest/HotelCancel',
    ];

    for (const endpoint of endpoints) {
      try {
        console.log(`\n   Testing: ${endpoint}`);
        const response = await axios.post(
          `${TBO_BOOKING_API_URL}${endpoint}`,
          {
            BookingMode: 5,
            RequestType: 4,
            BookingId: SAMPLE_BOOKING.bookingId,
            TokenId: tokenId,
            EndUserIp: '192.168.1.1',
            Remarks: 'Test',
          },
          { timeout: 10000 }
        );
        console.log(`   ✅ Endpoint exists:`, response.status);
      } catch (error: any) {
        if (error.response?.status === 404) {
          console.log(`   ❌ Endpoint not found (404)`);
        } else {
          console.log(`   ⚠️  Status ${error.response?.status || 'unknown'}`);
        }
      }
    }

    // Summary
    console.log('\n' + '='.repeat(70));
    console.log('📊 DIAGNOSIS SUMMARY');
    console.log('='.repeat(70));

    const successful = debugResults.filter(r => r.status === 200);
    const failed = debugResults.filter(r => r.status !== 200);

    console.log(`\n✅ Successful: ${successful.length}`);
    if (successful.length > 0) {
      successful.forEach(r => {
        console.log(`   • ${r.scenario}`);
        console.log(`     BookingId: ${r.bookingId}, Type: ${typeof r.request.BookingId}`);
      });
    }

    console.log(`\n❌ Failed: ${failed.length}`);
    if (failed.length > 0) {
      failed.forEach(r => {
        console.log(`   • ${r.scenario}`);
        console.log(`     Status: ${r.status}`);
        console.log(`     Error: ${r.error}`);
      });
    }

    // Recommendation
    console.log('\n' + '='.repeat(70));
    console.log('💡 RECOMMENDATION');
    console.log('='.repeat(70));

    if (successful.length > 0) {
      console.log('\n✅ Found working cancellation format!');
      const working = successful[0];
      console.log(`\nUse this configuration:`);
      console.log(JSON.stringify(working.request, null, 2));
    } else {
      console.log('\n⚠️  All scenarios failed. Possible issues:');
      console.log('   1. TBO account credentials might be invalid');
      console.log('   2. Bookings might already be cancelled');
      console.log('   3. TBO API might have different requirements for this endpoint');
      console.log('   4. Contact TBO support for SendChangeRequest API specifications');
    }

  } catch (error) {
    console.error('\n❌ Fatal error:', error);
  }
}

main().catch(console.error);
