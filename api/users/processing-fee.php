<?php
	
	include("../includes/session.php"); 
	include("../includes/config.php"); 
	include("../includes/db-functions.php");

	if (isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['thrift_txn_id']) && !empty($_GET['thrift_txn_id'])  && isset($_GET['thrift_txn_id']) && !empty($_GET['thrift_txn_id']) ) {
		
		$user_id = base64_decode($_GET['user_id']);

		$user_account_id = base64_decode($_GET['user_account_id']);


		$thrift_txn_id = base64_decode($_GET['thrift_txn_id']);		

		////////all balances whatsoever should be removed from the user primary account wallet inrespective of the account trying to do clearance.
		$query_primary_account = get_only_primary_user_account_details($user_id);
		$primary_account_id = $query_primary_account['user_account_id'];
		$balance = $query_primary_account['user_account_wallet_amount'];

		//$balance = get_user_account_details($user_account_id, 'user_account_wallet_amount');


		$deduction = $balance  - 2500;

		if ( $deduction < 0 ) {
			echo "<script>
				alert('Insufficient funds. Kindly fund your wallet to be able to pay for the clearance fee');
			</script>";
			header("refresh:1, url=thrift-transaction");
		}else{
			//////avoid user not paying clearance fee twice
			$query_duplicate_clearance = mysqli_query($con, "SELECT * FROM thrift_transaction_details WHERE txn_id = '$thrift_txn_id' AND thrift_processing_fee = 'paid'");
			if (mysqli_num_rows($query_duplicate_clearance)>0) {
				// do nothing that to alert
				echo "<script>
					alert('Clearance fee already paid. Please proceed to choose incentives or request for withdrawal.');
				</script>";
				header("refresh:1, url=thrift-transaction");
			}
			else{

			$update_processing_fee_query = create_user_processing_fee($deduction, $user_account_id, $thrift_txn_id );

			if ($update_processing_fee_query) { 
				update_user_balance($deduction, $primary_account_id, $con);
				submit_clearance_fee_log($user_id,$user_account_id,$thrift_txn_id,$balance,$deduction);
				echo "<script>
					alert('Your clearance fee has been successfully settled.');
				</script>";
				header("refresh:1, url=thrift-transaction");
			}
			}
		}
	}

?>