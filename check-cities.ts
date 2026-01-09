import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkCities() {
  const destinations = ['Mahabalipuram', 'Thanjavur', 'Madurai', 'Rameswaram'];
  
  console.log('🔍 Checking cities in dvi_cities table...\n');
  
  for (const destination of destinations) {
    const city = await prisma.dvi_cities.findFirst({
      where: { name: destination },
    });
    
    if (city) {
      console.log(`✅ "${destination}": Found`);
      console.log(`   - ID: ${(city as any).id}`);
      console.log(`   - Name: ${(city as any).name}`);
      console.log(`   - TBO City Code: ${(city as any).tbo_city_code || 'NULL/EMPTY'}`);
      console.log(`   - Country: ${(city as any).country || 'N/A'}`);
    } else {
      console.log(`❌ "${destination}": NOT FOUND`);
    }
    console.log('');
  }
  
  // Also search for partial matches
  console.log('\n📋 Searching for partial matches (cities containing these names)...\n');
  
  for (const destination of destinations) {
    const cities = await prisma.dvi_cities.findMany({
      where: {
        name: {
          contains: destination,
        },
      },
    });
    
    if (cities.length > 0) {
      console.log(`📍 Cities containing "${destination}":`);
      cities.forEach(city => {
        console.log(`   - ${(city as any).name} (TBO Code: ${(city as any).tbo_city_code || 'NULL'})`);
      });
    } else {
      console.log(`📍 No cities containing "${destination}"`);
    }
    console.log('');
  }
  
  await prisma.$disconnect();
}

checkCities().catch(err => {
  console.error('Error:', err);
  process.exit(1);
});
