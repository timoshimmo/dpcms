<?php include "sidebar.php" ?>

<?php
	

	if (isset($_POST['next_of_kin_btn'])) {
		
		$output = "";

		include("next_of_kin_post.php");

		$add_next_of_kin = create_user_next_of_kin($session_logged_in_user_id, $fullname, $phone_number, $address, $occupation,
			$email, $dob, $gender);

		if($add_next_of_kin){
			$output = "<div class='alert alert-success'>
				Next of Kin Information has been added successfully. Wait while the system redirect you.
			</div>";

			header("refresh:3, url=dashboard");
		}else{
			$output = "<div class='alert alert-danger'>
				Something went wrong. Please try again later.
			</div>";
		}
		
	}

	if (isset($_POST['update_next_of_kin_btn'])) {
		
		$output = "";

		$next_of_kin_id = mysqli_real_escape_string($con, $_POST['next_of_kin_id']);
		

		include("next_of_kin_post.php");

		$add_next_of_kin = update_user_next_of_kin($next_of_kin_id, $fullname, $phone_number, $address, $occupation,
			$email, $dob, $gender);

		if($add_next_of_kin){
			$output = "<div class='alert alert-success'>
				Next of Kin Information has been Updated successfully. Wait while the system redirect you.
			</div>";

			header("refresh:3, url=dashboard");
		}else{
			$output = "<div class='alert alert-danger'>
				Something went wrong. Please try again later.
			</div>";
		}
		
	}


	if (isset($_POST['user_update_btn'])) {
		
		$output = "";

		include("next_of_kin_post.php");

		$tmpname = $_FILES['photo']['tmp_name'];
		$filename = $_FILES['photo']['name'];
		$foldername = 'photos/' ;
		$joinfile = $foldername .uniqid(). $filename;

		$fileType = pathinfo($joinfile, PATHINFO_EXTENSION); 
                 
        // Allow certain file formats 
        $allowTypes = array('jpg', 'png', 'jpeg', 'JPEG', 'PNG', 'JPG'); 

        if(in_array($fileType, $allowTypes)){  
		$movefile = move_uploaded_file($tmpname, $joinfile);

		if ($movefile) {
			
			$update_users = update_user_information($fullname, $email,  $phone_number,  $joinfile,  $address,  $dob, $gender, $session_logged_in_user_id);

			if ($update_users) {
				$output = "<div class='alert alert-success'>
				Your Information has been Updated successfully. Wait while the system redirect you.
				</div>";
				header("refresh:1, url=dashboard");
			}else{
				$output = "<div class='alert alert-danger'>
				Something Went Wrong. Please try again later.
				</div>";
			}
		}
		}
		else{
			$output = '<div class="alert alert-danger">
								<span>Oops!!. Photo File format trying to upload not supported. Allowed file type for image are JPG, JPEG, PNG</span>
						</div>';
		}
	}

?>
<div class="main-container">
<div class="d-mobile">
	<?php include "header.php" ?>	
</div>


<header class="header d-flex-destop align-items-end" style="justify-content:space-evenly;">

	<h5 class="text-lg-bold header-tab-lists header-tab-lists-active" data-tab="tab1">Personal Information</h5>

	<?php
		if ($query_fetch_user_details['is_acct_active'] == 'yes' ) { 
	?>

	<h5 class="text-lg-bold header-tab-lists" data-tab="tab2">List Of Accounts</h5>

	<?php
		}
	?>

	<h5 class="text-lg-bold header-tab-lists <?php echo $query_fetch_user_details['is_acct_active'] == 'no' ? 'text-danger' : "" ?>" data-tab="tab3">Next Of Kin Information</h5>
