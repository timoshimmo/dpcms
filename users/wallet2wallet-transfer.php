<form class="d-flex flex-col form_transfer_wallet"  method="POST" style="gap:20px;">
			<div class="form-inline row align-items-center">
				<div class="col-sm-2">
				</div>
				<div class="col-sm-3">
					<label class="text-md">Transfer Type:</label>
				</div>
				<div class="col-sm-4" style="position:relative;"> 
					<select class="form-control select w2w_select_type_of_transfer" name="w2w_select_type_of_transfer" required>
						<option value="">Select Type of Transfer</option>
						<option value="self">Self</option>
						<option value="others">Others (3rd Party)</option>
						<option value="schedule_transfer">Schedule Transfer</option>
					</select>
				</div>
			</div>

			<!-- SELF ACCOUNT --> 
			<div class="form-inline row align-items-center">
				<div class="col-sm-2">
				</div>
				<div class="col-sm-3">
					<label class="text-md">Account to Debit:</label>
				</div>
				<div class="col-sm-4">
					<select class="form-control select self_w2w_select_acct_to_debit" name="self_w2w_select_acct_to_debit" required>
						<option value="">Select Account to Debit</option>
						<?php
						$cnt = 1;
						$query_user_all_account = query_user_all_account($session_logged_in_user_id);
						if (mysqli_num_rows($query_user_all_account)<=0) {
							echo "<option disabled>No Account available</option>";
						}
						else{
							while ($fetch_w2w = mysqli_fetch_array($query_user_all_account)) {
							?>

							<option value="<?php echo $fetch_w2w['user_account_id']; ?>"><?php echo $cnt++ ?>) <?php echo $fetch_w2w['user_account_token']; ?> <?php echo ($fetch_w2w['user_account_type'] =='primary' ? '<b>(Primary)</b>' : ''); ?></option>";
						<?php
							}
						}
						?>
						
					</select>
				</div>
			</div>


			<div class="form-inline row align-items-center">
				<div class="col-sm-2">
				</div>
				<div class="col-sm-3">
					<label class="text-md">Amount to Transfer:</label>
				</div>
				<div class="col-sm-4">
					<input type="number" class="form-control wallet_amt_to_transfer" name="wallet_amt_to_transfer" placeholder="Enter the Amount to Transfer" required>

					<input type="hidden" name="user_token" value="<?php echo md5($_SESSION['session_logged_in_user_id']); ?>"  required>
				</div>
			</div>

			<div class="form-inline row align-items-center">
				<div class="col-sm-2">
				</div>
				<div class="col-sm-3">
					<label class="text-md">Account to Credit:</label>
				</div>
				<div class="col-sm-4">
					<select class="form-control select self_w2w_select_acct_to_credit" name="self_w2w_select_acct_to_credit" required>
						<option value="">Select Account to Credit</option>
						<?php
						$cnt = 1;
						$query_user_all_account = query_user_all_account($session_logged_in_user_id);
						if (mysqli_num_rows($query_user_all_account)<=0) {
							echo "<option disabled>No Account available</option>";
						}
						else{
							while ($fetch_w2w = mysqli_fetch_array($query_user_all_account)) {
							?>

							<option value="<?php echo $fetch_w2w['user_account_id']; ?>"><?php echo $cnt++ ?>) <?php echo $fetch_w2w['user_account_token']; ?> <?php echo ($fetch_w2w['user_account_type'] =='primary' ? '<b>(Primary)</b>' : ''); ?></option>";
						<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<!-- SELF ACCOUNT -->



	

			<div class="d-flex justify-content-center">
				<button class="btn btn-main btn-sm noble_transfer_wallet">Transfer Wallet</button>
			</div>
		</form>


<script src="../js/jquery.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script src="../js/scripts.js"></script>