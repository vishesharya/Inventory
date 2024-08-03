<?php
session_start();
include_once 'include/connection.php';

$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

$query = "SELECT * FROM contact WHERE product = 'Tennis Ball'";

if (!empty($from_date) && !empty($to_date)) {
	$query .= " AND sub_time BETWEEN '$from_date' AND '$to_date'";
}

$result = mysqli_query($con, $query);

// Include your HTML structure for printing here

while($data = mysqli_fetch_array($result)) {
    // Display the data for printing
    echo "<p>{$data['pcode']} - {$data['name']} - {$data['mobile']} - {$data['email']} - {$data['city']} - {$data['state']}</p>";
}

// Include print script to trigger the browser print dialog
echo '<script type="text/javascript">
        window.print();
      </script>';

?>
