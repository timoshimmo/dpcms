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
						<h1 class="h3 d-inline align-middle">Manage All Virtual Account Transactions for <u><?php echo ucwords($fetch_users_info['fullname']); ?></u></h1>
					</div>

					<div class="row">
						<div class="col-12">
						
							<div class="card">
								<div class="card-header">
									
									<h6 class="card-subtitle text-muted">On this page, you can manage and view all virtual transactions for a selected member. PRIMARY ACCOUNTS ONLY. </h6>
								</div>
								<div class="card-body">
									<table id="datatables-reponsive" class="table table-responsive table-striped nowrap" style="width: 100%;">
										<thead>
											<tr>
												<th>S/N</th>
												<th>Txn ID</th>
												<th>Customer ID</th>
												<th>Session ID</th>
												<th>Amount</th>
												<th>Currency</th>
												<th>Status</th>
												<th>Created On</th>
											</tr>
										</thead>
										<tbody>
										
											<tr>
<?php
$cnt = 1;
$query_accounts = query_user_virtual_account_transactions($user_id);
if (mysqli_num_rows($query_accounts)<=0) {
	// this shows it doesn't exist
echo "<td colspan='13'><span style='color:red'>No Record found. please check back later</span></td>";

}
else{
while($fetchaccounts = mysqli_fetch_array($query_accounts)){
	extract($fetchaccounts);
?>
	<td><?php echo $cnt++; ?></td>
	<td><?php echo $webhook_transaction_id; ?></td>
	<td><?php echo $tx_ref; ?> </td>
	<td><?php echo $flw_ref; ?> </td>
	<td>₦<?php echo number_format($amount); ?></td>
	<td><?php echo $currency; ?></td>
	<td style="color: green;"><?php echo $status; ?></td>
	<td><?php echo date("D, M j, Y",strtotime($event_type_created_at)); ?></td>


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
   		  "scrollX": true
   		  //$.fn.dataTable.ext.errMode = 'none';
  });
	</script>
</body>

</html>