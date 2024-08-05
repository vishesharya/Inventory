<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

if (isset($_GET['product_name']) && isset($_GET['product_base'])) {
    $product_name = $_GET['product_name'];
    $product_base = $_GET['product_base'];

    $product_color_query = "SELECT DISTINCT product_color FROM football_received WHERE product_name = '$product_name' AND product_base = '$product_base'";
    $product_color_result = mysqli_query($con, $product_color_query);

    $colors = array();
    while ($row = mysqli_fetch_assoc($product_color_result)) {
        $colors[] = $row['product_color'];
    }

    echo json_encode($colors);
}
?>
