<?php
include_once 'include/connection.php';
include_once 'include/admin-main.php';

if(isset($_POST['admin_login'])){
    session_start();
    $check_login = new vishesh_admin();
    $login = $check_login->slect_valid_user("users");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php include('include/title.php'); ?> - Khanna Sports</title>
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
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/core/app.js"></script>
    <script type="text/javascript" src="assets/js/pages/login.js"></script>
    <!-- /theme JS files -->
</head>
<body class="login-container login-cover">

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Content area -->
                <div class="content pb-20">

                    <!-- Tabbed form -->
                    <div class="tabbable panel login-form width-400">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active"><a href="#basic-tab1" data-toggle="tab"><h6>Bar Code Generator</h6></a></li>
                        </ul>

                        <div class="tab-content panel-body">
                            <div class="tab-pane fade in active" id="basic-tab1">
                                <form method="post">
                                    <div class="text-center">
                                        <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
                                        <h5 class="content-group">Login to your account <small class="display-block">Your credentials</small></h5>
                                    </div>

                                    <div class="form-group has-feedback has-feedback-left">
                                        <input type="text" class="form-control" placeholder="Username" name="username" required="required">
                                        <div class="form-control-feedback">
                                            <i class="icon-user text-muted"></i>
                                        </div>
                                    </div>

                                    <div class="form-group has-feedback has-feedback-left">
                                        <input type="password" class="form-control" placeholder="Password" name="password" required="required">
                                        <div class="form-control-feedback">
                                            <i class="icon-lock2 text-muted"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group login-options">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" class="styled" checked="checked">
                                                    Remember
                                                </label>
                                            </div>

                                            <div class="col-sm-6 text-right">
                                                <a href="forgot.php">Forgot Password</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" name="admin_login" class="btn bg-blue btn-block">Login <i class="icon-arrow-right14 position-right"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /tabbed form -->
                </div>
                <!-- /content area -->
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-QCJH6HJ090"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-QCJH6HJ090');
    </script>
</body>
</html>
