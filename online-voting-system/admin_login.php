<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_username = sanitizeInput($_POST['username']);
    $correct_username = "admin"; // Set your admin username here

    if ($admin_username === $correct_username) {
        $_SESSION['admin_id'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Invalid username.";
    }
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Online Voting System</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header class="header">
        <h1>Admin Login</h1>
    </header>
    <main class="main-container">
        <form action="admin_login.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-danger">Admin Login</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Election Commission of India</p>
    </footer>
</body>
</html>
