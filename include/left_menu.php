<div class="sidebar sidebar-main">
				<div class="sidebar-content">

					<!-- User menu -->
					<div class="sidebar-user">
						<div class="category-content">
							<div class="media">
								<a href="#" class="media-left"><!--<img src="" class="img-circle img-sm" alt="">!--></a>
								<div class="media-body">
									<span class="media-heading text-semibold"><?php echo $_SESSION['name']; ?></span>
									<div class="text-size-mini text-muted">
										<i class="icon-pin text-size-small"></i> &nbsp; Khanna Sports
									</div>
								</div>

								<div class="media-right media-middle">
									<ul class="icons-list">
										<li>
											<a href="#"><i class="icon-cog3"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- /user menu -->


					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">

								<!-- Main -->
								
								<li class="active"><a href="dashboard.php"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
							  
								
								<li>
									<a href="fb_barcode.php"><i class="icon-stack2"></i><span>Football Barcode Generate </span></a>
								</li>
								<li>
									<a href="fcode_delete.php"><i class="icon-stack2"></i><span>Football Barcode Delete </span></a>
								</li>
								<li>
									<a href="f_query.php"><i class="icon-stack2"></i><span>Football Contact Query</span></a>
								</li>
								<li>
									<a href="football_top.php"><i class="icon-stack2"></i><span>Football Top 10 Contact Query</span></a>
								</li>
								<li>
									<a href="tb_barcode.php"><i class="icon-stack2"></i><span>Tennis Ball Barcode Generate </span></a>
								</li>
								<li>
									<a href="tcode_delete.php"><i class="icon-stack2"></i><span>Tennis Ball Barcode Delete</span></a>
								</li>
								<li>
									<a href="t_query.php"><i class="icon-stack2"></i><span>Tennis Ball Contact Query</span></a>
								</li>
								<li>
									<a href="tennisball_top.php"><i class="icon-stack2"></i><span>Tennis Ball Top 10 Contact Query</span></a>
								</li>
								<li>
									<a href="delete.php"><i class="icon-stack2"></i><span>Delete Old Code</span></a>
								</li>
								<li>
									<a href="customer_query_dtls.php"><i class="icon-stack2"></i><span>All Contact Query</span></a>
								</li>
								<li>
									<a href="delete_all_data.php"><i class="icon-stack2"></i><span>Factory Data Delete</span></a>
								</li>
							
								<?php if($_SESSION['role_user']=='1'){ ?>
									<li>
									<a href="forgotPassword.php"><i class="icon-stack2"></i><span>Forgot Password</span></a>
								</li> 
								
								<?php } ?>
								<!-- /main -->

								<!-- Layout -->
								
								
							
								<!-- /layout -->

								

							

							</ul>
						</div>
					</div>
					<!-- /main navigation -->

				</div>
			</div>