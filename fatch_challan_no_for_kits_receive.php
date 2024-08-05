<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

$labour = mysqli_real_escape_string($con, $_GET['labour']);
$from_date = mysqli_real_escape_string($con, $_GET['from_date']);
$to_date = mysqli_real_escape_string($con, $_GET['to_date']);

// Construct the query to fetch challan numbers based on labour name and date range
$query = "SELECT DISTINCT challan_no FROM kits_received WHERE labour_name = '$labour'";

if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND date_and_time BETWEEN '$from_date' AND '$to_date'";
}

$result = mysqli_query($con, $query);

$challan_numbers = [];
while ($row = mysqli_fetch_assoc($result)) {
    $challan_numbers[] = $row['challan_no'];
}

// Return the challan numbers as JSON
echo json_encode($challan_numbers);
?>
