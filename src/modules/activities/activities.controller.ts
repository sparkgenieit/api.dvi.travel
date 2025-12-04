import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  ParseIntPipe,
  Patch,
  Post,
  Put,
  Query,
} from '@nestjs/common';
import { ActivitiesService } from './activities.service';
import { CreateActivityDto } from './dto/create-activity.dto';
import { UpdateActivityDto } from './dto/update-activity.dto';
import { SaveTimeSlotsDto } from './dto/save-timeslots.dto';
import { SavePriceBookDto } from './dto/save-pricebook.dto';
import { SaveReviewDto } from './dto/save-review.dto';
import { ToggleStatusDto } from './dto/toggle-status.dto';

@Controller('activities')
export class ActivitiesController {
  constructor(private readonly service: ActivitiesService) {}

  // === LIST (DataTables style shape, but clean JSON) ===
  @Get()
  async list(
    @Query('q') q?: string,
    @Query('status') status?: '0' | '1',
  ) {
    return this.service.list({ q, status: status as any });
  }

  // === HOTSPOTS (for dropdown) ===
  @Get('hotspots')
  async hotspots(@Query('q') q?: string) {
    return this.service.hotspots(q);
  }

  // === BASIC INFO (Create) ===
  @Post()
  async create(@Body() dto: CreateActivityDto) {
    return this.service.createActivity(dto);
  }

  // === BASIC INFO (Update) ===
  @Put(':id')
  async update(
    @Param('id', ParseIntPipe) id: number,
    @Body() dto: UpdateActivityDto,
  ) {
    return this.service.updateActivity(id, dto);
  }

  // === STATUS TOGGLE ===
  @Patch(':id/status')
  async toggleStatus(
    @Param('id', ParseIntPipe) id: number,
    @Body() dto: ToggleStatusDto,
  ) {
    return this.service.toggleStatus(id, dto.status);
  }

  // === DELETE (soft) ===
  @Delete(':id')
  async softDelete(@Param('id', ParseIntPipe) id: number) {
    return this.service.softDelete(id);
  }

  // === GALLERY ===
  @Post(':id/images')
  async addImages(
    @Param('id', ParseIntPipe) id: number,
    @Body() body: { imageNames: string[]; createdby?: number },
  ) {
    return this.service.addImages(id, body.imageNames ?? [], body.createdby ?? 0);
  }

  @Delete(':id/images/:imageId')
  async deleteImage(
    @Param('id', ParseIntPipe) id: number,
    @Param('imageId', ParseIntPipe) imageId: number,
  ) {
    return this.service.deleteImage(id, imageId);
  }

  // === TIME SLOTS (default + special) ===
  @Post(':id/time-slots')
  async saveTimeSlots(
    @Param('id', ParseIntPipe) id: number,
    @Body() dto: SaveTimeSlotsDto,
  ) {
    return this.service.saveTimeSlots(id, dto);
  }

  // === PRICE BOOK (month rows with day_1..day_31) ===
  @Post(':id/pricebook')
  async savePriceBook(
    @Param('id', ParseIntPipe) id: number,
    @Body() dto: SavePriceBookDto,
  ) {
    return this.service.savePriceBook(id, dto);
  }

  // === REVIEWS ===
  @Post(':id/reviews')
  async addReview(
    @Param('id', ParseIntPipe) id: number,
    @Body() dto: SaveReviewDto,
  ) {
    return this.service.addOrUpdateReview(id, dto);
  }

  @Put(':id/reviews/:reviewId')
  async updateReview(
    @Param('id', ParseIntPipe) id: number,
    @Param('reviewId', ParseIntPipe) reviewId: number,
    @Body() dto: SaveReviewDto,
  ) {
    return this.service.addOrUpdateReview(id, { ...dto, reviewId });
  }

  @Delete(':id/reviews/:reviewId')
  async deleteReview(
    @Param('id', ParseIntPipe) id: number,
    @Param('reviewId', ParseIntPipe) reviewId: number,
  ) {
    return this.service.deleteReview(id, reviewId);
  }

  // === PREVIEW (aggregate read) ===
  @Get(':id/preview')
  async preview(@Param('id', ParseIntPipe) id: number) {
    return this.service.preview(id);
  }

  // === DETAILS (basic info + gallery + latest slots) ===
  @Get(':id')
  async details(@Param('id', ParseIntPipe) id: number) {
    return this.service.details(id);
  }
}

