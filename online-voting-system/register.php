<?php
require 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aadhar_number = sanitizeInput($_POST['aadhar_number']);
    $full_name = sanitizeInput($_POST['full_name']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $captured_image_data = $_POST['captured_image_data'];

    // Validate inputs
    if (empty($aadhar_number) || empty($full_name) || empty($email) || empty($password) || empty($captured_image_data)) {
        $errors[] = "All fields are required.";
    }

    if (count($errors) == 0) {
        $profile_image = "uploads/" . uniqid() . ".png";
        list($type, $captured_image_data) = explode(';', $captured_image_data);
        list(, $captured_image_data) = explode(',', $captured_image_data);
        $captured_image_data = base64_decode($captured_image_data);
        file_put_contents($profile_image, $captured_image_data);

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (aadhar_number, full_name, email, password_hash, profile_image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $aadhar_number, $full_name, $email, $password_hash, $profile_image);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Online Voting System</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="assets/js/capture.js"></script>
</head>
<body>
    <header class="header">
        <h1>Register</h1>
    </header>
    <main class="main-container">
        <?php if (count($errors) > 0): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="register.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="aadhar_number">Aadhar Number:</label>
                <input type="text" id="aadhar_number" name="aadhar_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div id="camera" class="mb-3"></div>
            <button type="button" class="btn btn-primary" onclick="captureImage()">Capture Image</button><br>
            <input type="hidden" id="captured_image_data" name="captured_image_data">
            <button type="submit" class="btn btn-success mt-3">Register</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Election Commission of India</p>
    </footer>
</body>
</html>
