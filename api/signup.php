<?php 
include("includes/session.php");
include("includes/config.php"); 
include("includes/db-functions.php"); 
include("includes/call-api.php"); 

?>

<?php



if (isset($_POST['create'])) {


	$output = "";
	$fetch_last_account_id = mysqli_fetch_array(query_last_account_id());
	$last_account_id = $fetch_last_account_id['user_account_id'];

	$generate_account_token = $token_name.random_numbers(11); 

	$generate_user_refferal_id = 'RF'.random_strings(14);
	$fullname = mysqli_real_escape_string($con, addslashes($_POST['fullname']));
	$email = mysqli_real_escape_string($con, addslashes($_POST['email']));
	$gender = mysqli_real_escape_string($con, addslashes($_POST['gender']));
	$number = mysqli_real_escape_string($con, addslashes($_POST['number']));
	$referral_id = mysqli_real_escape_string($con, addslashes($_POST['referral_id']));
	$password = mysqli_real_escape_string($con, addslashes($_POST['password']));
	$confirm_password = mysqli_real_escape_string($con, addslashes($_POST['confirm_password']));
	$terms_condition = $_POST['terms_condition'];

	///since we are now using phone number, let me login the phone 
	//$login_username = (empty($email) ? $number : $number);
	

	if ($password != $confirm_password) {
		
		$output = "<p class='text-center text-danger' style='margin:20px 0;'>Password do not match!</p>";

	}elseif(query_phone_number_exists($number) > 0 ){

		$output = "<p class='text-center text-danger' style='margin:20px 0;'>Phone Number already exist! Please use another phone number</p>";

	} elseif(query_referral_exists($referral_id) > 0 ){
			
		if(query_user_reg_within_forty_days($referral_id)){
			update_users_referral_count_within_forty_days($referral_id);
		}

		$query_user_insert = create_user_first_time_registration($fullname, $email, $gender, $number, md5($password) );

		$lastt_insert_id = mysqli_insert_id($con); 

		// Update Users referral total count
		$total_referral_count = query_counts_increment_user_referral($referral_id);

		if($query_user_insert){

			$processAccount = main_account_creation($lastt_insert_id, $generate_account_token, $generate_user_refferal_id, $referral_id);          

			//include("includes/send-email/send-email-after-user-registration.php");
			////send sms starts here
			$sms_message = "Welcome to Destiny Promoters Cooperative platform, you are getting this message because you just had a successful registration. Please proceed to pay your registration fee of ₦3,000 for your account to be activated.";
			@noblemerry_send_sms($number,$sms_message);
			////send sms ends here

			$output = "<p class='text-center text-success' style='margin:20px 0;'>Registration Successful. Kindly wait while you are being redirected to payment page.</p>";        

			$get_user_data = mysqli_fetch_array(query_login($number,md5($password), true));

			$_SESSION['session_logged_in_user_account_id'] = $get_user_data["user_account_id"]; 

			$_SESSION['session_logged_in_user_id'] = $get_user_data["user_id"];
			
			header("Location:fund-wallet-before-login"); 

		}
		

	}else{
		
		$query_user_insert = create_user_first_time_registration($fullname, $email, $gender, $number, md5($password) ); 
		if($query_user_insert){
				
			$last_insert_id = mysqli_insert_id($con);
			$processAccount = main_account_creation($last_insert_id, $generate_account_token, $generate_user_refferal_id, "NO_REFERRAL");

			//include("includes/send-email/send-email-after-user-registration.php");

			////send sms starts here
			$sms_message = "Welcome to Destiny Promoters Cooperative, you are getting this message because you just had a successful registration. Please proceed to pay your registration fee of ₦3,000 for your account to be activated.";
			@noblemerry_send_sms($number,$sms_message); 
			////send sms ends here

			$output = "<p class='text-center text-success' style='margin:20px 0;'>Registration Successful. Kindly wait while you are being redirected to payment page.</p>";

			$get_user_data = mysqli_fetch_array( query_login($number,md5($password), true));

			$_SESSION['session_logged_in_user_account_id'] = $get_user_data["user_account_id"]; 

			$_SESSION['session_logged_in_user_id'] = $get_user_data["user_id"];

			header("Location:fund-wallet-before-login");
			
		}
	}

}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>Register your Account | Destiny Promoters Cooperative</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/media.css">
	<link rel="stylesheet" type="text/css" href="assets/icons/remixicon/remixicon.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="icon" href="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" type="image/png">
	<link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
	<script src="assets/js/script.js" defer></script>
</head>
<body class="mainbg body-none"> 

	<div class="form-container">
		
		<a href="home"><img src="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" class="mx-auto" style="max-width:100px;"></a>

		<div class="form-desc">
			<h1 class="top-title">Register With Us</h1>
			<p class="text-blur-md">Join Destiny Promoters Cooperative Today</p>
		</div>

		<?php

		if (isset($_POST['create'])) {

			echo $output;	
		}
		?>
		<div class="container-body" style="margin-top:30px">
			<form class="form" action="" method="POST">
				<div class="form-group">
					<input type="text"  class="form-control" required value="<?php echo @$fullname ?>" placeholder="Full Name" name="fullname">
				</div>

				<div class="form-group" style="display: none;">
					<input type="email"  class="form-control" value="<?php echo @$email ?>" placeholder="Email Address" name="email">
				</div>

				<div class="form-group" name="gender">
					<select name="gender" required  class="form-control">
						<option value="">Select Gender</option>
						<option <?php echo @$gender == "Male" ? "selected" : ""   ?> value="Male">Male</option>
						<option  <?php echo @$gender == "Female" ? "selected" : ""   ?> value="Female">Female</option>
					</select>
				</div>

				<div class="form-group">
					<input type="number"  class="form-control" maxlength="11" oninput="javascript:if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength)" required value="<?php echo @$number ?>" placeholder="Phone Number" name="number">
				</div>

				<div class="form-group">
					<input type="text" class="form-control" value="<?php echo @$referral_id ?>" placeholder="Upline Referral Id (Optional)" name="referral_id">
				</div>

				<div class="form-group">
					<input type="password"  class="form-control" required value="<?php echo @$password ?>" placeholder="Password" name="password">
					<i class="ri-eye-fill toggle-password"></i>
				</div>

				<div class="form-group">
					<input type="password"  class="form-control" required value="<?php echo @$confirm_password ?>" placeholder="Confirm Password" name="confirm_password">
					<i class="ri-eye-fill toggle-password"></i>
				</div>

				<div class="form-group">
						<label>
							<input style="accent-color: var(--main)" type="checkbox" oninvalid="this.setCustomValidity('Kindly agree to our terms and condition before you can proceed')" <?php echo @$terms_condition == true ? "checked" : "" ?> required name="terms_condition" oninput="this.setCustomValidity('')"> I agree to Destiny Promoters <a class="text-colored" target="_blank" href="terms">terms and conditions</a>
						</label>
				</div> 
				
				<div class="form-group form-group-btn">
					<button name="create" class="form-btn">Create Account</button>
				</div>

				<div class="form-group text-center">
					<p class="text-blur-md">Already have an account? <a class="form-link-colored" href="login">Login</a></p>
				</div>
			</form>
		</div>
	</div>

</body>
</html>