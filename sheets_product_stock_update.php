<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['field']) && isset($_POST['value'])) {
        $id = $_POST['id'];
        $field = $_POST['field'];
        $value = $_POST['value'];

        // Sanitize input to prevent SQL injection
        $id = mysqli_real_escape_string($con, $id);
        $field = mysqli_real_escape_string($con, $field);
        $value = mysqli_real_escape_string($con, $value);

        // Update database
        $query = "UPDATE sheets_product SET $field = '$value' WHERE id = $id";
        $result = mysqli_query($con, $query);

        if (!$result) {
            echo "Error updating record: " . mysqli_error($con);
        } else {
            echo "Record updated successfully";
        }
    } else {
        echo "Invalid request";
    }
} else {
    echo "Invalid request method";
}
?>
