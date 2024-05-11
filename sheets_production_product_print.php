<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sheets Inventory Data For Packaging</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Sheets Inventory Data For Packaging</h1>
        <div class="table-container">
            <?php 
            // Initialize totals
            $total_big_panel = 0;
            $total_plain_panel = 0;
            $total_small_panel = 0;

            // Initialize serial number
            $sn = 1;

            // Initialize SQL query
            $q = "SELECT  `product_name`, `product_base`, `product_color`, `remaining_big_panel`, `remaining_small_panel`, `remaining_plain_panel` FROM sheets_production_product WHERE remaining_big_panel != 0 OR remaining_small_panel != 0 OR remaining_plain_panel != 0 ORDER BY product_name ASC";

            $show = mysqli_query($con, $q);

            // Check if there are rows returned
            if(mysqli_num_rows($show) > 0) {
                echo "<table>
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

                    // Add to totals
                    $total_big_panel += $data['remaining_big_panel'];
                    $total_plain_panel += $data['remaining_plain_panel'];
                    $total_small_panel += $data['remaining_small_panel'];

                    $sn++; // Increment serial number
                }

                // Display totals in table footer
                echo "<tr class='total-row'>
                    <td colspan='4'></td>
                    <td>Total: $total_big_panel</td>
                    <td>Total: $total_plain_panel</td>
                    <td>Total: $total_small_panel</td>
                    </tr>";

                echo "</table>";
            } else {
                // If no data found, display message
                echo "<p class='no-data'>No data found</p>";
            }
            ?>
        </div>
        <button onclick="window.print()">Print</button>
    </div>
</body>
</html>
