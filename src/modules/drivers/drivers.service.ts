// NEW FILE: src/modules/drivers/drivers.service.ts
import { Injectable } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { DriverListItemDto } from './dto/driver-list-item.dto';

@Injectable()
export class DriversService {
  constructor(private readonly prisma: PrismaService) {}

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
