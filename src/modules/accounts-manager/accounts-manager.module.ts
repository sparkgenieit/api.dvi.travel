import { Module } from "@nestjs/common";
import { AccountsManagerController } from "./accounts-manager.controller";
import { AccountsManagerService } from "./accounts-manager.service";
import { PrismaService } from "../../prisma.service";

@Module({
  controllers: [AccountsManagerController],
  providers: [AccountsManagerService, PrismaService],
  exports: [AccountsManagerService],
})
export class AccountsManagerModule {}
