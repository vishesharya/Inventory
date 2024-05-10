<?php
session_start();
include './include/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the necessary POST data is set
    if (isset($_POST['id']) && isset($_POST['field']) && isset($_POST['value'])) {
        $id = $_POST['id'];
        $field = $_POST['field'];
        $value = $_POST['value'];

        // Sanitize input to prevent SQL injection
        $id = mysqli_real_escape_string($con, $id);
        $field = mysqli_real_escape_string($con, $field);
        $value = mysqli_real_escape_string($con, $value);

        // Update database for kits_product table
        $query_kits_product = "UPDATE kits_product SET $field = '$value' WHERE id = $id";
        $result_kits_product = mysqli_query($con, $query_kits_product);

        // Update database for products table
        $query_products = "UPDATE products SET $field = '$value' WHERE id = $id";
        $result_products = mysqli_query($con, $query_products);
        
        // Update database for sheets_product table
        $query_sheets_product = "UPDATE sheets_product SET $field = '$value' WHERE id = $id";
        $result_sheets_product = mysqli_query($con, $query_sheets_product);

        if ($result_kits_product && $result_products && $result_sheets_product) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }
    } else {
        echo "Missing POST data";
    }
} else {
    echo "Invalid request";
}
?>
