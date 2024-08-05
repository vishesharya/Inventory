<?php 
include './include/check_login.php';
include './include/connection.php';
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
    <script src="assets/js/plugins/loaders/pace.min.js"></script>
    <script src="assets/js/core/libraries/jquery.min.js"></script>
    <script src="assets/js/core/libraries/bootstrap.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="assets/js/plugins/visualization/d3/d3.min.js"></script>
    <script src="assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
    <script src="assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script src="assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
    <script src="assets/js/plugins/ui/moment/moment.min.js"></script>
    <script src="assets/js/plugins/pickers/daterangepicker.js"></script>
    <script src="assets/js/core/app.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>
    <!-- /theme JS files -->
</head>

<body>
    <?php include('include/top.php'); ?>

    <div class="page-container">
        <?php include('include/left_menu.php'); ?>

        <div class="content-wrapper">
            <div class="page-header page-header-default">
                <div class="page-header-content">
                    <div class="page-title">
                        <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - Generate Barcode <?php echo $_SESSION['name']; ?></h4>
                    </div>
                </div>

                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                        <li class="active">Generate Barcode</li>
                    </ul>
                </div>
            </div>

            <div class="content">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <a href="fb_barcode.php" style="color:white">
                                <div class="col-lg-4">
                                    <div class="panel bg-teal-400">
                                        <p style="margin: 10px; font-size: large;">Football</p>
                                    </div>
                                </div>
                            </a>

                            <a href="tb_barcode.php" style="color:white">
                                <div class="col-lg-4">
                                    <div class="panel bg-teal-400">
                                        <p style="margin: 10px; font-size: large;">Tennis Ball</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('include/footer.php'); ?>
        </div>
    </div>
</body>
</html>
