// FILE: src/modules/vendors/vendors.service.ts

import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { VendorListItemDto } from './dto/vendor-list-item.dto';

type DropdownItem = {
  id: string;
  label: string;
};

@Injectable()
export class VendorsService {
  constructor(private readonly prisma: PrismaService) {}

  /**
   * Returns all vendors (non-deleted) with computed branch count.
   * Mirrors the behaviour of engine/json/__JSONvendor.php.
   */
  async listVendors(): Promise<VendorListItemDto[]> {
    // 1) Base vendor rows
    const vendors = await this.prisma.dvi_vendor_details.findMany({
      where: {
        deleted: 0,
      },
      orderBy: {
        vendor_id: 'desc',
      },
    });

    // 2) Branch counts per vendor
    const branchCounts = await this.prisma.dvi_vendor_branches.groupBy({
      by: ['vendor_id'],
      where: {
        deleted: 0,
      },
      _count: {
        vendor_id: true,
      },
    });

    const branchCountMap = new Map<number, number>();
    for (const row of branchCounts) {
      branchCountMap.set(
        // Prisma type for vendor_id is numeric in our schema
        row.vendor_id as unknown as number,
        row._count.vendor_id,
      );
    }

    // 3) Map to DTO
    const result: VendorListItemDto[] = vendors.map((v) => ({
      id: v.vendor_id as unknown as number,
      vendorName: v.vendor_name ?? '',
      vendorCode: v.vendor_code ?? '',
      vendorMobile: v.vendor_primary_mobile_number ?? '',
      vendorEmail: v.vendor_email ?? null,
      totalBranch: branchCountMap.get(v.vendor_id as unknown as number) ?? 0,
      status: v.status ?? 0,
    }));

    return result;
  }

  /**
   * Fetch full vendor info for the edit form (basic info + all branches).
   * This is the equivalent of what the PHP newvendor.php + __ajax_add_vendor_newform.php
   * do when loading the edit wizard.
   */
  async getVendorDetail(vendorId: number): Promise<any> {
    const vendor = await this.prisma.dvi_vendor_details.findFirst({
      where: {
        vendor_id: vendorId,
        deleted: 0,
      },
    });

    if (!vendor) {
      throw new NotFoundException(`Vendor ${vendorId} not found`);
    }

    const branches = await this.prisma.dvi_vendor_branches.findMany({
      where: {
        vendor_id: vendorId,
        deleted: 0,
      },
    });

    return {
      vendor,
      branches,
    };
  }

  /**
   * Create a new vendor basic record.
   *
   * Expectation:
   *  - `data` already uses column-style keys compatible with dvi_vendor_details,
   *    e.g. vendor_name, vendor_code, vendor_primary_mobile_number, vendor_email, etc.
   *  - The frontend should send the same fields the PHP form was posting.
   */
  async createVendorBasicInfo(data: any): Promise<any> {
    const created = await this.prisma.dvi_vendor_details.create({
      data: {
        deleted: 0,
        status: data.status ?? 1,
        ...data,
      },
    });

    // Return the same shape as the edit pre-fill endpoint
    return this.getVendorDetail(created.vendor_id as unknown as number);
  }

  /**
   * Update an existing vendor basic record (edit form save).
   */
  async updateVendorBasicInfo(vendorId: number, data: any): Promise<any> {
    // Ensure vendor exists (and not deleted)
    await this.getVendorDetail(vendorId);

    await this.prisma.dvi_vendor_details.update({
      where: {
        vendor_id: vendorId,
      },
      data,
    });

    return this.getVendorDetail(vendorId);
  }

  /**
   * List all non-deleted branches for a vendor.
   * Used for the "Branch Info" step.
   */
  async listBranches(vendorId: number): Promise<any[]> {
    return this.prisma.dvi_vendor_branches.findMany({
      where: {
        vendor_id: vendorId,
        deleted: 0,
      },
    });
  }

  /**
   * Create a new branch for a vendor.
   * The payload structure should mirror the PHP vendor branch form and
   * the dvi_vendor_branches schema (branch name, contacts, address, etc.).
   */
  async createBranch(vendorId: number, data: any): Promise<any> {
    // Make sure the vendor exists
    await this.getVendorDetail(vendorId);

    const created = await this.prisma.dvi_vendor_branches.create({
      data: {
        vendor_id: vendorId,
        deleted: 0,
        status: data.status ?? 1,
        ...data,
      },
    });

    return created;
  }

