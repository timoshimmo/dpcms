<?php include "sidebar.php";
include '../includes/call-api.php';
?>

<?php
	$query_primary = mysqli_query($con, "SELECT * FROM user_account WHERE user_id='$session_logged_in_user_id' AND user_account_type='primary' ");
	$fetch_primary_details  = mysqli_fetch_array($query_primary);
	$balance = $fetch_primary_details['user_account_wallet_amount'];
	$primary_account_account_id = $fetch_primary_details['user_account_id'];
		if (isset($_POST['proceed'])) {
				
			$output = "";

			$user_account_token = mysqli_real_escape_string($con,$_POST['user_account_id']);

			$user_referral_id = mysqli_real_escape_string($con,$_POST['user_referral_id']);

			$referred_referral_id = mysqli_real_escape_string($con,$_POST['sponsored_refid']);

			///define the new account registration fee which is 3000
			$registration_fee = 1000;//3000;
			$contribution_fee = 1500;
			$funding_weekly_fee = 1500; /////this is for the new acct to use for 2nd week
			$deduction_fee = $registration_fee + $contribution_fee + $funding_weekly_fee;

			$output = $deduction_fee;

			if ($balance < $deduction_fee) {
				$output = "<div class='alert alert-danger'>
					<p>Unable to create account. Insufficient funds in your primary account.</p>
					<p>Please fund your Primary Account wallet with ₦6,000 and try again later</p> 
				</div>";
			}
			else{

			$add_user_account = create_user_account($session_logged_in_user_id,$user_account_token, $user_referral_id,$referred_referral_id);

			if ($add_user_account) {
				$last_insert_id = mysqli_insert_id($con);

				/////increment the referral count to 1

			query_counts_increment_user_referral($referred_referral_id);
			
			/*$update = mysqli_query($con,"UPDATE user_account SET 
			referral_count = referral_count+1 WHERE
			referral_id = '$referred_referral_id'
			");
*/
			if(query_user_reg_within_forty_days($referred_referral_id)){
				update_users_referral_count_within_forty_days($referred_referral_id);
			}

			update_user_balance($balance - $deduction_fee, $primary_account_account_id, $con);

			/*THRIF ACTIVATION STARTS*/

			$date_format = "Y-m-d";

			$date_condition = date("D") == "Sat";

			$plan_id = 1;
			$fetch_thrift_query = mysqli_fetch_array( query_single_thrift_package($plan_id));
			
			$package_price = $fetch_thrift_query['thrift_package_price'];

			$single_referral_empowerment =  get_user_account_details($last_insert_id, 'single_referral_empowered');

			$cashback_profit = $single_referral_empowerment  == "yes" ? (($fetch_thrift_query['thrift_package_profit']/100) * $package_price) : 0;

			$start_days = $date_condition ? date($date_format, strtotime("today")) : date($date_format, strtotime( "next Saturday" ));

			$number_of_days = $fetch_thrift_query['thrift_package_working_weeks'] * 7;

			$end_days = date($date_format, strtotime("+".$number_of_days. " day", strtotime($start_days)) );

			$thrift_counter = "on";

			$thrift_status = "active";

			$check_thrift_exists = query_users_thrift_transaction($last_insert_id);

			$count_thrift_exists = mysqli_num_rows($check_thrift_exists);

			$referred_referral_id = get_user_account_details($last_insert_id, 'referred_referral_id');

			$check_referral_exists = query_referral_exists($referred_referral_id);

			/*IF USER FIRST TIME PACKAGE SUBSCRIPTION AND HAVE REFERRAL */
			if ( ($count_thrift_exists == 0) && ($check_referral_exists > 0) ) {

				set_total_referral_with_active_plan($referred_referral_id);
					
			}


			$query_thrift_plan_submission = user_thrift_package_transaction($session_logged_in_user_id, $last_insert_id, $plan_id, $cashback_profit ,$start_days, $end_days, $thrift_counter, $thrift_status);

			if ($query_thrift_plan_submission) {
				
				$user_thrift_txn_id = mysqli_insert_id($con);

				create_user_weekly_thrift($session_logged_in_user_id, $last_insert_id, $plan_id, $user_thrift_txn_id, 1, 'paid', 0);

				/////since user thrift was successful, now credit the new account
				/////and also debit the primary account of 1,300 naira
				////now add 1,300 to the newly created account
				////since 4,600 at once has been deducted above from primary acct
				update_user_balance($funding_weekly_fee, $last_insert_id, $con);

				



				/*$output = '<div class="alert alert-success">Thrift Plan activated successfully.</div>';
				header("refresh:5, url=thrift-transaction");*/



				$output = "<div class='alert alert-success'>
				Account Created Successfully And Thrift Created Successfully for account. Wait while the system redirect you!
				</div>";

				/////now send sms to the user that new account created successfully.
				////send sms starts here
			$sms_message = "Dear ".ucwords($user_fullname).", your request to add a new account on the Destiny Promoters account dashboard was successful and ₦6,000 deducted from your primary account wallet. Thank you for choosing Destiny Promoters Cooperative!!"; 
			@noblemerry_send_sms($user_phone_no,$sms_message);  
			////send sms ends here
				header("refresh:3, url=dashboard");
			}

			/*THRIFT ACTIVATION ENDS*/



				
				/*update_user_balance($contribution_fee, $last_insert_id, $con);*/

				

			}else{

				$output = "<div class='alert alert-danger'>
					Something went wrong. Try Again later
				</div>";
			}

			}

		}
