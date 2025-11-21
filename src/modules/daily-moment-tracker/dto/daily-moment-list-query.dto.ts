// FILE: src/modules/daily-moment-tracker/dto/daily-moment-list-query.dto.ts

export class DailyMomentListQueryDto {
  /**
   * Inclusive start date (YYYY-MM-DD). 
   * In PHP this was converted using dateformat_database.
   * Here we expect it already in DB format.
   */
  fromDate!: string;

  /**
   * Inclusive end date (YYYY-MM-DD).
   */
  toDate!: string;
}
