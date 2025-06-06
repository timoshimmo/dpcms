<header class="header d-flex align-center space-between">
		<a class="d-mobile mobile-logo" href="dashboard"><img src="https://i.ibb.co/prWn33kB/destiny-pdf.jpg" style="max-width:100%;"></a>

		<div class="header-desc d-flex-destop flex-col">

			<h5 class="text-lg-bold mb-0">Good <?php echo $dateTerm; ?>, <?php echo ucwords(stripslashes($fetch_user_details['fullname'])); ?></h5>
			<p class="text-blur text-">Destiny Promoters Logged In Account ID - <?php echo $fetch_user_details['user_account_token'];?> <span style="color: #CE0016;">(<?php echo ($fetch_user_details['user_account_type']=='others' ? "Other" : "Primary") ?> Account)</span></p>
		</div>

		<div class="header-right d-flex align-center">
			<div class="header-right-list-wrapper d-flex">
				<input type="checkbox" class="custom-check" name="">
				<div class="header-right-dropdown-wrapper">
					<h5 class="text-md-bold">Switch Account</h5>
						<ul class="header-right-dropdown-menu d-flex d-flex flex-col">

							<?php
								$query_user_all_account = query_user_all_account($session_logged_in_user_id);

								if(mysqli_num_rows($query_user_all_account) <= 0 ){

								}else{
									while($fetch_all_accounts = mysqli_fetch_array($query_user_all_account) ){
										extract($fetch_all_accounts);
										?>
											<li class="header-right-dropdown-list">
												<a href="switch-account?acct_id=<?php echo base64_encode($user_account_id);?>&user_id=<?php echo base64_encode($user_id);?>" class="d-flex header-dropdown-link align-center">
													<div class="header-dropdown-icon-wrapper">
														<span class="header-icon header-icon-success">
															<i class="ri-check-line text-white"></i>
														</span>
													</div>
													<div class="d-flex flex-col">
														<h6 class="text-sm-bold mb-0"><?php echo $user_account_token; ?></h6>
														<span class="text-sm-blur"><?php echo date("D, M, j Y", strtotime($user_account_created_at)); ?> <?php echo $user_account_type == "primary" ? "<span class='text-sm txt-success'>(Primary)</span>" : ""; ?></span>
													</div>
												</a>
											</li>

										<?php
									}
								}

							?>

							

							<!-- <li class="header-right-dropdown-list">
								<a href="" class="d-flex header-dropdown-link align-center">
									<div class="header-dropdown-icon-wrapper">
										<span class="header-icon header-icon-danger">
											<i class="ri-close-line text-white"></i>
										</span>
									</div>
									<div class="d-flex flex-col">
										<h6 class="text-sm-bold mb-0">Dora Blessing</h6>
										<span class="text-sm-blur">2020-11-04 12:00:23</span>
									</div>
								</a>
							</li>

							<li class="header-right-dropdown-list">
								<a href="" class="d-flex header-dropdown-link align-center">
									<div class="header-dropdown-icon-wrapper">
										<span class="header-icon header-icon-pending">
											<i class="ri-question-mark text-white"></i>
										</span>
									</div>
									<div class="d-flex flex-col">
										<h6 class="text-sm-bold mb-0">Dora Blessing</h6>
										<span class="text-sm-blur">2020-11-04 12:00:23</span>
									</div>
								</a>
							</li>

							<li class="header-right-dropdown-list">
								<a href="" class="d-flex header-dropdown-link align-center">
									<div class="header-dropdown-icon-wrapper">
										<span class="header-icon header-icon-pending">
											<i class="ri-question-mark text-white"></i>
										</span>
									</div>
									<div class="d-flex flex-col">
										<h6 class="text-sm-bold mb-0">Dora Blessing</h6>
										<span class="text-sm-blur">2020-11-04 12:00:23</span>
									</div>
								</a>
							</li>

							<li class="header-right-dropdown-list">
								<a href="" class="d-flex header-dropdown-link align-center">
									<div class="header-dropdown-icon-wrapper">
										<span class="header-icon header-icon-pending">
											<i class="ri-question-mark text-white"></i>
										</span>
									</div>
									<div class="d-flex flex-col">
										<h6 class="text-sm-bold mb-0">Dora Blessing</h6>
										<span class="text-sm-blur">2020-11-04 12:00:23</span>
									</div>
								</a>
							</li> -->

						</ul>
				</div>	
			</div>

			<div class="header-right-user d-flex-destop">
				<div class="header-right-img-wrapper">
					<img src="<?php echo $query_fetch_user_details['photo'] ? $query_fetch_user_details['photo'] : "photos/avatar-1.jpg.png" ?>" class="header-right-img">
				</div>

				<div class="d-flex flex-col">
					<h6 class="text-sm-bold mb-0">

						<?php 
							$first_name = explode(" ", $fetch_user_details['fullname']);  
							echo ucwords(stripslashes($first_name[0]));
							
						?>
								

					</h6>
					<span class="text-sm-blur"><?php echo $fetch_user_details['user_account_token'];?></span>
				</div>
			</div>
			<img class="d-mobile toggle-open" src="assets/images/bar.png">
		</div>

	</header>