<?php
// Include necessary files and start session if required
include_once 'include/connection.php'; // Include your database connection file

// Check if challan_no_issue and product_name are set and not empty
if(isset($_GET['challan_no_issue']) && !empty($_GET['challan_no_issue']) && isset($_GET['product_name']) && !empty($_GET['product_name'])) {
    // Sanitize the inputs
    $challan_no_issue = mysqli_real_escape_string($con, $_GET['challan_no_issue']);
    $product_name = mysqli_real_escape_string($con, $_GET['product_name']);

    // Query to fetch distinct product bases for the selected challan_no_issue and product_name
    $product_base_query = "SELECT DISTINCT product_base FROM sheets_job_work WHERE challan_no_issue = '$challan_no_issue' AND product_name = '$product_name' AND status = 0";
    $product_base_result = mysqli_query($con, $product_base_query);

    // Prepare an array to store the fetched product bases
    $product_bases = array();

    // Fetch and store the fetched product bases
    while($row = mysqli_fetch_assoc($product_base_result)) {
        $product_bases[] = $row['product_base'];
    }

    // Convert the array to JSON and echo it
    echo json_encode($product_bases);
}
?>
