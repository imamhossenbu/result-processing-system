<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Fetch students and courses for selection
$students = $conn->query("SELECT students.id, users.name FROM students JOIN users ON students.user_id = users.id");
$courses = $conn->query("SELECT id, name FROM courses");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $marks_obtained = $_POST['marks_obtained'];

    // Calculate grade point based on marks (simple example)
    if ($marks_obtained >= 80) $grade_point = 4.0;
    elseif ($marks_obtained >= 70) $grade_point = 3.5;
    elseif ($marks_obtained >= 60) $grade_point = 3.0;
    elseif ($marks_obtained >= 50) $grade_point = 2.5;
    elseif ($marks_obtained >= 40) $grade_point = 2.0;
    else $grade_point = 0;

    $sql_check = "SELECT * FROM results WHERE student_id='$student_id' AND course_id='$course_id'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Update existing marks
        $sql = "UPDATE results SET marks_obtained='$marks_obtained', grade_point='$grade_point' WHERE student_id='$student_id' AND course_id='$course_id'";
    } else {
        // Insert new marks
        $sql = "INSERT INTO results (student_id, course_id, marks_obtained, grade_point) VALUES ('$student_id', '$course_id', '$marks_obtained', '$grade_point')";
    }

    if ($conn->query($sql) === TRUE) {
        $message = "<p class='text-green-600'>Marks saved successfully.</p>";
    } else {
        $message = "<p class='text-red-600'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Marks</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
    <h2 class="text-xl font-bold mb-4">Add / Update Marks</h2>

    <?php echo $message; ?>

    <form method="post" class="bg-white p-6 rounded shadow max-w-md">
        <label class="block mb-2 font-semibold">Select Student:</label>
        <select name="student_id" class="mb-4 p-2 border rounded w-full" required>
            <option value="">-- Select Student --</option>
            <?php while ($row = $students->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
            <?php } ?>
        </select>

        <label class="block mb-2 font-semibold">Select Course:</label>
        <select name="course_id" class="mb-4 p-2 border rounded w-full" required>
            <option value="">-- Select Course --</option>
            <?php while ($row = $courses->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
            <?php } ?>
        </select>

        <label class="block mb-2 font-semibold">Marks Obtained:</label>
        <input type="number" name="marks_obtained" min="0" max="100" class="mb-4 p-2 border rounded w-full" required>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Save Marks</button>
    </form>

    <a href="dashboard.php" class="inline-block mt-6 text-blue-600 underline">Back to Dashboard</a>
</body>

</html>