  /**
   * Update an existing vendor branch.
   */
  async updateBranch(branchId: number, data: any): Promise<any> {
    const existing = await this.prisma.dvi_vendor_branches.findFirst({
      where: {
        vendor_branch_id: branchId,
        deleted: 0,
      },
    });

    if (!existing) {
      throw new NotFoundException(`Vendor branch ${branchId} not found`);
    }

    return this.prisma.dvi_vendor_branches.update({
      where: {
        vendor_branch_id: branchId,
      },
      data,
    });
  }

  /**
   * Soft-delete a vendor branch (sets deleted = 1), mirroring the PHP behaviour.
   */
  async softDeleteBranch(branchId: number): Promise<void> {
    const existing = await this.prisma.dvi_vendor_branches.findFirst({
      where: {
        vendor_branch_id: branchId,
        deleted: 0,
      },
    });

    if (!existing) {
      // Idempotent delete
      return;
    }

    await this.prisma.dvi_vendor_branches.update({
      where: {
        vendor_branch_id: branchId,
      },
      data: {
        deleted: 1,
      },
    });
  }

  /**
   * Soft-delete a vendor and all its branches.
   * Mirrors the PHP "delete vendor" behaviour (keep data but hide it).
   */
  async softDeleteVendor(vendorId: number): Promise<void> {
    const existing = await this.prisma.dvi_vendor_details.findFirst({
      where: {
        vendor_id: vendorId,
        deleted: 0,
      },
    });

    // If vendor doesn't exist or already deleted, make it idempotent
    if (!existing) {
      return;
    }

    await this.prisma.$transaction([
      this.prisma.dvi_vendor_details.update({
        where: { vendor_id: vendorId },
        data: {
          deleted: 1,
          status: 0,
        },
      }),
      this.prisma.dvi_vendor_branches.updateMany({
        where: { vendor_id: vendorId },
        data: {
          deleted: 1,
          status: 0,
        },
      }),
    ]);
  }

  // =====================================================================================
  // NEW: Dropdowns for Vendor Form
  //   - Roles: dvi_rolemenu
  //   - Countries / States / Cities: dvi_countries / dvi_states / dvi_cities
  //   - GST Types / Percentages: dvi_gst_setting
  // =====================================================================================

  /**
   * Roles dropdown.
   * Source: dvi_rolemenu
   *
   * NOTE: We do NOT filter on deleted/status here because those columns
   * are not present in the Prisma model (earlier error "Argument `deleted` is missing").
   */
  async getRoleOptions(): Promise<{ items: DropdownItem[] }> {
  // Fetch all active roles (status = 1). We don't filter on `deleted`
  // to avoid Prisma schema mismatches; the table already uses 0 for active.
  const rows = await this.prisma.dvi_rolemenu.findMany({
    where: {
      status: 1 as any,
    },
    orderBy: {
      role_name: 'asc' as any,
    },
  } as any);

  const items: DropdownItem[] = (rows as any[])
    .map((row) => {
      // Handle different possible field names safely
      const id =
        row.role_ID ??
        row.role_id ??
        row.roleId ??
        row.id;

      const label =
        row.role_name ??
        row.rolename ??
        row.name;

      if (!id || !label) return null;

      return {
        id: String(id),
        label: String(label),
      };
    })
    .filter((x): x is DropdownItem => x !== null);

  return { items };
}

  /**
   * Country dropdown.
   * Source: dvi_countries
   *
   * Mirrors HotelsService.countries() but mapped to DropdownItem.
   * No deleted/status filter because those fields are not in Prisma model.
   */
  async getCountryOptions(): Promise<{ items: DropdownItem[] }> {
    const rows = await this.prisma.dvi_countries.findMany({
      select: { id: true, name: true },
      orderBy: [{ name: 'asc' }] as any,
    } as any);

    const items: DropdownItem[] = (rows as any[])
      .map((r: any) => {
        if (!r.id || !r.name) return null;
        return {
          id: String(r.id),
          label: String(r.name),
        };
      })
      .filter((x): x is DropdownItem => x !== null);

    return { items };
  }

