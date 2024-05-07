<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Football Query Print</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link href="assets/labels.css" rel="stylesheet" type="text/css">
    <style>
        page {
            background: white;
            display: block;
            margin: 1.0cm;
           
        }
        @media print {
            body, page {
                margin: 0!important;
                box-shadow: 0;
                padding:0;
            }
        }
        @page {
            margin: 0;
            box-shadow: 0;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
        }
        .detail-table th,
        .detail-table td {
            padding: 10px;
            border: 1px solid #000; /* Set border to solid */
        }
        .main-heading {
            font-weight: bold;
            text-align: right;
            width: 200px;
        }
        .separator {
            border-top: 2px double #000;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <center>
        <page size="A4">
            <?php 
            // Initialize serial number
            $sn = 1;

            // Initialize SQL query
            $q = "SELECT 'product_name', 'product_base', 'product_color', 'remaining_big_panel', 'remaining_small_panel', 'remaining_plain_panel' FROM sheets_product ORDER BY product_name ASC";

     

            $show = mysqli_query($con, $q);

            // Check if there are rows returned
            if(mysqli_num_rows($show) > 0) {
                echo "<table class='detail-table'> <!-- Add class for the table -->
                    <tr>
                    <th>Sn.</th>
                    <th>Product Name</th>
                    <th>Product Base</th>
                    <th>Product Color</th>
                    <th>Big Panel Stock</th>
                    <th>Plain Panel Stock</th>
                    <th>Small Panel Stock</th>
                    </tr>";

                // Fetch and display data
                while($data = mysqli_fetch_array($show)) {
                    echo "<tr>
                    <td>".$sn."</td>
                    <td>".$data['product_name']."</td>
                    <td>".$data['product_base']."</td>
                    <td>".$data['product_color']."</td>
                    <td>".$data['remaining_big_panel']."</td>
                    <td>".$data['remaining_plain_panel']."</td>
                    <td>".$data['remaining_small_panel']."</td>
                    </tr>";
                    $sn++; // Increment serial number
                }

                echo "</table>";
            } else {
                // If no data found, display only the table headers
                echo "<table class='detail-table'> <!-- Add class for the table -->
                    <tr>
                    <th>Sn.</th>
                    <th>Product Name</th>
                    <th>Product Base</th>
                    <th>Product Color</th>
                    <th>Big Panel Stock</th>
                    <th>Plain Panel Stock</th>
                    <th>Small Panel Stock</th>
                    </tr>";
                echo "</table>";
                echo "<p>No data found</p>";
            }
            ?>
        </page>
    </center>
    <script type="text/javascript">
        window.print();
    </script>   
</body>
</html>
