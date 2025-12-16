import { Injectable } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import * as ExcelJS from 'exceljs';

@Injectable()
export class ItineraryExportService {
  constructor(private prisma: PrismaService) {}

  async exportItineraryToExcel(planId: number): Promise<ExcelJS.Workbook> {
    const workbook = new ExcelJS.Workbook();
    const sheet = workbook.addWorksheet('Itinerary');

    // Fetch itinerary plan details
    const plan: any = await this.prisma.$queryRaw`
      SELECT * FROM dvi_itinerary_plan_details 
      WHERE itinerary_plan_ID = ${planId} AND deleted = 0
    `;

    if (!plan || plan.length === 0) {
      throw new Error('Itinerary plan not found');
    }

    const planData = plan[0];

    // Fetch routes
    const routes: any[] = await this.prisma.$queryRaw`
      SELECT * FROM dvi_itinerary_route_details
      WHERE itinerary_plan_ID = ${planId} AND deleted = 0
      ORDER BY itinerary_route_date ASC
    `;

    // Fetch hotspots
    const hotspots: any[] = await this.prisma.$queryRaw`
      SELECT * FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = ${planId} AND deleted = 0
      ORDER BY itinerary_route_ID, hotspot_serial_number
    `;

    // Fetch hotels
    const hotels: any[] = await this.prisma.$queryRaw`
      SELECT h.*, ht.hotel_name, ht.hotel_category
      FROM dvi_itinerary_plan_hotel_details h
      LEFT JOIN dvi_hotel ht ON h.hotel_id = ht.hotel_ID
      WHERE h.itinerary_plan_id = ${planId} AND h.group_type = 1
    `;

    // Fetch vehicles
    const vehicles: any[] = await this.prisma.$queryRaw`
      SELECT v.*, vt.vehicle_type_title, vd.vendor_name
      FROM dvi_itinerary_plan_vendor_eligible_list v
      LEFT JOIN dvi_vehicle_type vt ON v.vehicle_type_id = vt.vehicle_type_ID
      LEFT JOIN dvi_vendor vd ON v.vendor_id = vd.vendor_ID
      WHERE v.itinerary_plan_id = ${planId} AND v.itineary_plan_assigned_status = 1
    `;

    // Define styles
    const headerStyleYellow = {
      font: { bold: true },
      alignment: { horizontal: 'left' as const },
      fill: { type: 'pattern' as const, pattern: 'solid' as const, fgColor: { argb: 'FFFFFF00' } },
      border: {
        top: { style: 'thin' as const },
        left: { style: 'thin' as const },
        bottom: { style: 'thin' as const },
        right: { style: 'thin' as const },
      },
    };

    const headerStyleBlue = {
      font: { bold: true },
      alignment: { horizontal: 'left' as const },
      fill: { type: 'pattern' as const, pattern: 'solid' as const, fgColor: { argb: 'FF8DB4E2' } },
      border: {
        top: { style: 'thin' as const },
        left: { style: 'thin' as const },
        bottom: { style: 'thin' as const },
        right: { style: 'thin' as const },
      },
    };

    const dataCellStyle = {
      border: {
        top: { style: 'thin' as const },
        left: { style: 'thin' as const },
        bottom: { style: 'thin' as const },
        right: { style: 'thin' as const },
      },
    };

    // Set column widths
    sheet.columns = [
      { key: 'A', width: 30 },
      { key: 'B', width: 50 },
    ];

    let currentRow = 1;

    // Add basic details
    const addHeaderRow = (label: string, value: string, style: any) => {
      const row = sheet.getRow(currentRow);
      row.getCell(1).value = label;
      row.getCell(2).value = value;
      row.getCell(1).style = style;
      row.getCell(2).style = dataCellStyle;
      currentRow++;
    };

    addHeaderRow('Quote ID', planData.itinerary_quote_ID || '', headerStyleYellow);
    addHeaderRow('Arrival Location', planData.arrival_location || '', headerStyleBlue);
    addHeaderRow('Departure Location', planData.departure_location || '', headerStyleBlue);
    addHeaderRow('Trip Start', planData.trip_start_date_and_time?.toLocaleString() || '', dataCellStyle);
    addHeaderRow('Trip End', planData.trip_end_date_and_time?.toLocaleString() || '', dataCellStyle);
    addHeaderRow('Days & Nights', `${planData.no_of_days}D / ${planData.no_of_nights}N`, dataCellStyle);
    addHeaderRow('Total Adult', planData.total_adult?.toString() || '0', dataCellStyle);
    addHeaderRow('Total Children', planData.total_children?.toString() || '0', dataCellStyle);
    addHeaderRow('Total Infants', planData.total_infants?.toString() || '0', dataCellStyle);

    currentRow++; // Add blank row

    // Add Day-wise Itinerary
    const mergedCellStyle = {
      font: { bold: true },
      alignment: { horizontal: 'center' as const },
      fill: { type: 'pattern' as const, pattern: 'solid' as const, fgColor: { argb: 'FFFFFF00' } },
      border: {
        top: { style: 'thin' as const },
        left: { style: 'thin' as const },
        bottom: { style: 'thin' as const },
        right: { style: 'thin' as const },
      },
    };

    for (const route of routes) {
      // Day header
      sheet.mergeCells(`A${currentRow}:B${currentRow}`);
      const dayRow = sheet.getRow(currentRow);
      dayRow.getCell(1).value = `Day ${route.itinerary_route_day_count} - ${route.itinerary_route_arrival_location} to ${route.itinerary_route_departure_location}`;
      dayRow.getCell(1).style = mergedCellStyle;
      currentRow++;

      // Add hotspots for this route
      const routeHotspots = hotspots.filter(
        (h: any) => h.itinerary_route_ID === route.itinerary_route_ID,
      );
      for (const hotspot of routeHotspots) {
        addHeaderRow(
          hotspot.itinerary_route_visiting_place_name || 'Hotspot',
          hotspot.itinerary_route_visiting_place_description || '',
          dataCellStyle,
        );
      }

      currentRow++; // Blank row after each day
    }

    // Add Hotel Details
    if (hotels.length > 0) {
      sheet.mergeCells(`A${currentRow}:B${currentRow}`);
      const hotelHeaderRow = sheet.getRow(currentRow);
      hotelHeaderRow.getCell(1).value = 'HOTEL DETAILS';
      hotelHeaderRow.getCell(1).style = mergedCellStyle;
      currentRow++;

      for (const hotel of hotels) {
        addHeaderRow(
          hotel.hotel_name || 'Hotel',
          `Category: ${hotel.hotel_category || 'N/A'}`,
          dataCellStyle,
        );
      }
      currentRow++;
    }

    // Add Vehicle Details
    if (vehicles.length > 0) {
      sheet.mergeCells(`A${currentRow}:B${currentRow}`);
      const vehicleHeaderRow = sheet.getRow(currentRow);
      vehicleHeaderRow.getCell(1).value = 'VEHICLE DETAILS';
      vehicleHeaderRow.getCell(1).style = mergedCellStyle;
      currentRow++;

      for (const vehicle of vehicles) {
        addHeaderRow(
          vehicle.vehicle_type_title || 'Vehicle',
          `Vendor: ${vehicle.vendor_name || 'N/A'}`,
          dataCellStyle,
        );
      }
    }

    return workbook;
  }
}
