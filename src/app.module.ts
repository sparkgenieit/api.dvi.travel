import { Module } from '@nestjs/common';
import { APP_GUARD } from '@nestjs/core';
import { RolesGuard } from './auth/roles.guard';
import { JwtAuthGuard } from './auth/jwt-auth.guard';
import { UsersModule } from './modules/users/users.module';
import { HotelsModule } from './modules/hotels/hotels.module';
import { AuthModule } from './modules/auth/auth.module';
import {AccountsManagerModule} from './modules/accounts-manager/accounts-manager.module';
import { AccountsLedgerModule } from './modules/accounts-ledger/accounts-ledger.module';
import { ItineraryModule } from './modules/itinerary/itinerary.module';
import {ItineraryDropdownsModule  } from './modules/itinerary-dropdowns/itinerary-dropdowns.module';
import { DailyMomentTrackerModule } from './modules/daily-moment-tracker/daily-moment-tracker.module';
import { AgentsModule } from './modules/agents/agents.module';
import {ItineraryViaRoutesModule} from './modules/itinerary-via-routes/itinerary-via-routes.module';
import { VendorsModule } from './modules/vendors/vendors.module';


@Module({
  imports: [AuthModule, UsersModule,VendorsModule,AgentsModule,ItineraryViaRoutesModule,HotelsModule,DailyMomentTrackerModule, ItineraryDropdownsModule, ItineraryModule,AccountsManagerModule, AccountsLedgerModule],
  providers: [
    { provide: APP_GUARD, useClass: JwtAuthGuard },
    { provide: APP_GUARD, useClass: RolesGuard },
  ],
})
export class AppModule {}
