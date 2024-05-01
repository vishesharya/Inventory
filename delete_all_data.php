<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

// Function to delete data from specified table between given dates
function deleteData($tableName, $dateColumn, $fromDate, $toDate) {
    global $con;
    $query = "DELETE FROM $tableName WHERE $dateColumn BETWEEN '$fromDate' AND '$toDate'";
    mysqli_query($con, $query) or die(mysqli_error($con));
}

if(isset($_POST['submit'])) {
    // Get the selected date range
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Delete data from multiple tables based on specified date range
    deleteData('contact', 'sub_time', $from_date, $to_date);
    deleteData('t_code', 'cr_date', $from_date, $to_date);
    deleteData('f_code', 'cr_date', $from_date, $to_date);
    deleteData('code', 'cr_date', $from_date, $to_date);

    // Redirect back to the same page after deleting
    header("Location: delete_all_data.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php include('include/title.php');?> - Delete All Data</title>
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
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Delete All Data</span></h4>
                        </div>
                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="delete_all_data.php" class="btn bg-indigo-300">Factory Data Delete</a></li>
                        </ul>
            
                    </div>  
                </div>
                <!-- /page header -->

              

                <!-- Content area -->
                <div class="content">
                    <div class="pad margin no-print">
                        <div class="callout callout-info">       
                            <!-- Form to select date range for data deletion -->
                            <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data" target="_blank" autocomplete="off">
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
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

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
