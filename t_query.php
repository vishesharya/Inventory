<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

// Handle form submission
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php include('include/title.php');?> Tennis Ball Contact Query</title>
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Tennis Ball Contact Query</span></h4>
						</div>
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li class="active"><a href="t_query.php" class="btn bg-indigo-300"  >Tennis Ball Contact Query</a></li>
						</ul>
					</div>
				</div>
				<!-- /page header -->

				<!-- Start print code -->

				<!-- Content area -->
				<div class="content">
					<div class="pad margin no-print">
						<div class="callout callout-info">       
							<form action="t_query.php" method="POST" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
								<div class="box-body">
									<div class="form-group">
										<label for="from_date" class="col-sm-1 control-label">From Date</label>
										<div class="col-sm-2">
											<input type="date" class="form-control datepicker" name="from_date" id="startdate" value="<?php echo $from_date; ?>" >
										</div>
										<label for="to_date" class="col-sm-1 control-label">To Date</label>
										<div class="col-sm-2">
											<input type="date" class="form-control" name="to_date" id="enddate" value="<?php echo $to_date; ?>" >
										</div>
										<div class="col-sm-2">
											<button type="submit" name="filter" class="btn btn-primary">Filter</button>
										</div> 
										<div class="col-sm-2">
											<button type="submit" formaction="tquery_print.php" formtarget="_blank" name="print" class="btn btn-warning">Print</button>   
										</div> 
									</div>
								</div>			  
							</form>
						</div>
					</div>

					<!-- End print code -->

					<!-- Multi column ordering -->
					<div class="panel panel-flat" style="overflow: auto;">
						<div class="panel-heading">
							<h5 class="panel-title">Tennis Ball Contact Query</h5>
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
							$sn=1;
							
							// Base query
							$query = "SELECT * FROM contact WHERE product = 'Tennis Ball'";
							
							// Add date filter if selected
							if (!empty($from_date) && !empty($to_date)) {
								$query .= " AND sub_time BETWEEN '$from_date' AND '$to_date'";
							}

							$result = mysqli_query($con, $query);

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
												<a href="include/delete_cust_query.php?id=<?php echo $data['id']; ?>" onClick="return del();">
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

	<!-- delete/edit validation start -->  
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
	<!-- delete/edit validation end --> 

</body>

</html>
