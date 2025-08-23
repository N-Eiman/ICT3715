<?php
// admin_locker_report.php (fixed)
// Shows locker allocation / usage; supports filtering by grade and date range.
// Replaces previous file — uses `lockersID` (correct column name) and adds error logging.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['adminID'])) {
    header("Location: admin_login.php");
    exit;
}

include '../include/db_connect.php';
include '../include/header.php';

// -----------------------------
// Helper: Run query with error check
// -----------------------------
function runQuery($conn, $sql) {
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        // Stop and show SQL error + the query — useful for debugging
        die("<strong>SQL Error:</strong> " . mysqli_error($conn) . "<br><strong>Query:</strong> " . htmlspecialchars($sql));
    }
    return $res;
}

// -----------------------------
// Determine filter inputs (GET)
// -----------------------------
// Fetch distinct grades from DB for the select options
$gradeRows = runQuery($conn, "SELECT DISTINCT studentGrade FROM student ORDER BY studentGrade ASC");
$allGrades = [];
while ($gr = mysqli_fetch_assoc($gradeRows)) {
    $allGrades[] = $gr['studentGrade'];
}

// defaults
$defaultGrades = ['Grade 8', 'Grade 11'];
$defaultStart = '2026-01-01';
$defaultEnd   = '2026-06-30';

// Read GET input; allow single or multiple grades
$selectedGrades = [];
if (isset($_GET['grades'])) {
    $g = $_GET['grades'];
    if (is_array($g)) $selectedGrades = $g;
    else $selectedGrades = [$g];
}
// sanitize: keep only grades that exist in DB
$selectedGrades = array_values(array_intersect($selectedGrades, $allGrades));
if (empty($selectedGrades)) {
    $selectedGrades = $defaultGrades;
}

$startDate = $_GET['start'] ?? $defaultStart;
$endDate   = $_GET['end']   ?? $defaultEnd;

// Basic date validation (YYYY-MM-DD), fallback to defaults on invalid input
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate)) $startDate = $defaultStart;
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate))   $endDate   = $defaultEnd;

// Build safe SQL list for grades
$escapedGrades = array_map(function($g) use ($conn) {
    return "'" . mysqli_real_escape_string($conn, $g) . "'";
}, $selectedGrades);
$gradeListSQL = implode(',', $escapedGrades);
$gradeDisplay = implode(', ', $selectedGrades);

// -----------------------------
// Summary + report queries (respecting filters)
// -----------------------------


// //-- 1️⃣ Add the studentSchoolNumber column to bookings
// ALTER TABLE bookings 
// ADD COLUMN studentSchoolNumber VARCHAR(50) AFTER parentID;

// -- 2️⃣ Populate it by linking via parentID
// UPDATE bookings b
// JOIN (
//     SELECT parentID, MIN(studentSchoolNumber) AS studentSchoolNumber
//     FROM student
//     GROUP BY parentID
// ) s ON b.parentID = s.parentID
// SET b.studentSchoolNumber = s.studentSchoolNumber;

// -- 3️⃣ Make sure there are no NULLs left
// UPDATE bookings 
// SET studentSchoolNumber = 'UNKNOWN' 
// WHERE studentSchoolNumber IS NULL;

// -- 4️⃣ Optional: Make it NOT NULL if you want to enforce it
// ALTER TABLE bookings 
// MODIFY studentSchoolNumber VARCHAR(50) NOT NULL;
// // Allocated lockers count (lockers assigned in bookings as lockersID NOT NULL)

$allocatedQuery = "
    SELECT COUNT(*) AS allocatedCount
    FROM bookings b
    JOIN student s ON b.studentSchoolNumber = s.studentSchoolNumber
    WHERE b.lockersID IS NOT NULL
      AND s.studentGrade IN ($gradeListSQL)
      AND b.booked_for BETWEEN '$startDate' AND '$endDate'
";
$allocatedCount = intval(mysqli_fetch_assoc(runQuery($conn, $allocatedQuery))['allocatedCount'] ?? 0);

// Awaiting allocation (parents booked but lockersID NULL)
$awaitingQuery = "
    SELECT COUNT(*) AS awaitingCount
    FROM bookings b
    JOIN student s ON b.studentSchoolNumber = s.studentSchoolNumber
    WHERE b.lockersID IS NULL
      AND s.studentGrade IN ($gradeListSQL)
      AND b.booked_for BETWEEN '$startDate' AND '$endDate'
";
$awaitingCount = intval(mysqli_fetch_assoc(runQuery($conn, $awaitingQuery))['awaitingCount'] ?? 0);

