<?php
ob_start();
	include("../includes/session.php"); 
	include("../includes/config.php"); 
	include("../includes/db-functions.php");

	if (isset($_GET['user_token']) && !empty($_GET['user_token']) && isset($_GET['txn_id']) && !empty($_GET['txn_id'])) {
		
		$user_id = base64_decode($_GET['user_token']);
		
		$txn_id = base64_decode($_GET['txn_id']);

		////////all balances whatsoever should be removed from the user primary account wallet irespective of the account trying to clear whole defaults once.
		$query_primary_account = get_only_primary_user_account_details($user_id);
		$primary_account_id = $query_primary_account['user_account_id'];
		$balance = $query_primary_account['user_account_wallet_amount'];
		//echo $balance;


		/////get the thrift transaction details & status with txnid to get the thrift fine
		////and to get some other details. 
		$query_thrift_fine = query_thrift_status($txn_id, 'thrift_fine');
		$query_thrift_start_date = query_thrift_status($txn_id, 'thrift_start_date');
		$user_account_id = query_thrift_status($txn_id, 'user_account_id');

		/////now that we've gotten the thrift fine using txn id, 
		//multiply thrift fine by 2
		$thrift_fine = $query_thrift_fine * 2;
		
		$deduction = $balance  - $thrift_fine;

		//give it comma, number format
		$the_thrift_fine = number_format($thrift_fine);

		if ($thrift_fine<=0) {
			echo "<script>
				alert('You do not have any Defaults to clear on this account. Thank you');
				window.location='profile'
			</script>";	
		}
		else if ($thrift_fine > $balance) {
			echo "<script>
				alert('Insufficient funds. Your primary account wallet balance is lesser than ₦$the_thrift_fine that you ought to pay, you will be redirected back to the profile page.');
				window.location='profile'
			</script>";
		}
		else if ( $deduction < 0 ) {
			echo "<script>
				alert('Insufficient funds. Kindly fund your wallet to pay your debt of ₦$the_thrift_fine');
				window.location='profile'
			</script>";
		}else{
			
			$update_thrift_debt_query = update_thrift_fine_debt($user_account_id, $txn_id, $deduction);

			if ($update_thrift_debt_query) { 
				update_user_balance($deduction, $primary_account_id, $con);
				submit_debt_cleared_once_payment_log($user_id,$user_account_id,$txn_id,$balance,$deduction);
				echo "<script>
					alert('All Defaults on the accounts Cleared Successfully. Thank you for choosing Destiny Promoters .');
					window.location='profile' 
				</script>";
			}
		}
	}

?>