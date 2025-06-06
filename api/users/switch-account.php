<?php
	session_start();
	if(isset($_GET['acct_id']) && !empty($_GET['acct_id']) && isset($_GET['user_id']) && !empty($_GET['user_id'])){
		//session_destroy();

		$acct_id = base64_decode($_GET['acct_id']);
		$user_id = base64_decode($_GET['user_id']);

		$_SESSION['session_logged_in_user_account_id'] = $acct_id; 
		$_SESSION['session_logged_in_user_id'] = $user_id;

		//echo $_SESSION['session_logged_in_user_account_id'];

		header("Location:dashboard");
		
	}

?>