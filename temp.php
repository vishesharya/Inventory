<?php
// Define the username and password
$username = "khannasports";
$password = "KSI#101$";

// Optionally, you can hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Display the username and hashed password
echo "Username: " . $username . "<br>";
echo "Password: " . $password . "<br>";
echo "Hashed Password: " . $hashed_password . "<br>";
?>
