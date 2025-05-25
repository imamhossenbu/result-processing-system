<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

if ($user['role'] === 'student') {
    header("Location: student_result.php");
    exit();
} elseif ($user['role'] === 'teacher') {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Invalid user role.";
}
