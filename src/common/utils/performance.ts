// Quick performance profiling script
// Add this to measure where time is spent

const markers = {};

function startMark(label) {
  markers[label] = Date.now();
  console.log(`[PERF] START: ${label}`);
}

function endMark(label) {
  if (!markers[label]) {
    console.log(`[PERF] ERROR: No start mark for ${label}`);
    return;
  }
  const duration = Date.now() - markers[label];
  console.log(`[PERF] END: ${label} - ${duration}ms`);
  delete markers[label];
  return duration;
}

module.exports = { startMark, endMark };
