import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  ParseIntPipe,
  Post,
  Put,
  Query,
} from "@nestjs/common";
import { CitiesService } from "./cities.service";
import { ListCitiesQueryDto } from "./dto/list-cities.query.dto";
import { CreateCityDto } from "./dto/create-city.dto";
import { UpdateCityDto } from "./dto/update-city.dto";
import { SuggestCitiesDto } from "./dto/suggest-cities.dto";
import { CheckCityDuplicateDto } from "./dto/check-city.dto";

@Controller("cities")
export class CitiesController {
  constructor(private readonly service: CitiesService) {}

  // ✅ DEBUG: confirms Prisma DB + counts
  @Get("debug")
  debug(@Query() q: ListCitiesQueryDto) {
    return this.service.debug(q.countryId ?? 101);
  }

  @Get()
  list(@Query() q: ListCitiesQueryDto) {
  return {
    __handler: "CitiesController.list",
    countryId: q.countryId ?? 101,
    ts: new Date().toISOString(),
  };
}

  @Get("states")
  listStates(@Query() q: ListCitiesQueryDto) {
    return this.service.listStates(q.countryId ?? 101);
  }

  @Post("suggest")
  suggest(@Body() dto: SuggestCitiesDto) {
    return this.service.suggest(dto);
  }

  @Post("check-duplicate")
  checkDuplicate(@Body() dto: CheckCityDuplicateDto) {
    return this.service.checkDuplicate(dto);
  }

  // ✅ must be before ":id"
  @Get(":id/delete-usage")
  deleteUsage(@Param("id", ParseIntPipe) id: number) {
    return this.service.getDeleteUsageCount(id);
  }

  @Get(":id")
  getOne(@Param("id", ParseIntPipe) id: number) {
    return this.service.getOne(id);
  }

  @Post()
  create(@Body() dto: CreateCityDto) {
    return this.service.create(dto);
  }

  @Put(":id")
  update(@Param("id", ParseIntPipe) id: number, @Body() dto: UpdateCityDto) {
    return this.service.update(id, dto);
  }

  @Delete(":id")
  remove(@Param("id", ParseIntPipe) id: number) {
    return this.service.softDelete(id);
  }
}
