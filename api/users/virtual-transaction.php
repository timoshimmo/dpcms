<?php include "sidebar.php" ?>
<?php
	$query_user_virtual_account = query_user_virtual_account_transactions($session_logged_in_user_id);

	$exist_virtual_account = mysqli_num_rows($query_user_virtual_account); 


?>
<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<h3 class="text-md-bold mb-3">Manage All Virtual Transactions</h3>


		<div class="mt-5">
			
			<div class="table-responsive">
				<div class="d-flex justify-content-end">
					<?php
						if ($exist_virtual_account <= 0 ) {
							echo '<a href="create-virtual-account" class="btn btn-sm btn-main">Create Virtual Account</a>';
						}
						else{
						?>
						<!-- <a href="javascript:void();" onclick="swal('Virtual Transactions Statements','You will be able to generate all your virtual account transactions history in PDF just like Bank Statements. The features is coming soon.','success');" class="btn btn-sm btn-main">Generate Virtual Statement</a> -->

						<form class="form-inline" target="_blank" method="POST" action="to-pdf/generate-virtual-transaction-pdf.php">
						<button type="submit" id="pdf" name="generate_pdf" class="btn btn-main btn-sm"><i class="fa fa-pdf" aria-hidden="true"></i>
						Generate PDF</button>
						</form>
						<?php
						}
					?>
					
				</div>
				<table id="table" class="table">
					<thead class="thead">
						<tr>
							<th>S/N</th>
							<th>Narration</th>
							<th>Virtual Ref</th>
							<th>Phone No</th>
							<th>Amount</th>
							<th>Payment Type</th>
							<th>Status</th>
							<th>Created Date</th>

							<!-- <th>Action</th> -->
						</tr>
					</thead>
					<tbody class="tbody">
						
						<?php
						$cnt = 1;
						if ($exist_virtual_account > 0) {

								while($fetch_virtual_details = mysqli_fetch_array($query_user_virtual_account)){
							?>
							<tr>	
									<td><?php echo $cnt++; ?></td>
									<td><?php echo $fetch_virtual_details['narration'];  ?></td>
									<td><?php echo $fetch_virtual_details['flw_ref'];  ?></td>
									<td><?php echo $fetch_virtual_details['phone_number'];  ?></td>
									<td><b>₦<?php echo number_format($fetch_virtual_details['charged_amount']); ?></b></td>
									<td><?php echo $fetch_virtual_details['payment_type'];  ?></td>
									<td><font color='green'><?php echo $fetch_virtual_details['status'];  ?></font></td>
									<td><?php echo date("l, F j, Y",strtotime($fetch_virtual_details['event_type_created_at']));  ?></td>

									
									
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