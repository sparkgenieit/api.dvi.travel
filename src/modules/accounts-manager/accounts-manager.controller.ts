// FILE: src/modules/accounts-manager/accounts-manager.controller.ts

import { Body, Controller, Get, Post, Query } from "@nestjs/common";
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
import { Public } from "../../auth/public.decorator";
import {
  ApiBearerAuth,
  ApiBody,
  ApiOkResponse,
  ApiOperation,
  ApiTags,
} from "@nestjs/swagger";

@ApiTags("accounts-manager")
@Controller("accounts-manager")
export class AccountsManagerController {
  constructor(
    private readonly service: AccountsManagerService,
  ) {}

  /**
   * Main list endpoint – unchanged.
   * GET /accounts-manager
   *
   * Marked as @Public() so React can load the page without JWT
   * while you are wiring the UI. Remove @Public() later if you
   * want this behind auth.
   */
  @Public()
  @Get()
  @ApiOperation({
    summary: "List account manager rows",
    description:
      "Returns the flattened component rows (hotel/vehicle/guide/hotspot/activity) filtered by status, quote, date range, component type, agent, and search.",
  })
  @ApiOkResponse({ type: AccountsManagerRowDto, isArray: true })
  async list(
    @Query() query: AccountsManagerQueryDto,
  ): Promise<AccountsManagerRowDto[]> {
    return this.service.list(query);
  }

  /**
   * Summary cards (payable / paid / balance) based on same filters
   * as the list endpoint.
   * GET /accounts-manager/summary
   *
   * Also made @Public() for now so the top cards work without JWT.
   */
  @Public()
  @Get("summary")
  @ApiOperation({
    summary: "Get summary totals for current filter",
    description:
      "Returns aggregated totals (totalPayable, totalPaid, totalBalance, rowCount) using the same filters as the list endpoint.",
  })
  @ApiOkResponse({ type: AccountsManagerSummaryDto })
  async summary(
    @Query() query: AccountsManagerQueryDto,
  ): Promise<AccountsManagerSummaryDto> {
    return this.service.getSummary(query);
  }

  /**
   * Quote ID autocomplete – distinct itinerary_quote_ID values.
   * Frontend can call: GET /accounts-manager/quotes?q=ABC
   *
   * This is a read-only lookup, so it's safe to expose as @Public()
   * during development.
   */
  @Public()
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
   *
   * Lookup only, made @Public() to avoid 401 while wiring filters.
   */
  @Public()
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
   *
   * Lookup only, also @Public().
   */
  @Public()
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
   *
   * ⚠️ Temporarily marked @Public() so you can test from React
   * without wiring JWT. Once you’re done wiring, remove @Public()
   * to secure it again and keep @ApiBearerAuth() for Swagger.
   */
  @Public()
  @Post("pay")
  @ApiBearerAuth()
  @ApiOperation({
    summary: "Record a payment against a component row",
    description:
      "Updates the per-component totals (total_paid and total_balance) for the specified hotel/vehicle/guide/hotspot/activity row.",
  })
  @ApiBody({ type: AccountsManagerPayDto })
  @ApiOkResponse({ description: "Payment recorded" })
  async pay(
    @Body() body: AccountsManagerPayDto,
  ): Promise<void> {
    return this.service.recordPayment(body);
  }
}
