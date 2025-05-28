<?php
	include '../includes/session.php';
	include("../includes/config.php");
	include '../includes/db-functions.php';
	include '../includes/call-api.php';
	if(!isset($_SESSION['admin_id'])) {
		header("Location: log-out?Logout_success");
	}
	if (isset($_GET['thrift_txn_id']) && !empty($_GET['thrift_txn_id']) && isset($_GET['usertoken']) && !empty($_GET['usertoken'])) {
	$admin_id = $_SESSION['admin_id'];
	$thrift_txn_id = base64_decode($_GET['thrift_txn_id']);
	$user_id = base64_decode($_GET['usertoken']);


	$update = mysqli_query($con, "UPDATE thrift_transaction_details SET 
			incentive_admin_delivery_status = 'delivered'
			WHERE user_id = '$user_id' AND incentive_pickup_type = 'Waybill'") or die(mysqli_error($con));
	
	if ($update) { 
		echo "<script>
		alert('Waybill Incentives Delivered Successfully');
		window.location='manage-waybill-only-settlement-account';
		</script>";
		
	} 
	else{
		echo "<script>alert('Oops!!, Error occur while trying to process the action, please try again later');
		window.location='manage-waybill-only-settlement-account';
		</script>";
	}
	
}
else{
	header("Location:log-out");
}
?>
