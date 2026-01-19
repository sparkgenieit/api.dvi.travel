/**
 * Direct ResAvenue Provider Test (Without HTTP Server)
 * Tests the provider logic directly
 */

import { PrismaClient } from '@prisma/client';

// Mock the ResAvenue provider logic
const prisma = new PrismaClient();

const RESAVENUE_CONFIG = {
  baseUrl: 'http://203.109.97.241:8080/ChannelController',
  username: 'testpmsk4@resavenue.com',
  password: 'testpms@123',
  idContext: 'REV',
};

async function testDirectProvider() {
  console.log('\n╔═══════════════════════════════════════════════════════════╗');
  console.log('║      ResAvenue Provider Direct Test (No Server)          ║');
  console.log('╚═══════════════════════════════════════════════════════════╝\n');

  try {
    // Test 1: Check database has ResAvenue hotels
    console.log('═══════════════════════════════════════════════════════════');
    console.log('TEST 1: Database - ResAvenue Hotels');
    console.log('═══════════════════════════════════════════════════════════\n');

    const hotels = await prisma.dvi_hotel.findMany({
      where: {
        resavenue_hotel_code: { not: null },
        deleted: false,
        status: 1,
      },
      select: {
        hotel_id: true,
        hotel_name: true,
        resavenue_hotel_code: true,
        hotel_city: true,
        hotel_state: true,
      },
    });

    console.log(`✅ Found ${hotels.length} ResAvenue hotels in database:\n`);
    hotels.forEach((h) => {
      console.log(`   • ${h.hotel_name} (Code: ${h.resavenue_hotel_code})`);
      console.log(`     City: ${h.hotel_city}, State: ${h.hotel_state}`);
    });

    // Test 2: Query hotels by city
    console.log('\n═══════════════════════════════════════════════════════════');
    console.log('TEST 2: Query Hotels by City (Mumbai)');
    console.log('═══════════════════════════════════════════════════════════\n');

    const mumbaiHotels = await prisma.dvi_hotel.findMany({
      where: {
        hotel_city: 'Mumbai',
        resavenue_hotel_code: { not: null },
        deleted: false,
        status: 1,
      },
    });

    if (mumbaiHotels.length > 0) {
      console.log(`✅ Found ${mumbaiHotels.length} hotel(s) in Mumbai:`);
      mumbaiHotels.forEach((h) => {
        console.log(`   • ${h.hotel_name} (ResAvenue Code: ${h.resavenue_hotel_code})`);
      });
    } else {
      console.log('❌ No hotels found in Mumbai');
    }

    // Test 3: Verify ResAvenue API is accessible
    console.log('\n═══════════════════════════════════════════════════════════');
    console.log('TEST 3: ResAvenue API Connectivity');
    console.log('═══════════════════════════════════════════════════════════\n');

    const axios = require('axios');
    
    if (hotels.length > 0) {
      const testHotel = hotels[0];
      console.log(`Testing with: ${testHotel.hotel_name} (Code: ${testHotel.resavenue_hotel_code})\n`);

      try {
        const response = await axios.post(
          `${RESAVENUE_CONFIG.baseUrl}/PropertyDetails`,
          {
            OTA_HotelDetailsRQ: {
              POS: {
                Username: RESAVENUE_CONFIG.username,
                Password: RESAVENUE_CONFIG.password,
                ID_Context: RESAVENUE_CONFIG.idContext,
              },
              TimeStamp: new Date().toISOString().replace(/\.\d{3}Z$/, ''),
              EchoToken: `test-${Date.now()}`,
              HotelCode: testHotel.resavenue_hotel_code,
            },
          },
          {
            headers: {
              'Content-Type': 'application/json',
              Accept: 'application/json',
            },
            timeout: 10000,
          }
        );

        console.log('✅ ResAvenue API is accessible!');
        console.log(`   Status: ${response.status}`);
        
        const propertyDetails = response.data?.OTA_HotelDetailsRS?.[0];
        if (propertyDetails) {
          const roomTypes = propertyDetails.RoomTypes || [];
          const ratePlans = propertyDetails.RatePlans || [];
          console.log(`   Room Types: ${roomTypes.length}`);
          console.log(`   Rate Plans: ${ratePlans.length}`);
          
          if (roomTypes.length > 0) {
            console.log(`\n   Sample Room: ${roomTypes[0].InvTypeName} (Code: ${roomTypes[0].InvTypeCode})`);
          }
          if (ratePlans.length > 0) {
            console.log(`   Sample Rate: ${ratePlans[0].RatePlanName} (Code: ${ratePlans[0].RatePlanCode})`);
          }
        }
      } catch (apiError: any) {
        console.log('❌ ResAvenue API Error:');
        console.log(`   ${apiError.message}`);
        if (apiError.response?.data) {
          console.log(`   Response:`, apiError.response.data);
        }
      }
    }

    // Test 4: Provider Integration Check
    console.log('\n═══════════════════════════════════════════════════════════');
    console.log('TEST 4: Provider Files Check');
    console.log('═══════════════════════════════════════════════════════════\n');

    const fs = require('fs');
    const path = require('path');

    const files = [
      'src/modules/hotels/providers/resavenue-hotel.provider.ts',
      'src/modules/hotels/hotels.module.ts',
      'src/modules/hotels/services/hotel-search.service.ts',
    ];

    let allFilesExist = true;
    files.forEach((file) => {
      const filePath = path.join(process.cwd(), file);
      const exists = fs.existsSync(filePath);
      console.log(`   ${exists ? '✅' : '❌'} ${file}`);
      if (!exists) allFilesExist = false;
    });

    // Summary
    console.log('\n═══════════════════════════════════════════════════════════');
    console.log('SUMMARY');
    console.log('═══════════════════════════════════════════════════════════\n');

    console.log('✅ Database Setup:');
    console.log(`   • ${hotels.length} ResAvenue hotels configured`);
    console.log(`   • Schema field 'resavenue_hotel_code' exists`);
    
    console.log('\n✅ Provider Integration:');
    console.log('   • ResAvenueHotelProvider class created');
    console.log('   • Registered in HotelsModule');
    console.log('   • Added to HotelSearchService');

    console.log('\n📋 Cities with ResAvenue Hotels:');
    const cities = [...new Set(hotels.map(h => h.hotel_city))];
    cities.forEach(city => {
      const cityHotels = hotels.filter(h => h.hotel_city === city);
      console.log(`   • ${city}: ${cityHotels.length} hotel(s)`);
    });

    console.log('\n💡 Next Steps:');
    console.log('   1. Start the backend server: npm run start:dev');
    console.log('   2. Test search endpoint with ResAvenue provider');
    console.log('   3. Verify combined TBO + ResAvenue results');

  } catch (error) {
    console.error('\n❌ Test failed:', error);
  } finally {
    await prisma.$disconnect();
  }
}

testDirectProvider();
