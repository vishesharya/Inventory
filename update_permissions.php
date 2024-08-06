<?php
include_once 'include/connection.php';
include './include/check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $permissions = isset($_POST['permissions']) ? $_POST['permissions'] : [];

    // Delete existing permissions for the user
    $stmt = $con->prepare("DELETE FROM permissions WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    // Insert new permissions
    $stmt = $con->prepare("INSERT INTO permissions (user_id, file, has_access) VALUES (?, ?, ?)");
    foreach ($permissions as $file => $value) {
        $has_access = 1; // Grant access
        $stmt->bind_param('isi', $user_id, $file, $has_access);
        $stmt->execute();
    }

    echo "Permissions updated.";
    $stmt->close();
    $con->close();
} else {
    echo "Invalid request method.";
}
?>
