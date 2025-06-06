<?php
include '../includes/session.php';
include '../includes/config.php';
include '../includes/db-functions.php';


 if (isset($_POST['btnadmin_login'])) {
  $ADMINemail = mysqli_real_escape_string($con,stripslashes($_POST['ADMINemail']));
  $ADMINpassword = mysqli_real_escape_string($con,md5($_POST['ADMINpassword']));

  $querylogin = mysqli_query($con,"SELECT * FROM admin WHERE email = '$ADMINemail' AND password = '$ADMINpassword' ");
  if (mysqli_num_rows($querylogin) > 0) {
    $fetchd = mysqli_fetch_assoc($querylogin);
    extract($fetchd);
    $_SESSION['role'] = $role;
    $_SESSION['admin_id'] = $admin_id;
    $_SESSION['time'] = time();
    $output = "<span style='color:green'>Login Successfully, you'll be redirected soon</span>";

    /////////admin login history HERE
    $ip_addr = noble_get_client_ip();
    $inser_history = mysqli_query($con,"INSERT into admin_login SET 
    	admin_id = '$admin_id',
    	ip_address = '$ip_addr',
    	login_date = NOW()
    ");
    header("refresh:2, url=overview?welcome_back_".$role);
  }
  else{
    $output = "<span style='color:red'>Oops!!,  Invalid login details provided</span>";
  }

 }
?>
<!DOCTYPE html>
<html lang="en">

<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard ">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="">

	<link rel="preconnect" href="https://fonts.gstatic.com/">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<!-- <link rel="canonical" href="pages-sign-in.html" /> -->

	<title>Sign In | Destiny Promoters Administrator Dashboard</title>

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&amp;display=swap" rel="stylesheet">

	<!-- Choose your prefered color scheme -->
	<!-- <link href="css/light.css" rel="stylesheet"> -->
	<!-- <link href="css/dark.css" rel="stylesheet"> -->

	<!-- BEGIN SETTINGS -->
	<!-- Remove this after purchasing -->
	<link class="js-stylesheet" href="css/light.css" rel="stylesheet">
	<!-- <script src="js/settings.js"></script> -->
	<style>
		body {
			opacity: 0;
		}
	</style>
	<!-- END SETTINGS -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-120946860-10"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-120946860-10', { 'anonymize_ip': true });
</script></head>
<!--
  HOW TO USE: 
  data-theme: default (default), dark, light, colored
  data-layout: fluid (default), boxed
  data-sidebar-position: left (default), right
  data-sidebar-layout: default (default), compact
-->

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<main class="d-flex w-100 h-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mt-4">
							<h1 class="h2">Welcome back Admin!</h1>
							<p class="lead">
								Sign in to your account to continue
							</p>
						</div>

						<div class="card">
							<div class="card-body">
								<div class="m-sm-3">
									<div class="d-grid gap-2 mb-3">
										<center><img src="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" style="max-width: 100px;"></center>
										<center><?php if (isset($_POST['btnadmin_login'])) {
    echo $output;
    } ?></center>
									</div>

									<form method="POST" action="">
										<div class="mb-3">
											<label class="form-label">Email</label>
											<input class="form-control form-control-lg" type="email" name="ADMINemail" placeholder="Enter your email" />
										</div>
										<div class="mb-3">
											<label class="form-label">Password</label>
											<input class="form-control form-control-lg" type="password" name="ADMINpassword" placeholder="Enter your password" />
											
										</div>
										<div>
											<div class="form-check align-items-center">
												<input id="customControlInline" type="checkbox" class="form-check-input" value="remember-me" name="remember-me"
													checked>
												<label class="form-check-label text-small" for="customControlInline">Remember me</label>
											</div>
										</div>
										<div class="d-grid gap-2 mt-3">
											<button class="btn btn-lg btn-primary" name="btnadmin_login">Sign in</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					
					</div>
				</div>
			</div>
		</div>
	</main>

	<!-- <script src="js/app.js"></script> -->

</body>

</html>