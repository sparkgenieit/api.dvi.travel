import { PrismaClient, Role } from '@prisma/client';
import * as bcrypt from 'bcrypt';

const prisma = new PrismaClient();

async function main() {
  console.log('Seeding database with default admin and sample data...');

  const hash = await bcrypt.hash('admin123', 10);
  const admin = await prisma.user.upsert({
    where: { email: 'admin@dvi.travel' },
    update: { password: hash, role: 'admin' as Role },
    create: {
      email: 'admin@dvi.travel',
      password: hash,
      fullName: 'Admin User',
      role: 'admin' as Role,
    },
  });

  // Hotels
  const hotel = await prisma.hotel.create({
    data: {
      name: 'DVI Grand Palace',
      code: 'DVI-GRAND',
      starRating: 5,
      city: 'Madurai',
      state: 'TN',
      country: 'India',
      description: 'Premium business hotel near the airport.',
      rooms: {
        create: [
          { name: 'Deluxe King', occupancy: 2, bedType: 'King', sizeSqft: 320 },
          { name: 'Twin Executive', occupancy: 2, bedType: 'Twin', sizeSqft: 300 }
        ]
      },
      amenities: {
        create: [
          { name: 'WiFi', category: 'General' },
          { name: 'Breakfast Buffet', category: 'Food & Beverage' },
          { name: 'Airport Pickup', category: 'Transport' }
        ]
      },
      priceBooks: {
        create: [
          {
            roomName: 'Deluxe King',
            season: 'Peak 2025 Q4',
            basePrice: '5999.00',
            currency: 'INR',
            extraAdult: '800.00',
            extraChild: '500.00',
            effectiveFrom: new Date('2025-10-01'),
            effectiveTo: new Date('2026-01-31')
          }
        ]
      },
      reviews: {
        create: [
          { rating: 5, comment: 'Excellent service!', author: 'Rithvik' },
          { rating: 4, comment: 'Great location for early flights.', author: 'Kiran' }
        ]
      }
    }
  });

  // Itinerary
  const itinerary = await prisma.itinerary.create({
    data: {
      code: 'DVI-IT-0001',
      title: 'South India Highlights',
      travelerName: 'Client A',
      travelerCount: 4,
      startDate: new Date('2025-12-20'),
      endDate: new Date('2025-12-28'),
      status: 'Draft',
      days: {
        create: [
          { dayNumber: 1, date: new Date('2025-12-20'), notes: 'Arrival and hotel check-in' },
          { dayNumber: 2, date: new Date('2025-12-21'), notes: 'City tour and temple visit' }
        ]
      }
    }
  });

  // Segments for Day 1
  const day1 = await prisma.itineraryDay.findFirst({
    where: { itineraryId: itinerary.id, dayNumber: 1 }
  });
  if (day1) {
    await prisma.itinerarySegment.createMany({
      data: [
        { dayId: day1.id, order: 1, type: 'transfer', title: 'Airport Pickup', location: 'IXM', startTime: '09:30', endTime: '10:30' },
        { dayId: day1.id, order: 2, type: 'hotel', title: 'Hotel Check-in', location: hotel.name, startTime: '11:00', endTime: '11:30' }
      ]
    });
  }

  console.log('Seeded successfully. Admin login: admin@dvi.travel / admin123');
}

main().catch((e) => {
  console.error(e);
  process.exit(1);
}).finally(async () => {
  await prisma.$disconnect();
});
