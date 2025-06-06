<?php
include 'includes/header.php';
if (isset($_GET["user_id"])) {
	$user_id = base64_decode($_GET["user_id"]);	
}else{
	header('location:log-out');
}

$fetch_next_of_kin_details = query_user_details($user_id);
extract($fetch_next_of_kin_details);
?>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<div class="wrapper">
	<?php include 'includes/nav-sidebar.php'; ?>

		<div class="main">
			<?php include 'includes/top-nav-header.php'; ?>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="mb-3">
						<h1 class="h3 d-inline align-middle">User Profile with the Virtual Account</h1>
					</div>

					<div class="row">

						<div class="col-xl-3">
						</div>
						<div class="col-xl-6">
							<div class="card">
								<div class="card-header">

									<h5 class="card-title mb-0">Profile Information for <u><?php echo ucwords($fullname); ?></u></h5>
								</div>
								<div class="card-body">
									<div class="row g-0">
										
										<div class="col-sm-9 col-xl-12 col-xxl-9">
											<center><strong>Customer Details</strong></center>
										</div>
									</div>

									<table class="table table-sm mt-2 mb-4">
										<tbody>
											<tr>
												<th>Name</th>
												<td><?php echo ucwords($fullname); ?></td>
											</tr>
											<tr>
												<th>Gender</th>
												<td><?php echo ucwords($gender); ?></td>
											</tr>
											<tr>
												<th>Date of Birth</th>
												<td><?php echo $dob; ?></td>
											</tr>
											<tr>
												<th>Phone Number</th>
												<td><?php echo $phone_no; ?></td>
											</tr>
											<tr>
												<th>Address</th>
												<td><?php echo ucwords($address); ?></td>
											</tr>
											<tr>
												<th>Status</th>
												<td><?php echo ($is_acct_active =='yes' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Not Active</span>' ); ?></td>
											</tr>
											<tr>
												<th>Created On</th>
												<td><?php echo date("l, F j, Y",strtotime($user_reg_date)); ?></td>
											</tr>
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

</body>

</html>