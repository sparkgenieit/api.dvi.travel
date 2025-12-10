const http = require('http');

function rebuildPlan4() {
  return new Promise((resolve, reject) => {
    const postData = JSON.stringify({
      itinerary_id: 4,
    });

    const options = {
      hostname: '127.0.0.1',
      port: 4006,
      path: '/api/v1/itineraries',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Content-Length': postData.length,
      },
    };

    const req = http.request(options, (res) => {
      let data = '';
      res.on('data', (chunk) => {
        data += chunk;
      });
      res.on('end', () => {
        console.log('Rebuild Response Status:', res.statusCode);
        if (res.statusCode >= 200 && res.statusCode < 300) {
          console.log('Plan 4 rebuild triggered');
          resolve(true);
        } else {
          console.log('Response:', data);
          reject(new Error('HTTP ' + res.statusCode));
        }
      });
    });

    req.on('error', reject);
    req.write(postData);
    req.end();
  });
}

rebuildPlan4()
  .then(() => {
    console.log('Rebuild completed. Waiting 3 seconds before count check...');
    setTimeout(() => {
      console.log('Ready for count check');
      process.exit(0);
    }, 3000);
  })
  .catch((err) => {
    console.error('Error rebuilding plan 4:', err.message);
    process.exit(1);
  });
