<?php include "sidebar.php" ?>
<?php

	$query_user_bank_details = query_user_virtual_bank_details($session_logged_in_user_id);

	$count_virtual_row = mysqli_num_rows($query_user_bank_details); 


?>
<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<h3 class="text-md-bold mb-3">Manage Your Virtual Account Details</h3>


		<div class="mt-5">
			
			<div class="table-responsive">
				<div class="d-flex justify-content-end">
				
					
				</div>
				<table id="table" class="table">
					<thead class="thead">
						<tr>
							<th>Bank Name</th>
							<th>Account Name</th>
							<th>Account Number</th>
							<th>Account Status</th>
							<th>Created At</th>
						</tr>
					</thead>
					<tbody class="tbody">
						<?php

							if ($count_virtual_row > 0) {

								$fetch_account_details = mysqli_fetch_array($query_user_bank_details);
							?>
								<tr>
								
									<td><?php echo $fetch_account_details['user_vt_acct_bank_name'];  ?></td>
									<td><?php echo substr($fetch_account_details['user_vt_acct_note'],31);  ?></td> 
									<td><?php echo $fetch_account_details['user_vt_acct_account_number'];  ?></td>
									<td><?php echo ($fetch_account_details['is_virtual_active']=='yes' ? '<font color="green">Active</font>' : '<font color="green">Inactive</font>');  ?></td>
									<td><?php echo date("d-M-Y h:iA", strtotime($fetch_account_details['user_vt_acct_created_at'])) ?></td>

									
								</tr>
						<?php
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