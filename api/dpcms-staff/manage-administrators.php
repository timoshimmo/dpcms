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
						<h1 class="h3 d-inline align-middle">Manage Administrators</h1>
					</div>

					<div class="card">
						<div class="card-header pb-0">

							<h5 class="card-title mb-0">Lists of Administrators</h5>
						</div>
						<div class="card-body">
							<table id="datatables-orders" class="table table-responsive table-striped" width="100%">
								<thead>
									<tr>
										<th>S/N</th>
										<th>Names</th>
										<th>Email Address</th>
										<th>Role</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<tr>
<?php
$cnt = 1;
$queryadmin = mysqli_query($con,"SELECT * FROM admin ORDER by admin_id DESC");
while($fetchadmin = mysqli_fetch_array($queryadmin)){
	extract($fetchadmin);
?>
										<td><strong><?php echo $cnt++; ?></strong></td>
										<td><?php echo ucwords($username); ?></td>
										<td><?php echo strtolower($email); ?></td>
										<td><span class="badge badge-success-light"><?php echo ucwords(str_replace("_", " ", $admin_role)); ?></span></td>
										<td>
										<?php
										if ($admin_role!='super_admin') {
										 	////do nothing
										 } 
										 else{
										?>
										<div class="card-actions float-end" style="display: none;">
										<div class="dropdown position-relative">
										<a href="#" data-bs-toggle="dropdown" data-bs-display="static">
										<i class="align-middle" data-feather="more-horizontal"></i>
										</a>

										<div class="dropdown-menu dropdown-menu-end">
										<a class="dropdown-item" href="#">Suspend Admin</a>
										<a class="dropdown-item" href="#">Reset Password</a>
										<a class="dropdown-item" href="#">Update Role</a>
										<a class="dropdown-item" href="#">Delete Admin</a>
										</div>
										</div>
										</div>
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
			</main>

			<?php include 'includes/footer.php'; ?>
		</div>
	</div>

	<script src="js/app.js"></script>

	<script src="js/datatables.js"></script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// Datatables Orders
			$("#datatables-orders").DataTable({
				responsive: true,
				aoColumnDefs: [{
					bSortable: false,
					aTargets: [-1]
				}]
			});
		});
	</script>
</body>

</html>