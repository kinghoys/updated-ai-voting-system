<?php
require 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitizeInput($_POST['name']);
    $party = sanitizeInput($_POST['party']);
    $profile_image = $_FILES['profile_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($profile_image);

    // Validate inputs
    if (empty($name) || empty($party) || empty($profile_image)) {
        $errors[] = "All fields are required.";
    }

    if (count($errors) == 0) {
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO candidates (name, party, profile_image) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $party, $target_file);

            if ($stmt->execute()) {
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
        } else {
            $errors[] = "Failed to upload image.";
        }
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
    <title>Add Candidate - Online Voting System</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header class="header">
        <h1>Add Candidate</h1>
    </header>
    <main class="main-container">
        <?php if (count($errors) > 0): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form action="add_candidate.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="party">Party:</label>
                <input type="text" id="party" name="party" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="profile_image">Profile Image:</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control-file" required>
            </div>
            <button type="submit" class="btn btn-success">Add Candidate</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Election Commission of India</p>
    </footer>
</body>
</html>
