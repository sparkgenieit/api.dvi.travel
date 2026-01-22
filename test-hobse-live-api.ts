/**
 * HOBSE Live API Test
 * Tests real HOBSE API endpoints with actual credentials
 */

import axios from 'axios';
import * as dotenv from 'dotenv';

// Load environment variables
dotenv.config();

// HOBSE API Configuration
const HOBSE_BASE_URL = process.env.HOBSE_BASE_URL;
const HOBSE_CLIENT_TOKEN = process.env.HOBSE_CLIENT_TOKEN;
const HOBSE_ACCESS_TOKEN = process.env.HOBSE_ACCESS_TOKEN;
const HOBSE_PRODUCT_TOKEN = process.env.HOBSE_PRODUCT_TOKEN;

// Colors for console output
const colors = {
  reset: '\x1b[0m',
  green: '\x1b[32m',
  red: '\x1b[31m',
  yellow: '\x1b[33m',
  cyan: '\x1b[36m',
  blue: '\x1b[34m',
};

function log(message: string, color: string = colors.reset) {
  console.log(`${color}${message}${colors.reset}`);
}

function buildHobseRequest(method: string, data: any = {}) {
  return {
    hobse: {
      version: '1.0',
      datetime: new Date().toISOString(),
      clientToken: HOBSE_CLIENT_TOKEN,
      accessToken: HOBSE_ACCESS_TOKEN,
      productToken: HOBSE_PRODUCT_TOKEN,
      request: {
        method: method,
        data: data,
      },
    },
  };
}

async function makeHobseRequest(method: string, data: any = {}) {
  const url = HOBSE_BASE_URL;
  const payload = buildHobseRequest(method, data);

  log(`\n📤 Calling: ${method}`, colors.cyan);
  log(`URL: ${url}`, colors.blue);
  log(`Payload: ${JSON.stringify(payload, null, 2)}`, colors.blue);

  try {
    const response = await axios.post(url, payload, {
      headers: {
        'Content-Type': 'application/json',
      },
      timeout: 30000,
    });

    log(`✅ Response received`, colors.green);
    return response.data;
  } catch (error: any) {
    log(`❌ Error: ${error.message}`, colors.red);
    if (error.response) {
      log(`Status: ${error.response.status}`, colors.red);
      log(`Data: ${JSON.stringify(error.response.data, null, 2)}`, colors.red);
    }
    throw error;
  }
}

async function testGetCityDetail() {
  log('\n' + '='.repeat(60), colors.yellow);
  log('TEST 1: GetCityDetail (Fetch Cities)', colors.yellow);
  log('='.repeat(60), colors.yellow);

  try {
    const response = await makeHobseRequest('htl/GetCityDetail', {});
    
    const cities = response.hobse?.response?.data || [];
    log(`\n📊 Total Cities: ${cities.length}`, colors.green);
    
    if (cities.length > 0) {
      log('\nSample Cities:', colors.cyan);
      cities.slice(0, 5).forEach((city: any) => {
        log(`  - ${city.CityName} (${city.CityCode})`, colors.blue);
      });
    }
    
    return cities;
  } catch (error) {
    log('❌ GetCityDetail failed', colors.red);
    return [];
  }
}

async function testGetHotelList(cityCode: string) {
  log('\n' + '='.repeat(60), colors.yellow);
  log('TEST 2: GetHotelList (Fetch Hotels for City)', colors.yellow);
  log('='.repeat(60), colors.yellow);

  try {
    const response = await makeHobseRequest('htl/GetHotelList', {
      CityCode: cityCode,
    });
    
    const hotels = response.hobse?.response?.data || [];
    log(`\n📊 Total Hotels in ${cityCode}: ${hotels.length}`, colors.green);
    
    if (hotels.length > 0) {
      log('\nSample Hotels:', colors.cyan);
      hotels.slice(0, 5).forEach((hotel: any) => {
        log(`  - ${hotel.HotelName} (${hotel.HotelCode})`, colors.blue);
      });
    }
    
    return hotels;
  } catch (error) {
    log(`❌ GetHotelList failed for city ${cityCode}`, colors.red);
    return [];
  }
}

