import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  console.log('\n=== CHECKING HOBSE CITY MAPPINGS ===\n');

  // 1. Check cities in plan 12
  console.log('📍 Cities in test itinerary (Plan 12):');
  const routesInPlan = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 12, deleted: 0 },
    select: {
      itinerary_route_ID: true,
      next_visiting_location: true,
      location_name: true,
    },
    orderBy: { itinerary_route_date: 'asc' },
  });

  console.log(JSON.stringify(routesInPlan, null, 2));

  // 2. Check city mappings
  console.log('\n📊 City mappings in dvi_cities:');
  const uniqueCities = [...new Set(routesInPlan.map((r: any) => r.next_visiting_location))];

  for (const cityName of uniqueCities) {
    const cityRow = await prisma.dvi_cities.findFirst({
      where: {
        OR: [
          { name: cityName },
          { tbo_city_code: cityName },
        ],
      },
      select: {
        name: true,
        tbo_city_code: true,
        hobse_city_code: true,
      },
    });

    console.log(`  "${cityName}":`, cityRow ? JSON.stringify(cityRow) : 'NOT FOUND');
  }

  // 3. Show all cities with HOBSE mappings
  console.log('\n✅ All cities with HOBSE mappings:');
  const citiesWithHobse = await prisma.dvi_cities.findMany({
    where: {
      hobse_city_code: { not: null },
    },
    select: {
      name: true,
      tbo_city_code: true,
      hobse_city_code: true,
    },
    take: 20,
  });

  console.log(JSON.stringify(citiesWithHobse, null, 2));

  // 4. List HOBSE hotel cities from API response
  console.log('\n🏨 HOBSE API has hotels in these cities:');
  const hobseHotelCities = [
    'Chennai',
    'Bangalore',
    'Thekkady',
    'Thiruvananthapuram',
    'Ernakulam',
    'Mumbai',
    'Coimbatore',
    'Pune',
    'Amritsar',
  ];

  for (const hobseCity of hobseHotelCities) {
    const mapping = await prisma.dvi_cities.findFirst({
      where: { name: hobseCity },
      select: { hobse_city_code: true },
    });
    console.log(`  ${hobseCity}: ${mapping?.hobse_city_code || 'NO MAPPING'}`);
  }

  await prisma.$disconnect();
}

main().catch(console.error);
