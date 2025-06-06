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
						<h1 class="h3 d-inline align-middle">Manage InActive Members</h1>
					</div>

					<div class="card">
						<div class="card-header pb-0">

							<h5 class="card-title mb-0">These are newly registered accounts that are yet to pay their registration fee or their registration payment is pending confirmation.</h5>
						</div>
					<div class="card-body">
						<table id="datatables-reponsive" class="table table-responsive table-striped nowrap" style="width: 100%;">
						<thead>
						<tr>
						<th>S/N</th>
						<th>Full Name</th>
						<th>Phone No</th>
						
						<th>Address</th>
						<th>Wallet Balance</th>
						<th>Total Account</th>
						
						<th>Status</th>
						<th>Created On</th>
						<th>Actions</th>
						
						</tr>
						</thead>
						<tbody>
						<tr>
<?php
$cnt = 1;
$queryuser = mysqli_query($con,"SELECT * FROM user JOIN user_account ON user_account.user_account_id = user.user_account_id WHERE user.is_acct_active='no' AND user.user_reg_fee='pending' AND user.can_login = 'yes' ORDER BY user.user_id DESC LIMIT 5000");
while ($fetchuser = mysqli_fetch_array($queryuser)) {
	extract($fetchuser);
?>
						<td><strong><?php echo $cnt++; ?></strong></td>
						<td><?php echo ucwords($fullname); ?></td>
						<td><?php echo $phone_no; ?></td>
						
						<td><?php echo $address; ?></td>
						<td>₦<?php echo number_format($user_account_wallet_amount); ?></td>
						<td><?php 
	$all_user_accounts = mysqli_num_rows(query_user_all_account($user_id));
	echo $all_user_accounts;  ?></td>
						
						<td><span class="badge badge-success-light">ACTIVE</span></td>

						<td><?php echo date("l, F j, Y",strtotime($user_reg_date)); ?></td>

						<td>
							
							<a href="admin-update-members-name?user_id=<?php echo base64_encode($user_id); ?>" class="btn btn-info btn-sm"><i class="fas fa-user-edit"></i> Edit Name</a>
							<a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reset password for <?php echo ucwords($fullname); ?> ? \n User Phone Number is <?php echo $phone_no; ?>. \n\n If yes, OK to continue. \n Password would be reset to 123456 and user will received a reset SMS.');" href='admin-reset-members-password?user_id=<?php echo base64_encode($user_id) ?>&user_phone=<?php echo base64_encode($phone_no); ?>&user_name=<?php echo base64_encode($fullname); ?>'><i class="fas fa-lock-open"></i> Reset Password</a>
						</td>                      
						
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
		$('#datatables-reponsive').css('height', '100px');
		
	</script>
</body>

</html>