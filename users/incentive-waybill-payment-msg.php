<?php
		if ($thrift_incentive_status == "pending" && $thrift_txn_status == 'completed' && $thrift_incentive_type) {
			?>
			<div class="alert alert-danger mt-0">
				<strong>N.B:</strong> You are to pay a sum of ₦<?php echo number_format($thrift_incentive_amount) ?> for incentives waybill. Kindly pay up to be able to request for withdrawal.
				<a onclick="return confirm('Are you sure you want to pay for this? \nThe system will charge your primary account wallet. \n It can not be Undo \n\nOK to Continue');" href="incentives-payment?user_id=<?php echo base64_encode($session_logged_in_user_id) ?>&user_account_id=<?php echo base64_encode($session_logged_in_user_account_id) ?>" class="btn btn-success btn-sm">Pay Now</a>
			</div>
		<?php
		}

?>
	