<?php
include 'conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql="DELETE FROM students WHERE id = $id";
    if(mysqli_query($conn, $sql)) {
        echo "Record deleted successfully.";
    }

    // Redirect back to the student list
    header("Location: index.php");
    exit;
} else {
    // If no ID is provided, redirect back to the student list
    header("Location: index.php");
    exit;
}