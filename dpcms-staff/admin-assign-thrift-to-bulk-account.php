<?php
include 'includes/header.php';
if (isset($_GET["token"])) {
	$get_user_id = base64_decode($_GET["token"]);	

}else{
	header('location:log-out');
}
$fetch_users_info = query_user_details($get_user_id);
$get_user_account_id = $fetch_users_info['user_account_id'];
//echo $get_user_account_id;
//exit();

	$query_active_thrift = query_users_thrift_active_transaction_desc($get_user_account_id);
	$check_active_account = mysqli_num_rows($query_active_thrift);
	$fetch_user_details = query_user_details($get_user_id);
	$total_account =  mysqli_num_rows(query_user_all_account($get_user_id));
	$total_automated_account = query_all_user_generated_account($get_user_id, true);
	$primary_account_wallet_balance = get_user_account_details($get_user_account_id, 'user_account_wallet_amount');
	$query_automated_account_active_thrift = mysqli_num_rows(query_generated_users_thrift_active_transaction_desc($get_user_id));

	/*get to know if the user still has an account yet to be paid*/
	$pending_r_w = admin_query_if_user_has_pending_acct_withdraw_request($get_user_id,true);
	/*bcus system will add thrift for those accounts too. if they're still pending*/


	if (isset($_POST['btnsubmit_assign_thrift'])) {
		
		$output = "";
		$package_id = $_POST['package_id'];
		$query_package  =  query_single_thrift_package($package_id);
		$fetch_package_details = mysqli_fetch_array($query_package);
		$thrift_package_price = $fetch_package_details['thrift_package_price'];
		$remaining_inactive_account = $total_automated_account - $query_automated_account_active_thrift;
		$total_thrift_amount_needed = $remaining_inactive_account * $thrift_package_price;
		$primary_wallet_remaining_balance = $primary_account_wallet_balance - $total_thrift_amount_needed;
			
		if( $primary_wallet_remaining_balance < 0){
			$output = "<div style='color:red'>Insufficient Funds. A total amount of <b> ₦". number_format($total_thrift_amount_needed) . "</b>  needed to assign thrift of  <b> $remaining_inactive_account auto generated account </b> with no running thrift.</div>";
		}else{
			$query_user_auto_generated_account = query_all_user_generated_account($get_user_id);

			while($fetch_all_auto_generated_account = mysqli_fetch_array($query_user_auto_generated_account)){
				
				$user_account_id = $fetch_all_auto_generated_account[0];

				$query_generated_account_active_thrift = query_users_thrift_active_transaction_desc($user_account_id);
				$account_generated_active_thrift = mysqli_num_rows($query_generated_account_active_thrift);
				if($account_generated_active_thrift > 0){
					continue 1;
				}

				/*INCREMENT TOTAL REFERRAL WITH ACTIVE PLAN IF FIRST TIME ASSIGNING THRIFT*/
				$check_thrift_exists = query_users_thrift_transaction($user_account_id);

				$count_thrift_exists = mysqli_num_rows($check_thrift_exists);

				$referred_referral_id = get_user_account_details($user_account_id, 'referred_referral_id');

				$check_referral_exists = query_referral_exists($referred_referral_id);

				/*IF USER FIRST TIME PACKAGE SUBSCRIPTION AND HAVE REFERRAL */
				
				if ( ($count_thrift_exists == 0) && ($check_referral_exists > 0) ) {
					set_total_referral_with_active_plan($referred_referral_id);		
				}
				/*INCREMENT TOTAL REFERRAL WITH ACTIVE PLAN IF FIRST TIME ASSIGNING THRIFT*/




				$date_format = "Y-m-d";

				$date_condition = date("D") == "Sat";

				$cashback_profit = ( ($fetch_package_details['thrift_package_profit']/100) * $thrift_package_price);

				$start_days = $date_condition ? date($date_format, strtotime("today")) : date($date_format, strtotime( "next Saturday" )); 

				$number_of_days = $fetch_package_details['thrift_package_working_weeks'] * 7;

				$end_days = date($date_format, strtotime("+".$number_of_days. " day", strtotime($start_days)) );

				$thrift_counter = "on";

				$thrift_status = "active";

				$query_thrift_plan_submission = user_thrift_package_transaction($get_user_id, $user_account_id, $package_id, $cashback_profit ,$start_days, $end_days, $thrift_counter, $thrift_status);

				if ($query_thrift_plan_submission) { 
					
					$user_thrift_txn_id = mysqli_insert_id($con);

					$primary_account_wallet_balance = get_user_account_details($get_user_account_id, 'user_account_wallet_amount');

					update_user_balance($primary_account_wallet_balance - $thrift_package_price, $get_user_account_id, $con);

					create_user_weekly_thrift($get_user_id, $user_account_id, $package_id, $user_thrift_txn_id, 1, 'paid', 0); 
				}
			}

			$output = "<div style='color:green; font-weight:bold;'>Thrift successfully assigned for the automated thrift account with inactive thrift. </div>"; 
			header("refresh:3");
		}
		
		
		/*$total_thrift_amount*/

		/*$wallet_balance = $user_wallet_balance - $package_price;*/
		
	}
