<?php include "sidebar.php" ?>
<?php
	
	//////check for account that's yet to pay for clearance. 
	///merge the thrift transactions and completed_thrift_50weeks_account 2geda please.
	$output_defaults = 0;
	$query_clearance = mysqli_query($con,"SELECT * FROM completed_thrift_50weeks_account JOIN thrift_transaction_details ON completed_thrift_50weeks_account.txn_id = thrift_transaction_details.txn_id JOIN user_account ON user_account.user_account_id = completed_thrift_50weeks_account.user_account_id WHERE thrift_transaction_details.txn_status = 'completed' AND thrift_transaction_details.thrift_processing_fee = 'pending' AND completed_thrift_50weeks_account.user_id = '$session_logged_in_user_id' ORDER by thrift_transaction_details.txn_id ASC");
	///session_logged_in_user_id
	$count_clearance = mysqli_num_rows($query_clearance); 

	////now we need to detect if one of the accounts is got a defaults 
	////now we need to detect if one of the accounts is got a defaults 
	////now we need to detect if one of the accounts is got a defaults 
	$query_clearance_check_defaults = mysqli_query($con,"SELECT * FROM completed_thrift_50weeks_account JOIN thrift_transaction_details ON completed_thrift_50weeks_account.txn_id = thrift_transaction_details.txn_id JOIN user_account ON user_account.user_account_id = completed_thrift_50weeks_account.user_account_id WHERE thrift_transaction_details.txn_status = 'completed' AND thrift_transaction_details.thrift_processing_fee = 'pending' AND completed_thrift_50weeks_account.user_id = '$session_logged_in_user_id' ORDER by thrift_transaction_details.txn_id ASC");
	while ($fetch_defaults = mysqli_fetch_array($query_clearance_check_defaults)) {
			if ($fetch_defaults['thrift_fine']>0) {
				
			//echo "User ACCT id is ".$fetch_defaults['user_account_id'].'</br>';
			
			$output_defaults = "with_defaults";
	}
	}
	////now we need to detect if one of the accounts is got a defaults 
	////now we need to detect if one of the accounts is got a defaults 
	////now we need to detect if one of the accounts is got a defaults 

	

?>
<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<?php include("shirt-payment-error.php");?>



		<h3 class="text-md-bold mb-3">Manage Bulk Accounts that are due for settlements to commence the clearance. </h3>

		<span><b><u><?php echo $count_clearance; ?> accounts are due for settlements </u></b></span>
		<p><b>NOTE:</b> Any clearance done on this page for bulk accounts, the incentives means of pick up would be <b>Self-Pick</b> by defaults. </p>

		<p><?php
			if ($query_fetch_user_details['user_cloth_fee'] == "pending") {
			?>
			<div class="alert alert-danger mt-0">
						Please you need to pay for Destiny Promoters Shirt before you can start any accounts clearance.  
						
				</div>
			<?php
			exit();
			}
			else if ($output_defaults =='with_defaults') {
			?>
			<div class="alert alert-danger mt-0">
						You've got some defaults to pay on your accounts that are due for clearance/settlements. <a href="accounts-with-defaults" class="btn btn-success btn-sm">Pay from here</a>
				</div>
			<?php
			}

		 ?></p>

		<div class="mt-5">
			
			<div class="table-responsive">
				<div class="d-flex justify-content-end">
					<?php
						if ($count_clearance >=1 && $query_fetch_user_details['user_cloth_fee'] == "paid" && $output_defaults ==0) {
						?>
						<a href="start-bulk-accounts-clearance?user_id=<?php echo base64_encode($session_logged_in_user_id) ?>&total_clearance=<?php echo base64_encode($count_clearance); ?>" class="btn btn-sm btn-main" onclick="return confirm('This will process clearance for the <?php echo $count_clearance; ?> accounts and set incentives pickup method to Self Pick. \n Please make sure your primary wallet is having enough in the wallet. \n\n After clearance fee successfully paid, you will be redirected to another page to request for Withdrawal for the <?php echo $count_clearance; ?> accounts.')">Start Clearance</a>
						<?php
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
							<th>Maturity Date</th>
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
									<td><?php echo date("D, M j, Y",strtotime($created_at));  ?></td>
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