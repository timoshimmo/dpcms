<?php
include 'includes/header.php';

if (isset($_POST['btnsubmit'])){

	$output ="";	
	$customer_virtual = mysqli_real_escape_string($con,$_POST['customer_virtual']);
	if (strlen($customer_virtual)<10 || strlen($customer_virtual)>10) {
		$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
					<div class='alert-message'>
					<strong>Oops!!</strong> Virtual account must be 10 digits. 
				   </div>
				   </div>";
	}
	else{
		if (mysqli_num_rows(admin_check_virtual_with_account_no($customer_virtual))<=0) {
			$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
					<div class='alert-message'>
					<strong>Oops!!</strong> Virtual account doesn't exist. Please re-check the virtual and try again. 
				   </div>
				   </div>";
		}
		else{
			$fetch = mysqli_fetch_array(admin_check_virtual_with_account_no($customer_virtual));
			extract($fetch);
			$output = "<div class='alert alert-success alert-dismissible' role='alert'>
					<div class='alert-message'>
					<strong>Success!!</strong> The system will redirect you in 2secs to view more details about the customer that owns the virtual account.
				   </div>
				   </div>";
				 header("refresh:3,url=admin-check-profile-with-virtual?user_id=".base64_encode($user_id));
			///admin-check-profile-with-virtual.php
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
						<h1 class="h3 d-inline align-middle">Check Profile with Virtual Account Number</h1><p></p>
						<p>Enter the virtual account number to check for a customer profile like (Name, Phone Number, Address etc)</p>

						<p>You can only check with a valid virtual account number on the customer's profile only.</p>
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
										<input type="number" name="customer_virtual" class="form-control" required placeholder="ENTER THE VALID VIRTUAL ACCOUNT NUMBER" value="<?php echo @$customer_virtual; ?>">
										</div>

										<button type="submit" class="btn btn-primary" name="btnsubmit" onclick="return confirm('Are you sure you want to check the virtual account ? \n OK to Proceed');">Search Profile</button>
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