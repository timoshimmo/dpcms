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
						<h1 class="h3 d-inline align-middle">Lists of Manual Funding Payment Proofs</h1>
					</div>

					<div class="card">
						<div class="card-header pb-0">

							<h5 class="card-title mb-0">The data tables will be showing 500 lists of (<b style="color: #CE0016;">Confirmed & Pending</b>) uploaded wallet payment proof.</h5>
						</div>
						<div class="card-body">
	<table id="datatables-reponsive" class="table table-responsive table-striped nowrap" width="100%">
								<thead>
									<tr>
										<th>S/N</th>
										<th>Full Name</th>
										<th>Mobile No</th>
										<th>Account ID</th>
										<th>Payment Type</th>
										<th>Created Date</th>
										<th>Action</th>
										</tr>
								</thead>
								<tbody>
									<tr>
<?php
		$cnt = 1;
		$payment_type = "fund wallet";
		$query_all_users_proof_tnx = admin_query_payment_proof($payment_type);

		if( mysqli_num_rows($query_all_users_proof_tnx) > 0 ){
			$count = 0;
		while ($fetch_all_users_proof_tnx = mysqli_fetch_array($query_all_users_proof_tnx)) {
				extract($fetch_all_users_proof_tnx);
			?>
			<td><strong><?php echo $cnt++; ?></strong></td>
			<td><?php echo ucwords($fullname); ?></td>
			<td><?php echo $phone_no; ?></td>
			<td><?php echo $user_account_token; ?><p class="text-center" style="color: #CE0016;">(<?php echo $user_account_type?>)</p></td>
			<td><?php echo ucwords($payment_type); ?></td>
			<td><?php echo date("D, M j, Y h:i:sA",strtotime($payment_created_date)) ?></td>
			<td>
				<?php
				if ($payment_type == "fund wallet") {
					include 'includes/table-actions/fund-wallet-proof-action.php';
				}
				else{
					////do nothing.
				}
				?>
			</td>
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
			</main>

<?php 
include 'includes/modal-dialog/admin-payment-proof-fund-wallet-modal.php';
include 'includes/footer.php'; 
?>
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
	</script>
</body>

</html>