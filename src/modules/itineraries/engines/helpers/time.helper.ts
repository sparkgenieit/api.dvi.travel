// FILE: src/modules/itineraries/engines/helpers/time.helper.ts

export type TimeLike = string | Date | number | null | undefined;

export function timeToSeconds(value: TimeLike): number {
  if (value == null) return 0;

  if (value instanceof Date) {
    // Interpret as time-of-day: HH:MM:SS from that Date
    const h = value.getHours();
    const m = value.getMinutes();
    const s = value.getSeconds();
    return h * 3600 + m * 60 + s;
  }

  if (typeof value === "number") {
    return Math.max(0, value);
  }

  // string
  const str = value.trim();
  if (!str) return 0;

  // Format "HH:MM:SS" or "HH:MM"
  const parts = str.split(":").map((p) => parseInt(p, 10) || 0);
  const [h, m, s] =
    parts.length === 3 ? parts : parts.length === 2 ? [parts[0], parts[1], 0] : [0, 0, 0];
  return h * 3600 + m * 60 + s;
}

export function secondsToTime(totalSeconds: number): string {
  if (!Number.isFinite(totalSeconds)) totalSeconds = 0;
  const secs = Math.max(0, Math.floor(totalSeconds));
  const h = Math.floor(secs / 3600);
  const m = Math.floor((secs % 3600) / 60);
  const s = secs % 60;
  const pad = (n: number) => (n < 10 ? `0${n}` : `${n}`);
  return `${pad(h)}:${pad(m)}:${pad(s)}`;
}

export function addTimes(base: TimeLike, delta: TimeLike): string {
  const total = timeToSeconds(base) + timeToSeconds(delta);
  return secondsToTime(total);
}

export function addSeconds(base: TimeLike, seconds: number): string {
  const total = timeToSeconds(base) + seconds;
  return secondsToTime(total);
}

export function minutesToTime(minutes: number): string {
  const secs = Math.round(Math.max(0, minutes) * 60);
  return secondsToTime(secs);
}
