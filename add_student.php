<?php
require "auth.php";
requireLogin();
require "conn.php";

$message = "";
$name = "";
$ageValue = "";
$gender = "";
$course = "";
$email = "";
$telephone = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nameInput = $_POST["name"] ?? "";
    $courseInput = $_POST["course"] ?? "";
    $emailInput = $_POST["email"] ?? "";
    $telephoneInput = $_POST["telephone"] ?? "";
    $ageInput = $_POST["age"] ?? "";
    $name = is_string($nameInput) ? trim($nameInput) : "";
    $ageValue = is_scalar($ageInput) ? (string) $ageInput : "";
    $age = filter_var($ageValue, FILTER_VALIDATE_INT);
    $genderInput = $_POST["gender"] ?? "";
    $course = is_string($courseInput) ? trim($courseInput) : "";
    $gender = is_string($genderInput) ? $genderInput : "";
    $email = is_string($emailInput) ? trim($emailInput) : "";
    $telephone = is_string($telephoneInput) ? trim($telephoneInput) : "";

    if ($name === "" || strlen($name) > 100 || !$age || $age < 1 || $age > 120 || !in_array($gender, ["Male", "Female"], true) || $course === "" || strlen($course) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254 || !isValidTelephone($telephone)) {
        $message = "Please enter valid details in every field.";
    } else {
        $existingEmail = $conn->prepare("SELECT id FROM students WHERE email = :email LIMIT 1");
        $existingEmail->execute([":email" => $email]);
        if ($existingEmail->fetch()) {
            $message = "A student with that email address already exists.";
        } else {
            $insert = $conn->prepare("INSERT INTO students (name, age, gender, course, email, telephone) VALUES (:name, :age, :gender, :course, :email, :telephone)");
            $insert->execute([
                ":name" => $name,
                ":age" => $age,
                ":gender" => $gender,
                ":course" => $course,
                ":email" => $email,
                ":telephone" => $telephone
            ]);
            $studentId = (int) $conn->lastInsertId();
            $update = $conn->prepare("UPDATE students SET regno = :regno WHERE id = :id");
            $update->execute([
                ":regno" => sprintf("STU-%05d", $studentId),
                ":id" => $studentId
            ]);

            header("Location: index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add student | CampusHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="app-page">
    <?php renderSidebar("add-student"); ?>
    <main class="main-content">
        <header class="page-header">
            <p class="eyebrow">STUDENT RECORDS</p>
            <h1>Add student</h1>
            <p class="muted">Enter the student's details below.</p>
        </header>
        <section class="panel form-panel">
            <?php if ($message !== "") { ?>
                <p class="notice error"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
            <?php } ?>
            <form method="post" class="form-card">
                <label for="name">Student name</label>
                <input id="name" name="name" type="text" maxlength="100" required value="<?php echo htmlspecialchars($name, ENT_QUOTES, "UTF-8"); ?>">
                <div class="form-row">
                    <div>
                        <label for="age">Age</label>
                        <input id="age" name="age" type="number" min="1" max="120" required value="<?php echo htmlspecialchars($ageValue, ENT_QUOTES, "UTF-8"); ?>">
                    </div>
                    <div>
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select gender</option>
                            <option value="Male" <?php echo $gender === "Male" ? "selected" : ""; ?>>Male</option>
                            <option value="Female" <?php echo $gender === "Female" ? "selected" : ""; ?>>Female</option>
                        </select>
                    </div>
                </div>
                <label for="course">Course</label>
                <input id="course" name="course" type="text" maxlength="100" required value="<?php echo htmlspecialchars($course, ENT_QUOTES, "UTF-8"); ?>">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" maxlength="254" autocomplete="email" required value="<?php echo htmlspecialchars($email, ENT_QUOTES, "UTF-8"); ?>">
                <label for="telephone">Telephone number</label>
                <input id="telephone" name="telephone" type="tel" maxlength="30" autocomplete="tel" required value="<?php echo htmlspecialchars($telephone, ENT_QUOTES, "UTF-8"); ?>">
                <div class="button-row">
                    <a class="button button-secondary" href="index.php">Cancel</a>
                    <button type="submit" class="button">Add student</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
