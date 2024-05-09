<?php
// Include your database connection file
include_once 'include/connection.php';

// Check if product name is provided via GET request
if (isset($_GET['product_name'])) {
    $product_name = $_GET['product_name'];

    // Fetch product colors based on the selected product name
    $query = "SELECT small_sheet_color FROM sheets_small_stock WHERE product_name = '$product_name'";
    $result = mysqli_query($con, $query);

    // Array to store fetched colors
    $colors = array();

    // Fetch colors and add them to the array
    while ($row = mysqli_fetch_assoc($result)) {
        $colors[] = $row['small_sheet_color'];
    }

    // Send JSON response with the fetched colors
    echo json_encode($colors);
} else {
    // If product name is not provided, return an empty array
    echo json_encode(array());
}
?>
