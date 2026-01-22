/**
 * HOBSE API Debug - Check raw response
 */

import axios from 'axios';
import * as dotenv from 'dotenv';

dotenv.config();

const HOBSE_BASE_URL = process.env.HOBSE_BASE_URL;
const HOBSE_CLIENT_TOKEN = process.env.HOBSE_CLIENT_TOKEN;
const HOBSE_ACCESS_TOKEN = process.env.HOBSE_ACCESS_TOKEN;
const HOBSE_PRODUCT_TOKEN = process.env.HOBSE_PRODUCT_TOKEN;

async function testRawResponse() {
  // Format date like PHP date("c") - ISO 8601 with milliseconds removed
  const now = new Date();
  const datetime = now.toISOString().replace(/\.\d{3}Z$/, '+00:00');
  
  const payload = {
    hobse: {
      version: '1.0',
      datetime: datetime,
      clientToken: HOBSE_CLIENT_TOKEN,
      accessToken: HOBSE_ACCESS_TOKEN,
      productToken: HOBSE_PRODUCT_TOKEN,
      request: {
        method: 'htl/GetHotelList',
        data: {
          resultType: 'json'
        },
      },
    },
  };

  console.log('📤 Request Payload:');
  console.log(JSON.stringify(payload, null, 2));

  try {
    const response = await axios.post(HOBSE_BASE_URL, payload, {
      headers: { 'Content-Type': 'application/json' },
      timeout: 30000,
    });

    console.log('\n✅ Response Status:', response.status);
    console.log('\n📥 Full Response:');
    console.log(JSON.stringify(response.data, null, 2));

  } catch (error: any) {
    console.log('\n❌ Error:', error.message);
    if (error.response) {
      console.log('Status:', error.response.status);
      console.log('Data:', JSON.stringify(error.response.data, null, 2));
    }
  }
}

testRawResponse();
