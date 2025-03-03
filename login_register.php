<?php
session_start();
require_once 'config.php';

// Registration
if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = 'Invalid email format';
        $_SESSION['active_form'] = 'register';
        header("Location: signup.php");
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['register_error'] = 'Password must be at least 8 characters long';
        $_SESSION['active_form'] = 'register';
        header("Location: signup.php");
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['register_error'] = 'Passwords do not match';
        $_SESSION['active_form'] = 'register';
        header("Location: signup.php");
        exit();
    }

    // Check if email already exists
    $conn->query("USE user_auth");
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered';
        $_SESSION['active_form'] = 'register';
        header("Location: signup.php");
        exit();
    }

    // Insert new user with created_at timestamp
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insert = $conn->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $insert->bind_param("sss", $name, $email, $hashedPassword);
    $insert->execute();

    $_SESSION['register_error'] = 'Registration successful. Please log in.';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}

// Login
if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['created_at'] = $user['created_at']; // Store timestamp in session

            header("Location: user_page.php");  // Redirect to user page
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>
