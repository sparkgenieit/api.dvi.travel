// FILE: src/modules/itineraries/services/hotel-pricing.service.ts
import { Injectable } from "@nestjs/common";
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
  // Removed Logger for performance

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
   * Check if a hotel has at least one non-zero room rate for the given date.
   * PHP filters hotels this way to ensure valid pricing exists.
   */
  async hasValidRates(hotel_id: number, onDate: Date): Promise<boolean> {
    const dc = dayCol(onDate);
    const y = String(onDate.getFullYear());
    const m = monthName(onDate);

    const rows: any[] = await this.prisma.dvi_hotel_room_price_book.findMany({
      where: { 
        hotel_id, 
        year: y, 
        month: m,
        [dc]: { gt: 0 } // Only get rows where day_X > 0
      },
      select: { room_id: true },
      take: 1,
    });

    return rows.length > 0;
  }

  /**
   * Hotel picker:
   * - Filters by category
   * - Tries exact city match (case insensitive) if provided
   * - Falls back to any hotel in that category
   * - NOW: Only picks hotels with valid (non-zero) rates for the date
   */
  async pickHotelByCategory(hotel_category: number, city?: string | null, onDate?: Date) {
    const hotelCategory = Number(hotel_category) || 0;
    const cityTrim = (city ?? "").trim();
    const whereBase: any = { hotel_category: hotelCategory };

    if (cityTrim) {
      const cityCandidates = [cityTrim, cityTrim.toUpperCase(), cityTrim.toLowerCase()];
      for (const c of cityCandidates) {
        const hotels = await this.prisma.dvi_hotel.findMany({
          where: { ...whereBase, hotel_city: c },
          select: {
            hotel_id: true,
            hotel_margin: true,
            hotel_margin_gst_type: true,
            hotel_margin_gst_percentage: true,
            hotel_hotspot_status: true,
            hotel_city: true,
          },
        });

        if (hotels.length > 0) {
          // Filter hotels to only those with valid rates for the date
          if (onDate) {
            const validHotels = [];
            for (const h of hotels) {
              const hasRates = await this.hasValidRates(h.hotel_id, onDate);
              if (hasRates) {
                validHotels.push(h);
              }
            }
            if (validHotels.length > 0) {
              const hotel = validHotels[Math.floor(Math.random() * validHotels.length)];
              return hotel;
            }
            // No valid hotels in this city, continue to fallback
          } else {
            // No date provided, pick any hotel (backward compatibility)
            const hotel = hotels[Math.floor(Math.random() * hotels.length)];
            return hotel;
          }
        }
      }
    }

    const fallbacks = await this.prisma.dvi_hotel.findMany({
      where: whereBase,
      select: {
        hotel_id: true,
        hotel_margin: true,
        hotel_margin_gst_type: true,
        hotel_margin_gst_percentage: true,
        hotel_hotspot_status: true,
        hotel_city: true,
      },
    });

    if (fallbacks.length > 0) {
      // Filter fallback hotels by valid rates
      if (onDate) {
        const validFallbacks = [];
        for (const h of fallbacks) {
          if (await this.hasValidRates(h.hotel_id, onDate)) {
            validFallbacks.push(h);
          }
        }
        if (validFallbacks.length > 0) {
          const selected = validFallbacks[Math.floor(Math.random() * validFallbacks.length)];
          return selected;
        }
        // No hotels with valid rates found at all
        return null;
      }
      // No date filtering
      const selected = fallbacks[Math.floor(Math.random() * fallbacks.length)];
      return selected;
    }

    return null;
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

    // Debug log removed for performance
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

    // Debug log removed for performance

    // Only breakfast used in your sample (lunch/dinner kept 0)
    return {
      breakfast: { price, gstPct: 0, gstType: 2 },
      lunch: { price: 0, gstPct: 0, gstType: 2 },
      dinner: { price: 0, gstPct: 0, gstType: 2 },
    };
  }
}