</header>

	<div class="main-content-body">

		<div class="mb-5 d-flex-mobile mobile-tabs">
			<span class="mobile-tab-lists active" data-tab="tab1">Personal Information</span>
			<?php
				if ($query_fetch_user_details['is_acct_active'] == 'yes' ) { 
			?>
			<span class="mobile-tab-lists" data-tab="tab2">List Of Accounts</span>
			<?php
				}
			?>
			<span class="mobile-tab-lists" data-tab="tab3">Next Of Kin Information</span>
			
		</div>

		<?php 
			if (isset($_POST['next_of_kin_btn']) || isset($_POST['update_next_of_kin_btn']) || isset($_POST['user_update_btn'])) {
				echo $output;

			}

			$fetch_next_of_kin_details = query_user_next_of_kin_details($session_logged_in_user_id);

			include("debt-fine-msg.php");
			include("shirt-payment-error.php");		
		?>
	
		<!-- TAB 3 STARTS -->
		<div id="tab3" class="profile-tabs">
			<form class="d-flex flex-col" method="POST" style="gap:40px;">
				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Full Name:</label>
					</div>
					<div class="col-sm-7">
						<input type="text" class="form-control input" required name="fullname" value="<?php 
							echo @$fetch_next_of_kin_details['next_of_kin_fullname'];
						 ?>">
					</div>
				</div>

				<input type="hidden" class="form-control input" required name="next_of_kin_id" value="<?php 
							echo @$fetch_next_of_kin_details['next_of_kin_id'];
						 ?>">


				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Phone Number:</label>
					</div>
					<div class="col-sm-7" style="position:relative;">
						<input type="text" class="form-control input input-padding" required value="<?php echo @$fetch_next_of_kin_details['next_of_kin_number'] ?>" name="phone_number">
						<img class="input-icon" src="assets/images/flag.png">
						<span class="input-icon">+234</span>
					</div>
				</div>

				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Address:</label>
					</div>
					<div class="col-sm-7">
						<input type="text" class="form-control input" required value="<?php echo @$fetch_next_of_kin_details['next_of_kin_address'] ?>" name="address">
					</div>
				</div>

				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Occupation:</label>
					</div>
					<div class="col-sm-7">
						<input type="text" class="form-control input" required value="<?php echo @$fetch_next_of_kin_details['next_of_kin_occupation']; ?>" name="occupation">
					</div>
				</div>

				<div class="form-inline row align-items-center d-none">
					<div class="col-sm-2">
						<label class="text-md">Email:</label>
					</div>
					<div class="col-sm-7">
						<input type="email" class="form-control input" value="<?php echo @$fetch_next_of_kin_details['next_of_kin_email'] ?>" name="email">
					</div>
				</div>

				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Dob:</label>
					</div>

					<div class="col-sm-7">
						<input type="text" class="form-control input" required placeholder="dd/mm/yy" value="<?php echo @$fetch_next_of_kin_details['next_of_kin_dob'] ?>" name="dob">
					</div>
					
				</div>


				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Gender:</label>
					</div>
					<div class="col-sm-7 row" style="gap:20px;">
						<div class="col-3" style="position:relative;">
							<select class="form-control select" name="gender" required>
								<option value="">Select Gender</option>
								<option <?php echo @$fetch_next_of_kin_details['next_of_kin_gender'] == "Male" ? "selected" : "" ?> value="Male">Male</option>
								<option  <?php echo @$fetch_next_of_kin_details['next_of_kin_gender'] == "Female" ? "selected" : "" ?> value="Female">Female</option>
							</select>
							<i class="ri-arrow-down-s-line select-icon text-blur"></i>
						</div>
					</div>
				</div>

				<?php if ($fetch_next_of_kin_details){ ?>
					<div class="d-flex justify-content-center">
						<button class="btn btn-main" name="update_next_of_kin_btn">Update & Continue</button>
					</div>	
				<?php }else{
					?>

						<div class="d-flex justify-content-center">
							<button class="btn btn-main" name="next_of_kin_btn">Save & Continue</button>
						</div>
				<?php
				} ?>
				
			</form>
		</div>

		<!-- TAB 3 ENDS -->

		<!-- TAB ONE STARTS-->
		<div id="tab1" class="profile-tabs profile-tabs-active">
			<form class="d-flex flex-col" method="POST" enctype="multipart/form-data" style="gap:40px;">
				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Full Name:</label>
					</div>
					<div class="col-sm-7">
						<input type="text" readonly class="form-control input" name="fullname" value="<?php 
							echo $query_fetch_user_details['fullname'];
						 ?>">
					</div>
				</div>

				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Email:</label>
					</div>
					<div class="col-sm-7">
						<input type="email" readonly class="form-control input" value="<?php echo $query_fetch_user_details['email_address']; ?>" name="email">
					</div>
				</div>

				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Phone Number:</label>
					</div>
					<div class="col-sm-7" style="position:relative;">
						<input type="text" readonly class="form-control input input-padding" value="<?php echo $query_fetch_user_details['phone_no']; ?>" name="phone_number">
						<img class="input-icon" src="assets/images/flag.png">
						<span class="input-icon">+234</span>
					</div>
				</div>

				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Photo:</label>
					</div>
					<div class="col-sm-7">
						<input type="file" class="form-control input" name="photo">
					</div>
				</div>


				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Address:</label>
					</div>
					<div class="col-sm-7">
						<input type="text" class="form-control input" value="<?php echo $query_fetch_user_details['address']; ?>" name="address">
					</div>
				</div>

			

				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Dob:</label>
					</div>
					<div class="col-sm-7">
						<input type="text" class="form-control input" required placeholder="dd/mm/yy" value="<?php echo $query_fetch_user_details['dob'] ?>" name="dob">
					</div>
				</div>


				<div class="form-inline row align-items-center">
					<div class="col-sm-2">
						<label class="text-md">Gender:</label>
					</div>
					<div class="col-sm-7 row" style="gap:20px;">
						<div class="col-3" style="position:relative;">
							<select class="form-control select" name="gender">
								<option value="">Select Gender</option>
								<option <?php echo $query_fetch_user_details['gender'] == "Male" ? "selected" : "" ?> value="Male">Male</option>
								<option  <?php echo $query_fetch_user_details['gender'] == "Female" ? "selected" : "" ?> value="Female">Female</option>
							</select>
							<i class="ri-arrow-down-s-line select-icon text-blur"></i>
						</div>
					</div>
				</div>

				<div class="d-flex justify-content-center">
					<button class="btn btn-main" name="user_update_btn">Save & Continue</button>
				</div>
			</form>
		</div>
		<!-- TAB ONE END-->
		

		<!-- TAB TWO START -->
		<div id="tab2" class="profile-tabs">
			<div class="table-responsive">
				<p class="d-flex flex-end mx-5"><a class="btn btn-primary" onclick="return confirm('You must have a minimum of ₦6,000 in your primary account before you can create a new Account. ₦3,000 is for registration fee and the system will charge ₦1,500 for the new account first week contribution while it will move the last ₦1,500 to the new Account wallet to use as second week contribution in advance.\n\n Are you sure you want to continue?')" href="add-account">Add Account</a></p>
				<table id="table" class="table">
					<thead class="thead">
						<tr>
							<th>S/N</th>
							<th>Account Id</th>
							<th>Account Type</th>
							<th>Wallet Balance</th>
							<th>Fine/Default</th>
							<th>Referral ID</th>
							<th>Total Referral</th>
							<th>Referral Within 40days</th>
							<th>Total Referral With Active Plan </th>
							<th>Created On</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody class="tbody">
				<?php  
					$query_accounts = query_user_all_account($session_logged_in_user_id);

					if (mysqli_num_rows($query_accounts)) {
						$count = 0;
						while ($fetch_user_accounts = mysqli_fetch_array($query_accounts)) {
							
							extract($fetch_user_accounts);

				////query all accounts with debt or paid
							$total_debt_contribution = getTotalDebtcontribution($user_account_id) * 1500;
							$the_users_fine = ($total_debt_contribution==0 ? "0" : "<font color='red'>₦".number_format($total_debt_contribution)."</font>");
							////query all accounts with debt or paid

								/*use this for the weekly history to next*/
								$query_debt_acct = mysqli_query($con, "SELECT * FROM thrift_transaction_details WHERE user_id = '$session_logged_in_user_id' AND  user_account_id = '$user_account_id' AND thrift_txn_counter = 'on' AND txn_status = 'active'");
							$fetch_debt_acct = mysqli_fetch_array($query_debt_acct);
							/*use this for the weekly history to next, nothing else*/
							
					////for ref within 40days starts here 
					$refWin40days = ($referral_count_within_forty_days >=5 & $referral_count>=5 & $total_referral_with_active_plan>=5) ? "<font color='green'>(can fast track)</font>" : "";
					//$refWin40days = ($referral_count_within_forty_days >=5) ? "<font color='green'>(can fast track)</font>" : (($referral_count_within_forty_days >=2 & $referral_count_within_forty_days <=4)? "<font color='red'>(can't fast track)</font>" : "");

					////for ref within 40days ends here

						?>

							<tr>
							<td><?php echo ++$count ?></td>
							<td><?php echo $user_account_token ?></td>
							<td><span class="text text-info"><?php echo $user_account_type ?></span></td>
							<td>₦<?php echo number_format($user_account_wallet_amount) ?></td>
							<td><?php echo $the_users_fine; 
							if ($the_users_fine<=0) {
								//do nothing since defaults is zero or less than
							}
							else{
								?>
								<a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to clear the whole default once? \n\n It can not be Undo and the system will charge your primary account \n\n OK to Continue');" href="clear-whole-defaults-per-account?user_token=<?php echo base64_encode($user_id); ?>&txn_id=<?php echo base64_encode($fetch_debt_acct['txn_id']); ?>">Clear All Default(s)</a>
							<?php
							}
							?> 

							</td>
							<td><?php echo $referral_id ?></td>
							<td><?php echo $referral_count ?></td>
							<td><?php echo $referral_count_within_forty_days.' '.@$refWin40days; ?></td>
							<td><?php echo $total_referral_with_active_plan ?></td>
							<td><?php echo date("D, M j, Y ",strtotime($user_account_created_at)) ?></td>
							<!-- <td><a onclick="return confirm('Are you sure you want to delete user account?')" href="delete-user-account?account_id=<?php //echo base64_encode($user_account_id) ?>" class="btn btn-danger">Delete Account</a></td> -->
							<td>
								<button type="button" data-referral-id="<?php echo $referral_id; ?>" class="btn btn-success btn-sm view-referral-btn" id="referralBtn<?php  echo $count ?>"  data-toggle="modal" data-target="#referralModal">View Downline</button>

								<a href="weekly-thrift-history?thrift_txn_id=<?php echo base64_encode($fetch_debt_acct['txn_id']); ?>&user_account_id=<?php echo base64_encode($user_account_id); ?>" class="btn btn-secondary btn-sm">Weekly History</a> 

								<a href="switch-account?acct_id=<?php echo base64_encode($user_account_id);?>&user_id=<?php echo base64_encode($session_logged_in_user_id);?>"class="btn btn-danger btn-sm">Switch to Account</a>
							</td>
							
						</tr>

					<?php
						}
					}
					

				?>
					
					</tbody>
				</table>
			</div>	
		</div>

		<!-- TAB TWO END-->

