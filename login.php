<?php 
include("includes/session.php"); 
include("includes/config.php"); 
include("includes/db-functions.php"); 
?>
<?php
	
	if(isset($_POST['login'])){

		$output = "";

		$email = $_POST['email'];

		$password = md5($_POST['password']); 

		$query_user_exists = query_login($email,$password);

		$get_user_data = mysqli_fetch_array(query_login($email,$password, true));
		//print_r($get_user_data['password']); 

		$security_password = @$get_user_data['password'];

/*		echo "the is $security_password".'<br/>';

		if ($security_password==$password) {
			echo "password correct";
		}
		else{
			echo "password not correct";
		}

		print($password);*/




		if($query_user_exists > 0){

			if ($security_password==$password) {
			$user_account_id = $get_user_data["user_account_id"]; 
			
			$_SESSION['session_logged_in_user_account_id'] = $user_account_id; 
			$_SESSION['session_logged_in_user_id'] = $get_user_data["user_id"];
			$user_id = $get_user_data["user_id"];

			if (isset($_SESSION['session_logged_in_user_id'])) {
				$output = "<p class='text-center text-success' style='margin:20px 0;'>Login Successful. Kindly wait while you are being redirected to your dashbord</p>";
				$query_user_payment = mysqli_query($con, "SELECT * FROM user where user_reg_fee='paid' AND user_id='$user_id'");

				$query_payment_count = mysqli_num_rows($query_user_payment); 

				$query_login_proof = user_get_login_payment_proof($user_account_id);


				$fetch_user_payment_proof =  mysqli_fetch_array($query_login_proof);

				$count_proof_payment = mysqli_num_rows($query_login_proof);
				
		

				if ($query_payment_count > 0) {
					header("refresh:1, url=users/dashboard");	
				}else if($count_proof_payment > 0){
						$output = "<p class='text-md text-colored'>This account has been flagged. <a href='#' style='padding:8px' class='btn btn-sm' data-toggle='modal' data-target='#exampleModal'>View Reason</a></p>";
				}else{

					$output = "<p class='text-center text-success' style='margin:20px 0;'>Login Successful</p>";
					header("refresh:1, url=fund-wallet-before-login.php");
				
				}
				
			}else{
				
				$output = "<p class='text-center text-danger' style='margin:20px 0;'>Something went wrong. Try again later.</p>";
			}

		}
		else{
			$output = "<p class='text-center text-danger' style='margin:20px 0;'>Incorrect data passed</p>";
		}
		}//end here
		else{

			$output = "<p class='text-center text-danger' style='margin:20px 0;'>Invalid Login Credentials!</p>";
		}

	}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>Members Login Page | Destiny Promoters Cooperative</title>
	<link rel="stylesheet" type="text/css" href="users/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/media.css">
	<link rel="stylesheet" type="text/css" href="assets/icons/remixicon/remixicon.css">
	<link rel="icon" href="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" type="image/png">
	<link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
	<script src="users/assets/js/jquery-3.5.1.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/script.js" defer></script>
	<style>
		a{ text-decoration: none; }
		.top-title{ font-weight: 700; }
		.form-control{ outline: none!important; box-shadow: none!important; }
		.form-control:focus{ border-color: unset; }
		.form-link-colored:hover{ color: var(--main); }
		a.text-blur-md:hover{color: var(--blur);}
	</style>
</head>
<body class="mainbg body-none">

<!-- <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#exampleModal">Click
</button> -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reason For Account Being Flagged</h5>
        
      </div>
      <div class="modal-body">
        	<h3><?php echo @$fetch_user_payment_proof['proof_cancel_reason']?></h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" style="padding:10px 20px" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

	<div class="form-container">
			
		<a href="home"><img src="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" class="mx-auto" style="max-width:100px;"></a>

		<div class="form-desc">
			<h1 class="top-title">Member's Login Page</h1>
			
		</div>

		<?php

		if (isset($_POST['login'])) {

			echo $output;	
		}
		?>


		<div class="container-body" style="margin-top:30px">
			<form class="form" action="" method="POST">
				<div class="form-group">
					<input type="text" required class="form-control" value="<?php echo @$email ?>" placeholder="Enter your Phone Number" name="email">
				</div>

				<div class="form-group reduce-spacing">
					<input type="password" required class="form-control" placeholder="Enter your Password" name="password">
					<i class="ri-eye-fill toggle-password"></i>
				</div>

				<div class="d-flex flex-end form-link">
					<a class="text-blur-md" href="forgot-password">Forget Password?</a>	
				</div>
				
				<div class="form-group form-group-btn">
					<button name="login" class="form-btn">Click to Login</button>
				</div>

				<div class="form-group m-none text-center">
					<p class="text-blur-md">Don't have an account? <a class="form-link-colored" href="signup">Register</a></p>
				</div>
			

			</form>
		</div>
	</div>

</body>
</html>