async function testGetHotelInfo(hotelCode: string) {
  log('\n' + '='.repeat(60), colors.yellow);
  log('TEST 3: GetHotelInfo (Hotel Details)', colors.yellow);
  log('='.repeat(60), colors.yellow);

  try {
    const response = await makeHobseRequest('htl/GetHotelInfo', {
      HotelCode: hotelCode,
    });
    
    const hotelInfo = response.hobse?.response?.data || {};
    log(`\n📊 Hotel Details:`, colors.green);
    log(`  Name: ${hotelInfo.HotelName}`, colors.blue);
    log(`  Code: ${hotelInfo.HotelCode}`, colors.blue);
    log(`  Address: ${hotelInfo.Address}`, colors.blue);
    log(`  Star Rating: ${hotelInfo.StarRating}`, colors.blue);
    
    return hotelInfo;
  } catch (error) {
    log(`❌ GetHotelInfo failed for hotel ${hotelCode}`, colors.red);
    return null;
  }
}

async function testGetHotelRoomDetail(hotelCode: string) {
  log('\n' + '='.repeat(60), colors.yellow);
  log('TEST 4: GetHotelRoomDetail (Room Types)', colors.yellow);
  log('='.repeat(60), colors.yellow);

  try {
    const response = await makeHobseRequest('htl/GetHotelRoomDetail', {
      HotelCode: hotelCode,
    });
    
    const rooms = response.hobse?.response?.data || [];
    log(`\n📊 Total Room Types: ${rooms.length}`, colors.green);
    
    if (rooms.length > 0) {
      log('\nSample Rooms:', colors.cyan);
      rooms.slice(0, 3).forEach((room: any) => {
        log(`  - ${room.RoomTypeName} (${room.RoomTypeCode})`, colors.blue);
        log(`    Max Adults: ${room.MaxAdults}, Max Children: ${room.MaxChildren}`, colors.blue);
      });
    }
    
    return rooms;
  } catch (error) {
    log(`❌ GetHotelRoomDetail failed for hotel ${hotelCode}`, colors.red);
    return [];
  }
}

async function testGetAvailableRoomTariff(hotelCode: string) {
  log('\n' + '='.repeat(60), colors.yellow);
  log('TEST 5: GetAvailableRoomTariff (Search Availability)', colors.yellow);
  log('='.repeat(60), colors.yellow);

  const today = new Date();
  const checkIn = new Date(today);
  checkIn.setDate(today.getDate() + 30); // 30 days from now
  const checkOut = new Date(checkIn);
  checkOut.setDate(checkIn.getDate() + 2); // 2 night stay

  const checkInStr = checkIn.toISOString().split('T')[0];
  const checkOutStr = checkOut.toISOString().split('T')[0];

  try {
    const response = await makeHobseRequest('htl/GetAvailableRoomTariff', {
      HotelCode: hotelCode,
      CheckInDate: checkInStr,
      CheckOutDate: checkOutStr,
      RoomGuests: [
        {
          NoOfAdults: 2,
          NoOfChildren: 0,
          ChildrenAges: [],
        },
      ],
    });
    
    const rooms = response.hobse?.response?.data?.Rooms || [];
    log(`\n📊 Available Rooms: ${rooms.length}`, colors.green);
    log(`Check-in: ${checkInStr}, Check-out: ${checkOutStr}`, colors.cyan);
    
    if (rooms.length > 0) {
      log('\nSample Available Rooms:', colors.cyan);
      rooms.slice(0, 3).forEach((room: any) => {
        log(`  - ${room.RoomTypeName}`, colors.blue);
        log(`    Rate Plan: ${room.RatePlanName}`, colors.blue);
        log(`    Price: ${room.TotalFare?.Currency} ${room.TotalFare?.Amount}`, colors.green);
      });
    }
    
    return rooms;
  } catch (error) {
    log(`❌ GetAvailableRoomTariff failed for hotel ${hotelCode}`, colors.red);
    return [];
  }
}

