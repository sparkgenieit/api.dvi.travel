// FILE: src/modules/guides/guidecontroller.ts
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
import {
  GuidesService,
  GuideListQueryDto,
  GuideBasicDto,
  GuidePricebookSaveDto,
  GuideReviewSaveDto,
} from './guideservice';

@Controller('guides')
export class GuidesController {
  constructor(private readonly guides: GuidesService) {}

  // ───────────────────────────── List (DataTable) ─────────────────────────────
  @Get()
  async list(
    @Query('page') page?: string,
    @Query('size') size?: string,
    @Query('q') q?: string,
    @Query('status') status?: string,
  ) {
    const dto: GuideListQueryDto = {
      page: page ? Number(page) : undefined,
      size: size ? Number(size) : undefined,
      q: q ?? undefined,
      status:
        status !== undefined && status !== null && status !== ''
          ? Number(status)
          : undefined,
    };
    return this.guides.list(dto);
  }

  // ───────────────────── Dynamic dropdowns / form options ────────────────────
  @Get('options')
  async formOptions() {
    return this.guides.formOptions();
  }

  // ───────────────────────────── Get form (edit) ─────────────────────────────
  @Get(':id/form')
  async getForm(@Param('id', ParseIntPipe) id: number) {
    return this.guides.getForm(id);
  }

  // ───────────────────────────── Save Step 1 (basic) ─────────────────────────
  // Create (no id in payload) or Update (with id) — mirrors PHP behavior
  @Post()
  async saveFormStep1(@Body() body: GuideBasicDto) {
    return this.guides.saveFormStep1(body);
  }

  // Optional convenience route for explicit update by :id; merges :id into body
  @Put(':id')
  async updateBasic(
    @Param('id', ParseIntPipe) id: number,
    @Body() body: GuideBasicDto,
  ) {
    return this.guides.saveFormStep1({ ...body, id });
  }

  // ───────────────────────────── Save Step 2 (pricebook) ─────────────────────
  @Put(':id/pricebook')
  async savePricebook(
    @Param('id', ParseIntPipe) id: number,
    @Body() body: Omit<GuidePricebookSaveDto, 'guide_id'> & { guide_id?: number },
  ) {
    const payload: GuidePricebookSaveDto = {
      guide_id: body.guide_id && body.guide_id > 0 ? body.guide_id : id,
      start_date: body.start_date,
      end_date: body.end_date,
      pax_prices: body.pax_prices ?? [],
    };
    return this.guides.savePricebook(payload);
  }

  // Composite helper to save pricebook then return preview (to enable Next)
  @Put(':id/pricebook-and-preview')
  async savePricebookAndPreview(
    @Param('id', ParseIntPipe) id: number,
    @Body() body: Omit<GuidePricebookSaveDto, 'guide_id'> & { guide_id?: number },
  ) {
    const payload: GuidePricebookSaveDto = {
      guide_id: body.guide_id && body.guide_id > 0 ? body.guide_id : id,
      start_date: body.start_date,
      end_date: body.end_date,
      pax_prices: body.pax_prices ?? [],
    };
    return this.guides.saveFormStep2AndPreview(payload);
  }

  // ───────────────────────────── Step 3 (reviews) ────────────────────────────
  @Post(':id/reviews')
  async addReview(
    @Param('id', ParseIntPipe) id: number,
    @Body() body: Omit<GuideReviewSaveDto, 'guide_id'> & { guide_id?: number },
  ) {
    const payload: GuideReviewSaveDto = {
      guide_id: body.guide_id && body.guide_id > 0 ? body.guide_id : id,
      rating: body.rating,
      description: body.description,
    };
    return this.guides.addReview(payload);
  }

  @Get(':id/reviews')
  async listReviews(@Param('id', ParseIntPipe) id: number) {
    return this.guides.listReviews(id);
  }

