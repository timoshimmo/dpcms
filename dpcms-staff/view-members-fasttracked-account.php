<?php
include 'includes/header.php';
///////THESE ARE ACCOUNTS THAT HAVE BEEN SETTLED AND NOT ON THE USERS DASHBOARD ANYMORE.
if (isset($_GET["user_id"])) {
	$user_id = base64_decode($_GET["user_id"]);	
}else{
	header('location:log-out');
}

$fetch_users_info = query_user_details($user_id);
?>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">
	<?php include 'includes/nav-sidebar.php'; ?>

		<div class="main">
			<?php include 'includes/top-nav-header.php'; ?>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="mb-3">
						<h1 class="h3 d-inline align-middle">Manage Fast-tracked Accounts for <u><?php echo ucwords($fetch_users_info['fullname']); ?></u></h1>
					</div>

					<div class="row">
						<div class="col-12">
						
							<div class="card">
								<div class="card-header">
									
									<h6 class="card-subtitle text-muted">These are the lists of accounts that have been fast tracked before and already cleared for settlement and have also been paid for empowerment. These types of fast tracked accounts are removed from the user's dashboard after settlements have been fully settled.</h6>
								</div>
								<div class="card-body">
									<table id="datatables-reponsive" class="table table-responsive table-striped nowrap" style="width: 100%;">
										<thead>
											<tr>
												<th>S/N</th>
												<th>Account ID</th>
												<th>Wallet Balance as @Paid</th>
												<th>Account Type</th>
												<th>Referral ID</th>
												<th>Total Referral</th>
												<th>Referral Within 40days</th>
												<th>Referral with Active Plans</th>
												<th>Previous Thrift End Date</th>
												<th>Current Thrift End Date</th>
												<th>Account Created On</th>
												<th>Fast Tracked On</th>
												<th>Paid On</th>


											</tr>
										</thead>
										<tbody>
										
											<tr>
<?php
$cnt = 1;
$query_accounts = get_users_fasttracked_settlements_account($user_id);
if (mysqli_num_rows($query_accounts)<=0) {
	// this shows it doesn't exist
echo "<td colspan='13'><span style='color:red'>No Record found. please check back later</span></td>";

}
else{
while($fetchaccounts = mysqli_fetch_array($query_accounts)){
	extract($fetchaccounts);
?>
	<td><?php echo $cnt++; ?></td>
	<td><?php echo $user_account_token; ?></td>
	<td>₦<?php echo $user_account_wallet_amount ? number_format($user_account_wallet_amount):0; ?></td>
	<td><?php echo ucwords($user_account_type); ?></td>
	<td><?php echo $referral_id ?></td>
	<td><?php echo $referral_count ?></td>
	<td><?php echo $referral_count_within_forty_days; ?></td>
	<td><?php echo $total_referral_with_active_plan ?></td>
	<td><?php echo date("D, M j, Y",strtotime($thrift_previous_end_date)); ?></td>
	<td><?php echo date("D, M j, Y",strtotime($thrift_current_end_date)); ?></td>
	<td><?php echo date("D, M j, Y",strtotime($user_account_created_at)); ?></td>
	<td><?php echo date("D, M j, Y",strtotime($thrift_fast_track_created_at)); ?></td>
	<td><?php echo date("D, M j, Y",strtotime($settlement_account_created_at)); ?></td>


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

				</div>
			</main>

			<?php include 'includes/footer.php'; ?>
		</div>
	</div>

	<script src="js/app.js"></script>

	<script src="js/datatables.js"></script>

	<script>
/*		document.addEventListener("DOMContentLoaded", function() {
			// Datatables Responsive
			$("#datatables-reponsive").DataTable({
				responsive: true,
				scrollX: true
			});
		});*/

		  $('#datatables-reponsive').DataTable({
   		  "scrollX": true,
   		  //$.fn.dataTable.ext.errMode = 'none';
  });
	</script>
</body>

</html>