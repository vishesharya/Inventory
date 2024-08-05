<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

// Initialize variables
$states = array();
$query_counts = array();
$modelMessage = '';

// Check if the form is submitted
if(isset($_POST['submit']) || isset($_POST['light']) || isset($_POST['heavy']) || isset($_POST['solid']) || isset($_POST['football_quick']) || isset($_POST['volleyball_quick'])) {
    // Get the selected date range, product, and model
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $product = $_POST['product'];
    $model = $_POST['model'];
    
    // Check if the light button is clicked and set the model accordingly
    if(isset($_POST['light'])) {
        $product = "Tennis Ball";
        $model = ['Famex Premium', 'Glorex Premium', 'Practice Premium', 'Practice', 'Glorex', 'Famex Star', 'Practice Star', 'Thrill', 'Turf'];
        $model = implode("', '", $model);
    }
    // Check if the heavy button is clicked and set the model accordingly
    if(isset($_POST['heavy'])) {
        $product = "Tennis Ball";
        $model = ['Super', 'Bouncer', 'Playmaster', 'Gold', 'Match', 'Super Eleven', 'Tournament', 'Raunak', 'Super – E', 'Ultimate'];
        $model = implode("', '", $model);
    }
    // Check if the solid button is clicked and set the model accordingly
    if(isset($_POST['solid'])) {
        $product = "Tennis Ball";
        $model = ['Swastik', 'Famex Solid (PL)', 'Aerospex'];
        $model = implode("', '", $model);
    }
    // Check if the football_quick button is clicked and set the model accordingly
    if(isset($_POST['football_quick'])) {
        $product = "Football";
        $model = ['Bullet', 'Famex', 'Famex Super', 'Five Star', 'Funball', 'Jyoti','Lokpriya', 'Practice Top', 'Ruby','Super', 'Winsrex'];
        $model = implode("', '", $model);
    }
    // Check if the volleyball_quick button is clicked and set the model accordingly
    if(isset($_POST['volleyball_quick'])) {
        $product = "Football";
        $model = ['Ruby 18 Panel', 'Aerospex', 'Glorex(PU)', 'Kiwikshot'];
        $model = implode("', '", $model);
    }

    // Construct SQL query based on selected parameters
    $sql_condition = "1"; // Default condition for all records
    if (!empty($from_date) && !empty($to_date)) {
        $sql_condition .= " AND sub_time BETWEEN '$from_date' AND '$to_date'";
    }
    if (!empty($product)) {
        $sql_condition .= " AND product = '$product'";
    }
    if (!empty($model)) {
        $sql_condition .= " AND model IN ('$model')";
    }

    // Fetch data from contact table based on the constructed SQL condition
    $query_contact = "SELECT state, COUNT(*) AS count FROM contact WHERE $sql_condition GROUP BY state";
    $stmt = mysqli_prepare($con, $query_contact);

    // Check if the prepared statement was successful
    if ($stmt) {
        // Execute the statement
        mysqli_stmt_execute($stmt);
        
        // Bind result variables
        mysqli_stmt_bind_result($stmt, $state, $count);
        
        // Fetch values
        while (mysqli_stmt_fetch($stmt)) {
            $states[] = $state;
            $query_counts[] = $count;
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle the case where the statement preparation failed
        echo "Error preparing statement: " . mysqli_error($con);
    }

    // Check if both product and model are satisfied before printing
    if (($product === 'Tennis Ball' && in_array($model, ['Famex Premium', 'Glorex Premium', 'Practice Premium', 'Practice', 'Glorex', 'Famex Star', 'Practice Star', 'Thrill', 'Turf']))
        || ($product === 'Football' && in_array($model, ['Ruby 18 Panel', 'Aerospex', 'Glorex(PU)', 'Kiwikshot'])
    )) {
        echo "Product: $product and Model: $model";
    }
} else {
    // Fetch total queries without any conditions if no filters are applied
    $query_total = "SELECT state, COUNT(*) AS count FROM contact GROUP BY state";
    $result_total = mysqli_query($con, $query_total);
    while ($row_total = mysqli_fetch_assoc($result_total)) {
        $states[] = $row_total['state'];
        $query_counts[] = $row_total['count'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analytics</title>
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
        .bar-chart-container {
            width: 100%;
            
            height: 400px;
        }
        #quick_button{
            display: flex;
            gap: 1rem;
            padding: 1rem;
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
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Analytics</span></h4>
                        </div>
                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="analytics.php" class="btn bg-indigo-300">Analytics</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /page header -->

                <!-- Content area -->
                <div class="content">

                    <div class="pad margin no-print">
                        <div class="callout callout-info">       
                            <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
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
                                        <label for="inputEmail3" class="col-sm-1 control-label">Product</label>

                                        <div class="col-sm-2">
                                            <select class="form-control select" name="product" id="product" onchange="updateModelDropdown()">
                                                  <option value=''>Select Product</option>
                                                  <option value='Tennis Ball'>Tennis Ball</option>
                                                  <option value='Football'>Football / Volleyball</option>
                                            <!-- Add other products here -->>
                                            </select>
                                        </div>


                                        <label for="inputEmail3" class="col-sm-1 control-label">Model</label>
                                        <div class="col-sm-2">
                                            <select class="form-control" name="model" id="model">
                                                <option value="">Select Model</option>
                                            </select>
                                        </div>


                                       
                                    </div>   
                                    <div class="col-sm-1" id="quick_button">
                                            <button type="submit" name="submit" class="btn btn-warning ">Submit</button>   

                                            <button type="submit" name="light" class="btn btn-warning bg-teal-400">Light Cricket Tennis Ball</button>  
                                            <button type="submit" name="heavy" class="btn btn-warning bg-teal-400">Heavy Cricket Tennis Ball</button>  
                                            <button type="submit" name="solid" class="btn btn-warning bg-teal-400">Solid Cricket Tennis Ball</button>
                                            <button type="submit" name="football_quick" class="btn btn-warning bg-teal-400">Football</button>  
                                            <button type="submit" name="volleyball_quick" class="btn btn-warning bg-teal-400">Volleyball</button>   
                                        </div>
                                </div>                  
                            </form>
                            
                        </div>
                    </div>

                    <section class="section">
                        <div class="row">
                            <!-- Bar Chart for queries per state -->
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">NO. of Queries/State <span id="selectedModel"></span></h5>
                                        <!-- Bar Chart for queries per state -->
                                        <div id="barChart" class="bar-chart-container"></div>
                                        <!-- Display selected product -->
                                        <h1 style="padding-left: 1rem;" id="msg_model"><?php echo $modelMessage !== '' ? "Selected product : $modelMessage" : "No Product selected"; ?></h1>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", () => {
                                                new ApexCharts(document.querySelector("#barChart"), {
                                                    series: [{
                                                        name: 'Queries',
                                                        data: <?php echo json_encode($query_counts); ?>
                                                    }],
                                                    chart: {
                                                        type: 'bar',
                                                        height: 350
                                                    },
                                                    plotOptions: {
                                                        bar: {
                                                            horizontal: false,
                                                            columnWidth: '55%',
                                                            endingShape: 'rounded'
                                                        },
                                                    },
                                                    dataLabels: {
                                                        enabled: false
                                                    },
                                                    stroke: {
                                                        show: true,
                                                        width: 2,
                                                        colors: ['transparent']
                                                    },
                                                    xaxis: {
                                                        categories: <?php echo json_encode($states); ?>
                                                    },
                                                    yaxis: {
                                                        title: {
                                                            text: 'Number of Queries'
                                                        }
                                                    },
                                                    fill: {
                                                        opacity: 1
                                                    },
                                                    tooltip: {
                                                        y: {
                                                            formatter: function (val) {
                                                                return val + " queries"
                                                            }
                                                        }
                                                    }
                                                }).render();
                                            });
                                        </script>
                                       
                                        <!-- End Bar Chart for queries per state -->
                                    </div>
                                </div>
                            </div>
                            
                            <!-- /Bar Chart for queries per state -->
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

    <script>
    function updateModelDropdown() {
        var productDropdown = document.getElementById('product');
        var modelDropdown = document.getElementById('model');
        var selectedProduct = productDropdown.value;
        modelDropdown.innerHTML = ''; // Clear existing options
        if (selectedProduct === 'Tennis Ball') {
            // Add options for Tennis Balls
            var tennisModels = ['Select Model','Aerospex','Bouncer', 'Famex Premium', 'Famex Solid (PL)', 'Famex Star', 'Glorex', 'Glorex Premium', 'Gold', 'Match', 'Playmaster', 'Practice', 'Practice Premium', 'Practice Star','Raunak', 'Super ','Super - E', 'Super Eleven', 'Swastik', 'Thrill', 'Tournament', 'Turf','Ultimate'];
            for (var i = 0; i < tennisModels.length; i++) {
                var option = document.createElement('option');
                option.text = tennisModels[i];
                option.value = tennisModels[i];
                modelDropdown.add(option);
            }
        } else if (selectedProduct === 'Football') {
            // Add options for Football
            var footballModels = ['Select Model','Aerospex', 'Bullet', 'Famex', 'Famex Super', 'Five Star', 'Funball', 'Glorex(PU)', 'Jyoti', 'Kiwikshot', 'Lokpriya', 'Practice Top', 'Ruby', 'Ruby 18 Panel', 'Super', 'Winsrex'];
            for (var j = 0; j < footballModels.length; j++) {
                var option2 = document.createElement('option');
                option2.text = footballModels[j];
                option2.value = footballModels[j];
                modelDropdown.add(option2);
            }
        }
        
        updateModelMessage(selectedProduct);
    }

    function updateModelMessage(selectedProduct) {
        var modelDropdown = document.getElementById('model');
        var selectedModel = modelDropdown.value;
        var message = '';

        if (selectedProduct === 'Tennis Ball') {
            if (selectedModel === 'Famex Premium' || selectedModel === 'Glorex Premium' || selectedModel === 'Practice Premium' || selectedModel === 'Practice' || selectedModel === 'Glorex' || selectedModel === 'Famex Star' || selectedModel === 'Practice Star' || selectedModel === 'Thrill' || selectedModel === 'Turf') {
                message = 'Light Cricket Tennis Ball';
            } else if (selectedModel === 'Super' || selectedModel === 'Bouncer' || selectedModel === 'Playmaster' || selectedModel === 'Gold' || selectedModel === 'Match' || selectedModel === 'Super Eleven' || selectedModel === 'Tournament' || selectedModel === 'Raunak' || selectedModel === 'Super – E' || selectedModel === 'Ultimate') {
                message = 'Heavy Cricket Tennis Ball';
            } else if (selectedModel === 'Swastik' || selectedModel === 'Famex Solid (PL)' || selectedModel === 'Aerospex') {
                message = 'Solid Cricket Tennis Balls';
            }
        } else if (selectedProduct === 'Football') {
            if (selectedModel === 'Ruby 18 Panel' || selectedModel === 'Aerospex' || selectedModel === 'Glorex(PU)' || selectedModel === 'Kiwikshot') {
                message = 'Volleyball';
            } else {
                message = selectedProduct;
            }
        }

        document.getElementById('msg_model').textContent = message !== '' ? 'Selected product: ' + message : 'No product selected';
    }
</script>
</body>
</html>
