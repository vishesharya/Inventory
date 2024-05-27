<?php
session_start();
include_once 'include/connection.php';

// Check if Challan No is set
if (isset($_GET['challan_no'])) {
    // Get the selected Challan No
    $selectedChallan = mysqli_real_escape_string($con, $_GET['challan_no']);
    
    // Query to fetch associated unique product names for the selected Challan No
    $query = "SELECT DISTINCT product_name FROM print_job_work WHERE challan_no_issue = '$selectedChallan' AND status = 0 ORDER BY product_name ASC";
    $result = mysqli_query($con, $query);

    // Fetch the unique product names and store them in an array
    $productNames = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $productNames[] = $row['product_name'];
    }

    // Send the JSON encoded array of unique product names
    echo json_encode($productNames);
}
?>
 