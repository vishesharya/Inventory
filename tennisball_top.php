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
    <title><?php include('include/title.php');?> Top 10 User </title>
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
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Tennis Ball Top 10 Contact Query</span></h4>
                        </div>

                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="tennisball_top.php" class="btn bg-indigo-300">Tennis Ball Top 10 Contact Query</a></li>
                        </ul>

                        
                    </div>
                </div>
                <!-- /page header -->

                 <!-- start print code  -->

                <!-- Content area -->
                <div class="content">
                 <div class="pad margin no-print">
                    <div class="callout callout-info">       
                        <form action="print_tennisball_top.php" method="POST" class="form-horizontal" enctype="multipart/form-data"  autocomplete="off">
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
                                        <button type="submit" name="submit" class="btn btn-warning">Print</button>   
                                    </div> 
                                </div>  
                                
                            </div>                 
                        </form>
                    </div>
                 </div>
                    
                <!-- end print code -->



                    
                    <!-- Multi column ordering -->
                    <div class="panel panel-flat" style="overflow: auto;">
                        <div class="panel-heading">
                            <h5 class="panel-title">Tennis Ball Top 10 Contact Query</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a data-action="collapse"></a></li>
                                    <li><a data-action="reload"></a></li>
                                    <li><a data-action="close"></a></li>
                                </ul>
                            </div>
                        </div>

                        <!--<div class="panel-body">
                        &nbsp;   
                        </div>-->

                        <table class="table datatable-multi-sorting">
                            <thead>
                                <tr>
                                    <th>Count</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Details</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $result = mysqli_query($con, "SELECT COUNT(*) as count, name, mobile, email, city, state FROM contact WHERE product = 'Tennis Ball' GROUP BY mobile ORDER BY count DESC LIMIT 10");
                            while($data = mysqli_fetch_array($result)) {
                            ?>
                                <tr>
                                    <td><?php echo $data['count']; ?></td>
                                    <td><?php echo ucfirst($data['name']); ?></td>
                                    <td><?php echo ucfirst($data['mobile']); ?></td>
                                    <td><?php echo $data['email']; ?></td>
                                    <td><?php echo $data['city']; ?></td>
                                    <td><?php echo $data['state']; ?></td>
                                    <td>
                                        <a href="print_tennisball_topdetails.php?mobile=<?php echo $data['mobile']; ?>">
                                            <input type="button" value="Details" class="btn bg-teal-400">
                                        </a>
                                    </td>
                                    
                                </tr>
                            <?php 
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
    function del() {
        var r = confirm('Are you sure want to delete ? ');
        if (!r) {
            return false;
        } else {
            return true;
        }
    }
    function edit() {
        var r = confirm('Are you sure want to edit ?');
        if (!r) {
            return false;
        } else {
            return true;
        }
    }
    </script>
    <!--delete/edit validation end--> 

</body>
</html>
