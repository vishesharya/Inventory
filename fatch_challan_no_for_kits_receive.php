<?php
// Include your database connection file
include_once 'include/connection.php';

// Check if the necessary parameter (labour_name) is set
if (isset($_GET['labour_name'])) {
    $selectedLabour = mysqli_real_escape_string($con, $_GET['labour_name']);

    // Check if the date range is provided
    if (isset($_GET['from_date'], $_GET['to_date'])) {
        $fromDate = mysqli_real_escape_string($con, $_GET['from_date']);
        $toDate = mysqli_real_escape_string($con, $_GET['to_date']);
        // Fetch the distinct challan numbers based on the selected labour and date range
        $query = "SELECT DISTINCT challan_no FROM kits_received WHERE labour_name = '$selectedLabour' AND date_and_time BETWEEN '$fromDate' AND '$toDate'";
    } else {
        // Fetch the distinct challan numbers based on the selected labour without considering date range
        $query = "SELECT DISTINCT challan_no FROM kits_received WHERE labour_name = '$selectedLabour'";
    }

    $result = mysqli_query($con, $query);

    if ($result) {
        $challanNumbers = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $challanNumbers[] = $row['challan_no'];
        }
        // Output the array of challan numbers as JSON
        echo json_encode($challanNumbers);
    } else {
        // Handle database query error
        echo "Error: " . mysqli_error($con);
    }
} else {
    // Handle missing parameter
    echo "Missing parameter: labour_name";
}
?>
