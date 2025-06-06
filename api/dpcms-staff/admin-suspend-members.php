<?php
	include '../includes/session.php';
	include("../includes/config.php");
	include '../includes/db-functions.php';
	include '../includes/call-api.php';

	if(!isset($_SESSION['admin_id'])) {
		header("Location: log-out?Logout_success");
	}

	if(isset($_GET['user_id']) && !empty($_GET['user_id']) ){

		$user_id = base64_decode($_GET['user_id']);
		$sess_id = $_SESSION['admin_id'];

		
		////now update the password via the user id with the reset password
		$suspend_user = mysqli_query($con,"UPDATE user SET can_login = 'no' WHERE user_id = '$user_id'"); 
		if ($suspend_user) {
			////log who reset the password
			$updateit = mysqli_query($con,"INSERT into admin_suspend_users_log SET admin_id = '$sess_id', user_id = '$user_id', admin_suspend_created_at = NOW()");
			
			////send sms ends here
			echo "<script>alert('The Member Suspended Successfully');
			window.location='manage-active-members';
			</script>";
		}
		else{
			echo "<script>alert('An error occurred while trying to suspend the customer. \n Please try again later or contact the website developer if these error persist');
			window.location='overview';
			</script>";
		}

	}
	else{
		echo "<script>alert('Incomplete OR Invalid Parameters passed. \n Please contact the website developer');
			window.location='overview';
			</script>";
	}

?>