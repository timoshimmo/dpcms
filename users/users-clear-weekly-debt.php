<?php
	include("../includes/session.php"); 
	include("../includes/config.php"); 
	include("../includes/db-functions.php");

	if (isset($_GET['txn_id']) && !empty($_GET['txn_id']) && isset($_GET['weekly_thrift_id']) && !empty($_GET['weekly_thrift_id']) && isset($_GET['package_id']) && !empty($_GET['package_id']) && isset($_GET['user_id']) && !empty($_GET['user_id']) && isset($_GET['thrift_week']) && !empty($_GET['thrift_week']) && isset($_GET['user_account_id']) && !empty($_GET['user_account_id'])) {
		
		
		$user_weekly_thrift_id = base64_decode($_GET['weekly_thrift_id']);
		
		$txn_id = base64_decode($_GET['txn_id']); 

		$package_id = base64_decode($_GET['package_id']);

		$user_id = base64_decode($_GET['user_id']);

		$user_account_id = base64_decode($_GET['user_account_id']);

		$thrift_current_week = base64_decode($_GET['thrift_week']);

		$query_user_primary_account  = get_single_user_only_primary_account($user_id);

		$fetch_primary_details = mysqli_fetch_assoc($query_user_primary_account);

		$current_primary_account_balance = $fetch_primary_details['user_account_wallet_amount'];
		
		$primary_user_account_id = $fetch_primary_details['user_account_id'];

		// echo $current_primary_account_balance . " ". $primary_user_account_id   .  "<br>"; 

		$query_thrift_fine =  query_thrift_status($txn_id, 'thrift_fine');

		$query_thrift_package =  query_single_thrift_package($package_id);

		$query_thrift_package_price = mysqli_fetch_array($query_thrift_package)['thrift_package_price'];

		$new_fine = $query_thrift_fine - $query_thrift_package_price;

		//$new_primary_account_balance = $current_primary_account_balance - $query_thrift_package_price;
		/////since it's a user side that wanna clear the debt, it wont be 1,500 again but 3,000.
		$new_primary_account_balance = $current_primary_account_balance - 3000;


		////since it's not admin end, let's set the admin id to 9000 since we can't really byepass

		
		//$admin_id = $_SESSION['admin_id'];
		$admin_id = "0";

/*		
		echo $thrift_current_week;
		echo "<script>
						alert('leggo');
						window.history.back()
					</script>";*/


		///firstly check if users already paid for the current week contribution before or not using txn id and user account id.

		$query_avoid_duplicate = mysqli_query($con,"SELECT * FROM clear_debt_log WHERE user_id = '$user_id' AND user_account_id = '$user_account_id' AND txn_id = '$txn_id' AND thrift_current_week = '$thrift_current_week' AND user_weekly_thrift_id = '$user_weekly_thrift_id'"); 

		if (mysqli_num_rows($query_avoid_duplicate)>0) {
			echo "<script>
						alert('The contribution week defaults already cleared and ₦3,000 removed from your wallet balance. Please reload the page to avoid charging you twice for defaults.'); 
						window.history.back()
				</script>";
		}
		else{

		if ($new_primary_account_balance >= 0){ 
	
			$update_primary_account_balance = mysqli_query($con, "UPDATE user_account SET user_account_wallet_amount = $new_primary_account_balance WHERE user_account_id = '$primary_user_account_id' ");

			if($update_primary_account_balance){

				clear_debt_log($user_id, $user_account_id, $txn_id, $admin_id, $thrift_current_week, $user_weekly_thrift_id);
				admin_clear_debt($new_fine, $txn_id, $user_weekly_thrift_id);
				
				echo "<script>
						alert('Debt cleared successfully & ₦3,000 charged from the primary wallet. Wait while the system redirect you'); 
				</script>";
				$redirect_url = "weekly-thrift-history?thrift_txn_id=".base64_encode($txn_id)."&user_account_id=".base64_encode($user_account_id)."";
				header("refresh:1,url=".$redirect_url);
				

			}else{

				echo "<script>
						alert('An error occurred, the system can not clear your debt at the moment. Try again later.');
						window.history.back() 
					</script>";
			}

		}else{

			echo "<script>
				alert('Insufficient funds in your primary account wallet. Please fund your primary account wallet and try again.');
				window.history.back() 
			</script>";
		}

		}	
	}

?>