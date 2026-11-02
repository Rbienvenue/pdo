<?php
$conn = new PDO("mysql:host=localhost;dbname=school", "root", "");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $course = trim($_POST["course"]);

    if (empty($name) || empty($age) || empty($gender) || empty($course)) {
        $message = "Please fill in all fields.";
    } elseif (!filter_var($age, FILTER_VALIDATE_INT) || $age < 1) {
        $message = "Please enter a valid age.";
    } else {

        $sql = "INSERT INTO students (name, age, gender, course)
                VALUES (:name, :age, :gender, :course)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ":name" => $name,
            ":age" => $age,
            ":gender" => $gender,
            ":course" => $course
        ]);

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <!-- <link rel="stylesheet" href="style.css"> -->

    <style>
        /* General page styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 30px;
        }

        /* Page heading */
        h2 {
            text-align: center;
            color: #333;
        }

        /* Table styling */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }

        /* Table headings and cells */
        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        /* Table header */
        th {
            background-color: #052435;
            color: white;
        }

        /* Alternate row colors */
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Hover effect */
        tr:hover {
            background-color: #e6f2ff;
        }

        /* Student form */
        form {
            width: 80%;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px #ccc;
        }

        /* Form labels */
        label {
            font-weight: bold;
            color: #333;
        }

        /* Form inputs */
        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Submit button */
        button {
            background-color: #052435;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Button hover */
        button:hover {
            background-color: #052435ac;
        }

        /* Links */
        a {
            color: #052435;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>


</head>

<body>

    <h2>Add New Student</h2>

    <p>
        <a href="index.php">Return to Student List</a>
    </p>
    <form action="" method="POST">

        <label for="name">Student Name:</label><br>
        <input type="text" id="name" name="name" required>
        <br><br>

        <label for="age">Age:</label><br>
        <input type="number" id="age" name="age" min="1" required>
        <br><br>

        <label for="gender">Gender:</label><br>
        <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <br><br>

        <label for="course">Course:</label><br>
        <input type="text" id="course" name="course" required>
        <br><br>

        <button type="submit">Add Student</button>

    </form>

    <p style="color: red;">
        <?php echo htmlspecialchars($message); ?>
    </p>

</body>

</html>