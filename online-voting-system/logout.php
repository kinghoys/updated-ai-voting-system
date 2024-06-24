<?php
session_start();

if (isset($_GET['message']) && $_GET['message'] == 'thankyou') {
    $message = "Thank you for voting!";
    $slogan = "Your vote is your voice. Protect it.";
} else {
    $message = "";
    $slogan = "";
}

session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logout - Online Voting System</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        setTimeout(function() {
            window.location.href = "index.php";
        }, 5000);
    </script>
</head>
<body>
    <header class="header">
        <h1 id="logout-message"><?php echo $message; ?></h1>
    </header>
    <main class="main-container">
        <p id="logout-slogan"><?php echo $slogan; ?></p>
        <button onclick="window.location.href='index.php'" class="btn btn-primary">Return to Home</button>
    </main>
    <footer>
        <p>&copy; 2024 Election Commission of India</p>
    </footer>
</body>
</html>
