<?php
session_start();

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to your login page
    exit; // Stop further execution
}
include_once 'include/connection.php';
include_once 'include/admin-main.php';

if (isset($_GET['product_name']) && isset($_GET['product_base'])) {
    $product_name = $_GET['product_name'];
    $product_base = $_GET['product_base'];

    $product_color_query = "SELECT DISTINCT product_color FROM kits_job_work WHERE product_name = '$product_name' AND product_base = '$product_base'";
    $product_color_result = mysqli_query($con, $product_color_query);

    $colors = array();
    while ($row = mysqli_fetch_assoc($product_color_result)) {
        $colors[] = $row['product_color'];
    }

    echo json_encode($colors);
}
?>
