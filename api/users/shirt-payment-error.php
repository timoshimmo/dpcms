<?php

		if ($query_fetch_user_details['user_cloth_fee'] == "pending") {
		?>
				<div class="alert alert-danger mt-0">
						<strong>N.B:</strong> You need to pay a sum of N3,500 for Destiny Promoters shirt.
						<a href="shirt-payment?user_account_id=<?php echo base64_encode($session_logged_in_user_account_id) ?>&user_id=<?php echo base64_encode($session_logged_in_user_id); ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to pay for the Destiny Promoters shirt ?');">Pay Now</a>
				</div>
		<?php 
		}