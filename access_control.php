<?php 

include './include/connection.php';
include_once 'include/admin-main.php';


// Get the current file name
$current_file = basename($_SERVER['PHP_SELF']);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User is not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Query the database to check if the user has access to this file
$query = "SELECT has_access FROM permissions WHERE user_id = ? AND file = ?";
$stmt = $con->prepare($query);

if ($stmt === false) {
    // Handle error - mysqli_prepare failed
    die('Prepare() failed: ' . htmlspecialchars($con->error));
}

$stmt->bind_param("is", $user_id, $current_file);
$stmt->execute();
$stmt->bind_result($has_access);
$stmt->fetch();
$stmt->close();

if ($has_access != 1) {
    // If the user does not have access, redirect or display an error message
    echo "You don't have permission to access this page.";
    exit();
}

?>