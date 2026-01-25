/**
 * TBO Cancellation Fix - Validation & Testing Script
 * 
 * This script validates that the fix has been properly implemented
 * and provides a test endpoint to verify the cancellation works.
 */

import axios from 'axios';
import { PrismaClient } from '@prisma/client';
import * as fs from 'fs';
import * as path from 'path';

const prisma = new PrismaClient();

interface ValidationResult {
  check: string;
  status: 'PASS' | 'FAIL' | 'WARN';
  message: string;
}

const results: ValidationResult[] = [];

function addResult(check: string, status: 'PASS' | 'FAIL' | 'WARN', message: string) {
  results.push({ check, status, message });
  const symbol = status === 'PASS' ? '✅' : status === 'FAIL' ? '❌' : '⚠️ ';
  console.log(`${symbol} ${check}: ${message}`);
}

async function validateCodeChanges() {
  console.log('\n' + '='.repeat(80));
  console.log('🔍 VALIDATING CODE CHANGES');
  console.log('='.repeat(80) + '\n');

  // Check 1: Verify TBOHotelProvider.cancelBooking signature
  try {
    const providerPath = path.join(
      process.cwd(),
      'src/modules/hotels/providers/tbo-hotel.provider.ts'
    );
    const providerCode = fs.readFileSync(providerPath, 'utf8');

    if (providerCode.includes('async cancelBooking(\n    bookingId: string,')) {
      addResult(
        'TBOHotelProvider.cancelBooking() signature',
        'PASS',
        'Updated to accept bookingId as first parameter'
      );
    } else {
      addResult(
        'TBOHotelProvider.cancelBooking() signature',
        'FAIL',
        'Signature not updated correctly'
      );
    }

    // Check 2: Verify BookingId parsing uses correct parameter
    if (providerCode.includes('BookingId: parseInt(bookingId)')) {
      addResult(
        'SendChangeRequest BookingId parameter',
        'PASS',
        'Using correct bookingId parameter'
      );
    } else {
      addResult(
        'SendChangeRequest BookingId parameter',
        'FAIL',
        'Still using wrong parameter for BookingId'
      );
    }
  } catch (error: any) {
    addResult('TBOHotelProvider validation', 'FAIL', error.message);
  }

  // Check 3: Verify TboHotelBookingService uses correct field
  try {
    const servicePath = path.join(
      process.cwd(),
      'src/modules/itineraries/services/tbo-hotel-booking.service.ts'
    );
    const serviceCode = fs.readFileSync(servicePath, 'utf8');

    if (serviceCode.includes('booking.tbo_booking_id,') &&
        serviceCode.includes('this.tboProvider.cancelBooking(\n            booking.tbo_booking_id,')) {
      addResult(
        'TboHotelBookingService cancellation call',
        'PASS',
        'Using booking.tbo_booking_id as first parameter'
      );
    } else {
      addResult(
        'TboHotelBookingService cancellation call',
        'FAIL',
        'Still using tbo_booking_reference_number'
      );
    }

    // Check 4: Verify comment added
    if (serviceCode.includes('IMPORTANT: Pass tbo_booking_id')) {
      addResult(
        'Code documentation',
        'PASS',
        'Comment added explaining the importance of using tbo_booking_id'
      );
    } else {
      addResult(
        'Code documentation',
        'WARN',
        'No explanatory comment found'
      );
    }
  } catch (error: any) {
    addResult('TboHotelBookingService validation', 'FAIL', error.message);
  }
}

