<?php include "sidebar.php"; ?>
<?php 
	$query_next_settlement = mysqli_query($con,"SELECT * FROM request_withdraw JOIN user_account ON user_account.user_account_id = request_withdraw.user_account_id WHERE request_withdraw.request_withdraw_status ='pending' AND request_withdraw.cronjob_fee='yes' AND request_withdraw.account_closed = 'no' AND request_withdraw.weekly_thrift_moved = 'no' AND request_withdraw.user_id = '$session_logged_in_user_id'") or die(mysqli_error($con));
	$exist_account = mysqli_num_rows($query_next_settlement);
?>
<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<h3 class="text-md-bold mb-3">Lists of Accounts for Next Settlement</h3>

		<?php 
		if (isset($_GET['settlement']) && !empty($_GET['settlement'])) {
			echo "You just requested for withdrawal, please allow the system 10mins to calculate your empowernment fee to know the accounts eligible for ₦150,000 or ₦75,000 using the account <b>total downline and referral with active plan</b> logic. <br><br>";

			echo "You can refresh this page after 10mins or click <a href='list-of-acct-for-next-settlement' style='color:red'>here</a> after 10mins to view updated data.";
		}
		else{
			echo "These are the lists of settlement acounts after you processed the clearance and  requested for withdrawal.<br><br>";
			echo "On this page, you can view your accounts eligible for ₦150,000 or ₦75,000 using the account <b>total downline and referral with active plan</b> logic.<br><br>";
			echo "You can also know your accounts settlement day.";
		}
		?>


		<div class="mt-5">
			
			<div class="table-responsive">
				<div class="d-flex justify-content-end">
					<?php
						/*if ($exist_account <= 0 ) {
							echo '<a href="add-bank-account" class="btn btn-sm btn-main">Add Bank Account</a>';
						}*/


					?>
					
				</div> 
				<table id="table" class="table"> 
					<thead class="thead">
						<tr>
							<th>S/N</th>
							<th>Acct ID</th>
							<th>Wallet Amount</th>
							<th>Empowerment Fee</th>
							<th>Payment Status</th>
							<th>Settlement Day/Date</th>
							<th>Clearance Date</th>
						</tr>
					</thead>
					<tbody class="tbody">
						<?php
							$cnt = 1;
							if ($exist_account > 0) {

								while($fetch_next_settlement = mysqli_fetch_array($query_next_settlement)){
								extract($fetch_next_settlement);
							?>
								<tr> 
									<td><?php echo $cnt++; ?></td>
									<td><?php echo $user_account_token;  ?><br><?php echo ($user_account_type == 'primary' ? "<font color='red'>(primary)</a>" : "")?></td>
									<td>₦<?php echo number_format($user_account_wallet_amount);?></td>
									<td>₦<?php echo number_format($request_withdrawable_amount);?></td>
									<td style="color: #CE0016;"><?php echo $request_withdraw_status; ?></td>
									<td><?php 
									//echo $request_withdraw_created_on;
									$date_to_day = strtotime($request_withdraw_created_on);
									$the_day = date('D', $date_to_day);
									//echo $the_day;

									////conditional statement to know date settlements fall on
									if ($the_day=='Mon' || $the_day=='Tue' || $the_day=='Wed') {
										
									$date = new DateTime($request_withdraw_created_on);

									// Modify the date it contains
									$date->modify('next friday');

									// Output
									$actual_settl_day = $date->format('Y-m-d');
									echo date("D, M j, Y",strtotime($actual_settl_day));
									}
									else if ($the_day=='Thu' || $the_day=='Fri' || $the_day=='Sat' || $the_day=='Sun') {
										
									$date = new DateTime($request_withdraw_created_on);

									// Modify the date it contains
									$date->modify('next wednesday');

									// Output
									$actual_settl_day = $date->format('Y-m-d');
									echo date("D, M j, Y",strtotime($actual_settl_day));

									}
									else{
										/////do nothing
									}
									?></td>
									<td><?php echo date("D, M j, Y",strtotime($request_withdraw_created_on));  ?></td>

									
								</tr>
						<?php
							}
						}
							else{
								//echo "dkjs";
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