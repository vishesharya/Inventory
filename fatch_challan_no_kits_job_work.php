<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

// Fetch parameters from the AJAX request
$selectedStitcher = $_GET['stitcher'];
$fromDate = $_GET['from_date'];
$toDate = $_GET['to_date'];

// Sanitize inputs
$selectedStitcher = mysqli_real_escape_string($con, $selectedStitcher);
$fromDate = mysqli_real_escape_string($con, $fromDate);
$toDate = mysqli_real_escape_string($con, $toDate);

// Construct the query to fetch challan numbers based on stitcher and date range
$query = "SELECT DISTINCT challan_no_issue FROM kits_job_work WHERE stitcher_name = '$selectedStitcher' AND date_and_time >= '$fromDate' AND date_and_time <= '$toDate'";
$result = mysqli_query($con, $query);

// Check if query was successful
if ($result) {
    $challanNumbers = array();
    // Fetch and store the results
    while ($row = mysqli_fetch_assoc($result)) {
        $challanNumbers[] = $row['challan_no_issue'];
    }
    // Return the JSON encoded array of challan numbers
    echo json_encode($challanNumbers);
} else {
    // If query fails, return an empty array
    echo json_encode(array());
}
?>
