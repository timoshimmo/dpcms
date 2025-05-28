<?php

$query_user_bank_details = query_user_virtual_bank_details($session_logged_in_user_id);
			$count_virtual_row = mysqli_num_rows($query_user_bank_details);
			if ($count_virtual_row ==1) {
				////do nothing
			}
			else{
				?>
			<div class="alert alert-danger mt-0">
				To fund your Destiny Promoters Account easily, please create a <b>Virtual Account Number</b> 
				<a href="create-virtual-account" class="btn btn-success btn-sm">Create Now</a>
			</div>
			<?php
			}

?>