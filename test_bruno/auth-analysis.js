/**
 * Authorization Analysis for TBO API
 * 
 * Shows how the backend generates the Authorization header
 */

// The credentials are hardcoded in the TBO provider
const TBO_SEARCH_USERNAME = 'TBOApi';
const TBO_SEARCH_PASSWORD = 'TBOApi@123';

// Step 1: Combine username and password
const credentials = `${TBO_SEARCH_USERNAME}:${TBO_SEARCH_PASSWORD}`;
console.log(`\n1️⃣  Combined Credentials:`);
console.log(`   ${credentials}\n`);

// Step 2: Encode to Base64
const basicAuthEncoded = Buffer.from(credentials).toString('base64');
console.log(`2️⃣  Base64 Encoded:`);
console.log(`   ${basicAuthEncoded}\n`);

// Step 3: Build Authorization header
const authHeader = `Basic ${basicAuthEncoded}`;
console.log(`3️⃣  Authorization Header:`);
console.log(`   ${authHeader}\n`);

// Show it as it appears in code
console.log(`4️⃣  In Code (tbo-hotel.provider.ts):`);
console.log(`\n   const basicAuth = Buffer.from('TBOApi:TBOApi@123').toString('base64');`);
console.log(`   // Result: ${basicAuthEncoded}\n`);

console.log(`   const response = await this.http.post(`);
console.log(`     '${process.env.SEARCH_API_URL || 'https://affiliate.tektravels.com/HotelAPI'}/Search',`);
console.log(`     searchRequest,`);
console.log(`     {`);
console.log(`       timeout: 30000,`);
console.log(`       headers: {`);
console.log(`         'Content-Type': 'application/json',`);
console.log(`         'Authorization': 'Basic ${basicAuthEncoded}',`);
console.log(`       },`);
console.log(`     }`);
console.log(`   );\n`);

// Verification: Decode back
console.log(`5️⃣  Verification (Decode back):`);
const decoded = Buffer.from(basicAuthEncoded, 'base64').toString('utf-8');
console.log(`   Decoded: ${decoded}`);
console.log(`   ✅ Matches original: ${decoded === credentials}\n`);

// Show environment variables
console.log(`6️⃣  Environment Variables (from .env or defaults):`);
console.log(`\n   TBO_USERNAME = process.env.TBO_USERNAME || 'Doview'`);
console.log(`   TBO_PASSWORD = process.env.TBO_PASSWORD || 'Doview@12345'\n`);

console.log(`   ^ These are for AUTHENTICATION endpoint (SharedData API)`);
console.log(`   ^ NOT for SEARCH endpoint\n`);

// Show the difference
console.log(`7️⃣  Summary of Two Different Authentications:\n`);
console.log(`   📝 AUTHENTICATION Endpoint (SharedData API):`);
console.log(`      - URL: https://sharedapi.tektravels.com/SharedData.svc/rest/Authenticate`);
console.log(`      - Credentials in BODY (JSON):`);
console.log(`        {`);
console.log(`          "ClientId": "ApiIntegrationNew",`);
console.log(`          "UserName": "Doview",`);
console.log(`          "Password": "Doview@12345",`);
console.log(`          "EndUserIp": "192.168.1.1"`);
console.log(`        }`);
console.log(`      - Returns: TokenId\n`);

console.log(`   🔍 SEARCH Endpoint (Hotel Search API):`);
console.log(`      - URL: https://affiliate.tektravels.com/HotelAPI/Search`);
console.log(`      - Credentials in HEADER (Basic Auth):`);
console.log(`        Authorization: Basic VEJPQXBpOlRCT0FwaUAxMjM=`);
console.log(`        (Base64 encoded: TBOApi:TBOApi@123)`);
console.log(`      - Does NOT use TokenId`);
console.log(`      - Uses separate hardcoded credentials\n`);

console.log(`════════════════════════════════════════════════════════════\n`);
