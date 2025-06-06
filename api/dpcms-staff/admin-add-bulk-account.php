<?php
include 'includes/header.php';
///////THESE ARE ACCOUNTS THAT HAVE BEEN SETTLED AND NOT ON THE USERS DASHBOARD ANYMORE.
if (isset($_GET["token"])) {
	$user_id = base64_decode($_GET["token"]);	


	/*get to know if the user still has an account yet to be paid*/
	$pending_r_w = admin_query_if_user_has_pending_acct_withdraw_request($user_id,true);
	/*bcus system will add thrift for those accounts too. if they're still pending*/
}else{
	header('location:log-out');
	//echo "fdjfj";
}

$fetch_users_info = query_user_details($user_id);
$user_account_id = $fetch_users_info['user_account_id'];
///sess_id


if (isset($_POST['btnsubmit_create_account'])){

	$output ="";
	$user_id = $user_id;


//echo "ddjdjd";
	/*	$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
											<div class='alert-message'>
												<strong>Oops!!</strong> An error occur, please contact the developer.
											</div>
										</div>";*/

	
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
						<h1 class="h3 d-inline align-middle">Add Bulk Account for <u><?php echo ucwords($fetch_users_info['fullname']); ?></u></h1>
					</div>

					<div class="row">
						<div class="col-3 col-xl-3">
						</div>
						<div class="col-12 col-xl-6">
							<div class="card">
								
				<?php
		if ($pending_r_w > 0) {
			echo "<h2 class='text-danger'>Can NOT Create Account</h2>";
			echo "<br>";
			echo "<h5 class='text-danger'>This user is having <b>".$pending_r_w."</b> account(s) that requested for withdrawal and is yet to be paid.</h5>";
			echo "<br>";
			echo "<h5 class='text-danger'>Please approve the accounts as <b>PAID</b> from the request withdrawal page and come back here to create account.</h5>";
		}
		else if (@query_generated_users_with_thrift_inactive($user_id, true)>0) {
			echo "<h3 style='color:red'>You can not add bulk account at the moment</h3>";
			echo "<h3 style='color:red'>The user has pending account yet to be assign thrift</h3>";
			echo "<h3 style='color:red'>Click <a href='admin-assign-thrift-to-bulk-account?token=".base64_encode($user_id)."'>here</a> to assign thrift.</h3>";
		}
		else{

			?>
		<div class="card-header">
									<h5 class="card-title"><center><?php if (isset($_POST['btnsubmit_create_account'])) {
			echo $output;
		} ?></center></h5>
								</div>
								<div class="card-body" style="margin-top: -7%;">
									<h3 style="text-align: center;">Input Number of Bulk Account</h3>
									<p style="color: red;">N.B: This process might take a little time and you can only add <b>100 accounts</b> at once.</p>

									<p style="color: red;">Once accounts added successfully, go to assign thrift to assign a contribution to the account just added before you can add new account.</p>


									<form method="POST" 
									class="form_submit_bulk_acct" action="">
										<div class="mb-3">
											
											<input type="email" disabled class="form-control" value="<?php echo ucwords($fetch_users_info['fullname']); ?>">
										</div>

										<div class="mb-3">
											
											<input type="number" class="form-control" name="total_account" id="totalNumberOfAccount" placeholder="Input Number of Account to Add" required>
										</div>

										<div class="mb-3">
											<input type="hidden" name="fund_wallet_amount" min="1000" id="amountToFundAccount" required  value="3000" class="form-control accountregfee">

											<input type="hidden" class="admin_token" value="<?php echo $sess_id; ?>">
											<input type="hidden" class="usertoken" value="<?php echo $user_id; ?>">
											<input type="hidden" class="user_acct_token" value="<?php echo $user_account_id; ?>">

										</div>

										<div class="mb-3">
											
											<input type="number" class="form-control" id="totalAmount" name="wallet_amt_for_bulk_acct" placeholder="Total wallet amount Needed to create account." disabled>
										</div>

										<button type="submit" class="btn btn-danger btnsubmit_create_account" name="btnsubmit_create_account">Add Bulk Account</button>
									</form>
								</div>
								<?php
							}
								?>
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

<script>
		totalNumberOfAccount.oninput = function() {
		if(amountToFundAccount.value){
			totalAmount.value = this.value * amountToFundAccount.value
		}/*else{
			totalAmount.value = this.value;
		}*/
	}


	amountToFundAccount.oninput = function() {
		if(totalNumberOfAccount.value){
			totalAmount.value = this.value * totalNumberOfAccount.value
		}else{
			totalAmount.value = this.value;
		}
	}

</script>