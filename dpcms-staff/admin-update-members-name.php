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
	$current_full_name = mysqli_real_escape_string($con,$_POST['current_full_name']);
	$new_members_name = mysqli_real_escape_string($con,$_POST['new_members_name']);
	
	////now update the name and store the admin id of the person that updated the name.

	 
	$query_name_update = admin_update_members_name($user_id,$new_members_name);

	if ($query_name_update) {
		////since name updated successfully, log the id of the admin that updated the member's name with date 
		admin_name_update_log($sess_id,$user_id,$current_full_name,$new_members_name);
		$output = "<div class='alert alert-success alert-dismissible' role='alert'>
											<div class='alert-message'>
												<strong>Success!!</strong> Name Updated Successfully. Wait while the system redirects you.
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
?>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">
			<?php include 'includes/nav-sidebar.php'; ?>

		<div class="main">
			<?php include 'includes/top-nav-header.php'; ?>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="mb-3">
						<h1 class="h3 d-inline align-middle">Full Name Update for <u><?php echo ucwords($fetch_users_info['fullname']); ?></u></h1>
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
											
											<input type="email" disabled class="form-control" value="CURRENT NAME - <?php echo ucwords($fetch_users_info['fullname']); ?>">
										</div>

										
										<div class="mb-3">
											<input type="hidden" name="current_full_name" value="<?php echo ucwords($fetch_users_info['fullname']); ?>">
											<input type="text" name="new_members_name" class="form-control" placeholder="Enter new name to edit to" required>
										</div>
										<button type="submit" class="btn btn-primary" name="btnsubmit">Update Name</button>
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