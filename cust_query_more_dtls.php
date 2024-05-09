<?php
session_start();

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to your login page
    exit; // Stop further execution
}
include_once 'include/connection.php';
include_once 'include/admin-main.php';
  
          $colR=mysqli_query($con,"select * from contact where id='$_GET[id]' ");
          $dtls=mysqli_fetch_array($colR); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php include('include/title.php');?> - Contact Query Details</title>
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

	<script type="text/javascript" src="assets/js/core/app.js"></script>
	<script type="text/javascript" src="assets/js/pages/form_layouts.js"></script>
	<!-- /theme JS files -->
<style>
label.col-lg-3.control-label {
    font-weight: 600;
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"> Contact Query Details</span><!-- - Horizontal--></h4>
						</div>

						
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li class="active"><a href="customer_query_dtls.php" class="btn bg-indigo-300"  > Contact Query Details</a></li>
						</ul>

						
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Horizontal form options -->
					<div class="row">
						

						<div class="col-md-12">

							<!-- Static mode -->
							<form class="form-horizontal" action="#">
								<div class="panel panel-flat">
									<div class="panel-heading">
										<h5 class="panel-title"> Contact Query Details</h5>
										<div class="heading-elements">
											<ul class="icons-list">
						                		<li><a data-action="collapse"></a></li>
						                		<li><a data-action="reload"></a></li>
						                		<li><a data-action="close"></a></li>
						                	</ul>
					                	</div>
									</div>

									<div class="panel-body">
										<div class="form-group">
											<label class="col-lg-3 control-label">Name :</label>
											<div class="col-lg-9">
												<div class="form-control-static"> <?php echo ucfirst($dtls['name']); ?></div>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-lg-3 control-label">Mobile :</label>
											<div class="col-lg-9">
												<div class="form-control-static"> <?php echo ucfirst($dtls['mobile']); ?></div>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-lg-3 control-label">Email :</label>
											<div class="col-lg-9">
												<div class="form-control-static"> <?php echo ucfirst($dtls['email']); ?></div>
											</div>
										</div>
										
											<div class="form-group">
											<label class="col-lg-3 control-label">City :</label>
											<div class="col-lg-9">
												<div class="form-control-static"> <?php echo ucfirst($dtls['city']); ?></div>
											</div>
										</div>
										
										
											<div class="form-group">
											<label class="col-lg-3 control-label">State :</label>
											<div class="col-lg-9">
												<div class="form-control-static"> <?php echo ucfirst($dtls['state']); ?></div>
											</div>
										</div>
										
											
										
										<div class="form-group">
											<label class="col-lg-3 control-label">Product code :</label>
											<div class="col-lg-9">
												<div class="form-control-static"> <?php echo ucfirst($dtls['pcode']); ?></div>
											</div>
										</div>

										<div class="form-group">
											<label class="col-lg-3 control-label">Product Name :</label>
											<div class="col-lg-9">
												<div class="form-control-static"> <?php echo ucfirst($dtls['product']); ?></div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Product Model :</label>
											<div class="col-lg-9">
												<div class="form-control-static"> <?php echo ucfirst($dtls['model']); ?></div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Create date:</label>
											<div class="col-lg-9">
												<div class="form-control-static"> <?php echo ucfirst($dtls['sub_time']); ?></div>
											</div>
										</div>
										
									</div>
								</div>
							</form>
							<!-- /static mode -->

						</div>
					</div>
					<!-- /vertical form options -->


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
