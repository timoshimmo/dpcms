<?php include "sidebar.php" ?>
<?php
	
	///update the is_bulk_clearance later after withdrawal request to cleared.

	$query_clearance = mysqli_query($con,"SELECT * FROM thrift_transaction_details JOIN user_account ON user_account.user_account_id = thrift_transaction_details.user_account_id WHERE thrift_transaction_details.txn_status = 'completed' AND thrift_transaction_details.is_bulk_clearance ='yes' AND thrift_transaction_details.thrift_processing_fee = 'paid' AND thrift_transaction_details.incentive_delivery_status = 'paid' AND thrift_transaction_details.user_id = '$session_logged_in_user_id' ORDER by txn_id ASC");

	$count_clearance = mysqli_num_rows($query_clearance); 


?>
<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<h3 class="text-md-bold mb-3">Request withdrawal for bulk accounts</h3>

		<span><b><u><?php echo $count_clearance; ?> accounts are eligible to requests for withdrawal</u></b></span>
		<p><b>NOTE:</b> On this page, you can request for withdrawal for all your bulk accounts that already paid for clearance fee.</p>


		<div class="mt-5">
			
			<div class="table-responsive">
				<div class="d-flex justify-content-end">

					<?php
					if ( mysqli_num_rows(query_user_bank_details($session_logged_in_user_id)) == 0) 
					{
						echo "<b><font color='red'>Kindly add bank account details before requesting for withdrawal</font></b>";
					}
					else{
						if ($count_clearance >=1) {
						?>
						<a href="request-bulk-accounts-withdrawal?user_id=<?php echo base64_encode($session_logged_in_user_id) ?>&total_clearance=<?php echo base64_encode($count_clearance); ?>" class="btn btn-sm btn-main" onclick="return confirm('This will request withdrawal for the <?php echo $count_clearance; ?> accounts at once. \n\n Thank you')">Request Withdrawal</a>
						<?php
						}
					}
					?>
					
				</div>
				<table id="table" class="table">
					<thead class="thead">
						<tr>
							<th>S/N</th>
							<th>Acct ID</th>
							<th>Balance</th>
							<th>Downline</th>
							<th>Downline Within 40days</th>
							<th>Referral with Active Plan</th>
							<th>Thrift End Date</th>
							<th>Reg. Date</th>


							<!-- <th>Action</th> -->
						</tr>
					</thead>
					<tbody class="tbody">
						<?php
							$cnt = 1;
							if ($count_clearance > 0) {

								while($fetch_clearance_acct = mysqli_fetch_array($query_clearance)){
									extract($fetch_clearance_acct);
							?>
								<tr>
									<td><?php echo $cnt++; ?></td>
									<td><?php echo $user_account_token; ?> <?php echo ($user_account_type == 'primary' ? "(Primary)" : ""); ?></td>
									<td>₦<?php echo number_format($user_account_wallet_amount); ?></td>
									<td><?php echo $referral_count; ?></td>
									<td><?php echo $referral_count_within_forty_days; ?></td>
									<td><?php echo $total_referral_with_active_plan; ?></td>
									<td><?php echo date("D, M j, Y",strtotime($thrift_end_date));  ?></td>
									<td><?php echo date("D, M j, Y",strtotime($user_account_created_at));  ?></td>


									
									
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