<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');

$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

$query = "SELECT * FROM contact WHERE product = 'Tennis Ball'";

if (!empty($from_date) && !empty($to_date)) {
	$query .= " AND sub_time BETWEEN '$from_date' AND '$to_date'";
}

$result = mysqli_query($con, $query);

// Include your HTML structure for printing here

echo '<h3>Tennis Ball Contact Query</h3>';
echo '<table border="1" cellpadding="10" cellspacing="0">
		<tr>
			<th>Sn.</th>
			<th>Product Code</th>
			<th>Name</th>
			<th>Mobile</th>
			<th>Email</th> 
			<th>City</th>
			<th>State</th>
		</tr>';

$sn = 1;
while($data = mysqli_fetch_array($result)) {
    echo "<tr>
            <td>{$sn}.</td>
            <td>{$data['pcode']}</td>
            <td>{$data['name']}</td>
            <td>{$data['mobile']}</td>
            <td>{$data['email']}</td>
            <td>{$data['city']}</td>
            <td>{$data['state']}</td>
          </tr>";
    $sn++;
}

echo '</table>';

// Include print script to trigger the browser print dialog
echo '<script type="text/javascript">
        window.print();
      </script>';

?>
