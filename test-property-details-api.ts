/**
 * Test PropertyDetails API using official documentation format
 * Testing hotels 261, 285, and 1098
 */
import axios from 'axios';

const BASE_URL = 'http://203.109.97.241:8080/ChannelController';

interface PropertyDetailsRequest {
  OTA_HotelDetailsRQ: {
    POS: {
      Username: string;
      Password: string;
      ID_Context: string;
    };
    TimeStamp: string;
    EchoToken: string;
    HotelCode: string;
    HotelName?: string;
  };
}

async function testPropertyDetails(hotelCode: string, hotelName: string) {
  console.log(`\n${'='.repeat(60)}`);
  console.log(`Testing Property Details for Hotel ${hotelCode} (${hotelName})`);
  console.log(`${'='.repeat(60)}`);

  const request: PropertyDetailsRequest = {
    OTA_HotelDetailsRQ: {
      POS: {
        Username: 'testpmsk4@resavenue.com',
        Password: 'testpms@123',
        ID_Context: 'REV',
      },
      TimeStamp: '20151015T15:22:50',
      EchoToken: `test-${Date.now()}`,
      HotelCode: hotelCode,
      HotelName: hotelName,
    },
  };

  console.log('\n📤 Request:');
  console.log(JSON.stringify(request, null, 2));

  try {
    const response = await axios.post(`${BASE_URL}/PropertyDetails`, request, {
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
    });

    console.log('\n✅ SUCCESS! Status:', response.status);
    console.log('\n📥 Response:');
    console.log(JSON.stringify(response.data, null, 2));

    // Parse room types and rate plans
    const hotelDetails = response.data?.OTA_HotelDetailsRS?.[0];
    
    if (hotelDetails) {
      const roomTypes = hotelDetails.RoomTypes || [];
      
      console.log('\n📊 SUMMARY:');
      console.log(`Hotel: ${hotelDetails.HotelDetail?.hotel_name || hotelName}`);
      console.log(`Total Room Types: ${roomTypes.length}`);

      if (roomTypes.length > 0) {
        console.log('\n🏠 Room Types & Rate Plans:');
        console.log('─'.repeat(60));

        const invCodes: number[] = [];
        const rateCodes: number[] = [];

        roomTypes.forEach((room: any, idx: number) => {
          console.log(`\n${idx + 1}. Room ID: ${room.room_id} - ${room.room_name}`);
          console.log(`   Occupancy: Base ${room.base_occupancy} / Max ${room.max_occupancy}`);
          console.log(`   Status: ${room.room_status}`);
          
          invCodes.push(room.room_id);

          if (room.RatePlans && room.RatePlans.length > 0) {
            console.log(`   Rate Plans (${room.RatePlans.length}):`);
            room.RatePlans.forEach((rate: any, rIdx: number) => {
              console.log(`     ${rIdx + 1}. Rate ID: ${rate.rate_id} - ${rate.rate_name}`);
              console.log(`        Valid: ${rate.valid_from} to ${rate.valid_to}`);
              console.log(`        Range: ₹${rate.min_rate} - ₹${rate.max_rate}`);
              console.log(`        Status: ${rate.rate_status}`);
              
              rateCodes.push(rate.rate_id);
            });
          }
        });

        console.log('\n🔧 HOTEL_CONFIG Entry:');
        console.log('─'.repeat(60));
        console.log(`'${hotelCode}': {`);
        console.log(`  invCodes: [${invCodes.join(', ')}],`);
        console.log(`  rateCodes: [${rateCodes.join(', ')}]`);
        console.log(`},`);
      } else {
        console.log('\n⚠️  No room types found');
      }
    }

    return { success: true, data: response.data };
  } catch (error: any) {
    console.log('\n❌ FAILED! Status:', error.response?.status || 'N/A');
    console.log('Error:', error.response?.data || error.message);
    
    return { success: false, error: error.response?.data || error.message };
  }
}

async function main() {
  console.log('╔══════════════════════════════════════════════════════════╗');
  console.log('║  PropertyDetails API Test (Official Documentation)      ║');
  console.log('╚══════════════════════════════════════════════════════════╝');
  console.log('\nTesting OTA_HotelDetailsRQ API for all 3 hotels...\n');

  const results = [];

  // Test all 3 hotels
  results.push(await testPropertyDetails('261', 'PMS Test Hotel'));
  results.push(await testPropertyDetails('1098', 'TMahal Palace'));
  results.push(await testPropertyDetails('285', 'TM Globus'));

  // Final summary
  console.log('\n\n╔══════════════════════════════════════════════════════════╗');
  console.log('║  FINAL SUMMARY                                           ║');
  console.log('╚══════════════════════════════════════════════════════════╝\n');

  const successful = results.filter(r => r.success).length;
  console.log(`✅ Successful: ${successful}/3 hotels`);
  console.log(`❌ Failed: ${3 - successful}/3 hotels\n`);

  if (successful === 3) {
    console.log('🎉 All hotels have PropertyDetails! Update HOTEL_CONFIG with the codes above.\n');
  } else if (successful > 0) {
    console.log('⚠️  Some hotels have PropertyDetails. Update HOTEL_CONFIG for working hotels.\n');
  } else {
    console.log('❌ PropertyDetails API not working with these credentials.');
    console.log('   Possible reasons:');
    console.log('   1. API not enabled for sandbox account');
    console.log('   2. Hotels not configured in ResAvenue PMS');
    console.log('   3. Need different authentication\n');
    console.log('💡 Fallback: Use existing codes from test-inventory-rate-apis.ts for hotel 261.\n');
  }

  console.log('═'.repeat(60) + '\n');
}

main().catch((error) => {
  console.error('Fatal error:', error);
  process.exit(1);
});
