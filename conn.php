<?php
$conn = new PDO("mysql:host=localhost", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$conn->exec("CREATE DATABASE IF NOT EXISTS school");
$conn->exec("USE school");
$conn->exec(
    "CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        age INT,
        gender VARCHAR(10),
        course VARCHAR(100),
        email VARCHAR(254) NULL,
        telephone VARCHAR(30) NULL,
        regno VARCHAR(32) NULL,
        password_hash VARCHAR(255) NULL
    )"
);

$columns = $conn->query("SHOW COLUMNS FROM students")->fetchAll(PDO::FETCH_COLUMN);
if (!in_array("regno", $columns, true)) {
    $conn->exec("ALTER TABLE students ADD COLUMN regno VARCHAR(32) NULL");
}
if (!in_array("password_hash", $columns, true)) {
    $conn->exec("ALTER TABLE students ADD COLUMN password_hash VARCHAR(255) NULL");
}
if (!in_array("email", $columns, true)) {
    $conn->exec("ALTER TABLE students ADD COLUMN email VARCHAR(254) NULL");
}
if (!in_array("telephone", $columns, true)) {
    $conn->exec("ALTER TABLE students ADD COLUMN telephone VARCHAR(30) NULL");
}

$emailIndex = $conn->query("SHOW INDEX FROM students WHERE Key_name = 'students_email_unique'")->fetch();
if (!$emailIndex) {
    $conn->exec("CREATE UNIQUE INDEX students_email_unique ON students (email)");
}

$conn->exec("UPDATE students SET regno = CONCAT('STU-', LPAD(id, 5, '0')) WHERE regno IS NULL OR regno = ''");

$count = $conn->query("SELECT COUNT(*) FROM students")->fetchColumn();
if ($count == 0) {
    $conn->exec(
        "INSERT INTO students (name, age, gender, course) VALUES
        ('Jean Claude', 20, 'Male', 'IT'),
        ('Marie Claire', 19, 'Female', 'Software Development'),
        ('Patrick', 21, 'Male', 'Networking'),
        ('Alice', 20, 'Female', 'IT'),
        ('Eric', 22, 'Male', 'Database'),
        ('Diane', 19, 'Female', 'Software Development'),
        ('Emmanuel', 21, 'Male', 'Networking'),
        ('Grace', 20, 'Female', 'IT'),
        ('David', 23, 'Male', 'Database'),
        ('Beatrice', 18, 'Female', 'Software Development')"
    );
    $conn->exec("UPDATE students SET regno = CONCAT('STU-', LPAD(id, 5, '0')) WHERE regno IS NULL OR regno = ''");
}
