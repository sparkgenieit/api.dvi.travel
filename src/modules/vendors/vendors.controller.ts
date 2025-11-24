// FILE: src/modules/vendors/vendors.controller.ts

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
} from '@nestjs/common';
import {
  ApiOkResponse,
  ApiOperation,
  ApiTags,
} from '@nestjs/swagger';
import { VendorsService } from './vendors.service';
import { VendorListItemDto } from './dto/vendor-list-item.dto';
import { Public } from '../../auth/public.decorator';

type DropdownItem = {
  id: string;
  label: string;
};

@ApiTags('vendors')
@Controller('vendors')
export class VendorsController {
  constructor(private readonly vendorsService: VendorsService) {}

  /**
   * List vendors for the main Vendors grid.
   *
   * This is equivalent to the old engine/json/__JSONvendor.php,
   * but returns a clean JSON collection of VendorListItemDto.
   *
   * NOTE: Marked as @Public() so it can be used before auth is fully wired.
   * You can remove @Public() later if needed.
   */
  @Public()
  @Get()
  @ApiOperation({
    summary: 'List vendors',
    description:
      'Returns all active vendors from dvi_vendor_details along with computed branch counts from dvi_vendor_branches.',
  })
  @ApiOkResponse({ type: VendorListItemDto, isArray: true })
  async list(): Promise<VendorListItemDto[]> {
    return this.vendorsService.listVendors();
  }

  /**
   * Get full vendor details (basic info + branches) for the edit form.
   * This is what the React "Edit Vendor" form should call for pre-fill.
   */
  @Public()
  @Get(':id')
  @ApiOperation({
    summary: 'Get vendor detail',
    description:
      'Loads a single vendor row from dvi_vendor_details and all its non-deleted branches from dvi_vendor_branches.',
  })
  async getOne(
    @Param('id', ParseIntPipe) id: number,
  ): Promise<any> {
    return this.vendorsService.getVendorDetail(id);
  }

  /**
   * Create a new vendor (basic info step).
   *
   * The payload should mirror what the old PHP form was posting to
   * __ajax_manage_vendor.php?type=vendor_basic_info, i.e. use column-style
   * keys such as vendor_name, vendor_code, vendor_primary_mobile_number,
   * vendor_email, vendor_margin, vendor_margin_gst_type, etc.
   */
  @Post()
  @ApiOperation({
    summary: 'Create vendor basic info',
    description:
      'Creates a new row in dvi_vendor_details and returns data suitable for the vendor edit wizard pre-fill.',
  })
  async create(
    @Body() body: any,
  ): Promise<any> {
    return this.vendorsService.createVendorBasicInfo(body);
  }

  /**
   * Update vendor basic info for an existing vendor.
   * Called when saving the Basic Info step in the edit form.
   */
  @Put(':id')
  @ApiOperation({
    summary: 'Update vendor basic info',
    description:
      'Updates dvi_vendor_details for the given vendor_id using the payload coming from the vendor form.',
  })
  async update(
    @Param('id', ParseIntPipe) id: number,
    @Body() body: any,
  ): Promise<any> {
    return this.vendorsService.updateVendorBasicInfo(id, body);
  }

  @Public()
  @Delete(':id')
  @ApiOperation({
    summary: 'Soft delete vendor',
    description:
      'Marks vendor and its branches as deleted (deleted = 1, status = 0) in dvi_vendor_details and dvi_vendor_branches.',
  })
  async deleteVendor(
    @Param('id', ParseIntPipe) id: number,
  ): Promise<{ success: boolean }> {
    await this.vendorsService.softDeleteVendor(id);
    return { success: true };
  }

  /**
   * List all branches for a vendor.
   * Can be used to hydrate a separate Branches tab if needed.
   */
  @Public()
  @Get(':id/branches')
  @ApiOperation({
    summary: 'List vendor branches',
    description:
      'Returns all non-deleted branches from dvi_vendor_branches for the given vendor_id.',
  })
  async listBranches(
    @Param('id', ParseIntPipe) id: number,
  ): Promise<any[]> {
    return this.vendorsService.listBranches(id);
  }

  /**
   * Create a new branch for a vendor.
   * Equivalent to __ajax_manage_vendor.php?type=vendor_branch (insert).
   */
  @Post(':id/branches')
  @ApiOperation({
    summary: 'Create vendor branch',
    description:
      'Inserts a new row into dvi_vendor_branches linked to the given vendor_id.',
  })
  async createBranch(
    @Param('id', ParseIntPipe) id: number,
    @Body() body: any,
  ): Promise<any> {
    return this.vendorsService.createBranch(id, body);
  }

  /**
   * Update an existing vendor branch.
   * Equivalent to __ajax_manage_vendor.php?type=vendor_branch (update).
   */
  @Put('branches/:branchId')
  @ApiOperation({
    summary: 'Update vendor branch',
    description:
      'Updates a row in dvi_vendor_branches identified by vendor_branch_id.',
  })
  async updateBranch(
    @Param('branchId', ParseIntPipe) branchId: number,
    @Body() body: any,
  ): Promise<any> {
    return this.vendorsService.updateBranch(branchId, body);
  }

  /**
   * Soft-delete a vendor branch (marks deleted = 1).
   * Mirrors __ajax_manage_vendor.php?type=confirm_branch_delete.
   */
  @Delete('branches/:branchId')
  @ApiOperation({
    summary: 'Delete vendor branch',
    description:
      'Soft-deletes a row in dvi_vendor_branches by setting deleted = 1.',
  })
  async deleteBranch(
    @Param('branchId', ParseIntPipe) branchId: number,
  ): Promise<{ success: boolean }> {
    await this.vendorsService.softDeleteBranch(branchId);
    return { success: true };
  }

