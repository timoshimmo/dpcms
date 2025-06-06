<?php
include("../includes/session.php"); 
include("../includes/config.php"); 
include("../includes/db-functions.php"); 


	if (isset($_GET['thrift_txn_id']) && !empty($_GET['thrift_txn_id']) && isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['user_account_id']) && !empty($_GET['user_account_id']) && isset($_GET['package_id']) && !empty($_GET['package_id'])) {

		
		$thrift_txn_id = base64_decode($_GET['thrift_txn_id']);

		$thrift_user_account_id = base64_decode($_GET['user_account_id']);

		$thrift_user_id = base64_decode($_GET['user_id']);

		$thrift_package_id = base64_decode($_GET['package_id']);

		$query_thrift_package = query_single_thrift_package($thrift_package_id);

		$fetch_thrift_package = mysqli_fetch_array($query_thrift_package);

		$single_referral_empowerment =  get_user_account_details($thrift_user_account_id, 'single_referral_empowered');

		$fetch_thrift_package_profit = $single_referral_empowerment == "yes" ?  $fetch_thrift_package['thrift_package_profit'] : 0;

		$fetch_thrift_package_price = $fetch_thrift_package['thrift_package_price'];

		$fetch_thrift_package_weeks = $fetch_thrift_package['thrift_package_working_weeks'];

		$profit =  ($fetch_thrift_package_profit/100) * $fetch_thrift_package_price * $fetch_thrift_package_weeks ;

		$query_last_thrift_txn = query_users_thrift_transaction_desc($thrift_user_account_id);
		
		$fetch_last_thrift_txn = mysqli_fetch_array($query_last_thrift_txn);

		$thrift_txn_status =  @$fetch_last_thrift_txn['txn_status'];

		
		/*$check_withdrawal = mysqli_num_rows( check_request_withdraw_exist($thrift_txn_id) );

		$get_user_account_type = get_user_account_details($thrift_user_account_id, 'user_account_type');

		$query_fetch_user_details = query_user_details($thrift_user_id);


		if ($get_user_account_type == "primary" &&  $check_withdrawal <= 0 && $thrift_txn_status == "completed" && $query_fetch_user_details['user_cloth_fee'] == "pending") {
			$charges = 3500;
		}else if($check_withdrawal <= 0 && $thrift_txn_status == "completed"){
			$charges = 1000;
		}*/
		
		$total_withdraw =  (int) ( ($fetch_thrift_package_price * $fetch_thrift_package_weeks ) +  $profit  );
	
		if ( mysqli_num_rows(query_user_bank_details($thrift_user_id)) == 0) {
			echo "<script>
				alert('Kindly add bank account details before requesting for withdrawal');
				window.location='add-bank-account';
			</script>";
		}else{

			//avoid duplicate withdrawal request for a single transaction.
		$check_withdrawal = mysqli_num_rows( check_request_withdraw_exist($thrift_txn_id));
		if ($check_withdrawal > 0) {
			// if > 0, means withdrawal request already sent for such txn
			///do nothing but alert
			echo "<script>
						alert('Withdrawal request already placed');
					</script>";
				header("refresh:1, url=thrift-transaction");
		}
		else{
			$insert_withdrawal = request_withdraw($thrift_user_id, $thrift_user_account_id, $thrift_txn_id, $thrift_package_id,$total_withdraw);

			//mysqli_query($con, "UPDATE user SET user_cloth_fee = 'paid' WHERE user_id = '$thrift_user_id' ");

			if ($insert_withdrawal) {
				 	echo "<script>
						alert('Your request for withdrawal has been successfully submitted. Admin would review it and credit the bank account details attached to your Destiny Promoters Profile');
					</script>";
				header("refresh:1, url=thrift-transaction");

			 } else{
			 	echo "nn";
			 }
			}
		}

	}else{
		header("location:dashboard");
	}


?>