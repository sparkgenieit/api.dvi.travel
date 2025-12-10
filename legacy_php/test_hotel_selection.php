<?php
/*
* Hotel Candidates (readable list) — Improved UI
* - Per date × group (category)
* - Filters: radius + category + facilities (NOTE: facilities clause is currently commented in SQL block; uncomment to enforce)
* - Sorted by nearest distance
* - No vendor API calls
*/

include_once('jackus.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

/* ---------------- PDO helpers ---------------- */
function pdo(): PDO
{
  static $pdo = null;
  if ($pdo) return $pdo;
  $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
  $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => true, // tolerate repeated named params
  ]);
  try {
    $pdo->exec("SET SESSION MAX_EXECUTION_TIME=90000");
  } catch (Throwable $e) {
  }
  return $pdo;
}
function dbq(string $sql, array $params = []): PDOStatement
{
  $st = pdo()->prepare($sql);
  $st->execute($params);
  return $st;
}

/* ---------------- Small utils ---------------- */
function h($s)
{
  return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
function n2($x)
{
  return number_format((float)$x, 2);
}
function pretty_json($x)
{
  return json_encode($x, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

/* replace named params with literal values for display-only (phpMyAdmin copy) */
function sql_for_display(string $sql, array $params): string
{
  $out = $sql;
  uksort($params, fn($a, $b) => strlen($b) <=> strlen($a)); // longest first
  foreach ($params as $k => $v) {
    $ph = $k[0] === ':' ? $k : (':' . $k);
    if (is_numeric($v))       $rep = (string)$v;
    elseif ($v === null)      $rep = 'NULL';
    else                      $rep = "'" . str_replace(["\\", "'"], ["\\\\", "\\'"], (string)$v) . "'";
    $out = str_replace($ph, $rep, $out);
  }
  return $out;
}

/* ---------------- Facilities WHERE (safe placeholders) ---------------- */
function build_facility_clause(array $facilities, string $alias = 'H', string $prefix = 'fac'): array
{
  $clause = '';
  $params = [];
  if ($facilities) {
    $checks = [];
    foreach ($facilities as $i => $f) {
      $ph = ':' . $prefix . $i;
      $checks[] = "JSON_CONTAINS(COALESCE(NULLIF($alias.hotel_facilities,''),'[]'), JSON_QUOTE($ph))";
      $params[$ph] = $f;
    }
    $clause = ' AND (' . implode(' AND ', $checks) . ') ';
  }
  return [$clause, $params];
}

/* ---------------- Collect plan + dates ---------------- */
function load_plan_and_dates(int $planId): array
{
  $plan = dbq("
    SELECT
      p.no_of_nights,
      p.preferred_hotel_facilities,
      p.preferred_hotel_category,
      p.trip_start_date_and_time,
      p.meal_plan_breakfast,
      p.meal_plan_lunch,
      p.meal_plan_dinner,
      p.total_extra_bed,
      p.total_child_with_bed,
      p.total_child_without_bed
    FROM dvi_itinerary_plan_details p
    WHERE p.deleted='0' AND p.itinerary_plan_ID = :pid
    LIMIT 1
  ", [':pid' => $planId])->fetch();
  if (!$plan) return [null, [], null];

  $start    = date('Y-m-d', strtotime($plan['trip_start_date_and_time']));
  $checkout = date('Y-m-d', strtotime($start . ' +' . ((int)$plan['no_of_nights']) . ' day'));

  $dates = dbq("
    SELECT DISTINCT itinerary_route_date AS d
    FROM dvi_itinerary_route_details
    WHERE deleted='0' AND status='1'
      AND itinerary_plan_ID = :pid
      AND itinerary_route_date < :checkout
    ORDER BY d
  ", [':pid' => $planId, ':checkout' => $checkout])->fetchAll(PDO::FETCH_COLUMN);

  return [$plan, $dates, $checkout];
}

/* ---------------- Query hotels for a single date & category ---------------- */
function query_hotels_for_date_category(
  int $planId,
  string $dateYmd,
  int $categoryId,
  float $radiusKm,
  string $facilityClause,
  array $facilityParams
): array {

  $sql = "
SELECT
  IRD.itinerary_route_date AS route_date,
  SL.destination_location_city,
  SL.destination_location_lattitude,
  SL.destination_location_longitude,
  H.hotel_id, H.hotel_name,
  H.hotel_category   AS hotel_category_id,
  HC.hotel_category_title AS hotel_category,
  H.hotel_latitude, H.hotel_longitude, H.hotel_place,
  COALESCE(NULLIF(H.hotel_facilities,''),'[]') AS hotel_facilities,
  H.is_dvi_hotels, H.tbo_hotel_code, H.hobse_hotel_code,
  (6371 * ACOS(
    COS(RADIANS(SL.destination_location_lattitude)) *
    COS(RADIANS(H.hotel_latitude)) *
    COS(RADIANS(H.hotel_longitude) - RADIANS(SL.destination_location_longitude)) +
    SIN(RADIANS(SL.destination_location_lattitude)) *
    SIN(RADIANS(H.hotel_latitude))
  )) AS distance_in_km
FROM dvi_itinerary_route_details IRD
JOIN dvi_stored_locations SL ON SL.location_ID = IRD.location_id
JOIN dvi_hotel H ON H.hotel_place = SL.destination_location_city
 AND H.hotel_latitude  BETWEEN SL.destination_location_lattitude  - (:R/111.045)
                           AND SL.destination_location_lattitude  + (:R/111.045)
 AND H.hotel_longitude BETWEEN SL.destination_location_longitude - (:R/(111.045 * COS(RADIANS(SL.destination_location_lattitude))))
                           AND SL.destination_location_longitude + (:R/(111.045 * COS(RADIANS(SL.destination_location_lattitude))))
LEFT JOIN dvi_hotel_category HC ON HC.hotel_category_id = H.hotel_category
WHERE IRD.deleted='0' AND IRD.status='1'
  AND IRD.itinerary_plan_ID = :pid
  AND IRD.itinerary_route_date = :d
  AND H.status='1' AND H.deleted='0'
  AND H.hotel_category = :cat
  /* {$facilityClause} */  -- ← enable facilities by removing this comment
HAVING distance_in_km <= :R
ORDER BY distance_in_km ASC, H.hotel_id ASC
";

  $params = $facilityParams + [
    ':pid' => $planId,
    ':d'   => $dateYmd,         // ← bind the date
    ':cat' => $categoryId,
    ':R'   => $radiusKm,
  ];

  $rows = dbq($sql, $params)->fetchAll();

  foreach ($rows as &$r) {
    $src = [];
    if (!empty($r['is_dvi_hotels']))    $src[] = 'DVI';
    if (!empty($r['hobse_hotel_code'])) $src[] = 'HOBSE';
    if (!empty($r['tbo_hotel_code']))   $src[] = 'TBO';
    $r['hotel_sources'] = implode(',', $src);
    $r['distance_in_km'] = (float)$r['distance_in_km'];
  }
  unset($r);

  return [
    'sql'    => $sql,
    'params' => $params,
    'rows'   => $rows,
  ];
}

/* ---------------- Controller: build the report data ---------------- */
function build_candidates_report(int $planId, float $radiusKm): array
{
  [$plan, $dates, $checkout] = load_plan_and_dates($planId);
  if (!$plan) return ['plan' => null];

  // Default titles when no preferred list is provided
  $defaultMap = ['group1' => '2*', 'group2' => '3*', 'group3' => '4*', 'group4' => '5*'];

  // Use preferred_hotel_category (if present) with the rules,
  // else fall back to $defaultMap
  [$groupTitles, $groupIds] = resolve_group_categories($plan, $defaultMap);

  $facArr = array_values(array_filter(array_map('trim', explode(',', (string)$plan['preferred_hotel_facilities']))));
  [$facilityClause, $facilityParams] = build_facility_clause($facArr, 'H', 'fac_');

  $byDate = [];
  $sqlEcho = [];

  foreach ($dates as $d) {
    foreach (['group1', 'group2', 'group3', 'group4'] as $g) {
      $catId = $groupIds[$g];
      $res = query_hotels_for_date_category($planId, $d, $catId, $radiusKm, $facilityClause, $facilityParams);
      $byDate[$d][$g] = $res['rows'];
      $sqlEcho[$d][$g] = sql_for_display($res['sql'], $res['params']);
    }
  }

  return [
    'plan'         => $plan,
    'dates'        => $dates,
    'groupTitles'  => $groupTitles,
    'groupIds'     => $groupIds,
    'radiusKm'     => $radiusKm,
    'byDate'       => $byDate,
    'sqlEcho'      => $sqlEcho,
    'facilities'   => $facArr,
  ];
}

/* ---------------- Modern, compact UI ---------------- */
function render_candidates_html(array $rpt, int $planId, float $radiusKm, int $showPerGroup): void
{
  $plan = $rpt['plan'];
  $dates = $rpt['dates'];
  $firstDate = $dates ? $dates[0] : null;
?>
  <!doctype html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <title>Hotel Candidates (by date × group)</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
      :root {
        --bg: #0b1220;
        --panel: #ffffff;
        --ink: #0f172a;
        --muted: #64748b;
        --line: #e5e7eb;
        --chip: #eef2ff;
        --chipBorder: #e2e8f0;
        --brand: #2563eb;
      }

      * {
        box-sizing: border-box
      }

      body {
        font: 14px/1.5 system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial;
        margin: 0;
        color: var(--ink);
        background: #f6f8fb
      }

      .wrap {
        max-width: 1200px;
        margin: 70px auto 40px;
        padding: 0 16px
      }

      h1 {
        font-size: 20px;
        margin: 0 0 6px
      }

      .toolbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: var(--panel);
        border-bottom: 1px solid var(--line);
        display: flex;
        gap: 12px;
        align-items: center;
        padding: 10px 16px;
        z-index: 50
      }

      .toolbar .group {
        display: flex;
        gap: 8px;
        align-items: center
      }

      .toolbar input[type="text"],
      .toolbar input[type="number"] {
        padding: 6px 8px;
        border: 1px solid var(--line);
        border-radius: 8px;
        min-width: 180px
      }

      .toolbar .btn {
        padding: 7px 10px;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: #fff;
        cursor: pointer
      }

      .tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 0 0 12px
      }

      .tab {
        padding: 6px 10px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: #fff;
        cursor: pointer
      }

      .tab.active {
        background: var(--brand);
        color: #fff;
        border-color: var(--brand)
      }

      .card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 1px 0 rgba(0, 0, 0, .03);
        margin-bottom: 18px
      }

      .grouphead {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 10px 0 6px
      }

      .muted {
        color: var(--muted)
      }

      .chip {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 999px;
        background: var(--chip);
        color: #334155;
        border: 1px solid var(--chipBorder);
        font-size: 12px
      }

      table {
        width: 100%;
        border-collapse: collapse
      }

      th,
      td {
        padding: 6px 8px;
        border-bottom: 1px solid #eef2f7;
        text-align: left;
        vertical-align: top
      }

      th {
        font-weight: 600;
        color: #475569;
        background: #f8fafc;
        position: sticky;
        top: 40px;
        z-index: 1
      }

      .btnline {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
        margin: 8px 0
      }

      .btnsm {
        padding: 4px 8px;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
        font-size: 12px
      }

      details {
        margin-top: 6px
      }

      pre {
        white-space: pre-wrap;
        background: #0b1220;
        color: #eaf2ff;
        padding: 10px;
        border-radius: 8px;
        font-size: 12px;
        overflow: auto
      }

      .hidden {
        display: none
      }

      .datepanel.hidden {
        display: none
      }
    </style>
  </head>

  <body>
    <div class="toolbar">
      <div class="group">
        <strong>Plan:</strong> <span>#<?php echo h($planId); ?></span>
        <span class="muted">•</span>
        <strong>Radius</strong>
        <form id="radiusForm" method="get" style="display:flex;gap:8px;align-items:center">
          <input type="hidden" name="plan" value="<?php echo h($planId); ?>">
          <input type="number" step="0.1" name="radius" value="<?php echo h($radiusKm); ?>" style="width:90px">
          <strong>Show</strong>
          <input type="number" min="5" step="5" name="show" value="<?php echo h($showPerGroup); ?>" style="width:80px">
          <button class="btn" type="submit">Apply</button>
        </form>
      </div>
      <div class="group" style="margin-left:auto">
        <input id="globalFilter" type="text" placeholder="Filter hotels or city (active date)…">
        <button class="btn" id="expandAll">Expand all</button>
        <button class="btn" id="collapseAll">Collapse all</button>
      </div>
    </div>

    <div class="wrap">
      <h1>Hotel Candidates (nearest within radius, by date × group)</h1>

      <?php if (!$plan) { ?>
        <div class="card">Plan not found.</div>
      <?php } else { ?>
        <div class="card" style="display:flex;justify-content:space-between;align-items:center">
          <div class="muted">
            Radius: <b><?php echo h($rpt['radiusKm']); ?> km</b> &nbsp;•&nbsp;
            Group titles: <?php echo h(json_encode($rpt['groupTitles'])); ?> &nbsp;•&nbsp;
            Facilities filter: <?php echo $rpt['facilities'] ? h(implode(', ', $rpt['facilities'])) : '<i>none</i>'; ?> 
            <br>
            <br>
            Meals:
            B=<?php echo !empty($plan['meal_plan_breakfast']) ? 'true' : 'false'; ?>,
            L=<?php echo !empty($plan['meal_plan_lunch']) ? 'true' : 'false'; ?>,
            D=<?php echo !empty($plan['meal_plan_dinner']) ? 'true' : 'false'; ?> &nbsp;•&nbsp;
            Counts:
            EB=<?php echo (int)($plan['total_extra_bed'] ?? 0); ?>,
            CWB=<?php echo (int)($plan['total_child_with_bed'] ?? 0); ?>,
            CNB=<?php echo (int)($plan['total_child_without_bed'] ?? 0); ?>
          </div>
          <div class="muted">Dates: <?php echo count($rpt['dates']); ?></div>
        </div>

        <!-- Date tabs -->
        <div class="tabs" id="dateTabs">
          <?php foreach ($dates as $i => $d): ?>
            <button class="tab<?php echo ($i === 0 ? ' active' : ''); ?>" data-target="date-<?php echo h(str_replace('-', '', $d)); ?>">
              <?php echo h($d); ?>
            </button>
          <?php endforeach; ?>
        </div>
        <!-- Date panels -->
        <?php foreach ($dates as $i => $d):
          // Fetch distinct stay cities for THIS date from itinerary
          $cityNames = dbq("SELECT DISTINCT SL.destination_location_city AS city FROM dvi_itinerary_route_details IRD JOIN dvi_stored_locations SL ON SL.location_ID = IRD.location_id WHERE IRD.deleted='0' AND IRD.status='1' AND IRD.itinerary_plan_ID = :pid AND IRD.itinerary_route_date = :d", [':pid' => $planId, ':d' => $d])->fetchAll(PDO::FETCH_COLUMN);

          $cityLabel = $cityNames
            ? implode(', ', array_slice($cityNames, 0, 3)) . (count($cityNames) > 3 ? ' +' . (count($cityNames) - 3) . ' more' : '')
            : '—';
        ?>
          <?php $panelId = 'date-' . str_replace('-', '', $d); ?>
          <div class="datepanel<?php echo ($i !== 0 ? ' hidden' : ''); ?>" id="<?php echo h($panelId); ?>" data-date="<?php echo h($d); ?>">
            <div class="card">
              <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                  <b><?php echo h($d); ?></b>
                  <span class="muted">• Stay location: <?php echo h($cityLabel); ?></span>
                </div>
                <div class="chip">
                  <?php echo (new DateTime($d) < new DateTime(date('Y-m-d'))) ? 'past date' : 'upcoming'; ?>
                </div>
              </div>

              <?php foreach (['group1', 'group2', 'group3', 'group4'] as $g): ?>
                <?php
                $title = $rpt['groupTitles'][$g];
                $list  = $rpt['byDate'][$d][$g] ?? [];
                $sql   = $rpt['sqlEcho'][$d][$g] ?? '';
                $initial = $showPerGroup;
                $total   = count($list);
                ?>
                <div class="grouphead">
                  <h3 style="margin:10px 0 6px 0;">
                    <?php echo h(strtoupper($g)); ?>
                    <span class="muted">(<?php echo h($title); ?>)</span>
                    — <span class="countShown"><?php echo $total; ?></span>/<span class="countTotal"><?php echo $total; ?></span> hotel(s)
                  </h3>
                  <div class="btnline">
                    <button class="btnsm toggleCollapse">Collapse</button>
                  </div>
                </div>

                <div class="tablewrap" data-initial="<?php echo $initial; ?>">
                  <table class="hoteltable">
                    <thead>
                      <tr>
                        <th style="width:34px">#</th>
                        <th>Hotel</th>
                        <th>City</th>
                        <th>Category</th>
                        <th>Distance</th>
                        <th>Sources</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $irow = 0;
                      foreach ($list as $hrow): $irow++; ?>
                        <tr>
                          <td><?php echo $irow; ?></td>
                          <td class="hname">#<?php echo h($hrow['hotel_id']); ?> <?php echo h($hrow['hotel_name']); ?></td>
                          <td class="hcity"><?php echo h($hrow['destination_location_city']); ?></td>
                          <td><?php echo h($hrow['hotel_category']); ?></td>
                          <td><?php echo n2($hrow['distance_in_km']); ?> km</td>
                          <td><?php echo h($hrow['hotel_sources'] ?: '—'); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>

                  <details>
                    <summary>Show SQL (phpMyAdmin-ready)</summary>
                    <div class="btnline" style="margin:8px 0 0 0">
                      <button class="btnsm copySql" data-sql="<?php echo h($sql); ?>">Copy SQL</button>
                    </div>
                    <pre><?php echo h($sql); ?></pre>
                  </details>
                </div>
                <hr style="border:none;border-top:1px dashed #e5e7eb;margin:14px 0">
              <?php endforeach; ?>

            </div><!-- /card -->
          </div><!-- /datepanel -->
        <?php endforeach; ?>

      <?php } ?>
    </div>
    <script>
      (function() {
        // Tabs (switch date panels; keep filter empty on switch)
        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(btn => {
          btn.addEventListener('click', () => {
            tabs.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.datepanel').forEach(p => p.classList.add('hidden'));
            const panel = document.getElementById(btn.dataset.target);
            if (panel) panel.classList.remove('hidden');
            const input = document.getElementById('globalFilter');
            input.value = '';
            applyFilter(''); // re-counts for the active panel
          });
        });

        // Count visible rows (not display:none)
        function visibleCount(rows) {
          let n = 0;
          rows.forEach(tr => {
            if (tr.style.display !== 'none') n++;
          });
          return n;
        }

        // Recompute counts for one group section
        function recalcCountsFor(wrap) {
          const rows = Array.from(wrap.querySelectorAll('tbody tr'));
          const shown = visibleCount(rows);
          const head = wrap.previousElementSibling; // .grouphead
          if (!head) return;
          const countShown = head.querySelector('.countShown');
          const countTotal = head.querySelector('.countTotal');
          if (countShown) countShown.textContent = String(shown);
          if (countTotal) countTotal.textContent = String(rows.length); // overall total
        }

        // Apply global filter to active date only
        function applyFilter(q) {
          q = (q || '').trim().toLowerCase();
          const activePanel = document.querySelector('.datepanel:not(.hidden)');
          if (!activePanel) return;
          activePanel.querySelectorAll('.tablewrap').forEach(wrap => {
            const rows = wrap.querySelectorAll('tbody tr');
            rows.forEach(tr => {
              const hay = ((tr.querySelector('.hname')?.textContent || '') + ' ' +
                (tr.querySelector('.hcity')?.textContent || '')).toLowerCase();
              tr.style.display = (!q || hay.includes(q)) ? '' : 'none';
            });
            recalcCountsFor(wrap);
          });
        }

        document.getElementById('globalFilter').addEventListener('input', (e) => applyFilter(e.target.value));

        // Expand/Collapse all for active date
        document.getElementById('expandAll').addEventListener('click', () => {
          const panel = document.querySelector('.datepanel:not(.hidden)');
          if (!panel) return;
          panel.querySelectorAll('.tablewrap').forEach(wrap => {
            wrap.classList.remove('hidden');
            const toggle = wrap.previousElementSibling?.querySelector('.toggleCollapse');
            if (toggle) toggle.textContent = 'Collapse';
          });
        });
        document.getElementById('collapseAll').addEventListener('click', () => {
          const panel = document.querySelector('.datepanel:not(.hidden)');
          if (!panel) return;
          panel.querySelectorAll('.tablewrap').forEach(wrap => {
            wrap.classList.add('hidden');
            const toggle = wrap.previousElementSibling?.querySelector('.toggleCollapse');
            if (toggle) toggle.textContent = 'Expand';
          });
        });

        // Per-group collapse/expand (event delegation)
        document.addEventListener('click', (ev) => {
          const btn = ev.target.closest('.toggleCollapse');
          if (!btn) return;
          const head = btn.closest('.grouphead');
          const wrap = head?.nextElementSibling;
          if (!wrap || !wrap.classList.contains('tablewrap')) return;
          const willCollapse = !wrap.classList.contains('hidden');
          wrap.classList.toggle('hidden', willCollapse);
          btn.textContent = willCollapse ? 'Expand' : 'Collapse';
        });

        // Copy SQL (delegated)
        document.addEventListener('click', async (ev) => {
          const btn = ev.target.closest('.copySql');
          if (!btn) return;
          try {
            await navigator.clipboard.writeText(btn.dataset.sql || '');
            btn.textContent = 'Copied ✓';
            setTimeout(() => btn.textContent = 'Copy SQL', 1200);
          } catch (e) {
            btn.textContent = 'Copy failed';
            setTimeout(() => btn.textContent = 'Copy SQL', 1200);
          }
        });

        // Initial count for the first (visible) panel
        const firstPanel = document.querySelector('.datepanel:not(.hidden)');
        if (firstPanel) {
          firstPanel.querySelectorAll('.tablewrap').forEach(recalcCountsFor);
        }
      })();
    </script>
  </body>

  </html>
<?php
}

/* ------------------- bootstrap ------------------- */
$planId = isset($_GET['plan']) ? (int)$_GET['plan'] : 17881;
if ($planId <= 0) {
  echo "<div style='font:14px system-ui;margin:20px'>Add <code>?plan=YOUR_PLAN_ID</code> to the URL.</div>";
  exit;
}
$radius = isset($_GET['radius']) ? (float)$_GET['radius'] : 10.0;
$show   = isset($_GET['show']) ? max(5, (int)$_GET['show']) : 25;

$rpt = build_candidates_report($planId, $radius);
render_candidates_html($rpt, $planId, $radius, $show);
