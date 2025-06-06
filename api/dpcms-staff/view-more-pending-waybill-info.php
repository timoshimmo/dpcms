<?php
include 'includes/header.php';
if (isset($_GET['thrift_txn_id']) && !empty($_GET['thrift_txn_id']) && isset($_GET['waybill-user']) && !empty($_GET['waybill-user'])) {
		$thrift_txn_id = base64_decode($_GET["thrift_txn_id"]);	
	/////get the user id that owns the txn id to list incentives out and type.
	$get_userid = query_thrift_status($thrift_txn_id, "user_id");

	$get_info = query_user_details($get_userid);

	$query_for_incentives = mysqli_query($con, "SELECT * FROM request_withdraw JOIN user ON user.user_id = request_withdraw.user_id JOIN user_account ON user_account.user_account_id = request_withdraw.user_account_id JOIN thrift_package ON thrift_package.thrift_package_id = request_withdraw.thrift_package_id JOIN thrift_transaction_details ON thrift_transaction_details.txn_id = request_withdraw.thrift_txn_id WHERE request_withdraw.request_withdraw_status='pending' AND request_withdraw.cronjob_fee='yes' AND request_withdraw.user_id = '$get_userid' AND thrift_transaction_details.incentive_pickup_type = 'Waybill' AND thrift_transaction_details.incentive_admin_delivery_status = 'pending' ORDER by request_withdraw.request_withdraw_id DESC ");
	$count = mysqli_num_rows($query_for_incentives);
}
else{
		header('location:log-out');
}
?>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">
		<?php include 'includes/nav-sidebar.php'; ?>

		<div class="main">
			<?php include 'includes/top-nav-header.php'; ?>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="mb-3">
						<h1 class="h3 d-inline align-middle">Pending Waybill Settlements for <span style="color: #CE0016;"><?php echo $get_info['fullname']; ?></span></h1>
					</div>

					<div class="card">
						<div class="card-header pb-0">

							<h5 class="card-title mb-0">Lists of pending waybill accounts breakdown for <span style="color: #CE0016;"><?php echo $get_info['fullname']; ?></span>.</h5>
						</div>
						<div class="card-body">
							<table id="datatables-reponsive" class="table table-responsive table-striped nowrap" width="100%">
								<thead>
									<tr>
										<th>S/N</th>
										<th>Account ID</th>
										<th>Account Type</th>
										<th>Incentive Type</th>
										<th>Pickup Type</th>
										<th>Delivery Number</th>
										<th>Delivery Amount</th>
										<th>Delivery State</th>
										<th>Delivery City</th>
									</tr>
								</thead>
								<tbody>
									<tr>
<?php
$cnt = 1;

while($fetch_waybill = mysqli_fetch_array($query_for_incentives)){
	extract($fetch_waybill);


?>
										<td><strong><?php echo $cnt++; ?></strong></td>
										<td><?php echo $user_account_token; ?></td>
										<td><?php echo $user_account_type; ?></td>
										<td><?php echo "Foodstuff & Provision"; ?></td>
										<td><span style="color: #CE0016;"><?php echo $incentive_pickup_type; ?></span></td>
										<td><?php echo $incentive_delivery_number; ?></td>

										<td><?php echo (empty($incentive_amount) ? '' : "₦".number_format($incentive_amount)); ?></td>

										<td><?php echo @get_state($incentive_delivery_state); ?></td>
										<td><?php echo @get_city($incentive_delivery_city); ?></td>
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