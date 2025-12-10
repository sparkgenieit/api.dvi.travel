// FILE: src/modules/itineraries/services/hotel-pricing.service.ts
import { Injectable, Logger } from "@nestjs/common";
import { PrismaService } from "../../../prisma.service";

type DayCol =
  | "day_1" | "day_2" | "day_3" | "day_4" | "day_5" | "day_6" | "day_7" | "day_8" | "day_9" | "day_10"
  | "day_11" | "day_12" | "day_13" | "day_14" | "day_15" | "day_16" | "day_17" | "day_18" | "day_19" | "day_20"
  | "day_21" | "day_22" | "day_23" | "day_24" | "day_25" | "day_26" | "day_27" | "day_28" | "day_29" | "day_30"
  | "day_31";

function dayCol(d: Date): DayCol {
  // Use getDate (calendar day) like PHP/MySQL
  return `day_${d.getDate()}` as DayCol;
}

function monthName(d: Date) {
  return d.toLocaleString("en-US", { month: "long" });
}

function N(v: any) {
  const n = Number(v ?? 0);
  return Number.isFinite(n) ? n : 0;
}

@Injectable()
export class HotelPricingService {
  private readonly log = new Logger("HotelPricingService");

  constructor(private readonly prisma: PrismaService) {}

  /** Simple money round (2 decimals) */
  money(v: any) {
    const n = Number(v ?? 0);
    if (!Number.isFinite(n)) return 0;
    return Math.round((n + Number.EPSILON) * 100) / 100;
  }

  /**
   * PHP GST split logic:
   * gstType: 1 = inclusive, 2 = exclusive
   * Returns { amount (before GST), tax }
   */
  splitGST(
    gross: number,
    gstPct: number,
    gstType: number,
  ) {
    const g = this.money(gross);
    const pct = Number(gstPct ?? 0);

    if (g <= 0 || pct <= 0) {
      return { amount: this.money(g), tax: 0 };
    }

    if (gstType === 1) {
      // Inclusive: gross already includes GST
      const base = g / (1 + pct / 100);
      const baseRounded = this.money(base);
      const tax = this.money(g - baseRounded);
      return { amount: baseRounded, tax };
    }

    // Exclusive: GST on top of gross
    const tax = this.money((g * pct) / 100);
    return { amount: g, tax };
  }

  /**
   * Hotel picker:
   * - Filters by category
   * - Tries exact city match (case insensitive) if provided
   * - Falls back to any hotel in that category
   */
  async pickHotelByCategory(hotel_category: number, city?: string | null) {
    const hotelCategory = Number(hotel_category) || 0;
    const cityTrim = (city ?? "").trim();
    const whereBase: any = { hotel_category: hotelCategory };

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

        this.log.debug(
          `pickHotelByCategory(cat=${hotelCategory}, city='${c}') -> ${hotel ? hotel.hotel_id : "null"}`,
        );
        if (hotel) return hotel;
      }
    }

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
      `pickHotelByCategory(fallback, cat=${hotelCategory}, city='${cityTrim}') -> ${fallback ? fallback.hotel_id : "null"}`,
    );
    return fallback;
  }

  /**
   * Room prices for that date from dvi_hotel_room_price_book.
   * We return ALL room rows, PHP will typically pick the first non-zero rate.
   * GST for rooms is stored elsewhere → gstPct=0, gstType=2 (exclusive) for now.
   */
  async getRoomPrices(hotel_id: number, onDate: Date) {
    const dc = dayCol(onDate);
    const y = String(onDate.getFullYear());
    const m = monthName(onDate);

    const rows: any[] = await this.prisma.dvi_hotel_room_price_book.findMany({
      where: { hotel_id, year: y, month: m },
      select: { room_id: true, [dc]: true } as any,
      orderBy: { room_id: "asc" },
    });

    const mapped = rows.map((r) => ({
      room_id: N(r.room_id),
      rate: N(r[dc]),
      gstPct: 0,
      gstType: 2,
    }));

    this.log.debug(
      `getRoomPrices(hotel=${hotel_id}, ${y}-${m}, ${dc}) rows=${mapped.length}`,
    );
    return mapped;
  }

  /**
   * Meal prices for that date from dvi_hotel_meal_price_book.
   * Schema only has a single price per day, no GST columns → gstPct=0.
   * We use it as BREAKFAST price (matching your sample rows where only breakfast is non-zero).
   */
  async getMealPrice(hotel_id: number, onDate: Date) {
    const dc = dayCol(onDate);
    const y = String(onDate.getFullYear());
    const m = monthName(onDate);

    const row: any = await this.prisma.dvi_hotel_meal_price_book.findFirst({
      where: { hotel_id, year: y, month: m },
      select: { [dc]: true } as any,
    });

    const price = this.money(row?.[dc]);

    this.log.debug(
      `getMealPrice(hotel=${hotel_id}, ${y}-${m}, ${dc}) breakfastPrice=${price}`,
    );

    // Only breakfast used in your sample (lunch/dinner kept 0)
    return {
      breakfast: { price, gstPct: 0, gstType: 2 },
      lunch: { price: 0, gstPct: 0, gstType: 2 },
      dinner: { price: 0, gstPct: 0, gstType: 2 },
    };
  }
}
