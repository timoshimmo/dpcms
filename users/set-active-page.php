<?php
@$page_active_color = "navigation-list-active";
@$home = $profile = $payment = $investment = $wal_transaction = $thrift_transaction = $investment = $fund_wallet =$users_payment_proof = "";

	$page = basename($_SERVER['PHP_SELF']);

	if ($page=='index.php') {
		$home = $page_active_color; 
	} 
	else if( $page == "profile.php"){
		$profile = $page_active_color; 
	}
	else if( $page == "manage-bank-account.php" || $page == "add-bank-account.php" ){
		$payment = $page_active_color; 
	}
	else if( $page == "investment.php" || $page == "investment-details.php" || $page == "investment-plan-details.php"){
		$investment = $page_active_color; 
	}
	else if( $page == "wallet-transaction.php"){
		$wal_transaction = $page_active_color; 
	}
	else if( $page == "thrift-transaction.php"){
		$thrift_transaction = $page_active_color; 
	}
	else if( $page == "fund-wallet.php" || $page == "confirm-fund-wallet.php"){

		 $fund_wallet = $page_active_color; 
	}else if ($page == "users-payment-proof.php") {
		$users_payment_proof = $page_active_color;
	}
	else{
		//do nothing
	}


?>