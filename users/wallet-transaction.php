<?php include "sidebar.php" ?>
<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<h3 class="text-md-bold mb-3">Wallet Transaction Details</h3>

		<div class="mt-5">
			<div class="table-responsive">
				<table id="table" class="table">
					<thead class="thead">
						<tr>
							<th>S/N</th>
							<th>Transaction Ref</th>
							<th>Amount</th>
							<th>Payment Status</th>
							<th>Payment Method</th>

							<th>Payment Date</th>
						</tr>
					</thead>
					<tbody class="tbody">
						<?php

						$query_wallet_tnx = get_user_wallet_transactions($session_logged_in_user_account_id);

						if( mysqli_num_rows($query_wallet_tnx) > 0){
							$count = 0;

							while($fetch_tnx_details = mysqli_fetch_array($query_wallet_tnx)){
								?>

									<tr>
										<td><?php echo ++$count; ?></td>
										<td><?php echo $fetch_tnx_details['wallet_txn_ref']; ?></td>
										<td>₦<?php echo number_format($fetch_tnx_details['wallet_payment_amount']); ?></td>
										<td><button class="btn <?php echo $fetch_tnx_details['wallet_txn_status'] == 'successful' ? 'btn-success': 'btn-danger'  ?>"><?php echo $fetch_tnx_details['wallet_txn_status'] ?></button></td>
										<td><?php echo ($fetch_tnx_details['wallet_payment_txn_id']=='manually' ? "Manual Funding" : "Gateway Funding"); ?></td>

										<td><?php echo date("d-M-Y h:iA", strtotime($fetch_tnx_details['wallet_txn_created_at'])) ?></td>
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