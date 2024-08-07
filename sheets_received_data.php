<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php include('include/title.php');?> Kits Receive Data</title>
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
         

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Page header -->
                <div class="page-header page-header-default">
                    <div class="page-header-content">
                        <div class="page-title">
                            <h4><i class="icon-arrow-left52 position-left"></i> <a href="inventory.php" class="text-semibold">Click Here - Go Back</a></h4>
                        </div>

                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="inventory.php" class="btn bg-indigo-300"  >Sheets Inventory Data</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /page header -->

                <!-- Print form -->
                <div class="content">
                    <div class="pad margin no-print">
                        <div class="callout callout-info">
                            <form action="sheets_received_data_print.php" method="POST" class="form-horizontal" enctype="multipart/form-data"  autocomplete="off">
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

                <!-- Table of football contact query -->
               
                    <div class="panel panel-flat" style="overflow: auto;">
                        <div class="panel-heading">
                            <h5 class="panel-title">Sheets Inventory Management</h5>
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
                                    <th>Challan No.</th>                           
                                    <th>Product Name</th>
                                    <th>Product Base</th>
                                    <th>Product Color</th>
                                    <th>Big Panel</th>
                                    <th>Plain Panel</th>
                                    <th>Plain Panel Color</th>
                                    <th>Small Panel</th>
                                    <th>Date/Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_quantity1 = 0;
                                $total_quantity2 = 0;
                                $total_quantity3 = 0;
                                $sn=1;
                                $result=mysqli_query($con, "SELECT * FROM sheets_received"); // Selecting all fields from the kits_received table
                                while($data=mysqli_fetch_array($result)) {
                                    $total_quantity1 += $data['quantity1'];
                                    $total_quantity2 += $data['quantity2'];
                                    $total_quantity3 += $data['quantity3'];
                                ?>
                                <tr>
                                    <td><?php echo $sn; ?>.</td>
                                    <td><?php echo $data['challan_no']; ?></td>
                                    <td><?php echo $data['product_name']; ?></td>
                                    <td><?php echo ucfirst($data['product_base']); ?></td>
                                    <td><?php echo ucfirst($data['product_color']); ?></td>
                                    <td><?php echo $data['quantity1']; ?></td>
                                    <td><?php echo $data['quantity2']; ?></td>
                                    <td><?php echo $data['small_panel_color']; ?></td>
                                    <td><?php echo $data['quantity3']; ?></td>
                                    <td><?php echo $data['date_and_time']; ?></td>
                                </tr>
                                <?php 
                                $sn++; 
                                }  
                                ?>
                            </tbody>
                            <tfoot>
                            <tr>
                <th colspan="5">Total:</th>
                <th><?php echo $total_quantity1; ?></th>
                <th><?php echo $total_quantity2; ?></th>
                <th>-</th> 
                <th><?php echo $total_quantity3; ?></th>
                <th>-</th> 
            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /Table of football contact query -->

                <!-- Footer -->
                <?php include('include/footer.php'); ?>
                <!-- /footer -->

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
