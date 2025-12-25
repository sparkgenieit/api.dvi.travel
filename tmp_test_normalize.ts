const normalizeCityName = (name: string) =>
  String(name || "")
    .toLowerCase()
    .replace(/[.,()]/g, " ")
    .replace(/\b(international|domestic)\b/g, " ")
    .replace(
      /\b(airport|air\s*port|railway|rail|station|stn|junction|jn|central|egmore|terminus|bus\s*stand|stand)\b/g,
      " ",
    )
    .replace(/\s+/g, " ")
    .trim();

const containsLocation = (hotspotLocation: string, target: string) => {
  if (!target) return false;
  const t = normalizeCityName(target);
  const parts = String(hotspotLocation || "")
    .split("|")
    .map((p) => normalizeCityName(p));
  console.log('Target:', t);
  console.log('Parts:', parts);
  return parts.includes(t);
};

const loc = "Madurai|Madurai, Railway Station|Madurai, Bus Stand , Arappalayam|Madurai Airport|Madurai, Mattuthavani";
const target = "Madurai";

console.log('Result:', containsLocation(loc, target));
