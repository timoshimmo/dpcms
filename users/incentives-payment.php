<?php
	
	include("../includes/session.php"); 
	include("../includes/config.php"); 
	include("../includes/db-functions.php");

	if (isset($_GET['user_account_id']) && !empty($_GET['user_account_id']) && isset($_GET['user_id']) && !empty($_GET['user_id'])) {
		
		$user_id = base64_decode($_GET['user_id']);

		$user_account_id = base64_decode($_GET['user_account_id']);

		////////all balances whatsoever should be removed from the user primary account wallet inrespective of the account trying to do clearance.
		$query_primary_account = get_only_primary_user_account_details($user_id);
		$primary_account_id = $query_primary_account['user_account_id'];
		$balance = $query_primary_account['user_account_wallet_amount'];

		//$balance = get_user_account_details($user_account_id, 'user_account_wallet_amount');

		$query_last_txn = query_users_thrift_transaction_desc($user_account_id);

		$fetch_last_thrift_txn = mysqli_fetch_array($query_last_txn);

		$txn_id = $fetch_last_thrift_txn['txn_id'];

		$incentive_price = $fetch_last_thrift_txn['incentive_amount'];

		$deduction = $balance  - $incentive_price;

		if ($incentive_price > $balance) {
			echo "<script>
				alert('Insufficient funds. Your primary account wallet is lesser than what you ought to pay');
			</script>";
			header("refresh:1, url=thrift-transaction");
		}
		else if ( $deduction < 0 ) {
			echo "<script>
				alert('Insufficient funds. Kindly fund your wallet to pay.');
			</script>";
			header("refresh:1, url=thrift-transaction");
		}else{

			$update_thrift_debt_query = set_thrift_incentives_status( $txn_id, $deduction, $user_account_id, "paid" );

			if ($update_thrift_debt_query) {
				update_user_balance($deduction, $primary_account_id, $con);
				submit_incentives_payment_log($user_id,$user_account_id,$txn_id,$balance,$deduction);
				echo "<script>
					alert('Incentives waybill payment has been successfully cleared. You can now place your withdrawal request');
				</script>";
				header("refresh:1, url=thrift-transaction");

			}
		}
	}

?>