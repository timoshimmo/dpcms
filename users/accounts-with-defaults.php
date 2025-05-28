<!DOCTYPE html>
<html>
<head>
	<title>Account With Defaults | Destiny Promoters </title>
<?php include "sidebar.php";
$query_defaults = mysqli_query($con,"SELECT * FROM user_account JOIN thrift_transaction_details ON user_account.user_account_id = thrift_transaction_details.user_account_id WHERE thrift_transaction_details.thrift_fine>0 AND thrift_transaction_details.txn_status = 'completed' AND thrift_transaction_details.thrift_txn_counter = 'off' AND thrift_transaction_details.user_id = '$session_logged_in_user_id' ORDER by user_account.user_account_id ASC");
$count_defaults = mysqli_num_rows($query_defaults);
//exit();
?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<?php include("shirt-payment-error.php");?>



		<h3 class="text-md-bold mb-3">Manage All Accounts with Defaults </h3>

		<div class="mt-5">
			

				<table id="table" class="table">
					<thead class="thead">
						<tr>
							<th>S/N</th>
							<th>Acct ID</th>
							<th>Balance</th>
							<th>Fine/Defaults</th>
							<th>Downline</th>
							<th>Downline Within 40days</th>
							<th>Referral with Active Plan</th>
							<th>Reg. Date</th>


							<!-- <th>Action</th> -->
						</tr>
					</thead>
					<tbody class="tbody">
						<?php
							$cnt = 1;
							if ($count_defaults > 0) {

								while($fetch_defaults_acct = mysqli_fetch_array($query_defaults)){
									extract($fetch_defaults_acct);
							////query all accounts with debt or paid
							$total_debt_contribution = getTotalDebtcontribution($user_account_id) * 1300;
							$the_users_fine = ($total_debt_contribution==0 ? "0" : "<font color='red'>₦".number_format($total_debt_contribution)."</font>");
							////query all accounts with debt or paid
							?>
								<tr>
									<td><?php echo $cnt++; ?></td>
									<td><?php echo $user_account_token; ?> <?php echo ($user_account_type == 'primary' ? "(Primary)" : ""); ?></td>
									<td>₦<?php echo number_format($user_account_wallet_amount); ?></td>
							<td><?php echo $the_users_fine; 
							if ($the_users_fine<=0) {
								//do nothing since defaults is zero or less than
							}
							else{
								?>
								<a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to clear the whole default once? \n\n It can not be Undo and the system will charge your primary account \n\n OK to Continue');" href="clear-whole-defaults-per-account?user_token=<?php echo base64_encode($user_id); ?>&txn_id=<?php echo base64_encode($fetch_defaults_acct['txn_id']); ?>">Clear All Default(s)</a>
							<?php
							}
							?> 

							</td>
									<td><?php echo $referral_count; ?></td>
									<td><?php echo $referral_count_within_forty_days; ?></td>
									<td><?php echo $total_referral_with_active_plan; ?></td>
									
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