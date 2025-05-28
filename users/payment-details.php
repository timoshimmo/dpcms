<?php include "sidebar.php" ?>
<?php 

	/*CHECKS FOR USER EXISTENCE IN DATABSE*/
	$count_user_existence = mysqli_num_rows( query_user_all_account(base64_decode($_GET['payer'])));

	/*CHECKS IF USER EXIST and TRANSACTION STATUS IS SUCCESSFUL*/
	if(isset($_GET['status'], $_GET['transaction_id'], $_GET['tx_ref']) && ($_GET['status'] == "successful") && ($count_user_existence > 0) && !empty($_GET['tx_ref']) && !empty($_GET['transaction_id']) ){
		
		$wallet_txn_ref = base64_decode($_GET['tx_ref']);

		$wallet_txn_status = $_GET['status'];                          

		$wallet_payment_txn_id = $_GET['transaction_id']; 

		$wallet_user_account_id =  base64_decode($_GET['account_id']);

		$user_id = base64_decode($_GET['payer']);

		include '../includes/call-api.php';
		$verify_transactionid = flutterwave_payment_transaction_verification_with_txnid($wallet_payment_txn_id);
		//print_r($verify_transactionid); 
		if ($verify_transactionid['status']==='success' && $verify_transactionid['data']['status']==='successful') {
			///i need to get the real amount fee coming from flutterwave cus user can change payment fee.
			$wallet_payment_amount = isset($_GET['type']) ? 0 : $verify_transactionid['data']['amount'];
			//echo "SUCCESSFUL KZ";
			//echo $verify_transactionid['data']['amount'];
		if ( check_wallet_transaction_exist($wallet_payment_txn_id) == 0 ) {
			
			$fund_wallet_query =  fund_wallet_creation($user_id, $wallet_user_account_id, $wallet_txn_ref, $wallet_payment_txn_id, $wallet_payment_amount,$wallet_txn_status);

			if( isset($_GET['type']) ){ 
				confirm_user_registration_payment($user_id);

				/////////////add the user id to a table to process virtual account.
				$query_insert_virtual = mysqli_query($con,"SELECT * FROM cron_bot_virtual_creation WHERE user_id = '$user_id'");
				if (mysqli_num_rows($query_insert_virtual)>0) {
					/////do nothing since the id already exist
				}
				else{
					////insert it, it never exist b4 be that
					$insert_virtual = mysqli_query($con,"INSERT into cron_bot_virtual_creation SET
						user_id = '$user_id', 
						account_id = '$wallet_user_account_id',
						transaction_id = '$wallet_payment_txn_id',
						tx_ref = '$wallet_txn_ref',
						status = '$wallet_txn_status'
					") or die(mysqli_error($con));
				}

			}
		}

		}
		else{ 
			echo "<script>alert('Transaction was unsuccessful, good day');
			window.location='logout';
			</script>";
		}


	}else{
		  echo "<script>alert('Payment reference not found. Your payment is invalid');
		  window.location='logout';
		  </script>";
	}
?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body pt-5">
		<h3 class="text-md-bold my-4"><?php echo isset($_GET['type']) ? "Registration" :  "Funding Wallet" ?> Payment Details</h3>
		<!-- <h5 class="text-sm-bold subtitle text-blur">Please confirm the Amount to fund & Information details below before you continue</h5> -->

		<?php
			if ($query_fetch_user_details['is_acct_active'] == 'no' ){
		?>
			
			<div class="alert alert-danger mt-0">
				<strong>N.B:</strong> Your Account is Not Active yet. Kindly go to your profile section to add your Next of Kin Info.
			</div>

		<?php
			}
		?>

		<div class="mt-5 investment-details-wrapper py-5">
			<form class="d-flex flex-col space-mobile" action="" method="POST" style="gap:40px;">
				<div class="form-inline row align-items-center">
					<div class="col-sm-4">
						<label class="text-md-bold">Payment Ref:</label>
					</div>
					<div class="col-sm-8">
						<?php echo @$wallet_txn_ref ?>
					</div>
				</div>


				<div class="form-inline row align-items-center">
					<div class="col-sm-4">
						<label class="text-md-bold">Payment For Account:</label>
					</div>
					<div class="col-sm-8">
						<?php echo @get_user_account_details($wallet_user_account_id, "user_account_token"); ?>
					</div>
				</div>


				<div class="form-inline row align-items-center">
					<div class="col-sm-4">
						<label class="text-md-bold">Payment Amount:</label>
					</div>
					<div class="col-sm-8">
						<?php echo isset($_GET['type']) ? 3000: number_format(@$wallet_payment_amount) ?>
					</div>
				</div>

				<div class="form-inline row align-items-center">
					<div class="col-sm-4">
						<label class="text-md-bold">Payment Status:</label>
					</div>
					<div class="col-sm-8">
						<?php echo @$wallet_txn_status?>
					</div>
				</div>
			
			</form>
		</div>

	</div>

</div>
</body>
</html>