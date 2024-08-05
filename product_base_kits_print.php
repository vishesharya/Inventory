<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

// Check if product name and challan_no_issue are set
if (isset($_GET['product_name']) && isset($_GET['challan_no_issue'])) {
    // Get the selected product name and challan_no_issue
    $productName = mysqli_real_escape_string($con, $_GET['product_name']);
    $selectedChallan = mysqli_real_escape_string($con, $_GET['challan_no_issue']);
    
    // Query to fetch associated unique product bases for the selected product name and challan_no_issue
    $query = "SELECT DISTINCT product_base FROM print_job_work WHERE product_name = '$productName' AND challan_no_issue = '$selectedChallan' AND status = 0 ORDER BY product_base ASC";
    $result = mysqli_query($con, $query);

    // Fetch the unique product bases and store them in an array
    $productBases = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $productBases[] = $row['product_base'];
    }

    // Send the JSON encoded array of unique product bases
    echo json_encode($productBases);
}
?>
 