?>
<style type="text/css">
	table tr th{
		width: 300px;
		text-transform: uppercase;
	}
</style>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">
	<?php include 'includes/nav-sidebar.php'; ?>

		<div class="main">
			<?php include 'includes/top-nav-header.php'; ?>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="mb-3">
						<h1 class="h3 d-inline align-middle">Assign Thrift for Generated Thrift Account</h1>
					</div>

					<div class="row">

						<div class="col-xl-3">
						</div>
						<div class="col-xl-6">
							<div class="card">
								<div class="card-header">

									<h5 class="card-title mb-0">Assign thrift for <u><?php echo ucwords($fetch_users_info['fullname']); ?></u></h5>

										<?php

			if (isset($_POST['btnsubmit_assign_thrift'])) {
		
				echo $output;
			}

		?>
								</div>

		<?php
		if($check_active_account > 10000){

			echo "<h2 class='text-danger'>The User Primary account doesn't have an active thrift yet.</h2>";
			echo "<br>";
			echo "<h5 class='text-danger'>This user primary account must have an active thrift running before you can auto assign thrift to the other newly generated thrift account</h5>";


		}
		else if ($pending_r_w >0) {
			// this means that the user still have an account that is yet to be paid
			// such user can not assign thrift to the just created account. 
			/// approve the pending request withdraw for the user and system will allow the account to be assigned
			echo "<h2 class='text-danger'>Can NOT Assign Thrift</h2>";
			echo "<br>";
			echo "<h5 class='text-danger'>This user is having <b>".$pending_r_w."</b> account(s) that requested for withdrawal and is yet to be paid.</h5>";
			echo "<br>";
			echo "<h5 class='text-danger'>Please approve the accounts as <b>PAID</b> from the request withdrawal page and come back here to assign thrift.</h5>";
		}
		else{
		?>
								<div class="card-body">
									

									<table class="table table-sm mt-2 mb-4">
										<tbody>
											<tr>
												<th>Full Name</th>
												<td><?php echo ucwords($fetch_users_info['fullname']); ?></td>
											</tr>
											<tr>
												<th>Total Account</th>
												<td><?php echo $total_account; ?></td>
											</tr>
											<tr>
												<th>Total Manual Account</th>
												<td><?php echo $total_account - $total_automated_account; ?></td>
											</tr>
											<tr>
												<th>Total Automated Account</th>
												<td><?php echo $total_automated_account; ?></td>
											</tr>
											<tr>
												<th>Automated Account with Active Thrift</th>
												<td><?php echo @$query_automated_account_active_thrift; ?></td>
											</tr>
											<tr>
												<th>Automated Account with InActive Thrift</th>
												<td><?php echo @query_generated_users_with_thrift_inactive($get_user_id, true) ; ?></td>
											</tr>
											<tr>
												<th>Primary Account Balance</th>
												<td><span class="badge bg-danger">₦<?php
					$primary_account_wallet_balance = get_user_account_details($get_user_account_id, 'user_account_wallet_amount');
				 echo number_format($primary_account_wallet_balance) ?></span></td>
											</tr>
										</tbody>
									</table>

				<?php
	if($total_automated_account != $query_automated_account_active_thrift){
	?>
									<div class="row g-0">
										
										<div class="col-sm-12 col-xl-8 col-xxl-9">
					<form action="" method="POST">

			<div class="form-group">
				<select class="form-control" required  name="package_id">
					<option value="">Select Thrift Package</option>
					<?php
						$query_all_package = query_all_thrift_package();
						$count_package = mysqli_num_rows(  $query_all_package );

						if($count_package > 0){
							while($fetch_all_package = mysqli_fetch_array($query_all_package)){
								extract($fetch_all_package);
							?>
								<option value="<?php echo $thrift_package_id ?>"><?php echo ucwords($thrift_package_name) .  " - ₦".number_format($thrift_package_price)."" ?> for <?php echo $thrift_package_working_weeks; ?> Weeks</option>
						<?php

							}

						}

					?>
				</select>
			</div>

			<p style="margin-top: 10px;">
			<button type="submit" name="btnsubmit_assign_thrift" class="btn btn-primary float-right">Assign Thrift</button></p>
		</form>
										</div>
									</div>
	<?php
	}
	?>
								

								</div>
				<?php
				}
				?>
							</div>
						</div>
					</div>

				</div>
			</main>

			<?php include 'includes/footer.php'; ?>
		</div>
	</div>

	<script src="js/app.js"></script>

</body>

</html>