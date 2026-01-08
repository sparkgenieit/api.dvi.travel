/**
 * Debug TBO API Issues
 * Figure out why we're getting 500 errors
 */

const axios = require('axios');

async function testTboApi() {
  console.log(`\n${'='.repeat(80)}`);
  console.log(`DEBUGGING TBO API - Why 500 Error?`);
  console.log(`${'='.repeat(80)}\n`);

  const basicAuth = Buffer.from('TBOApi:TBOApi@123').toString('base64');

  // Test 1: Current payload (that's failing)
  console.log(`❌ TEST 1: Current Payload (Returns 500)\n`);
  const payload1 = {
    "CheckIn": "2026-03-26",
    "CheckOut": "2026-03-27",
    "HotelCodes": "",
    "CityCode": "126117",
    "GuestNationality": "IN",
    "PaxRooms": [
      { "Adults": 2, "Children": 0, "ChildrenAges": [] }
    ],
    "ResponseTime": 23.0,
    "IsDetailedResponse": true,
    "Filters": {
      "Refundable": true,
      "NoOfRooms": 0,
      "MealType": "WithMeal",
      "OrderBy": 0,
      "StarRating": 0,
      "HotelName": null
    }
  };
  
  console.log(`Payload:`);
  console.log(JSON.stringify(payload1, null, 2));
  console.log(`\nIssues to check:`);
  console.log(`  1. CityCode: 126117 - Valid? Check if exists in TBO`);
  console.log(`  2. Dates: 2026-03-26 to 2026-03-27 - Valid future date? ✅`);
  console.log(`  3. HotelCodes: "" (empty) - Should search all hotels ✅`);
  console.log(`  4. Authorization: Basic ${basicAuth} ✅\n`);

  // Test 2: Simplified payload
  console.log(`\n✅ TEST 2: Simplified Payload (Most likely to work)\n`);
  const payload2 = {
    "CheckIn": "2026-03-26",
    "CheckOut": "2026-03-27",
    "CityCode": "126117",
    "GuestNationality": "IN",
    "PaxRooms": [
      { "Adults": 2, "Children": 0 }
    ],
    "IsDetailedResponse": true
  };
  
  console.log(`Payload:`);
  console.log(JSON.stringify(payload2, null, 2));
  console.log(`\nChanges: Removed optional fields (Filters, HotelCodes, ResponseTime)\n`);

  // Test 3: Check if city code is valid
  console.log(`\n🔍 TEST 3: Possible Issues\n`);
  console.log(`Issue A: City Code Invalid`);
  console.log(`  - CityCode 126117 might not be valid for TBO`);
  console.log(`  - Should verify in TBO master database\n`);
  
  console.log(`Issue B: Request Format Wrong`);
  console.log(`  - Missing required field`);
  console.log(`  - Wrong data type`);
  console.log(`  - Extra invalid fields\n`);

  console.log(`Issue C: Authorization Wrong`);
  console.log(`  - Authorization: Basic ${basicAuth}`);
  console.log(`  - Decoded: TBOApi:TBOApi@123`);
  console.log(`  - Status: ✅ Looks correct\n`);

  // Test 4: Try different dates (further in future)
  console.log(`\n📅 TEST 4: Try Different Dates (More in Future)\n`);
  const payload3 = {
    "CheckIn": "2026-04-15",
    "CheckOut": "2026-04-16",
    "CityCode": "126117",
    "GuestNationality": "IN",
    "PaxRooms": [
      { "Adults": 2, "Children": 0 }
    ],
    "IsDetailedResponse": true
  };
  
  console.log(`Changed dates to: 2026-04-15 to 2026-04-16`);
  console.log(`Payload:`);
  console.log(JSON.stringify(payload3, null, 2));

  // Test 5: Try with common city code
  console.log(`\n🏙️  TEST 5: Try With Delhi City Code\n`);
  const payload4 = {
    "CheckIn": "2026-04-15",
    "CheckOut": "2026-04-16",
    "CityCode": "1",
    "GuestNationality": "IN",
    "PaxRooms": [
      { "Adults": 2, "Children": 0 }
    ],
    "IsDetailedResponse": true
  };
  
  console.log(`Changed CityCode to: 1 (Delhi - most common)`);
  console.log(`Payload:`);
  console.log(JSON.stringify(payload4, null, 2));

  console.log(`\n${'='.repeat(80)}`);
  console.log(`RECOMMENDATIONS`);
  console.log(`${'='.repeat(80)}\n`);
  
  console.log(`1. First: Try simplified payload with fewer fields`);
  console.log(`2. Second: Try with Delhi (CityCode: 1) instead of Mahabalipuram (126117)`);
  console.log(`3. Third: Verify if city code 126117 is valid in TBO's system`);
  console.log(`4. Fourth: Check if dates are too far in future\n`);

  console.log(`ACTION: Update Bruno requests with simplified payloads\n`);
}

testTboApi();
