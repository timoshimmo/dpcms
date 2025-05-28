<?php include "sidebar.php" ?>
<?php

	$query_user_bank_details = query_user_bank_details($session_logged_in_user_id);

	$exist_account = mysqli_num_rows($query_user_bank_details); 


?>
<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<h3 class="text-md-bold mb-3">Manage Bank Details</h3>


		<div class="mt-5">
			
			<div class="table-responsive">
				<div class="d-flex justify-content-end">
					<?php
						if ($exist_account <= 0 ) {
							echo '<a href="add-bank-account" class="btn btn-sm btn-main">Add Bank Account</a>';
						}
					?>
					
				</div>
				<table id="table" class="table">
					<thead class="thead">
						<tr>
							<th>Bank Name</th>
							<th>Account Name</th>
							<th>Account Number</th>
							<!-- <th>Action</th> -->
						</tr>
					</thead>
					<tbody class="tbody">
						<?php

							if ($exist_account > 0) {

								$fetch_account_details = mysqli_fetch_array($query_user_bank_details);
							?>
								<tr>
								
									<td><?php echo $fetch_account_details['lists_bankname']  ?></td>
									<td><?php echo $fetch_account_details['account_name']  ?></td>
									<td><?php echo $fetch_account_details['account_no']  ?></td>
									<!-- <td><a onclick="return confirm('Are you sure you want to delete this?')" href="delete-bank-account?user_id=<?php echo base64_encode($fetch_account_details['id']) ?>" class="btn btn-danger">Delete</a></td> -->
									
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