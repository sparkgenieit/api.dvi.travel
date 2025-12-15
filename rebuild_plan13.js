const { PrismaClient } = require('@prisma/client');
const axios = require('axios');
const prisma = new PrismaClient();

async function rebuildPlan13() {
  try {
    // Get plan data from database
    const plan = await prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: 13 },
    });

    if (!plan) {
      console.log('Plan 13 not found');
      return;
    }

    // Get quote data for routes
    const quote = await prisma.dvi_itinerary_quote_route_list.findMany({
      where: { itinerary_quote_ID: plan.itinerary_quote_ID, deleted: 0 },
      orderBy: { route_order: 'asc' },
    });

    // Get travellers
    const travellers = await prisma.dvi_itinerary_plan_travellers.findMany({
      where: { itinerary_plan_ID: 13, deleted: 0 },
    });

    // Get vehicles
    const vehicles = await prisma.dvi_itinerary_plan_vehicle_details.findMany({
      where: { itinerary_plan_ID: 13, deleted: 0 },
    });

    // Build request payload
    const payload = {
      plan: {
        itinerary_plan_id: plan.itinerary_plan_ID,
        agent_id: plan.agent_id,
        quote_id: plan.itinerary_quote_ID,
        trip_start_date: plan.trip_start_date,
        trip_end_date: plan.trip_end_date,
        pickup_date_and_time: plan.pick_up_date_and_time,
        arrival_type: plan.arrival_type,
        departure_type: plan.departure_type,
        entry_ticket_required: plan.entry_ticket_required,
        nationality: plan.nationality,
        total_adult: plan.total_adult,
        total_children: plan.total_children,
        total_infants: plan.total_infants,
        itinerary_preference: plan.itinerary_preference,
        arrival_location: plan.arrival_location,
        departure_location: plan.departure_location,
      },
      routes: quote.map(r => ({
        location_id: r.location_id,
        route_date: r.itinerary_route_date,
        start_time: r.route_start_time,
        end_time: r.route_end_time,
      })),
      travellers: travellers.map(t => ({
        traveller_name: t.itinerary_plan_travellers_name,
        age: t.itinerary_plan_travellers_age,
        gender: t.itinerary_plan_travellers_gender,
        nationality: t.itinerary_plan_travellers_nationality,
        food_pref_id: t.itinerary_plan_travellers_food_pref_id,
      })),
      vehicles: vehicles.map(v => ({
        vehicle_type_id: v.vehicle_type_id,
        total_vehicle_qty: v.total_vehicle_qty,
      })),
    };

    console.log('Triggering rebuild for Plan 13...\n');
    
    const response = await axios.post('http://localhost:4006/api/v1/itineraries', payload, {
      headers: { 'Content-Type': 'application/json' },
    });

    console.log('✅ Response:', response.status);
    console.log(JSON.stringify(response.data, null, 2));

    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error.response?.data || error.message);
    await prisma.$disconnect();
  }
}

rebuildPlan13();
