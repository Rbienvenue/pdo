<?php
require "conn.php";
require "auth.php";

if (!empty($_SESSION["student_id"])) {
    header("Location: dashboard.php");
    exit;
}

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
    $password = $_POST["password"] ?? "";
    $name = is_string($nameInput) ? trim($nameInput) : "";
    $ageValue = is_scalar($ageInput) ? (string) $ageInput : "";
    $age = filter_var($ageValue, FILTER_VALIDATE_INT);
    $genderInput = $_POST["gender"] ?? "";
    $course = is_string($courseInput) ? trim($courseInput) : "";
    $gender = is_string($genderInput) ? $genderInput : "";
    $email = is_string($emailInput) ? trim($emailInput) : "";
    $telephone = is_string($telephoneInput) ? trim($telephoneInput) : "";
    $password = is_string($password) ? $password : "";

    if ($name === "" || strlen($name) > 100 || !$age || $age < 1 || $age > 120 || !in_array($gender, ["Male", "Female"], true) || $course === "" || strlen($course) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254 || !isValidTelephone($telephone)) {
        $message = "Please enter valid details in every field.";
    } elseif (strlen($password) < 8) {
        $message = "Your password must be at least 8 characters long.";
    } else {
        $existingEmail = $conn->prepare("SELECT id FROM students WHERE email = :email LIMIT 1");
        $existingEmail->execute([":email" => $email]);
        if ($existingEmail->fetch()) {
            $message = "An account with that email address already exists.";
        } else {
            $existing = $conn->prepare("SELECT id FROM students WHERE name = :name AND password_hash IS NOT NULL LIMIT 1");
            $existing->execute([":name" => $name]);
            if ($existing->fetch()) {
                $message = "An account with that name already exists. Please sign in instead.";
            } else {
                $conn->beginTransaction();
                $insert = $conn->prepare(
                    "INSERT INTO students (name, age, gender, course, email, telephone, password_hash)
                     VALUES (:name, :age, :gender, :course, :email, :telephone, :password_hash)"
                );
                $insert->execute([
                    ":name" => $name,
                    ":age" => $age,
                    ":gender" => $gender,
                    ":course" => $course,
                    ":email" => $email,
                    ":telephone" => $telephone,
                    ":password_hash" => password_hash($password, PASSWORD_DEFAULT)
                ]);
                $studentId = (int) $conn->lastInsertId();
                $update = $conn->prepare("UPDATE students SET regno = :regno WHERE id = :id");
                $update->execute([
                    ":regno" => sprintf("STU-%05d", $studentId),
                    ":id" => $studentId
                ]);
                $conn->commit();

                session_regenerate_id(true);
                $_SESSION["student_id"] = $studentId;
                $_SESSION["student_name"] = $name;
                header("Location: dashboard.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create account | CampusHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <main class="auth-card register-card">
        <a class="brand auth-brand" href="register.php">Campus<span>Hub</span></a>
        <p class="eyebrow">STUDENT PORTAL</p>
        <h1>Create your account</h1>
        <p class="muted">Enter your details to get started.</p>
        <?php if ($message !== "") { ?>
            <p class="notice error"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
        <?php } ?>
        <form method="post" class="form-card">
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" maxlength="100" autocomplete="name" required value="<?php echo htmlspecialchars($name, ENT_QUOTES, "UTF-8"); ?>">
            <div class="form-row">
                <div>
                    <label for="age">Age</label>
                    <input id="age" name="age" type="number" min="1" max="120" required value="<?php echo htmlspecialchars($ageValue, ENT_QUOTES, "UTF-8"); ?>">
                </div>
                <div>
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select</option>
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
            <label for="password">Password <span class="label-hint">(at least 8 characters)</span></label>
            <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" required>
            <button type="submit" class="button full-width">Create account</button>
        </form>
        <p class="auth-switch">Already registered? <a href="login.php">Sign in</a></p>
    </main>
</body>
</html>
