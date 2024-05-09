<?php
session_start();

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to your login page
    exit; // Stop further execution
}
include_once 'include/connection.php';
include_once 'include/admin-main.php';

// Add new codes to the database
if (isset($_POST['AddCode'])) {
 

    // Redirecting to the specified page after adding codes
    
}

// Deleting codes from the database within a specified date range
if(isset($_POST['delete'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $result = mysqli_query($con, "DELETE FROM f_code WHERE cr_date BETWEEN '$from_date' AND '$to_date'");
    if($result) {
        header("Location: fcode_delete.php");
    exit();
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php include('include/title.php');?> - Football Barcode Delete</title>
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
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Football Barcode Delete</span></h4>
                        </div>
                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="fcode_delete.php" class="btn bg-indigo-300">Football Barcode Delete</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /page header -->

                <!-- Content area -->
                <div class="content">
                    <div class="pad margin no-print">
                        <div class="callout callout-info">       
                            <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data"  autocomplete="off">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-1 control-label">From Date</label>
                                        <div class="col-sm-2">
                                            <input type="date" class="form-control datepicker" name="from_date" id="startdate">
                                        </div>
                                        <label for="inputEmail3" class="col-sm-1 control-label">To Date</label>
                                        <div class="col-sm-2">
                                            <input type="date" class="form-control" name="to_date" id="enddate">
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" name="submit" class="btn btn-warning">Submit</button>   
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" name="delete" class="btn btn-success">Delete</button>   
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
                                    $colR = mysqli_query($con, "SELECT * FROM f_code WHERE cr_date BETWEEN '$from_date' AND '$to_date' ORDER BY id DESC");
                                    while ($data = mysqli_fetch_array($colR)) {
                                ?>
                                <tr>
                                    <td><?php echo $sn; ?>.</td>
                                    <td><?php echo ucfirst($data['fcode']); ?></td>
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