// Total bookings in the selected period & grades
$summaryPeriodQuery = "
    SELECT COUNT(*) AS total_booked
    FROM bookings b
    JOIN student s ON b.studentSchoolNumber = s.studentSchoolNumber
    WHERE s.studentGrade IN ($gradeListSQL)
      AND b.booked_for BETWEEN '$startDate' AND '$endDate'
";
$totalBookedPeriod = intval(mysqli_fetch_assoc(runQuery($conn, $summaryPeriodQuery))['total_booked'] ?? 0);

// Total bookings all time (for context — not filtered)
$totalAllTimeQuery = "SELECT COUNT(*) AS total_booked FROM bookings";
$totalAllTime = intval(mysqli_fetch_assoc(runQuery($conn, $totalAllTimeQuery))['total_booked'] ?? 0);

// Locker usage by grade (for the chosen grades and period)
$usageByGradeQuery = "
    SELECT s.studentGrade AS grade, 
           COUNT(CASE WHEN b.lockersID IS NOT NULL THEN 1 END) AS total_allocated,
           COUNT(CASE WHEN b.lockersID IS NULL THEN 1 END) AS total_unallocated
    FROM student s
    LEFT JOIN bookings b 
      ON s.studentSchoolNumber = b.studentSchoolNumber
      AND b.booked_for BETWEEN '$startDate' AND '$endDate'
    WHERE s.studentGrade IN ($gradeListSQL)
    GROUP BY s.studentGrade
    ORDER BY s.studentGrade
";
$usageByGradeResult = runQuery($conn, $usageByGradeQuery);

// Allocated lockers detailed list (student + allocated lockers)
$allocatedListQuery = "
    SELECT s.studentSchoolNumber, s.studentName, s.studentSurname, s.studentGrade, b.lockersID
    FROM student s
    JOIN bookings b ON s.studentSchoolNumber = b.studentSchoolNumber
    WHERE b.lockersID IS NOT NULL
      AND s.studentGrade IN ($gradeListSQL)
      AND b.booked_for BETWEEN '$startDate' AND '$endDate'
    ORDER BY s.studentGrade, s.studentSurname
";
$allocatedListResult = runQuery($conn, $allocatedListQuery);

// Allocation breakdown by grade (Grade 8–12)
$gradeAllocationQuery = "
    SELECT s.studentGrade AS grade, COUNT(*) AS allocated
    FROM bookings b
    JOIN student s ON b.studentSchoolNumber = s.studentSchoolNumber
    WHERE b.lockersID IS NOT NULL
      AND s.studentGrade IN ('Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12')
    GROUP BY s.studentGrade
    ORDER BY s.studentGrade
";
$gradeAllocations = runQuery($conn, $gradeAllocationQuery);

// Prepare data for chart
$gradeLabels = [];
$gradeCounts = [];
while ($row = mysqli_fetch_assoc($gradeAllocations)) {
    $gradeLabels[] = $row['grade'];
    $gradeCounts[] = $row['allocated'];
}


?>
<style>
/* Ensure body covers the viewport and sets background */
body {
    position: relative;       /* allows overlay positioning */
    min-height: 100vh;        /* ensure full viewport coverage */
    background: url('../images/locker.jpg') no-repeat center center fixed;
    background-size: cover;
}

/* Full-page overlay */
body::before {
    content: "";
    position: fixed;          /* covers viewport even when scrolling */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4); /* adjust opacity as needed */
    z-index: 1;               /* sits above background but below content */
}

/* Ensure main content sits above overlay */
.container, .report-card, nav.navbar, footer {
    position: relative;       /* needed for z-index */
    z-index: 2;               /* above overlay */
}

/* Report cards — include original styling + overlay-safe z-index */
.report-card {
    position: relative;       /* above overlay */
    z-index: 2;
    background: rgba(255,255,255,0.95);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.18);
}

/* Optional: make scrollable table visible above overlay */
.scrollable-table {
    max-height: 420px;
    overflow-y: auto;
    position: relative;
    z-index: 2;
}

/* Adjust charts so they remain readable */
canvas {
    max-width: 100%;
    height: 300px !important; /* smaller for a cleaner layout */
    position: relative;
    z-index: 2;
    
}

/* Keep nav and footer above overlay */
nav.navbar, footer {
    z-index: 3;
}
</style>

