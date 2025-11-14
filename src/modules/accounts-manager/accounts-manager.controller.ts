import { Controller, Get, Query } from "@nestjs/common";
import { AccountsManagerService } from "./accounts-manager.service";
import { AccountsManagerQueryDto } from "./dto/accounts-manager-query.dto";
import { AccountsManagerRowDto } from "./dto/accounts-manager-row.dto";

@Controller("accounts-manager")
export class AccountsManagerController {
  constructor(
    private readonly service: AccountsManagerService,
  ) {}

  @Get()
  async list(
    @Query() query: AccountsManagerQueryDto,
  ): Promise<AccountsManagerRowDto[]> {
    return this.service.list(query);
  }
}