  /**
   * State dropdown, filtered by country.
   * Source: dvi_states
   *
   * Mirrors HotelsService.states(), but wraps in DropdownItem.
   */
  async getStateOptions(
    countryId: number | string,
  ): Promise<{ items: DropdownItem[] }> {
    const cid = Number(countryId);
    if (!Number.isFinite(cid) || cid <= 0) {
      return { items: [] };
    }

    const rows = await this.prisma.dvi_states.findMany({
      where: { country_id: cid } as any,
      select: { id: true, name: true, country_id: true },
      orderBy: [{ name: 'asc' }] as any,
    } as any);

    const items: DropdownItem[] = (rows as any[])
      .map((r: any) => {
        if (!r.id || !r.name) return null;
        return {
          id: String(r.id),
          label: String(r.name),
        };
      })
      .filter((x): x is DropdownItem => x !== null);

    return { items };
  }

  /**
   * City dropdown, filtered by state.
   * Source: dvi_cities
   *
   * Mirrors HotelsService.cities(), but wraps in DropdownItem.
   */
  async getCityOptions(
    stateId: number | string,
  ): Promise<{ items: DropdownItem[] }> {
    const sid = Number(stateId);
    if (!Number.isFinite(sid) || sid <= 0) {
      return { items: [] };
    }

    const rows = await this.prisma.dvi_cities.findMany({
      where: { state_id: sid } as any,
      select: { id: true, name: true, state_id: true },
      orderBy: [{ name: 'asc' }] as any,
    } as any);

    const items: DropdownItem[] = (rows as any[])
      .map((r: any) => {
        if (!r.id || !r.name) return null;
        return {
          id: String(r.id),
          label: String(r.name),
        };
      })
      .filter((x): x is DropdownItem => x !== null);

    return { items };
  }

  /**
   * Vendor Margin GST Type dropdown.
   *
   * For now we keep this static because:
   *  - Your React form already uses "included"/"excluded" from gstTypeOptions.
   *  - dvi_gst_setting Prisma model fields for type/category are not guaranteed.
   */
  async getGstTypeOptions(): Promise<{ items: DropdownItem[] }> {
    return {
      items: [
        { id: 'included', label: 'Included' },
        { id: 'excluded', label: 'Excluded' },
      ],
    };
  }

  /**
   * Vendor Margin GST Percentage dropdown.
   * Source: dvi_gst_setting
   *
   * Mirrors HotelsService.gstPercentages() logic, but mapped to DropdownItem.
   * No deleted/status filter (those columns are not in Prisma model).
   */
  async getGstPercentOptions(): Promise<{ items: DropdownItem[] }> {
    const rows = await this.prisma.dvi_gst_setting.findMany({
      select: { gst_setting_id: true, gst_value: true },
      orderBy: [{ gst_value: 'asc' }] as any,
    } as any);

    const seen = new Set<number>();
    const items: DropdownItem[] = [];

    for (const r of rows as any[]) {
      const v = Number(r.gst_value);
      if (!Number.isFinite(v)) continue;
      if (seen.has(v)) continue;
      seen.add(v);

      items.push({
        id: String(v),
        label: String(v), // "0", "5", "12", "18", etc.
      });
    }

    // Fallback if table is empty / not configured
    if (!items.length) {
      [0, 5, 12, 18].forEach((v) => {
        items.push({
          id: String(v),
          label: String(v),
        });
      });
    }

    return { items };
  }

  // =====================================================================================
  // NEW: Vendor Wizard Steps 3-6
  // =====================================================================================

  // --- Step 3: Driver Costs (dvi_vendor_vehicle_types) ---

  async getVendorVehicleTypes(vendorId: number): Promise<any[]> {
    return this.prisma.dvi_vendor_vehicle_types.findMany({
      where: { vendor_id: vendorId, deleted: 0 },
    });
  }

