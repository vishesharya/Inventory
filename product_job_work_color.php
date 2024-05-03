<?php
include_once 'include/connection.php';

if (isset($_GET['product_name']) && isset($_GET['product_base'])) {
    $product_name = $_GET['product_name'];
    $product_base = $_GET['product_base'];

    $product_color_query = "SELECT DISTINCT kits_job_work FROM kits_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND status = '0'";
    $product_color_result = mysqli_query($con, $product_color_query);

    $colors = array();
    while ($row = mysqli_fetch_assoc($product_color_result)) {
        $colors[] = $row['product_color'];
    }

    echo json_encode($colors);
}
?>
