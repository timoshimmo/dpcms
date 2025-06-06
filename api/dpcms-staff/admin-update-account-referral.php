<?php
	include '../includes/session.php';
	include("../includes/config.php");
	include '../includes/db-functions.php';
	include '../includes/call-api.php';
	if(!isset($_SESSION['admin_id'])) {
		header("Location: log-out?Logout_success");
	}
	if (isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['user_account_id']) && !empty($_GET['user_account_id'])) {
	$admin_id = $_SESSION['admin_id'];
	$user_id = base64_decode($_GET['user_id']);
	$user_account_id = base64_decode($_GET['user_account_id']);
	//echo $admin_id;
	

	///query ref id first.
	$query_the_acct = query_single_user_account_with_id($user_account_id,md5($user_id));
	$fetch_theid = mysqli_fetch_array($query_the_acct);
	extract($fetch_theid);
	echo $user_account_token;
	$get_ref_count = ($referral_count<=0 ? 0+1 : $referral_count);
	$get_ref_with_a_p = ($total_referral_with_active_plan<=0 ? 0+1 : $total_referral_with_active_plan);

	echo $get_ref_with_a_p;
	$url = 'view-members-accounts?user_id='.base64_encode($user_id).'&user_account_id='.base64_encode($user_account_id).'';
	//exit();
	///update referral with acct id
	$update_ref = mysqli_query($con,"UPDATE user_account SET referral_count = '$get_ref_count', total_referral_with_active_plan = '$get_ref_with_a_p' WHERE user_id = '$user_id' AND user_account_id = '$user_account_id'");
	if ($update_ref) {
		////now that referral updated, logo the admin that initiated the actions. 
		$insert_ref_update = mysqli_query($con,"INSERT into admin_referral_update_log SET
			user_id = '$user_id',
			admin_id = '$admin_id',
			user_account_id = '$user_account_id',
			old_referral_count = '$referral_count',
			old_referral_with_active_plan = '$total_referral_with_active_plan',
			referral_update_created_at = NOW()
		") or die(mysqli_error($con));
		echo "<script>
		alert('Referral Updated Successfully');
		window.location='".$url."';
		</script>";	
	} 
	else{
		echo "<script>alert('Oops!!, Error occur while trying to update the referral, please try again later');
		window.location='".$url."';
		</script>";
	}
	
}
?>
