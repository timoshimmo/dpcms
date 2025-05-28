<?php
include("../includes/session.php"); 
include("../includes/config.php"); 
include("../includes/db-functions.php"); 

if (isset($_GET['tnx_id']) && !empty($_GET['tnx_id']) && isset($_GET['initial_end_date']) && !empty($_GET['initial_end_date'])) {
		
	$txn_id = base64_decode($_GET['tnx_id']);

	$initial_date = base64_decode($_GET['initial_end_date']);


	//$current_fast_track_date = date("Y-m-d", strtotime("-25 weeks", strtotime($initial_date)) ) ;
	$current_fast_track_date = date("Y-m-d",strtotime("today"));

	$session_logged_in_user_account_id  = $_SESSION['session_logged_in_user_account_id'];
	
	$session_logged_in_user_id = $_SESSION['session_logged_in_user_id'];

	//echo $txn_id;




	////PLS KZ, CHANGE THE END DATE TO TODAY AND KNOW THE LAST THRIFT WEEK THAT THE ACCOUNT IS CURRENTLY ON.  
	////CALCULATE & SUBSTRACT CURRENT WEEK FROM 50 WEEKS
	////MULTIPLY 1300 BY TOTAL OF WEEKS AFTER SUBSTRACTION FROM 50WKS
	////LOG THE FINAL TOTAL TO A COLUMN/TABLE WITH TXN ID & USER ACCT ID & USER ID
	////HAVE ANOTHER COLUMN TO KNOW IF THE REMAINING FASTTRACK THRIFT WEEKS PAID FOR OR NOT.


	/////remove the exit() later.
	////now check/get the user_Acct_id last current weekly contribution and remove that from 50weeks
	///then calculate and multiply the total result week by 1300.

	////now use DESC to get last current week
//	$query_current_weeks = mysqli_query($con,"SELECT * FROM user_weekly_thrift_backup WHERE user_id = '$session_logged_in_user_id' AND user_account_id = '$session_logged_in_user_account_id' AND thrift_txn_id = '$txn_id' ORDER by user_weekly_thrift_id DESC LIMIT 1");
	$query_current_weeks = mysqli_query($con,"SELECT count(user_thrift_current_weeks) AS currentwks FROM user_weekly_thrift_backup WHERE user_id = '$session_logged_in_user_id' AND user_account_id = '$session_logged_in_user_account_id' AND thrift_txn_id = '$txn_id'");
	$fetch_current_weeks = mysqli_fetch_array($query_current_weeks);
	$current_week_output = $fetch_current_weeks['currentwks'];

	///now remove current_week_output from 50 and calc and multiple the result by 1300
	$weeks_substraction = 50 - $current_week_output;

	//now multiply 1300 by the weeks_substraction
	$remaining_weeks_balance = $weeks_substraction * 1300;
	echo $remaining_weeks_balance;

	////so remaining_weeks_balance is now what we'll log as the balance the user needs to pay since didn't complete the 50weeks as fast track user. thank you


	/*CHECK IF A PARTICULAR THRIFT HAS NOT BEEN FAST TRACK BEFORE*/

	if(query_thrift_status($txn_id, "thrift_transaction_fast_track") == "no") {

		$create_fast_track = create_thrift_fast_track($session_logged_in_user_id, $session_logged_in_user_account_id, $initial_date, $current_fast_track_date, $txn_id );

			if ($create_fast_track) {
				///added by kz March 20 2023
				log_fasttrack_weeks_balance($session_logged_in_user_id,$session_logged_in_user_account_id,$txn_id,$current_week_output,$weeks_substraction,$remaining_weeks_balance);

				end_thrift_transaction($txn_id);

				///added by kz March 20 2023

				echo "<script>
					alert('Thrift has been successfully fast tracked');
					window.location='thrift-transaction';
				</script>";
			}else{
		

				echo "<script>
					alert('Something went wrong, please try again later.');
					window.location='thrift-transaction';
				</script>";
			}
		
	}


	
}else{
	header("location:dashboard");
}

?>