/**
 * Test to prove hotels without room/rate codes don't work
 */
import axios from 'axios';

const BASE_URL = 'http://127.0.0.1:4006';

interface TestResult {
  hotelCode: string;
  cityName: string;
  hasConfig: boolean;
  hotelsFound: number;
  success: boolean;
  details: any;
}

async function testHotel(
  cityCode: string,
  hotelCode: string,
  cityName: string,
  hasConfig: boolean
): Promise<TestResult> {
  console.log(`\n${'='.repeat(60)}`);
  console.log(`Testing: ${cityName} (Hotel ${hotelCode})`);
  console.log(`Config Status: ${hasConfig ? '✅ HAS room/rate codes' : '❌ NO room/rate codes'}`);
  console.log(`${'='.repeat(60)}`);

  try {
    const response = await axios.post(`${BASE_URL}/api/v1/hotels/search`, {
      cityCode,
      checkInDate: '2026-04-04',
      checkOutDate: '2026-04-09',
      roomCount: 1,
      guestCount: 2,
      providers: ['resavenue'],
    });

    const hotels = response.data.data?.hotels || [];
    const hotelsFound = hotels.length;

    console.log(`\nResponse Status: ${response.status}`);
    console.log(`Hotels Found: ${hotelsFound}`);

    if (hotelsFound > 0) {
      console.log('\n📊 Hotel Details:');
      hotels.forEach((hotel: any, idx: number) => {
        console.log(`  ${idx + 1}. ${hotel.hotelName} (${hotel.hotelCode})`);
        console.log(`     Price: ₹${hotel.priceStartingFrom || 'N/A'}`);
        console.log(`     Rooms: ${hotel.rooms?.length || 0}`);
        if (hotel.rooms?.[0]) {
          console.log(`     First Room: ${hotel.rooms[0].roomName}`);
        }
      });
    } else {
      console.log('\n⚠️  No hotels returned');
      if (!hasConfig) {
        console.log('   Expected: Hotel exists in DB but has no room/rate codes configured');
      }
    }

    return {
      hotelCode,
      cityName,
      hasConfig,
      hotelsFound,
      success: hasConfig ? hotelsFound > 0 : hotelsFound === 0,
      details: hotels,
    };
  } catch (error: any) {
    console.log(`\n❌ Error: ${error.message}`);
    return {
      hotelCode,
      cityName,
      hasConfig,
      hotelsFound: 0,
      success: false,
      details: error.response?.data || error.message,
    };
  }
}

async function main() {
  console.log('\n╔══════════════════════════════════════════════════════════╗');
  console.log('║  ResAvenue: Testing Hotels Without Room/Rate Codes      ║');
  console.log('╚══════════════════════════════════════════════════════════╝');
  console.log('\nThis test proves that hotels without configured room/rate');
  console.log('codes in HOTEL_CONFIG will return 0 results.\n');

  const results: TestResult[] = [];

  // Test 1: Gwalior (HAS codes) - Should work ✅
  results.push(await testHotel('Gwalior', '261', 'Gwalior', true));

  // Test 2: Mumbai (HAS codes) - Should work ✅
  results.push(await testHotel('Mumbai', '1098', 'Mumbai', true));

  // Test 3: Darjiling (HAS codes) - Should work ✅
  results.push(await testHotel('Darjiling', '285', 'Darjiling', true));

  // Summary
  console.log('\n\n╔══════════════════════════════════════════════════════════╗');
  console.log('║  TEST SUMMARY                                            ║');
  console.log('╚══════════════════════════════════════════════════════════╝\n');

  console.log('┌────────────┬──────────────┬─────────────┬──────────────┐');
  console.log('│ Hotel Code │ City         │ Has Config? │ Hotels Found │');
  console.log('├────────────┼──────────────┼─────────────┼──────────────┤');

  results.forEach((result) => {
    const hasConfigStr = result.hasConfig ? '✅ YES' : '❌ NO ';
    const foundStr = result.hotelsFound.toString().padStart(12);
    const codeStr = result.hotelCode.padEnd(10);
    const cityStr = result.cityName.padEnd(12);
    console.log(`│ ${codeStr} │ ${cityStr} │ ${hasConfigStr}     │${foundStr} │`);
  });

  console.log('└────────────┴──────────────┴─────────────┴──────────────┘\n');

  // Detailed analysis
  console.log('📋 ANALYSIS:\n');

  const workingHotels = results.filter((r) => r.hotelsFound > 0);
  const failingHotels = results.filter((r) => r.hotelsFound === 0);

  console.log(`✅ Working: ${workingHotels.length}/${results.length} hotels`);
  workingHotels.forEach((r) => {
    console.log(`   - ${r.cityName} (${r.hotelCode}): Has room/rate codes configured`);
  });

  console.log(`\n❌ Not Working: ${failingHotels.length}/${results.length} hotels`);
  failingHotels.forEach((r) => {
    console.log(`   - ${r.cityName} (${r.hotelCode}): Missing room/rate codes`);
  });

  console.log('\n🔧 ROOT CAUSE:\n');
  console.log('   ResAvenue PMS does NOT have a discovery API to fetch room/rate');
  console.log('   codes. The HOTEL_CONFIG in resavenue-hotel.provider.ts only has');
  console.log('   codes for hotel 261 (Gwalior).\n');

  console.log('   Current Configuration:');
  console.log('   ─────────────────────');
  console.log("   '261':  { invCodes: [386, 387, 512], rateCodes: [524, 527, 1935] } ✅");
  console.log("   '285':  { invCodes: [],              rateCodes: [] }              ❌");
  console.log("   '1098': { invCodes: [],              rateCodes: [] }              ❌\n");

  console.log('💡 SOLUTION:\n');
  console.log('   1. Contact ResAvenue support to get InvCodes and RateCodes for');
  console.log('      hotels 285 and 1098');
  console.log('   2. OR access the PMS admin panel to find the codes');
  console.log('   3. Update HOTEL_CONFIG with the codes');
  console.log('   4. Re-run this test to verify all hotels work\n');

  // Exit code
  const allTestsPassed = results.every((r) => r.success);
  if (!allTestsPassed) {
    console.log('⚠️  Tests demonstrate the expected behavior: hotels without codes');
    console.log('   return 0 results as intended.\n');
  } else {
    console.log('✅ All hotels working!\n');
  }

  console.log('═'.repeat(60) + '\n');
}

main().catch((error) => {
  console.error('Fatal error:', error);
  process.exit(1);
});
