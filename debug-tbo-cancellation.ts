import axios from 'axios';

const TBO_API_URL = 'https://sharedapi.tektravels.com/SharedData.svc/rest';
const TBO_BOOKING_API_URL = 'https://sharedapi.tektravels.com';

interface AuthResponse {
  Status: number;
  TokenId: string;
  Error: {
    ErrorCode: number;
    ErrorMessage: string;
  };
  Member: {
    FirstName: string;
    LastName: string;
    Email: string;
    MemberId: number;
    AgencyId: number;
    LoginName: string;
  };
}

interface CancellationRequest {
  BookingMode: number;
  RequestType: number;
  Remarks: string;
  BookingId: number;
  EndUserIp: string;
  TokenId: string;
}

async function authenticateTBO(): Promise<string> {
  try {
    console.log('\n🔐 TBO Authentication Request...');
    const response = await axios.post<AuthResponse>(
      `${TBO_API_URL}/Authenticate`,
      {
        UserName: 'Doview',
        Password: process.env.TBO_PASSWORD || 'Your_Password_Here',
        EndUserIp: '192.168.1.1',
      },
      {
        headers: {
          'Content-Type': 'application/json',
        },
        timeout: 30000,
      }
    );

    console.log('✅ TBO Authentication successful');
    console.log(`TokenId: ${response.data.TokenId}`);
    return response.data.TokenId;
  } catch (error: any) {
    console.error('❌ Authentication failed:', error.response?.data || error.message);
    throw error;
  }
}

async function testCancellationRequest(
  bookingId: number,
  tokenId: string,
  remarks: string
): Promise<void> {
  try {
    console.log(`\n❌ Testing cancellation for booking: ${bookingId}`);

    const cancellationRequest: CancellationRequest = {
      BookingMode: 5,
      RequestType: 4, // HotelCancel
      Remarks: remarks,
      BookingId: bookingId,
      EndUserIp: '192.168.1.1',
      TokenId: tokenId,
    };

    console.log('📤 Cancellation Request Payload:');
    console.log(JSON.stringify(cancellationRequest, null, 2));

    const response = await axios.post(
      `${TBO_BOOKING_API_URL}/hotelservice.svc/rest/SendChangeRequest`,
      cancellationRequest,
      {
        headers: {
          'Content-Type': 'application/json',
        },
        timeout: 30000,
      }
    );

    console.log('✅ Cancellation response received:');
    console.log(JSON.stringify(response.data, null, 2));
  } catch (error: any) {
    console.error('❌ Cancellation request failed:');
    console.error('Status Code:', error.response?.status);
    console.error('Response Data:', JSON.stringify(error.response?.data, null, 2));
    console.error('Error Message:', error.message);

    // Log headers sent
    console.log('\n📋 Request Headers:');
    console.log(JSON.stringify(error.config?.headers, null, 2));

    // Log full config
    console.log('\n📋 Full Request Config:');
    console.log(JSON.stringify({
      method: error.config?.method,
      url: error.config?.url,
      data: error.config?.data,
    }, null, 2));
  }
}

async function debugTBOCancellation(): Promise<void> {
  try {
    console.log('🚀 Starting TBO Cancellation Debug');
    console.log('='.repeat(60));

    // Step 1: Authenticate
    const tokenId = await authenticateTBO();

    // Step 2: Test cancellation with different request formats
    const testBookingId = 669667240173025;

    // Test 1: Current format (failing)
    console.log('\n' + '='.repeat(60));
    console.log('TEST 1: Current format (as per logs)');
    console.log('='.repeat(60));
    await testCancellationRequest(
      testBookingId,
      tokenId,
      'Hotel cancelled via voucher'
    );

    // Test 2: Try with string BookingId
    console.log('\n' + '='.repeat(60));
    console.log('TEST 2: String BookingId instead of number');
    console.log('='.repeat(60));
    const stringRequest = {
      BookingMode: 5,
      RequestType: 4,
      Remarks: 'Hotel cancelled via voucher',
      BookingId: testBookingId.toString(), // Try as string
      EndUserIp: '192.168.1.1',
      TokenId: tokenId,
    };
    console.log('📤 Request Payload:');
    console.log(JSON.stringify(stringRequest, null, 2));
    try {
      const response = await axios.post(
        `${TBO_BOOKING_API_URL}/hotelservice.svc/rest/SendChangeRequest`,
        stringRequest,
        {
          headers: { 'Content-Type': 'application/json' },
          timeout: 30000,
        }
      );
      console.log('✅ Response:', JSON.stringify(response.data, null, 2));
    } catch (err: any) {
      console.error('❌ Error:', err.response?.status, JSON.stringify(err.response?.data, null, 2));
    }

    // Test 3: Check what BookingId format TBO expects
    console.log('\n' + '='.repeat(60));
    console.log('TEST 3: Trying with smaller test BookingId');
    console.log('='.repeat(60));
    await testCancellationRequest(
      12345678, // Smaller booking ID
      tokenId,
      'Test cancellation'
    );

  } catch (error) {
    console.error('❌ Debug process failed:', error);
  }
}

// Run the debug
debugTBOCancellation().catch(console.error);
