<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Get student ID from students table by user_id
$student_res = $conn->query("SELECT id FROM students WHERE user_id='$user_id'");
if ($student_res->num_rows === 0) {
    echo "Student record not found.";
    exit();
}
$student = $student_res->fetch_assoc();
$student_id = $student['id'];

$sql = "SELECT c.name, c.code, c.credit, r.marks_obtained, r.grade_point FROM results r 
        JOIN courses c ON r.course_id = c.id
        WHERE r.student_id = '$student_id'";

$results = $conn->query($sql);

$total_points = 0;
$total_credits = 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
    <h2 class="text-2xl font-bold mb-6 text-center">My Result Sheet</h2>

    <table class="w-full max-w-3xl mx-auto border-collapse border border-gray-300 bg-white">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="border border-gray-300 px-4 py-2">Course</th>
                <th class="border border-gray-300 px-4 py-2">Code</th>
                <th class="border border-gray-300 px-4 py-2">Credit</th>
                <th class="border border-gray-300 px-4 py-2">Marks</th>
                <th class="border border-gray-300 px-4 py-2">Grade Point</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $results->fetch_assoc()) {
                $total_points += $row['credit'] * $row['grade_point'];
                $total_credits += $row['credit'];
            ?>
                <tr>
                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['code']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $row['credit']; ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $row['marks_obtained']; ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $row['grade_point']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php
    $gpa = $total_credits > 0 ? round($total_points / $total_credits, 2) : 0;
    ?>
    <p class="max-w-3xl mx-auto mt-6 font-bold text-lg">Total GPA: <?php echo $gpa; ?></p>
    <p class="max-w-3xl mx-auto mt-2"><a href="logout.php" class="text-red-600 underline">Logout</a></p>
</body>

</html>