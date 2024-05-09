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
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Dashboard <?php echo $_SESSION['name']; ?></h4>
                        </div>
                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active">Dashboard</li>
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
                                
                                   <a href="./sheets_received.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Receive Form</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="./sheets_issue.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Issue Form</p>
                                        </div>
                                    </div>
                                </a>
                                
                                   <a href="./sheets_received_data.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Sheets Receive Data</p>
                                        </div>
                                    </div>
                                </a>
                                 <a href="./sheets_issue_data.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Sheets Issue Data</p>
                                        </div>
                                    </div>
                                </a>
                                 
                                <a href="sheets_issue_all_slip.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Sheets Issue Slip</p>
                                        </div>
                                    </div>
                                </a>
                                 <a href="./sheets_product_list.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Inventory Status</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="sheets_color_panel_inventory.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-teal-400">
                                            <p style="margin: 10px; font-size: large;">Color Pannel Inventory Status</p>
                                        </div>
                                    </div>
                                </a>
                                
                              
                                <a href="sheets_panel_color.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-pink-400">
                                            <p style="margin: 10px; font-size: large;"> Add/Delete Color Panel</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="./add_labour.php" style='color:white'>
                                    <div class="col-lg-4">
                                        <div class="panel bg-pink-400">
                                            <p style="margin: 10px; font-size: large;">Add/Delete Labour</p>
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
