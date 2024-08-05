<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Football Query Print</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link href="assets/styles.css" rel="stylesheet" type="text/css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-row td {
            font-weight: bold;
        }
        .no-data {
            text-align: center;
            margin-top: 20px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #45a049;
        }
        @media print {
            .print_btn {
                display: none !important;
            }
        }
        .heading {
            align-items: center;
            text-align: center;
        }
    </style>
</head>
<body>
    <center>
        <page size="A4">
         <h1>Sheets Received Data</h1>
            <?php 
            // Initialize serial number
            $sn = 1;

            // Initialize totals
            $total_big_panel_quantity = 0;
            $total_plain_panel_quantity = 0;
            $total_small_panel_quantity = 0;

            // Initialize SQL query
            $q = "SELECT `id`, `challan_no`, `product_name`, `product_base`,`product_color`, `quantity1`, `quantity2`, `small_panel_color`, `quantity3`, `date_and_time` FROM sheets_received";

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
                echo "<table class='detail-table'> <!-- Add class for the table -->
                    <tr>
                    <th>Sr.</th>
                    <th>Challan No.</th>
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
                    <td>".$data['product_name']."</td>
                    <td>".$data['product_base']."</td>
                    <td>".$data['product_color']."</td>
                    <td>".$data['quantity1']."</td>
                    <td>".$data['quantity2']."</td>
                    <td>".$data['small_panel_color']."</td>
                    <td>".$data['quantity3']."</td>
                    <td>".$data['date_and_time']."</td>
                    </tr>";

                    // Sum up the quantities
                    $total_big_panel_quantity += $data['quantity1'];
                    $total_plain_panel_quantity += $data['quantity2'];
                    $total_small_panel_quantity += $data['quantity3'];

                    $sn++; // Increment serial number
                }

                // Display totals row
                echo "<tr class='total-row'>
                    <td colspan='5'></td>
                    <td>".$total_big_panel_quantity."</td>
                    <td>".$total_plain_panel_quantity."</td>
                    <td></td>
                    <td>".$total_small_panel_quantity."</td>
                    <td></td>
                    </tr>";
                echo "</table>";
            } else {
                // If no data found, display only the table headers
                echo "<table class='detail-table'> <!-- Add class for the table -->
                <tr>
                <th>Sr.</th>
                <th>Challan No.</th>
                <th>Product Name</th>
                <th>Product Base</th>
                <th>Product Color</th>
                <th>Big Panel Quantity</th>
                <th>Plain Panel Quantity</th>
                <th>Small Panel Color</th>
                <th>Small Panel Quantity</th>
                <th>Date And Time</th>
                </tr>";
                echo "</table>";
                echo "<p class='no-data-msg'>No data found</p>"; // Added a message for no data found
            }
            ?>
        </page>
    </center>
    <script type="text/javascript">
        window.print();
    </script>   
</body>
<script type="text/javascript">
        window.print();
    </script> 
</html>
