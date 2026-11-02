<?php
include 'conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM students WHERE id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        echo "Error deleting record: " . $e->getMessage();
    }

} else {
    header("Location: index.php");
    exit;
}
?>