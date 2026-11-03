<?php
require "auth.php";
requireLogin();
require "conn.php";

$stmt = $conn->query("SELECT * FROM students ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students | CampusHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="app-page">
    <?php renderSidebar("students"); ?>
    <main class="main-content">
        <header class="page-header page-header-inline">
            <div>
                <p class="eyebrow">DIRECTORY</p>
                <h1>Students</h1>
                <p class="muted">Browse and manage student records.</p>
            </div>
            <a class="button" href="add_student.php">Add student</a>
        </header>
        <section class="panel table-panel">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Course</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["regno"] ?? "", ENT_QUOTES, "UTF-8"); ?></td>
                                <td><strong><?php echo htmlspecialchars($row["name"] ?? "", ENT_QUOTES, "UTF-8"); ?></strong></td>
                                <td><?php echo htmlspecialchars((string) $row["age"], ENT_QUOTES, "UTF-8"); ?></td>
                                <td><?php echo htmlspecialchars($row["gender"] ?? "", ENT_QUOTES, "UTF-8"); ?></td>
                                <td><?php echo htmlspecialchars($row["course"] ?? "", ENT_QUOTES, "UTF-8"); ?></td>
                                <td><?php echo htmlspecialchars($row["email"] ?? "Not provided", ENT_QUOTES, "UTF-8"); ?></td>
                                <td><?php echo htmlspecialchars($row["telephone"] ?? "Not provided", ENT_QUOTES, "UTF-8"); ?></td>
                                <td class="table-actions">
                                    <a href="edit_student.php?id=<?php echo (int) $row["id"]; ?>">Edit</a>
                                    <a class="danger-link" href="delete_student.php?id=<?php echo (int) $row["id"]; ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
