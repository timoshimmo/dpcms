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


if (isset($_POST['btnsubmit_fund_wallet'])){

	$output ="";
	$user_id = $user_id;
	$wallet_user_account_id = mysqli_real_escape_string($con,$_POST['user_account_id']);
	$wallet_payment_amount = mysqli_real_escape_string($con,$_POST['wallet_amount']);
	$wallet_session_id = mysqli_real_escape_string($con,trim($_POST['wallet_session_id']));
	$wallet_txn_ref = 'py_ref-'.time(); 
	 
	$fund_wallet_query =  admin_credit_members_wallet($user_id, $wallet_user_account_id, $wallet_txn_ref, "manually", $wallet_payment_amount,"successful",$sess_id,$wallet_session_id);


	/*get the incoming user phone number to send sms*/
	$query_new_user = @query_user_details($user_id);
	////iit is returning the mysqli_fetch_Array        
	$user_fullname = $query_new_user['fullname']; 
	$new_user_phone_number = @$query_new_user['phone_no'];
	/*get the incoming user phone number to send sms*/

	
	if ($fund_wallet_query) {
			////send sms starts here
			//$sms_message = "Dear ".ucwords($user_fullname).", your Destiny Promoters account wallet has been credited with N".number_format($wallet_payment_amount).". Please access your dashboard to confirm.";
			//@noblemerry_send_sms($new_user_phone_number,$sms_message);  
			////send sms ends here

		$output = "<div class='alert alert-success alert-dismissible' role='alert'>
											<div class='alert-message'>
												<strong>Success!!</strong> Account Funded Successfully.
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
?>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">
			<?php include 'includes/nav-sidebar.php'; ?>

		<div class="main">
			<?php include 'includes/top-nav-header.php'; ?>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="mb-3">
						<h1 class="h3 d-inline align-middle">Fund Wallet for <u><?php echo ucwords($fetch_users_info['fullname']); ?></u></h1>
						<p></p>

						<h4>Is the wallet you're about to fund a virtual payment yet to reflect? Kindly <a target="_blank" href="view-single-member-wallet-transactions?user_id=<?php echo base64_encode($user_id); ?>">click here</a> to check if same amount to fund manually funded for the customer before. </h4>

						<p>Enter the <b>session ID below</b> if the payment you're about to fund is a virtual payment that hasn't reflect OR enter <b>1</b> in the session ID field if it's a normal wallet funding that are not related to virtual payment.</p>
						

					</div>

					<div class="row">
						<div class="col-3 col-xl-3">
						</div>
						<div class="col-12 col-xl-6">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title"><center><?php if (isset($_POST['btnsubmit_fund_wallet'])) {
			echo $output;
		} ?></center></h5>
								</div>
								<div class="card-body" style="margin-top: -7%;">
									<form method="POST" action="">
										<div class="mb-3">
											
											<input type="email" disabled class="form-control" value="<?php echo strtoupper($fetch_users_info['fullname']); ?>">
										</div>
										<div class="mb-3">
											<select class="form-select" name="user_account_id" required>
												<option value="" selected>Select Account</option>
								<?php
								$query_user_all_account = query_user_all_account($user_id);

								if(mysqli_num_rows($query_user_all_account) <= 0 ){

								}else{
									while($fetch_all_accounts = mysqli_fetch_array($query_user_all_account) ){
										extract($fetch_all_accounts);
										?>
										
										<option <?php echo base64_decode(@$_GET['user_account_id']) == $user_account_id ? "selected" : "" ?> value="<?php echo $user_account_id ?>"><?php echo $user_account_token ?> <?php echo "($user_account_type)" ?></option>

										<?php
									}
								} 

							?>
											</select>
										</div>
										
										<div class="mb-3">
										<input type="number" name="wallet_amount" class="form-control" placeholder="Enter Amount to Credit" required>
										</div>

										<div class="mb-3">
										<input type="text" name="wallet_session_id" class="form-control" placeholder="Enter Session ID if it's a virtual payment OR 1 as non-virtual payment" required>
										</div>
										<button type="submit" class="btn btn-primary" name="btnsubmit_fund_wallet" onclick="return confirm('Are you sure you want to fund the wallet ?\n\n OK to continue');">Fund Account</button>
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