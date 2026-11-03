<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function requireLogin()
{
    if (empty($_SESSION["student_id"])) {
        header("Location: login.php");
        exit;
    }
}

function isValidTelephone($telephone)
{
    $digitCount = preg_match_all("/[0-9]/", $telephone);
    return preg_match("/^[0-9+(). -]{7,30}$/", $telephone) === 1
        && $digitCount >= 7
        && $digitCount <= 15;
}

function renderSidebar($activePage)
{
    $studentName = htmlspecialchars($_SESSION["student_name"] ?? "Student", ENT_QUOTES, "UTF-8");
    ?>
    <aside class="sidebar">
        <a class="brand" href="dashboard.php">Campus<span>Hub</span></a>
        <div class="sidebar-profile">
            <div class="profile-avatar small"><?php echo strtoupper(substr($studentName, 0, 1)); ?></div>
            <div>
                <strong><?php echo $studentName; ?></strong>
                <span>Student portal</span>
            </div>
        </div>
        <nav class="sidebar-nav" aria-label="Main navigation">
            <a class="<?php echo $activePage === "dashboard" ? "active" : ""; ?>" href="dashboard.php">Dashboard</a>
            <a class="<?php echo $activePage === "students" ? "active" : ""; ?>" href="index.php">Students</a>
            <a class="<?php echo $activePage === "add-student" ? "active" : ""; ?>" href="add_student.php">Add student</a>
        </nav>
        <a class="logout-link" href="logout.php">Log out</a>
    </aside>
    <?php
}
