<?php
include 'includes/header.php';
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
						<h1 class="h3 d-inline align-middle">Manage Accounts for <u><?php echo ucwords($fetch_users_info['fullname']); ?></u></h1>
					</div>

					<div class="row">
						<div class="col-12">
						
							<div class="card">
								<div class="card-header">
									
									<h6 class="card-subtitle text-muted">On this page, you can manage lists of accounts , view each accounts created date and also update each accounts referrals</h6>
								</div>
								<div class="card-body">
									<table id="datatables-reponsive" class="table table-responsive table-striped nowrap" style="width: 100%;">
										<thead>
											<tr>
												<th>S/N</th>
												<th>Account ID</th>
												<th>Wallet Balance</th>
												<th>Account Type</th>
												<th>Referral ID</th>
												<th>Total Referral</th>
												<th>Referral with Active Plans</th>
												<th>Current Weeks</th>
												<th>Weeks Paid</th>
												<th>Weeks Unpaid</th>
												<th>Total Default</th>
												<th>Created At</th>

												<th>Referral Update</th>
												<th>Action</th>



											</tr>
										</thead>
										<tbody>
										
	<tr>
<?php
$cnt = 1;
$query_accounts = query_user_all_account($user_id);
while($fetchaccounts = mysqli_fetch_array($query_accounts)){
	extract($fetchaccounts);
	$user_reg_date = explode(" ", $user_account_created_at)[0];
	$next_seven_months = strtotime("+6 month", strtotime($user_reg_date));
	$today = strtotime("today");

	///get the last account registered last cus it can't do referral
	$query_last_acctid = mysqli_query($con, "SELECT * FROM user_account WHERE user_id = '$user_id' ORDER by user_account_id DESC LIMIT 1");
	$fetch_last_id = mysqli_fetch_array($query_last_acctid);
	$the_last_id = $fetch_last_id['user_account_id'];


	/////query out weekly thrift
	$query_wklythrift = mysqli_query($con,"SELECT * FROM user_weekly_thrift WHERE user_account_id = '$user_account_id' AND user_id = '$user_id'");
	$count_wklythrift = mysqli_num_rows($query_wklythrift);

	////count paid thrift weeks
	$query_wklypaid = mysqli_query($con,"SELECT * FROM user_weekly_thrift WHERE user_account_id = '$user_account_id' AND user_id = '$user_id' AND user_weekly_thrift_status = 'paid'");
	$count_wkpaid = mysqli_num_rows($query_wklypaid);

	////count unpaid thrift weeks
	$query_wklydebt = mysqli_query($con,"SELECT * FROM user_weekly_thrift WHERE user_account_id = '$user_account_id' AND user_id = '$user_id' AND user_weekly_thrift_status = 'debt'");
	$count_wkdebt = mysqli_num_rows($query_wklydebt);

	////count defaults of that accounts. 
	$query_count_def = mysqli_query($con,"SELECT * FROM thrift_transaction_details WHERE user_account_id = '$user_account_id' AND user_id = '$user_id' ");
	$count_def = mysqli_num_rows($query_count_def);
	$fetch_def = mysqli_fetch_array($query_count_def);

?>
	<td><?php echo $cnt++; ?></td>
	<td><?php echo $user_account_token; ?></td>
	<td>₦<?php echo $user_account_wallet_amount ? number_format($user_account_wallet_amount):0; ?></td>
	<td><?php echo ucwords($user_account_type); ?></td>
	<td><?php echo $referral_id ?></td>
	<td><?php echo $referral_count ?></td>
	<td><?php echo $total_referral_with_active_plan ?></td>
	<td><?php echo $count_wklythrift; ?></td>
	<td><?php echo $count_wkpaid ?></td>
	<td><?php echo $count_wkdebt ?></td>
	<td>₦<?php echo number_format($fetch_def['thrift_fine']); ?></td>
	<td><?php echo date("D, M j, Y",strtotime($user_account_created_at)); ?></td>
	<td>
		<?php
			if ($referral_count>=1 && $total_referral_with_active_plan>=1) {
				// do nothing.
			}
			else{
				//echo $fetch_last_id['user_account_id'];
				if($today >= $next_seven_months){
						echo "<font color='red'>Exceeded 6 Months!!</font>";
					}
				else if ($the_last_id == $user_account_id) {
					echo "<font color='red'>It's last Account</font>";
				}
				else{
		?>
		<a onclick="return confirm('Are you sure you want to update the account referral ? \n\n OK to Proceed');" href="admin-update-account-referral?user_id=<?php echo base64_encode($user_id); ?>&user_account_id=<?php echo base64_encode($user_account_id); ?>" class="btn btn-primary btn-sm"><i class="fas fa-users"></i> Update Referral</a></td>
		<?php
			}
			}
		?>
		<td>
			<?php
			if ($user_account_type=='primary') {
			?>
			<a href="admin-debit-members-wallet?user_id=<?php echo base64_encode($user_id); ?>" class="btn btn-danger btn-sm"><i class="fas fa-wallet"></i> Debit Wallet</a>
			<?php
			}
			?>
		</td>
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
   		  "scrollX": true
   		  //$.fn.dataTable.ext.errMode = 'none';
  });
		$('#datatables-reponsive').css('height', '100px');
	</script>
</body>

</html>


<!-- 		<div class="card-actions float-end">
						<div class="dropdown position-relative">
						<a href="#" data-bs-toggle="dropdown" data-bs-display="static">
						<i class="align-middle" data-feather="more-horizontal"></i>
						</a>

						<div class="dropdown-menu dropdown-menu-end">
						<a class="dropdown-item" href="#">Update Referral</a>

						</div>
						</div>
						</div> -->