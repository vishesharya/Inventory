<?php
// Include your database connection file
include_once 'include/connection.php';

// Retrieve parameters from the AJAX request
$selectedLabour = $_GET['labour'];
$fromDate = $_GET['from_date'];
$toDate = $_GET['to_date'];

// Prepare and execute the SQL query to fetch challan numbers
$query = "SELECT DISTINCT challan_no FROM kits_received WHERE labour_name = ? AND date_and_time BETWEEN ? AND ?";
$stmt = $con->prepare($query);
$stmt->bind_param("sss", $selectedLabour, $fromDate, $toDate);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the challan numbers and store them in an array
$challanNumbers = array();
while ($row = $result->fetch_assoc()) {
    $challanNumbers[] = $row['challan_no'];
}

// Send the JSON response
echo json_encode($challanNumbers);

// Close the database connection
$stmt->close();
$con->close();
?>
