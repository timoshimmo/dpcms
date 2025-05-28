<?php
				if (@$fetch_last_thrift_txn['thrift_fine']  > 0) {
					?>
					<div class="alert alert-danger mt-0">
						<strong>N.B:</strong> You still have some weekly thrift DEBT with FINE. Kindly pay up to be able to request for withdrawal.
						<a onclick="return confirm('Are you sure you want to pay for your weekly contribution DEFAULT? \n The system will charge your primary account \n\n OK to Continue');" href="clear-debt?user_id=<?php echo base64_encode($session_logged_in_user_id) ?>&user_account_id=<?php echo base64_encode($session_logged_in_user_account_id) ?>" class="btn btn-success btn-sm">Clear Debt</a>
					</div>
				<?php
				}

		?>
	