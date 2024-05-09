<?php
session_start();

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to your login page
    exit; // Stop further execution
}
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
            $q = "SELECT `challan_no`, `stitcher_name`, `product_name`, `product_base`, `product_color`, `issue_quantity`, `date_and_time`, `bladder_name`, `bladder_quantity`, `thread_name`, `thread_quantity` FROM kits_issue";

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
                    <th>Serial No.</th>
                    <th>Challan No.</th>
                    <th>Stitcher Name</th>
                    <th>Product Name</th>
                    <th>Product Base</th>
                    <th>Product Color</th>
                    <th>Issue Quantity</th>
                  
                    <th>Bladder Name</th>
                    <th>Bladder Quantity</th>
                    <th>Thread Name</th>
                    <th>Thread Quantity</th>
                      <th>Date And Time</th>
                    </tr>";

                // Fetch and display data
                while($data = mysqli_fetch_array($show)) {
                    echo "<tr>
                    <td>".$sn."</td>
                    <td>".$data['challan_no']."</td>
                    <td>".$data['stitcher_name']."</td>
                    <td>".$data['product_name']."</td>
                    <td>".$data['product_base']."</td>
                    <td>".$data['product_color']."</td>
                    <td>".$data['issue_quantity']."</td>
                 
                    <td>".$data['bladder_name']."</td>
                    <td>".$data['bladder_quantity']."</td>
                    <td>".$data['thread_name']."</td>
                    <td>".$data['thread_quantity']."</td>
                       <td>".$data['date_and_time']."</td>
                    </tr>";
                    $sn++; // Increment serial number
                }

                echo "</table>";
            } else {
                // If no data found, display only the table headers
                echo "<table class='detail-table'> <!-- Add class for the table -->
                    <tr>
                    <th>Serial No.</th>
                    <th>Challan No.</th>
                    <th>Stitcher Name</th>
                    <th>Product Name</th>
                    <th>Product Base</th>
                    <th>Product Color</th>
                    <th>Issue Quantity</th>
                    
                    <th>Bladder Name</th>
                    <th>Bladder Quantity</th>
                    <th>Thread Name</th>
                    <th>Thread Quantity</th>
                    <th>Date/Time</th>
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
