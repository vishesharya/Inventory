// handle_forgot_password.php
<?php
include './include/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    if (empty($email)) {
        $response['success'] = false;
        $response['message'] = 'Email is required.';
        echo json_encode($response);
        exit();
    }

    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(16));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $con->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE username = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        if ($stmt->execute()) {
            $resetLink = "http://khannasports.cp.in/reset_password.php?token=$token";

            // Send email with the reset link (pseudo code)
            // mail($email, "Password Reset", "Click this link to reset your password: $resetLink");

            $response['success'] = true;
            $response['message'] = 'Password reset link has been sent to your email.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to update reset token.';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Email not found.';
    }
    
    echo json_encode($response);
    exit();
}
?>

}
?>
