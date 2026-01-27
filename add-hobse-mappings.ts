import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  console.log('🔧 Adding HOBSE city code mappings...\n');

  // Based on HOBSE API response, we need to map Indian cities to available HOBSE cities
  // Since Mahabalipuram and Thanjavur aren't in HOBSE, we'll map them to nearest cities

  // Option 1: Map Mahabalipuram to Chennai (same metro area)
  const mahabalipuram = await prisma.dvi_cities.update({
    where: { name: 'Mahabalipuram' },
    data: { hobse_city_code: '19' }, // Chennai's HOBSE code
  });
  console.log('✅ Mahabalipuram mapped to Chennai (hobse_city_code: 19)');

  // Option 2: Map Thanjavur to Coimbatore or keep as is
  // Thanjavur is a tier-2 city, not available in HOBSE test data
  // For demo, we can map it to Coimbatore (nearest available)
  const thanjavur = await prisma.dvi_cities.update({
    where: { name: 'Thanjavur' },
    data: { hobse_city_code: '24' }, // Coimbatore's HOBSE code
  });
  console.log('✅ Thanjavur mapped to Coimbatore (hobse_city_code: 24)');

  console.log('\n✅ City mappings updated!');
  console.log('\nVerifying...');

  const updated = await prisma.dvi_cities.findMany({
    where: {
      name: { in: ['Mahabalipuram', 'Thanjavur'] },
    },
    select: { name: true, tbo_city_code: true, hobse_city_code: true },
  });

  console.log(JSON.stringify(updated, null, 2));

  await prisma.$disconnect();
}

main().catch(console.error);
