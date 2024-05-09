<?php
session_start();

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to your login page
    exit; // Stop further execution
}
include_once 'include/connection.php';
include_once 'include/admin-main.php';

// Check if stitcher and challan number are set
if (isset($_GET['stitcher']) && isset($_GET['challan'])) {
    // Get the selected stitcher and challan number
    $selectedStitcher = mysqli_real_escape_string($con, $_GET['stitcher']);
    $selectedChallan = mysqli_real_escape_string($con, $_GET['challan']);

    // Initialize arrays to store unique product combinations
    $uniqueProducts = array();

    // Fetch distinct product names, product bases, and product colors based on selected stitcher, challan number, and status = 0
    $query = "SELECT DISTINCT product_name, product_base, product_color 
              FROM kits_job_work 
              WHERE stitcher_name = '$selectedStitcher' 
              AND challan_no_issue = '$selectedChallan' 
              AND status = 0";

    $result = mysqli_query($con, $query);

    // Fetch data and store unique product combinations
    while ($row = mysqli_fetch_assoc($result)) {
        // Construct a unique key for the product combination
        $productKey = $row['product_name'] . '_' || $row['product_base'] . '_' || $row['product_color'];

        // Check if the product combination already exists
        if (!isset($uniqueProducts[$productKey])) {
            // If not, add it to the array
            $uniqueProducts[$productKey] = array(
                'product_name' => $row['product_name'],
                'product_base' => $row['product_base'],
                'product_color' => $row['product_color']
            );
        }
    }

    // Prepare arrays for dropdown options
    $productNames = array();
    $productBases = array();
    $productColors = array();

    // Extract product names, product bases, and product colors from the unique product combinations
    foreach ($uniqueProducts as $product) {
        $productNames[] = $product['product_name'];
        $productBases[] = $product['product_base'];
        $productColors[] = $product['product_color'];
    }

    // Filter out duplicate product names and bases
    $uniqueProductNames = array_unique($productNames);
    $uniqueProductBases = array_unique($productBases);
    $uniqueProductColors = array_unique($productColors);

    // Prepare JSON response
    $response = array(
        'productNames' => $uniqueProductNames,
        'productBases' => $uniqueProductBases,
        'productColors' => $uniqueProductColors // Show only unique colors
    );

    // Send JSON response
    echo json_encode($response);
} else {
    // If stitcher or challan number is not set, return empty response
    echo json_encode(array());
}
?>
