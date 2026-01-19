/**
 * Insert ResAvenue Test Hotels into Database
 * Run this script to add the 3 ResAvenue test properties
 */

import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

const RESAVENUE_TEST_HOTELS = [
  {
    hotel_name: 'PMS Test Hotel',
    hotel_code: 'RESAVENUE-261',
    resavenue_hotel_code: '261',
    hotel_city: 'Gwalior',
    hotel_state: 'Madhya Pradesh',
    hotel_country: 'India',
    hotel_category: 3, // 3-star
  },
  {
    hotel_name: 'TM Globus',
    hotel_code: 'RESAVENUE-285',
    resavenue_hotel_code: '285',
    hotel_city: 'Darjiling',
    hotel_state: 'West Bengal',
    hotel_country: 'India',
    hotel_category: 3, // 3-star
  },
  {
    hotel_name: 'TMahal Palace',
    hotel_code: 'RESAVENUE-1098',
    resavenue_hotel_code: '1098',
    hotel_city: 'Mumbai',
    hotel_state: 'Maharashtra',
    hotel_country: 'India',
    hotel_category: 4, // 4-star
  },
];

async function insertResAvenueHotels() {
  console.log('🏨 Inserting ResAvenue Test Hotels...\n');

  try {
    for (const hotel of RESAVENUE_TEST_HOTELS) {
      // Check if hotel already exists
      const existing = await prisma.dvi_hotel.findFirst({
        where: {
          resavenue_hotel_code: hotel.resavenue_hotel_code,
        },
      });

      if (existing) {
        console.log(`⚠️  Hotel "${hotel.hotel_name}" (Code: ${hotel.resavenue_hotel_code}) already exists (ID: ${existing.hotel_id})`);
        continue;
      }

      // Insert new hotel
      const inserted = await prisma.dvi_hotel.create({
        data: {
          hotel_name: hotel.hotel_name,
          hotel_code: hotel.hotel_code,
          resavenue_hotel_code: hotel.resavenue_hotel_code,
          hotel_city: hotel.hotel_city,
          hotel_state: hotel.hotel_state,
          hotel_country: hotel.hotel_country,
          hotel_category: hotel.hotel_category,
          status: 1, // Active
          deleted: false,
          createdby: 1, // System admin
          createdon: new Date(),
        },
      });

      console.log(`✅ Inserted: ${hotel.hotel_name} (ID: ${inserted.hotel_id}, ResAvenue Code: ${hotel.resavenue_hotel_code})`);
    }

    console.log('\n📊 Summary:');
    const totalResAvenueHotels = await prisma.dvi_hotel.count({
      where: {
        resavenue_hotel_code: { not: null },
        deleted: false,
      },
    });

    console.log(`Total ResAvenue hotels in database: ${totalResAvenueHotels}`);

    // Display all ResAvenue hotels
    const allResAvenueHotels = await prisma.dvi_hotel.findMany({
      where: {
        resavenue_hotel_code: { not: null },
        deleted: false,
      },
      select: {
        hotel_id: true,
        hotel_name: true,
        resavenue_hotel_code: true,
        hotel_city: true,
        hotel_state: true,
        status: true,
      },
      orderBy: {
        hotel_id: 'desc',
      },
    });

    console.log('\n📋 All ResAvenue Hotels:');
    console.table(allResAvenueHotels);

  } catch (error) {
    console.error('❌ Error inserting hotels:', error);
    throw error;
  } finally {
    await prisma.$disconnect();
  }
}

// Run the script
insertResAvenueHotels()
  .then(() => {
    console.log('\n✅ ResAvenue hotels insertion completed!');
    process.exit(0);
  })
  .catch((error) => {
    console.error('\n❌ Failed to insert hotels:', error);
    process.exit(1);
  });
