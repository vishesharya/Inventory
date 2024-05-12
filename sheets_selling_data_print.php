<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Seets Selling Data</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link href="assets/labels.css" rel="stylesheet" type="text/css">
    <style>
        /* Add custom styles for improved UI */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-data {
            text-align: center;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Seets Selling Data</h1>
        <form method="post" action="">
            <label for="from_date">From Date:</label>
            <input type="date" id="from_date" name="from_date">
            <label for="to_date">To Date:</label>
            <input type="date" id="to_date" name="to_date">
            <button type="submit" name="submit">Submit</button>
        </form>
        <?php
        // Initialize serial number
        $sn = 1;

        // Initialize SQL query
        $q = "SELECT `id`, `challan_no`,`invoice_number`,`buyer_name`, `product_name`, `product_base`, `quantity1`, `quantity2`, `small_panel_color`, `quantity3`, `date_and_time` FROM sheets_selling";

        // Check if the submit button is clicked and from_date and to_date are specified
        if(isset($_POST['submit']) && !empty($_POST['from_date']) && !empty($_POST['to_date'])) {
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];

            $fdate = date('Y-m-d', strtotime($_POST['from_date']));
            $tdate = date('Y-m-d', strtotime($_POST['to_date']));

            // Add condition for date range
            $q .= " WHERE date_and_time BETWEEN '$fdate' AND '$tdate'";
        }

        $q .= " ORDER BY date_and_time ASC";

        $show = mysqli_query($con, $q);

        // Check if there are rows returned
        if(mysqli_num_rows($show) > 0) {
            echo "<table>
                    <tr>
                        <th>Sr.</th>
                        <th>Challan No.</th>
                        <th>Invoice No.</th>
                        <th>Buyer Name</th>
                        <th>Product Name</th>
                        <th>Product Base</th>
                        <th>Product Color</th>
                        <th>Big Panel Quantity</th>
                        <th>Plain Panel Quantity</th>
                        <th>Small Panel Color</th>
                        <th>Small Panel Quantity</th>
                        <th>Date And Time</th>
                    </tr>";

            // Fetch and display data
            while($data = mysqli_fetch_array($show)) {
                echo "<tr>
                        <td>".$sn."</td>
                        <td>".$data['challan_no']."</td>
                        <td>".$data['invoice_number']."</td>
                        <td>".$data['buyer_name']."</td>
                        <td>".$data['product_name']."</td>
                        <td>".$data['product_base']."</td>
                        <td>".$data['product_color']."</td>
                        <td>".$data['quantity1']."</td>
                        <td>".$data['quantity2']."</td>
                        <td>".$data['small_panel_color']."</td>
                        <td>".$data['quantity3']."</td>
                        <td>".$data['date_and_time']."</td>
                    </tr>";
                $sn++; // Increment serial number
            }

            echo "</table>";
        } else {
            // If no data found, display only the table headers
            echo "<p class='no-data'>No data found</p>";
        }
        ?>
    </div>
    <script type="text/javascript">
        window.print();
    </script>  
</body>
</html>
