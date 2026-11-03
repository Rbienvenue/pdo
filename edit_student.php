<?php
require "auth.php";
requireLogin();
require "conn.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header("Location: index.php");
    exit;
}

$select = $conn->prepare("SELECT * FROM students WHERE id = :id");
$select->execute([":id" => $id]);
$student = $select->fetch(PDO::FETCH_ASSOC);
if (!$student) {
    header("Location: index.php");
    exit;
}

$message = "";
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
        $existingEmail = $conn->prepare("SELECT id FROM students WHERE email = :email AND id <> :id LIMIT 1");
        $existingEmail->execute([":email" => $email, ":id" => $id]);
        if ($existingEmail->fetch()) {
            $message = "Another student already uses that email address.";
        } else {
            $update = $conn->prepare(
                "UPDATE students SET name = :name, age = :age, gender = :gender, course = :course, email = :email, telephone = :telephone WHERE id = :id"
            );
            $update->execute([
                ":name" => $name,
                ":age" => $age,
                ":gender" => $gender,
                ":course" => $course,
                ":email" => $email,
                ":telephone" => $telephone,
                ":id" => $id
            ]);
            if ((int) $_SESSION["student_id"] === $id) {
                $_SESSION["student_name"] = $name;
            }
            header("Location: index.php");
            exit;
        }
    }

    $student["name"] = $name;
    $student["age"] = $ageValue;
    $student["gender"] = $gender;
    $student["course"] = $course;
    $student["email"] = $email;
    $student["telephone"] = $telephone;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit student | CampusHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="app-page">
    <?php renderSidebar("students"); ?>
    <main class="main-content">
        <header class="page-header">
            <p class="eyebrow">STUDENT RECORDS</p>
            <h1>Edit student</h1>
            <p class="muted">Update this student's information.</p>
        </header>
        <section class="panel form-panel">
            <?php if ($message !== "") { ?>
                <p class="notice error"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
            <?php } ?>
            <form method="post" class="form-card">
                <label for="name">Student name</label>
                <input type="text" id="name" name="name" maxlength="100" required value="<?php echo htmlspecialchars($student["name"] ?? "", ENT_QUOTES, "UTF-8"); ?>">
                <div class="form-row">
                    <div>
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" min="1" max="120" required value="<?php echo htmlspecialchars((string) ($student["age"] ?? ""), ENT_QUOTES, "UTF-8"); ?>">
                    </div>
                    <div>
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select gender</option>
                            <option value="Male" <?php echo ($student["gender"] ?? "") === "Male" ? "selected" : ""; ?>>Male</option>
                            <option value="Female" <?php echo ($student["gender"] ?? "") === "Female" ? "selected" : ""; ?>>Female</option>
                        </select>
                    </div>
                </div>
                <label for="course">Course</label>
                <input type="text" id="course" name="course" maxlength="100" required value="<?php echo htmlspecialchars($student["course"] ?? "", ENT_QUOTES, "UTF-8"); ?>">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" maxlength="254" required value="<?php echo htmlspecialchars($student["email"] ?? "", ENT_QUOTES, "UTF-8"); ?>">
                <label for="telephone">Telephone number</label>
                <input type="tel" id="telephone" name="telephone" maxlength="30" required value="<?php echo htmlspecialchars($student["telephone"] ?? "", ENT_QUOTES, "UTF-8"); ?>">
                <div class="button-row">
                    <a class="button button-secondary" href="index.php">Cancel</a>
                    <button type="submit" class="button">Save changes</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
