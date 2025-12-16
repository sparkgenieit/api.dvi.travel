import { Injectable } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';

@Injectable()
export class DashboardService {
  constructor(private prisma: PrismaService) {}

  async getDashboardStats() {
    // Get counts in parallel for better performance
    const [
      totalAgents,
      totalDrivers,
      totalGuides,
      totalItineraries,
      confirmedBookings,
      cancelledBookings,
      totalVehicles,
      totalVendors,
      totalHotels,
      totalHotelRooms,
      totalAmenities,
    ] = await Promise.all([
      // Total Agents
      this.prisma.dvi_agent.count({
        where: { deleted: 0 },
      }),

      // Total Drivers
      this.prisma.dvi_driver_details.count({
        where: { deleted: 0 },
      }),

      // Total Guides
      this.prisma.dvi_guide_details.count({
        where: { deleted: 0 },
      }),

      // Total Itineraries
      this.prisma.dvi_itinerary_plan_details.count({
        where: { deleted: 0 },
      }),

      // Confirmed Bookings
      this.prisma.dvi_itinerary_plan_details.count({
        where: { deleted: 0, quotation_status: 1 },
      }),

      // Cancelled Bookings (assuming there's a cancelled status)
      this.prisma.dvi_itinerary_plan_details.count({
        where: { deleted: 1 },
      }),

      // Total Vehicles
      this.prisma.dvi_vehicle_type.count({
        where: { deleted: 0 },
      }),

      // Total Vendors
      this.prisma.dvi_vendor_details.count({
        where: { deleted: 0 },
      }),

      // Total Hotels
      this.prisma.dvi_hotel.count({
        where: { deleted: false },
      }),

      // Total Hotel Rooms
      this.prisma.dvi_hotel_roomtype.count({
        where: { deleted: 0 },
      }),

      // Total Amenities
      this.prisma.dvi_hotel_amenities.count({
        where: { deleted: 0 },
      }),
    ]);

    // Get vendor branches count
    const vendorBranches: any = await this.prisma.$queryRaw`
      SELECT COUNT(DISTINCT vendor_branch_id) as count 
      FROM dvi_vendor_branches 
      WHERE deleted = 0
    `;
    const totalVendorBranches = Number(vendorBranches[0]?.count || 0);

    // Get inactive vendors
    const inactiveVendors = await this.prisma.dvi_vendor_details.count({
      where: { deleted: 0, status: 0 },
    });

    // Get driver stats
    const activeDrivers = await this.prisma.dvi_driver_details.count({
      where: { deleted: 0, status: 1 },
    });

    const inactiveDrivers = await this.prisma.dvi_driver_details.count({
      where: { deleted: 0, status: 0 },
    });

    // Get revenue data (this is a placeholder - adjust based on your actual revenue tracking)
    const currentMonth = new Date().getMonth() + 1;
    const currentYear = new Date().getFullYear();
    const lastMonth = currentMonth === 1 ? 12 : currentMonth - 1;
    const lastMonthYear = currentMonth === 1 ? currentYear - 1 : currentYear;

    // Calculate total revenue from confirmed itineraries
    const totalRevenueData: any = await this.prisma.$queryRaw`
      SELECT COALESCE(SUM(expecting_budget), 0) as total
      FROM dvi_itinerary_plan_details
      WHERE deleted = 0 AND quotation_status = 1
    `;
    const totalRevenue = Number(totalRevenueData[0]?.total || 0);

    // Get current month profit
    const currentMonthProfit: any = await this.prisma.$queryRaw`
      SELECT COALESCE(SUM(expecting_budget), 0) as total
      FROM dvi_itinerary_plan_details
      WHERE deleted = 0 
        AND quotation_status = 1
        AND MONTH(trip_start_date_and_time) = ${currentMonth}
        AND YEAR(trip_start_date_and_time) = ${currentYear}
    `;

    // Get last month profit
    const lastMonthProfit: any = await this.prisma.$queryRaw`
      SELECT COALESCE(SUM(expecting_budget), 0) as total
      FROM dvi_itinerary_plan_details
      WHERE deleted = 0 
        AND quotation_status = 1
        AND MONTH(trip_start_date_and_time) = ${lastMonth}
        AND YEAR(trip_start_date_and_time) = ${lastMonthYear}
    `;

    const currentProfit = Number(currentMonthProfit[0]?.total || 0);
    const lastProfit = Number(lastMonthProfit[0]?.total || 0);

    // Calculate percentage change
    let profitChange = 0;
    if (lastProfit > 0) {
      profitChange = ((currentProfit - lastProfit) / lastProfit) * 100;
    }

    // Get daily moment data (today's itineraries)
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    const dailyMoments = await this.prisma.dvi_itinerary_plan_details.findMany({
      where: {
        deleted: 0,
        trip_start_date_and_time: {
          gte: today,
          lt: tomorrow,
        },
      },
      select: {
        itinerary_quote_ID: true,
        arrival_location: true,
      },
      take: 5,
    });

    // Get top agent (placeholder - adjust based on your rating/performance logic)
    const topAgent = await this.prisma.dvi_agent.findFirst({
      where: { deleted: 0 },
      select: {
        agent_name: true,
        agent_primary_mobile_number: true,
      },
      orderBy: { agent_ID: 'desc' },
    });

    return {
      stats: {
        totalAgents,
        totalDrivers,
        totalGuides,
        totalItineraries,
        totalRevenue,
        confirmedBookings,
        cancelledBookings,
      },
      profit: {
        lastMonth: lastProfit,
        currentMonth: currentProfit,
        percentageChange: Number(profitChange.toFixed(2)),
      },
      vehicles: {
        total: totalVehicles,
        onRoute: 0, // Placeholder - implement based on your vehicle tracking
        available: totalVehicles,
        upcoming: 0,
      },
      vendors: {
        total: totalVendors,
        branches: totalVendorBranches,
        inactive: inactiveVendors,
      },
      drivers: {
        total: totalDrivers,
        active: activeDrivers,
        inactive: inactiveDrivers,
        onRoute: 0, // Placeholder
        available: activeDrivers,
      },
      hotels: {
        total: totalHotels,
        rooms: totalHotelRooms,
        amenities: totalAmenities,
        bookings: confirmedBookings,
      },
      dailyMoment: dailyMoments.map((dm) => ({
        quoteId: dm.itinerary_quote_ID,
        location: dm.arrival_location,
      })),
      starPerformer: topAgent
        ? {
            name: topAgent.agent_name,
            phone: topAgent.agent_primary_mobile_number,
            performance: 60, // Placeholder
          }
        : null,
    };
  }
}
