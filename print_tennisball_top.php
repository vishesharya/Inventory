<?php
session_start();

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to your login page
    exit; // Stop further execution
}
include_once 'include/connection.php';
include_once 'include/admin-main.php';

// Include necessary files
include_once 'include/connection.php';
include_once 'include/admin-main.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tennis Ball Top 10 Query Print</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link href="assets/labels.css" rel="stylesheet" type="text/css">
    <style>
        page {
            background: white;
            display: block;
            margin: 1.0cm;
            box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
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
        .detail-table td {
            padding: 10px;
            border: 1px solid #000;
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
            // Initialize $where variable
            $where = '';

            // Check if form is submitted
            if(isset($_POST['submit'])) {
                // Get dates from form
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];

                // Convert dates to Y-m-d format
                $fdate = date('Y-m-d', strtotime($_POST['from_date']));
                $tdate = date('Y-m-d', strtotime($_POST['to_date']));

                // Check if dates are empty
                if(empty($_POST['from_date']) && empty($_POST['to_date'])){
                    // Get minimum and maximum submission times
                    $minTime = mysqli_fetch_array(mysqli_query($con,"SELECT MIN(sub_time) as sub_time FROM `contact` WHERE product = 'Tennis Ball'")); 
                    $maxTime = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(sub_time) as sub_time FROM `contact` WHERE product = 'Tennis Ball'")); 

                    // Set dates to minimum and maximum submission times
                    $fdate = date('Y-m-d', strtotime($minTime['sub_time'])); 
                    $tdate = date('Y-m-d', strtotime($maxTime['sub_time'])); 
                }

                // Set condition for SQL query
                $where = " AND sub_time BETWEEN '$fdate' AND '$tdate'";
            }

            // Formulate SQL query
            $q = "SELECT * FROM contact WHERE product = 'Tennis Ball' $where ORDER BY sub_time ASC, id ASC";
            $show = mysqli_query($con, $q);   
            ?>

            <!-- Display data in a table -->
            <table class="detail-table">
                <?php while($row = mysqli_fetch_array($show)) { ?>
                    <tr class="separator">
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td class="main-heading">Name:</td>
                        <td><?php echo ucfirst($row['name']); ?></td>
                    </tr>
                    <tr>
                        <td class="main-heading">Mobile:</td>
                        <td><?php echo $row['mobile']; ?></td>
                    </tr>
                    <tr>
                        <td class="main-heading">Email:</td>
                        <td><?php echo $row['email']; ?></td>
                    </tr>
                    <tr>
                        <td class="main-heading">City:</td>
                        <td><?php echo $row['city']; ?></td>
                    </tr>
                    <tr>
                        <td class="main-heading">State:</td>
                        <td><?php echo $row['state']; ?></td>
                    </tr>
                    <tr>
                        <td class="main-heading">Product:</td>
                        <td><?php echo $row['product']; ?></td>
                    </tr>
                    <tr>
                        <td class="main-heading">Model:</td>
                        <td><?php echo $row['model']; ?></td>
                    </tr>
                    <tr>
                        <td class="main-heading">Product Code:</td>
                        <td><?php echo $row['pcode']; ?></td>
                    </tr>
                    <tr>
                        <td class="main-heading">Time:</td>
                        <td><?php echo $row['sub_time']; ?></td>
                    </tr>
                <?php } ?>
                <tr class="separator">
                    <td colspan="2"></td>
                </tr>
            </table>

        </page>
    </center>
    <!-- Script to print the page -->
    <script type="text/javascript">
        window.print();
    </script>   
</body>
</html>
