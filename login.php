<?php
require "conn.php";
require "auth.php";

if (!empty($_SESSION["student_id"])) {
    header("Location: dashboard.php");
    exit;
}

$message = "";
$name = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nameInput = $_POST["name"] ?? "";
    $password = $_POST["password"] ?? "";
    $name = is_string($nameInput) ? trim($nameInput) : "";
    $password = is_string($password) ? $password : "";

    $stmt = $conn->prepare("SELECT id, name, password_hash FROM students WHERE name = :name AND password_hash IS NOT NULL");
    $stmt->execute([":name" => $name]);
    $student = null;
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $candidate) {
        if (password_verify($password, $candidate["password_hash"])) {
            $student = $candidate;
            break;
        }
    }

    if ($student) {
        session_regenerate_id(true);
        $_SESSION["student_id"] = $student["id"];
        $_SESSION["student_name"] = $student["name"];
        header("Location: dashboard.php");
        exit;
    }

    $message = "The name or password is incorrect.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in | CampusHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <main class="auth-card">
        <a class="brand auth-brand" href="login.php">Campus<span>Hub</span></a>
        <p class="eyebrow">STUDENT PORTAL</p>
        <h1>Welcome back</h1>
        <p class="muted">Sign in to view your student dashboard.</p>
        <?php if ($message !== "") { ?>
            <p class="notice error"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
        <?php } ?>
        <form method="post" class="form-card">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" maxlength="100" autocomplete="username" required value="<?php echo htmlspecialchars($name, ENT_QUOTES, "UTF-8"); ?>">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button type="submit" class="button full-width">Sign in</button>
        </form>
        <p class="auth-switch">New to CampusHub? <a href="register.php">Create an account</a></p>
    </main>
</body>
</html>
