<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');

// Initialize variables
$check_count_f = 0;
$uncheck_count_f = 0;
$check_count_t = 0; 
$uncheck_count_t = 0;

// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Get the selected date range
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Fetch data from f_code table based on the date range
    $query_f = "SELECT status, COUNT(*) AS count FROM f_code WHERE cr_date BETWEEN '$from_date' AND '$to_date' GROUP BY status";
    $result_f = mysqli_query($con, $query_f);

    // Process the data to count check and uncheck codes for f_code
    while ($row_f = mysqli_fetch_assoc($result_f)) {
        if ($row_f['status'] == 1) {
            $check_count_f = $row_f['count'];
        } elseif ($row_f['status'] == 0) {
            $uncheck_count_f = $row_f['count'];
        }
    }

    // Fetch data from t_code table based on the date range
    $query_t = "SELECT status, COUNT(*) AS count FROM t_code WHERE cr_date BETWEEN '$from_date' AND '$to_date' GROUP BY status";
    $result_t = mysqli_query($con, $query_t);

    // Process the data to count check and uncheck codes for t_code
    while ($row_t = mysqli_fetch_assoc($result_t)) {
        if ($row_t['status'] == 1) {
            $check_count_t = $row_t['count'];
        } elseif ($row_t['status'] == 0) {
            $uncheck_count_t = $row_t['count'];
        }
    }
} else {
    // If the date range is not filled, fetch and display counts for all codes
    $query_all_f = "SELECT status, COUNT(*) AS count FROM f_code GROUP BY status";
    $result_all_f = mysqli_query($con, $query_all_f);
    while ($row_all_f = mysqli_fetch_assoc($result_all_f)) {
        if ($row_all_f['status'] == 1) {
            $check_count_f = $row_all_f['count'];
        } elseif ($row_all_f['status'] == 0) {
            $uncheck_count_f = $row_all_f['count'];
        }
    }

    $query_all_t = "SELECT status, COUNT(*) AS count FROM t_code GROUP BY status";
    $result_all_t = mysqli_query($con, $query_all_t);
    while ($row_all_t = mysqli_fetch_assoc($result_all_t)) {
        if ($row_all_t['status'] == 1) {
            $check_count_t = $row_all_t['count'];
        } elseif ($row_all_t['status'] == 0) {
            $uncheck_count_t = $row_all_t['count'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php include('include/title.php');?>Code Usage Ratio</title>
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

    <!-- Vendor JS Files -->
    <script src="new/vendor/apexcharts/apexcharts.min.js"></script>

    <style>
        .card {
            margin: 1rem;
            border: 1px solid black; /* Add border */
            border-radius: 10px; /* Add border radius */
        }
        .card-title {
            padding-left: 10px;
            background-color: rgb(55, 71, 79);
            color: white;
            font-size: large;
        }
        /* Make the chart container responsive */
        .pie-chart-container {
            width: 100%;
            max-width: 600px; /* Set maximum width if needed */
            
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
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Code Usage Ratio</span></h4>
                        </div>
                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="check_ratio.php" class="btn bg-indigo-300">Code Usage Ratio</a></li>
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
                                            <button type="submit" name="submit" class="btn btn-success">Submit</button>   
                                        </div>
                                    </div>   
                                </div>                  
                            </form>
                        </div>

                    <section class="section">
                        <div class="row">
                            <!-- Chart for f_code table -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Football</h5>
                                        <!-- Pie Chart for f_code table -->
                                        <div id="pieChart_f" class="pie-chart-container"></div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", () => {
                                                // Calculate percentage values for f_code table
                                                let total_f = <?php echo $check_count_f + $uncheck_count_f; ?>;
                                                let checkedPercentage_f = <?php echo $check_count_f; ?> / total_f * 100;
                                                let uncheckedPercentage_f = <?php echo $uncheck_count_f; ?> / total_f * 100;

                                                new ApexCharts(document.querySelector("#pieChart_f"), {
                                                    series: [<?php echo $check_count_f; ?>, <?php echo $uncheck_count_f; ?>],
                                                    chart: {
                                                        height: 350,
                                                        type: 'pie',
                                                        toolbar: {
                                                            show: true
                                                        }
                                                    },
                                                    labels: ['Checked Codes (' + checkedPercentage_f.toFixed(2) + '%)', 'Unchecked Codes (' + uncheckedPercentage_f.toFixed(2) + '%)'],
                                                    colors: ['#28a745', '#dc3545'] // Green for checked, Red for unchecked
                                                }).render();
                                            });
                                        </script>
                                        <!-- End Pie Chart for f_code table -->
                                    </div>
                                </div>
                            </div>
                            <!-- /Chart for f_code table -->

                            <!-- Chart for t_code table -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Tennis Ball</h5>
                                        <!-- Pie Chart for t_code table -->
                                        <div id="pieChart_t" class="pie-chart-container"></div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", () => {
                                                // Calculate percentage values for t_code table
                                                let total_t = <?php echo $check_count_t + $uncheck_count_t; ?>;
                                                let checkedPercentage_t = <?php echo $check_count_t; ?> / total_t * 100;
                                                let uncheckedPercentage_t = <?php echo $uncheck_count_t; ?> / total_t * 100;

                                                new ApexCharts(document.querySelector("#pieChart_t"), {
                                                    series: [<?php echo $check_count_t; ?>, <?php echo $uncheck_count_t; ?>],
                                                    chart: {
                                                        height: 350,
                                                        type: 'pie',
                                                        toolbar: {
                                                            show: true
                                                        }
                                                    },
                                                    labels: ['Checked Codes (' + checkedPercentage_t.toFixed(2) + '%)', 'Unchecked Codes (' + uncheckedPercentage_t.toFixed(2) + '%)'],
                                                    colors: ['#28a745', '#dc3545'] // Green for checked, Red for unchecked
                                                }).render();
                                            });
                                        </script>
                                        <!-- End Pie Chart for t_code table -->
                                    </div>
                                </div>
                            </div>
                            <!-- /Chart for t_code table -->
                        </div>
                    </section>
                
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
