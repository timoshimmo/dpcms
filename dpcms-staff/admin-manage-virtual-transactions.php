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
						<h1 class="h3 d-inline align-middle">Manage Virtual Transactions</h1>
						<a href="admin-filter-export-virtual-transactions.php"><button style="float:center;" class="btn btn-primary" onclick="return confirm('Are you sure you want to export the data as CSV? \n\n OK to continue');">Export as CSV</button></a>

					</div>

					<div class="card">
						<div class="card-header pb-0">

							<h5 class="card-title mb-0">You can manage Virtual Accounts Payments transactions for all noblemerrians.</h5>
						</div>
					<div class="card-body">
						<table id="datatables-reponsive" class="table table-responsive table-striped nowrap" style="width: 100%;">
						<thead>
						<tr>
						<th>S/N</th>
						<th>TXN ID</th>
						<th>Full Name</th>
						<th>Phone No</th>
						<th>Virtal Ref</th>
						<th>Payment Amount</th>
						<th>Status</th>
						<th>Event Type</th>
						<th>Created At</th>
						</tr>
						</thead>
						<tbody>
						<tr>
<?php
$cnt = 1;
$query_all_wallet_tnx = mysqli_query($con,"SELECT * FROM webhook_event_transaction JOIN user_virtual_account ON user_virtual_account.virtual_account_tx_ref = webhook_event_transaction.tx_ref ORDER by webhook_event_transaction.webhook_event_transaction_id DESC"); 
while ($fetch_all_wallet_tnx = mysqli_fetch_array($query_all_wallet_tnx)) {
	extract($fetch_all_wallet_tnx);

	        $query_users_out = mysqli_query($con,"SELECT * FROM user WHERE user_id = '$user_id'");
            $fetch_user = mysqli_fetch_array($query_users_out);
?>
						<td><strong><?php echo $cnt++; ?></strong></td>
						<td><?php echo $webhook_transaction_id; ?></td>
						<td><?php echo ucwords($fetch_user['fullname']); ?></td>
						<td><?php echo $fetch_user['phone_no']; ?></td>
						<td><?php echo $flw_ref; ?></td>
						<td>₦<?php echo number_format($charged_amount); ?></td>
						<td><?php echo $status; ?></td>
						<td><?php echo $event_type; ?></td>
						<td><?php echo date("l, F j, Y",strtotime($event_type_created_at)); ?></td>                

						
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
		/*document.addEventListener("DOMContentLoaded", function() {
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