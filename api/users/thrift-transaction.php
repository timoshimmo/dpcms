<?php include "sidebar.php" ?>

<div class="main-container">
	<?php include "header.php" ?>

	<div class="main-content-body">
		<h3 class="text-md-bold mb-3">Thrift Transaction Details</h3>

		<?php include("debt-fine-msg.php"); ?>
		<?php include("shirt-payment-error.php");?>

		<?php

			$thrift_txn_id =  @$fetch_last_thrift_txn['txn_id'];

			$thrift_txn_status =  @$fetch_last_thrift_txn['txn_status'];

			$thrift_processing_fee = @$fetch_last_thrift_txn['thrift_processing_fee'];

			$thrift_incentive_status = @$fetch_last_thrift_txn['incentive_delivery_status'];

			$thrift_incentive_type = @$fetch_last_thrift_txn['incentive_type'];

			$thrift_incentive_amount = @$fetch_last_thrift_txn['incentive_amount'];

			$check_withdrawal = mysqli_num_rows( check_request_withdraw_exist($thrift_txn_id) );

			////get the check_fasttrack_weeks_balance_paid($session_logged_in_user_account_id);
			$get_fasttrack_balance = mysqli_num_rows(check_fasttrack_weeks_balance_paid($session_logged_in_user_account_id));

			 include("incentive-waybill-payment-msg.php");



			/*IF USERS HASNT PLACED REQUEST FOR LAST THRIFT*/
			if($check_withdrawal <= 0 && $thrift_txn_status == "completed" && $thrift_processing_fee == "pending"){
			?> 
				<div class="alert alert-info mt-0">
						<strong>N.B: You are to pay a sum of ₦<?php echo number_format(2000); ?> for clearance fee. <a onclick="return confirm('Are you sure you want to pay for the ₦2,500 clearance fee? \n The system will charge your primary account wallet.\n\n OK to Continue');" href="processing-fee?user_id=<?php echo base64_encode($session_logged_in_user_id); ?>&user_account_id=<?php echo base64_encode($session_logged_in_user_account_id) ?>&thrift_txn_id=<?php echo base64_encode($thrift_txn_id) ?>" class="btn btn-success btn-sm">Pay Now</a></strong> 
				</div>

			<?php
			} 
			if ($get_fasttrack_balance > 0) {
				////since the user acct hasn't paid for the remain fasttrack balance
				///////fetch the balance and other details related to the user fast track
				$fetch_fasttrack_bal = mysqli_fetch_array(check_fasttrack_weeks_balance_paid($session_logged_in_user_account_id));
				$remaining_weeks_to_balance = $fetch_fasttrack_bal['remaining_weeks_to_balance'];
				$current_weeks_fasttracked = $fetch_fasttrack_bal['current_week_when_fasttracked'];
				$uncovered_thrift_weeks = $fetch_fasttrack_bal['uncovered_weeks_when_fasttracked'];

			?>
			<div class="alert alert-danger mt-0" style="display:;">
						<strong>N.B: You are to pay a sum of ₦<?php echo number_format($remaining_weeks_to_balance); ?> for the <b>(<?php echo $uncovered_thrift_weeks; ?>)</b> uncovered thrift contribution weeks because you fast-tracked when your thrift account was on week <?php echo $current_weeks_fasttracked; ?>. <a onclick="return confirm('Are you sure you are ready to pay for this? \n The system will charge your primary account wallet.\n\n OK to Continue');" href=" pay-fasttrack-week-balance-fee?user_id=<?php echo base64_encode($session_logged_in_user_id); ?>&user_account_id=<?php echo base64_encode($session_logged_in_user_account_id) ?>&thrift_txn_id=<?php echo base64_encode($thrift_txn_id) ?>&fasttrack_bal_to_pay=<?php echo base64_encode($remaining_weeks_to_balance); ?>" class="btn btn-success btn-sm">Pay Now</a></strong>  
				</div>
			<?php

			}

		?>
		<div class="mt-5">
			<div class="table-responsive">
				<table id="table" class="table">
					<thead class="thead">
						<tr>
							<th>S/N</th>
							<th>Plans</th>
							<th>Amount</th>
							<th>Profit</th>
							<th>Current Weeks</th>
							<th>Fine Amount</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Plan Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody class="tbody">
				<?php  
				$the_current_wks = '';
					$query_thrift = query_users_thrift_transaction($session_logged_in_user_account_id);

					if (mysqli_num_rows($query_thrift)) {
						$count = 0;
						while ($fetch_thrift_tnx = mysqli_fetch_array($query_thrift)) {
							
							extract($fetch_thrift_tnx);
							$start_days = date("d, M Y",strtotime($thrift_start_date)) 
						?>

							<tr>
							<td><?php echo ++$count ?></td>
							<td><?php echo $thrift_package_name ?></td>
							<td>₦<?php echo number_format($thrift_package_price) ?></td>
							<td>₦<?php 

								echo  number_format($thrift_profit) ?></td>
							<td>
								<?php
									
									$today = strtotime("today");

									$str_start_days = strtotime($thrift_start_date);

									$str_end_days = strtotime($thrift_end_date);

									if($str_start_days > $today){
										echo $the_current_wks = 0;
									}else if ($today >= $str_end_days) {
										
										echo "<p class='text text-success my-0'>Thrift matured</p>";
										

									}else{
										echo floor(($today - $str_start_days) / (60 * 60 * 24 * 7)) + 1;
									}
								?>
							</td>
							<td class="<?php echo $thrift_fine > 0 ? 'text-danger' : ''; ?>">₦<?php echo number_format($thrift_fine); ?></td>
							<td><?php echo $start_days?></td>
							<td><?php echo date("d, M Y",strtotime($thrift_end_date)) ?></td>
							<td><button class="btn <?php echo query_thrift_status($txn_id, 'txn_status')  == "active" ? 'btn-success' : 'btn-danger'?>"><?php echo query_thrift_status($txn_id, 'txn_status') ?></button></td>
							<td>
							<?php
								$total_referral_count =  get_user_account_details($session_logged_in_user_account_id, 'referral_count');

								$total_referral_with_active_plan = get_user_account_details($session_logged_in_user_account_id, 'total_referral_with_active_plan');

								if ($incentive_delivery_status == "pending" && $thrift_txn_status == 'completed' && !$thrift_incentive_type) {
								?>

									<a class="btn btn-primary" href="action-before-request-withdrawal?txn_id=<?php echo base64_encode($txn_id); ?>&user_account_id=<?php echo base64_encode($session_logged_in_user_account_id) ?>">Choose Incentives</a>

								<?php
								}


								$referral_within_forty_days =  get_user_account_details($session_logged_in_user_account_id, 'referral_count_within_forty_days');

								$referral_id =  get_user_account_details($session_logged_in_user_account_id, 'referral_id');

								$user_account_created_at =  get_user_account_details($session_logged_in_user_account_id, 'user_account_created_at');

								$user_account_created_at = explode(" ", $user_account_created_at)[0];

								$next_six_months = strtotime("+6 month", strtotime($user_account_created_at));

								$current_day = strtotime("today");

								if (($current_day >= $next_six_months) && total_user_referral_within_five_months($referral_id) && ($referral_within_forty_days >= 5) && ($total_referral_count >= 5) && ($total_referral_with_active_plan >= 5) && $thrift_transaction_fast_track == 'no' && $txn_status != 'completed') {

								?>
									<a class="btn btn-primary" href="fast-track?tnx_id=<?php echo base64_encode($txn_id)?>&initial_end_date=<?php echo base64_encode(date("Y-m-d",strtotime($thrift_end_date))) ?>" onclick="return confirm('Are you sure you want to fast-track this account? \n It can not be Undo! \n\n OK to Continue');">Fast Track</a>
								<?php
									
								} else if($thrift_transaction_fast_track == 'yes'){
								?>
									<button class="btn btn-success">Fast Tracked</button>
								<?php

								}
							?>

							<?php 
								$check_withdraw_request = check_request_withdraw_exist($txn_id);
								$count_request = mysqli_num_rows($check_withdraw_request);

								/*IF THRIFT TRANSACTION HAS ENDED AND USER HAS NO FINE AND HASNT REQUEST FOR WITHDRAW*/
								if ( ($today >= $str_end_days) && $thrift_fine == 0  && $count_request == 0 &&  $thrift_processing_fee != "pending" &&  $get_user_account_type != "primary"  && $incentive_delivery_status != "pending" ) {
							?>
								<a onclick="return confirm('Are you sure you want to request withdrawal for this account ? \n It can not be Undo! \n\n OK to Continue');" class="btn btn-primary" href="request-withdraw?user_id=<?php echo base64_encode($session_logged_in_user_id) ?>&user_account_id=<?php echo base64_encode($session_logged_in_user_account_id) ?>&thrift_txn_id=<?php echo base64_encode($txn_id);?>&package_id=<?php echo base64_encode($thrift_package_id) ?>">Request Withdrawal</a>
							<?php
								}else if( ($today >= $str_end_days) && $thrift_fine == 0  && $count_request == 0 &&  $thrift_processing_fee != "pending" &&  $get_user_account_type == "primary" && $query_fetch_user_details['user_cloth_fee'] != "pending" && $incentive_delivery_status != "pending"){

								?>

									<a onclick="return confirm('Are you sure you want to request withdrawal for this account ? \n It can not be Undo! \n\n OK to Continue');" class="btn btn-primary" href="request-withdraw?user_id=<?php echo base64_encode($session_logged_in_user_id) ?>&user_account_id=<?php echo base64_encode($session_logged_in_user_account_id) ?>&thrift_txn_id=<?php echo base64_encode($txn_id);?>&package_id=<?php echo base64_encode($thrift_package_id) ?>">Request Withdrawal</a>

							<?php


								}


								else if(check_request_withdraw_approved($txn_id) > 0){

									echo "<span class='btn btn-success'>Paid</span>";

								}else if($count_request > 0 ){
									echo "<span class='btn btn-primary'>Withdrawal Requested</span>";
								}
							 ?>
 							
 							<!-- code breakdown starts here 17 sept 2024 -->
                        	<?php
 							if (@$the_current_wks==0 || @$the_current_wks==1) {
 								////this is because we now added user weekly thrift backup
 								///new thrift package wont show weekly history.
 								//this is to show first 0 week , the weekly history
 							?>
 							<a href="one-weekly-thrift-history?thrift_txn_id=<?php echo base64_encode($txn_id)?>&user_account_id=<?php echo base64_encode($session_logged_in_user_account_id) ?>" class="btn btn-secondary">Weekly history</a>
 							<?php
 							}
 							else{
 							?>
							 <a href="weekly-thrift-history?thrift_txn_id=<?php echo base64_encode($txn_id)?>&user_account_id=<?php echo base64_encode($session_logged_in_user_account_id) ?>" class="btn btn-secondary">Weekly history</a>
 							<!-- code breakdown ends here 17 sept 2024 -->
 							<?php
 							}
 							?>

							</td>
							
						</tr>

					<?php
						}
					}
					
				?>
					
					</tbody>
				</table>
			</div>	
		</div>
	</div>
</div>


</body>
</html>