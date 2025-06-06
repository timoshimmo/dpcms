<?php
include 'includes/header.php';
if (isset($_GET['search_with_number']) && !empty($_GET['search_with_number'])) {
	$search_with_number = mysqli_real_escape_string($con,$_GET['search_with_number']);
	/////check if the phone number exist in db.
	$queryuser = mysqli_query($con,"SELECT * FROM user JOIN user_account ON user_account.user_account_id = user.user_account_id WHERE user_reg_fee !='pending' AND is_acct_active!='no'  AND can_login ='yes' AND phone_no = '$search_with_number' ORDER BY user.user_id ASC LIMIT 20000");
	$count_user = mysqli_num_rows($queryuser);
	///////anything search for, log it somewhere with the user id
	mysqli_query($con,"INSERT into admin_track_search_log SET admin_id = '$sess_id', search_value = '$search_with_number', track_search_created_at = NOW()");
	if ($count_user<=0) {
		echo "<script>alert('No User found, Please check if the member account is Active OR Phone Number is Valid');
		window.location='overview';
		</script>";
	}
}
else{

$queryuser = mysqli_query($con,"SELECT * FROM user JOIN user_account ON user_account.user_account_id = user.user_account_id WHERE user_reg_fee !='pending' AND is_acct_active!='no'  AND can_login ='yes' ORDER BY user.user_id ASC LIMIT 20000");
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
						<h1 class="h3 d-inline align-middle">Manage Active Members</h1>
						<a href="admin-filter-export-active-members.php"><button style="float:center;" class="btn btn-primary" onclick="return confirm('Are you sure you want to export the data as CSV? \n\n OK to continue');">Export as CSV</button></a>
					</div>


					<div class="card">
						<div class="card-header pb-0">

							<h5 class="card-title mb-0">Lists of Active Members. <span style="color: #CE0016;">This data tables only shows members that there accounts are active (NOK done, Photo uploaded & Bank Details Added)</span></h5>
						</div>
						<div class="card-body">
	<table id="datatables-reponsive" class="table table-responsive table-striped nowrap" width="100%">
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
						<th>Options</th>
						</tr>
						</thead>
						<tbody>
						<tr>
<?php
$cnt = 1;
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
							<a href="manage-next-of-kin?user_id=<?php echo base64_encode($user_id); ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i>  View NOK</a>
							<a href="view-members-accounts?user_id=<?php echo base64_encode($user_id); ?>" class="btn btn-warning btn-sm"><i class="fas fa-eye"></i>  View All Accounts</a>
							<a href="view-members-bank-details?user_id=<?php echo base64_encode($user_id); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-piggy-bank"></i> View Bank Details</a>
							<a href="admin-credit-members-wallet?user_id=<?php echo base64_encode($user_id); ?>" class="btn btn-success btn-sm"><i class="fas fa-wallet"></i> Credit Wallet</a>
							<a href="admin-update-members-name?user_id=<?php echo base64_encode($user_id); ?>" class="btn btn-info btn-sm"><i class="fas fa-user-edit"></i> Edit Name</a>
							<a href="admin-update-members-phone?user_id=<?php echo base64_encode($user_id); ?>" class="btn btn-info btn-sm"><i class="fas fa-phone"></i> Update Phone</a>
							<a href="admin-add-bulk-account?token=<?php echo base64_encode($user_id); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Thrift Account</a>
							<?php
								if (@query_generated_users_with_thrift_inactive($user_id, true)>0) {
							?>
							<a href="admin-assign-thrift-to-bulk-account?token=<?php echo base64_encode($user_id); ?>" class="btn btn-dark btn-sm"><i class="fas fa-industry"></i> Assign Thrift</a>
							<?php
							}
							?>
							<a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reset password for <?php echo ucwords($fullname); ?> ? \n User Phone Number is <?php echo $phone_no; ?>. \n\n If yes, OK to continue. \n Password would be reset to 123456 and user will received a reset SMS.');" href='admin-reset-members-password?user_id=<?php echo base64_encode($user_id) ?>&user_phone=<?php echo base64_encode($phone_no); ?>&user_name=<?php echo base64_encode($fullname); ?>'><i class="fas fa-lock-open"></i> Reset Password</a>

							<a style="display:;" href="admin-suspend-members?user_id=<?php echo base64_encode($user_id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to suspend this user? \n It can not be UNDO!! \n Click OK to Proceed')"><i class="fas fa-cancel"></i> Suspend User</a>
						</td>
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
						
						<a class="dropdown-item" href="view-single-member-wallet-transactions?user_id=<?php echo base64_encode($user_id); ?>">Manage Wallet Transactions</a>
						<a class="dropdown-item" href="view-members-paid-account?user_id=<?php echo base64_encode($user_id); ?>">View Paid Accounts</a>
						<a class="dropdown-item" href="view-members-fasttracked-account?user_id=<?php echo base64_encode($user_id); ?>">View Fast-tracked Accounts</a>
						<a class="dropdown-item" href="view-members-pending-settlement-account?user_id=<?php echo base64_encode($user_id); ?>">Pending Withdrawal Accounts</a>
						<a class="dropdown-item" href="#">Update Phone Number</a>
						<a class="dropdown-item" href="#">Suspend Account</a> 
						

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
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
              <form action="admin-filter-export-active-members.php" method="POST">
            <div class="modal-body">
                <p class="">Select the start & end date, then click on the submit button while you wait for some seconds for the data to download on your computer. </p>
                <p style='color:#CE0016;'>Please always add a day to your supposed <b>End date</b> for the system to filter and fetch the correct & complete data.</p>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>Start Date: </label>
                            <input type="date" name="filter_start_date" class="form-control date" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group">
                            <label>End Date: </label>
                            <input type="date" name="filter_end_date" class="form-control date" autocomplete="off" required>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm btn_close_modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" name="btn_filter_btw_dates" onclick="return confirm('Are you set to filter the data ? \n\n Click OK to Proceed \n\n Or close button to end it');">Submit</button>
            </div>
        </form>
    </div>
  </div>
</div>
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

		$(".btnexport_csv").click(function(e){
			e.preventDefault();
			$("#exampleModal").modal('show');
		});

		$(".btn_close_modal").click(function(e){
			e.preventDefault();
			$("#exampleModal").modal('hide');
		})

	</script>



</body>

</html>