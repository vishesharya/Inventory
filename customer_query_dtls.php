<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php include('include/title.php');?> -All Contact Query</title>
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
                            <!-- Page title -->
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">All Contact Query</span></h4>
                        </div>
                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <!-- Breadcrumb -->
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="customer_query_dtls.php" class="btn bg-indigo-300">All Contact Query</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /page header -->
                 <!-- Print form -->
                 <div class="content">
                    <div class="pad margin no-print">
                        <div class="callout callout-info">
                            <form action="all_customer_query_print.php" method="POST" class="form-horizontal" enctype="multipart/form-data" target="_blank" autocomplete="off">
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
                                            <button type="submit" name="submit" class="btn btn-warning ">Print</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
             
                <!-- /Print form -->

                <!-- Content area -->
                <div class="content">
                    <!-- Contact query table -->
                    <div class="panel panel-flat" style="overflow: auto;">
                        <div class="panel-heading">
                            <h5 class="panel-title"> Contact Query</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <!-- Panel action icons -->
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
                                    <th>Product Code</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Details</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sn = 1;
                                $result = mysqli_query($con, "SELECT * FROM contact WHERE 1 ");
                                while($data = mysqli_fetch_array($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $sn; ?>.</td>
                                    <td><?php echo $data['pcode']; ?></td>
                                    <td><?php echo ucfirst($data['name']); ?></td>
                                    <td><?php echo ucfirst($data['mobile']); ?></td>
                                    <td><?php echo $data['email']; ?></td>
                                    <td><?php echo $data['city']; ?></td>
                                    <td><?php echo $data['state']; ?></td>
                                    <td>
                                        <a href="cust_query_more_dtls.php?id=<?php echo $data['id']; ?>">
                                            <input type="button" value="Details" class="btn bg-teal-400">
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <ul class="icons-list">
                                            <li class="dropdown">
                                                <a href="include/delete_cust_query.php?id=<?php echo $data['id']; ?>" onclick="return del()">
                                                    <img src="assets/images/del.png" style="width:20px;">
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <?php 
                                $sn++; 
                                }  
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /contact query table -->

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

    <!-- Delete/Edit validation -->
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
    <!-- /Delete/Edit validation -->
</body>
</html>
