<?php
	
	include("../includes/session.php"); 
	include("../includes/config.php"); 
	include("../includes/db-functions.php");

	if (isset($_GET['user_account_id']) && !empty($_GET['user_account_id']) && isset($_GET['user_id']) && !empty($_GET['user_id'])) {
		
		$user_id = base64_decode($_GET['user_id']);
		
		$user_account_id = base64_decode($_GET['user_account_id']);

		////////all balances whatsoever should be removed from the user primary account wallet irespective of the account trying to do clearance.
		$query_primary_account = get_only_primary_user_account_details($user_id);
		$primary_account_id = $query_primary_account['user_account_id'];
		$balance = $query_primary_account['user_account_wallet_amount'];

		//$balance = get_user_account_details($user_account_id, 'user_account_wallet_amount');

		$query_last_txn = query_users_thrift_transaction_desc($user_account_id);

		$fetch_last_thrift_txn = mysqli_fetch_array($query_last_txn);

		$txn_id = $fetch_last_thrift_txn['txn_id'];

		$thrift_fine = $fetch_last_thrift_txn['thrift_fine'] * 2;

		$deduction = $balance  - $thrift_fine;

		//give it comma, number format
		$the_thrift_fine = number_format($thrift_fine);

		if ($thrift_fine > $balance) {
			echo "<script>
				alert('Insufficient funds. Your primary account wallet is lesser than what you ought to pay');
				window.location='thrift-transaction'
			</script>";
		}
		else if ( $deduction < 0 ) {
			echo "<script>
				alert('Insufficient funds. Kindly fund your wallet to pay your debt of ₦$the_thrift_fine');
				window.location='thrift-transaction'
			</script>";
		}else{

			$update_thrift_debt_query = update_thrift_fine_debt($user_account_id, $txn_id, $deduction);

			if ($update_thrift_debt_query) { 
				update_user_balance($deduction, $primary_account_id, $con);
				submit_debt_cleared_once_payment_log($user_id,$user_account_id,$txn_id,$balance,$deduction);
				echo "<script>
					alert('Debt Cleared Successfully. You can now place your withdrawal request');
					window.location='thrift-transaction' 
				</script>";
			}
		}
	}

?>