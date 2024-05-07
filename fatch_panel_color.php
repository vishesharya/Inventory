<?php
// Include database connection
include_once 'include/connection.php';

// Check if product_name is set in the request
if (isset($_GET['product_name'])) {
    $product_name = $_GET['product_name'];

    // Query to fetch small sheet colors based on the selected product name
    $query = "SELECT DISTINCT small_sheet_color FROM sheets_small_stock WHERE product_name = ? ORDER BY small_sheet_color ASC";

    // Prepare the statement
    $stmt = mysqli_prepare($con, $query);

    // Bind the product name parameter
    mysqli_stmt_bind_param($stmt, "s", $product_name);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get result
    $result = mysqli_stmt_get_result($stmt);

    // Array to store small sheet colors
    $colors = array();

    // Fetch colors and add them to the array
    while ($row = mysqli_fetch_assoc($result)) {
        $colors[] = $row['small_sheet_color'];
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close database connection
    mysqli_close($con);

    // Return small sheet colors as JSON response
    echo json_encode($colors);
} else {
    // If product_name is not set, return an empty array
    echo json_encode(array());
}
?>
