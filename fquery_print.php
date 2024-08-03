<?php
session_start();
include_once 'include/connection.php';

// Get the filter parameters
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

// Filter query
$query = "SELECT * FROM contact WHERE product = 'Football'";

if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND sub_time BETWEEN '$from_date' AND '$to_date'";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Football Contact Query Print</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
</head>
<body>
    <div class="container">
        <h2>Football Contact Query Report</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sn.</th>
                    <th>Product Code</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sn = 1;
                $result = mysqli_query($con, $query);
                while($data = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td><?php echo $sn; ?>.</td>
                    <td><?php echo htmlspecialchars($data['pcode']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($data['name'])); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($data['mobile'])); ?></td>
                    <td><?php echo htmlspecialchars($data['email']); ?></td>
                    <td><?php echo htmlspecialchars($data['city']); ?></td>
                    <td><?php echo htmlspecialchars($data['state']); ?></td>
                    <td>
                        <a href="cust_query_more_dtls.php?id=<?php echo htmlspecialchars($data['id']); ?>">
                            Details
                        </a>
                    </td>
                </tr>
                <?php
                $sn++;
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
