<?php
include 'includes/header.php';
if (isset($_GET["user_id"])) {
	$user_id = base64_decode($_GET["user_id"]);	
}else{
	header('location:log-out');
}
$fetch_bank_details = @mysqli_fetch_array(query_user_bank_details($user_id));
if (mysqli_num_rows(query_user_bank_details($user_id))<=0) {
	echo "<script>alert('No bank account details for this member yet. Tell the member to add a bank details.');
	window.location='manage-active-members';
	</script>";
}
else{
$fetch_next_of_kin_details = @query_user_details($user_id);
extract($fetch_next_of_kin_details);
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
						<h1 class="h3 d-inline align-middle">Bank Account information</h1>
					</div>

					<div class="row">

						<div class="col-xl-3">
						</div>
						<div class="col-xl-6">
							<div class="card">
								<div class="card-header">

									<h5 class="card-title mb-0">Bank Info for <u><?php echo @ucwords($fullname); ?></u></h5>
								</div>
								<div class="card-body">
									<div class="row g-0">
										
										<div class="col-sm-9 col-xl-12 col-xxl-9">
											<center><strong>Bank Account Details</strong></center>
										</div>
									</div>

									<table class="table table-sm mt-2 mb-4">
										<tbody>
											<tr>
												<th>Account Name</th>
												<td><?php echo @$fetch_bank_details['account_name']; ?></td>
											</tr>
											<tr>
												<th>Account Number</th>
												<td><?php echo @$fetch_bank_details['account_no']; ?></td>
											</tr>
											<tr>
												<th>Bank Name</th>
												<td><?php echo @$fetch_bank_details['lists_bankname'] ?></td>
											</tr>
										</tbody>
									</table>

								
									<center><a onclick="return confirm('Are you sure you want to delete the bank account details ? \n\n It can not be Undo');" href="admin-delete-members-bank-details?user_id=<?php echo base64_encode($user_id); ?>" class="btn btn-danger">DELETE BANK ACCOUNT</a></center>
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