?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body pt-5">
		<h3 class="text-md-bold mb-3" style="margin-top: 40px;">Create More Account</h3>

		<div class="mt-5 investment-details-wrapper py-5">
			<?php

				if (isset($_POST['proceed'])) {
					echo $output;
				}

			?>
			
			<?php


				if( ($balance - 1500) < 0){

					echo "<div class='alert alert-danger'>
					Unable to create account. Insufficient funds in your primary account</div>";

				}else if (mysqli_num_rows(query_users_active_contribution_acct($user_id))>=49) {
					echo "<div class='alert alert-danger'>
					Oops!!, You can not add more account, please contact the customer care on <a href='tel:+23408184212487'>+23408184212487</a>  OR <a href='tel:+23409031504010'>+23409031504010</a> </div>";
				}
				else{
					
			?>
			<form class="d-flex flex-col space-mobile" method="POST"  style="gap:40px;">
			<div class="form-inline row align-items-center" style="display: none;">
				<div class="col-sm-4">
					<label class="text-md">Account ID:</label>
				</div>
				<div class="col-sm-8">
					<?php
						$fetch_last_account_id = mysqli_fetch_array(query_last_account_id());
						$last_account_id = $fetch_last_account_id['user_account_id'];
					?>
					<input type="text" readonly value="<?php echo 'DPC'.random_numbers(11)?>" class="form-control input" name="user_account_id">
				</div>
			</div>


			<div class="form-inline row align-items-center" style="display: none;">
				<div class="col-sm-4"> 
					<label class="text-md">Referral ID:</label>
				</div> 
				<div class="col-sm-8"> 
					<input type="text" readonly value="<?php 
					
					
					$generate_user_refferal_id = 'RF'.random_strings(14);
					
					echo $generate_user_refferal_id?>" class="form-control input" name="user_referral_id">
				</div>
			</div>



			<div class="form-inline row align-items-center">
				<div class="col-sm-4">
					<label class="text-md">Sponsor ID: <br><small style="color: #CE0016;">this means your upline referral id</small></label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control input" name="sponsored_refid" required>
				</div>
			</div>
			

			<div class="d-flex justify-content-center">
				<button class="btn btn-main proceed" name="proceed">Proceed</button>
			</div>
		</form>
		<?php

				}
		?>
		</div>

	</div>

</div>
</body>
</html>