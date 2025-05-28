<!DOCTYPE html>
<html>
<head>
	<title>Noblemerry Dashboard</title>
<?php include "sidebar.php" ?>

<?php
	
	if (isset($_GET['plan_id']) && !empty($_GET['plan_id'])) {

		$plan_id = base64_decode($_GET['plan_id']);
		
		$fetch_thrift_query = mysqli_fetch_array( query_single_thrift_package($plan_id));
					
	}else{
		header("Location:dashboard");
	}

	if (isset($_POST['save'])) {
			
		$output = "";

		$user_wallet_balance = get_user_account_details($session_logged_in_user_account_id, "user_account_wallet_amount");

		$package_price = $fetch_thrift_query['thrift_package_price'];

		$wallet_balance = $user_wallet_balance - $package_price;

		if ( $wallet_balance < 0 ) {
		
			$output = '<div class="alert alert-danger">Insufficient Wallet balance. Kindly fund your wallet</div>';
		
		}else{

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

				$output = '<div class="alert alert-success">Thrift Plan activated successfully.</div>';
				header("refresh:5, url=thrift-transaction");
			}

		}

		


	}
?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<a href="javascript:history.back()" class="back-link d-inline-flex">
			<i class="ri-arrow-left-s-line"></i>
			<span>Back</span>
		</a>

		<div class="mt-3 investment-details-wrapper">
			<h3 class="text-center">Thrift Plan Details</h3>

			<?php

				if (isset($_POST['save'])) {
					echo $output;
				}

			?>
			
			
			<form action="" method="POST">
			<div class="mt-4 d-flex flex-col" style="gap:11px;">
				<h4 class="text-md-bold"><?php echo $fetch_thrift_query['thrift_package_name']; ?> plan</h4>

				<div class="d-flex align-center investment-details-list">
					<h6 class="m-0 text-sm-bold">Product Code:</h6>
					<span class="text-blur"><?php echo $fetch_thrift_query['thrift_package_plan_code'] ?></span>
				</div>


				<div class="d-flex align-center investment-details-list">
					<h6 class="m-0 text-sm-bold">Length:</h6>
					<span class="text-blur"><?php echo $fetch_thrift_query['thrift_package_working_weeks'] ?> Weeks</span>
				</div>

				<div class="d-flex align-center investment-details-list">
					<h6 class="m-0 text-sm-bold">Cashback:</h6>
					<span class="text-blur"><?php echo $fetch_thrift_query['thrift_package_profit'] ?>% when you refer an account who must be up to 5months.</span>
				</div>

				<div class="mt-4 investment-plan-wrapper d-flex">
					
					<div class="investment-plan">
						<p class="m-0 text-blur text-sm">Investment Plan Price</p>
						<span class="text-sm-bold pb-0 text-blur">N<?php  echo number_format($fetch_thrift_query['thrift_package_price'])?></span>
					</div>
				</div>

				<?php

				$query_thrift_exists = mysqli_query($con, "SELECT * FROM thrift_transaction_details WHERE user_account_id ='$session_logged_in_user_account_id' AND thrift_txn_counter='on' AND txn_status ='active' ");
				if(mysqli_num_rows($query_thrift_exists) > 0){
					?>

					<p class="text-center text-danger">You have a running thrift plan on this account</p>
				<?php
					}else if (@$fetch_last_thrift_txn['thrift_fine']  > 0) {
				?>

					<p class="text-center text-danger">You still have a thrift debt and fine that is needed to be paid.</p>	

				<?php

					}else{
				?>

				<div class="d-flex justify-content-center mt-5">
						<button class="investment-plan-btn" name="save">Subscribe & Continue</button>
				</div>

				<?php
					}
				?>
			

			</div>
			</form>
		</div>

	</div>

</div>
</body>
</html>