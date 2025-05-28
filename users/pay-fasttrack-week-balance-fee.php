<?php
	
	include("../includes/session.php"); 
	include("../includes/config.php"); 
	include("../includes/db-functions.php");

	if (isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['thrift_txn_id']) && !empty($_GET['thrift_txn_id'])  && isset($_GET['thrift_txn_id']) && !empty($_GET['thrift_txn_id']) && isset($_GET['fasttrack_bal_to_pay']) && !empty($_GET['fasttrack_bal_to_pay'])) {
		
		$user_id = base64_decode($_GET['user_id']);

		$user_account_id = base64_decode($_GET['user_account_id']);


		$thrift_txn_id = base64_decode($_GET['thrift_txn_id']);		

		$fasttrack_bal_to_pay = base64_decode($_GET['fasttrack_bal_to_pay']);		


		////////all balances whatsoever should be removed from the user primary account wallet inrespective of the account trying to do clearance.
		$query_primary_account = get_only_primary_user_account_details($user_id);
		$primary_account_id = $query_primary_account['user_account_id'];
		$balance = $query_primary_account['user_account_wallet_amount'];

		//$balance = get_user_account_details($user_account_id, 'user_account_wallet_amount');


		$deduction = $balance  - $fasttrack_bal_to_pay;

		if ($fasttrack_bal_to_pay > $balance) {
			echo "<script>
				alert('Insufficient funds. Your primary account wallet is lesser than what you ought to pay');
				window.location='dashboard'
			</script>";
		}
		else if ( $deduction < 0 ) {
			echo "<script>
				alert('Insufficient funds. Kindly fund your wallet to be able to pay for the fee');
				window.location='dashboard'
			</script>";
		}else{
			////check if the txn id of that account status not in paid to avoid duplicate payment.
			$query_duplicate = mysqli_query($con,"SELECT * FROM fast_track_weeks_balance WHERE user_id = '$user_id' AND user_account_id = '$user_account_id' AND txn_id = '$thrift_txn_id' AND fasttrack_weeks_pending_balance = 'paid'");
			if (mysqli_num_rows($query_duplicate)>0) {
				// this shows it exist and user already paid for that fast track before. 
				///ignore and avoid user paying such money again
				echo "<script> 
					alert('You already paid for the fast track uncovered contribution balance payment.');
				</script>";
				header("refresh:1, url=thrift-transaction");
			}
			else{

			$query_pay_fasttrack_bal = mysqli_query($con,"UPDATE fast_track_weeks_balance SET fasttrack_weeks_pending_balance = 'paid' WHERE user_id = '$user_id' AND user_account_id = '$user_account_id' AND txn_id = '$thrift_txn_id'");

			if ($query_pay_fasttrack_bal) {  
				update_user_balance($deduction, $primary_account_id, $con);
				submit_fasttrack_balance_payment_log($user_id,$user_account_id,$thrift_txn_id,$balance,$deduction);
				echo "<script> 
					alert('Your fast track uncovered contribution balance payment has been successfully paid.');
				</script>";
				header("refresh:1, url=thrift-transaction");
				}
			}
		}
	}

?>