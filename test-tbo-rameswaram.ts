import axios from 'axios';

const SHARED_API_URL = 'https://sharedapi.tektravels.com';
const SEARCH_API_URL = 'https://affiliate.tektravels.com/HotelAPI';
const USERNAME = 'Doview';
const PASSWORD = 'Doview@12345';

async function testRameswaramHotels() {
  try {
    // Step 1: Authenticate
    console.log('🔐 Authenticating with TBO API...\n');
    const authResponse = await axios.post(
      `${SHARED_API_URL}/SharedData.svc/rest/Authenticate`,
      {
        ClientId: 'ApiIntegrationNew',
        UserName: USERNAME,
        Password: PASSWORD,
        EndUserIp: '192.168.1.1',
      }
    );

    const tokenId = authResponse.data.TokenId;
    console.log(`✅ Got TokenId: ${tokenId.substring(0, 15)}...\n`);

    // Step 2: Search Rameswaram hotels
    console.log('🔍 Searching for Rameswaram hotels...\n');
    
    const searchRequest = {
      CheckInDate: '2026-02-20',
      CheckOutDate: '2026-02-21',
      CountryCode: 'IN',
      CityId: '133179', // Rameswaram
      GuestNationality: 'IN',
      PaxRooms: [
        {
          Adults: 2,
          Children: 0,
          ChildrenAges: [],
        },
      ],
      ResponseTime: 23.0,
      IsDetailedResponse: true,
      Filters: {
        Refundable: false, // Try without refundable filter
        NoOfRooms: 1,
        MealType: 'All', // Try all meal types
        OrderBy: 0,
        StarRating: 0,
        HotelName: null,
      },
    };

    const searchResponse = await axios.post(
      `${SEARCH_API_URL}/Search`,
      searchRequest,
      {
        headers: {
          'Content-Type': 'application/json',
        },
        auth: {
          username: tokenId,
          password: '',
        },
      }
    );

    const hotels = searchResponse.data?.HotelResult?.Hotels || [];
    console.log(`📊 Total hotels returned: ${hotels.length}\n`);

    if (hotels.length > 0) {
      console.log('🏨 Sample hotels from TBO API Response:\n');
      
      // Check first 5 hotels
      for (let i = 0; i < Math.min(5, hotels.length); i++) {
        const hotel = hotels[i];
        console.log(`Hotel ${i + 1}:`);
        console.log(`  HotelCode: ${hotel.HotelCode}`);
        console.log(`  HotelName: ${hotel.HotelName || '(NOT PROVIDED)'}`);
        console.log(`  Keys: ${Object.keys(hotel).join(', ')}`);
        console.log(`  Full Object: ${JSON.stringify(hotel).substring(0, 200)}...\n`);
      }

      // Check if specific hotel codes are in the response
      const targetCodes = ['1687511', '5115360', '1839111', '1825083', '1138045'];
      console.log('\n🎯 Checking for specific hotel codes:\n');
      
      for (const code of targetCodes) {
        const found = hotels.find((h: any) => h.HotelCode === code);
        if (found) {
          console.log(`✅ ${code}: HotelName = "${found.HotelName || 'NOT PROVIDED'}"`);
        } else {
          console.log(`❌ ${code}: Not found in response`);
        }
      }
    }
  } catch (error: any) {
    console.error('❌ Error:', error.message);
    if (error.response) {
      console.error('Response data:', error.response.data);
    }
  }
}

testRameswaramHotels();
