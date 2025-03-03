<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// If logged in, display the welcome message
$userName = $_SESSION['name'] ?? 'User ';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .welcome-box {
            text-align: center;
            margin-top: 50px;
        }
        .welcome-box h2 {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        .welcome-box p {
            font-size: 20px; 
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-box">
            <h2>Welcome, <?= htmlspecialchars($userName); ?>!</h2>
            <p>You have successfully logged in.</p>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>