<?php
// Include the database connection
include_once 'include/connection.php';

// Get the product name1 and base1 from the query parameters
$product_name1 = mysqli_real_escape_string($con, $_GET['product_name1']);
$product_base1 = mysqli_real_escape_string($con, $_GET['product_base1']);

// Query to fetch distinct product colors based on the product name1 and base1
$query = "SELECT DISTINCT product_color FROM sheets_product WHERE product_name = '$product_name1' AND product_base = '$product_base1' ORDER BY product_color ASC";
$result = mysqli_query($con, $query);

// Initialize an array to hold the colors
$colors = array();

// Fetch the colors and add them to the array
while ($row = mysqli_fetch_assoc($result)) {
    $colors[] = $row['product_color'];
}

// Return the colors as a JSON response
echo json_encode($colors);

// Close the database connection
mysqli_close($con);
?>
