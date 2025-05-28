<?php include "sidebar.php" ?>

<?php
		
	/*$output = query_user_data($session_logged_in_user_id, 'password');*/
	if( isset($_POST['change-password'])){

		$old_password = mysqli_real_escape_string($con, md5($_POST['old_password']));

		$new_password = mysqli_real_escape_string($con, md5($_POST['new_password']));

		$cnew_password = mysqli_real_escape_string($con, md5($_POST['cnew_password']));
		
		$current_password =  query_user_data($session_logged_in_user_id, 'password');

		if( $current_password != $old_password){
			$output = "<div class='alert alert-danger'>
				Old Password Does Not Match 
			</div>";
		} else if( $new_password != $cnew_password){

			$output = "<div class='alert alert-danger'> 
				New Password Does Not Match
			</div>"; 

		}else{
			$query_user_pass_update = update_user_password($session_logged_in_user_id, $new_password);
			if($query_user_pass_update){
				$output = "<div class='alert alert-success'>
					Password Successfully Changed. The system will now log you out. 
				</div>"; 
				header("refresh:3,url=logout");
			}
		}

	}

?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body pt-5">
		<h3 class="text-md-bold my-3">Change Password</h3>

		<div class="mt-5 investment-details-wrapper py-5">
			<?php

				if (isset($_POST['change-password'])) {
					echo $output;
				}

			?>
			
			<form class="d-flex flex-col space-mobile" method="POST"  style="gap:40px;">
		
			<div class="form-inline row align-items-center">
				<div class="col-sm-4">
					<label class="text-md">Old Password:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" value="<?php echo @$_POST['old_password']?>" required class="form-control input" name="old_password" placeholder="enter your old Password">
				</div>
			</div>



			<div class="form-inline row align-items-center">
				<div class="col-sm-4">
					<label class="text-md">New Password:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control input" value="<?php echo @$_POST['new_password']?>" name="new_password" placeholder="enter your new Password" required>
				</div>
			</div>

			<div class="form-inline row align-items-center">
				<div class="col-sm-4">
					<label class="text-md">Confirm New Password:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control input" value="<?php echo @$_POST['cnew_password']?>" name="cnew_password" placeholder="re-enter your new Password to confirm" required>
				</div>
			</div>
			

			<div class="d-flex justify-content-center">
				<button class="btn btn-main proceed" name="change-password">Change Password</button>
			</div>
		</form>
		
		</div>

	</div>

</div>


</body>
</html>