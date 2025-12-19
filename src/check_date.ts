// Check what the UTC hours are for the date stored as 06:30:00
const d = new Date("1970-01-01T06:30:00Z");
console.log("Date:", d);
console.log("getUTCHours:", d.getUTCHours());
console.log("getUTCMinutes:", d.getUTCMinutes());
console.log("getUTCSeconds:", d.getUTCSeconds());
console.log("getHours:", d.getHours());
console.log("getMinutes:", d.getMinutes());
console.log("getSeconds:", d.getSeconds());
