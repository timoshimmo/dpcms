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


if (isset($_POST['btnsubmit_deduct_wallet'])){

	$output ="";
	$user_id = $user_id;
	$amount_to_debit = mysqli_real_escape_string($con,$_POST['wallet_amount']);
	$wallet_txn_ref = 'py_ref-'.time(); 

	/////get the user account table to select the primary account only. 
	if (mysqli_num_rows(query_user_all_account($user_id))<=0) {
		 $output = "<div class='alert alert-danger alert-dismissible' role='alert'>
					<div class='alert-message'>
						<strong>Oops!!</strong> Their is an error with the customer primary account.
					</div>
					</div>";
		 }	 
	else{
		$fetch_prim = mysqli_fetch_array(query_user_all_account($user_id));
		$current_bal = $fetch_prim['user_account_wallet_amount'];
		$wallet_user_account_id = $fetch_prim['user_account_id'];

		if ($amount_to_debit > $current_bal) {
			$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
											<div class='alert-message'>
												<strong>Oops!!</strong> Amount to deduct (₦".number_format($amount_to_debit).") is greater than the current wallet balance (₦".number_format($current_bal).")
											</div>
										</div>";
		}
		else{
			


		$debit_wallet_query =  admin_debit_members_wallet($user_id, $wallet_user_account_id, $wallet_txn_ref, "manually", $amount_to_debit,"successful",$sess_id,"debit");
	
	if ($debit_wallet_query) {
		

		$output = "<div class='alert alert-success alert-dismissible' role='alert'>
											<div class='alert-message'>
												<strong>Success!!</strong> Wallet Deducted Successfully.
											</div>
										</div>";
		header("refresh:1, url=view-single-member-wallet-transactions?user_id=".base64_encode($user_id));
	}
	else{
				$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
											<div class='alert-message'>
												<strong>Oops!!</strong> An error occur, please contact the developer.
											</div>
										</div>";
		header("refresh:3, url=manage-active-members");			
	}
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
						<h1 class="h3 d-inline align-middle">Debit Wallet for <u><?php echo ucwords($fetch_users_info['fullname']); ?></u></h1>
						<p></p>

				

						<p>Enter the amount to deduct, the system will automatically target the primary account wallet balance and deduct from it.</p>
						

					</div>

					<div class="row">
						<div class="col-3 col-xl-3">
						</div>
						<div class="col-12 col-xl-6">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title"><center><?php if (isset($_POST['btnsubmit_deduct_wallet'])) {
			echo $output;
		} ?></center></h5>
								</div>
								<div class="card-body" style="margin-top: -7%;">
									<form method="POST" action="">
										<div class="mb-3">
											
											<input type="email" disabled class="form-control" value="<?php echo strtoupper($fetch_users_info['fullname']); ?>">
										</div>
									
										
										<div class="mb-3">
										<input type="number" name="wallet_amount" class="form-control" placeholder="Enter Amount to Debit" required>
										</div>

										<button type="submit" class="btn btn-primary" name="btnsubmit_deduct_wallet" onclick="return confirm('Are you sure you want to debit the wallet ?\n\n OK to continue');">Debit Account</button>
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