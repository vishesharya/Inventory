<?php
// Include necessary files and start session if required
include_once 'include/connection.php'; // Include your database connection file

// Check if labour_name is set and not empty
if(isset($_GET['labour_name']) && !empty($_GET['labour_name'])) {
    // Sanitize the input
    $labour_name = mysqli_real_escape_string($con, $_GET['labour_name']);

    // Query to fetch distinct challan numbers for the selected labour
    $challan_query = "SELECT DISTINCT challan_no_issue FROM sheets_job_work WHERE labour_name = '$labour_name' AND status = 0";
    $challan_result = mysqli_query($con, $challan_query);

    // Prepare an array to store the fetched challan numbers
    $challan_numbers = array();

    // Fetch and store the fetched challan numbers
    while($row = mysqli_fetch_assoc($challan_result)) {
        $challan_numbers[] = $row['challan_no_issue'];
    }

    // Convert the array to JSON and echo it
    echo json_encode($challan_numbers);
}
?>
