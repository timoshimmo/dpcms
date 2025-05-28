<?php
	include '../includes/session.php';
	include("../includes/config.php");
	include '../includes/db-functions.php';
	include '../includes/call-api.php';
	if(!isset($_SESSION['admin_id'])) {
		header("Location: log-out?Logout_success");
	}
	if (isset($_GET['payment_proof_id']) && !empty($_GET['payment_proof_id']) && isset($_GET['user']) && !empty($_GET['user'])) {
	$admin_id = $_SESSION['admin_id'];
	$payment_proof_id = base64_decode($_GET['payment_proof_id']);
	$user_id = base64_decode($_GET['user']);


	/////delete the payment proof
	$delete = mysqli_query($con, "DELETE FROM payment_proof WHERE payment_proof_id = '$payment_proof_id' ");
	
	if ($delete) {
		/////now that payment proof deleted, log it somewhere to track those payment proof details. 
		$insert_deletion = mysqli_query($con,"INSERT into admin_delete_payment_proof SET
			user_id = '$user_id',
			deleted_by_admin_id = '$admin_id',
			date_deleted = NOW()
		") or die(mysqli_error($con));
		echo "<script>
		alert('Payment Proof Deleted Successfully');
		window.location='payment-proof-reg';
		</script>";
		
	} 
	else{
		echo "<script>alert('Oops!!, Error occur while trying to delete the payment proof, please try again later');
		window.location='payment-proof-reg';
		</script>";
	}
	
}
?>
