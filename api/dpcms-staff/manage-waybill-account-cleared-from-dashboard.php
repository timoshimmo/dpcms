<?php
include 'includes/header.php';
?>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">
		<?php include 'includes/nav-sidebar.php'; ?>

		<div class="main">
			<?php include 'includes/top-nav-header.php'; ?>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="mb-3">
						<h1 class="h3 d-inline align-middle">Manage Waybill Accounts Cleared from Dashboard - Settlements Account</h1>
					</div>

					<div class="card">
						<div class="card-header pb-0">

							<h5 class="card-title mb-0">Lists of Paid accounts cleared from dashboard. Waybill clearance accounts in descending order (Latest at the top).</h5>
						</div>
						<div class="card-body">
							<table id="datatables-reponsive" class="table table-responsive table-striped nowrap" width="100%">
								<thead>
									<tr>
										<th>S/N</th>
										<th>Full Name</th>
										<th>Phone No</th>
										<th>Account ID</th>
										<th>Settlements Account</th>
										<th>Waybill Created At</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr>
<?php
$cnt = 1;
/*$query_waybill_incentives = mysqli_query($con, "SELECT * FROM request_withdraw JOIN user ON user.user_id = request_withdraw.user_id JOIN settlement_account ON settlement_account.user_account_id = request_withdraw.user_account_id JOIN thrift_package ON thrift_package.thrift_package_id = request_withdraw.thrift_package_id JOIN thrift_transaction_details ON thrift_transaction_details.txn_id = request_withdraw.thrift_txn_id WHERE request_withdraw.request_withdraw_status='paid' AND request_withdraw.cronjob_fee='yes' AND thrift_transaction_details.incentive_pickup_type = 'Waybill' AND thrift_transaction_details.incentive_admin_delivery_status = 'pending' GROUP by request_withdraw.user_id ORDER by request_withdraw.request_withdraw_id DESC LIMIT 10");*/
$query_waybill_incentives = mysqli_query($con,"SELECT * FROM request_withdraw JOIN user ON user.user_id = request_withdraw.user_id JOIN thrift_package ON thrift_package.thrift_package_id = request_withdraw.thrift_package_id JOIN thrift_transaction_details ON thrift_transaction_details.txn_id = request_withdraw.thrift_txn_id WHERE request_withdraw.request_withdraw_status='paid' AND request_withdraw.account_closed='yes' AND thrift_transaction_details.incentive_pickup_type = 'Waybill'  AND request_withdraw_created_on > NOW() - INTERVAL 35 DAY GROUP by request_withdraw.user_id ORDER by request_withdraw.request_withdraw_id DESC");
	$count = mysqli_num_rows($query_waybill_incentives);
	//echo $count;

while($fetch_waybill = mysqli_fetch_array($query_waybill_incentives)){
	extract($fetch_waybill);

	///get the settlement acct.
	$query_sett = mysqli_query($con,"SELECT * FROM settlement_account WHERE user_account_id = '$user_account_id'");
		$count_sett = mysqli_num_rows($query_sett);
		$fetch_sett = mysqli_fetch_array($query_sett);

	/////////now that we group withdrawal request together, we need to get total number of settlement account that never been paid , still pending at the moment
				$query_pending_settlement_account = admin_query_all_paid_waybill_withdrawalable_acct_by_userid($user_id);
				$total_pending_settlement_account = mysqli_num_rows($query_pending_settlement_account);
				$fetch_pending_settlement_account = mysqli_fetch_array($query_pending_settlement_account); 
?>
										<td><strong><?php echo $cnt++; ?></strong></td>
										<td><?php echo ucwords($fullname); ?></td>
										<td><?php echo $phone_no; ?></td>
										<td><?php echo $fetch_sett['user_account_token']; ?></td>
										<td><?php echo $total_pending_settlement_account; ?></td>

										<td><?php echo date("l, F j, Y | h:i:sa",strtotime($request_withdraw_created_on)); ?></td>
										<td><a href="view-more-paid-cleared-account-waybill-info.php?thrift_txn_id=<?php echo base64_encode($thrift_txn_id)?>&waybill-user=<?php echo base64_encode($fullname)?>"><button class="btn btn-primary">View More Info</button></a>
										<a href="set-pending-waybill-incentives-delivered?thrift_txn_id=<?php echo base64_encode($thrift_txn_id) ?>&usertoken=<?php echo base64_encode($user_id) ?>"><button class="btn btn-info" onclick="return confirm('Are you sure you want to set this users waybill to DELIVERED ? \n\n OK to Proceed');">Set to Delivered</button></a>
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
			</main>

			<?php include 'includes/footer.php'; ?>
		</div>
	</div>

	<script src="js/app.js"></script>

	<script src="js/datatables.js"></script>

	<script>
/*		document.addEventListener("DOMContentLoaded", function() {
			// Datatables Orders
			$("#datatables-orders").DataTable({
				responsive: true,
				aoColumnDefs: [{
					bSortable: false,
					aTargets: [-1]
				}]
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