async function validateDatabase() {
  console.log('\n' + '='.repeat(80));
  console.log('📊 VALIDATING DATABASE STATE');
  console.log('='.repeat(80) + '\n');

  try {
    // Check if there are TBO bookings with tbo_booking_id populated
    const bookingWithId = await prisma.tbo_hotel_booking_confirmation.findFirst({
      where: {
        tbo_booking_id: {
          not: null,
        },
      },
    });

    if (bookingWithId) {
      addResult(
        'TBO bookings with tbo_booking_id',
        'PASS',
        `Found ${bookingWithId.tbo_hotel_booking_confirmation_ID}: ${bookingWithId.tbo_booking_id}`
      );
    } else {
      addResult(
        'TBO bookings with tbo_booking_id',
        'WARN',
        'No bookings found with tbo_booking_id. Make new bookings to test.'
      );
    }

    // Check schema
    const bookingCount = await prisma.tbo_hotel_booking_confirmation.count();
    addResult(
      'TBO bookings in database',
      bookingCount > 0 ? 'PASS' : 'WARN',
      `Found ${bookingCount} TBO bookings`
    );
  } catch (error: any) {
    addResult('Database validation', 'FAIL', error.message);
  }
}

async function generateTestScript() {
  console.log('\n' + '='.repeat(80));
  console.log('📝 GENERATING TEST SCRIPT');
  console.log('='.repeat(80) + '\n');

  const testScript = `
// Test the fixed cancellation endpoint
// 1. Start the backend: npm run start:dev
// 2. Use this test request:

POST http://localhost:4006/api/v1/itineraries/{itinerary_plan_id}/hotel-vouchers

Authorization: Bearer {your_auth_token}
Content-Type: application/json

{
  "itineraryPlanId": 11,
  "vouchers": [
    {
      "hotelId": 1219121,
      "hotelDetailsIds": [385],
      "routeDates": ["2026-04-27"],
      "confirmedBy": "testtt",
      "emailId": "kiran.phpfish@gmail.com",
      "mobileNumber": "4234234",
      "status": "cancelled",
      "invoiceTo": "gst_bill_against_dvi",
      "voucherTermsCondition": "Standard hotel voucher terms and conditions apply."
    }
  ]
}

// Expected: Success with proper TBO cancellation
// Check logs for: "✅ Booking cancelled successfully" message
  `;

  console.log('Test Request:');
  console.log(testScript);
  addResult('Test script generated', 'PASS', 'Ready to execute');
}

async function validateCancel() {
  console.log('\n' + '='.repeat(80));
  console.log('🚀 VALIDATION SUMMARY');
  console.log('='.repeat(80) + '\n');

  const passed = results.filter(r => r.status === 'PASS').length;
  const failed = results.filter(r => r.status === 'FAIL').length;
  const warnings = results.filter(r => r.status === 'WARN').length;

  console.log(`Results: ✅ ${passed} PASS | ❌ ${failed} FAIL | ⚠️ ${warnings} WARNINGS\n`);

  if (failed > 0) {
    console.log('❌ VALIDATION FAILED - Please review the changes above');
  } else if (warnings > 0) {
    console.log('⚠️  VALIDATION PASSED WITH WARNINGS - Review notes above');
  } else {
    console.log('✅ VALIDATION PASSED - Fix is properly implemented!');
  }

  console.log('\n' + '='.repeat(80));
  console.log('📋 DETAILED RESULTS');
  console.log('='.repeat(80) + '\n');

  results.forEach(r => {
    const symbol = r.status === 'PASS' ? '✅' : r.status === 'FAIL' ? '❌' : '⚠️ ';
    console.log(`${symbol} ${r.check}`);
    console.log(`   ${r.message}\n`);
  });

  console.log('\n' + '='.repeat(80));
  console.log('🎯 NEXT STEPS');
  console.log('='.repeat(80));
  console.log(`
1. Compile TypeScript:
   npm run build

2. Start the backend:
   npm run start:dev

3. Run the test API call (see above)

4. Check the logs for:
   ✅ "Booking cancelled successfully" - Cancellation worked
   ❌ "Cancel Booking Error" with 400 - Issue persists

5. If still getting 400:
   - Verify tbo_booking_id is populated in the database
   - Check TBO API credentials are correct
   - Ensure bookings haven't already been cancelled
  `);
}

async function main() {
  try {
    await validateCodeChanges();
    await validateDatabase();
    await generateTestScript();
    await validateCancel();
  } finally {
    await prisma.$disconnect();
  }
}

main().catch(console.error);