async function runTests() {
  log('\n' + '═'.repeat(60), colors.yellow);
  log('🚀 HOBSE LIVE API TEST SUITE', colors.yellow);
  log('═'.repeat(60), colors.yellow);

  // Verify configuration
  log('\n🔧 Configuration Check:', colors.cyan);
  log(`Base URL: ${HOBSE_BASE_URL}`, colors.blue);
  log(`Client Token: ${HOBSE_CLIENT_TOKEN ? '✅ Set' : '❌ Missing'}`, HOBSE_CLIENT_TOKEN ? colors.green : colors.red);
  log(`Access Token: ${HOBSE_ACCESS_TOKEN ? '✅ Set' : '❌ Missing'}`, HOBSE_ACCESS_TOKEN ? colors.green : colors.red);
  log(`Product Token: ${HOBSE_PRODUCT_TOKEN ? '✅ Set' : '❌ Missing'}`, HOBSE_PRODUCT_TOKEN ? colors.green : colors.red);

  if (!HOBSE_BASE_URL || !HOBSE_CLIENT_TOKEN || !HOBSE_ACCESS_TOKEN || !HOBSE_PRODUCT_TOKEN) {
    log('\n❌ Missing HOBSE configuration in .env file', colors.red);
    process.exit(1);
  }

  let testsPassed = 0;
  let testsFailed = 0;

  try {
    // Test 1: Get Cities
    const cities = await testGetCityDetail();
    if (cities.length > 0) {
      testsPassed++;
      log('✅ TEST 1 PASSED', colors.green);
    } else {
      testsFailed++;
      log('❌ TEST 1 FAILED', colors.red);
    }

    // Use first city for subsequent tests
    const cityCode = cities[0]?.CityCode;
    if (!cityCode) {
      log('\n❌ No cities found, cannot continue', colors.red);
      return;
    }

    // Test 2: Get Hotels for City
    const hotels = await testGetHotelList(cityCode);
    if (hotels.length > 0) {
      testsPassed++;
      log('✅ TEST 2 PASSED', colors.green);
    } else {
      testsFailed++;
      log('❌ TEST 2 FAILED', colors.red);
    }

    // Use first hotel for subsequent tests
    const hotelCode = hotels[0]?.HotelCode;
    if (!hotelCode) {
      log('\n❌ No hotels found, cannot continue', colors.red);
      return;
    }

    // Test 3: Get Hotel Info
    const hotelInfo = await testGetHotelInfo(hotelCode);
    if (hotelInfo) {
      testsPassed++;
      log('✅ TEST 3 PASSED', colors.green);
    } else {
      testsFailed++;
      log('❌ TEST 3 FAILED', colors.red);
    }

    // Test 4: Get Room Details
    const rooms = await testGetHotelRoomDetail(hotelCode);
    if (rooms.length > 0) {
      testsPassed++;
      log('✅ TEST 4 PASSED', colors.green);
    } else {
      testsFailed++;
      log('❌ TEST 4 FAILED', colors.red);
    }

    // Test 5: Get Available Room Tariff
    const availableRooms = await testGetAvailableRoomTariff(hotelCode);
    if (availableRooms.length > 0) {
      testsPassed++;
      log('✅ TEST 5 PASSED', colors.green);
    } else {
      testsFailed++;
      log('❌ TEST 5 FAILED', colors.red);
    }

  } catch (error) {
    log(`\n❌ Test suite error: ${error}`, colors.red);
  }

  // Summary
  log('\n' + '═'.repeat(60), colors.yellow);
  log('📊 TEST SUMMARY', colors.yellow);
  log('═'.repeat(60), colors.yellow);
  log(`Total Tests: ${testsPassed + testsFailed}`, colors.cyan);
  log(`✅ Passed: ${testsPassed}`, colors.green);
  log(`❌ Failed: ${testsFailed}`, colors.red);
  log(`Success Rate: ${Math.round((testsPassed / (testsPassed + testsFailed)) * 100)}%`, colors.cyan);

  if (testsPassed === 5) {
    log('\n🎉 All tests passed! HOBSE API integration is working!', colors.green);
  } else {
    log('\n⚠️  Some tests failed. Check the logs above for details.', colors.yellow);
  }
}

// Run tests
runTests().catch((error) => {
  log(`\n❌ Fatal error: ${error}`, colors.red);
  process.exit(1);
});
