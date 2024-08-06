<?php
session_start();
include './include/connection.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch the user ID from the database
if (isset($_SESSION['username'])) { // Assuming username is stored in session after login
    $username = $_SESSION['username'];

    // Prepare and execute the query to fetch the user ID
    $stmt = $con->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    // Store the user ID in the session
    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
    } else {
        // Handle the case where the user ID is not found
        header('Location: login.php');
        exit;
    }
}

// Now, you can use the user ID for further processing or verification
$user_id = $_SESSION['user_id'];

// Additional code to verify the user or provide access can go here

?>
