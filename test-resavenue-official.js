const http = require('http');

// ResAvenue Credentials (Official)
const username = 'testpmsk4@resavenue.com';
const password = 'testpms@123';
const idContext = 'REV';
const hotelId = '261'; // PMS Test Hotel
const hotelName = 'PMS Test Hotel';

// Create Basic Auth header
const authString = Buffer.from(`${username}:${password}`).toString('base64');

console.log('\n=== TESTING OTA_HotelResNotifRQ WITH OFFICIAL RESAVENUE CREDENTIALS ===\n');
console.log('Official Credentials Provided:');
console.log(`  Hotel ID: ${hotelId}`);
console.log(`  Hotel Name: ${hotelName}`);
console.log(`  Username: ${username}`);
console.log(`  Password: ${password}`);
console.log(`  ID_Context: ${idContext}`);
console.log(`  Basic Auth: Basic ${authString}`);
console.log(`  Endpoint: http://203.109.97.241:8080/ChannelController/PropertyDetails\n`);

// Test 1: Simple Property Details with Hotel ID 261
console.log('═'.repeat(70));
console.log('TEST 1: OTA_HotelDetailsRQ - Get PMS Test Hotel Details (Hotel ID: 261)');
console.log('═'.repeat(70));

const propertyDetailsRequest = {
  OTA_HotelDetailsRQ: {
    POS: {
      Username: username,
      Password: password,
      ID_Context: idContext,
    },
    TimeStamp: new Date().toISOString().replace(/\.\d{3}Z$/, ''),
    EchoToken: `test-${Date.now()}`,
    HotelCode: hotelId, // Using official Hotel ID 261
  },
};

const propertyRequestBody = JSON.stringify(propertyDetailsRequest);

