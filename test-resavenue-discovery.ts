/**
 * Test if ResAvenue Inventory/Rate APIs work without specifying codes
 */
import axios from 'axios';

const BASE_URL = 'http://203.109.97.241:8080/ChannelController';

async function testInventoryWithoutCodes() {
  console.log('Testing Inventory API WITHOUT InvCodes...\n');
  
  try {
    const response = await axios.post(
      `${BASE_URL}/PropertyDetails`,
      {
        OTA_HotelInventoryRQ: {
          POS: {
            Username: 'testpmsk4@resavenue.com',
            Password: 'testpms@123',
            ID_Context: 'REV',
          },
          TimeStamp: '20261015T15:22:50',
          EchoToken: 'test',
          HotelCode: '261',
          Start: '2026-04-04',
          End: '2026-04-09',
          // NO InvCodes specified
        },
      },
      { headers: { 'Content-Type': 'application/json' } }
    );

    console.log('✅ Status:', response.status);
    const inventories = response.data?.OTA_HotelInventoryRS?.Inventories;
    console.log('Inventories found:', inventories?.length || 0);
    
    if (inventories && inventories.length > 0) {
      console.log('\n📊 Inventory Codes discovered:');
      inventories.forEach((inv: any, idx: number) => {
        console.log(`  ${idx + 1}. InvCode: ${inv.InvCode}`);
      });
    }
    
    return inventories;
  } catch (error: any) {
    console.log('❌ Error:', error.response?.status || error.message);
    console.log('Response:', JSON.stringify(error.response?.data, null, 2));
    return null;
  }
}

async function testRatesWithoutCodes() {
  console.log('\n\nTesting Rate API WITHOUT RateCodes...\n');
  
  try {
    const response = await axios.post(
      `${BASE_URL}/PropertyDetails`,
      {
        OTA_HotelRateRQ: {
          POS: {
            Username: 'testpmsk4@resavenue.com',
            Password: 'testpms@123',
            ID_Context: 'REV',
          },
          HotelCode: '261',
          Start: '2026-04-04',
          End: '2026-04-09',
          // NO RateCodes specified
        },
      },
      { headers: { 'Content-Type': 'application/json' } }
    );

    console.log('✅ Status:', response.status);
    const rates = response.data?.OTA_HotelRateRS?.Rates;
    console.log('Rates found:', rates?.length || 0);
    
    if (rates && rates.length > 0) {
      console.log('\n💰 Rate Codes discovered:');
      rates.forEach((rate: any, idx: number) => {
        console.log(`  ${idx + 1}. RateCode: ${rate.RateCode}`);
      });
    }
    
    return rates;
  } catch (error: any) {
    console.log('❌ Error:', error.response?.status || error.message);
    console.log('Response:', JSON.stringify(error.response?.data, null, 2));
    return null;
  }
}

async function main() {
  console.log('═══════════════════════════════════════════════════════════');
  console.log('  ResAvenue API Discovery Test');
  console.log('═══════════════════════════════════════════════════════════\n');
  
  const inventories = await testInventoryWithoutCodes();
  const rates = await testRatesWithoutCodes();
  
  console.log('\n═══════════════════════════════════════════════════════════');
  console.log('SUMMARY');
  console.log('═══════════════════════════════════════════════════════════');
  
  if (inventories && inventories.length > 0) {
    console.log('✅ Inventory API returns all rooms when InvCodes omitted');
  } else {
    console.log('❌ Inventory API requires InvCodes');
  }
  
  if (rates && rates.length > 0) {
    console.log('✅ Rate API returns all rates when RateCodes omitted');
  } else {
    console.log('❌ Rate API requires RateCodes');
  }
  
  console.log('\n');
}

main();
