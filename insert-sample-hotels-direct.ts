import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function insertSampleHotels() {
  console.log('\n🏨 INSERTING SAMPLE HOTELS DIRECTLY INTO DATABASE\n');
  console.log('════════════════════════════════════════════\n');

  const sampleHotels = [
    // Mahabalipuram hotels
    {
      tbo_hotel_code: 'MAH_001',
      tbo_city_code: '126117',
      hotel_name: 'The Fisherman\'s Cove Resort',
      hotel_address: 'Mahabalipuram Beach, Chennai',
      city_name: 'Mahabalipuram',
      star_rating: 4,
    },
    {
      tbo_hotel_code: 'MAH_002',
      tbo_city_code: '126117',
      hotel_name: 'Radisson Blu Resort & Spa',
      hotel_address: 'Mahabalipuram, Tamil Nadu',
      city_name: 'Mahabalipuram',
      star_rating: 5,
    },
    {
      tbo_hotel_code: 'MAH_003',
      tbo_city_code: '126117',
      hotel_name: 'Ideal Beach Resort',
      hotel_address: 'Mahabalipuram Beach Road',
      city_name: 'Mahabalipuram',
      star_rating: 3,
    },
    // Thanjavur hotels
    {
      tbo_hotel_code: 'THJ_001',
      tbo_city_code: '139605',
      hotel_name: 'Hotel Tamilnadu',
      hotel_address: 'Thanjavur City Center',
      city_name: 'Thanjavur',
      star_rating: 3,
    },
    {
      tbo_hotel_code: 'THJ_002',
      tbo_city_code: '139605',
      hotel_name: 'The Gateway Hotel',
      hotel_address: 'Thanjavur, Tamil Nadu',
      city_name: 'Thanjavur',
      star_rating: 4,
    },
    {
      tbo_hotel_code: 'THJ_003',
      tbo_city_code: '139605',
      hotel_name: 'Sangam Taj Hotel',
      hotel_address: 'Thanjavur Palace Road',
      city_name: 'Thanjavur',
      star_rating: 4,
    },
    // Madurai hotels
    {
      tbo_hotel_code: 'MAD_001',
      tbo_city_code: '127067',
      hotel_name: 'Calibre House Hotel',
      hotel_address: 'Madurai City Center',
      city_name: 'Madurai',
      star_rating: 3,
    },
    {
      tbo_hotel_code: 'MAD_002',
      tbo_city_code: '127067',
      hotel_name: 'Taj Garden Retreat',
      hotel_address: 'Madurai, Tamil Nadu',
      city_name: 'Madurai',
      star_rating: 4,
    },
    {
      tbo_hotel_code: 'MAD_003',
      tbo_city_code: '127067',
      hotel_name: 'Royal Court Hotel',
      hotel_address: 'Madurai Business District',
      city_name: 'Madurai',
      star_rating: 4,
    },
    // Rameswaram hotels
    {
      tbo_hotel_code: 'RAM_001',
      tbo_city_code: '133179',
      hotel_name: 'Rameswaram Hotel & Resort',
      hotel_address: 'Rameswaram Beach Road',
      city_name: 'Rameswaram',
      star_rating: 3,
    },
    {
      tbo_hotel_code: 'RAM_002',
      tbo_city_code: '133179',
      hotel_name: 'Ocean View Resort',
      hotel_address: 'Rameswaram, Tamil Nadu',
      city_name: 'Rameswaram',
      star_rating: 4,
    },
    {
      tbo_hotel_code: 'RAM_003',
      tbo_city_code: '133179',
      hotel_name: 'Sri Saravana Hotel',
      hotel_address: 'Rameswaram Temple Road',
      city_name: 'Rameswaram',
      star_rating: 3,
    },
  ];

  let insertedCount = 0;
  for (const hotel of sampleHotels) {
    try {
      await prisma.tbo_hotel_master.upsert({
        where: { tbo_hotel_code: hotel.tbo_hotel_code },
        create: {
          tbo_hotel_code: hotel.tbo_hotel_code,
          tbo_city_code: hotel.tbo_city_code,
          hotel_name: hotel.hotel_name,
          hotel_address: hotel.hotel_address,
          city_name: hotel.city_name,
          star_rating: hotel.star_rating,
          hotel_image_url: '',
          description: `Hotel in ${hotel.city_name}`,
          check_in_time: '14:00',
          check_out_time: '11:00',
          facilities: JSON.stringify(['WiFi', 'Restaurant', 'Room Service']),
          status: 1,
          created_at: new Date(),
          updated_at: new Date(),
        },
        update: { updated_at: new Date() },
      });
      insertedCount++;
      console.log(`✅ ${hotel.hotel_name}`);
    } catch (error) {
      const msg = error instanceof Error ? error.message : String(error);
      console.error(`❌ ${hotel.hotel_name}: ${msg.substring(0, 50)}`);
    }
  }

  console.log('\n════════════════════════════════════════════');
  console.log(`✅ Inserted/Updated ${insertedCount} hotels\n`);

  await prisma.$disconnect();
}

insertSampleHotels().catch(err => {
  console.error('Fatal error:', err);
  process.exit(1);
});
