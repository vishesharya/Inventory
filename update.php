<?php
// Include your database connection file
include_once 'include/connection.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required parameters are set
    if (isset($_POST['id']) && isset($_POST['field']) && isset($_POST['value'])) {
        // Sanitize input to prevent SQL injection (better: use prepared statements)
        $id = $_POST['id'];
        $field = $_POST['field'];
        $value = $_POST['value'];

        // Update database
        $tables = ['kits_product', 'products', 'sheets_product'];
        foreach ($tables as $table) {
            $query = "UPDATE $table SET $field = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "si", $value, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Check if update was successful
        if (mysqli_errno($con)) {
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
