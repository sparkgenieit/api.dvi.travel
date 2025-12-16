const http = require('http');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJpYXQiOjE3NjU2MDU2MTEsImV4cCI6MTc2NjIxMDQxMX0.DG8SZAtVZy-oqEbiixgbeXoHeqkCVY4wI4aVtvL7B3Q';

console.log('\n=== CHECKING CHENNAI HOTELS AND PRICING ===\n');

const queries = [
  {
    name: 'Hotels in Chennai (all categories)',
    query: `SELECT 
      hotel_id,
      hotel_name,
      hotel_city,
      hotel_location,
      hotel_category,
      hotel_star,
      deleted,
      status
    FROM dvi_hotel 
    WHERE hotel_city LIKE '%Chennai%' OR hotel_location LIKE '%Chennai%'
    ORDER BY hotel_category, hotel_name`
  },
  {
    name: 'Category 3 Hotels in Chennai',
    query: `SELECT 
      hotel_id,
      hotel_name,
      hotel_city,
      hotel_location,
      hotel_category,
      deleted,
      status
    FROM dvi_hotel 
    WHERE (hotel_city LIKE '%Chennai%' OR hotel_location LIKE '%Chennai%')
      AND hotel_category = 3
    ORDER BY hotel_name`
  },
  {
    name: 'Pricing for Chennai Hotels - December 2025',
    query: `SELECT 
      pb.hotel_id,
      h.hotel_name,
      h.hotel_city,
      pb.room_id,
      pb.month,
      pb.year,
      pb.day_24,
      pb.status,
      pb.deleted
    FROM dvi_hotel_room_price_book pb
    LEFT JOIN dvi_hotel h ON h.hotel_id = pb.hotel_id
    WHERE h.hotel_city LIKE '%Chennai%'
      AND pb.month = 'December'
      AND pb.year = '2025'
      AND pb.deleted = 0
      AND pb.status = 1
    ORDER BY h.hotel_name, pb.room_id`
  },
  {
    name: 'All Hotels in Category 3 with December 2025 pricing',
    query: `SELECT 
      h.hotel_id,
      h.hotel_name,
      h.hotel_city,
      COUNT(DISTINCT pb.room_id) as room_count,
      SUM(CASE WHEN pb.day_24 > 0 THEN 1 ELSE 0 END) as rooms_with_day24_price
    FROM dvi_hotel h
    LEFT JOIN dvi_hotel_room_price_book pb 
      ON pb.hotel_id = h.hotel_id 
      AND pb.month = 'December'
      AND pb.year = '2025'
      AND pb.deleted = 0
      AND pb.status = 1
    WHERE h.hotel_category = 3
      AND h.deleted = 0
      AND h.status = 1
    GROUP BY h.hotel_id, h.hotel_name, h.hotel_city
    HAVING room_count > 0 AND rooms_with_day24_price > 0
    ORDER BY h.hotel_city, h.hotel_name`
  }
];

function executeQuery(query) {
  return new Promise((resolve, reject) => {
    const postData = JSON.stringify({ query: query.query });

    const options = {
      hostname: '127.0.0.1',
      port: 4006,
      path: '/api/v1/debug/query',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Content-Length': Buffer.byteLength(postData)
      }
    };

    const req = http.request(options, (res) => {
      let data = '';
      
      res.on('data', (chunk) => {
        data += chunk;
      });
      
      res.on('end', () => {
        try {
          const parsed = JSON.parse(data);
          resolve({ name: query.name, data: parsed });
        } catch (e) {
          resolve({ name: query.name, data: data, error: e.message });
        }
      });
    });

    req.on('error', (error) => {
      reject(error);
    });

    req.write(postData);
    req.end();
  });
}

async function runQueries() {
  for (const query of queries) {
    console.log(`\n${'='.repeat(80)}`);
    console.log(`QUERY: ${query.name}`);
    console.log('='.repeat(80));
    
    try {
      const result = await executeQuery(query);
      
      if (result.error) {
        console.log('Error:', result.error);
        console.log(result.data);
      } else if (result.data && result.data.results) {
        console.log(`Found ${result.data.results.length} records`);
        console.log(JSON.stringify(result.data.results, null, 2));
      } else {
        console.log(JSON.stringify(result.data, null, 2));
      }
    } catch (err) {
      console.error('Error:', err.message);
    }
  }
  
  console.log('\n' + '='.repeat(80));
  console.log('ANALYSIS');
  console.log('='.repeat(80));
  console.log('\nIf Chennai hotels exist but have no pricing for December 24, 2025:');
  console.log('  → Add pricing in hotel price book for December 2025');
  console.log('\nIf Chennai has no category 3 hotels:');
  console.log('  → Add category 3 hotels for Chennai OR adjust itinerary to use different category');
  console.log('\nIf pricing exists but day_24 is 0 or NULL:');
  console.log('  → Update day_24 column with actual price');
  console.log('\n');
}

runQueries().catch(console.error);
