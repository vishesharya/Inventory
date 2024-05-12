<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

$total_small_panel = 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sheets Color Panel Inventory Data For Packaging</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link href="assets/labels.css" rel="stylesheet" type="text/css">
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
        .heading{
            align-items: center;
            text-align: center;
        }
     
       
        
    </style>
</head>
<body>
    <div class="container">
                <h2 class="heading">SHEETS COLOR PANEL INVENTORY (PACKAGING)</h2>
        <div class="table-container">
            <?php 
            // Initialize totals
            $total_small_panel = 0;

            // Initialize serial number
            $sn = 1;

            // Initialize SQL query
            $q = "SELECT `product_name`, `small_sheet_color`, `small_sheet_balance` FROM sheets_small_stock WHERE small_sheet_balance != 0  ORDER BY product_name ASC";

            $show = mysqli_query($con, $q);

            // Check if there are rows returned
            if(mysqli_num_rows($show) > 0) {
                echo "<table>
                    <tr>
                    <th>Sn.</th>
                    <th>Product Name</th>
                    <th>small Panel Color</th>
                    <th>Small panel Balance</th>
                    </tr>";

                // Fetch and display data
                while($data = mysqli_fetch_array($show)) {
                    echo "<tr>
                    <td>".$sn."</td>
                    <td>".$data['product_name']."</td>
                    <td>".$data['small_sheet_color']."</td>
                    <td>".$data['small_sheet_balance']."</td>
                    </tr>";

                    // Add to totals
                    $total_small_panel += $data['small_sheet_balance'];
                  

                    $sn++; // Increment serial number
                }

                // Display totals in table footer
                echo "<tr class='total-row'>
                    <td colspan='3'></td>
                 
                    <td>Total: $total_small_panel</td>
                    </tr>";

                echo "</table>";
            } else {
                // If no data found, display message
                echo "<p class='no-data'>No data found</p>";
            }
            ?>
        </div>
        <button class="print_btn" onclick="window.print()">Print</button>
    </div>
    <script type="text/javascript">
        window.print();
    </script> 
</body>
</html>
