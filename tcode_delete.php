<?php
session_start();

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to your login page
    exit; // Stop further execution
}
include_once 'include/connection.php';
include_once 'include/admin-main.php';

if (isset($_POST['AddCode'])) {
    $codes = $_POST['tcode'];
    for ($i = 0; $i < $codes; $i++) {
        $length = 16; // Adjusted length to accommodate the prefix "FB"
        $prefix = 'TB'; // Prefix to be added to the code

        // Generating the remaining characters for the code
        $remaining_length = $length - strlen($prefix);
        $str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $random_string = substr(str_shuffle($str), 0, $remaining_length);

        // Concatenating prefix and random string to form the code
        $bar = $prefix . $random_string;

        $cr_date = date('Y-m-d');

        // Inserting the code with prefix into the database
        $result = mysqli_query($con, "INSERT INTO t_code (tcode, status, cr_date) VALUES ('$bar', '0', '$cr_date')") or die(mysqli_error());
    }

    header("Location: tcode_delete.php");
    exit();
}

if(isset($_POST['delete'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    // Deleting data from f_code table within the specified date range
    $result = mysqli_query($con, "DELETE FROM t_code WHERE cr_date BETWEEN '$from_date' AND '$to_date'");
    if($result) {
        echo "Data deleted successfully!";
    } else {
        echo "Error deleting data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php include('include/title.php');?> - Tennis Ball Barcode Delete</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="assets/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    
    <script type="text/javascript" src="assets/js/core/app.js"></script>
    <script type="text/javascript" src="assets/js/pages/datatables_sorting.js"></script>
    <!-- /theme JS files -->
    <style>
        .pull.pull-right {
            padding: 11px 0 0 0;
        }
    </style>
</head>

<body>

    <!-- Main navbar -->
    <?php include('include/top.php'); ?>
    <!-- /main navbar -->


    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main sidebar -->
            <?php include('include/left_menu.php'); ?>
            <!-- /main sidebar -->


            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Page header -->
                <div class="page-header page-header-default">
                    <div class="page-header-content">
                        <div class="page-title">
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Tennis Ball Barcode Delete</span> 
                            </h4>
                        </div>

                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="tcode_delete.php" class="btn bg-indigo-300"  >Tennis Ball Barcode Delete</a></li>
                        </ul>
                        <section class="content-header">
                            <div class="pull pull-right">
                               
                            </div>
                        </section>
                    </div>  
                </div>
                <!-- /page header -->
               

                <!-- Content area -->
                <div class="content">
                    <div class="pad margin no-print">
                        <div class="callout callout-info" >       
                            <form action="tcode_delete.php" method="POST" class="form-horizontal" enctype="multipart/form-data"  autocomplete="off">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-1 control-label">From Date</label>
                                        <div class="col-sm-2">
                                            <input type="date" class="form-control datepicker" name="from_date" id="startdate" >
                                        </div>
                                        <label for="inputEmail3" class="col-sm-1 control-label">To Date</label>
                                        <div class="col-sm-2">
                                            <input type="date" class="form-control" name="to_date" id="enddate" >
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" name="submit" class="btn btn-warning ">Submit</button>   
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" name="delete" class="btn btn-success ">Delete</button>   
                                        </div>
                                    </div>  
                                </div>                  
                            </form>
                        </div>
                    </div>
                    <!-- Multi column ordering -->
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h5 class="panel-title">User Details</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                    <li><a data-action="reload"></a></li>
                                    <li><a data-action="close"></a></li>
                                </ul>
                            </div>
                        </div>
                        <table class="table datatable-multi-sorting">
                            <thead>
                                <tr>
                                    <th>Sn.</th>
                                    <th>Code</th>
                                    <th>Status</th>
                                    <th>Date/time</th>
                                    <th style="display: none;"></th>
                                    <th style="display: none;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(isset($_POST['submit'])) {
                                    $sn = 1;
                                    $from_date = $_POST['from_date'];
                                    $to_date = $_POST['to_date'];
                                    $colR = mysqli_query($con, "SELECT * FROM t_code WHERE cr_date BETWEEN '$from_date' AND '$to_date' ORDER BY id DESC");
                                    while ($data = mysqli_fetch_array($colR)) {
                                ?>
                                <tr>
                                    <td><?php echo $sn; ?>.</td>
                                    <td><?php echo ucfirst($data['tcode']); ?></td>
                                    <td>
                                        <?php if ($data['status'] == '1') { ?>
                                            <span class="label label-success">Checked</span>
                                        <?php } else { ?>
                                            <span class="label label-default">Unchecked</span>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo ucfirst($data['cr_date']); ?></td>
                                    <td style="display: none;"></td>
                                    <td style="display: none;"></td>
                                </tr>
                                <?php 
                                        $sn++; 
                                    } 
                                } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /multi column ordering -->

                    <!-- Footer -->
                    <?php include('include/footer.php'); ?>
                    <!-- /footer -->

                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->

    <!--delete/edit validation start-->  
    <script>
        function del(){
            var r=confirm('Are you sure want to delete ? ');
            if(!r){
                return false;
            }
            else{
                return true;
            }
        }
        function edit(){
            var r=confirm('Are you sure want to edit ?');
            if(!r){
                return false;
            }
            else{
                return true;
            }
        }
    </script>
    <!--delete/edit validation end--> 

</body>
</html>
