// COMPARISON: PHP Plan 2 vs NestJS Plan 5 Payloads

console.log('\n=== PAYLOAD COMPARISON ===\n');

const phpPlan2 = {
  plan_id: 2,
  agent: 2,
  arrival_location: "Chennai International Airport",
  departure_location: "Pondicherry Airport",
  trip_start_date: "13/12/2025",
  trip_start_time: "11:00 AM",
  trip_end_date: "15/12/2025",
  trip_end_time: "8:57 PM",  // ⚠️ NOTE: 8:57 PM
  itinerary_type: 2,
  arrival_type: 1,
  departure_type: 1,
  no_of_nights: 2,
  no_of_days: 3,
  expecting_budget: 15000,
  entry_ticket_required: 0,
  itinerary_adult: 2,
  itinerary_children: 0,
  itinerary_infants: 0,
  guide_for_itinerary: 0,
  nationality: 101,
  food_type: 1,  // ⚠️ DIFFERENT
  meal_plan_breakfast: "on",  // ⚠️ PHP specific
  itinerary_prefrence: 3,
  pick_up_date_and_time: "13/12/2025 11:00 AM",
  routes: [
    {
      date: "13/12/2025",
      source: "Chennai International Airport",
      next: "Chennai"
    },
    {
      date: "14/12/2025",
      source: "Chennai",
      next: "Pondicherry"
    },
    {
      date: "15/12/2025",
      source: "Pondicherry",
      next: "Pondicherry Airport"
    }
  ],
  vehicle: {
    type: 1,
    count: 1
  }
};

const nestjsPlan5 = {
  plan_id: 5,
  agent_id: 126,
  arrival_point: "Chennai International Airport",
  departure_point: "Pondicherry Airport",
  trip_start_date: "2025-12-13T11:00:00+05:30",  // 11:00 AM
  trip_end_date: "2025-12-15T20:00:00+05:30",    // ⚠️ 8:00 PM (20:00)
  itinerary_type: 2,
  arrival_type: 1,
  departure_type: 1,
  no_of_nights: 2,
  no_of_days: 3,
  budget: 15000,
  entry_ticket_required: 0,
  adult_count: 2,
  child_count: 0,
  infant_count: 0,
  guide_for_itinerary: 0,
  nationality: 101,
  food_type: 0,  // ⚠️ DIFFERENT
  itinerary_preference: 3,
  pick_up_date_and_time: "2025-12-13T11:00:00+05:30",
  routes: [
    {
      date: "2025-12-13T00:00:00+05:30",
      location_name: "Chennai International Airport",
      next_visiting_location: "Chennai",
      no_of_days: 1
    },
    {
      date: "2025-12-14T00:00:00+05:30",
      location_name: "Chennai",
      next_visiting_location: "Pondicherry",
      no_of_days: 2
    },
    {
      date: "2025-12-15T00:00:00+05:30",
      location_name: "Pondicherry",
      next_visiting_location: "Pondicherry Airport",
      no_of_days: 3
    }
  ],
  vehicles: [
    { vehicle_type_id: 1, vehicle_count: 1 }
  ]
};

console.log('=== KEY DIFFERENCES ===\n');

const differences = [
  {
    field: 'trip_end_time',
    php: '8:57 PM (20:57)',
    nestjs: '8:00 PM (20:00)',
    impact: '❌ CRITICAL - 57 minute difference in end time'
  },
  {
    field: 'food_type',
    php: '1',
    nestjs: '0',
    impact: '⚠️  May affect meal planning'
  },
  {
    field: 'agent_id',
    php: '2',
    nestjs: '126',
    impact: 'ℹ️  Different agents - expected for different plans'
  },
  {
    field: 'meal_plan_breakfast',
    php: 'on',
    nestjs: 'not specified in payload',
    impact: 'ℹ️  PHP specific field'
  },
  {
    field: 'preferred_hotel_category',
    php: 'not specified',
    nestjs: '[2]',
    impact: 'ℹ️  NestJS specific field'
  }
];

differences.forEach((diff, i) => {
  console.log(`${i + 1}. ${diff.field}`);
  console.log(`   PHP:    ${diff.php}`);
  console.log(`   NestJS: ${diff.nestjs}`);
  console.log(`   ${diff.impact}\n`);
});

console.log('=== MATCHING FIELDS ===\n');
console.log('✅ Arrival location: Chennai International Airport');
console.log('✅ Departure location: Pondicherry Airport');
console.log('✅ Start date/time: 13/12/2025 11:00 AM');
console.log('✅ Itinerary type: 2');
console.log('✅ No of nights: 2');
console.log('✅ No of days: 3');
console.log('✅ Budget: 15000');
console.log('✅ Adults: 2, Children: 0, Infants: 0');
console.log('✅ Nationality: 101');
console.log('✅ Vehicle: Type 1, Count 1');
console.log('✅ Routes: All 3 routes match (dates and locations)');

console.log('\n=== RECOMMENDATION ===\n');
console.log('The payloads are MOSTLY ALIGNED except for:');
console.log('');
console.log('1. ❌ END TIME MISMATCH:');
console.log('   - Update NestJS trip_end_date to: "2025-12-15T20:57:00+05:30"');
console.log('   - Current: 8:00 PM, Should be: 8:57 PM');
console.log('');
console.log('2. ⚠️  FOOD TYPE:');
console.log('   - PHP: 1, NestJS: 0');
console.log('   - May need to update NestJS to food_type: 1');
console.log('');
console.log('These differences could affect the optimization results comparison.');
