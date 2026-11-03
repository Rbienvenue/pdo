<?php
require "auth.php";
requireLogin();
require "conn.php";

if (isset($_GET["id"])) {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    if (!$id || $id < 1) {
        header("Location: index.php");
        exit;
    }

    try {
        $sql = "DELETE FROM students WHERE id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ":id" => $id
        ]);

        if ((int) $_SESSION["student_id"] === $id) {
            $_SESSION = [];
            session_destroy();
            header("Location: login.php");
            exit;
        }

        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        echo "Error deleting record: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, "UTF-8");
    }

} else {
    header("Location: index.php");
    exit;
}
?>