<?php 
session_start();
error_reporting(0);
include('include/connection.php');
include_once 'include/admin-main.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php include('include/title.php');?> - Dashboard</title>
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
    <script type="text/javascript" src="assets/js/plugins/visualization/d3/d3.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
    <script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
    <script type="text/javascript" src="assets/js/core/app.js"></script>
    <script type="text/javascript" src="assets/js/pages/dashboard.js"></script>
    <!-- /theme JS files -->
</head>

<body>
    <!-- Main navbar -->
    <?php include('include/top.php'); ?>
    <!-- /main navbar -->

    <!-- Page container -->
    <div  class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main sidebar -->
            
            <!-- /main sidebar -->

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Page header -->
                <div class="page-header page-header-default">
                    <div class="page-header-content">
                        <div class="page-title">
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> Kits Inventory (WITHOUT PRINT)<?php echo $_SESSION['name']; ?></h4>
                        </div>
                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="Kits_inventory.php"><i class="icon-home2 position-left"></i>GO Back</a></li>
                            <li class="active">Kits Inventory (WITHOUT PRINT)</li>
                        </ul>
                    </div>
                </div>
                <!-- /page header -->

                <!-- Content area -->
                <div class="content">

                    <!-- Dashboard content -->
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Quick stats boxes -->
                            <div class="row">
                                
                                <a href="kits_issue_for_printing.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Issue Form (WITHOUT PRINT)</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="kits_received_for_printing.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Receive Form (WITHOUT PRINT)</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="without_print_issue_slip.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Kits Issue Slip (WITHOUT PRINT)</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="without_print_received_slip.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Kits Received Slip (WITHOUT PRINT)</p>
                                        </div>
                                    </div>
                                </a>

                                <a href="kits_issue_print_data.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Kits Issue Data (WITHOUT PRINT)</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="kits_received_print_data.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Kits Received Data (WITHOUT PRINT)</p>
                                        </div>
                                    </div>
                                </a>
                                

                                <a href="printing_job_work.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Kits Job Work (WITHOUT PRINT)</p>
                                        </div>
                                    </div>
                                </a>

                                <a href="ink_inventory.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Ink Inventory Status</p>
                                        </div>
                                    </div>
                                </a>
                       
                            </div>
                        </div>
                    </div>
                    <!-- /dashboard content -->

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
</body>
</html>
