<?php
include 'includes/header.php';

if (isset($_POST['btnsubmit'])){

	$output ="";	
	$customer_phone = mysqli_real_escape_string($con,$_POST['customer_phone']);
	$payment_session_id = mysqli_real_escape_string($con,$_POST['payment_session_id']);

	/////CHECK PHONE NUMBER EXIST & THE USER STATUS IS ACTIVE
	$query_phone_status = check_phone_exist_with_acct_active($customer_phone);
	////check if phone number already exist
	if (strlen($payment_session_id)<30 || strlen($payment_session_id)>30) {
		$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
					<div class='alert-message'>
					<strong>Error:</strong> The session ID is not VALID.
				   </div>
				   </div>";
	}
	else if (mysqli_num_rows($query_phone_status) <=0) {
		$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
					<div class='alert-message'>
					<strong>Oops!!</strong> Phone Number does not exist! Please check the number and try again.
				   </div>
				   </div>";
	}
	else{
		$fetch_customer_details = mysqli_fetch_array($query_phone_status);
		extract($fetch_customer_details);
		///proceed to check virtual transactions for the person if the person get b4 or not. 
		if (mysqli_num_rows(query_user_virtual_bank_details($user_id))<=0) {
			// since the virtual doesn't exist for the person. stop here. else, proceed
			$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
					<div class='alert-message'>
					<strong>Oops!!</strong> The customer does not have a virtual account yet, please.
				   </div>
				   </div>";
		}
		else{
			///fetch the virtual acct details and let's move.
			$fetch_virtual = mysqli_fetch_array(query_user_virtual_bank_details($user_id));
			$fetched_tx_ref = $fetch_virtual['virtual_account_tx_ref'];

			//////check if the session ID also on wallet transactions. FUND MANUALLY BEFORE
			$query_wallet_sess_id = check_session_id_wallet_transaction($user_id,$payment_session_id);

			///now that we fetch, let's proceed to check if the session ID exist from webhook
			///using the tx_ref which is the customer ID
			$check_sess_exist = virtual_check_sessionid_exist($payment_session_id,$fetched_tx_ref);
			
			if (mysqli_num_rows($check_sess_exist)>0) {
				// it exist, do something. and redirect to page to see virtual transactions
				$output = "<div class='alert alert-success alert-dismissible' role='alert'>
					<div class='alert-message'>
					The payment with this session ID already reflected on the customer's virtual transactions and wallet. Click <a href='view-single-member-virtual-transactions?user_id=".base64_encode($user_id)."'>here<a> to manage all the virtual transactions for the customer. 
				   </div>
				   </div>";
			}
			else if (mysqli_num_rows($query_wallet_sess_id)>0) {
				// /The session ID for the customer already exists and payment FUNDED before
				////MANUAL PAYMENT SECTION
				$output = "<div class='alert alert-success alert-dismissible' role='alert'>
					<div class='alert-message'>
					The session ID for the customer already exists and the payment manually funded for the customer before. Click <a href='view-single-member-wallet-transactions?user_id=".base64_encode($user_id)."'>here<a> to manage all wallet funding transactions for the customer.  
				   </div>
				   </div>";
			}
			else{
				///it does not exist
				////now let the system redirects you to the manual wallet funding page to 
				///credit the user , but enter the session id for that transactions, please. 
				$output = "<div class='alert alert-success alert-dismissible' role='alert'>
					<div class='alert-message'>
					<strong>Valid!!</strong> The system will redirect you to the manual wallet funding page in 3secs. 
				   </div>
				   </div>";
				 header("refresh:3,url=admin-credit-members-wallet?user_id=".base64_encode($user_id));
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
						<h1 class="h3 d-inline align-middle">Virtual Account Session ID Validation</h1>
						<p>This is a page where you can validate any virtual payment transactions with its session ID to confirm if the payment was successful, not credited or already reflected on the users virtual transactions.</p>

						<p>ALWAYS ASK FOR STATEMENT FOR THE PARTICULAR TRANSACTION (IF NEEDED), WELL-DETAILED PAYMENT RECEIPT THAT HAS SESSION ID</p>

						<p>After checking for the payment status and the session ID do exist with us but not yet credited before, the system will redirect you to fund the customer's wallet manually and you will also need to provide the session ID while funding the wallet.</p>
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
											
											<input type="text" name="customer_phone" class="form-control" required placeholder="ENTER THE CUSTOMER VALID PHONE NO" value="<?php echo @$customer_phone; ?>">
										</div>

										<div class="mb-3">
											<input type="text" name="payment_session_id" class="form-control" placeholder="ENTER THE PAYMENT SESSION ID" required value="<?php echo @$payment_session_id; ?>">
										</div>
										<button type="submit" class="btn btn-primary" name="btnsubmit" onclick="return confirm('Are you sure you want to check the status ? \n OK to Proceed');">Check Status</button>
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