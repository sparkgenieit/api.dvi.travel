import { Injectable, Logger } from "@nestjs/common";
import { PrismaService } from "../../../prisma.service";

type DayCol =
  | "day_1"|"day_2"|"day_3"|"day_4"|"day_5"|"day_6"|"day_7"|"day_8"|"day_9"|"day_10"
  | "day_11"|"day_12"|"day_13"|"day_14"|"day_15"|"day_16"|"day_17"|"day_18"|"day_19"|"day_20"
  | "day_21"|"day_22"|"day_23"|"day_24"|"day_25"|"day_26"|"day_27"|"day_28"|"day_29"|"day_30"
  | "day_31";

function dayCol(d: Date): DayCol { return `day_${d.getUTCDate()}` as DayCol; }
function monthName(d: Date) { return d.toLocaleString("en-US", { month: "long" }); }
function N(v: any) { const n = Number(v ?? 0); return Number.isFinite(n) ? n : 0; }

@Injectable()
export class HotelPricingService {
  private readonly log = new Logger("HotelPricingService");
  constructor(private readonly prisma: PrismaService) {}

  /**
   * Relaxed hotel picker:
   * - Filters by category (schema field: dvi_hotel.hotel_category)
   * - Tries city match if provided (exact/upper/lower), then falls back to any city
   * - DOES NOT hard-filter by status/deleted (your data may keep status=0)
   */
  async pickHotelByCategory(hotel_category: number, city?: string | null) {
    const cityTrim = (city ?? "").trim();
    const whereBase: any = { hotel_category: Number(hotel_category) || 0 };

    // 1) Try with city if we got one
    if (cityTrim) {
      const cityCandidates = [cityTrim, cityTrim.toUpperCase(), cityTrim.toLowerCase()];
      for (const c of cityCandidates) {
        const hotel = await this.prisma.dvi_hotel.findFirst({
          where: { ...whereBase, hotel_city: c },
          select: {
            hotel_id: true,
            hotel_margin: true,
            hotel_margin_gst_type: true,
            hotel_margin_gst_percentage: true,
            hotel_hotspot_status: true,
            hotel_city: true,
          },
          orderBy: { hotel_id: "asc" },
        });
        this.log.debug(`pickHotelByCategory(city='${c}') -> ${hotel ? hotel.hotel_id : "null"}`);
        if (hotel) return hotel;
      }
    }

    // 2) Fallback: any city in that category
    const fallback = await this.prisma.dvi_hotel.findFirst({
      where: whereBase,
      select: {
        hotel_id: true,
        hotel_margin: true,
        hotel_margin_gst_type: true,
        hotel_margin_gst_percentage: true,
        hotel_hotspot_status: true,
        hotel_city: true,
      },
      orderBy: { hotel_id: "asc" },
    });

    this.log.debug(
      `pickHotelByCategory(fallback, cat=${hotel_category}, city='${cityTrim}') -> ${fallback ? fallback.hotel_id : "null"}`
    );
    return fallback;
  }

  /** Room prices for that date from dvi_hotel_room_price_book (GST not present → gstPct=0) */
  async getRoomPrices(hotel_id: number, onDate: Date) {
    const dc = dayCol(onDate);
    const y = String(onDate.getFullYear());
    const m = monthName(onDate);

    const rows: any[] = await this.prisma.dvi_hotel_room_price_book.findMany({
      where: { hotel_id, year: y, month: m },
      select: { room_id: true, [dc]: true } as any,
      orderBy: { room_id: "asc" },
    });

    const mapped = rows.map(r => ({ room_id: N(r.room_id), rate: N(r[dc]), gstPct: 0 }));
    this.log.debug(`getRoomPrices(hotel=${hotel_id}, ${y}-${m}, ${dc}) rows=${mapped.length}`);
    return mapped;
  }

  /** Meal prices for that date from dvi_hotel_meal_price_book (no GST columns → gstPct=0 for all) */
  async getMealPrice(hotel_id: number, onDate: Date) {
    const dc = dayCol(onDate);
    const y = String(onDate.getFullYear());
    const m = monthName(onDate);
    const row: any = await this.prisma.dvi_hotel_meal_price_book.findFirst({
      where: { hotel_id, year: y, month: m },
      select: { [dc]: true } as any,
    });

    const price = N(row?.[dc]);
    this.log.debug(`getMealPrice(hotel=${hotel_id}, ${y}-${m}, ${dc}) price=${price}`);
    return {
      breakfast: { price, gstPct: 0 },
      lunch:     { price, gstPct: 0 },
      dinner:    { price, gstPct: 0 },
    };
  }
}
