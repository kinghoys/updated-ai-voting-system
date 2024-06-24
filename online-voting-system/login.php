<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aadhar_number = sanitizeInput($_POST['aadhar_number']);
    $password = sanitizeInput($_POST['password']);

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE aadhar_number = ?");
    $stmt->bind_param("s", $aadhar_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: vote.php");
        }
        exit();
    } else {
        echo "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Online Voting System</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header class="header">
        <h1>Login</h1>
    </header>
    <main class="main-container">
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="aadhar_number">Aadhar Number:</label>
                <input type="text" id="aadhar_number" name="aadhar_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Login</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Election Commission of India</p>
    </footer>
</body>
</html>
