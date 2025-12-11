const mysql = require('mysql2/promise');

async function checkCategorization() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  try {
    console.log('\n=== ROUTE 3 HOTSPOT CATEGORIZATION TEST ===\n');

    const location_name = 'Pondicherry'; // SOURCE
    const next_visiting_name = 'Pondicherry Airport'; // DESTINATION

    const hotspots = [
      { id: 18, name: 'Auroville', location: 'Pondicherry Airport|Pondicherry', priority: 1 },
      { id: 16, name: 'Paradise Beach', location: 'Pondicherry Airport', priority: 1 },
      { id: 25, name: 'Manakula Temple', location: 'Pondicherry Airport|Pondicherry', priority: 2 },
    ];

    console.log('SOURCE location: "Pondicherry"');
    console.log('DESTINATION location: "Pondicherry Airport"\n');

    hotspots.forEach(h => {
      const locations = h.location.split('|');
      const primaryLocation = locations[0];
      
      const matchesSource = h.location.includes(location_name);
      const matchesDestination = h.location.includes(next_visiting_name);
      const isPrimaryDestination = primaryLocation === next_visiting_name;
      
      console.log(`Hotspot ${h.id}: ${h.name}`);
      console.log(`  Location: "${h.location}"`);
      console.log(`  Primary: "${primaryLocation}"`);
      console.log(`  Matches SOURCE ("${location_name}"): ${matchesSource}`);
      console.log(`  Matches DESTINATION ("${next_visiting_name}"): ${matchesDestination}`);
      console.log(`  Is PRIMARY destination: ${isPrimaryDestination}`);
      
      // NestJS logic:
      const shouldAddToSource = matchesSource && !(matchesDestination && isPrimaryDestination);
      const shouldAddToDestination = matchesDestination;
      
      console.log(`  → Add to SOURCE: ${shouldAddToSource}`);
      console.log(`  → Add to DESTINATION: ${shouldAddToDestination}\n`);
    });

    console.log('Expected categorization:');
    console.log('  SOURCE: [18, 25] (match "Pondicherry", not primary "Pondicherry Airport")');
    console.log('  DESTINATION: [18, 16, 25] (all match "Pondicherry Airport")');
    console.log('\nPHP concatenates: SOURCE + DESTINATION');
    console.log('  With deduplication: [18, 25] + [16] = [18, 25, 16]');

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await connection.end();
  }
}

checkCategorization();
