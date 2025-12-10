/**
 * Converts HH:MM:SS strings to Prisma TIME Date objects.
 * Prisma @db.Time(0) fields require JS Date objects with time-only values.
 * Handles times that exceed 24 hours by wrapping to modulo 24.
 */
export class TimeConverter {
  /**
   * Convert HH:MM:SS string to a Date object suitable for Prisma TIME fields.
   * Uses epoch date (1970-01-01) with the specified time.
   * Times exceeding 24 hours are wrapped using modulo 24.
   */
  static stringToDate(timeStr: string | null | undefined): Date {
    if (!timeStr) {
      return new Date(1970, 0, 1, 0, 0, 0);
    }

    const parts = String(timeStr).trim().split(":");
    let h = Number(parts[0] ?? "0") || 0;
    const m = Number(parts[1] ?? "0") || 0;
    const s = Number(parts[2] ?? "0") || 0;

    // Wrap hours to 0-23 range (handle multi-day times)
    h = h % 24;

    const d = new Date(1970, 0, 1, h, m, s);
    return d;
  }

  /**
   * Convert seconds to a Date object suitable for Prisma TIME fields.
   * Handles seconds that exceed 86400 (24 hours) by wrapping.
   */
  static secondsToDate(seconds: number): Date {
    if (!Number.isFinite(seconds)) seconds = 0;
    // Wrap to 24-hour boundary
    const wrappedSeconds = Math.max(0, Math.floor(seconds)) % 86400;
    const d = new Date(1970, 0, 1, 0, 0, 0);
    d.setSeconds(wrappedSeconds);
    return d;
  }

  /**
   * Convert a time value (string, Date, or seconds) to a Prisma TIME Date object.
   */
  static toDate(value: string | Date | number | null | undefined): Date {
    if (!value) {
      return new Date(1970, 0, 1, 0, 0, 0);
    }

    if (value instanceof Date) {
      return value;
    }

    if (typeof value === "number") {
      return this.secondsToDate(value);
    }

    return this.stringToDate(String(value));
  }

  /**
   * Convert a time value to HH:MM:SS string format for database TIME columns.
   * For string fields that store TIME data.
   */
  static toTimeString(value: string | Date | number | null | undefined): string {
    if (!value) {
      return "00:00:00";
    }

    if (typeof value === "string") {
      // Already a string, just ensure it's HH:MM:SS format
      const parts = value.trim().split(":");
      const h = String(Number(parts[0] ?? "0") || 0).padStart(2, "0");
      const m = String(Number(parts[1] ?? "0") || 0).padStart(2, "0");
      const s = String(Number(parts[2] ?? "0") || 0).padStart(2, "0");
      return `${h}:${m}:${s}`;
    }

    if (value instanceof Date) {
      const h = String(value.getHours()).padStart(2, "0");
      const m = String(value.getMinutes()).padStart(2, "0");
      const s = String(value.getSeconds()).padStart(2, "0");
      return `${h}:${m}:${s}`;
    }

    if (typeof value === "number") {
      // Treat as seconds
      const seconds = Math.floor(Math.abs(value));
      const hours = Math.floor(seconds / 3600);
      const minutes = Math.floor((seconds % 3600) / 60);
      const secs = seconds % 60;
      return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
    }

    return "00:00:00";
  }
}
