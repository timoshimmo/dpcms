<?php
	include '../includes/session.php';
	include("../includes/config.php");
	include '../includes/db-functions.php';
	include '../includes/call-api.php';

	if(!isset($_SESSION['admin_id'])) {
		header("Location: log-out?Logout_success");
	}

	if(isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['user_phone']) && !empty($_GET['user_phone']) && isset($_GET['user_name']) && !empty($_GET['user_name'])){

		$user_id = base64_decode($_GET['user_id']);
		$user_phone = base64_decode($_GET['user_phone']);
		$user_fullname = base64_decode($_GET['user_name']);
		$sess_id = $_SESSION['admin_id'];


		////////reset the user password to 123456
		$reset_password = md5(123456);
		//echo $user_id; 
		
		////now update the password via the user id with the reset password
		$update_password = mysqli_query($con,"UPDATE user SET password = '$reset_password' WHERE user_id = '$user_id'"); 
		if ($update_password) {
			////log who reset the password
			$updateit = mysqli_query($con,"INSERT into admin_password_reset_log SET admin_id = '$sess_id', user_id = '$user_id', password_reset_created_at = NOW()");
			///now send the user a password reset sms
			////send sms starts here
			$sms_message = "Dear ".ucwords($user_fullname).", your Destiny Promoters account password has been reset successfully to 123456. Please contact the Destiny Promoters Administrator if you still find it difficult to access your account dashboard after the successful password reset.";
			@noblemerry_send_sms($user_phone,$sms_message);        
			////send sms ends here
			echo "<script>alert('Password Reset Successfully');
			window.location='manage-active-members';
			</script>";
		}
		else{
			echo "<script>alert('An error occurred while trying to reset the user Password. \n Please try again later or contact the website developer if these error persist');
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