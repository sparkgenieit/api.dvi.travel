const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function addWalletBalance() {
  try {
    console.log('💰 Adding wallet balance for agent 126...\n');

    // Update agent wallet balance
    const result = await prisma.dvi_agent.update({
      where: { agent_ID: 126 },
      data: { 
        total_cash_wallet: 500000, // Adding ₹5,00,000 for multiple test runs
        total_coupon_wallet: 0
      }
    });

    console.log('✅ Wallet balance updated successfully!');
    console.log(`   Agent ID: ${result.agent_ID}`);
    console.log(`   Cash Wallet: ₹${result.total_cash_wallet}`);
    console.log(`   Coupon Wallet: ₹${result.total_coupon_wallet}`);
    console.log('\n✅ You can now run the booking test!\n');

  } catch (error) {
    console.error('❌ Error:', error.message);
  } finally {
    await prisma.$disconnect();
  }
}

addWalletBalance();
