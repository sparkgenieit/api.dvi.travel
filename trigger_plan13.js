const axios = require('axios');

async function triggerPlan13() {
  try {
    const response = await axios.post('http://localhost:3000/api/itineraries/plans/13/sync-changes', {}, {
      headers: {
        'Content-Type': 'application/json',
      },
    });

    console.log('Response:', response.status, response.statusText);
    console.log('Data:', JSON.stringify(response.data, null, 2));
  } catch (error) {
    console.error('Error:', error.response?.data || error.message);
  }
}

triggerPlan13();
