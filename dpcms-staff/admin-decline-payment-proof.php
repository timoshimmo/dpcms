<?php
include 'includes/header.php';
if (isset($_GET["token"]) && !empty($_GET['token']) && isset($_GET['payment_proof_id']) && !empty($_GET['payment_proof_id']) && isset($_GET['user']) && !empty($_GET['user'])) {
	$admin_id = base64_decode($_GET["token"]);	
	$payment_proof_id = base64_decode($_GET["payment_proof_id"]);	
	$user_id = base64_decode($_GET["user"]);	

}else{
	header('location:log-out');
}

///sess_id
$fetch_users_info = query_user_details($user_id);


if (isset($_POST['btnsubmit_reason'])){

	$output ="";
	$user_id = $user_id;
	$cancelled_by = $admin_id;
	$reason = mysqli_real_escape_string($con,$_POST['decline_payment_proof_reasons']);

	$update_proof_cancel = admin_update_reg_payment_proof($payment_proof_id, "cancelled", $reason);

	/*get the incoming user phone number to send sms*/
	////iit is returning the mysqli_fetch_Array        
	$user_fullname = $fetch_users_info['fullname']; 
	$new_user_phone_number = @$fetch_users_info['phone_no'];
	/*get the incoming user phone number to send sms*/

	
	if ($update_proof_cancel) {
		////since successful, update the id of the admin that decline payment. 
		admin_log_who_cancelled_payment_proof($admin_id,$payment_proof_id); 
			////send sms starts here
			$sms_message = "Dear ".ucwords($user_fullname).", your Destiny Promoters registration payment receipt was declined and cancelled. Please call the customer care.";
			@noblemerry_send_sms($new_user_phone_number,$sms_message);  
			////send sms ends here

		$output = "<div class='alert alert-success alert-dismissible' role='alert'>
											<div class='alert-message'>
												<strong>Success!!</strong> Payment Declined Successfully.
											</div>
										</div>";
		header("refresh:3, url=payment-proof-reg");			
	}
	else{
				$output = "<div class='alert alert-danger alert-dismissible' role='alert'>
											<div class='alert-message'>
												<strong>Oops!!</strong> An error occur, please contact the developer.
											</div>
										</div>";
		header("refresh:3, url=payment-proof-reg");			
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
						<h1 class="h3 d-inline align-middle">Decline Payment Proof for <u><?php echo ucwords($fetch_users_info['fullname']); ?></u></h1>
					</div>

					<div class="row">
						<div class="col-3 col-xl-3">
						</div>
						<div class="col-12 col-xl-6">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title"><center><?php if (isset($_POST['btnsubmit_reason'])) {
			echo $output;
		} ?></center></h5>
								</div>
								<div class="card-body" style="margin-top: -7%;">
									<form method="POST" action="">
										<div class="mb-3">
											
											<input type="email" disabled class="form-control" value="<?php echo strtoupper($fetch_users_info['fullname']); ?>">
										</div>

										
										<div class="mb-3">
										<textarea class="form-control" name="decline_payment_proof_reasons" placeholder="Enter the Payment proof decline reasons...... Please use of symbols and quotes not allowed" cols="7" rows="10" style="resize: none;" required></textarea>
										</div>
										<button type="submit" class="btn btn-danger" name="btnsubmit_reason">Decline Payment</button>
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