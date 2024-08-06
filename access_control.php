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
    // If the user does not have access, display a styled error message
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Access Denied</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
            }
            .error-message {
                text-align: center;
                padding: 20px;
                border-radius: 8px;
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                max-width: 600px;
                width: 90%;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>
    <body>
        <div class="error-message">
            <h1>Access Denied</h1>
            <p>You don't have permission to access this page.</p>
        </div>
    </body>
    </html>';
    exit();
}

?>
