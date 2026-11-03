<?php
require "auth.php";
requireLogin();
require "conn.php";

$stmt = $conn->prepare("SELECT id, name, age, gender, course, email, telephone, regno FROM students WHERE id = :id");
$stmt->execute([":id" => $_SESSION["student_id"]]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    $_SESSION = [];
    session_destroy();
    header("Location: login.php");
    exit;
}

$initials = strtoupper(substr(trim($student["name"]), 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | CampusHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="app-page">
    <?php renderSidebar("dashboard"); ?>
    <main class="main-content">
        <header class="page-header">
            <div>
                <p class="eyebrow">OVERVIEW</p>
                <h1>Dashboard</h1>
                <p class="muted">Welcome back, <?php echo htmlspecialchars($student["name"], ENT_QUOTES, "UTF-8"); ?>.</p>
            </div>
        </header>
        <section class="welcome-card">
            <div class="profile-photo">
                <a class="student-avatar" href="profile.jpeg" target="_blank" rel="noopener" aria-label="View full profile image in a new tab">
                    <img src="profile.jpeg" alt="Profile image">
                </a>
                <a class="view-photo-link" href="profile.jpeg" target="_blank" rel="noopener">View full image</a>
            </div>
            <div class="welcome-copy">
                <p class="eyebrow">STUDENT PROFILE</p>
                <h2><?php echo htmlspecialchars($student["name"], ENT_QUOTES, "UTF-8"); ?></h2>
                <p>Your student information at a glance.</p>
            </div>
            <span class="status-pill">Active student</span>
        </section>
        <section class="detail-grid" aria-label="Student details">
            <article class="detail-card">
                <span>Student ID</span>
                <strong><?php echo htmlspecialchars($student["regno"] ?? "", ENT_QUOTES, "UTF-8"); ?></strong>
            </article>
            <article class="detail-card">
                <span>Class / course</span>
                <strong><?php echo htmlspecialchars($student["course"] ?? "Not provided", ENT_QUOTES, "UTF-8"); ?></strong>
            </article>
            <article class="detail-card">
                <span>Age</span>
                <strong><?php echo htmlspecialchars((string) $student["age"], ENT_QUOTES, "UTF-8"); ?></strong>
            </article>
            <article class="detail-card">
                <span>Gender</span>
                <strong><?php echo htmlspecialchars($student["gender"] ?? "Not provided", ENT_QUOTES, "UTF-8"); ?></strong>
            </article>
            <article class="detail-card">
                <span>Email address</span>
                <strong><?php echo htmlspecialchars($student["email"] ?? "Not provided", ENT_QUOTES, "UTF-8"); ?></strong>
            </article>
            <article class="detail-card">
                <span>Telephone number</span>
                <strong><?php echo htmlspecialchars($student["telephone"] ?? "Not provided", ENT_QUOTES, "UTF-8"); ?></strong>
            </article>
        </section>
        <section class="panel dashboard-next">
            <div>
                <h2>Manage student records</h2>
                <p class="muted">View the student directory or add a new student record.</p>
            </div>
            <div class="button-row">
                <a class="button button-secondary" href="index.php">View students</a>
                <a class="button" href="add_student.php">Add student</a>
            </div>
        </section>
    </main>
</body>
</html>
