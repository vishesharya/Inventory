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
            <h1>Football Inventory</h1>
            <?php 
            // Initialize serial number
            $sn = 1;

            // Initialize total quantity
            $total_quantity = 0;

            // Initialize SQL query
            $q = "SELECT  `product_name`, `product_base`, `product_color`, `remaining_quantity` FROM products";

            $q .= " ORDER BY `product_name` ASC";

            $show = mysqli_query($con, $q);

            // Check if there are rows returned
            if(mysqli_num_rows($show) > 0) {
                echo "<table class='detail-table'> <!-- Add class for the table -->
                    <tr>
                    <th>Sr.</th>
                    <th>Product Name</th>
                    <th>Product Base</th>
                    <th>Product Color</th>
                    <th>Balance</th>
                    </tr>";

                while($data = mysqli_fetch_array($show)) {
                    if ($data['remaining_quantity'] > 0) {
                        echo "<tr>
                        <td>".$sn."</td>
                        <td>".$data['product_name']."</td>
                        <td>".$data['product_base']."</td>
                        <td>".$data['product_color']."</td>
                        <td>".$data['remaining_quantity']."</td>
                        </tr>";
                        $sn++; // Increment serial number
                        // Add remaining quantity to total
                        $total_quantity += $data['remaining_quantity'];
                    }
                }

                // Display total row
                echo "<tr>
                    <td colspan='4' style='text-align: right;'>Total:</td>
                    <td>".$total_quantity."</td>
                </tr>";

                echo "</table>";
            } else {
                // If no data found, display only the table headers
                echo "<table class='detail-table'> <!-- Add class for the table -->
                    <tr>
                    <th>Sr.</th>
                    <th>Product Name</th>
                    <th>Product Base</th>
                    <th>Product Color</th>
                    <th>Balance</th>
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
