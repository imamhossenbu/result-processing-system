<?php
include 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $role = $_POST['role']; // 'student' or 'teacher'

    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='text-green-600 text-center'>Registered successfully. <a href='login.php' class='underline'>Login here</a></p>";
    } else {
        echo "<p class='text-red-600 text-center'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <form method="post" class="bg-white p-6 rounded shadow w-96">
        <h2 class="text-xl font-bold mb-4 text-center">Register</h2>
        <input type="text" name="name" placeholder="Name" class="mb-3 p-2 w-full border rounded" required>
        <input type="email" name="email" placeholder="Email" class="mb-3 p-2 w-full border rounded" required>
        <input type="password" name="password" placeholder="Password" class="mb-3 p-2 w-full border rounded" required>
        <select name="role" class="mb-3 p-2 w-full border rounded" required>
            <option value="">Select Role</option>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 w-full rounded">Register</button>
        <p class="text-center mt-3 text-sm">Already have an account? <a href="login.php" class="text-blue-500 underline">Login</a></p>
    </form>
</body>

</html>