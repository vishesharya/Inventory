<?php
session_start();
include_once 'include/connection.php';

// Check if stitcher is set
if (isset($_GET['stitcher'])) {
    // Get the selected stitcher
    $selectedStitcher = mysqli_real_escape_string($con, $_GET['stitcher']);
    
    // Query to fetch associated unique challan numbers for the selected stitcher
    $query = "SELECT DISTINCT challan_no_issue FROM kits_job_work WHERE stitcher_name = '$selectedStitcher' AND status = 0";
    $result = mysqli_query($con, $query);

    // Fetch the unique challan numbers and store them in an array
    $challanNumbers = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $challanNumbers[] = $row['challan_no_issue'];
    }

    // Send the JSON encoded array of unique challan numbers
    echo json_encode($challanNumbers);
}
?>
