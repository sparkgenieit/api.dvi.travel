import axios from 'axios';

const MASTER_API_URL = 'http://api.tbotechnology.in/TBOHolidays_HotelAPI';
const USERNAME = 'TBOStaticAPITest';
const PASSWORD = 'Tbo@11530818';

async function checkCityList() {
  try {
    // Step 1: Get CityList for India (no authentication needed for static data endpoints)
    console.log('🌍 Fetching CityList for India (CountryCode: IN)...\n');
    
    const cityListResponse = await axios.post(
      `${MASTER_API_URL}/CityList`,
      {
        CountryCode: 'IN'
      },
      {
        headers: {
          'Content-Type': 'application/json',
        },
        auth: {
          username: USERNAME,
          password: PASSWORD,
        },
        timeout: 30000,
      }
    );

    const cities = cityListResponse.data?.CityList || [];
    console.log(`📊 Total cities returned: ${cities.length}\n`);
    
    // Log the full response to see structure
    console.log('📋 Full Response:', JSON.stringify(cityListResponse.data, null, 2).substring(0, 1000));
    console.log('\n📋 Response keys:', Object.keys(cityListResponse.data));

    if (cities.length > 0) {
      // Search for Rameswaram
      const rameswaramVariants = cities.filter((city: any) => 
        city.Name && city.Name.toLowerCase().includes('rameswaram')
      );

      console.log('🔍 Searching for Rameswaram variants:\n');
      if (rameswaramVariants.length > 0) {
        console.log(`✅ Found ${rameswaramVariants.length} Rameswaram entry/entries:\n`);
        rameswaramVariants.forEach((city: any) => {
          console.log(`  Name: ${city.Name}`);
          console.log(`  Code: ${city.Code}`);
          console.log(`  Full Object: ${JSON.stringify(city)}\n`);
        });
      } else {
        console.log('❌ Rameswaram NOT FOUND in CityList API response\n');
      }

      // Also check for the specific city code we have in database
      console.log('🎯 Checking for city code 133179 in response:\n');
      const cityWithCode = cities.find((city: any) => city.Code === '133179');
      if (cityWithCode) {
        console.log(`✅ City with code 133179 found: ${cityWithCode.Name}`);
      } else {
        console.log('❌ City code 133179 NOT FOUND in response');
      }

      // Show some sample cities
      console.log('\n📋 Sample cities from Tamil Nadu region:\n');
      const tamilNaduCities = cities.filter((city: any) => 
        city.Name && (
          city.Name.includes('Tamil') || 
          city.Name.includes('Madurai') ||
          city.Name.includes('Chennai') ||
          city.Name.includes('Coimbatore')
        )
      ).slice(0, 10);
      
      tamilNaduCities.forEach((city: any) => {
        console.log(`  ${city.Code} - ${city.Name}`);
      });
    }

    // Check response structure
    console.log('\n📋 Response keys:', Object.keys(cityListResponse.data));
    
  } catch (error: any) {
    console.error('❌ Error:', error.message);
    if (error.response) {
      console.error('Response status:', error.response.status);
      console.error('Response data:', JSON.stringify(error.response.data).substring(0, 500));
    }
  }
}

checkCityList();
