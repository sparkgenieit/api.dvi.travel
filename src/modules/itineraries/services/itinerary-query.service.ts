import { Injectable, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../../../prisma.service';

/**
 * Itinerary Query Service
 * Handles all query/retrieval operations for itineraries
 * - Get plan for edit
 * - Get customer info form
 * - Check wallet balances
 * - Get agents for filter
 */
@Injectable()
export class ItineraryQueryService {
  constructor(private readonly prisma: PrismaService) {}

  /**
   * Get plan details for editing
   */
  async getPlanForEdit(planId: number) {
    // Fetch the plan
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: planId },
    });

    if (!plan) {
      throw new BadRequestException(`Plan ${planId} not found`);
    }

    // Fetch routes
    const routes = await this.prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: planId, deleted: 0 },
      orderBy: { no_of_days: 'asc' },
    });

    // Fetch via routes for each route
    const routesWithVia = await Promise.all(
      routes.map(async (route) => {
        const viaRoutes = await this.prisma.dvi_itinerary_via_route_details.findMany({
          where: {
            itinerary_plan_ID: planId,
            itinerary_route_ID: route.itinerary_route_ID,
            deleted: 0,
          },
          orderBy: { itinerary_via_route_ID: 'asc' },
        });

        return {
          ...route,
          via_routes: viaRoutes.map(v => ({
            itinerary_via_location_ID: v.itinerary_via_location_ID,
            itinerary_via_location_name: v.itinerary_via_location_name,
          })),
        };
      }),
    );

    // Fetch vehicles - note: this table uses lowercase itinerary_plan_id
    const vehicles = await this.prisma.dvi_itinerary_plan_vehicle_details.findMany({
      where: { itinerary_plan_id: planId, deleted: 0 },
      orderBy: { vehicle_details_ID: 'asc' },
    });

    return {
      plan,
      routes: routesWithVia,
      vehicles,
    };
  }

  /**
   * Get customer info form data
   */
  async getCustomerInfoForm(planId: number) {
    // Get plan details
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: planId },
      select: {
        itinerary_quote_ID: true,
        agent_id: true,
      },
    });

    if (!plan) {
      throw new BadRequestException('Itinerary plan not found');
    }

    // Get agent details
    const agent = await this.prisma.dvi_agent.findUnique({
      where: { agent_ID: plan.agent_id },
      select: {
        agent_name: true,
        total_cash_wallet: true,
      },
    });

    if (!agent) {
      throw new BadRequestException('Agent not found');
    }

    const walletBalance = Number(agent.total_cash_wallet || 0);
    const formattedBalance = walletBalance.toLocaleString('en-IN', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });

    return {
      quotation_no: plan.itinerary_quote_ID || '',
      agent_name: agent.agent_name,
      agent_id: plan.agent_id,
      wallet_balance: formattedBalance,
      balance_sufficient: walletBalance > 0,
    };
  }

  /**
   * Check wallet balance for an agent
   */
  async checkWalletBalance(agentId: number) {
    const agent = await this.prisma.dvi_agent.findUnique({
      where: { agent_ID: agentId },
      select: {
        total_cash_wallet: true,
      },
    });

    if (!agent) {
      throw new BadRequestException('Agent not found');
    }

    const balance = Number(agent.total_cash_wallet || 0);
    const formattedBalance = `₹ ${balance.toLocaleString('en-IN', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })}`;

    return {
      balance,
      formatted_balance: formattedBalance,
      is_sufficient: balance > 0,
    };
  }

  /**
   * Get agent wallet balance
   */
  async getAgentWalletBalance(agentId: number) {
    const agent = await this.prisma.dvi_agent.findUnique({
      where: { agent_ID: agentId },
      select: { total_cash_wallet: true },
    });

    return { balance: Number(agent?.total_cash_wallet || 0) };
  }

  /**
   * Get agents list for filter
   */
  async getAgentsForFilter(req: any) {
    const agents = await this.prisma.dvi_agent.findMany({
      where: {
        agent_status: 1,
        deleted: 0,
      },
      select: {
        agent_ID: true,
        agent_name: true,
      },
      orderBy: { agent_name: 'asc' },
    });

    return agents.map(a => ({
      id: a.agent_ID,
      name: a.agent_name,
    }));
  }
}
