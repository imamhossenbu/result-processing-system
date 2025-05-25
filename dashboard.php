<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$students = $conn->query("SELECT s.id, u.name, s.student_id, s.department, s.semester
                          FROM students s
                          JOIN users u ON s.user_id = u.id");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Teacher Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-4">Welcome, <?php echo htmlspecialchars($user['name']); ?> (Teacher)</h1>

    <div class="mb-6">
        <a href="add_marks.php" class="bg-green-600 text-white px-4 py-2 rounded mr-3">Add Marks</a>
        <a href="add_student.php" class="bg-blue-600 text-white px-4 py-2 rounded mr-3">Add Student</a>
        <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded">Logout</a>
    </div>

    <h2 class="text-xl font-semibold mb-4">Student List</h2>
    <table class="min-w-full bg-white border border-gray-300 shadow-md">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Student ID</th>
                <th class="px-4 py-2 text-left">Department</th>
                <th class="px-4 py-2 text-left">Semester</th>
                <th class="px-4 py-2 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $students->fetch_assoc()): ?>
                <tr class="border-t">
                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['student_id']); ?></td>
                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['department']); ?></td>
                    <td class="px-4 py-2"><?php echo htmlspecialchars($row['semester']); ?></td>
                    <td class="px-4 py-2 text-center space-x-2">
                        <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Are you sure to delete?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>