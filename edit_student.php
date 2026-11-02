<?php
include 'conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Select student using PDO
    $select_query = "SELECT * FROM students WHERE id = :id";
    $stmt = $conn->prepare($select_query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}

// Update student
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];

    $update_query = "UPDATE students 
                     SET name = :name, age = :age, gender = :gender, course = :course 
                     WHERE id = :id";

    $stmt = $conn->prepare($update_query);

    try {
        $stmt->execute([
            ':name' => $name,
            ':age' => $age,
            ':gender' => $gender,
            ':course' => $course,
            ':id' => $id
        ]);

        header("Location: index.php");
        exit;

    } catch (PDOException $e) {
        echo "Error updating record: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>Edit Student</h2>

    <form action="" method="POST">

        <label for="name">Student Name:</label><br>
        <input type="text" id="name" name="name" required
            value="<?php echo htmlspecialchars($student['name']); ?>">
        <br><br>

        <label for="age">Age:</label><br>
        <input type="number" id="age" name="age" min="1" required
            value="<?php echo htmlspecialchars($student['age']); ?>">
        <br><br>

        <label for="gender">Gender:</label><br>
        <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male" <?php echo ($student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo ($student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
        </select>
        <br><br>

        <label for="course">Course:</label><br>
        <input type="text" id="course" name="course" required
            value="<?php echo htmlspecialchars($student['course']); ?>">
        <br><br>

        <button type="submit">Edit Student</button>

    </form>
</body>

</html>