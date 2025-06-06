<?php 
include("includes/session.php");
//include("includes/config.php"); 
//include("includes/db-functions.php"); 
/*

if (isset($_GET['token']) && !empty($_GET['token'])) {
	$mobile_number = base64_decode($_GET['token']);
	$masked_phone_number =  str_pad(substr($mobile_number, -4), strlen($mobile_number), '*', STR_PAD_LEFT);
}
else{
	echo "error";
	exit();
}*/
?>

<?php
	/*

	if (isset($_POST['reset'])) {

		$output = "";
		$new_password = mysqli_real_escape_string($con,$_POST['new_password']);
		$confirm_new_password = mysqli_real_escape_string($con,$_POST['confirm_new_password']);
		$password_reset_otp = mysqli_real_escape_string($con,$_POST['password_reset_otp']);

		//////recheck again if phone number still exist for db.
		$query_number_exists = query_phone_number_exists($mobile_number); 
		if ($query_number_exists<=0) {
			$output  = "<p class='text-center text-danger' style='margin:20px 0;'>The phone number does not exist.</p>";
			}
		else{
			$user_id =  query_phone_number_exists($mobile_number, "user_id");
			$user_fullname =  query_phone_number_exists($mobile_number, "fullname");

			//first check if the otp is valid on the person's database.
			$query_otp = mysqli_query($con,"SELECT * FROM user WHERE wallet_transfer_sms_otp = '$password_reset_otp' AND user_id = '$user_id'");
			if (mysqli_num_rows($query_otp)<=0) { 
				////this shows it doesnt exist or valid. then throw the error.
			$output  = "<p class='text-center text-danger' style='margin:20px 0;'>The OTP to reset the password is Invalid. click <a href='forgot-password' style='color:blue;'>here</a> to reset your password again.</p>";		
			}
			else{
				///since otp is correct, then proceed to reset the password.

				///continue to check if new and confirm password tally
			if ($new_password!=$confirm_new_password) {
			$output  = "<p class='text-center text-danger' style='margin:20px 0;'>The password entered does not match</p>";		
			}
			else{
				////NOW UPDATE THE PASSWORD VIA THE USER ID. 
				$valid_password = md5($new_password);
				$update_password = mysqli_query($con,"UPDATE user SET password = '$valid_password' WHERE user_id = '$user_id'");
				if ($update_password) {
					$output  = "<p class='text-center text-success' style='margin:20px 0;'>The password changed successfully. you will be redirected to the login page now.</p>";
					header("refresh:5,url=login");	
				}
				else{
					$output  = "<p class='text-center text-danger' style='margin:20px 0;'>An error occurred while trying to reset your password, please contact the customer care line. </p>";	
				}
				
				
			}
			}

			

		}


	}

*/

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>Change Phone Number Password | Destiny Promoters Cooperative</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<!-- <link rel="stylesheet" type="text/css" href="assets/css/media.css"> -->
	<!-- <link rel="stylesheet" type="text/css" href="assets/icons/remixicon/remixicon.css"> -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="icon" href="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" type="image/png">
	<link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="mainbg">

	<div class="form-container">
						
		<!-- <div class="form-desc">
			<h1 class="top-title">Change Password</h1>
			<p class="text-blur-md">Enter the OTP sent to the phone number ('.<?php echo $masked_phone_number; ?>.')</p>
		</div>

		
		

		<div class="container-body">
			<form class="form" action="" method="POST">
				
				<div class="form-group">
					<input type="number" class="form-control" placeholder="Enter OTP Received" name="password_reset_otp" maxlength="6" oninput="javascript:if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength)">
				</div>

				<div class="form-group">
					<input type="text" class="form-control" placeholder="Enter New Password" name="new_password">
				</div>

				<div class="form-group">
					<input type="text" class="form-control" placeholder="Confirm New Password" name="confirm_new_password">
				</div>

				<div class="form-group form-group-btn">
					<button name="reset" class="form-btn">Change Password</button>
				</div>

				<div class="form-group text-center">
					<a class="text-blur-md" href="login">Back to login</a>
				</div>
			

			</form>
		</div>-->
	</div>

</body>
</html>