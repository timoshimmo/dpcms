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
						<h1 class="h3 d-inline align-middle">Manage Wallets Transactions</h1>
						<a href="admin-filter-export-wallet-transactions.php"><button style="float:center;" class="btn btn-primary" onclick="return confirm('Are you sure you want to export the data as CSV? \n\n OK to continue');">Export as CSV</button></a>

					</div>

					<div class="card">
						<div class="card-header pb-0">

							<h5 class="card-title mb-0">You can manage wallet transactions for all DPCMS Members.</h5>
						</div>
					<div class="card-body">
						<table id="datatables-reponsive" class="table table-responsive table-striped nowrap" style="width: 100%;">
						<thead>
						<tr>
						<th>S/N</th>
						<th>Full Name</th>
						<th>Phone No</th>
						<th>User ID</th>
						<th>Transaction Ref</th>
						<th>Payment Transaction</th>
						<th>Payment Amount</th>
						<th>Transaction Status</th>
						<th>Created At</th>
						</tr>
						</thead>
						<tbody>
						<tr>
<?php
$cnt = 1;
$query_all_wallet_tnx = admin_fetch_wallet_transactions();
while ($fetch_all_wallet_tnx = mysqli_fetch_array($query_all_wallet_tnx)) {
	extract($fetch_all_wallet_tnx);
?>
						<td><strong><?php echo $cnt++; ?></strong></td>
						<td><?php echo ucwords($fullname); ?></td>
						<td><?php echo $phone_no; ?></td>
						<td><?php echo $user_account_token;?>
						<p><b class="text-primary text-center">(<?php echo $user_account_type?>)</b></p>
						</td>
						<td><?php echo $wallet_txn_ref; ?></td>
						<td>
							<b><?php echo ($wallet_payment_txn_id=='manually' ? 'Manual Funding' : 'Gateway Payment'); ?></b>
						</td>
						<td>₦<?php echo number_format($wallet_payment_amount) ?></td>
						<td><b><?php echo ($wallet_txn_status=='pending' ? '<font color="red">Pending</font>' : '<font color="green">Successful</font>'); ?></b></td>
						<td><?php echo date("l, F j, Y",strtotime($wallet_txn_created_at)); ?></td>                
						
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