  @Delete('reviews/:reviewId')
  async deleteReview(@Param('reviewId', ParseIntPipe) reviewId: number) {
    return this.guides.deleteReview(reviewId);
  }

  // ───────────────────────────── Step 4 (preview) ────────────────────────────
  @Get(':id/preview')
  async getPreview(@Param('id', ParseIntPipe) id: number) {
    return this.guides.getPreview(id);
  }

  /**
   * NEW: One-shot payload for the React Preview page (mirrors PHP overall preview flow).
   * Returns:
   * {
   *   preview: { basic, reviews, slots[], preferredFor[] },
   *   options: { states[], genders[], bloodGroups[], guideSlots[], languages[], gst[] }
   * }
   */
  @Get(':id/preview-page')
  async getPreviewPage(@Param('id', ParseIntPipe) id: number) {
    const [preview, options] = await Promise.all([
      this.guides.getPreview(id),
      this.guides.formOptions(),
    ]);
    return { preview, options };
  }

  /**
   * NEW alias using PHP-ish naming so you can hit /guides/:id/overallpreview
   * if your old client code expects that route. Same response as /preview-page.
   */
  @Get(':id/overallpreview')
  async getOverallPreview(@Param('id', ParseIntPipe) id: number) {
    const [preview, options] = await Promise.all([
      this.guides.getPreview(id),
      this.guides.formOptions(),
    ]);
    return { preview, options };
  }

  // ───────────────────────────── status / delete ─────────────────────────────
  @Patch(':id/status')
  async toggleStatus(
    @Param('id', ParseIntPipe) id: number,
    @Body('status') status: number,
  ) {
    return this.guides.toggleStatus(id, Number(status));
  }

  @Delete(':id')
  async softDelete(@Param('id', ParseIntPipe) id: number) {
    return this.guides.softDelete(id);
  }

  // ───────────────────────────── Dropdown Data (Controller) ─────────────────────────────

@Get('dropdowns/roles')
async getRolesDropdown() {
  return this.guides.getRolesDropdown();
}

@Get('dropdowns/languages')
async getLanguagesDropdown() {
  return this.guides.getLanguagesDropdown();
}

@Get('dropdowns/countries')
async getCountriesDropdown() {
  return this.guides.getCountriesDropdown();
}

/** Dependent: states by countryId */
@Get('dropdowns/states')
async getStatesDropdown(@Query('countryId') countryId?: string) {
  return this.guides.getStatesDropdown(Number(countryId));
}

/** Dependent: cities by stateId */
@Get('dropdowns/cities')
async getCitiesDropdown(@Query('stateId') stateId?: string) {
  return this.guides.getCitiesDropdown(Number(stateId));
}

/** GST types: Included(1), Excluded(2) */
@Get('dropdowns/gst-types')
async getGstTypesDropdown() {
  return this.guides.getGstTypesDropdown();
}

/** GST% list from dvi_gst_setting.gst_title */
@Get('dropdowns/gst-percentages')
async getGstPercentagesDropdown() {
  return this.guides.getGstPercentagesDropdown();
}

/** Hotspot places from dvi_hotspot_place.hotspot_name */
@Get('dropdowns/hotspots')
async getHotspotPlacesDropdown() {
  return this.guides.getHotspotPlacesDropdown();
}

/** Activities from dvi_activity.activity_title */
@Get('dropdowns/activities')
async getActivitiesDropdown() {
  return this.guides.getActivitiesDropdown();
}

/**
 * One-shot fetch for all dropdowns.
 * Optional query params: countryId (to scope states), stateId (to scope cities)
 * Example:
 *   GET /guides/dropdowns/all?countryId=101&stateId=33
 */
@Get('dropdowns/all')
async getAllDropdowns(
  @Query('countryId') countryId?: string,
  @Query('stateId') stateId?: string,
) {
  return this.guides.getAllDropdowns({
    countryId: countryId ? Number(countryId) : undefined,
    stateId: stateId ? Number(stateId) : undefined,
  });
}
}
