<?php
include_once 'include/connection.php';

if (isset($_GET['product_name1']) && isset($_GET['product_base1'])) {
    $product_name = $_GET['product_name1'];
    $product_base = $_GET['product_base1'];

    $product_color_query = "SELECT DISTINCT product_color1 FROM print_received WHERE product_name1 = '$product_name' AND product_base1 = '$product_base'";
    $product_color_result = mysqli_query($con, $product_color_query);

    $colors = array();
    while ($row = mysqli_fetch_assoc($product_color_result)) {
        $colors[] = $row['product_color1'];
    }

    echo json_encode($colors);
}
?>
