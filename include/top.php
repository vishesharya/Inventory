<?php 

if(!isset($_SESSION['username'])
    ){
	header("location:index.php?msg=your session has been expired. Please login here!");
	exit();
}
$_SESSION['time'] = time();
?>
<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			

			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>

				
			</ul>

			<p class="navbar-text"><span class="label bg-success">Online</span></p>
			

			<ul class="nav navbar-nav navbar-right">
				
	<li class="dropdown dropdown-user">
                
					<a  href="validation.php" target="_blank" >
						<button  class="btn btn-success ">Verification Form</button>  
					</a>

				
				</li>
				<li class="dropdown dropdown-user">
                
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="assets/images/profile.png" alt="">
						<span><?php echo $_SESSION['name']; ?></span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						
						<li><a href="logout.php"><i class="icon-switch2"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->