<?php
// Include database connection
include_once 'include/connection.php';

// Check if product_name is set in the request
if (isset($_GET['product_name'])) {
    $product_name = $_GET['product_name'];

    // Query to fetch small sheet colors based on the selected product name
    $query = "SELECT DISTINCT small_sheet_color FROM sheets_small_stock WHERE product_name = '$product_name' ORDER BY small_sheet_color ASC";
    $result = mysqli_query($con, $query);

    // Array to store small sheet colors
    $colors = array();

    // Fetch colors and add them to the array
    while ($row = mysqli_fetch_assoc($result)) {
        $colors[] = $row['small_sheet_color'];
    }

    // Close database connection
    mysqli_close($con);

    // Return small sheet colors as JSON response
    echo json_encode($colors);
} else {
    // If product_name is not set, return an empty array
    echo json_encode(array());
}
?>
