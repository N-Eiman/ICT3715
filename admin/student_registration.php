<?php
session_start();
if (!isset($_SESSION['adminID'])) {
    header("Location: admin_login.php");
    exit;
}

include '../include/db_connect.php';
include '../include/header.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentSchoolNumber = $_POST['studentSchoolNumber'];
    $studentName = $_POST['studentName'];
    $studentSurname = $_POST['studentSurname'];
    $studentGrade = $_POST['studentGrade'];
    $parentID = $_POST['parentID']; // chosen parent

    $stmt = $conn->prepare("INSERT INTO student (studentSchoolNumber, studentName, studentSurname, studentGrade, parentID) 
                            VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        $message = "Prepare failed: " . $conn->error;
    } else {
        $stmt->bind_param("isssi", $studentSchoolNumber, $studentName, $studentSurname, $studentGrade, $parentID);
        if ($stmt->execute()) {
            $message = "✅ Student registered successfully!";
        } else {
            $message = "Error inserting student: " . $stmt->error;
        }
        $stmt->close();
    }
}

// fetch parents for dropdown
$parents = [];
$res = $conn->query("SELECT parentID, parentName, parentSurname FROM parents ORDER BY parentSurname ASC, parentName ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $parents[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration | Locker System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2f2a1d6a2.js" crossorigin="anonymous"></script>
    <style>
        body { background: #bbe4e9; color: #5585b5; font-family: monospace, sans-serif; }
        .container { margin-top: 50px; max-width: 700px; }
        .card { background: rgba(255,255,255,0.8); border-radius: 12px; padding: 25px; }
        .btn-primary { background: #5585b5; border: none; }
        .btn-primary:hover { background: rgba(255,215,0,0.8); color: #333; }
        .message { margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>

<main class="container">
    <div class="card">
        <h2 class="mb-4"><i class="fas fa-user-plus"></i> Student Registration</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="studentSchoolNumber" class="form-label">Student School Number</label>
                <input type="number" class="form-control" id="studentSchoolNumber" name="studentSchoolNumber" required>
            </div>
            <div class="mb-3">
                <label for="studentName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="studentName" name="studentName" required>
            </div>
            <div class="mb-3">
                <label for="studentSurname" class="form-label">Surname</label>
                <input type="text" class="form-control" id="studentSurname" name="studentSurname" required>
            </div>
            <div class="mb-3">
                <label for="studentGrade" class="form-label">Grade</label>
                <select class="form-control" id="studentGrade" name="studentGrade" required>
                    <option value="">-- Select Grade --</option>
                    <option value="Grade 8">Grade 8</option>
                    <option value="Grade 9">Grade 9</option>
                    <option value="Grade 10">Grade 10</option>
                    <option value="Grade 11">Grade 11</option>
                    <option value="Grade 12">Grade 12</option>
                </select>
            </div>
            <div class="mb-3">
                 <label for="parentID" class="form-label">Select Parent</label>
                    <select class="form-control" id="parentID" name="parentID" required>
                            <option value="">-- Choose Parent --</option>
                            <?php foreach ($parents as $parent): ?>
                            <option value="<?= $parent['parentID'] ?>">
                            <?= htmlspecialchars($parent['parentSurname']) ?>, <?= htmlspecialchars($parent['parentName']) ?> (ID: <?= $parent['parentID'] ?>)
                            </option>
                            <?php endforeach; ?>
                    </select>
        </div>

            <button type="submit" class="btn btn-primary">Register Student</button>
        </form>
    </div>
</main>

<?php include '../include/footer.php'; ?>
