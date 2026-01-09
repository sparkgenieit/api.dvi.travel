import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:4006/api/v1';
const TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJhZ2VudElkIjowLCJzdGFmZklkIjowLCJndWlkZUlkIjowLCJpYXQiOjE3NjcyNDkzMjAsImV4cCI6MTc2Nzg1NDEyMH0.0-AJW4SWm1NFTzJFjEAe69-byHfu0X1sFmGwP_fTOmw';

async function insertHotelsDirectly() {
  console.log('\n🏨 INSERTING SAMPLE HOTELS DIRECTLY INTO DATABASE\n');
  console.log('════════════════════════════════════════════\n');

  // Sample hotels for each city with realistic pricing
  const sampleHotels = [
    // Mahabalipuram hotels
    {
      tbo_hotel_code: 'MAH_001',
      tbo_city_code: '126117',
      hotel_name: 'The Fisherman\'s Cove Resort',
      hotel_address: 'Mahabalipuram Beach, Chennai',
      star_rating: 4,
      price: 4500,
    },
    {
      tbo_hotel_code: 'MAH_002',
      tbo_city_code: '126117',
      hotel_name: 'Radisson Blu Resort & Spa',
      hotel_address: 'Mahabalipuram, Tamil Nadu',
      star_rating: 5,
      price: 7500,
    },
    {
      tbo_hotel_code: 'MAH_003',
      tbo_city_code: '126117',
      hotel_name: 'Ideal Beach Resort',
      hotel_address: 'Mahabalipuram Beach Road',
      star_rating: 3,
      price: 2500,
    },
    // Thanjavur hotels
    {
      tbo_hotel_code: 'THJ_001',
      tbo_city_code: '139605',
      hotel_name: 'Hotel Tamilnadu',
      hotel_address: 'Thanjavur City Center',
      star_rating: 3,
      price: 2000,
    },
    {
      tbo_hotel_code: 'THJ_002',
      tbo_city_code: '139605',
      hotel_name: 'The Gateway Hotel',
      hotel_address: 'Thanjavur, Tamil Nadu',
      star_rating: 4,
      price: 4000,
    },
    {
      tbo_hotel_code: 'THJ_003',
      tbo_city_code: '139605',
      hotel_name: 'Sangam Taj Hotel',
      hotel_address: 'Thanjavur Palace Road',
      star_rating: 4,
      price: 3800,
    },
    // Madurai hotels
    {
      tbo_hotel_code: 'MAD_001',
      tbo_city_code: '127067',
      hotel_name: 'Calibre House Hotel',
      hotel_address: 'Madurai City Center',
      star_rating: 3,
      price: 2200,
    },
    {
      tbo_hotel_code: 'MAD_002',
      tbo_city_code: '127067',
      hotel_name: 'Taj Garden Retreat',
      hotel_address: 'Madurai, Tamil Nadu',
      star_rating: 4,
      price: 4200,
    },
    {
      tbo_hotel_code: 'MAD_003',
      tbo_city_code: '127067',
      hotel_name: 'Royal Court Hotel',
      hotel_address: 'Madurai Business District',
      star_rating: 4,
      price: 4000,
    },
    // Rameswaram hotels
    {
      tbo_hotel_code: 'RAM_001',
      tbo_city_code: '133179',
      hotel_name: 'Rameswaram Hotel & Resort',
      hotel_address: 'Rameswaram Beach Road',
      star_rating: 3,
      price: 2000,
    },
    {
      tbo_hotel_code: 'RAM_002',
      tbo_city_code: '133179',
      hotel_name: 'Ocean View Resort',
      hotel_address: 'Rameswaram, Tamil Nadu',
      star_rating: 4,
      price: 3800,
    },
    {
      tbo_hotel_code: 'RAM_003',
      tbo_city_code: '133179',
      hotel_name: 'Sri Saravana Hotel',
      hotel_address: 'Rameswaram Temple Road',
      star_rating: 3,
      price: 2300,
    },
  ];

  for (const hotel of sampleHotels) {
    try {
      // Call endpoint to insert hotel
      const response = await axios.post(
        `${API_BASE_URL}/hotels/add-master-hotel`,
        hotel,
        {
          headers: {
            'Authorization': `Bearer ${TOKEN}`,
            'Content-Type': 'application/json',
          }
        }
      );
      console.log(`✅ ${hotel.hotel_name} - ${hotel.tbo_city_code}`);
    } catch (error) {
      const msg = error instanceof Error ? error.message : String(error);
      console.log(`⚠️  ${hotel.hotel_name}: ${msg.substring(0, 50)}`);
    }
  }

  console.log('\n════════════════════════════════════════════\n');
  console.log('✅ Hotels insertion completed\n');
}

insertHotelsDirectly().catch(err => {
  console.error('Error:', err.message);
  process.exit(1);
});
