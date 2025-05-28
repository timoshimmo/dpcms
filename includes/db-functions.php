<?php
	
function create_user_first_time_registration($fullname, $email_address, $gender, $phone_no, $password){
	global $con;

	$query = mysqli_query($con, "INSERT into user SET
		fullname	 = '$fullname',
		email_address	 = '$email_address',
		gender	 = '$gender',
		phone_no	 = '$phone_no',
		password	 = '$password',
		user_reg_date	 = NOW()
	") or die(mysqli_error($con));

	return $query;
}


function create_user_next_of_kin($user_id, $next_of_kin_fullname, $next_of_kin_number, $next_of_kin_address, $next_of_kin_occupation, $next_of_kin_email, $next_of_kin_dob, $next_of_kin_gender ){
	global $con;

	$query = mysqli_query($con, "INSERT into user_next_of_kin SET
		user_id	 = '$user_id',
		next_of_kin_fullname	 = '$next_of_kin_fullname',
		next_of_kin_number	 = '$next_of_kin_number',
		next_of_kin_address	 = '$next_of_kin_address',
		next_of_kin_occupation	 = '$next_of_kin_occupation',
		next_of_kin_email	 = '$next_of_kin_email',
		next_of_kin_dob = '$next_of_kin_dob',
		next_of_kin_gender = '$next_of_kin_gender',
		date_created_on	 = NOW()
	") or die(mysqli_error($con));

	$update_user_status = mysqli_query($con, "UPDATE user SET
		is_acct_active = 'yes' WHERE user_id = '$user_id'
	");

	return $query;
}


function create_user_account($user_id,$user_account_token, $referral_id, $referred_referral_id, $account_generated_type = 'manual'){
	global $con;

	$query = mysqli_query($con, "INSERT into user_account SET
		user_id	 = '$user_id',
		user_account_token	 = '$user_account_token',
		user_account_type	 = 'others',
		referral_id = '$referral_id',
		referred_referral_id = '$referred_referral_id',
		account_generated_type = '$account_generated_type',
		user_account_created_at	 = NOW()
	") or die(mysqli_error($con));

	return $query;
}


function confirm_user_registration_payment($user_id){
	
	global $con;

	$query = mysqli_query($con, "UPDATE user SET user_reg_fee='paid' WHERE user_id='$user_id' ")or die(mysqli_error($con));

	return $query;
}


function add_user_bank_details($user_id, $bank_sort_code, $account_name, $account_no){
	
	global $con;

	$query = 	mysqli_query($con, "INSERT into account_details SET
		user_id	 = '$user_id',
		bank_sort_code	 = '$bank_sort_code',
		account_name	 = '$account_name',
		account_no	 = '$account_no'
	") or die(mysqli_error($con));

	return $query;
}



function query_user_bank_details($user_id){
	
	global $con;

	$query = 	mysqli_query($con, "SELECT * FROM account_details JOIN lists_banksortcode ON account_details.bank_sort_code = lists_banksortcode.lists_banksortcode  WHERE user_id = '$user_id' ") or die(mysqli_error($con));

	return $query;
}



function update_user_next_of_kin($next_of_kin_id, $next_of_kin_fullname, $next_of_kin_number, $next_of_kin_address, $next_of_kin_occupation, $next_of_kin_email, $next_of_kin_dob, $next_of_kin_gender ){
	global $con;

	$query = mysqli_query($con, "UPDATE user_next_of_kin SET
		next_of_kin_fullname	 = '$next_of_kin_fullname',
		next_of_kin_number	 = '$next_of_kin_number',
		next_of_kin_address	 = '$next_of_kin_address',
		next_of_kin_occupation	 = '$next_of_kin_occupation',
		next_of_kin_email	 = '$next_of_kin_email',
		next_of_kin_dob = '$next_of_kin_dob',
		next_of_kin_gender = '$next_of_kin_gender'
		WHERE next_of_kin_id =  '$next_of_kin_id'
	") or die(mysqli_error($con));

	return $query;
}



function update_user_information($fullname, $email_address,  $phone_no,  $photo,  $address,  $dob, $gender, $user_id){
	global $con;

	$query = mysqli_query($con, "UPDATE user SET
		fullname	 = '$fullname',
		email_address	 = '$email_address',
		phone_no	 = '$phone_no',
		photo	 = '$photo',
		address	 = '$address',
		dob	 = '$dob',
		gender	 = '$gender'
		WHERE user_id = '$user_id'
	") or die(mysqli_error($con));

	return $query;

}



function update_user_password($user_id, $password){
	global $con;

	$query = mysqli_query($con, "UPDATE user SET
		password = '$password'
		WHERE user_id = '$user_id'
	") or die(mysqli_error($con));

	return $query;

}



function query_user_next_of_kin_details($user_id){

	global $con;
 
	$query = mysqli_query($con, "SELECT * FROM user_next_of_kin JOIN user ON user.user_id = user_next_of_kin.user_id WHERE user.user_id='$user_id' ");

	return mysqli_fetch_array($query);

}


function main_account_creation($user_id, $user_account_token, $referral_id, $referred_referral_id ){
	global $con;

	$query = mysqli_query($con, "INSERT into user_account SET
		user_id = '$user_id',
		user_account_token = '$user_account_token',
		referral_id = '$referral_id',
		referred_referral_id = '$referred_referral_id',
		user_account_created_at = NOW()
	") or die(mysqli_error($con));

	$last_insert_id = mysqli_insert_id($con);


	$update_user_id = mysqli_query($con, "UPDATE user SET
		user_account_id = '$last_insert_id' WHERE user_id = '$user_id'
	");

	return $query;
}

function query_email_exists($email, $type=false){
	global $con;

	$query = mysqli_query($con, "SELECT *  FROM user WHERE email_address='$email'") or die(mysqli_error($con));
	$rows = mysqli_num_rows($query);
	$fetch_query = mysqli_fetch_array($query);
	if ($rows > 0 && $type) {
		return $fetch_query[$type];
	}
	return $rows;
}


function query_referral_exists($referral_id){
	global $con;
	$query = mysqli_query($con, "SELECT referral_id FROM user_account WHERE referral_id='$referral_id'") or die(mysqli_error($con));
	$rows = mysqli_num_rows($query);
	return $rows;
}


function query_user_reg_within_forty_days($referral_id){
	
	global $con;
	
	$query = mysqli_query($con, "SELECT * FROM user_account WHERE referral_id='$referral_id'") or die(mysqli_error($con));

	$fetch_user_query = mysqli_fetch_array($query);

	$registered_date = explode(" ", $fetch_user_query['user_account_created_at'])[0];

	$next_forty_days = strtotime("+40 day", strtotime($registered_date));

	//echo date("d-M-Y", $next_forty_days);

	$today = strtotime("today");

	if($today <= $next_forty_days ){

		return true;
	}

	return false;

}



function total_user_referral_within_five_months($referral_id){
			
			global $con;

			//$query_users_referral = mysqli_query($con, "SELECT * FROM user_account WHERE referred_referral_id = '$referral_id' ");
			////you can uncomment the above query and comment the next query 
			/////just to get order by if working or not just to get last user
			$query_users_referral = mysqli_query($con, "SELECT * FROM user_account WHERE referred_referral_id = '$referral_id' ORDER by user_account_id DESC");

			$count_referral = mysqli_num_rows($query_users_referral);
			
			/////this count_referral should be change back to 5 from 1 later
			/////using 1 for testing moreover base on Destiny Promoters having referral
			////update paid for at #2,500
			if($count_referral >= 1){

				$count_total_referral = 0;

				while($fetch_current_users_referred = mysqli_fetch_array($query_users_referral)){
					
					extract($fetch_current_users_referred);

					$user_reg_date = explode(" ", $user_account_created_at)[0];
					

					$next_five_months = strtotime("+5 month", strtotime($user_reg_date));

					$today = strtotime("today");

					if($today >= $next_five_months){
						$count_total_referral++;
					}

				}

				if($count_total_referral >=  1){
					/////this count_total_referral should be change back to 5 from 1 later
			/////using 1 for testing moreover base on Destiny Promoters having referral
			////update paid for at #2,500
					return true;
				}else{
					return false;
				}

			}else{
				return false;
			}			

}



function update_users_referral_count_within_forty_days($referral_id){
	
	global $con;

	mysqli_query($con, "UPDATE user_account SET referral_count_within_forty_days = referral_count_within_forty_days + 1 WHERE referral_id='$referral_id'");
}


function query_counts_increment_user_referral($referral_id){
	global $con;
	$query = mysqli_query($con, "UPDATE user_account SET referral_count = referral_count + 1  WHERE referral_id='$referral_id'") or die(mysqli_error($con)); 
	return $query;
}


function query_login($email, $password, $data=false){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM user JOIN user_account ON user.user_account_id = user_account.user_account_id WHERE user.email_address='$email' OR user.phone_no='$email' AND user.password='$password' AND user.can_login='yes'");

	$count = mysqli_num_rows($query);  

	return $data ? $query : $count;
}

function query_user_all_account($user_id){

	global $con;
 
	$query = mysqli_query($con, "SELECT * FROM user_account WHERE user_id='$user_id' ORDER BY user_account_id ASC");

	return $query;

}


function query_all_user_generated_account($user_id, $count=false){

	global $con;
 
	$query = mysqli_query($con, "SELECT * FROM user_account WHERE user_id='$user_id' AND account_generated_type='automated' ORDER BY user_account_id DESC");

	return $count ? mysqli_num_rows($query) : $query;

}



function query_user_details($user_id){

	global $con;
 
	$query = mysqli_query($con, "SELECT * FROM user WHERE user_id='$user_id' ");

	return mysqli_fetch_array($query);

}



function query_user_exists_by_id($user_id){

	global $con;
 
	$query = mysqli_query($con, "SELECT * FROM user WHERE user_id='$user_id' ");

	$count = mysqli_num_rows($query);

	return $count;

}


function check_wallet_transaction_exist($wallet_payment_txn_id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM wallet_transaction_details WHERE wallet_payment_txn_id='$wallet_payment_txn_id'");
	$count = mysqli_num_rows($query);

	return $count;
}



function fund_wallet_creation($user_id, $user_account_id, $wallet_txn_ref, $wallet_payment_txn_id, $wallet_payment_amount, $wallet_txn_status ){ 
	
	global $con;

	$query = mysqli_query($con, "INSERT into wallet_transaction_details SET
		user_id = '$user_id',
		user_account_id = '$user_account_id',
		wallet_txn_ref = '$wallet_txn_ref',
		wallet_payment_txn_id = '$wallet_payment_txn_id',
		wallet_payment_amount = '$wallet_payment_amount',
		wallet_txn_status = '$wallet_txn_status',
		wallet_txn_created_at = NOW()
	") or die(mysqli_error($con));

	
	$update_user_id = mysqli_query($con, "UPDATE user_account SET user_account_wallet_amount = $wallet_payment_amount + user_account_wallet_amount  WHERE user_account_id = '$user_account_id' ") or die(mysqli_error($con));
	
	return $query;
}


function get_user_account_details($user_account_id, $type){

	global $con;

	$query =  mysqli_query($con, "SELECT * from user_account WHERE user_account_id = '$user_account_id'");


	$fetch_data = mysqli_fetch_array($query);

	return $fetch_data[$type];

}

function end_thrift_transaction($txn_id){
	
	global $con;

	$query = mysqli_query($con, "UPDATE thrift_transaction_details SET
		thrift_txn_counter = 'off',
		txn_status = 'completed'
		WHERE txn_id = '$txn_id'
	");

	return $con;
}


function set_total_referral_with_active_plan($referral_id){

	global $con;

	$query = mysqli_query($con,"UPDATE user_account SET 
		total_referral_with_active_plan = total_referral_with_active_plan + 1
		WHERE referral_id = '$referral_id';
	");

	return $query;

}

function get_user_wallet_transactions($user_account_id){
	global $con;
	
	$query = mysqli_query($con, "SELECT * FROM wallet_transaction_details WHERE user_account_id='$user_account_id' ORDER by wallet_txn_id DESC");
	return $query;
}



function fetch_all_bank_account_details(){
	global $con;

	$query = mysqli_query($con, "SELECT * from lists_banksortcode");
	
	return $query;	
}


function admin_fetch_wallet_transactions(){
	global $con;

	$query = mysqli_query($con, "SELECT * from wallet_transaction_details JOIN user_account ON user_account.user_account_id = wallet_transaction_details.user_account_id AND  wallet_transaction_details.wallet_payment_amount != 0 JOIN user ON user.user_id = wallet_transaction_details.user_id WHERE user_account.user_account_type = 'primary' ORDER BY wallet_transaction_details.wallet_txn_id DESC LIMIT 5000");

	return $query;
}

function query_all_thrift_package(){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM thrift_package");

	return $query;
}


function query_single_thrift_package($id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM thrift_package WHERE thrift_package_id='$id'");

	return $query;
}


function user_thrift_package_transaction($user_id,$user_account_id, $thrift_package_id, $thrift_profit,$thrift_start_date ,$thrift_end_date, $thrift_txn_counter, $txn_status){
	
	global $con;

	$query = mysqli_query($con, "INSERT INTO thrift_transaction_details SET
		user_id = '$user_id',
		user_account_id = '$user_account_id',
		thrift_package_id = '$thrift_package_id',
		thrift_profit = '$thrift_profit',
		thrift_start_date = '$thrift_start_date',
		thrift_end_date = '$thrift_end_date',
		thrift_txn_counter = '$thrift_txn_counter',
		txn_status = '$txn_status',
		txn_created_at = NOW()
	") or die(mysqli_error($con));

	/*$user_account_wallet_account = mysqli_query($con, "UPDATE user_account SET user_account_wallet_amount = user_account_wallet_amount - $package_price WHERE user_account_id = '$user_account_id' ") or die(mysqli_error($con));*/

	return $query;
}


function query_users_thrift_transaction($user_account_id){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM thrift_transaction_details JOIN thrift_package ON thrift_package.thrift_package_id =  thrift_transaction_details.thrift_package_id WHERE user_account_id = '$user_account_id' ORDER BY txn_id DESC");

	return $query;
}


function query_users_thrift_transaction_desc($user_account_id){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM thrift_transaction_details WHERE user_account_id = '$user_account_id' AND txn_status = 'completed' ORDER BY txn_id DESC ");

	return $query;
}


function query_users_thrift_active_transaction_desc($user_account_id){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM thrift_transaction_details WHERE user_account_id = '$user_account_id' AND txn_status != 'completed' ORDER BY txn_id DESC ");

	return $query;
}

function query_generated_users_thrift_active_transaction_desc($user_id){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM thrift_transaction_details JOIN user_account ON user_account.user_account_id = thrift_transaction_details.user_account_id  WHERE thrift_transaction_details.user_id = '$user_id' AND txn_status != 'completed' AND  user_account.account_generated_type = 'automated' ORDER BY txn_id DESC ");

	return $query;
}


function query_generated_users_with_thrift_inactive($user_id, $count=false){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM user_account LEFT JOIN  thrift_transaction_details ON  thrift_transaction_details.user_account_id = user_account.user_account_id WHERE user_account.user_id = '$user_id' AND  user_account.account_generated_type = 'automated' AND thrift_transaction_details.user_account_id is null ");

	return $count ? mysqli_num_rows($query) : $query;
}


function query_all_users_thrift_active_transaction($user_id, $user_account_id){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM thrift_transaction_details JOIN thrift_package ON thrift_transaction_details.thrift_package_id = thrift_package.thrift_package_id  WHERE user_id = '$user_id' AND thrift_transaction_details.user_account_id != '$user_account_id'  AND txn_status = 'active' ORDER BY txn_id DESC ");

	return $query;
}



function query_thrift_status($txn_id, $type){
	global $con;

	$query = mysqli_query($con, "SELECT * from thrift_transaction_details WHERE txn_id ='$txn_id' ");

	return mysqli_fetch_array($query)[$type];
}


function admin_clear_debt($thrift_fine, $txn_id, $user_weekly_thrift_id){
	
	global $con;

	$query = mysqli_query($con, "UPDATE thrift_transaction_details SET
		thrift_fine = '$thrift_fine' WHERE txn_id = '$txn_id'
	") or die(mysqli_error($con));


	mysqli_query($con, "UPDATE user_weekly_thrift SET
		user_weekly_thrift_status = 'paid' WHERE user_weekly_thrift_id = '$user_weekly_thrift_id' AND thrift_txn_id = '$txn_id'") or die(mysqli_error($con));

	return $query;
}

function admin_query_users_thrift_transaction(){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM thrift_transaction_details JOIN thrift_package ON thrift_package.thrift_package_id =  thrift_transaction_details.thrift_package_id JOIN user ON user.user_id = thrift_transaction_details.user_id JOIN user_account on thrift_transaction_details.user_account_id = user_account.user_account_id  ORDER BY thrift_transaction_details.txn_id DESC");

	return $query;

}


function get_url(){

	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	  $page_current_name = $protocol.basename($_SERVER['HTTP_HOST']);
	  if ($page_current_name=='http://localhost' || $page_current_name=='127.0.0.1') {
	    $get_page_link = $page_current_name."/destiny";
	  }
	  else{
	    $get_page_link = $page_current_name;
	  }

	  return $get_page_link;
	}


function create_payment_proof($user_id, $user_account_id, $payment_screenshot, $payment_type){
	global $con;

	$query = mysqli_query($con, "INSERT INTO payment_proof SET
		user_id	 = '$user_id',
		user_account_id	 = '$user_account_id',
		payment_screenshot	 = '$payment_screenshot',
		payment_type='$payment_type',
		payment_created_date = NOW()
	") or die(mysqli_error($con));

	return $query;
}



function admin_query_payment_proof($payment_type){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM payment_proof JOIN user ON payment_proof.user_id = user.user_id JOIN user_account ON  payment_proof.user_account_id = user_account.user_account_id WHERE payment_proof.payment_type = '$payment_type' ORDER BY payment_proof_id DESC LIMIT 100");

	return $query;
}	



function admin_update_payment_proof($payment_proof_id){

	global $con;

	$query = mysqli_query($con,"UPDATE payment_proof set 
		payment_status ='paid' WHERE payment_proof_id ='$payment_proof_id' ");

	return $query;

}


function user_get_payment_proof($user_account_id){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM payment_proof WHERE user_account_id = '$user_account_id' AND payment_type != 'registration' ORDER BY payment_proof_id DESC");

	return $query;
}



function user_get_login_payment_proof($user_account_id){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM payment_proof WHERE user_account_id = '$user_account_id' AND payment_type = 'registration' AND payment_status = 'cancelled'");

	return $query;
}


function admin_update_reg_payment_proof($payment_proof_id, $status, $reason = NULL){

	global $con;

	$query = mysqli_query($con,"UPDATE payment_proof set 
		payment_status ='$status', 
		proof_cancel_reason = '$reason'
		WHERE payment_proof_id ='$payment_proof_id' ");

	return $query;

}




function create_thrift_fast_track($user_id, $user_account_id, $thrift_previous_end_date, $thrift_current_end_date, $txn_id ){
	global $con;

	$query = mysqli_query($con, "INSERT INTO thrift_fast_track SET
		user_id = '$user_id',
		user_account_id = '$user_account_id',
		thrift_previous_end_date = '$thrift_previous_end_date',
		thrift_current_end_date = '$thrift_current_end_date',
		thrift_fast_track_created_at = NOW()
	");

	mysqli_query($con, "UPDATE thrift_transaction_details SET
		thrift_transaction_fast_track = 'yes',
		thrift_end_date = '$thrift_current_end_date'
		WHERE txn_id = '$txn_id'
	");

	return $query;
}


function admin_query_all_fast_track(){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM thrift_fast_track JOIN user_account ON user_account.user_account_id = thrift_fast_track.user_account_id JOIN user on user.user_id= thrift_fast_track.user_id ORDER by thrift_fast_track.thrift_fast_track_id DESC");

	return $query;
}


function check_if_weekly_thrift_exist($user_account_id, $current_weeks, $thrift_txn_id){

	global $con;

	$today = strtotime("today");

	$date = date("Y-m-d", $today);

	$query =  mysqli_query($con, "SELECT * FROM user_weekly_thrift  WHERE user_account_id ='$user_account_id' AND user_thrift_current_weeks = '$current_weeks' AND thrift_txn_id = '$thrift_txn_id'");

	$count = mysqli_num_rows($query);

	return $count;

}





function create_user_weekly_thrift($user_id, $user_account_id, $package_id, $thrift_txn_id, $user_thrift_current_weeks, $type, $balance){
	global $con;

	/////check to avoid duplicate thrift current week please.
	$query = mysqli_query($con,"SELECT * FROM user_weekly_thrift WHERE user_id = '$user_id' AND user_account_id = '$user_account_id' AND thrift_txn_id = '$thrift_txn_id' AND user_thrift_current_weeks = '$user_thrift_current_weeks'");
	if (mysqli_num_rows($query)>0) {
		//this means it exist. do nothing than to re-set the is_saturday_thrift_run to YES.
		 mysqli_query($con, "UPDATE user_account SET user_account_wallet_amount='$balance', is_saturday_thrift_run = 'yes' WHERE user_account_id = '$user_account_id' ");
	}
	else{

	$query = mysqli_query($con, "INSERT INTO user_weekly_thrift SET  
		user_id = '$user_id', 
		user_account_id = '$user_account_id',
		thrift_package_id = '$package_id',
		thrift_txn_id = '$thrift_txn_id',
		user_thrift_current_weeks = '$user_thrift_current_weeks',
		user_weekly_thrift_status = '$type',
		user_weekly_thrift_created_at = NOW()
	");

	mysqli_query($con, "UPDATE user_account SET user_account_wallet_amount='$balance', is_saturday_thrift_run = 'yes' WHERE user_account_id = '$user_account_id' ");

	}
	return $query;

}


function query_user_weekly_thrift($thrift_txn_id, $user_account_id){
		
		global $con;

		$query = mysqli_query($con, "SELECT * from user_weekly_thrift WHERE user_account_id = '$user_account_id' AND thrift_txn_id = '$thrift_txn_id' ");

		return $query;

}



function create_thrift_fine($user_account_id, $amount, $txn_id){

	global $con;

	$query = mysqli_query($con, "UPDATE thrift_transaction_details SET thrift_fine = thrift_fine + $amount WHERE user_account_id = '$user_account_id' AND txn_id = '$txn_id'");

	return $query;
}


function request_withdraw($user_id, $user_account_id, $thrift_txn_id,$thrift_package_id, $request_withdrawable_amount){
	global $con;

	$query = mysqli_query($con, "INSERT INTO request_withdraw SET
		user_id ='$user_id',
		user_account_id = '$user_account_id',
		thrift_txn_id = '$thrift_txn_id',
		thrift_package_id = '$thrift_package_id	',
		request_withdrawable_amount ='$request_withdrawable_amount',
		request_withdraw_created_on	 = NOW()
	") or die(mysqli_error($con));

	return $query;
}

function check_request_withdraw_exist($thrift_txn_id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM request_withdraw WHERE thrift_txn_id='$thrift_txn_id'");

	return $query;
}


function admin_query_all_withdraw_request(){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM request_withdraw JOIN user ON user.user_id = request_withdraw.user_id JOIN user_account ON user_account.user_account_id = request_withdraw.user_account_id JOIN thrift_package ON thrift_package.thrift_package_id = request_withdraw.thrift_package_id JOIN thrift_transaction_details ON thrift_transaction_details.txn_id = request_withdraw.thrift_txn_id WHERE request_withdraw.request_withdraw_status='pending' AND request_withdraw.cronjob_fee='yes' GROUP by request_withdraw.user_id ORDER by user.fullname ASC LIMIT 20");

	return $query;
	
}


function check_request_withdraw_approved($thrift_txn_id){
	
	global $con;

	$query = mysqli_query($con, "SELECT * FROM request_withdraw WHERE thrift_txn_id='$thrift_txn_id' AND request_withdraw_status = 'paid' ");

	return mysqli_num_rows($query);
}


function update_thrift_fine_debt($user_account_id, $txn_id, $balance){
	
	global $con;

	$query = mysqli_query($con, "UPDATE thrift_transaction_details SET thrift_fine = 0 WHERE user_account_id = '$user_account_id' AND txn_id = '$txn_id'");

	mysqli_query($con, "UPDATE user_weekly_thrift SET user_weekly_thrift_status = 'paid', user_weekly_thrift_created_at = NOW() WHERE user_account_id = '$user_account_id' AND user_weekly_thrift_status = 'debt' AND thrift_txn_id='$txn_id'");

	return $query;
}


function create_user_shirt_payment($balance, $user_account_id, $user_id){

	global $con;

	$query = mysqli_query($con, "UPDATE user SET user_cloth_fee = 'paid' WHERE user_id = '$user_id' ") or die(mysqli_error($con));

	update_user_balance($balance, $user_account_id, $con);

	return $query;

}



function create_user_processing_fee($balance, $user_account_id, $thrift_txn_id){
	
	global $con;

	$query = mysqli_query($con, "UPDATE  thrift_transaction_details SET thrift_processing_fee = 'paid' WHERE txn_id = '$thrift_txn_id' ") or die(mysqli_error($con));

	return $query;
}


function update_user_balance($balance, $user_account_id, $con){

	$query = mysqli_query($con, "UPDATE user_account SET user_account_wallet_amount = $balance WHERE user_account_id = '$user_account_id' ") or die(mysqli_error($con));

	return $query;
}


function set_thrift_incentives($txn_id, $incentive_type,$incentive_pickup_type, $incentive_delivery_state, $incentive_delivery_number, $incentive_amount,$incentive_delivery_city, $incentive_delivery_status = "pending"  ){
	
	global $con;

	$query = mysqli_query($con, "UPDATE thrift_transaction_details SET 
			incentive_type = '$incentive_type',
			incentive_pickup_type = '$incentive_pickup_type',
			incentive_delivery_state = '$incentive_delivery_state',
			incentive_delivery_number = '$incentive_delivery_number',
			incentive_amount = '$incentive_amount',
			incentive_delivery_city = '$incentive_delivery_city',
			incentive_delivery_status = '$incentive_delivery_status'
			WHERE txn_id = '$txn_id'
	") or die(mysqli_error($con));

	return $query;
}



function set_thrift_incentives_status( $txn_id, $balance, $user_account_id, $incentive_delivery_status ){
	
	global $con;

	$query = mysqli_query($con, "UPDATE thrift_transaction_details SET 
			incentive_delivery_status = '$incentive_delivery_status'
			WHERE txn_id = '$txn_id'
	") or die(mysqli_error($con));
	return $query;
}

	
function get_state($state_id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM states where state_id='$state_id'");

	return mysqli_fetch_array($query)['state_name'];
}

function get_city($city_id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM cities where city_id='$city_id'");

	return mysqli_fetch_array($query)['city_name'];

}


function set_thrift_incentive_delivery($thrift_txn_id){
	global $con;

	$query = mysqli_query($con, "UPDATE thrift_transaction_details SET 
			incentive_admin_delivery_status = 'delivered'
			WHERE txn_id = '$thrift_txn_id'
	") or die(mysqli_error($con));

	return $query;
}


//kazeem work starts here
function get_single_user_only_primary_account($session_logged_in_user_id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM user_account JOIN user ON user.user_id = user_account.user_id WHERE user_account.user_id = '$session_logged_in_user_id' AND user_account.user_account_type='primary'");
	return $query;
}



function query_user_virtual_bank_details($user_id){
	
	global $con;

	$query = mysqli_query($con, "SELECT * FROM user_virtual_account WHERE user_id = '$user_id' ") or die(mysqli_error($con));

	return $query;
}

function query_check_user_virtual_account_existence($user_id){
	global $con;
	
	$query = mysqli_query($con,"SELECT * FROM user_virtual_account WHERE user_id = '$user_id'") or die(mysqli_error($con));

	return $query;
}


function create_webhook_event_history($transaction_id,$tx_ref,$flw_ref,$customer_name,$phone_number,$email,$device_fingerprint,$amount,$currency,$charged_amount,$app_fee,$merchant_fee,$processor_response,$ip,$narration,$status,$payment_type,$created_at,$event_type){
	global $con;

	$query = mysqli_query($con,"INSERT into webhook_event_transaction_history SET
		webhook_transaction_id = '$transaction_id',
		tx_ref = '$tx_ref',
		flw_ref = '$flw_ref', 
		customer_name = '$customer_name',
		phone_number = '$phone_number',
		email = '$email',
		device_fingerprint = '$device_fingerprint',
		amount = '$amount',
		currency = '$currency',
		charged_amount = '$charged_amount',
		app_fee = '$app_fee', 
		merchant_fee = '$merchant_fee',
		processor_response = '$processor_response',
		ip = '$ip',
		narration = '$narration',
		status = '$status',
		payment_type = '$payment_type',
		created_at = '$created_at',
		event_type = '$event_type',
		event_type_created_at = NOW()
	") or die(mysqli_error($con));

	return $query;
}




function query_single_user_with_id($user_id){
	global $con;  
	$query = mysqli_query($con,"SELECT * FROM user WHERE user_id = '$user_id'");
	return $query;
}


function admin_manage_virtual_account_transactions(){
	global $con;
	$query = mysqli_query($con,"SELECT * FROM webhook_event_transaction JOIN user_virtual_account ON user_virtual_account.virtual_account_tx_ref = webhook_event_transaction.tx_ref JOIN user ON user.user_id = user_virtual_account.user_id ORDER by webhook_event_transaction.webhook_event_transaction_id DESC LIMIT 1"); 
	return $query;
}
//kazeem work ends here




/*IB UPDATE STARTS HERE*/

function query_last_account_id(){
		
	global $con;

	$query = mysqli_query($con, "SELECT user_account_id FROM user_account ORDER BY user_account_id DESC");

	return $query;
}

function random_strings($length_of_string){ 
    $str_result = mt_rand(111111, 999999 ) . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz'; 
    return substr(str_shuffle($str_result), 0, $length_of_string); 
}

function random_numbers($length_of_numbers){  
    $str_result = mt_rand(111111, 999999 ) . '1234567890987684321'. mt_rand(111111, 999999); 
    return substr(str_shuffle($str_result), 0, $length_of_numbers); 
}  


/*IB UPDATE ENDS HERE*/	


function query_phone_number_exists($phone, $type=false){
	global $con;

	$query = mysqli_query($con, "SELECT *  FROM user WHERE phone_no='$phone'") or die(mysqli_error($con));
	$rows = mysqli_num_rows($query); 
	$fetch_query = mysqli_fetch_array($query);
	if ($rows > 0 && $type) {
		return $fetch_query[$type];
	}
	return $rows;
}
	




function query_user_data($user_id, $value){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM user where user_id='$user_id'");

	return mysqli_fetch_array($query)[$value];
}




/*october 12*/
function user_check_payment_proof_existence($user_id){
	global $con;
	$query = mysqli_query($con,"SELECT * FROM payment_proof WHERE user_id = '$user_id' AND payment_status = 'unpaid'");
	return $query;
}

/*october 12*/


/*october 16*/
function clear_debt_log($user_id, $user_account_id, $txn_id, $admin_id, $thrift_current_week, $user_weekly_thrift_id){
	global $con;

	$query = mysqli_query($con, "INSERT INTO clear_debt_log SET
		user_id='$user_id',
		user_account_id='$user_account_id',
		txn_id='$txn_id',
		admin_id='$admin_id',
		thrift_current_week='$thrift_current_week',
		user_weekly_thrift_id='$user_weekly_thrift_id',
		clear_debt_created_on=NOW()
	");
}



function query_user_virtual_account_transactions($user_id){
	global $con;
	$query = mysqli_query($con,"SELECT * FROM webhook_event_transaction JOIN user_virtual_account ON user_virtual_account.virtual_account_tx_ref = webhook_event_transaction.tx_ref JOIN user ON user.user_id = user_virtual_account.user_id WHERE user_virtual_account.user_id = '$user_id' ORDER by webhook_event_transaction.webhook_event_transaction_id DESC"); 
	return $query;
}
/*october 16*/



/*October 24*/
function create_wallet_transfer_pin($user_id,$transfer_pin){
	global $con;	
	$query = mysqli_query($con,"UPDATE user SET wallet_transfer_pin = '$transfer_pin' WHERE user_id = '$user_id'");
	return $query;
} 
/*October 24*/



/*November 5*/
function admin_insert_user_reg_fee($user_id, $user_account_id, $admin_id){
	
	global $con;

	$query = mysqli_query($con, "INSERT INTO reg_fee SET
		user_id='$user_id',
		user_account_id='$user_account_id',
		admin_id='$admin_id',
		reg_fee_created_on=NOW();
	");

	return $query;

}
/*November 5*/


function getTotalPaidcontribution($user_account_id,$thrift_txn_id){
		
		global $con;

		$query = mysqli_query($con, "SELECT * FROM user_weekly_thrift WHERE user_account_id='$user_account_id' AND user_weekly_thrift_status='paid' AND thrift_txn_id = '$thrift_txn_id' GROUP by user_thrift_current_weeks");

		$count = mysqli_num_rows($query);

		return $count;

}



/*Jan 16 2023*/

function query_single_user_account_with_id($user_account_id,$user_id){
	global $con;

	$query = mysqli_query($con,"SELECT * FROM user_account WHERE md5(user_id) = '$user_id' AND user_account_id = '$user_account_id'");

	return $query;
}

/*Jan 16 2023*/



/*APRIL 8, 2023 CREATED FOR FASTRACK*/
function log_fasttrack_weeks_balance($user_id,$user_account_id,$txn_id,$current_week_when_fasttracked,$uncovered_weeks_when_fasttracked,$remaining_weeks_to_balance){
	global $con;

	/////check if never exist/log fast track for that user id and user acct and txn id  before
	$check_duplicate = mysqli_query($con,"SELECT * FROM fast_track_weeks_balance WHERE user_id = '$user_id' AND user_account_id = '$user_account_id' AND txn_id = '$txn_id' AND fasttrack_weeks_pending_balance = 'pending'");
	if (mysqli_num_rows($check_duplicate)>0) {
		// code...
	}
	else{
		////do something
	$query = mysqli_query($con,"INSERT into fast_track_weeks_balance SET 
		user_id = '$user_id',
		user_account_id = '$user_account_id',
		txn_id = '$txn_id',
		current_week_when_fasttracked = '$current_week_when_fasttracked',
		uncovered_weeks_when_fasttracked = '$uncovered_weeks_when_fasttracked',
		remaining_weeks_to_balance = '$remaining_weeks_to_balance',
		fast_track_weeks_balance_created_at = NOW()
	");
	}

	return @$query;
}





function check_fasttrack_weeks_balance_paid($user_account_id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM fast_track_weeks_balance WHERE user_account_id='$user_account_id' AND fasttrack_weeks_pending_balance = 'pending'");

	return $query;
}


function submit_clearance_fee_log($user_id,$user_account_id,$txn_id,$balance_before_deduction,$balance_after_deduction){
	global $con;

	$query = mysqli_query($con, "INSERT into clearance_fee_log SET 
		user_id = '$user_id',
		user_account_id = '$user_account_id',
		txn_id = '$txn_id',
		balance_before_deduction = '$balance_before_deduction',
		balance_after_deduction = '$balance_after_deduction',
		clearance_fee_log_date = NOW()
	");

	return $query;
}

function get_only_primary_user_account_details($user_id){

	global $con;

	$query =  mysqli_query($con, "SELECT * FROM user_account WHERE user_id = '$user_id' AND user_account_type = 'primary'");


	$fetch_data = mysqli_fetch_array($query);

	return $fetch_data;

}


function submit_fasttrack_balance_payment_log($user_id,$user_account_id,$txn_id,$balance_before_deduction,$balance_after_deduction){
	global $con;

	$query = mysqli_query($con, "INSERT into fasttrack_balance_payment_log SET 
		user_id = '$user_id', 
		user_account_id = '$user_account_id',
		txn_id = '$txn_id', 
		balance_before_deduction = '$balance_before_deduction',
		balance_after_deduction = '$balance_after_deduction',
		fasttrack_balance_fee_log_date = NOW()
	");

	return $query;
}

function submit_incentives_payment_log($user_id,$user_account_id,$txn_id,$balance_before_deduction,$balance_after_deduction){
	global $con;

	$query = mysqli_query($con, "INSERT into incentives_payment_log SET 
		user_id = '$user_id', 
		user_account_id = '$user_account_id',
		txn_id = '$txn_id', 
		balance_before_deduction = '$balance_before_deduction',
		balance_after_deduction = '$balance_after_deduction',
		incentives_fee_log_date = NOW()
	");

	return $query;
}


function submit_debt_cleared_once_payment_log($user_id,$user_account_id,$txn_id,$balance_before_deduction,$balance_after_deduction){
	global $con;

	$query = mysqli_query($con, "INSERT into debt_cleared_once_payment_log SET 
		user_id = '$user_id', 
		user_account_id = '$user_account_id',
		txn_id = '$txn_id', 
		balance_before_deduction = '$balance_before_deduction',
		balance_after_deduction = '$balance_after_deduction',
		onetime_debt_cleared_fee_log_date = NOW()
	");

	return $query;
}
/*APRIL 8, 2023 CREATED FOR FASTRACK*/



/*APRIL 16 2023*/
function admin_query_all_pending_withdrawalable_acct_by_userid($user_id){
	global $con;
	$query = mysqli_query($con,"SELECT * FROM request_withdraw WHERE user_id = '$user_id' AND request_withdraw_status = 'pending'");

	return $query;
}

function update_empowered_withdrawal_account($withdrawal_fee,$user_account_id,$thrift_txn_id){
	global $con;
	$query = mysqli_query($con,"UPDATE request_withdraw SET 
			request_withdrawable_amount = '$withdrawal_fee', cronjob_fee = 'yes' WHERE
			user_account_id = '$user_account_id' AND
			thrift_txn_id = '$thrift_txn_id'
		");
	return $query;
}

function query_all_non_closure_paid_account(){
	global $con;
	$query = mysqli_query($con,"SELECT * FROM request_withdraw JOIN user_account ON request_withdraw.user_account_id = user_account.user_account_id WHERE request_withdraw.request_withdraw_status = 'paid' AND cronjob_fee = 'yes' AND account_closed = 'no' LIMIT 100");
	return $query;
}

function move_paidaccount_to_settlement_tbl($user_account_id,$user_id){
	global $con;
	$query = mysqli_query($con,"INSERT into settlement_account (user_account_id,user_id,user_account_token,referral_id,referred_referral_id,referral_count,referral_count_within_forty_days,single_referral_empowered,total_referral_with_active_plan,user_account_wallet_amount,user_account_ledger_balance,user_account_type,account_generated_type,user_account_created_at) (SELECT user_account_id,user_id,user_account_token,referral_id,referred_referral_id,referral_count,referral_count_within_forty_days,single_referral_empowered,total_referral_with_active_plan,user_account_wallet_amount,user_account_ledger_balance,user_account_type,account_generated_type,user_account_created_at FROM user_account WHERE user_account_id = '$user_account_id' AND user_id = '$user_id')") or die(mysqli_error($con));
	return $query;
}

function update_settlement_account_created_date($user_account_id,$user_id){
	global $con;
	$query = mysqli_query($con,"UPDATE settlement_account SET settlement_account_created_at = NOW() WHERE user_account_id = '$user_account_id' AND user_id = '$user_id'");
	return $query;
}

function permanent_delete_user_account($user_account_id,$user_id){
	global $con; 
	$query = mysqli_query($con,"DELETE FROM user_account WHERE user_account_id = '$user_account_id' AND user_id = '$user_id'"); 
	return $query;
}

function log_other_account_settlement_wallet_transfer($user_id,$user_account_id,$balance_before_deduction,$balance_after_deduction,$the_acct_primary_balance){
	global $con;
	$query = mysqli_query($con,"INSERT into settlement_acct_balance_transfer SET 
		user_id = '$user_id',
		user_account_id = '$user_account_id',
		balance_before_deduction = '$balance_before_deduction',
		balance_after_deduction = '$balance_after_deduction',
		the_acct_primary_balance = '$the_acct_primary_balance',
		created_at = NOW()
	") or die(mysqli_error($con));
	return $query;
}

function query_check_duplicate_close_account($user_id,$user_account_id){
	global $con;
	$query = mysqli_query($con,"SELECT * FROM settlement_account WHERE user_id = '$user_id' AND user_account_id = '$user_account_id' AND user_account_type = 'others'");
	return $query;
}

function move_paidaccount_to_settlement_tbl_primary($user_account_id,$user_id){
	global $con;
	$query = mysqli_query($con,"INSERT into settlement_account (user_account_id,user_id,user_account_token,referral_id,referred_referral_id,referral_count,referral_count_within_forty_days,single_referral_empowered,total_referral_with_active_plan,user_account_wallet_amount,user_account_ledger_balance,user_account_type,account_generated_type,user_account_created_at) (SELECT user_account_id,user_id,user_account_token,referral_id,referred_referral_id,referral_count,referral_count_within_forty_days,single_referral_empowered,total_referral_with_active_plan,user_account_wallet_amount,user_account_ledger_balance,user_account_type,account_generated_type,user_account_created_at FROM user_account WHERE user_account_id = '$user_account_id' AND user_id = '$user_id')") or die(mysqli_error($con));
	return $query;
}


function update_settlement_account_created_date_primary($user_account_id,$user_id,$last_insert_id){
	global $con;
	$query = mysqli_query($con,"UPDATE settlement_account SET settlement_account_created_at = NOW() WHERE user_account_id = '$user_account_id' AND user_id = '$user_id' AND settlement_account_id = '$last_insert_id'");
	return $query;
}

function update_close_primary_account_column_after_settlement($user_id,$user_account_id,$generate_user_refferal_id){
	global $con;
	$query = mysqli_query($con,"UPDATE user_account SET 
		referral_id = '$generate_user_refferal_id',
		referral_count = '0',
		referral_count_within_forty_days = '0',
		single_referral_empowered = 'no',
		total_referral_with_active_plan = '0',
		user_account_created_at = NOW() WHERE
		user_id = '$user_id' AND user_account_id = '$user_account_id' AND user_account_type = 'primary'
	") or die(mysqli_error($con));
	return $query;
}
/*APRIL 16 2023*/


/*JUNE 6 2023*/
function query_active_account_totalpaid_debt_transaction($user_id, $user_account_id){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM thrift_transaction_details WHERE user_id = '$user_id' AND user_account_id = '$user_account_id' AND txn_status ='active' ORDER BY txn_id DESC");

	return $query;
}


function getTotalDebtcontribution($user_account_id){
		
		global $con;

		$query = mysqli_query($con, "SELECT * FROM user_weekly_thrift WHERE user_account_id='$user_account_id' AND user_weekly_thrift_status='debt'");

		$count = mysqli_num_rows($query);

		return $count;

}
/*JUNE 6 2023*/




/*SEPT 2023 UPDATES FOR ADMIN*/
// Function to get the client IP address
function noble_get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


function get_users_paid_settlements_account($user_id){
	global $con;
	$query = mysqli_query($con,"SELECT * FROM settlement_account JOIN user ON user.user_id = settlement_account.user_id WHERE settlement_account.user_id = '$user_id'");
	return $query;	
}

function get_users_fasttracked_settlements_account($user_id){
	global $con;
	$query = mysqli_query($con,"SELECT * FROM thrift_fast_track JOIN user ON user.user_id = thrift_fast_track.user_id JOIN settlement_account ON settlement_account.user_account_id = thrift_fast_track.user_account_id WHERE thrift_fast_track.user_id = '$user_id'");
	return $query;	
}

function query_withdrawal_request_for_single_user($user_id){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM request_withdraw JOIN user ON user.user_id = request_withdraw.user_id JOIN user_account ON user_account.user_account_id = request_withdraw.user_account_id JOIN thrift_package ON thrift_package.thrift_package_id = request_withdraw.thrift_package_id JOIN thrift_transaction_details ON thrift_transaction_details.txn_id = request_withdraw.thrift_txn_id WHERE request_withdraw.request_withdraw_status='pending' AND request_withdraw.cronjob_fee='yes' AND request_withdraw.user_id = '$user_id' ORDER by request_withdraw.request_withdraw_id ASC ");

	return $query;
	
}

function admin_credit_members_wallet($user_id, $user_account_id, $wallet_txn_ref, $wallet_payment_txn_id, $wallet_payment_amount, $wallet_txn_status, $wallet_funded_by,$wallet_session_id){ 
	
	global $con;

	$query = mysqli_query($con, "INSERT into wallet_transaction_details SET
		user_id = '$user_id',
		user_account_id = '$user_account_id',
		wallet_txn_ref = '$wallet_txn_ref',
		wallet_payment_txn_id = '$wallet_payment_txn_id',
		wallet_payment_amount = '$wallet_payment_amount',
		wallet_txn_status = '$wallet_txn_status',
		wallet_funded_by = '$wallet_funded_by',
		wallet_session_id = '$wallet_session_id',
		wallet_txn_created_at = NOW()
	") or die(mysqli_error($con));


	$update_user_id = mysqli_query($con, "UPDATE user_account SET user_account_wallet_amount = user_account_wallet_amount + $wallet_payment_amount  WHERE user_account_id = '$user_account_id' ") or die(mysqli_error($con));
	
	return $query;
}


function admin_fetch_wallet_transactions_single_user_primary_only($user_id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM wallet_transaction_details JOIN user_account ON user_account.user_account_id = wallet_transaction_details.user_account_id JOIN user ON user.user_id = wallet_transaction_details.user_id WHERE user_account.user_account_type = 'primary' AND wallet_transaction_details.user_id = '$user_id' ORDER BY wallet_transaction_details.wallet_txn_id DESC");

	return $query;
}


function admin_update_members_name($user_id,$new_members_name){
	global $con;

	$query = mysqli_query($con, "UPDATE user SET fullname = '$new_members_name' WHERE user_id = '$user_id'") or die(mysqli_error($con));

	return $query;
}

function admin_name_update_log($sess_id,$user_id,$old_user_name,$new_members_name){
	global $con;

	$query = mysqli_query($con, "INSERT into admin_name_update_log SET 
		admin_id = '$sess_id',
		user_id = '$user_id',
		old_user_name = '$old_user_name',
		new_user_name = '$new_members_name',
		name_update_created_at = NOW()
		") or die(mysqli_error($con));

	return $query;
}

function admin_log_who_cancelled_payment_proof($admin_id,$payment_proof_id){
	global $con;

	$query = mysqli_query($con, "UPDATE payment_proof SET proof_cancelled_by = '$admin_id' WHERE payment_proof_id = '$payment_proof_id'") or die(mysqli_error($con));

	return $query;
}

function admin_insert_payment_confirmed_by($admin_id,$user_id,$payment_proof_id){
	global $con;

	$query = mysqli_query($con, "INSERT into admin_payment_proof_confirmation_log SET 
		admin_id = '$admin_id',
		user_id = '$user_id',
		payment_proof_id = '$payment_proof_id',
		proof_confirmed_at = NOW()
		") or die(mysqli_error($con));

	return $query;
}

/*SEPT 2023 UPDATES FOR NEW ADMIN*/



/*DECEMBER FOODIE CODES STARTS HERE*/
/*DECEMBER FOODIE CODES STARTS HERE*/
/*DECEMBER FOODIE CODES STARTS HERE*/

function check_eligible_to_apply_for_foodie($user_id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM user_account JOIN thrift_transaction_details ON user_account.user_account_id = thrift_transaction_details.user_account_id WHERE user_account.user_id = '$user_id' AND thrift_transaction_details.txn_status = 'active' AND thrift_transaction_details.thrift_txn_counter = 'on'") or die(mysqli_error($con));

	return $query;
}


function check_if_foodie_eligible_exist($user_id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM foodie_application WHERE user_id = '$user_id' AND foodie_application_status = 'active'") or die(mysqli_error($con));

	return $query;
}

function create_foodie_application($user_id,$total_active_thrift){
	global $con;

	$query = mysqli_query($con, "INSERT into foodie_application SET 
		user_id = '$user_id',
		total_active_thrift = '$total_active_thrift',
		foodie_application_date = NOW()
	") or die(mysqli_error($con));

	return $query;
}

function query_foodie_virtual_account_existence($user_id){
	global $con;
	
	$query = mysqli_query($con,"SELECT * FROM foodie_virtual_account WHERE user_id = '$user_id'") or die(mysqli_error($con));

	return $query;
}

function log_foodie_virtual_account_data_created($user_id,$virtual_account_tx_ref,$R_vt_acct_status,$R_vt_acct_message,$R_vt_acct_response_message,$R_vt_acct_flw,$R_vt_acct_order_ref,$R_vt_acct_account_number,$R_vt_acct_frequency,$R_vt_acct_bank_name,$R_vt_acct_created_at,$R_vt_acct_expiry_date,$R_vt_acct_note)
{
  global $con; 

      /////now logged to db foodie_virtual_account tho
      $query = mysqli_query($con,"INSERT into foodie_virtual_account SET 
        user_id = '$user_id',
        virtual_account_tx_ref = '$virtual_account_tx_ref',
        user_vt_acct_status = '$R_vt_acct_status',
        user_vt_acct_message = '$R_vt_acct_message',
        user_vt_acct_response_message = '$R_vt_acct_response_message',
        user_vt_acct_flw_ref = '$R_vt_acct_flw',
        user_vt_acct_order_ref = '$R_vt_acct_order_ref',
        user_vt_acct_account_number = '$R_vt_acct_account_number',
        user_vt_acct_frequency = '$R_vt_acct_frequency',
        user_vt_acct_bank_name = '$R_vt_acct_bank_name',
        user_vt_acct_created_at = '$R_vt_acct_created_at',
        user_vt_acct_expiry_date = '$R_vt_acct_expiry_date',
        user_vt_acct_note = '$R_vt_acct_note'
      ");

      return $query;

}


function query_user_foodie_virtual_details($user_id){
	
	global $con;

	$query = mysqli_query($con, "SELECT * FROM foodie_virtual_account WHERE user_id = '$user_id' ") or die(mysqli_error($con));

	return $query;
}

function query_dec_foodie_package(){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM foodie_package");

	return $query;
}

function get_individual_foodie_account_wallet_bal($user_id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM foodie_application WHERE user_id = '$user_id' ");

	return $query;
}

function update_foodie_wallet_balance($user_id,$balance){
	global $con;

	$query = mysqli_query($con, "UPDATE foodie_application SET foodie_wallet = '$balance' WHERE user_id = '$user_id'");

	return $query;
}


function query_register_foodie_aaccount($user_id,$foodie_package_id,$foodie_account_token,$foodie_account_start_date,$foodie_account_end_date){
	global $con;

	$query = mysqli_query($con, "INSERT into foodie_account SET
		user_id = '$user_id',
		foodie_package_id = '$foodie_package_id',
		foodie_account_token = '$foodie_account_token',
		foodie_account_start_date = '$foodie_account_start_date',
		foodie_account_end_date = '$foodie_account_end_date',
		foodie_account_created_at = NOW()
	") or die(mysqli_error($con));

	return $query;
}

function query_individual_foodie_account($user_id){
	global $con;

	$query = mysqli_query($con, "SELECT * FROM foodie_account WHERE user_id = '$user_id' AND foodie_account_status = 'active' ") or die(mysqli_error($con));

	return $query;
}


function create_foodie_monthly_counter($user_id,$foodie_account_id,$foodie_current_months,$current_months_paid){
	global $con;

	$query = mysqli_query($con, "INSERT into foodie_monthly_counter_history SET
		user_id = '$user_id', 
		foodie_account_id = '$foodie_account_id',
		foodie_current_months = '$foodie_current_months',
		current_months_paid = '$current_months_paid',
		foodie_monthly_created_at = NOW()
	") or die(mysqli_error($con));

	return $query;
}



function log_foodie_webhook_txn_history($transaction_id,$tx_ref,$flw_ref,$customer_name,$phone_number,$email,$device_fingerprint,$amount,$currency,$charged_amount,$app_fee,$merchant_fee,$processor_response,$ip,$narration,$status,$payment_type,$created_at,$event_type){
	global $con;

	$query = mysqli_query($con,"INSERT into foodie_webhook_transaction_history SET
		webhook_transaction_id = '$transaction_id',
		tx_ref = '$tx_ref',
		flw_ref = '$flw_ref', 
		customer_name = '$customer_name',
		phone_number = '$phone_number',
		email = '$email',
		device_fingerprint = '$device_fingerprint',
		amount = '$amount',
		currency = '$currency',
		charged_amount = '$charged_amount',
		app_fee = '$app_fee', 
		merchant_fee = '$merchant_fee',
		processor_response = '$processor_response',
		ip = '$ip',
		narration = '$narration',
		status = '$status',
		payment_type = '$payment_type',
		created_at = '$created_at',
		event_type = '$event_type',
		event_type_created_at = NOW()
	") or die(mysqli_error($con));

	return $query;
}

/*DECEMBER FOODIE CODES ENDS HERE*/
/*DECEMBER FOODIE CODES ENDS HERE*/
/*DECEMBER FOODIE CODES ENDS HERE*/




/*JAN 2024 CODE STARTS HERE*/
function query_users_active_contribution_acct($user_id){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM thrift_transaction_details JOIN thrift_package ON thrift_transaction_details.thrift_package_id = thrift_package.thrift_package_id  WHERE thrift_transaction_details.user_id = '$user_id' AND thrift_transaction_details.txn_status = 'active' ORDER BY thrift_transaction_details.txn_id DESC ");

	return $query;
}


function let_primary_acct_activate_thrift_once($user_account_id){
///this is to restrict primary acct from having 2nd contribtn again.
	global $con;
	
	$query = mysqli_query($con, "SELECT * FROM thrift_transaction_details JOIN user_account ON user_account.user_account_id = thrift_transaction_details.user_account_id WHERE user_account.user_account_id ='$user_account_id' AND user_account.user_account_type = 'primary' AND thrift_transaction_details.txn_status ='completed' ");

	return $query;
}


function get_about_to_paid_account_without_tshirt($user_id){
///this is to check if a user exist in request withdraw db b4 or not
	global $con;

	$query = mysqli_query($con, "SELECT * FROM settlement_account WHERE user_id = '$user_id'");
	/////if the user is not found in settlement_account tbl and we compare that does the user pay for tshirt, then this are the new people to give tshirt.

	//////since u didnt pay for the shirt at all.

	return $query;
}


function admin_query_filter_pending_withdraw_request($start_date,$end_date){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM request_withdraw JOIN user ON user.user_id = request_withdraw.user_id JOIN user_account ON user_account.user_account_id = request_withdraw.user_account_id JOIN thrift_package ON thrift_package.thrift_package_id = request_withdraw.thrift_package_id JOIN thrift_transaction_details ON thrift_transaction_details.txn_id = request_withdraw.thrift_txn_id WHERE ( request_withdraw.request_withdraw_status='pending' AND request_withdraw.cronjob_fee='yes' AND request_withdraw_created_on BETWEEN '" . $start_date . "' AND  '" . $end_date . "')"."GROUP by request_withdraw.user_id ORDER by user.fullname ASC");

	return $query;


	
}


function admin_query_if_user_has_pending_acct_withdraw_request($user_id, $count=false){
	global $con;
	$query = mysqli_query($con,"SELECT * FROM request_withdraw WHERE user_id = '$user_id' AND request_withdraw_status = 'pending' AND account_closed = 'no'");

	return $count ? mysqli_num_rows($query) : $query;
}


function admin_query_all_paid_waybill_withdrawalable_acct_by_userid($user_id){
	global $con;
	$query = mysqli_query($con,"SELECT * FROM request_withdraw JOIN thrift_transaction_details ON thrift_transaction_details.txn_id = request_withdraw.thrift_txn_id WHERE request_withdraw.user_id = '$user_id' AND request_withdraw.request_withdraw_status='paid' AND request_withdraw.account_closed='yes' AND thrift_transaction_details.incentive_pickup_type = 'Waybill' AND request_withdraw_created_on > NOW() - INTERVAL 35 DAY ");

	return $query;
}
/*JAN 2024 CODE ENDS HERE*/


/*JULY 24 2024 CODE ENDS HERE*/
function admin_update_members_phone($user_id,$new_members_phone){
	global $con;

	$query = mysqli_query($con, "UPDATE user SET phone_no = '$new_members_phone' WHERE user_id = '$user_id'") or die(mysqli_error($con));

	return $query;
}


function admin_phone_number_update_log($sess_id,$user_id,$current_phone,$new_members_phone){
	global $con;

	$query = mysqli_query($con, "INSERT into admin_phone_update_log SET 
		admin_id = '$sess_id',
		user_id = '$user_id',
		old_phone_no = '$current_phone',
		new_phone_no = '$new_members_phone',
		phone_update_created_at = NOW()
		") or die(mysqli_error($con));

	return $query;
}
/*JULY 24 2024 CODE ENDS HERE*/






/*AUGUST 2024 CODE STARTS HERE*/
function query_defaulted_account_individual($user_id, $count=false){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM user_account LEFT JOIN  thrift_transaction_details ON thrift_transaction_details.user_account_id = user_account.user_account_id WHERE user_account.user_id = '$user_id' AND thrift_transaction_details.thrift_fine >0 AND thrift_transaction_details.txn_status != 'hold'");

	return $count ? mysqli_num_rows($query) : $query;
}

function query_log_bulk_defaults_clearance($user_id,$no_of_defaulted_account,$payment_with_charges,$payment_with_out_charges,$balance_before_cleared,$balance_after_cleared){
	global $con;

	$query = mysqli_query($con,"INSERT into bulk_defaulted_account_clearing SET 
		user_id = '$user_id',
		no_of_defaulted_account = '$no_of_defaulted_account',
		payment_with_charges = '$payment_with_charges',
		payment_with_out_charges = '$payment_with_out_charges',
		balance_before_cleared = '$balance_before_cleared',
		balance_after_cleared = '$balance_after_cleared',
		bulk_default_created_at = NOW()
	") or die(mysqli_error($con));

	return $query;
}
/*AUGUST 2024 CODE STARTS HERE*/






/*SEPT 2024 CODE HERE*/
function weekly_last_thrift_exist_on_backup($user_account_id, $current_weeks, $thrift_txn_id){

	global $con;

	$today = strtotime("today");

	$date = date("Y-m-d", $today);

	$query =  mysqli_query($con, "SELECT * FROM user_weekly_thrift WHERE user_account_id ='$user_account_id' AND user_thrift_current_weeks = '$current_weeks' AND thrift_txn_id = '$thrift_txn_id'");

	$count = mysqli_num_rows($query);

	return $count;

}







/*CODE TO CLEAR AUG 31ST & SEPT 7 DEFAULTS HERE*/
function clear_aug31st_defaults_without_charges($user_id, $count=false){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM debts_log LEFT JOIN thrift_transaction_details ON thrift_transaction_details.user_account_id = debts_log.user_account_id WHERE debts_log.user_id = '$user_id' AND thrift_transaction_details.txn_status != 'hold' AND debts_log.status_updated = 'no' GROUP by debts_log.debts_txn_id");

	return $count ? mysqli_num_rows($query) : $query;
}

function clear_sept7th_defaults_without_charges($user_id, $count=false){

	global $con;

	$query = mysqli_query($con, "SELECT * FROM debts_log2 LEFT JOIN thrift_transaction_details ON thrift_transaction_details.user_account_id = debts_log2.user_account_id WHERE debts_log2.user_id = '$user_id' AND thrift_transaction_details.txn_status != 'hold' AND debts_log2.status_updated = 'no' GROUP by debts_log2.debts_txn_id");

	return $count ? mysqli_num_rows($query) : $query;
}
/*CODE TO CLEAR AUG 31ST & SEPT 7 DEFAULTS HERE*/





////SEPT 25 2024
function check_phone_exist_with_acct_active($phone, $type=false){
	global $con;

	$query = mysqli_query($con,"SELECT * FROM user WHERE phone_no = '$phone' AND user_reg_fee = 'paid' AND can_login = 'yes'");
	return $query;
}

function virtual_check_sessionid_exist($session_id,$tx_ref_customer_id){
	global $con;

	$query = mysqli_query($con,"SELECT * FROM webhook_event_transaction WHERE flw_ref = '$session_id' AND tx_ref = '$tx_ref_customer_id'");
	return $query;
}

function check_session_id_wallet_transaction($user_id,$session_id){
	global $con;

	$query = mysqli_query($con,"SELECT * FROM wallet_transaction_details WHERE user_id = '$user_id' AND wallet_payment_txn_id = 'manually' AND wallet_session_id = '$session_id'");
	return $query;
}

function admin_check_virtual_with_account_no($virtual_account_no){
	global $con;

	$query = mysqli_query($con,"SELECT * FROM user_virtual_account WHERE user_vt_acct_account_number = '$virtual_account_no'");
	return $query;
}
/*SEPT 2024 CODE ENDS HERE*/








/*NOV 2024 CODE STARTS HERE*/
function admin_debit_members_wallet($user_id, $user_account_id, $wallet_txn_ref, $wallet_payment_txn_id, $wallet_payment_amount, $wallet_txn_status, $wallet_funded_by,$wallet_payment_type){ 
	
	global $con;

	$query = mysqli_query($con, "INSERT into wallet_transaction_details SET
		user_id = '$user_id',
		user_account_id = '$user_account_id',
		wallet_txn_ref = '$wallet_txn_ref',
		wallet_payment_txn_id = '$wallet_payment_txn_id',
		wallet_payment_amount = '$wallet_payment_amount',
		wallet_txn_status = '$wallet_txn_status',
		wallet_funded_by = '$wallet_funded_by',
		wallet_payment_type = '$wallet_payment_type',
		wallet_txn_created_at = NOW()
	") or die(mysqli_error($con));


	$update_user_id = mysqli_query($con, "UPDATE user_account SET user_account_wallet_amount = user_account_wallet_amount - $wallet_payment_amount  WHERE user_account_id = '$user_account_id' ") or die(mysqli_error($con));
	
	return $query;
}
/*NOV 2024 CODE ENDS   HERE*/
?>