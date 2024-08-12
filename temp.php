<?php

include './include/connection.php';

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Define the username and password
$username = "khannasports";
$password = "KSI#101$";

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL query to insert the user data
$sql = "INSERT INTO users (username, password) VALUES (?, ?)";

// Prepare and bind the statement
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $username, $hashed_password);

// Execute the statement
if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

// Close the statement and connection
$stmt->close();
$con->close();
?>
