<?php
include_once 'include/connection.php';

$city = '';

if(isset($_POST['passA'])){
    $user = $_POST['a1'];   
    $pass = $_POST['password'];    
    
    if($user == "Rottman") {
        $city = mysqli_query($con, "UPDATE login SET password='$pass' WHERE id = 1");   
    } else {
        echo "Wrong Answer";
        die;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php include('include/title.php');?> - Forgot Password</title>
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
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/pages/form_layouts.js"></script>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="assets/js/core/app.js"></script>
    <script type="text/javascript" src="assets/js/pages/editor_ckeditor.js"></script>
    <!-- /theme JS files -->
</head>
<body>
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->
            <div class="content-wrapper">
                <!-- Page header -->
                <div class="page-header page-header-default">
                    <div class="page-header-content">
                        <div class="page-title">
                            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Forgot Password</span></h4>
                        </div>
                    </div>
                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Dashboard</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /page header -->

                <!-- Content area -->
                <div class="content">
                    <!-- Fieldset legend -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Advanced legend -->
                            <form action="#" method="post">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">Forgot Password</h5>
                                        <?php if($city != '') { ?>
                                            <div class="alert bg-primary alert-styled-left">
                                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                                                <span class="text-semibold">Ok!</span> Forgot Password added successfully.
                                            </div>
                                        <?php } ?>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                                <li><a data-action="reload"></a></li>
                                                <li><a data-action="close"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <fieldset>
                                            <legend class="text-semibold">
                                                <i class="icon-file-text2 position-left"></i>
                                                Enter Forgot Password 
                                                <a class="control-arrow" data-toggle="collapse" data-target="#demo1">
                                                    <i class="icon-circle-down2"></i>
                                                </a>
                                            </legend>
                                            <div class='row'>
                                                <div class='col-lg-2'>
                                                    <div class="form-group">
                                                        <label>Question:</label>
                                                        <select  class="form-control">
                                                            <option>College Name</option>
                                                        </select>
                                                    </div>
                                                </div>    
                                                <div class='col-lg-2'>
                                                    <div class="form-group">
                                                        <label>Answer:</label>
                                                        <input type='text' name='a1' class='form-control'>
                                                    </div>
                                                </div>    
                                            </div>

                                            <div class="collapse in" id="demo1">
                                                <div class="form-group">
                                                    <label>Password:</label>
                                                    <input type="text" name="password" class="form-control" placeholder="Forgot Password.." required>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="text-right">
                                            <button type="submit" name="passA" class="btn btn-primary">Submit<i class="icon-arrow-right14 position-right"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /fieldset legend -->
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