<div class="container mt-4">
     <!-- Dashboard Title -->
  <h1 class="text-center fw-bold mb-3" style="color: #ffd700;">
    Locker MIS Dashboard — <?= htmlspecialchars($gradeDisplay) ?> (<?= htmlspecialchars($startDate) ?> → <?= htmlspecialchars($endDate) ?>)
  </h1>
  <div class="report-card mb-4 text-center" style="color: #fff;">
    
    <canvas id="gradeChart" width="400" height="400"></canvas>
  </div>

 
  <!-- Filter Form -->
  <div class="report-card mb-4">
    <form method="get" class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Grades</label>
        <select name="grades[]" class="form-select" multiple>
          <?php foreach ($allGrades as $g): 
            $sel = in_array($g, $selectedGrades) ? 'selected' : '';
            echo "<option value=\"" . htmlspecialchars($g) . "\" $sel>" . htmlspecialchars($g) . "</option>";
          endforeach; ?>
        </select>
        <div class="form-text">Hold Ctrl (Cmd) to select multiple.</div>
      </div>

      <div class="col-md-3">
        <label class="form-label">Start date</label>
        <input type="date" name="start" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
      </div>

      <div class="col-md-3">
        <label class="form-label">End date</label>
        <input type="date" name="end" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
      </div>

      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Apply filter</button>
      </div>
    </form>
  </div>

  <!-- KPI Tiles -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="report-card text-center">
        <h6>Bookings (period)</h6>
        <h3 class="text-success"><?= $totalBookedPeriod ?></h3>
        <small class="text-muted">For selected grades & period</small>
      </div>
    </div>

    <div class="col-md-4">
      <div class="report-card text-center">
        <h6>Allocated lockers</h6>
        <h3 class="text-primary"><?= $allocatedCount ?></h3>
        <small class="text-muted">Assigned (lockersID present)</small>
      </div>
    </div>

    <div class="col-md-4">
      <div class="report-card text-center">
        <h6>Awaiting allocation</h6>
        <h3 class="text-warning"><?= $awaitingCount ?></h3>
        <small class="text-muted">Booked but lockers not assigned</small>
      </div>
    </div>
  </div>

  <!-- Locker Allocation Chart -->
  <div class="report-card mb-4 text-center">
    
    <canvas id="lockerChartFiltered" width="400" height="400"></canvas>
  </div>

  <!-- Locker Usage Table -->
  <div class="report-card mb-4">
    <h5>Locker Usage by Grade (allocated / unallocated)</h5>
    <table class="table table-sm table-bordered">
      <thead class="table-dark">
        <tr><th>Grade</th><th>Allocated</th><th>Awaiting</th></tr>
      </thead>
      <tbody>
        <?php while ($r = mysqli_fetch_assoc($usageByGradeResult)): ?>
          <tr>
            <td><?= htmlspecialchars($r['grade']) ?></td>
            <td><?= intval($r['total_allocated']) ?></td>
            <td><?= intval($r['total_unallocated']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Allocated Lockers List -->
  <div class="report-card mb-5">
    <h5>Allocated Lockers — Detailed list</h5>
    <div class="scrollable-table">
      <table class="table table-sm table-striped table-bordered">
        <thead class="table-primary">
          <tr>
            <th>Student #</th>
            <th>Name</th>
            <th>Grade</th>
            <th>Locker ID</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($allocatedListResult) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($allocatedListResult)): ?>
              <tr>
                <td><?= htmlspecialchars($row['studentSchoolNumber']) ?></td>
                <td><?= htmlspecialchars($row['studentName'] . ' ' . $row['studentSurname']) ?></td>
                <td><?= htmlspecialchars($row['studentGrade']) ?></td>
                <td><?= htmlspecialchars($row['lockersID']) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center">No allocated lockers found for this filter.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const gradeCtx = document.getElementById('gradeChart').getContext('2d');
new Chart(gradeCtx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($gradeLabels) ?>,
        datasets: [{
            data: <?= json_encode($gradeCounts) ?>,
            backgroundColor: ['#2196F3', '#4CAF50', '#FFC107', '#FF5722', '#9C27B0'],
            borderColor: ['#1976D2', '#2E7D32', '#FFA000', '#E64A19', '#7B1FA2'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Locker Allocation by Grade (8–12)',
              color: '#ffd700',   // title color
                font: { size: 18, weight: 'bold' }
             }
        }
    }
});

const lockerCtx = document.getElementById('lockerChartFiltered').getContext('2d');
new Chart(lockerCtx, {
    type: 'pie',
    data: {
        labels: ['Allocated', 'Awaiting'],
        datasets: [{
            data: [<?= intval($allocatedCount) ?>, <?= intval($awaitingCount) ?>],
            backgroundColor: ['#2196F3', '#9C27B0'],
            borderColor: ['#1976D2', '#7B1FA2'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Locker Allocation (Filtered Grades)',
              color: '#ffd700',   // title color
                font: { size: 18, weight: 'bold' }
             }
        }
    }
});
</script>

<?php include '../include/footer.php'; ?>
