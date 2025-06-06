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
						<h1 class="h3 d-inline align-middle">Manage My Login History</h1>
					</div>

					<div class="card">
						<div class="card-header pb-0">

							<h5 class="card-title mb-0">Lists of times i've logged in so far.</h5>
						</div>
						<div class="card-body">
							<table id="datatables-reponsive" class="table table-responsive table-striped" width="100%">
								<thead>
									<tr>
										<th>S/N</th>
										<th>IP ADDRESS</th>
										<th>Created At</th>
									</tr>
								</thead>
								<tbody>
									<tr>
<?php
$cnt = 1;
$querylogin_history = mysqli_query($con,"SELECT * FROM admin_login JOIN admin ON admin.admin_id = admin_login.admin_id ORDER by admin_login.admin_login_id DESC");
while($fetch_login_history = mysqli_fetch_array($querylogin_history)){
	extract($fetch_login_history);
?>
										<td><strong><?php echo $cnt++; ?></strong></td>
										<td><?php echo $ip_address; ?></td>
										<td><?php echo date("l, F j, Y | h:i:sa",strtotime($login_date)); ?></td>
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