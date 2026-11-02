<?php
include 'conn.php';
if (isset($_GET['id'])) {
    $select_query = "SELECT * FROM students WHERE id = " . $_GET['id'];
    $result = mysqli_query($conn, $select_query);
    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
    } else {
        header("Location: index.php");
        exit;
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];

    $update_query = "UPDATE students SET name='$name', age='$age', gender='$gender', course='$course' WHERE id=" . $_GET['id'];
    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
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
        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($student['name']); ?>">
        <br><br>

        <label for="age">Age:</label><br>
        <input type="number" id="age" name="age" min="1" required value="<?php echo htmlspecialchars($student['age']); ?>" >
        <br><br>

        <label for="gender">Gender:</label><br>
        <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <br><br>

        <label for="course">Course:</label><br>
        <input type="text" id="course" name="course" required value="<?php echo htmlspecialchars($student['course']); ?>">
        <br><br>

        <button type="submit">Edit Student</button>

    </form>
</body>

</html>