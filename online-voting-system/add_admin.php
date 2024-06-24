<?php
require 'config.php';

$admin_aadhar = '123456789012';
$admin_full_name = 'Admin User';
$admin_email = 'admin@example.com';
$admin_password = password_hash('adminpassword', PASSWORD_BCRYPT);
$admin_role = 'admin';

// Check if the admin user already exists
$stmt = $conn->prepare("SELECT * FROM users WHERE aadhar_number = ?");
$stmt->bind_param("s", $admin_aadhar);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Insert admin user
    $stmt = $conn->prepare("INSERT INTO users (aadhar_number, full_name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $admin_aadhar, $admin_full_name, $admin_email, $admin_password, $admin_role);

    if ($stmt->execute()) {
        echo "Admin user created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Admin user already exists.";
}
?>
