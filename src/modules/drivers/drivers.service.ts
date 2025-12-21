// NEW FILE: src/modules/drivers/drivers.service.ts
import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { DriverListItemDto } from './dto/driver-list-item.dto';
import { CreateDriverDto } from './dto/create-driver.dto';
import { UpdateDriverDto } from './dto/update-driver.dto';

@Injectable()
export class DriversService {
  constructor(private readonly prisma: PrismaService) {}

  async create(dto: CreateDriverDto) {
    const b = dto.basic;
    return this.prisma.dvi_driver_details.create({
      data: {
        vendor_id: b.vendorId,
        vehicle_type_id: b.vehicleTypeId,
        driver_name: b.driverName,
        driver_primary_mobile_number: b.primaryMobileNumber,
        driver_alternate_mobile_number: b.alternateMobileNumber,
        driver_whatsapp_mobile_number: b.whatsappMobileNumber,
        driver_email: b.email,
        driver_license_number: b.licenseNumber,
        driver_license_issue_date: b.licenseIssueDate ? new Date(b.licenseIssueDate) : null,
        driver_license_expiry_date: b.licenseExpiryDate ? new Date(b.licenseExpiryDate) : null,
        driver_aadharcard_num: b.aadharNumber,
        driver_voter_id_num: b.voterIdNumber,
        driver_pan_card: b.panNumber,
        driver_blood_group: b.bloodGroup ? parseInt(b.bloodGroup) : 0,
        driver_gender: b.gender ? parseInt(b.gender) : 0,
        driver_address: b.address,
        status: 1,
        deleted: 0,
        createdon: new Date(),
      },
    });
  }

  async findOne(id: number) {
    const driver = await this.prisma.dvi_driver_details.findFirst({
      where: { driver_id: id, deleted: 0 },
    });
    if (!driver) throw new NotFoundException('Driver not found');
    return driver;
  }

  async update(id: number, dto: UpdateDriverDto) {
    const existing = await this.findOne(id);
    const b = dto.basic;
    return this.prisma.dvi_driver_details.update({
      where: { driver_id: id },
      data: {
        vendor_id: b?.vendorId ?? existing.vendor_id,
        vehicle_type_id: b?.vehicleTypeId ?? existing.vehicle_type_id,
        driver_name: b?.driverName ?? existing.driver_name,
        driver_primary_mobile_number: b?.primaryMobileNumber ?? existing.driver_primary_mobile_number,
        driver_alternate_mobile_number: b?.alternateMobileNumber ?? existing.driver_alternate_mobile_number,
        driver_whatsapp_mobile_number: b?.whatsappMobileNumber ?? existing.driver_whatsapp_mobile_number,
        driver_email: b?.email ?? existing.driver_email,
        driver_license_number: b?.licenseNumber ?? existing.driver_license_number,
        driver_license_issue_date: b?.licenseIssueDate ? new Date(b.licenseIssueDate) : existing.driver_license_issue_date,
        driver_license_expiry_date: b?.licenseExpiryDate ? new Date(b.licenseExpiryDate) : existing.driver_license_expiry_date,
        driver_aadharcard_num: b?.aadharNumber ?? existing.driver_aadharcard_num,
        driver_voter_id_num: b?.voterIdNumber ?? existing.driver_voter_id_num,
        driver_pan_card: b?.panNumber ?? existing.driver_pan_card,
        driver_blood_group: b?.bloodGroup ? parseInt(b.bloodGroup) : existing.driver_blood_group,
        driver_gender: b?.gender ? parseInt(b.gender) : existing.driver_gender,
        driver_address: b?.address ?? existing.driver_address,
        updatedon: new Date(),
      },
    });
  }

  /**
   * List drivers (equivalent of __JSONdriver.php)
   * - Optional vendor filter (like $logged_vendor_id)
   */
  async findAll(vendorId?: number): Promise<DriverListItemDto[]> {
    const drivers = await this.prisma.dvi_driver_details.findMany({
      where: vendorId ? { vendor_id: vendorId } : undefined,
      orderBy: { driver_id: 'asc' },
    });

    const todayStr = new Date().toISOString().slice(0, 10); // YYYY-MM-DD

    return drivers.map((d: any) => {
      const exp: Date | null = d.driver_license_expiry_date
        ? new Date(d.driver_license_expiry_date)
        : null;

      let licenseStatus = 'Active';
      if (exp) {
        const expStr = exp.toISOString().slice(0, 10);
        if (expStr === todayStr) {
          licenseStatus = 'Expires Today';
        } else if (expStr < todayStr) {
          licenseStatus = 'In-Active';
        } else {
          licenseStatus = 'Active';
        }
      }

      return {
        id: d.driver_id,
        name: d.driver_name,
        mobile: d.driver_primary_mobile_number,
        licenseNumber: d.driver_license_number,
        licenseExpiryDate: exp,
        licenseStatus,
        status: d.status === 1 || d.status === true,
      };
    });
  }

  async updateStatus(id: number, status: boolean): Promise<void> {
    await this.prisma.dvi_driver_details.update({
      where: { driver_id: id },
      data: { status: status ? 1 : 0 },
    });
  }

  async remove(id: number): Promise<void> {
    await this.prisma.dvi_driver_details.delete({
      where: { driver_id: id },
    });
  }
}
