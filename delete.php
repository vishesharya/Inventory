<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';


$code='';
if(isset($_POST['AddCode'])){
    
$codes=$_POST['code'];    
    
/* function random_string($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}*/

for ($i = 0; $i < $codes; $i++){

$length = 16;
$str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';

$bar =  substr(str_shuffle($str), 0, $length);
 
$cr_date=date('Y-m-d');
 
$result=mysqli_query($con,"INSERT INTO code (code,status,cr_date)
 value('$bar','0','$cr_date')") or die(mysqli_error());
 
 
 header("Location: bar_code.php"); 
}   

}



?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php include('include/title.php');?> - Bar Code Generator Details</title>
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
<style>
.pull.pull-right {
    padding: 11px 0 0 0;
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
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Bar Code Generator Details</span> 
							</h4>
						</div>

					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
							<li class="active"><a href="bar_code.php" class="btn bg-indigo-300"  >Bar Code Generator Details</a></li>
						</ul>

											<section class="content-header">

            <div class="pull pull-right">
                <!--<button type="button" data-toggle="modal" data-target="#myModal"class="btn btn-info"><i class="fa fa-plus-square" aria-hidden="true"></i>-->
                <!--    Generate Bar Code-->
                    
                    
                    
                <!--</button>-->
             
            </div>
 
</section>
					</div>
                    
				</div>
                   
				<!-- /page header -->
    <!-- (Ajax Modal)-->
    <div class="modal fade" id="myModal"  role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body"><div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title"><i class="fa fa-plus-square" aria-hidden="true"></i> Add New Bar Code</h4>
</div>
<div class="modal-body">
   <div class="row">
	  	<div class="box box-danger">
	    	<div class="box-body">
	      <div class="col-md-12">
               
				<form action="#"  method="post" class="form-horizontal" enctype="multipart/form-data" accept-charset="utf-8" novalidate="novalidate">
                 <div class="col-md-6">
	      <div class="form-group">	
			<label>Enter No. of Bar Code:</label>			
            <input type="text" name="code" value="" class="form-control input-lg error" placeholder="Enter No. of Bar Code"   required >
	      </div>
		  </div>
                  <div class="col-md-6">
	  <div class="form-group">  				
		<button name="AddCode" type="submit" class="btn btn-info " value="true" style="margin-top: 27px;
    padding: 9px 4px 8px 3px;"><i class="fa fa-floppy-o" aria-hidden="true"></i> Generate Pin</button>
    
	  </div> 
      
		</form>	  
      
	  </div>
	  </div>	
	  </div>
  </div>
</div>
 <!-- Form Validation -->
</div></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

				<!-- Content area -->
				<div class="content">
 <div class="pad margin no-print">
      <div class="callout callout-info" >       
            <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data"  autocomplete="off">
              <div class="box-body">
                
                  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-1 control-label">From Date</label>
                  <div class="col-sm-2">
                      <input type="date" class="form-control datepicker" name="from_date" id="startdate" >
                  </div>
                   <label for="inputEmail3" class="col-sm-1 control-label">To Date</label>
                  <div class="col-sm-2">
                      <input type="date" class="form-control" name="to_date" id="enddate" >
                  </div>
                  
				  <div class="col-sm-2">
                <button type="submit" name="submit" class="btn btn-warning ">Submit</button>   
                  </div>
                  <div class="col-sm-2">
                <button type="submit" name="delete" class="btn btn-success ">Delete</button>   
                  </div>
                </div>	
              
                </div>			
               
				  
				
								   
                                     
                               
            </form>
      </div>
    </div>
					
					<!-- Multi column ordering -->
				<!-- Multi column ordering -->
					<div class="panel panel-flat">
						<div class="panel-heading">
							<h5 class="panel-title">User Details</h5>
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

						<table class="table datatable-multi-sorting" >
							<thead>
								<tr>
								    <th>Sn.</th>
								    <th>Code</th>
								    <th>Status</th>
								    <th>Date/time</th>
								    <th style="display: none;"></th>
								    <th style="display: none;"></th>
								
								</tr>
							</thead>
							<tbody>
									<?php $sn=1;
					if(isset($_POST['submit']))
					{
					    $fdate=$_POST['from_date'];
					    $to=$_POST['to_date'];
					    $q="select * from  code where cr_date>='$fdate' and cr_date<='$to' order by id desc";
         $colR=mysqli_query($con,"select * from  code where cr_date>='$fdate' and cr_date<='$to' order by id desc");
          while($data=mysqli_fetch_array($colR)){
		  
	
								?>
								<tr>
								    <td><?php echo $sn; ?>.</td>
								  
									<td><?php echo ucfirst($data['code']); ?></td>
								<td><?php if($data['status']=='1') { ?>
									<span class="label label-success">Checked</span> <?php } ?>
									<?php if($data['status']=='0') {  ?>
									<span class="label label-default">Unchecked</span>
									<?php } ?></td>
									<td><?php echo ucfirst($data['cr_date']); ?></td>
						            <td style="display: none;"></td>
								    <td style="display: none;"></td>
								</tr>
	 <?php $sn++; } } 
	 if(isset($_POST['delete']))
	 {
	     $fdate=$_POST['from_date'];
					    $to=$_POST['to_date'];
	   $colR=mysqli_query($con,"delete from  code where cr_date>='$fdate' and cr_date<='$to' and status='0' ");   
	   echo "deleted";
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
	function del(){
	var r=confirm('Are you sure want to delete ? ');
	if(!r){
	return false;
	}
	else{
	return true;
	}
	}
	function edit(){
	var r=confirm('Are you sure want to edit ?');
	if(!r){
	return false;
	}
	else{
	return true;
	}
	}
	</script>
<!--delete/edit validation end--> 

</body>
</html>
