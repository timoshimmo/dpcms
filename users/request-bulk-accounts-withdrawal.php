<?php
	
	include("../includes/session.php"); 
	include("../includes/config.php"); 
	include("../includes/db-functions.php");

	if (isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['total_clearance']) && !empty($_GET['total_clearance'])) {
		$user_id = base64_decode($_GET['user_id']);
		$total_clearance = base64_decode($_GET['total_clearance']);

	$output = "";
	$cnt = 1;
	$query_clearance = mysqli_query($con,"SELECT * FROM thrift_transaction_details JOIN user_account ON user_account.user_account_id = thrift_transaction_details.user_account_id WHERE thrift_transaction_details.txn_status = 'completed' AND thrift_transaction_details.is_bulk_clearance ='yes' AND thrift_transaction_details.thrift_processing_fee = 'paid' AND thrift_transaction_details.incentive_delivery_status = 'paid' AND thrift_transaction_details.user_id = '$user_id' ORDER by txn_id ASC");

	$count_clearance = mysqli_num_rows($query_clearance); 

	if ( mysqli_num_rows(query_user_bank_details($user_id)) == 0) {
			echo "<script>
				alert('Kindly add bank account details before requesting for withdrawal');
				window.location='add-bank-account';
			</script>";
		}
	else{

	while ($fetch_request_withdrawal = mysqli_fetch_array($query_clearance)) {
		extract($fetch_request_withdrawal);

		//avoid duplicate withdrawal request for a single transaction.
		$check_withdrawal = mysqli_num_rows( check_request_withdraw_exist($txn_id));
		if ($check_withdrawal > 0) {
			// if > 0, means withdrawal request already sent for such txn
			///do nothing but alert
			echo "<script>
						alert('Withdrawal request already placed');
					</script>";
				header("refresh:1, url=list-of-acct-for-next-settlement?settlement=new");
		}
		else{
			/////request for ther withdrawal
			$insert_withdrawal = request_withdraw($user_id, $user_account_id, $txn_id, $thrift_package_id,"65000");
			$update_the_clearance = mysqli_query($con,"UPDATE thrift_transaction_details SET is_bulk_clearance = 'cleared' WHERE txn_id = '$txn_id' AND user_id = '$user_id'");
			$output = "Your request for withdrawal has been successfully submitted. Admin would review it and credit the bank account details attached to your Destiny Promoters Profile";
		}
	
	}
	if ($insert_withdrawal) {
		echo "<script> 
						alert('Your request for withdrawal has been successfully submitted. Admin would review it and credit the bank account details attached to your Destiny Promoters Profile');
					</script>";
		header("refresh:1, url=list-of-acct-for-next-settlement?settlement=new");

	}


	
	}
	}