<?php
include '../includes/session.php';
include '../includes/config.php';
include '../includes/db-functions.php';
include_once '../includes/call-api.php';
if (!isset($_SESSION['admin_id'])) {
	header("Location: log-out?Logout_success");
}
else{
/////get admin details
	$sess_id = $_SESSION['admin_id'];
	$get_d = mysqli_query($con,"SELECT * FROM admin WHERE admin_id = '$sess_id'");
	$fetch_de = mysqli_fetch_array($get_d);
	$admin_role = $fetch_de['role'];
	$admin_id = $fetch_de['admin_id']; 
	$admin_email = $fetch_de['email'];
	$admin_username = $fetch_de['username'];

}

?>
<!DOCTYPE html>
<html lang="en">

<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Destiny Promoters ">
	<meta name="author" content="Destiny Promoters ">
	<meta name="keywords" content="Destiny Promoters , Empowerment Program, Incentives Self & Waybill">

	<link rel="preconnect" href="https://fonts.gstatic.com/">
	<link rel="shortcut icon" href="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" />

	<link rel="canonical" href="overview" />

	<title>DPCMS-PANEL Dashboard | Destiny Promoters </title>

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&amp;display=swap" rel="stylesheet">

	<!-- Choose your prefered color scheme -->
	<!-- <link href="css/light.css" rel="stylesheet"> -->
	<!-- <link href="css/dark.css" rel="stylesheet"> -->

	<!-- BEGIN SETTINGS -->
	<!-- Remove this after purchasing -->
	<link class="js-stylesheet" href="css/light.css" rel="stylesheet">
	<style type="text/css">
	.pcoded-mtext{ color: white!important; font-size: 0.95rem;}
	.pcoded-micon i{font-weight: 800; color: white!important;}
	
		/*swal starts here*/
.swal-modal{ max-width: 350px; }
.swal-title{color: #3aaf68; font-size: 1rem; padding-top: 0;}
.swal-text{font-size: 0.8rem;}
.swal-button--confirm{padding: 7px 25px; font-weight: 700; font-size: 0.8rem; margin: 10px 0 0;border-radius: 20px;cursor: pointer; color: #3aaf68; border: 1px solid #3aaf68; user-select: none; background: transparent; outline: unset!important;}
.swal-footer{ display: flex; justify-content: center; margin-top: 0px;padding: 10px 16px 20px; }
.swal-icon--success{background: #dffae8;}
.swal-icon--error{background: #ffe9e9;}
.swal-icon--warning{background: #f9af99 ;}
.swal-icon--warning__body, .swal-icon--warning__dot{background:#f1592a ;}
.swal-icon--error + .swal-title {color: #be2541;}
.swal-icon--warning + .swal-title {color: #f1592a;}
.swal-icon{  border: none; margin: 5px auto;}
.swal-icon::before,.swal-icon::after, .swal-icon--success__ring{all: unset;}
.swal-icon--success__hide-corners{display: none;}
.swal-icon--error ~ .swal-footer .swal-button--confirm{ background:#be2541 ; border: none; color: white; }
.swal-icon--warning ~ .swal-footer .swal-button--confirm{ background:#f1592a ; border: none; color: white; }
/*swal ends here*/
</style>
<!-- 	<script src="js/settings.js"></script> -->
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
</script>
</head>
<!--
  HOW TO USE: 
  data-theme: default (default), dark, light, colored
  data-layout: fluid (default), boxed
  data-sidebar-position: left (default), right
  data-sidebar-layout: default (default), compact
-->