<div class="modal fade show" id="referralModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" >
			<div class="modal-dialog" role="document" style="max-width: 750px;">
			<div class="modal-content">
			  <div class="modal-header">
			    <h5 class="modal-title" style="color:black;font-weight: ;" id="exampleModalLabel">List of Referrals</h5>
			    
			  </div>
			  <div class="modal-body">
			    
			    	<div class="table-responsive" style="overflow-x: auto!important;">
			    		<table class="table">
			    			<thead>
			                  <tr>
			                    <th>S/N</th>
			                    <th>DPCMS ID</th>
			                    <th>FULLNAME</th>
			                    <th>PICTURE</th>
			                   <!--  <th>REGISTRATION DATE</th> -->
			                  </tr>
			                </thead>

			                <tbody id="modalTbody">
			                 
			              	</tbody>
			    		</table>
			    	</div>

			  </div>
			  <div class="modal-footer">
			    <button type="button" class="btn btn-secondary btn-sm" style="padding:10px 20px" data-dismiss="modal">Close</button>
			    
			  </div>
			</div>
			</div>
		</div>
	


	</div>
</div>






<script>
	
$("table").on("click","button.view-referral-btn", function(){

	var click_id = $(this).attr("id");

	var referral_id = this.dataset.referralId;

		$.ajax({
			type: "GET",
			url: "ajax-request.php",
			data:{referral_id},
			cache: false,
			beforeSend: function(){
			$(`#${click_id}`).html("Please wait...").attr("disabled",true);
			},
			success: function(data){

				
				$('#referralModal').modal({
    backdrop: 'static',
    keyboard: false
				})

				if (data == "no referral") {


					modalTbody.innerHTML = `<tr>
						<td colspan="7"><h4 class="text-danger">NO REFERRAL. THANK YOU</h4></td>
					</tr>`


				}else{

					var decodedData = JSON.parse(data);

					var mapedData =  decodedData.map( (userData,index) => {

						return(
							`<tr>
				                    <td>${index + 1}</td>
				                    <td>${userData.user_account_token}</td>	
				                    <td>${userData.fullname}</td>
				                    <td><img src="${userData.photo}" width="100" height="100" style="object-fit: cover;"></td>
				           	               
			                  </tr>`
						)

					});

					modalTbody.innerHTML = mapedData.join("");

				}
				

				 $(`#${click_id}`).html("View Referral").removeAttr("disabled",true);


			}
	})
})	

</script>
</body>
</html>