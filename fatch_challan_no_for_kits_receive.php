<?php
include_once 'include/connection.php';

$labour = mysqli_real_escape_string($con, $_GET['labour']);
$from_date = mysqli_real_escape_string($con, $_GET['from_date']);
$to_date = mysqli_real_escape_string($con, $_GET['to_date']);

$query = "SELECT DISTINCT challan_no FROM kits_received WHERE labour_name = '$labour'";

if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND date_and_time BETWEEN '$from_date' AND '$to_date'";
}

$result = mysqli_query($con, $query);
$challan_numbers = [];

while ($row = mysqli_fetch_assoc($result)) {
    $challan_numbers[] = $row['challan_no'];
}

echo json_encode($challan_numbers);
?>
