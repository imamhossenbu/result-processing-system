<?php
include 'dbconnect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION['user'] = $result->fetch_assoc();
        header("Location: index.php");
        exit();
    } else {
        echo "<p class='text-red-600 text-center'>Invalid email or password</p>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <form method="post" class="bg-white p-6 rounded shadow w-96">
        <h2 class="text-xl font-bold mb-4 text-center">Login</h2>
        <input type="email" name="email" placeholder="Email" class="mb-3 p-2 w-full border rounded" required>
        <input type="password" name="password" placeholder="Password" class="mb-3 p-2 w-full border rounded" required>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 w-full rounded">Login</button>
        <p class="text-center mt-3 text-sm">Don't have an account? <a href="register.php" class="text-green-500 underline">Register</a></p>
    </form>
</body>

</html>