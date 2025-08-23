<?php
include '../include/header.php';
require '../include/db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentSchoolNumber'])) {
    $studentID = $_POST['studentSchoolNumber'];

    // Get student grade
    $stmt = $conn->prepare("SELECT studentGrade FROM student WHERE studentSchoolNumber = ?");
    $stmt->bind_param("i", $studentID);
    $stmt->execute();
    $stmt->bind_result($grade);
    $stmt->fetch();
    $stmt->close();

    if ($grade) {
        $gradeNumber = (int) filter_var($grade, FILTER_SANITIZE_NUMBER_INT);

        // Find available locker for this grade
        $stmt = $conn->prepare("
            SELECT lockersID FROM lockers 
            WHERE grade = ? AND lockersID NOT IN (
                SELECT lockersID FROM bookings WHERE lockersID IS NOT NULL
            )
            LIMIT 1
        ");
        $stmt->bind_param("i", $gradeNumber);
        $stmt->execute();
        $stmt->bind_result($lockerID);
        $stmt->fetch();
        $stmt->close();

        if ($lockerID) {
            // Assign locker
            $conn->begin_transaction();
            try {
                $stmt = $conn->prepare("UPDATE bookings SET lockersID = ? WHERE studentSchoolNumber = ?");
                $stmt->bind_param("ii", $lockerID, $studentID);
                $stmt->execute();
                $stmt->close();

                $message = "Locker $lockerID assigned to student $studentID.";
                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback();
                $message = "Error: " . $e->getMessage();
            }
        } else {
            $message = "No available lockers for Grade $gradeNumber.";
        }
    } else {
        $message = "Student grade not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Locker Management</title>
    <style>
        body {
            background: linear-gradient(to bottom right, #ffe6f0, #ffffff);
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 900px;
            margin: 60px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #cc3366;
            margin-bottom: 30px;
        }
        .scroll-box {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f9f2f4;
            color: #cc3366;
        }
        tr:hover {
            background-color: #fceef3;
        }
        button {
            background-color: #cc3366;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f2f4;
            border-left: 5px solid #cc3366;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="fw-bold">Locker Allocation Panel</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <h3 style="color:#cc3366;">Locker Availability by Grade</h3>
        <table style="width:100%; margin-bottom:20px; border-collapse:collapse;">
            <thead>
                <tr style="background-color:#f9f2f4;">
                    <th style="padding:10px;">Grade</th>
                    <th style="padding:10px;">Total Lockers</th>
                    <th style="padding:10px;">Assigned</th>
                    <th style="padding:10px;">Available</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("
                    SELECT grade, 
                        COUNT(*) AS total, 
                        SUM(CASE WHEN lockersID IN (SELECT lockersID FROM bookings WHERE lockersID IS NOT NULL) THEN 1 ELSE 0 END) AS assigned,
                        SUM(CASE WHEN lockersID NOT IN (SELECT lockersID FROM bookings WHERE lockersID IS NOT NULL) THEN 1 ELSE 0 END) AS available
                    FROM lockers
                    GROUP BY grade
                    ORDER BY grade
                ");
                while ($row = $stmt->fetch_assoc()):
                ?>
                <tr>
                    <td style="padding:10px;"><?= htmlspecialchars($row['grade']) ?></td>
                    <td style="padding:10px;"><?= $row['total'] ?></td>
                    <td style="padding:10px;"><?= $row['assigned'] ?></td>
                    <td style="padding:10px;"><?= $row['available'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="scroll-box">
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Grade</th>
                        <th>School Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("
                        SELECT s.studentName, s.studentGrade, s.studentSchoolNumber
                        FROM student s
                        WHERE s.studentSchoolNumber NOT IN (
                            SELECT studentSchoolNumber FROM bookings WHERE lockersID IS NOT NULL
                        )
                        LIMIT 10
                    ");
                    while ($row = $stmt->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['studentName']) ?></td>
                        <td><?= htmlspecialchars($row['studentGrade']) ?></td>
                        <td><?= htmlspecialchars($row['studentSchoolNumber']) ?></td>
                        <td>
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="studentSchoolNumber" value="<?= $row['studentSchoolNumber'] ?>">
                                <button type="submit">Allocate</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
<?php include '../include/footer.php'; ?>
</html>


