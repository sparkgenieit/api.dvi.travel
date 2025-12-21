// FILE: src/modules/accounts-manager/accounts-manager.controller.ts

import { Body, Controller, Get, Post, Query, UseGuards, Req } from "@nestjs/common";
import { AccountsManagerService } from "./accounts-manager.service";
import { AccountsManagerQueryDto } from "./dto/accounts-manager-query.dto";
import { AccountsManagerRowDto } from "./dto/accounts-manager-row.dto";
import {
  AccountsManagerSummaryDto,
  AccountsManagerQuoteDto,
  AccountsManagerAgentDto,
  AccountsManagerPaymentModeDto,
  AccountsManagerPayDto,
} from "./dto/accounts-manager-extra.dto";
import {
  ApiBearerAuth,
  ApiBody,
  ApiOkResponse,
  ApiOperation,
  ApiTags,
} from "@nestjs/swagger";
import { JwtAuthGuard } from "../../auth/jwt-auth.guard";

@ApiTags("accounts-manager")
@ApiBearerAuth() // uses default bearer auth from main.ts
@Controller("accounts-manager")
export class AccountsManagerController {
  constructor(
    private readonly service: AccountsManagerService,
  ) {}

  /**
   * Main list endpoint.
   * GET /accounts-manager
   */
  @UseGuards(JwtAuthGuard)
  @Get()
  @ApiOperation({
    summary: "List account manager rows",
    description:
      "Returns the flattened component rows (hotel/vehicle/guide/hotspot/activity) filtered by status, quote, date range, component type, agent, and search.",
  })
  @ApiOkResponse({ type: AccountsManagerRowDto, isArray: true })
  async list(
    @Req() req: any,
    @Query() query: AccountsManagerQueryDto,
  ): Promise<AccountsManagerRowDto[]> {
    const user = req.user;
    // Role 4 is Agent
    if (user.role === 4) {
      query.agentId = Number(user.agentId);
    } else if (user.role === 6) {
      // Accounts role - see everything
    } else if (user.role === 3 || user.role === 8 || (user.staffId && user.staffId > 0)) {
      // Travel Expert logic
      (query as any).travelExpertId = Number(user.staffId);
    }
    return this.service.list(query);
  }

  /**
   * Summary cards (payable / paid / balance) based on same filters
   * as the list endpoint.
   * GET /accounts-manager/summary
   */
  @UseGuards(JwtAuthGuard)
  @Get("summary")
  @ApiOperation({
    summary: "Get summary totals for current filter",
    description:
      "Returns aggregated totals (totalPayable, totalPaid, totalBalance, rowCount) using the same filters as the list endpoint.",
  })
  @ApiOkResponse({ type: AccountsManagerSummaryDto })
  async summary(
    @Req() req: any,
    @Query() query: AccountsManagerQueryDto,
  ): Promise<AccountsManagerSummaryDto> {
    const user = req.user;
    // Role 4 is Agent
    if (user.role === 4) {
      query.agentId = Number(user.agentId);
    } else if (user.role === 6) {
      // Accounts role - see everything
    } else if (user.role === 3 || user.role === 8 || (user.staffId && user.staffId > 0)) {
      (query as any).travelExpertId = Number(user.staffId);
    }
    return this.service.getSummary(query);
  }

  /**
   * Quote ID autocomplete – distinct itinerary_quote_ID values.
   * GET /accounts-manager/quotes?q=ABC
   */
  @Get("quotes")
  @ApiOperation({
    summary: "Search quote IDs",
    description:
      "Returns a list of matching quote IDs for the autocomplete field.",
  })
  @ApiOkResponse({ type: AccountsManagerQuoteDto, isArray: true })
  async quotes(
    @Query("q") phrase?: string,
  ): Promise<AccountsManagerQuoteDto[]> {
    return this.service.searchQuotes(phrase ?? "");
  }

  /**
   * Agent dropdown for the filter.
   * GET /accounts-manager/agents
   */
  @Get("agents")
  @ApiOperation({
    summary: "List agents for filter dropdown",
  })
  @ApiOkResponse({ type: AccountsManagerAgentDto, isArray: true })
  async agents(): Promise<AccountsManagerAgentDto[]> {
    return this.service.listAgents();
  }

  /**
   * Mode of payment list for Pay Now modal.
   * GET /accounts-manager/payment-modes
   */
  @Get("payment-modes")
  @ApiOperation({
    summary: "List modes of payment",
    description:
      "Returns the available payment modes (e.g. Cash, UPI, Net Banking) for the Pay Now modal.",
  })
  @ApiOkResponse({ type: AccountsManagerPaymentModeDto, isArray: true })
  async paymentModes(): Promise<AccountsManagerPaymentModeDto[]> {
    return this.service.listPaymentModes();
  }

  /**
   * Pay Now – updates total_paid and total_balance for a component row.
   * POST /accounts-manager/pay
   */
  @Post("pay")
  @ApiOperation({
    summary: "Record a payment against a component row",
    description:
      "Updates the per-component totals (total_paid and total_balance) for the specified hotel/vehicle/guide/hotspot/activity row.",
  })
  @ApiBearerAuth() // optional (redundant but explicit for this route)
  @ApiBody({ type: AccountsManagerPayDto })
  @ApiOkResponse({ description: "Payment recorded" })
  async pay(
    @Body() body: AccountsManagerPayDto,
  ): Promise<void> {
    return this.service.recordPayment(body);
  }
}
