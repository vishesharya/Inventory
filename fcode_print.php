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
<title>Football Barcode Print</title>
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
        padding: 0;
    }
}

@page {
    margin: 0;
    box-shadow: 0;
}

p {
    position: relative;
    font-size: 15px;
    line-height: 70px !important;
    margin-top: 0px!important;
    margin-left: 0px!important;
    margin-bottom: 0px!important;
    margin-right: 0px!important;
    padding: 0 0 0 22px!important;
}

.label {
    position: relative;
    width: 5.05cm!important;
    height: 1.92cm!important;
    margin: 0.1cm!important;
    float: left;
    text-align: initial;
    overflow: hidden;
    outline: 2px dotted;
    font-size: 13px;
    margin-top: 22px;
    font-weight: 300;
}
</style>
</head>
<body>
    <center>
    <page size="A4">
    <?php 
    if(isset($_POST['submit'])) {
        $from_date =   $_POST['from_date'];
        $to_date   =   $_POST['to_date'];

        $fdate = date('Y-m-d', strtotime($_POST['from_date']));
        $tdate = date('Y-m-d', strtotime($_POST['to_date']));

        if(empty($_POST['from_date']) && empty($_POST['to_date'])){
            $minDate = mysqli_fetch_array(mysqli_query($con,"SELECT MIN(cr_date) as cr_date FROM `code` WHERE 1 ")); 
            $maxDate = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(cr_date) as cr_date FROM `code` WHERE 1 ")); 
            $fdate = date('Y-m-d', strtotime($minDate['cr_date'])); 
            $tdate = date('Y-m-d', strtotime($maxDate['cr_date'])); 
        }

        $where = " AND status='0' AND cr_date BETWEEN '$fdate' AND '$tdate'";
    }

    $q = "SELECT * FROM f_code WHERE 1 $where ORDER BY cr_date ASC, id ASC";
    $show = mysqli_query($con, "SELECT * FROM f_code WHERE 1 $where ORDER BY cr_date ASC, id ASC");    

    while($row = mysqli_fetch_array($show)) { 
    ?>
    <div class="label" style="width:25%;">
        <p style="text-align: -webkit-center;font-size: 16px;"></p>
        <p style='text-transform: uppercase;'><?php echo $row['fcode']; ?></p>
    </div>
    <?php 
    } 
    ?>
    </page>
    </center>
    <script type="text/javascript">
        window.print();
    </script>   
</body>
</html>
