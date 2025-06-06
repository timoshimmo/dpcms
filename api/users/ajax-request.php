<?php
include("../includes/session.php");
include("../includes/config.php");  
include("../includes/db-functions.php"); 

	if (isset($_POST['plan_id']) && !empty($_POST['plan_id']) && isset($_POST['session_logged_in_user_account_id']) && !empty($_POST['session_logged_in_user_account_id'])  && isset($_POST['session_logged_in_user_id']) && !empty($_POST['session_logged_in_user_id'])) {
		
		$plan_id = $_POST['plan_id'];

		$session_logged_in_user_account_id = $_POST['session_logged_in_user_account_id'];

		$session_logged_in_user_id  = $_POST['session_logged_in_user_id'];

		$fetch_thrift_query = mysqli_fetch_array( query_single_thrift_package($plan_id));

		$user_wallet_balance = get_user_account_details($session_logged_in_user_account_id, "user_account_wallet_amount");

		$package_price = $fetch_thrift_query['thrift_package_price'];

		$wallet_balance = $user_wallet_balance - $package_price;

		$query_thrift_exists = mysqli_query($con, "SELECT * FROM thrift_transaction_details WHERE user_account_id ='$session_logged_in_user_account_id' AND thrift_txn_counter='on' AND txn_status ='active' ");

		if ( $wallet_balance < 0 ) {
		
			echo "insufficient_funds";

		}else if(mysqli_num_rows($query_thrift_exists) > 0){
			
			echo "already_subscribe";

		}else if($wallet_balance >= 0 ){

			$date_format = "Y-m-d";

			$date_condition = date("D") == "Sat";

			$single_referral_empowerment =  get_user_account_details($session_logged_in_user_account_id, 'single_referral_empowered');

			$cashback_profit = $single_referral_empowerment  == "yes" ? (($fetch_thrift_query['thrift_package_profit']/100) * $package_price) : 0;

			$start_days = $date_condition ? date($date_format, strtotime("today")) : date($date_format, strtotime( "next Saturday" ));

			$number_of_days = $fetch_thrift_query['thrift_package_working_weeks'] * 7;

			$end_days = date($date_format, strtotime("+".$number_of_days. " day", strtotime($start_days)) );

			$thrift_counter = "on";

			$thrift_status = "active";

			$check_thrift_exists = query_users_thrift_transaction($session_logged_in_user_account_id);

			$count_thrift_exists = mysqli_num_rows($check_thrift_exists);

			$referred_referral_id = get_user_account_details($session_logged_in_user_account_id, 'referred_referral_id');

			$check_referral_exists = query_referral_exists($referred_referral_id);

			/*IF USER FIRST TIME PACKAGE SUBSCRIPTION AND HAVE REFERRAL */
			if ( ($count_thrift_exists == 0) && ($check_referral_exists > 0) ) {

				set_total_referral_with_active_plan($referred_referral_id);
					
			}


			$query_thrift_plan_submission = user_thrift_package_transaction($session_logged_in_user_id, $session_logged_in_user_account_id, $plan_id, $cashback_profit ,$start_days, $end_days, $thrift_counter, $thrift_status);

			if ($query_thrift_plan_submission) {
				
				$user_thrift_txn_id = mysqli_insert_id($con);

				create_user_weekly_thrift($session_logged_in_user_id, $session_logged_in_user_account_id, $plan_id, $user_thrift_txn_id, 1, 'paid', $wallet_balance);
				
				echo "subscribe_success";
				
			}

		}



	}




	if (isset($_GET['referral_id']) && !empty($_GET['referral_id'])) {

	  $referral_id =  $_GET['referral_id'];

	  $query_all_referral = mysqli_query($con, "SELECT fullname, phone_no, photo, address, user_reg_date, is_acct_active, user_account_token FROM user_account JOIN user ON user_account.user_id  = user.user_id WHERE referred_referral_id='$referral_id'");

	  $count_referral = mysqli_num_rows($query_all_referral);


		if ( $count_referral > 0) {
			 // echo $count_query;
			while($fetch_referral = mysqli_fetch_assoc($query_all_referral)) {
				$arr[] = $fetch_referral;

			}

			echo json_encode($arr);

		} else{

			echo "no referral";

		}

	}


?>
