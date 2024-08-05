<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

// Check if challan_no_issue, product_name, and product_base are set
if (isset($_GET['challan_no_issue']) && isset($_GET['product_name']) && isset($_GET['product_base'])) {
    // Get the selected challan_no_issue, product_name, and product_base
    $selectedChallan = mysqli_real_escape_string($con, $_GET['challan_no_issue']);
    $productName = mysqli_real_escape_string($con, $_GET['product_name']);
    $productBase = mysqli_real_escape_string($con, $_GET['product_base']);
    
    // Query to fetch associated unique product colors for the selected challan_no_issue, product_name, and product_base
    $query = "SELECT DISTINCT product_color FROM kits_job_work WHERE challan_no_issue = '$selectedChallan' AND product_name = '$productName' AND product_base = '$productBase' AND status = 0 ORDER BY product_color ASC";
    $result = mysqli_query($con, $query);

    // Fetch the unique product colors and store them in an array
    $productColors = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $productColors[] = $row['product_color'];
    }

    // Send the JSON encoded array of unique product colors
    echo json_encode($productColors);
}
?>
 