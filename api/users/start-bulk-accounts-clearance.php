<?php
	
	include("../includes/session.php"); 
	include("../includes/config.php"); 
	include("../includes/db-functions.php");

	if (isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['total_clearance']) && !empty($_GET['total_clearance'])) {
		$user_id = base64_decode($_GET['user_id']);
		$total_clearance = base64_decode($_GET['total_clearance']);

		//echo $total_clearance;
		$output = "";
		$cnt = 1;
	$query_clearance = mysqli_query($con,"SELECT * FROM completed_thrift_50weeks_account JOIN thrift_transaction_details ON completed_thrift_50weeks_account.txn_id = thrift_transaction_details.txn_id JOIN user_account ON user_account.user_account_id = completed_thrift_50weeks_account.user_account_id WHERE thrift_transaction_details.txn_status = 'completed' AND thrift_transaction_details.thrift_processing_fee = 'pending' AND completed_thrift_50weeks_account.user_id = '$user_id' ORDER by thrift_transaction_details.txn_id ASC");

	$count_clearance = mysqli_num_rows($query_clearance); 
		if ($count_clearance!=$total_clearance) {
			echo "<script>
					alert('Total bulk account for clearance does not match. Please go back to the dashboard to start the clearance one after the other.');
					window.location.href='account-due-for-clearance';
				</script>";
				
		}
		else{
			$query_primary_account = get_only_primary_user_account_details($user_id);
		$primary_account_id = $query_primary_account['user_account_id'];
		$balance = $query_primary_account['user_account_wallet_amount'];
			while ($fetch_clearance_acct = mysqli_fetch_array($query_clearance)) {
				extract($fetch_clearance_acct);
				
		////////all balances whatsoever should be removed from the user primary account wallet inrespective of the account trying to do clearance.
		

		//echo $balance;		
		$total_deduction = $query_primary_account['user_account_wallet_amount'] - 2000 * $count_clearance;
		$deduction = $balance  -= 2000;

		if ( $total_deduction < 0 ) {
			$output = "Insufficient funds. Kindly fund your wallet to be able to pay for the clearance fee";
			header("refresh:1, url=account-due-for-clearance");
		}
		else{
			//////avoid user not paying clearance fee twice
			$query_duplicate_clearance = mysqli_query($con, "SELECT * FROM thrift_transaction_details WHERE txn_id = '$txn_id' AND thrift_processing_fee = 'paid'");
			if (mysqli_num_rows($query_duplicate_clearance)>0) {
				// do nothing that to alert
				$output = "Clearance fee already paid. Thank you!!";
				header("refresh:1, url=account-due-for-clearance");
			}
			else{

			/*$update_processing_fee_query = create_user_processing_fee($deduction, $user_account_id, $txn_id );*/
			///select incentives as self-pick automatically for all txn id please.
			$update_processing_fee_query = mysqli_query($con, "UPDATE thrift_transaction_details SET thrift_processing_fee = 'paid', incentive_type = 'Foodstuff', incentive_pickup_type = 'Self-pick', incentive_delivery_status = 'paid', is_bulk_clearance = 'yes' WHERE txn_id = '$txn_id' ") or die(mysqli_error($con));

			if ($update_processing_fee_query) { 
				update_user_balance($total_deduction, $primary_account_id, $con);
				submit_clearance_fee_log($user_id,$user_account_id,$txn_id,$balance,$deduction);
				$output = "Your clearance fee has been successfully settled";
				header("refresh:1, url=bulk-account-requests-withdrawal");
			}
			}
		}

		

		}
		echo "<script>alert(".$output.")</script>";
		}
	}