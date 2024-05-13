<?php
// Include necessary files and start session if required
include_once 'include/connection.php'; // Include your database connection file

// Check if challan_no_issue is set and not empty
if(isset($_GET['challan_no_issue']) && !empty($_GET['challan_no_issue'])) {
    // Sanitize the input
    $challan_no_issue = mysqli_real_escape_string($con, $_GET['challan_no_issue']);

    // Query to fetch distinct product names for the selected challan_no_issue
    $product_query = "SELECT DISTINCT product_name FROM sheets_job_work WHERE challan_no_issue = '$challan_no_issue' AND status = 0";
    $product_result = mysqli_query($con, $product_query);

    // Prepare an array to store the fetched product names
    $product_names = array();

    // Fetch and store the fetched product names
    while($row = mysqli_fetch_assoc($product_result)) {
        $product_names[] = $row['product_name'];
    }

    // Convert the array to JSON and echo it
    echo json_encode($product_names);
}
?>
