const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function resetPlan5() {
  console.log('\n=== RESETTING PLAN 5 TO TEST NESTJS LOGIC ===\n');
  
  try {
    // Delete all hotspot details for plan 5
    const deleted = await prisma.dvi_itinerary_route_hotspot_details.deleteMany({
      where: {
        itinerary_plan_ID: 5
      }
    });
    
    console.log(`✅ Deleted ${deleted.count} rows from plan 5`);
    console.log('\nNow run: node tmp/trigger_optimization.js');
    console.log('This will regenerate plan 5 using NestJS logic\n');
    
  } catch (error) {
    console.error('Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

resetPlan5();