const propertyOptions = {
  hostname: '203.109.97.241',
  port: 8080,
  path: '/ChannelController/PropertyDetails',
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Basic ${authString}`,
    'Content-Length': Buffer.byteLength(propertyRequestBody),
  },
};

console.log('\n📤 Sending PropertyDetails Request for Hotel ID 261...');
console.log('Request:');
console.log(JSON.stringify(propertyDetailsRequest, null, 2));

const propertyReq = http.request(propertyOptions, (res) => {
  console.log(`\n✅ Response Status: ${res.statusCode}`);
  
  let data = '';
  res.on('data', (chunk) => {
    data += chunk;
  });

  res.on('end', () => {
    console.log('\n📥 Response Body:');
    try {
      const parsed = JSON.parse(data);
      console.log(JSON.stringify(parsed, null, 2));
      
      if (res.statusCode === 200) {
        console.log('\n✅ TEST 1 PASSED - Got property details with correct credentials!');
      } else {
        console.log(`\n❌ TEST 1 FAILED - Status ${res.statusCode}`);
      }
    } catch (e) {
      console.log(data);
    }

    // Test 2: Booking with proper Hotel ID
    console.log('\n' + '═'.repeat(70));
    console.log('TEST 2: OTA_HotelResNotifRQ - Booking Confirmation for Hotel 261');
    console.log('═'.repeat(70));

    const bookingRequest = {
      OTA_HotelResNotifRQ: {
        Target: 'Production',
        Version: '1.0',
        EchoToken: `booking-${Date.now()}`,
        TimeStamp: new Date().toISOString().replace(/\.\d{3}Z$/, ''),
        POS: {
          SourceID: {
            ID: username,
          },
          RequestorID: {
            User: username,
            Password: password,
            ID_Context: idContext,
          },
        },
        HotelReservations: {
          HotelReservation: [
            {
              UniqueID: {
                ID: `DVI-${Date.now()}`,
                OTA: 'DVI',
                BookingSource: 'DVI Journey Manager',
              },
              ResStatus: 'Confirm',
              RoomStays: {
                RoomStay: [
                  {
                    TimeSpan: {
                      Start: '2026-02-23',
                      End: '2026-02-25',
                    },
                    BasicPropertyInfo: {
                      HotelCode: hotelId, // Using Hotel ID 261
                      HotelName: hotelName,
                    },
                    GuestCounts: {
                      GuestCount: [
                        {
                          Count: 2,
                          AgeQualifyingCode: '10',
                        },
                      ],
                    },
                    RoomTypes: {
                      RoomType: {
                        NumberOfUnits: 1,
                        RoomDescription: {
                          Name: 'Deluxe Room',
                        },
                        RoomTypeCode: 'INV001',
                      },
                    },
                    Total: {
                      CurrencyCode: 'INR',
                      Amount: 5000,
                    },
                    RatePlans: {
                      RatePlan: {
                        RatePlanName: 'Test Plan',
                        RatePlanCode: 'RATE001',
                      },
                    },
                  },
                ],
              },
              ResGlobalInfo: {
                SpecialRequest: 'Test booking request',
                Total: {
                  CurrencyCode: 'INR',
                  TotalTax: 500,
                  TaxType: 'Inclusive',
                  TotalBookingAmount: 5500,
                  Commission: 0,
                  CommissionType: 'Inclusive',
                },
              },
              CreateDateTime: new Date().toISOString().replace(/\.\d{3}Z$/, ''),
              PayAtHotel: 'Y',
              ResGuests: {
                ResGuest: [
                  {
                    Profiles: {
                      ProfileInfo: {
                        UniqueID: {
                          Type: '1',
                          ID_Context: 'Guest-1',
                        },
                        Profile: {
                          ProfileType: '1',
                          Customer: {
                            PersonName: {
                              GivenName: 'John',
                              Surname: 'Doe',
                            },
                            Email: 'john.doe@test.com',
                            Telephone: '+919876543210',
                          },
                        },
                      },
                    },
                    ResGuestRPH: 0,
                  },
                  {
                    Profiles: {
                      ProfileInfo: {
                        UniqueID: {
                          Type: '1',
                          ID_Context: 'Guest-2',
                        },
                        Profile: {
                          ProfileType: '1',
                          Customer: {
                            PersonName: {
                              GivenName: 'Jane',
                              Surname: 'Doe',
                            },
                            Email: 'jane.doe@test.com',
                            Telephone: '+919876543211',
                          },
                        },
                      },
                    },
                    ResGuestRPH: 1,
                  },
                ],
              },
            },
          ],
        },
      },
    };

    const bookingRequestBody = JSON.stringify(bookingRequest);

    const bookingOptions = {
      hostname: '203.109.97.241',
      port: 8080,
      path: '/ChannelController/PropertyDetails',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Basic ${authString}`,
        'Content-Length': Buffer.byteLength(bookingRequestBody),
      },
    };

    console.log('\n📤 Sending Booking Confirmation Request...');
    console.log('Request:');
    console.log(JSON.stringify(bookingRequest, null, 2));

    const bookingReq = http.request(bookingOptions, (res) => {
      console.log(`\n✅ Response Status: ${res.statusCode}`);

      let data = '';
      res.on('data', (chunk) => {
        data += chunk;
      });

      res.on('end', () => {
        console.log('\n📥 Response Body:');
        try {
          const parsed = JSON.parse(data);
          console.log(JSON.stringify(parsed, null, 2));
          
          if (res.statusCode === 200 || res.statusCode === 201) {
            console.log('\n✅ TEST 2 PASSED - Booking confirmed with correct credentials!');
          } else {
            console.log(`\n⚠️  TEST 2 - Status ${res.statusCode}`);
          }
        } catch (e) {
          console.log(data);
        }

        // Test 3: Cancellation with Hotel 261
        console.log('\n' + '═'.repeat(70));
        console.log('TEST 3: OTA_HotelResNotifRQ - Booking Cancellation');
        console.log('═'.repeat(70));

        const cancellationRequest = {
          OTA_HotelResNotifRQ: {
            Target: 'Production',
            Version: '1.0',
            EchoToken: `cancel-${Date.now()}`,
            TimeStamp: new Date().toISOString().replace(/\.\d{3}Z$/, ''),
            POS: {
              SourceID: {
                ID: username,
              },
              RequestorID: {
                User: username,
                Password: password,
                ID_Context: idContext,
              },
            },
            HotelReservations: {
              HotelReservation: [
                {
                  UniqueID: {
                    ID: `DVI-${Date.now()}`,
                    OTA: 'DVI',
                    BookingSource: 'DVI Journey Manager',
                  },
                  ResStatus: 'Cancel',
                  ResGlobalInfo: {
                    SpecialRequest: 'Testing cancellation',
                  },
                },
              ],
            },
          },
        };

        const cancellationRequestBody = JSON.stringify(cancellationRequest);

        const cancellationOptions = {
          hostname: '203.109.97.241',
          port: 8080,
          path: '/ChannelController/PropertyDetails',
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Basic ${authString}`,
            'Content-Length': Buffer.byteLength(cancellationRequestBody),
          },
        };

        console.log('\n📤 Sending Cancellation Request...');
        console.log('Request:');
        console.log(JSON.stringify(cancellationRequest, null, 2));

        const cancellationReq = http.request(cancellationOptions, (res) => {
          console.log(`\n✅ Response Status: ${res.statusCode}`);

          let data = '';
          res.on('data', (chunk) => {
            data += chunk;
          });

          res.on('end', () => {
            console.log('\n📥 Response Body:');
            try {
              const parsed = JSON.parse(data);
              console.log(JSON.stringify(parsed, null, 2));
              
              if (res.statusCode === 200 || res.statusCode === 201) {
                console.log('\n✅ TEST 3 PASSED - Cancellation successful!');
              } else {
                console.log(`\n⚠️  TEST 3 - Status ${res.statusCode}`);
              }
            } catch (e) {
              console.log(data);
            }

            // Summary
            console.log('\n' + '═'.repeat(70));
            console.log('📋 TEST SUMMARY');
            console.log('═'.repeat(70));
            console.log('\nCredentials Used:');
            console.log(`  Username: ${username}`);
            console.log(`  Password: ${password}`);
            console.log(`  ID_Context: ${idContext}`);
            console.log(`  Hotel ID: ${hotelId} (${hotelName})`);
            console.log(`  Endpoint: http://203.109.97.241:8080/ChannelController/PropertyDetails`);
            console.log('\nRequest Format:');
            console.log(`  Standard Requests: OTA_HotelDetailsRQ with flat POS structure`);
            console.log(`  Booking Requests: OTA_HotelResNotifRQ with nested POS structure`);
            console.log('\n✅ ALL API CALLS COMPLETED');
            console.log('═'.repeat(70));
            process.exit(0);
          });
        });

        cancellationReq.on('error', (error) => {
          console.error('❌ Cancellation request error:', error.message);
          process.exit(1);
        });

        console.log('Sending cancellation request...\n');
        cancellationReq.write(cancellationRequestBody);
        cancellationReq.end();
      });
    });

    bookingReq.on('error', (error) => {
      console.error('❌ Booking request error:', error.message);
      process.exit(1);
    });

    console.log('Sending booking confirmation request...\n');
    bookingReq.write(bookingRequestBody);
    bookingReq.end();
  });
});

propertyReq.on('error', (error) => {
  console.error('❌ Property details request error:', error.message);
  console.error('   This likely means the ResAvenue server is not reachable');
  process.exit(1);
});

console.log('Sending property details request...\n');
propertyReq.write(propertyRequestBody);
propertyReq.end();