  // ===========================================================================
  // EXISTING: Vendor-scoped dropdown endpoints (keep as-is for compatibility)
  // These stay under /vendors/dropdowns/*
  // ===========================================================================

  @Public()
  @Get('dropdowns/roles')
  @ApiOperation({
    summary: 'Vendor form - role options',
    description: 'Returns role options from dvi_rolemenu for the Vendor form.',
  })
  @ApiOkResponse({
    schema: {
      type: 'object',
      properties: {
        items: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              id: { type: 'string' },
              label: { type: 'string' },
            },
          },
        },
      },
    },
  })
  async getRoleOptions(): Promise<{ items: DropdownItem[] }> {
    return this.vendorsService.getRoleOptions();
  }

  @Public()
  @Get('dropdowns/countries')
  @ApiOperation({
    summary: 'Vendor form - country options',
    description: 'Returns country options from dvi_countries for the Vendor form.',
  })
  @ApiOkResponse({
    schema: {
      type: 'object',
      properties: {
        items: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              id: { type: 'string' },
              label: { type: 'string' },
            },
          },
        },
      },
    },
  })
  async getCountryOptions(): Promise<{ items: DropdownItem[] }> {
    return this.vendorsService.getCountryOptions();
  }

  @Public()
  @Get('dropdowns/states')
  @ApiOperation({
    summary: 'Vendor form - state options',
    description:
      'Returns state options from dvi_states filtered by countryId for the Vendor form.',
  })
  @ApiOkResponse({
    schema: {
      type: 'object',
      properties: {
        items: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              id: { type: 'string' },
              label: { type: 'string' },
            },
          },
        },
      },
    },
  })
  async getStateOptions(
    @Query('countryId') countryId: string,
  ): Promise<{ items: DropdownItem[] }> {
    return this.vendorsService.getStateOptions(countryId);
  }

  @Public()
  @Get('dropdowns/cities')
  @ApiOperation({
    summary: 'Vendor form - city options',
    description:
      'Returns city options from dvi_cities filtered by stateId for the Vendor form.',
  })
  @ApiOkResponse({
    schema: {
      type: 'object',
      properties: {
        items: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              id: { type: 'string' },
              label: { type: 'string' },
            },
          },
        },
      },
    },
  })
  async getCityOptions(
    @Query('stateId') stateId: string,
  ): Promise<{ items: DropdownItem[] }> {
    return this.vendorsService.getCityOptions(stateId);
  }

  @Public()
  @Get('dropdowns/gst-types')
  @ApiOperation({
    summary: 'Vendor form - GST type options',
    description:
      'Returns GST type options (Included / Excluded) from dvi_gst_setting for the Vendor form.',
  })
  @ApiOkResponse({
    schema: {
      type: 'object',
      properties: {
        items: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              id: { type: 'string' },
              label: { type: 'string' },
            },
          },
        },
      },
    },
  })
  async getGstTypeOptions(): Promise<{ items: DropdownItem[] }> {
    return this.vendorsService.getGstTypeOptions();
  }

  @Public()
  @Get('dropdowns/gst-percents')
  @ApiOperation({
    summary: 'Vendor form - GST percentage options',
    description:
      'Returns GST percentage options from dvi_gst_setting for the Vendor form.',
  })
  @ApiOkResponse({
    schema: {
      type: 'object',
      properties: {
        items: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              id: { type: 'string' },
              label: { type: 'string' },
            },
          },
        },
      },
    },
  })
  async getGstPercentOptions(): Promise<{ items: DropdownItem[] }> {
    return this.vendorsService.getGstPercentOptions();
  }
}

// ===========================================================================
// NEW: Top-level /dropdowns/* controller used by React form
// This matches calls like api("/dropdowns/countries") etc.
// ===========================================================================

@ApiTags('dropdowns')
@Public()
@Controller('dropdowns')
export class VendorsDropdownsController {
  constructor(private readonly vendorsService: VendorsService) {}

  @Get('roles')
  @ApiOperation({
    summary: 'Role dropdown (flat array)',
  })
  async roles(): Promise<DropdownItem[]> {
    const { items } = await this.vendorsService.getRoleOptions();
    return items;
  }

  @Get('countries')
  @ApiOperation({
    summary: 'Country dropdown (flat array)',
  })
  async countries(): Promise<DropdownItem[]> {
    const { items } = await this.vendorsService.getCountryOptions();
    return items;
  }

  @Get('states')
  @ApiOperation({
    summary: 'State dropdown (flat array, filtered by countryId)',
  })
  async states(
    @Query('countryId') countryId: string,
  ): Promise<DropdownItem[]> {
    const { items } = await this.vendorsService.getStateOptions(countryId);
    return items;
  }

  @Get('cities')
  @ApiOperation({
    summary: 'City dropdown (flat array, filtered by stateId)',
  })
  async cities(
    @Query('stateId') stateId: string,
  ): Promise<DropdownItem[]> {
    const { items } = await this.vendorsService.getCityOptions(stateId);
    return items;
  }

  @Get('gst-types')
  @ApiOperation({
    summary: 'GST type dropdown (flat array)',
  })
  async gstTypes(): Promise<DropdownItem[]> {
    const { items } = await this.vendorsService.getGstTypeOptions();
    return items;
  }

  @Get('gst-percents')
  @ApiOperation({
    summary: 'GST percentage dropdown (flat array)',
  })
  async gstPercents(): Promise<DropdownItem[]> {
    const { items } = await this.vendorsService.getGstPercentOptions();
    return items;
  }
}
