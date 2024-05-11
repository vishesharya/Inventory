<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Sanitize input to prevent SQL injection
        $id = mysqli_real_escape_string($con, $id);

        // Delete from kits_product table
        $query_kits_product = "DELETE FROM kits_product WHERE id = $id";
        $result_kits_product = mysqli_query($con, $query_kits_product);

        // Delete from products table
        $query_products = "DELETE FROM products WHERE id = $id";
        $result_products = mysqli_query($con, $query_products);

        // Delete from sheets_product table
        $query_sheets_product = "DELETE FROM sheets_product WHERE id = $id";
        $result_sheets_product = mysqli_query($con, $query_sheets_product);

        // Delete from sheets_production_product table
        $query_sheets_production_product = "DELETE FROM sheets_production_product WHERE id = $id";
        $result_sheets_production_product = mysqli_query($con, $query_sheets_production_product);

        if ($result_kits_product && $result_products && $result_sheets_product && $result_sheets_production_product) {
            echo "Row deleted successfully.";
        } else {
            echo "Error deleting row: " . mysqli_error($con);
        }
    }
}
?>
