<?php include "sidebar.php" ?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">




	
		<?php
        echo '<div class="alert alert-info mt-0">
				<strong>New Members:</strong> Click <a href="https://chat.whatsapp.com/BF0s1XqQcdbCrBSBIPrYP4" target="_blank" class="btn btn-success btn-sm">Here</a> to join the new Destiny Promoters WhatsApp Group Channel .
					 </div>';
			if ($query_fetch_user_details['is_acct_active'] == 'no' ){
		?>
			
			<div class="alert alert-danger mt-0">
				<strong>N.B:</strong> Your Account is Not Active yet. Kindly go to your profile section to add your Next of Kin Info. <a href="profile" class="btn btn-success btn-sm">Go to Profile</a>
			</div>

		<?php
			}
			else if (mysqli_num_rows(query_user_bank_details($session_logged_in_user_id))<=0) {
				echo '<div class="alert alert-danger mt-0">
				<strong>N.B:</strong> You need to add your bank account details to your profile before your account can be ACTIVE. Please note that you will need administrator approval later if you need to change/update the bank account details on your profile after being added to your account. <a href="manage-bank-account" class="btn btn-success btn-sm">Add Bank Details</a>
					 </div>';
			}
			else if (empty($query_fetch_user_details['photo'])) {
				echo '<div class="alert alert-danger mt-0">
				<strong>N.B:</strong> You need to upload your profile photo before you can view other features. <a href="profile" class="btn btn-success btn-sm">Upload Photo</a>
					 </div>';
			}
			else if (!empty($query_fetch_user_details['photo'])) {
				// using this to check for those that uploaded video or other format instead of images before the photo validation.
				$user_profile_photo = $query_fetch_user_details['photo'];
				$get_fileType = pathinfo($user_profile_photo, PATHINFO_EXTENSION); 
				 // Allow certain file formats 
        		$allowTypes = array('jpg', 'png', 'jpeg', 'JPEG', 'PNG', 'JPG'); 
        		if(in_array($get_fileType, $allowTypes)){ 
        			//echo "format supported"; 
        			//do nothing since you uploaded a genuine and supported format
        		}
        		else{
        			//echo "format not supported";
        			echo "<script>alert('Please, you need to re-upload your profile photo to let your account remain active because the existing photo you uploaded is not a supported format, failure to do this will result in getting your account suspended.')</script>";
        			echo '<div class="alert alert-danger mt-0">
				<strong>N.B:</strong> Please, you need to re-upload your profile photo to let your account remain active because the existing photo you uploaded is not a supported format, failure to do this will result in getting your account suspended. <a href="profile" class="btn btn-success btn-sm">Re-Upload Photo</a>
					 </div>';
        		}
				//echo $get_fileType;
			}

			include("virtual-dashboard-message.php");
			include("debt-fine-msg.php");

			
			
			include("shirt-payment-error.php");	

		?>



		<div class="home-referral-text-wrapper d-flex">
			<div class="home-referral-text d-flex align-center">
				<h6 class="m-0 text-lg-bold">Referral I.D:</h6>
				<span class="text-blur"><?php echo get_user_account_details($session_logged_in_user_account_id, 'referral_id'); ?></span>
			</div>
			<span class="badge-danger" onclick="copyToClipBoard(this)">Copy Text</span>
			<textarea id="copyInput" style="display: none;"><?php echo get_user_account_details($session_logged_in_user_account_id, 'referral_id'); ?></textarea>
		</div>

		<div class="mt-5">
			<div class="grid-lists d-flex">
				<div class="grid-col-4 d-flex align-center">
					<img style="width:60px" src="assets/images/dollar-bag.png">
					<div> 
						<h6 class="text-md-bold mb-2">Wallet Balance</h6>
						<h2 class="m-0 text-blur">₦<?php 
							$wallet_balance = get_user_account_details($session_logged_in_user_account_id, "user_account_wallet_amount");
						 	echo  number_format($wallet_balance);
						 ?></h2>
					</div>
				</div>

				<div class="grid-col-4 grid-col-4 d-flex align-center">
					<img style="width:60px" src="assets/images/dollar-bag-rise.png">
					<div>
						<h6 class="text-md-bold mb-2 color1">Total Thrift Account</h6>
						
						<h2 class="m-0 text-blur"><?php 
							
							$counts = mysqli_num_rows($query_user_all_account);

							echo $counts;
							
						 ?></h2>
					</div>
				</div>

				<!-- <div class="grid-col-4 grid-col-4 d-flex align-center">
					<img style="width:60px" src="assets/images/coin.png">
					<div>
						<h6 class="text-md-bold mb-2 color2">Total Earnings</h6>
						<h2 class="m-0 text-blur">NGN 0</h2>
					</div>
				</div> -->

				<div class="grid-col-4 grid-col-4 d-flex align-center">
					<img style="width:60px" src="assets/images/user-group.png">
					<div>
						<h6 class="text-md-bold mb-2 color3">Total Downlines</h6>
						<h2 class="m-0 text-blur"><?php echo get_user_account_details($session_logged_in_user_account_id, "referral_count"); ?></h2>
					</div>
				</div>

				<div class="grid-col-4 grid-col-4 d-flex align-center">
					<img style="width:60px" src="assets/images/target.png">
					<div>
						<h6 class="text-md-bold mb-2 color4">Active Plan(s)</h6>
						<h2 class="m-0 text-blur"><?php echo mysqli_num_rows(query_users_thrift_transaction($session_logged_in_user_account_id)) ?></h2>
					</div>
				</div>


					<?php
				
						$query_user_bank_details = query_user_virtual_bank_details($session_logged_in_user_id);
						$count_virtual_row = mysqli_num_rows($query_user_bank_details);
						if ($count_virtual_row > 0) {

								$fetch_account_details = mysqli_fetch_array($query_user_bank_details);
						 
					?>
				<div class="grid-col-4 grid-col-4 d-flex align-center">
					<img style="width:60px;" src="https://www.freeiconspng.com/uploads/bank-icon-23.jpg"> 
					<div> 
						<h6 class="text-md-bold mb-2 color5" style="color: #CE0016;"><?php echo $fetch_account_details['user_vt_acct_bank_name'];  ?></h6>
						<h6 class="text-md-bold mb-2 color5" style="color: #CE0016;"><?php echo $fetch_account_details['user_vt_acct_account_number'];  ?></h6>
						<h6 class="text-md-bold mb-2 color5" style="color: #CE0016;"><?php echo substr($fetch_account_details['user_vt_acct_note'],31);  ?></h6>

						<!-- <h2 class="m-0 text-blur">djj</h2> -->
					</div>
				</div>
				<?php
						}
					
				?>


			<div class="grid-col-4 grid-col-4 d-flex align-center">
					<img style="width:60px" src="https://us.123rf.com/450wm/argus456/argus4561604/argus456160405290/54950608-paid-sign-background-with-some-soft-smooth-lines-isolated-red-rubber-stamp-on-a-solid-white.jpg">
					<div>
						<h6 class="text-md-bold mb-2 color3">Total Contributions</h6>
						<h2 class="m-0 text-blur">₦<?php 
							$query_active_thrift =  query_active_account_totalpaid_debt_transaction($session_logged_in_user_id, $session_logged_in_user_account_id);
							$count_active_thrift = mysqli_num_rows($query_active_thrift);
							if ($count_active_thrift<=0) {
								echo 0;	
							}
							else{
							
							$fetch_paid_active_thrift = mysqli_fetch_array($query_active_thrift);
							@$get_txn_id = $fetch_paid_active_thrift['txn_id'];
					
							$total_contribution = getTotalPaidcontribution($session_logged_in_user_account_id,$get_txn_id) * 1500;
								echo number_format($total_contribution);
							}
							 ?>
							 </h2>
					</div>
				</div>


				<div class="grid-col-4 grid-col-4 d-flex align-center">
					<img style="width:60px" src="assets/images/noble-debts-images.png">
					<div>
						<h6 class="text-md-bold mb-2 color3">Total Debts (Defaults)</h6>
						<h2 class="m-0 text-blur">₦<?php 
							$query_active_thrift =  query_active_account_totalpaid_debt_transaction($session_logged_in_user_id, $session_logged_in_user_account_id);

							$fetch_thrift_package = mysqli_fetch_assoc($query_active_thrift);

							//$package_price = $fetch_thrift_package['thrift_package_price']; 

							$total_debt_contribution = getTotalDebtcontribution($session_logged_in_user_account_id) * 1500;
							if($total_debt_contribution>0){
							    echo number_format($total_debt_contribution * 2).'<span style="font-size:10px;">+charges</span>';
							}
							else{
							    echo number_format($total_debt_contribution * 2);
							}
								
							 ?>
							 </h2>
					</div>
				</div>


				<div class="grid-col-4 grid-col-4 d-flex align-center">
					<img style="width:60px" src="https://cdn-icons-png.flaticon.com/128/4285/4285550.png">
					<div>
						<h6 class="text-md-bold mb-2 color3">Current Thrift Weeks</h6>
						<h2 class="m-0 text-blur">
								<?php
					$query_thrift = mysqli_query($con, "SELECT * FROM thrift_transaction_details JOIN thrift_package ON thrift_package.thrift_package_id =  thrift_transaction_details.thrift_package_id WHERE user_account_id = '$session_logged_in_user_account_id' AND txn_status = 'active' AND thrift_txn_counter = 'on' ORDER BY txn_id DESC");
					if (mysqli_num_rows($query_thrift)<=0) {
						// code...
						echo 0;
					}
					else{
					$fetch_thrift_tnx = mysqli_fetch_array($query_thrift);
					extract($fetch_thrift_tnx);

									$today = strtotime("today");

									$str_start_days = strtotime($thrift_start_date);

									$str_end_days = strtotime($thrift_end_date);

									if($str_start_days > $today){
										echo 0;
									}else if ($today >= $str_end_days) {
										
										echo "<p class='text text-success my-0 text-sm'>Thrift matured</p>";
										

									}else{
										echo  floor(($today - $str_start_days) / (60 * 60 * 24 * 7)) + 1;

									}
								}
								?>
						</span>
							 </h2>
					</div>
				</div>


		
			
		</div>
	</div>
</div>

</body>
</html>