import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:4006/api/v1';
const TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJhZ2VudElkIjowLCJzdGFmZklkIjowLCJndWlkZUlkIjowLCJpYXQiOjE3NjcyNDkzMjAsImV4cCI6MTc2Nzg1NDEyMH0.0-AJW4SWm1NFTzJFjEAe69-byHfu0X1sFmGwP_fTOmw';

async function syncTargetCities() {
  const cityCodes = ['126117', '139605', '127067', '133179'];
  const cityNames = ['Mahabalipuram', 'Thanjavur', 'Madurai', 'Rameswaram'];

  console.log('\n🚀 SYNCING HOTELS FOR TARGET CITIES\n');
  console.log('════════════════════════════════════════════\n');

  for (let i = 0; i < cityCodes.length; i++) {
    const cityCode = cityCodes[i];
    const cityName = cityNames[i];

    try {
      console.log(`🏨 Syncing hotels for: ${cityName} (Code: ${cityCode})`);
      
      const response = await axios.post(
        `${API_BASE_URL}/hotels/sync/city/${cityCode}`,
        {},
        { 
          timeout: 120000,
          headers: {
            'Authorization': `Bearer ${TOKEN}`,
            'Content-Type': 'application/json',
          }
        }
      );

      if (response.data.success) {
        console.log(`   ✅ SUCCESS: ${response.data.message}`);
      } else {
        console.log(`   ❌ FAILED: ${response.data.message}`);
      }
    } catch (error) {
      const msg = error instanceof Error ? error.message : String(error);
      console.error(`   ❌ ERROR: ${msg}`);
    }
    console.log('');
  }

  // Check count after sync
  console.log('\n📊 Checking updated hotel count...');
  try {
    const countResponse = await axios.get(`${API_BASE_URL}/hotels/sync/master-data/count`, {
      headers: {
        'Authorization': `Bearer ${TOKEN}`,
      }
    });
    console.log(`✅ ${countResponse.data.message}`);
  } catch (error) {
    console.error(`❌ Failed to get count`);
  }

  console.log('\n════════════════════════════════════════════');
  console.log('🎉 SYNC COMPLETED\n');
}

syncTargetCities().catch(err => {
  console.error('Error:', err.message);
  process.exit(1);
});
