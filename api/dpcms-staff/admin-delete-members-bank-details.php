<?php
	include '../includes/session.php';
	include("../includes/config.php");
	include '../includes/db-functions.php';
	include '../includes/call-api.php';
	if(!isset($_SESSION['admin_id'])) {
		header("Location: log-out?Logout_success");
	}
	if (isset($_GET['user_id'])) {
	$admin_id = $_SESSION['admin_id'];
	$user_id = base64_decode($_GET['user_id']);
	///delete fromDB
	$delete_account = mysqli_query($con,"DELETE FROM account_details WHERE user_id = '$user_id'");
	if ($delete_account) {
		/////now that account details deleted, log it somewhere to track those deleting account details. 
		$insert_deletion = mysqli_query($con,"INSERT into admin_delete_bank_details SET
			user_id = '$user_id',
			deleted_by_admin_id = '$admin_id',
			date_deleted = NOW()
		") or die(mysqli_error($con));
		echo "<script>
		alert('Bank account details deleted Successfully');
		window.location='overview';
		</script>";
		
	} 
	else{
		echo "<script>alert('Oops!!, Error occur while trying to delete Account details, please try again later');
		window.location='overview';
		</script>";
	}
	
}
?>
