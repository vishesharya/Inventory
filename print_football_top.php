<?php
session_start();
include_once 'include/connection.php';

$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

$query = "SELECT * FROM contact WHERE product = 'Football'";

if (!empty($from_date) && !empty($to_date)) {
	$query .= " AND sub_time BETWEEN '$from_date' AND '$to_date'";
}

$result = mysqli_query($con, $query);

// Include your HTML structure for printing here

echo '<h3>Football Top 10 Contact Query</h3>';
echo '<table border="1" cellpadding="10" cellspacing="0">
		<tr>
			<th>Sn.</th>
		
			<th>Name</th>
			<th>Mobile</th>
			<th>Email</th> 
			<th>City</th>
			<th>State</th>
            <th>Product Name</th>
            <th>Model</th>
            <th>Code</th>
            <th>Date</th>
		</tr>';

$sn = 1;
while($data = mysqli_fetch_array($result)) {
    echo "<tr>
            <td>{$sn}.</td>
            
            <td>{$data['name']}</td>
            <td>{$data['mobile']}</td>
            <td>{$data['email']}</td>
            <td>{$data['city']}</td>
            <td>{$data['state']}</td>
             <td>{$data['product']}</td>
              <td>{$data['model']}</td>
              <td>{$data['pcode']}</td>
               <td>{$data['sub_time']}</td>
          </tr>";
    $sn++;
}

echo '</table>';

// Include print script to trigger the browser print dialog
echo '<script type="text/javascript">
        window.print();
      </script>';

?>