  async updateVendorVehicleType(vendorId: number, data: any): Promise<any> {
    const { vehicle_type_id, ...rest } = data;

    // Check if exists
    const existing = await this.prisma.dvi_vendor_vehicle_types.findFirst({
      where: { vendor_id: vendorId, vehicle_type_id, deleted: 0 },
    });

    if (existing) {
      return this.prisma.dvi_vendor_vehicle_types.update({
        where: { vendor_vehicle_type_ID: existing.vendor_vehicle_type_ID },
        data: { ...rest, updatedon: new Date() },
      });
    } else {
      return this.prisma.dvi_vendor_vehicle_types.create({
        data: {
          vendor_id: vendorId,
          vehicle_type_id,
          ...rest,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }
  }

  // --- Step 4: Vehicle Info (dvi_vehicle) ---

  async getVendorVehicles(vendorId: number): Promise<any[]> {
    return this.prisma.dvi_vehicle.findMany({
      where: { vendor_id: vendorId, deleted: 0 },
    });
  }

  async createVendorVehicle(vendorId: number, data: any): Promise<any> {
    return this.prisma.dvi_vehicle.create({
      data: {
        ...data,
        vendor_id: vendorId,
        createdon: new Date(),
        status: 1,
        deleted: 0,
      },
    });
  }

  async updateVendorVehicle(vehicleId: number, data: any): Promise<any> {
    return this.prisma.dvi_vehicle.update({
      where: { vehicle_id: vehicleId },
      data: { ...data, updatedon: new Date() },
    });
  }

  async softDeleteVehicle(vehicleId: number): Promise<void> {
    await this.prisma.dvi_vehicle.update({
      where: { vehicle_id: vehicleId },
      data: { deleted: 1 },
    });
  }

  // --- Step 5: Pricebook (dvi_vehicle_local_pricebook, dvi_vehicle_outstation_price_book) ---

  async getVendorLocalPricebook(vendorId: number): Promise<any[]> {
    return this.prisma.dvi_vehicle_local_pricebook.findMany({
      where: { vendor_id: vendorId, deleted: 0 },
    });
  }

  async updateVendorLocalPricebook(vendorId: number, data: any): Promise<any> {
    const { vehicle_price_book_id, ...rest } = data;
    if (vehicle_price_book_id) {
      return this.prisma.dvi_vehicle_local_pricebook.update({
        where: { vehicle_price_book_id },
        data: { ...rest, updatedon: new Date() },
      });
    } else {
      return this.prisma.dvi_vehicle_local_pricebook.create({
        data: {
          ...rest,
          vendor_id: vendorId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }
  }

  async getVendorOutstationPricebook(vendorId: number): Promise<any[]> {
    return this.prisma.dvi_vehicle_outstation_price_book.findMany({
      where: { vendor_id: vendorId, deleted: 0 },
    });
  }

  async updateVendorOutstationPricebook(vendorId: number, data: any): Promise<any> {
    const { vehicle_outstation_price_book_id, ...rest } = data;
    if (vehicle_outstation_price_book_id) {
      return this.prisma.dvi_vehicle_outstation_price_book.update({
        where: { vehicle_outstation_price_book_id },
        data: { ...rest, updatedon: new Date() },
      });
    } else {
      return this.prisma.dvi_vehicle_outstation_price_book.create({
        data: {
          ...rest,
          vendor_id: vendorId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }
  }

  // --- Step 6: Permit Cost (dvi_permit_cost) ---

  async getVendorPermitCosts(vendorId: number): Promise<any[]> {
    return this.prisma.dvi_permit_cost.findMany({
      where: { vendor_id: vendorId, deleted: 0 },
    });
  }

  async updateVendorPermitCost(vendorId: number, data: any): Promise<any> {
    const { permit_cost_id, ...rest } = data;
    if (permit_cost_id) {
      return this.prisma.dvi_permit_cost.update({
        where: { permit_cost_id },
        data: { ...rest, updatedon: new Date() },
      });
    } else {
      return this.prisma.dvi_permit_cost.create({
        data: {
          ...rest,
          vendor_id: vendorId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }
  }

  // --- Dropdowns for Steps 3-6 ---

  async getVehicleTypeOptions(): Promise<{ items: DropdownItem[] }> {
    const rows = await this.prisma.dvi_vehicle_type.findMany({
      where: { deleted: 0, status: 1 },
      orderBy: { vehicle_type_title: 'asc' },
    });
    return {
      items: rows.map((r) => ({
        id: String(r.vehicle_type_id),
        label: r.vehicle_type_title || '',
      })),
    };
  }

  async getTimeLimitOptions(vendorId: number): Promise<{ items: DropdownItem[] }> {
    const rows = await this.prisma.dvi_time_limit.findMany({
      where: { vendor_id: vendorId, deleted: 0 },
    });
    return {
      items: rows.map((r) => ({
        id: String(r.time_limit_id),
        label: r.time_limit_title || '',
      })),
    };
  }

  async getKmsLimitOptions(vendorId: number): Promise<{ items: DropdownItem[] }> {
    const rows = await this.prisma.dvi_kms_limit.findMany({
      where: { vendor_id: vendorId, deleted: 0 },
    });
    return {
      items: rows.map((r) => ({
        id: String(r.kms_limit_id),
        label: r.kms_limit_title || '',
      })),
    };
  }
}
