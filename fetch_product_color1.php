<?php
include_once 'include/connection.php';

if (isset($_GET['product_name1']) && isset($_GET['product_base1'])) {
    $product_name = $_GET['product_name1'];
    $product_base = $_GET['product_base1'];

    $product_color_query = "SELECT DISTINCT product_color1 FROM print_received WHERE product_name1 = ? AND product_base1 = ?";
    $stmt = $con->prepare($product_color_query);
    $stmt->bind_param("ss", $product_name, $product_base);
    $stmt->execute();
    $product_color_result = $stmt->get_result();

    $colors = array();
    while ($row = $product_color_result->fetch_assoc()) {
        $colors[] = $row['product_color1'];
    }

    // Close statement
    $stmt->close();

    // Return the results as a JSON response
    header('Content-Type: application/json');
    echo json_encode($colors);
} else {
    // If either productName or productBase is not selected, return an empty response
    echo json_encode(array());
}
?>
