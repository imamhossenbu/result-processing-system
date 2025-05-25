<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $student_id = $_POST['student_id'];
    $department = $_POST['department'];
    $semester = $_POST['semester'];

    $sql = "INSERT INTO students (user_id, student_id, department, semester)
            VALUES ('$user_id', '$student_id', '$department', '$semester')";

    if ($conn->query($sql)) {
        $message = "<p class='text-green-600 mb-4'>✅ Student added successfully!</p>";
    } else {
        $message = "<p class='text-red-600 mb-4'>❌ Error: " . $conn->error . "</p>";
    }
}

// Get students not yet added to the `students` table
$users = $conn->query("SELECT * FROM users WHERE role='student' AND id NOT IN (SELECT user_id FROM students)");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">➕ Add Student</h2>
        <?php echo $message; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Select Student User:</label>
                <select name="user_id" class="w-full p-2 border rounded" required>
                    <?php while ($row = $users->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['name']) . " ({$row['email']})"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <input type="text" name="student_id" placeholder="Student ID (e.g. U20231234)" class="w-full p-2 border rounded" required>
            <input type="text" name="department" placeholder="Department (e.g. CSE)" class="w-full p-2 border rounded" required>
            <input type="text" name="semester" placeholder="Semester (e.g. Spring 2025)" class="w-full p-2 border rounded" required>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Add Student</button>
        </form>

        <div class="mt-6">
            <a href="dashboard.php" class="text-blue-700 underline">⬅ Back to Dashboard</a>
        </div>
    </div>
</body>

</html>