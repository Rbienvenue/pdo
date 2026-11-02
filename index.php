<?php
$conn = new PDO("mysql:host=localhost", "root", "");

$conn->exec("CREATE DATABASE IF NOT EXISTS school");

$conn = new PDO("mysql:host=localhost;dbname=school", "root", "");

$sql = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    age INT,
    gender VARCHAR(10),
    course VARCHAR(100)
)";

$conn->exec($sql);

$sql = "INSERT INTO students (name, age, gender, course) VALUES
('Jean Claude', 20, 'Male', 'IT'),
('Marie Claire', 19, 'Female', 'Software Development'),
('Patrick', 21, 'Male', 'Networking'),
('Alice', 20, 'Female', 'IT'),
('Eric', 22, 'Male', 'Database'),
('Diane', 19, 'Female', 'Software Development'),
('Emmanuel', 21, 'Male', 'Networking'),
('Grace', 20, 'Female', 'IT'),
('David', 23, 'Male', 'Database'),
('Beatrice', 18, 'Female', 'Software Development')";

$count = $conn->query("SELECT COUNT(*) FROM students")->fetchColumn();

if ($count == 0) {
    $conn->exec($sql);
}

$sql = "SELECT * FROM students";
$stmt = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #052435;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e6f2ff;
        }

        form {
            width: 80%;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px #ccc;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #052435;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #052435ac;
        }

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


<p>
</p>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Course</th>
        </tr>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                <td><?php echo htmlspecialchars($row['course']); ?></td>
                <td><a href="edit_student.php"></a></td>
            </tr>
        <?php } ?>
    <a href="add_student.php">Add new student</a>
    </table>
    

</body>

</html>