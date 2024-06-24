<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$candidate_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM candidates WHERE id = ?");
$stmt->bind_param("i", $candidate_id);
$stmt->execute();
$result = $stmt->get_result();
$candidate = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitizeInput($_POST['name']);
    $party = sanitizeInput($_POST['party']);
    $profile_image = $_FILES['profile_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($profile_image);

    move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file);

    $stmt = $conn->prepare("UPDATE candidates SET name = ?, party = ?, profile_image = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $party, $target_file, $candidate_id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Candidate - Online Voting System</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header class="header">
        <h1>Edit Candidate</h1>
    </header>
    <main class="main-container">
        <form action="edit_candidate.php?id=<?php echo $candidate_id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo $candidate['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="party">Party:</label>
                <input type="text" id="party" name="party" class="form-control" value="<?php echo $candidate['party']; ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_image">Profile Image:</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control-file" required>
            </div>
            <button type="submit" class="btn btn-success">Update Candidate</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Election Commission of India</p>
    </footer>
</body>
</html>
