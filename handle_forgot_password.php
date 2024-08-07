// handle_forgot_password.php
<?php
include './include/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $result = $con->query("SELECT * FROM users WHERE username = '$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(16));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $con->query("UPDATE users SET reset_token = '$token', reset_expiry = '$expiry' WHERE username = '$email'");

        $resetLink = "http://khannasports.co.in/reset_password.php?token=$token";

        // Send email with the reset link (pseudo code)
        // mail($email, "Password Reset", "Click this link to reset your password: $resetLink");

        $response['success'] = true;
        $response['message'] = 'Password reset link has been sent to your email.';
    } else {
        $response['success'] = false;
        $response['message'] = 'Email not found.';
    }
    
    echo json_encode($response);
    exit();
}
?>
