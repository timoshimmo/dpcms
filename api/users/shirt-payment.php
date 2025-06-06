<?php
	
	include("../includes/session.php"); 
	include("../includes/config.php"); 
	include("../includes/db-functions.php");

	if (isset($_GET['user_account_id']) && !empty($_GET['user_account_id']) && isset($_GET['user_id']) && !empty($_GET['user_id']) ) {
		
		$user_account_id = base64_decode($_GET['user_account_id']);

		$user_id = base64_decode($_GET['user_id']);

		/////we need the primary acct alone to be able to pay for all payments.
		$fetch_query = mysqli_fetch_array(get_single_user_only_primary_account($user_id));
		$primary_id = $fetch_query['user_account_id'];
			/////we need the primary acct alone to be able to pay for all payments.
        
		$balance = get_user_account_details($primary_id, 'user_account_wallet_amount');
		    //echo "bal is ".$balance;
	

		$deduction = $balance  - 3500;

		if ( $deduction < 0 ) {
			echo "<script>
				alert('Insufficient funds. Kindly fund your wallet to be able to pay');
				window.location='dashboard'
			</script>";
		}else{
			//////avoid user making payment twice for Tshirt
			$query_duplicate_shirt = mysqli_query($con, "SELECT * FROM user WHERE user_cloth_fee = 'paid' AND user_id = '$user_id' ") or die(mysqli_error($con));
			if (mysqli_num_rows($query_duplicate_shirt)>0) {
				// do nothing just alert that user paid for Tshirt already
				echo "<script>
					alert('You already paid for the Destiny Promoters T-Shirt.');
				</script>";
				header("refresh:1, url=dashboard");
			}
			else{

			$update_thrift_debt_query = create_user_shirt_payment($deduction, $primary_id, $user_id);

			if ($update_thrift_debt_query) {
				echo "<script>
					alert('Destiny Promoters T-Shirt fee was paid successfully.');
				</script>";
				header("refresh:1, url=dashboard");
			}
		}
		}
	}

?>