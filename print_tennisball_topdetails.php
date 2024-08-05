<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

if(isset($_GET['mobile'])) {
    $mobile = $_GET['mobile'];
    $result = mysqli_query($con, "SELECT * FROM contact WHERE mobile = '$mobile' AND product = 'Tennis Ball'");

    // Display details in a table
    echo "<table border='1'>
        <tr>
            <th>Count</th>
            <th>Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>City</th>
            <th>State</th>
            <th>Product</th>
            <th>Model</th>
            <th>Product Code</th>
        </tr>";

    $count = 0;
    while($data = mysqli_fetch_array($result)) {
        $count++;
        echo "<tr>
            <td>$count</td>
            <td>".$data['name']."</td>
            <td>".$data['mobile']."</td>
            <td>".$data['email']."</td>
            <td>".$data['city']."</td>
            <td>".$data['state']."</td>
            <td>".$data['product']."</td>
            <td>".$data['model']."</td>
            <td>".$data['pcode']."</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "Mobile number not provided.";
}
?>
