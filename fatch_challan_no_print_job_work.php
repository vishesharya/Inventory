<?php
// Include your database connection file
include_once 'include/connection.php';

// Fetch parameters from the AJAX request
$selectedLabour = $_GET['labour'];
$fromDate = $_GET['from_date'];
$toDate = $_GET['to_date'];

// Sanitize inputs
$selectedLabour = mysqli_real_escape_string($con, $selectedLabour);
$fromDate = mysqli_real_escape_string($con, $fromDate);
$toDate = mysqli_real_escape_string($con, $toDate);

// Construct the query to fetch challan numbers based on labour and date range
$query = "SELECT DISTINCT challan_no_issue FROM print_job_work WHERE labour_name = '$selectedLabour' AND date_and_time >= '$fromDate' AND date_and_time <= '$toDate'";
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
