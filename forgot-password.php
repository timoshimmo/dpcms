<?php 
include("includes/session.php");
include("includes/config.php"); 
include("includes/db-functions.php"); 
include("includes/call-api.php");
?>

<?php
	

	if (isset($_POST['reset'])) {

		$output = "";
		$sms_msg = "";
		$sms_sender_count_msg = "";
		$sms_otp = rand(11111111,99999999);
		$send_sms_otp = substr($sms_otp, 0,6);

		$mobile_number = mysqli_real_escape_string($con,$_POST['mobile_number']);
		
		$query_number_exists = query_phone_number_exists($mobile_number); 

		if ($query_number_exists > 0) { 
			$user_id =  query_phone_number_exists($mobile_number, "user_id");
			$user_fullname =  query_phone_number_exists($mobile_number, "fullname");
			$sms_sender_count =  query_phone_number_exists($mobile_number, "sms_sender_count");

			////everyone is allowed to reset password thrice in a day. 
			///please check if the user sms sender count is not more than 3.
			if ($sms_sender_count>=3) {
				$sms_sender_count_msg = "quota_reached"; 
				$output  = "<p class='text-center text-danger' style='margin:20px 0;'>Oops!!, You've reached the <b>3 maximum SMS OTP</b> you can receive in a day. Try again tomorrow.</p>";
			}
			else{

			$masked_phone_number =  str_pad(substr($mobile_number, -4), strlen($mobile_number), '*', STR_PAD_LEFT);

			////update user column wallet_transfer_sms_otp
			$update_user_sms_otp = mysqli_query($con,"UPDATE user SET wallet_transfer_sms_otp = '$send_sms_otp', sms_sender_count = sms_sender_count+1, sms_sender_date = NOW() WHERE user_id = '$user_id'");
			if ($update_user_sms_otp) {
				////url link to go to change to new password.
			$goto_url = "confirm-reset-number-password?token=".base64_encode($mobile_number);
				////send sms starts here
			$sms_message = "Dear ".ucwords($user_fullname).", you requested for a password reset, your One Time Password is ".$send_sms_otp.". PLEASE DO NOT DISCLOSE. For enquiries; call +2348184212487 or +2349031504010";
			if(noblemerry_send_sms($mobile_number,$sms_message)){
				$output = "<p class='text-center text-success' style='margin:20px 0;'><b>Success!!</b> A 6 digit OTP code was sent to the registered phone number ('.$masked_phone_number.'). Please enter the OTP to proceed.</p>"; 
				header("refresh:3,url=".$goto_url);
			}
			else{
				$output  = "<p class='text-center text-danger' style='margin:20px 0;'>You can not receive OTP SMS at the moment to reset your password, please try again later.</p>";
			} 
			////send sms ends here
			}
		}
			
		}else{
			$output  = "<p class='text-center text-danger' style='margin:20px 0;'>Invalid Phone Number.</p>";
		}
	}



?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>Forgot Password With Phone Number | Destiny Promoters Cooperative</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/media.css">
	<link rel="stylesheet" type="text/css" href="assets/icons/remixicon/remixicon.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="icon" href="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" type="image/png">
	<link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="mainbg">

	<div class="form-container">
		
	<a href="home"><img src="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" class="mx-auto" style="max-width:100px;"></a>

				
		<div class="form-desc">
			<h1 class="top-title">Forgot Password Page</h1>
			<p class="text-blur-md">Enter your phone number to reset your password</p>
		</div>

		<?php

			if (isset($_POST['reset'])) {
					echo $output;
			}

		?>
		

		<div class="container-body">
			<form class="form" action="" method="POST">
				
				<div class="form-group">
					<input type="number" class="form-control" placeholder="Enter your Mobile Number" name="mobile_number"  maxlength="11" oninput="javascript:if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength)">
				</div>

				<div class="form-group form-group-btn">
					<button name="reset" class="form-btn">Reset Password</button>
				</div>

				<div class="form-group text-center">
					<a class="text-blur-md" href="login">Back to login</a>
				</div>
			

			</form>
		</div>
	</div>

</body>
</html>