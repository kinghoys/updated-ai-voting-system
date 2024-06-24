<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$candidate_id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?");
$stmt->bind_param("i", $candidate_id);

if ($stmt->execute()) {
    header("Location: admin_dashboard.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>
