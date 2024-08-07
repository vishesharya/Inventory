// handle_reset_password.php
<?php
include './include/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $result = $con->query("SELECT * FROM users WHERE reset_token = '$token' AND reset_expiry > NOW()");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $con->query("UPDATE users SET password = '$newPassword', reset_token = NULL, reset_expiry = NULL WHERE reset_token = '$token'");
        $response['success'] = true;
        $response['message'] = 'Password has been reset successfully.';
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid or expired token.';
    }

    echo json_encode($response);
    exit();
}
?>
