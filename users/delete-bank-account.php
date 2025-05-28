<?php
include("../includes/session.php"); 
include("../includes/config.php"); 
	
	if (isset($_GET['user_id'])) {


		$id = base64_decode($_GET['user_id']);

		$query = mysqli_query($con, "DELETE FROM account_details WHERE id = '$id' ");

		if ($query) {
			echo "<script>
				alert('Bank Details Deleted successfully');
				window.location=manage-bank-account;
			</script>";

		}else{
			echo "<script>
				alert('Failed');
				window.location=manage-bank-account;
			</script>";
		}
	}

?>