<?php include "sidebar.php" ?>
<?php

	if (isset($_GET['thrift_txn_id']) && !empty($_GET['thrift_txn_id']) && isset($_GET['user_account_id']) && !empty($_GET['user_account_id'])) {

		$thrift_txn_id = base64_decode($_GET['thrift_txn_id']);

		$thrift_user_account_id = base64_decode($_GET['user_account_id']);
	}


?>
<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<h3 class="text-md-bold mb-3">Manage Thrift Weekly History</h3>

		<div class="mt-5">
			
			<div class="table-responsive">
				
				<table id="table" class="table">
					<thead class="thead">
						<tr>
							<th>Thrift Week</th>
							<th>Thrift Status</th>
							<th>Action</th>
							<th>Created Date</th>
						</tr>
					</thead>
					<tbody class="tbody">
						<?php

							$query_weekly_thrift = query_user_weekly_thrift($thrift_txn_id, $thrift_user_account_id);

							if ( mysqli_num_rows($query_weekly_thrift) > 0) {
								while($fetch_weekly_thrift = mysqli_fetch_array($query_weekly_thrift)){
										extract($fetch_weekly_thrift);
									?>
									<tr>
										<td><?php echo $user_thrift_current_weeks ?></td>
										<td style="color: <?php echo $user_weekly_thrift_status == "debt" ? "red" : "green" ?>;"><?php echo $user_weekly_thrift_status ?></td>
										<td><?php
										if ($user_weekly_thrift_status == "debt") {
										?>

<a href="users-clear-weekly-debt?txn_id=<?php  echo base64_encode($thrift_txn_id) ?>&weekly_thrift_id=<?php echo base64_encode($user_weekly_thrift_id) ?>&package_id=<?php echo base64_encode($thrift_package_id) ?>&user_id=<?php echo base64_encode($user_id) ?>&thrift_week=<?php echo base64_encode($user_thrift_current_weeks)?>&user_account_id=<?php echo base64_encode($user_account_id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to clear this default for week <?php echo $user_thrift_current_weeks?> ? \n You\'d be charged ₦2,600 instead of normal weekly ₦1,300 contribution from your primary account wallet to clear this default. \n\n This action can\'t be Undo. \n\n Click OK to Continue');">Clear Default</a>
										<?php
										}

										?></td>
										<td><?php echo date("D, d-M-Y", strtotime($user_weekly_thrift_created_at)) ?></td>
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