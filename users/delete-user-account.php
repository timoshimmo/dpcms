<?php
include("../includes/session.php"); 
include("../includes/config.php"); 
	
	if (isset($_GET['account_id'])) {

		$id = base64_decode($_GET['account_id']);

		$query = mysqli_query($con, "DELETE FROM user_account WHERE user_account_id = '$id' ");

		if ($query) {
			echo "<script>
				alert('User Account Deleted successfully');
				window.location = 'profile'
			</script>";
			

		}else{
			echo "<script>
				alert('Failed')
			</script>";
		}
	}

?>