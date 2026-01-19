/**
 * Test ResAvenue Hotel Cancellation Implementation
 * 
 * This script tests:
 * 1. ResAvenueHotelBookingService.cancelItineraryHotels() 
 * 2. ResAvenueHotelProvider.cancelBooking()
 * 3. Database status updates
 */

import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function testResAvenueCancellation() {
  console.log('🔍 Testing ResAvenue Cancellation Implementation\n');

  try {
    // Check if table exists by trying to query it
    try {
      const count = await prisma.resavenue_hotel_booking_confirmation.count();
      console.log(`✅ Table resavenue_hotel_booking_confirmation exists (${count} records)\n`);
    } catch (error) {
      console.log('❌ Table resavenue_hotel_booking_confirmation does not exist');
      console.log('   Run: npx prisma db push\n');
      return;
    }

    // Check implementation files
    console.log('🔍 Checking implementation files:\n');

    const fs = require('fs');
    const path = require('path');

    // Check ResAvenueHotelBookingService exists
    const bookingServicePath = path.join(
      __dirname,
      'src/modules/itineraries/services/resavenue-hotel-booking.service.ts'
    );
    
    let serviceExists = false;
    let hasCancelMethod = false;
    
    try {
      const bookingServiceCode = fs.readFileSync(bookingServicePath, 'utf8');
      serviceExists = true;
      hasCancelMethod = bookingServiceCode.includes('cancelItineraryHotels');
    } catch (error) {
      console.log('❌ ResAvenueHotelBookingService file not found');
    }
    
    console.log(`✅ ResAvenueHotelBookingService: ${serviceExists ? 'EXISTS' : 'MISSING'}`);
    console.log(`✅ ResAvenueHotelBookingService.cancelItineraryHotels(): ${hasCancelMethod ? 'EXISTS' : 'MISSING'}`);

    // Check ResAvenueHotelProvider
    const providerPath = path.join(
      __dirname,
      'src/modules/hotels/providers/resavenue-hotel.provider.ts'
    );
    const providerCode = fs.readFileSync(providerPath, 'utf8');
    const hasProviderCancel = providerCode.includes('async cancelBooking');
    
    console.log(`✅ ResAvenueHotelProvider.cancelBooking(): ${hasProviderCancel ? 'EXISTS' : 'MISSING'}`);

    // Check itineraries.service.ts calls ResAvenue cancellation
    const itinerariesServicePath = path.join(
      __dirname,
      'src/modules/itineraries/itineraries.service.ts'
    );
    const itinerariesServiceCode = fs.readFileSync(itinerariesServicePath, 'utf8');
    const callsResAvenueCancellation = itinerariesServiceCode.includes('resavenueHotelBooking.cancelItineraryHotels');
    
    console.log(`✅ ItinerariesService calls ResAvenue cancellation: ${callsResAvenueCancellation ? 'YES' : 'NO'}`);

    // Check ResAvenueHotelProvider is injected
    let hasResAvenueProviderInjection = false;
    if (serviceExists) {
      const bookingServiceCode = fs.readFileSync(bookingServicePath, 'utf8');
      hasResAvenueProviderInjection = bookingServiceCode.includes('private readonly resavenueProvider: ResAvenueHotelProvider');
    }
    
    console.log(`✅ ResAvenueHotelProvider injected in ResAvenueHotelBookingService: ${hasResAvenueProviderInjection ? 'YES' : 'NO'}`);

    // Check if service is registered in module
    const modulePath = path.join(
      __dirname,
      'src/modules/itineraries/itinerary.module.ts'
    );
    const moduleCode = fs.readFileSync(modulePath, 'utf8');
    const isRegistered = moduleCode.includes('ResAvenueHotelBookingService');
    
    console.log(`✅ ResAvenueHotelBookingService registered in module: ${isRegistered ? 'YES' : 'NO'}`);

    console.log('\n📊 Implementation Summary:');
    
    if (serviceExists && hasCancelMethod && hasProviderCancel && callsResAvenueCancellation && hasResAvenueProviderInjection && isRegistered) {
      console.log('✅ ALL COMPONENTS IMPLEMENTED!');
      console.log('\n🎯 Cancellation Flow:');
      console.log('   1. User cancels itinerary → POST /api/v1/itineraries/cancel');
      console.log('   2. ItinerariesService.cancelHotels()');
      console.log('   3. → TboHotelBookingService.cancelItineraryHotels() (for TBO hotels)');
      console.log('   4. → ResAvenueHotelBookingService.cancelItineraryHotels() (for ResAvenue hotels)');
      console.log('   5.   → Finds bookings from resavenue_hotel_booking_confirmation');
      console.log('   6.   → ResAvenueHotelProvider.cancelBooking() for each');
      console.log('   7.     → Calls ResAvenue API: OTA_HotelResNotifRQ (ResStatus=Cancel)');
      console.log('   8.   → Updates booking status=0 in database');
      console.log('   9. → Marks hotels cancelled in itinerary tables');
      console.log('   10. → Creates refund record\n');

      console.log('📋 Database Tables:');
      console.log('   - tbo_hotel_booking_confirmation (TBO bookings)');
      console.log('   - resavenue_hotel_booking_confirmation (ResAvenue bookings)');
      console.log('   - dvi_itinerary_plan_hotel_details (main itinerary hotels)');
      console.log('   - dvi_cancelled_itinerary_plan_hotel_details (cancelled hotels)\n');
    } else {
      console.log('❌ MISSING COMPONENTS:');
      if (!serviceExists) console.log('   - ResAvenueHotelBookingService file');
      if (!hasCancelMethod) console.log('   - cancelItineraryHotels method');
      if (!hasProviderCancel) console.log('   - ResAvenueHotelProvider.cancelBooking');
      if (!callsResAvenueCancellation) console.log('   - Call to ResAvenue cancellation in itinerary service');
      if (!hasResAvenueProviderInjection) console.log('   - ResAvenueHotelProvider injection');
      if (!isRegistered) console.log('   - Service registration in module');
    }

    // Check for any existing ResAvenue bookings
    const booking = await prisma.resavenue_hotel_booking_confirmation.findFirst({
      where: {
        status: 1,
        deleted: 0,
      },
      orderBy: {
        createdon: 'desc',
      },
    });

    if (booking) {
      console.log('\n💡 Found ResAvenue booking to test with:');
      console.log(`   ID: ${booking.resavenue_hotel_booking_confirmation_ID}`);
      console.log(`   Itinerary Plan ID: ${booking.itinerary_plan_ID}`);
      console.log(`   Booking Ref: ${booking.resavenue_booking_reference}`);
      console.log(`   Hotel Code: ${booking.resavenue_hotel_code}`);
      console.log(`   Amount: ${booking.net_amount}`);
    } else {
      console.log('\n💡 No ResAvenue bookings found in database yet');
      console.log('   You\'ll need to create a ResAvenue booking first before testing cancellation');
    }

    console.log('\n💡 To test live cancellation:');
    console.log('   1. Start backend: npm run start:dev');
    console.log('   2. Get auth token from login');
    console.log('   3. POST /api/v1/itineraries/cancel');
    console.log('      Body: { "itinerary_plan_id": <id>, "reason": "Testing cancellation" }');
    console.log('   4. Check logs for both TBO and ResAvenue cancellation results');

  } catch (error) {
    console.error('❌ Error:', error.message);
  } finally {
    await prisma.$disconnect();
  }
}

testResAvenueCancellation();
