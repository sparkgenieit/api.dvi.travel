// FILE: src/modules/itinerary-dropdowns/itinerary-dropdowns.controller.ts

import { Controller, Get } from '@nestjs/common';
import { ApiBearerAuth, ApiTags } from '@nestjs/swagger';
import { ItineraryDropdownsService } from './itinerary-dropdowns.service';

@ApiTags('Itinerary Dropdowns')
@ApiBearerAuth() // uses the default bearer auth from main.ts
@Controller('itinerary-dropdowns')
export class ItineraryDropdownsController {
  constructor(private readonly svc: ItineraryDropdownsService) {}

  @Get('locations')
  locations() {
    return this.svc.getLocations();
  }

  @Get('itinerary-types')
  itineraryTypes() {
    return this.svc.getItineraryTypes();
  }

  @Get('travel-types')
  travelTypes() {
    return this.svc.getTravelTypes();
  }

  @Get('entry-ticket-options')
  entryTicketOptions() {
    return this.svc.getEntryTicketOptions();
  }

  @Get('guide-options')
  guideOptions() {
    return this.svc.getGuideOptions();
  }

  @Get('nationalities')
  nationalities() {
    return this.svc.getNationalities();
  }

  @Get('food-preferences')
  foodPreferences() {
    return this.svc.getFoodPreferences();
  }

  @Get('vehicle-types')
  vehicleTypes() {
    return this.svc.getVehicleTypes();
  }

  @Get('hotel-categories')
  hotelCategories() {
    return this.svc.getHotelCategories();
  }

  @Get('hotel-facilities')
  hotelFacilities() {
    return this.svc.getHotelFacilities();
  }
}
