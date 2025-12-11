const axios = require('axios');

async function main() {
  console.log('=== REGENERATING PHP PLAN 2 ===');
  console.log('This will show if the 34-minute travel time is consistent\n');

  // Call the PHP optimization endpoint
  // You'll need to update this URL to match your PHP server
  const phpUrl = 'http://localhost/dvi_fullstack/dvi_backend_php/optimize_plan.php?plan_id=2';
  
  console.log(`Calling: ${phpUrl}`);
  console.log('(Update the URL if your PHP server is elsewhere)\n');

  try {
    const response = await axios.post(phpUrl);
    console.log('✅ PHP optimization triggered');
    console.log('Response:', response.data);
    
    console.log('\n⏳ Wait a moment for PHP to finish, then check the travel time:');
    console.log('   node tmp\\compare_h4_travel_details.js');
  } catch (error) {
    console.log('❌ Error calling PHP:',  error.message);
    console.log('\nYou may need to:');
    console.log('1. Start your PHP server');
    console.log('2. Update the URL in this script');
    console.log('3. Or manually trigger PHP optimization for Plan 2');
  }
}

main().catch(console.error);
