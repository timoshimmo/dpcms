<?php
include 'includes/header.php';
///////THESE ARE ACCOUNTS THAT HAVE BEEN SETTLED AND NOT ON THE USERS DASHBOARD ANYMORE.
if (isset($_GET["user_id"])) {
	$user_id = base64_decode($_GET["user_id"]);	
}else{
	header('location:log-out');
}

$fetch_users_info = query_user_details($user_id);
///sess_id


if (isset($_POST['btnsubmit'])){


	$output ="";
	$user_id = $user_id;
	$current_phone = mysqli_real_escape_string($con,$_POST['current_phone']);
	$new_members_phone = mysqli_real_escape_string($con,$_POST['new_members_phone']);
	
	////now update the phone and store the admin id of the person that updated the phone.

	////check if phone number already exist or not before updating abeg
	if (query_phone_number_exists($new_members_phone) >0) {
		$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
					<div class='alert-message'>
					<strong>Oops!!</strong> Phone Number already exist! Please use another number.
				   </div>
				   </div>";
	}
	else if (strlen($new_members_phone)<11 || strlen($new_members_phone)>11) {
		$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
					<div class='alert-message'>
					<strong>Oops!!</strong> Phone Number should be 11 digits, please.
				   </div>
				   </div>";
	}
	else{

	$query_phone_update = admin_update_members_phone($user_id,$new_members_phone);

	if ($query_phone_update) {
		////since phone updated successfully, log the id of the admin that updated the member's phone with date 
		admin_phone_number_update_log($sess_id,$user_id,$current_phone,$new_members_phone);
		$output = "<div class='alert alert-success alert-dismissible' role='alert'>
											<div class='alert-message'>
												<strong>Success!!</strong> Phone Number Updated Successfully. Wait while the system redirects you.
											</div>
										</div>";
		header("refresh:2, url=overview"); 
	}
	else{
				$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
											<div class='alert-message'>
												<strong>Oops!!</strong> An error occur, please contact the developer.
											</div>
										</div>";
		//header("refresh:3, url=manage-active-members");			
	}
	}

	
	}
?>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">
			<?php include 'includes/nav-sidebar.php'; ?>

		<div class="main">
			<?php include 'includes/top-nav-header.php'; ?>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="mb-3">
						<h1 class="h3 d-inline align-middle">Phone Number Update for <u><?php echo ucwords($fetch_users_info['fullname']); ?></u></h1>
					</div>

					<div class="row">
						<div class="col-3 col-xl-3">
						</div>
						<div class="col-12 col-xl-6">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title"><center><?php if (isset($_POST['btnsubmit'])) {
			echo $output;
		} ?></center></h5>
								</div>
								<div class="card-body" style="margin-top: -7%;">
									<form method="POST" action="">
										<div class="mb-3">
											
											<input type="email" disabled class="form-control" value="CURRENT PHONE NO - <?php echo ucwords($fetch_users_info['phone_no']); ?>">
										</div>

										
										<div class="mb-3">
											<input type="hidden" name="current_phone" value="<?php echo ucwords($fetch_users_info['phone_no']); ?>">
											<input type="number" name="new_members_phone" class="form-control" placeholder="Enter new Phone Number" required>
										</div>
										<button type="submit" class="btn btn-primary" name="btnsubmit" onclick="return confirm('Are you sure you want to update this ? \n OK to Proceed');">Update Phone</button>
									</form>
								</div>
							</div>
						</div>

					</div>

				</div>
			</main>

						<?php include 'includes/footer.php'; ?>
		</div>
	</div>

	<script src="js/app.js"></script>

</body>

</html>