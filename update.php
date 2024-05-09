<?php
// Include your database connection file
include_once 'include/connection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required parameters are set
    if (isset($_POST['id']) && isset($_POST['field']) && isset($_POST['value'])) {
        // Sanitize input to prevent SQL injection
        $id = mysqli_real_escape_string($con, $_POST['id']);
        $field = mysqli_real_escape_string($con, $_POST['field']);
        $value = mysqli_real_escape_string($con, $_POST['value']);

        // Update database
        $query = "UPDATE kits_product SET $field = '$value' WHERE id = $id";
        $result = mysqli_query($con, $query);

        // Update database
        $query = "UPDATE products SET $field = '$value' WHERE id = $id";
        $result = mysqli_query($con, $query);

        // Update database
        $query = "UPDATE sheets_product SET $field = '$value' WHERE id = $id";
        $result = mysqli_query($con, $query);

        // Check if update was successful
        if (!$result) {
            echo "Error updating record: " . mysqli_error($con);
        } else {
            echo "Record updated successfully";
        }
    } else {
        echo "Missing parameters";
    }
} else {
    echo "Invalid request method";
}
?>
