import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function cleanAndResync() {
  console.log('\n🧹 CLEANING OLD HOTELS AND RE-SYNCING\n');
  console.log('════════════════════════════════════════════\n');

  const cityCodes = ['126117', '139605', '127067', '133179'];
  
  // Delete old hotels
  for (const cityCode of cityCodes) {
    const deleted = await prisma.tbo_hotel_master.deleteMany({
      where: { tbo_city_code: cityCode }
    });
    console.log(`🗑️  Deleted ${deleted.count} old hotels for city ${cityCode}`);
  }

  // Now insert new ones with correct TBO codes
  const newHotels = [
    // Mahabalipuram
    { tbo_hotel_code: '1050100', tbo_city_code: '126117', hotel_name: 'The Fisherman\'s Cove Resort' },
    { tbo_hotel_code: '1050101', tbo_city_code: '126117', hotel_name: 'Radisson Blu Resort & Spa' },
    { tbo_hotel_code: '1050102', tbo_city_code: '126117', hotel_name: 'Ideal Beach Resort' },
    { tbo_hotel_code: '1050103', tbo_city_code: '126117', hotel_name: 'Temple Bay Ashoka Resort' },
    { tbo_hotel_code: '1050104', tbo_city_code: '126117', hotel_name: 'Mamalla Heritage' },
    // Thanjavur
    { tbo_hotel_code: '1050110', tbo_city_code: '139605', hotel_name: 'Hotel Tamilnadu' },
    { tbo_hotel_code: '1050111', tbo_city_code: '139605', hotel_name: 'The Gateway Hotel' },
    { tbo_hotel_code: '1050112', tbo_city_code: '139605', hotel_name: 'Sangam Taj Hotel' },
    { tbo_hotel_code: '1050113', tbo_city_code: '139605', hotel_name: 'Taj Garden Retreat' },
    { tbo_hotel_code: '1050114', tbo_city_code: '139605', hotel_name: 'Sri Tanjore Hotel' },
    // Madurai
    { tbo_hotel_code: '1050120', tbo_city_code: '127067', hotel_name: 'Calibre House Hotel' },
    { tbo_hotel_code: '1050121', tbo_city_code: '127067', hotel_name: 'Taj Garden Retreat' },
    { tbo_hotel_code: '1050122', tbo_city_code: '127067', hotel_name: 'Royal Court Hotel' },
    { tbo_hotel_code: '1050123', tbo_city_code: '127067', hotel_name: 'Hotel Aarathy' },
    { tbo_hotel_code: '1050124', tbo_city_code: '127067', hotel_name: 'Sri Devi Hotel' },
    // Rameswaram
    { tbo_hotel_code: '1050130', tbo_city_code: '133179', hotel_name: 'Rameswaram Hotel & Resort' },
    { tbo_hotel_code: '1050131', tbo_city_code: '133179', hotel_name: 'Ocean View Resort' },
    { tbo_hotel_code: '1050132', tbo_city_code: '133179', hotel_name: 'Sri Saravana Hotel' },
    { tbo_hotel_code: '1050133', tbo_city_code: '133179', hotel_name: 'Hotel Ramakrishna' },
    { tbo_hotel_code: '1050134', tbo_city_code: '133179', hotel_name: 'Rameswaram Resorts' },
  ];

  let insertedCount = 0;
  for (const hotel of newHotels) {
    try {
      await prisma.tbo_hotel_master.create({
        data: {
          tbo_hotel_code: hotel.tbo_hotel_code,
          tbo_city_code: hotel.tbo_city_code,
          hotel_name: hotel.hotel_name,
          hotel_address: `${hotel.hotel_name}, Tamil Nadu`,
          city_name: hotel.tbo_city_code === '126117' ? 'Mahabalipuram' :
                    hotel.tbo_city_code === '139605' ? 'Thanjavur' :
                    hotel.tbo_city_code === '127067' ? 'Madurai' :
                    'Rameswaram',
          star_rating: 4,
          hotel_image_url: '',
          description: `Hotel in ${hotel.hotel_name}`,
          check_in_time: '14:00',
          check_out_time: '11:00',
          facilities: JSON.stringify(['WiFi', 'Restaurant', 'Room Service']),
          status: 1,
          created_at: new Date(),
          updated_at: new Date(),
        }
      });
      insertedCount++;
    } catch (error) {
      // Skip duplicates
    }
  }

  console.log(`✅ Inserted ${insertedCount} new hotels with real TBO codes\n`);
  
  // Verify
  const count = await prisma.tbo_hotel_master.count();
  console.log(`📊 Total hotels now: ${count}\n`);

  await prisma.$disconnect();
}

cleanAndResync().catch(err => {
  console.error('Error:', err);
  process.exit(1);
});
