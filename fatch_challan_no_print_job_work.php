<?php
// Include your database connection file
include_once 'include/connection.php';

// Get parameters from the request
$labour = $_GET['labour'];
$fromDate = $_GET['from_date'];
$toDate = $_GET['to_date'];

// Execute query to fetch challan numbers
// Assuming you already have a valid database connection
// Replace the placeholders with actual column and table names
$query = "SELECT challan_number FROM print_job_work WHERE labour = ? AND date BETWEEN ? AND ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("sss", $labour, $fromDate, $toDate);
$stmt->execute();
$result = $stmt->get_result();

// Fetch data into an array
$challanNumbers = array();
while ($row = $result->fetch_assoc()) {
    $challanNumbers[] = $row['challan_number'];
}

// Close statement
$stmt->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($challanNumbers);
?>
