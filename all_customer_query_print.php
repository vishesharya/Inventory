<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contact Query Print</title>
    <link href="assets/labels.css" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
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
            if(isset($_POST['submit'])) {

                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];

                $fdate = date('Y-m-d', strtotime($_POST['from_date']));
                $tdate = date('Y-m-d', strtotime($_POST['to_date']));

                if(empty($_POST['from_date']) && empty($_POST['to_date'])){
                    $minTime = mysqli_fetch_array(mysqli_query($con,"SELECT MIN(sub_time) as sub_time FROM `contact`")); 
                    $maxTime = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(sub_time) as sub_time FROM `contact`")); 

                    $fdate = date('Y-m-d', strtotime($minTime['sub_time'])); 
                    $tdate = date('Y-m-d', strtotime($maxTime['sub_time'])); 
                }

                $where = " WHERE sub_time BETWEEN '$fdate' AND '$tdate'";
            }

            $q = "SELECT * FROM contact $where ORDER BY sub_time ASC, id ASC";
            $show = mysqli_query($con, $q);    

            // Array to keep track of printed mobile numbers
            $printed_mobiles = array();
            $unique_mobiles = array();

            echo "<table class='detail-table'> <!-- Add class for the table -->
                    <tr>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Time</th>
                    </tr>";

            while($data = mysqli_fetch_array($show)) {
                // Check if mobile number is already printed
                if(!in_array($data['mobile'], $printed_mobiles)) {
                    echo "<tr>
                            <td>".$data['name']."</td>
                            <td>".$data['mobile']."</td>
                            <td>".$data['email']."</td>
                            <td>".$data['city']."</td>
                            <td>".$data['state']."</td>
                            <td>".$data['sub_time']."</td>
                          </tr>";
                    // Add mobile number to printed list
                    $printed_mobiles[] = $data['mobile'];
                }
            }

            echo "</table>";
            ?>
        </page>
    </center>
    <script type="text/javascript">
        window.print();
    </script>   
</body>
</html>
