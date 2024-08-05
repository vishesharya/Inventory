<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

// Check if challan_no_issue, product_name, and product_base are set and not empty
if(isset($_GET['challan_no_issue']) && !empty($_GET['challan_no_issue']) && isset($_GET['product_name']) && !empty($_GET['product_name']) && isset($_GET['product_base']) && !empty($_GET['product_base'])) {
    // Sanitize the inputs
    $challan_no_issue = mysqli_real_escape_string($con, $_GET['challan_no_issue']);
    $product_name = mysqli_real_escape_string($con, $_GET['product_name']);
    $product_base = mysqli_real_escape_string($con, $_GET['product_base']);

    // Query to fetch distinct product colors for the selected challan_no_issue, product_name, and product_base
    $product_color_query = "SELECT DISTINCT product_color FROM sheets_job_work WHERE challan_no_issue = '$challan_no_issue' AND product_name = '$product_name' AND product_base = '$product_base' AND status = 0";
    $product_color_result = mysqli_query($con, $product_color_query);

    // Prepare an array to store the fetched product colors
    $product_colors = array();

    // Fetch and store the fetched product colors
    while($row = mysqli_fetch_assoc($product_color_result)) {
        $product_colors[] = $row['product_color'];
    }

    // Convert the array to JSON and echo it
    echo json_encode($product_colors);
}
?>
