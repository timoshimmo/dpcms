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
						<h1 class="h3 d-inline align-middle">Manage All Members Defaults</h1>
						<a href="admin-filter-export-general-members-defaults.php"><button style="float:center;" class="btn btn-primary" onclick="return confirm('Are you sure you want to export the data as CSV? \n\n OK to continue');">Export as CSV</button></a>
						
					</div>

					<div class="card">
						<div class="card-header pb-0">

							<h5 class="card-title mb-0">Lists of defaults for general members.</h5>
						</div>
						<div class="card-body">
							<table id="datatables-reponsive" class="table table-responsive table-striped" width="100%">
								<thead>
									<tr>
										<th>S/N</th>
										<th>Full Name</th>
										<th>Phone Number</th>
										<th>Account Type</th>
										<th>Account Token</th>
										<th>Thrift Fine</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Thrift Status</th>
										<th>Created At</th>
									</tr>
								</thead>
								<tbody>
		<tr>
<?php
$cnt = 1;
$querylogin_history = mysqli_query($con,"SELECT * FROM thrift_transaction_details JOIN user_account ON user_account.user_account_id = thrift_transaction_details.user_account_id GROUP by user_account.user_id ORDER by thrift_transaction_details.txn_id DESC");
while($fetch_login_history = mysqli_fetch_array($querylogin_history)){
	extract($fetch_login_history);

	/////query users out
	$query_users_out = mysqli_query($con,"SELECT * FROM user WHERE user_id = '$user_id'");
	$fetch_user = mysqli_fetch_array($query_users_out);
?>
		<td><strong><?php echo $cnt++; ?></strong></td>
		<td><?php echo $fetch_user['fullname']; ?></td>
		<td><?php echo $fetch_user['phone_no']; ?></td>
		<td><?php echo $user_account_type; ?></td>
		<td><?php echo $user_account_token; ?></td>
		<td><?php echo $thrift_fine; ?></td>
		<td><?php echo $thrift_start_date; ?></td>
		<td><?php echo $thrift_end_date; ?></td>
		<td><?php echo $txn_status; ?></td>

		<td><?php echo date("l, F j, Y | h:i:sa",strtotime($txn_created_at)); ?></td>
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