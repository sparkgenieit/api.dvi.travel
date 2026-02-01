// Example: How the fix works for Chennai (4 hotels)

const routes = [
  { itinerary_route_ID: 151, location: 'Chennai', date: '2026-04-27' },
  // ... other routes
];

const hotelsByRoute = new Map([
  [151, [
    { hotelCode: '40', hotelName: 'Demo Hotel 2', price: 3000, provider: 'HOBSE' },
    { hotelCode: '1277095', hotelName: 'Pride Hotel Chennai', price: 5238, provider: 'tbo' },
    { hotelCode: '1128903', hotelName: 'The Residency Towers', price: 9608, provider: 'tbo' },
    { hotelCode: '1261860', hotelName: 'The Accord Metropolitan', price: 10620, provider: 'tbo' },
  ]]
]);

// ============ FIRST PASS ============
// Process Route 151 (Chennai):

// Step 1: Sort hotels by price (ascending)
const sortedHotels = [
  { hotelName: 'Demo Hotel 2', price: 3000 },           // index 0
  { hotelName: 'Pride Hotel Chennai', price: 5238 },    // index 1
  { hotelName: 'The Residency Towers', price: 9608 },   // index 2
  { hotelName: 'The Accord Metropolitan', price: 10620 },// index 3
];

// Step 2: We have 4 hotels, so numHotels = 4
const numHotels = 4;

// Step 3: Since numHotels <= 4, use: groupType = Math.min(index + 1, 4)
// Result:
// - Demo Hotel 2 (index 0)              → groupType = Math.min(0+1, 4) = 1 ✅
// - Pride Hotel Chennai (index 1)       → groupType = Math.min(1+1, 4) = 2 ✅
// - The Residency Towers (index 2)      → groupType = Math.min(2+1, 4) = 3 ✅
// - The Accord Metropolitan (index 3)   → groupType = Math.min(3+1, 4) = 4 ✅

// hotelGroupAssignments Map:
// "151-40:1" → 1
// "151-1277095:2" → 2
// "151-1128903:3" → 3
// "151-1261860:4" → 4

// ============ SECOND PASS ============
// Build packages for each tier:

// Tier 1 (Budget Hotels - groupType = 1):
// - Check 151-40:1 → exists (Demo Hotel 2 ✅)
// - Check 151-1277095:1 → doesn't exist
// - Check 151-1128903:1 → doesn't exist
// - Check 151-1261860:1 → doesn't exist
// Result: [Demo Hotel 2]

// Tier 2 (Mid-Range Hotels - groupType = 2):
// - Check 151-40:2 → doesn't exist
// - Check 151-1277095:2 → exists (Pride Hotel Chennai ✅)
// - Check 151-1128903:2 → doesn't exist
// - Check 151-1261860:2 → doesn't exist
// Result: [Pride Hotel Chennai]

// Tier 3 (Premium Hotels - groupType = 3):
// - Check 151-40:3 → doesn't exist
// - Check 151-1277095:3 → doesn't exist
// - Check 151-1128903:3 → exists (The Residency Towers ✅)
// - Check 151-1261860:3 → doesn't exist
// Result: [The Residency Towers]

// Tier 4 (Luxury Hotels - groupType = 4):
// - Check 151-40:4 → doesn't exist
// - Check 151-1277095:4 → doesn't exist
// - Check 151-1128903:4 → doesn't exist
// - Check 151-1261860:4 → exists (The Accord Metropolitan ✅)
// Result: [The Accord Metropolitan]

// ============ FINAL RESPONSE ============
// Package 1 (Budget): Demo Hotel 2 (₹3,000)
// Package 2 (Mid-Range): Pride Hotel Chennai (₹5,238)
// Package 3 (Premium): The Residency Towers (₹9,608)
// Package 4 (Luxury): The Accord Metropolitan (₹10,620)

// ============ SCENARIO 2: Single Hotel ============
// If Chennai had only 1 hotel (e.g., Demo Hotel 2):

// First Pass:
// Since sortedHotels.length === 1:
//   for (let groupType = 1; groupType <= 4; groupType++) {
//     hotelGroupAssignments.set(`151-40:${groupType}`, groupType);
//   }
// Result:
// "151-40:1" → 1
// "151-40:2" → 2
// "151-40:3" → 3
// "151-40:4" → 4

// Second Pass:
// All 4 tiers will find the hotel:
// Package 1 (Budget): Demo Hotel 2 (₹3,000)
// Package 2 (Mid-Range): Demo Hotel 2 (₹3,000)
// Package 3 (Premium): Demo Hotel 2 (₹3,000)
// Package 4 (Luxury): Demo Hotel 2 (₹3,000)
