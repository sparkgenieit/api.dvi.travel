/**
 * Test Hotel Voucher Cancellation API (Node.js/JavaScript)
 * This script POSTs to the fixed endpoint to test the TBO cancellation
 * 
 * Usage: node test-hotel-voucher-cancellation.js
 * Or with token: AUTH_TOKEN=your_token node test-hotel-voucher-cancellation.js
 */

const axios = require('axios');

// Configuration
const API_URL = 'http://localhost:4006/api/v1/itineraries/11/hotel-vouchers';
const AUTH_TOKEN = process.env.AUTH_TOKEN || 'your_auth_token_here';

// Request payload
const payload = {
  itineraryPlanId: 11,
  vouchers: [
    {
      hotelId: 1219121,
      hotelDetailsIds: [385],
      routeDates: ['2026-04-27'],
      confirmedBy: 'testtt',
      emailId: 'kiran.phpfish@gmail.com',
      mobileNumber: '4234234',
      status: 'cancelled',
      invoiceTo: 'gst_bill_against_dvi',
      voucherTermsCondition: 'Standard hotel voucher terms and conditions apply.',
    },
  ],
};

async function testHotelVoucherCancellation() {
  console.log('\n' + '='.repeat(80));
  console.log('🧪 Testing Hotel Voucher Cancellation');
  console.log('='.repeat(80) + '\n');

  console.log('📋 Request Details:');
  console.log(`   URL: ${API_URL}`);
  console.log(`   Method: POST`);
  console.log(`   Payload:`, JSON.stringify(payload, null, 2));
  console.log('\n⏳ Sending request...\n');

  try {
    const response = await axios.post(API_URL, payload, {
      headers: {
        'Authorization': `Bearer ${AUTH_TOKEN}`,
        'Content-Type': 'application/json',
      },
      timeout: 30000,
    });

    console.log('✅ SUCCESS - Request completed successfully!\n');

    console.log('📊 Response Status:', response.status, response.statusText);
    console.log('\n📝 Response Data:');
    console.log(JSON.stringify(response.data, null, 2));

    console.log('\n' + '='.repeat(80));
    console.log('✅ TEST PASSED - Hotel voucher cancellation works!');
    console.log('='.repeat(80) + '\n');

    return true;
  } catch (error) {
    console.log('❌ ERROR - Request failed!\n');

    if (error.response) {
      console.log('📊 Response Status:', error.response.status, error.response.statusText);
      console.log('\n📝 Error Response:');
      console.log(JSON.stringify(error.response.data, null, 2));

      if (error.response.status === 400) {
        console.log('\n⚠️  400 Bad Request - Check if the fix was applied correctly');
      } else if (error.response.status === 401) {
        console.log('\n⚠️  401 Unauthorized - Check AUTH_TOKEN');
      } else if (error.response.status === 404) {
        console.log('\n⚠️  404 Not Found - Check API_URL is correct');
      }
    } else if (error.request) {
      console.log('📊 No response received');
      console.log('   Error:', error.message);
      console.log('   Is the server running on localhost:4006?');
    } else {
      console.log('❌ Error:', error.message);
    }

    console.log('\n' + '='.repeat(80));
    console.log('❌ TEST FAILED');
    console.log('='.repeat(80) + '\n');

    return false;
  }
}

// Run the test
testHotelVoucherCancellation().catch((error) => {
  console.error('Fatal error:', error);
  process.exit(1);
});
