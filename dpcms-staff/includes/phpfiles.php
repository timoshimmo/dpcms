<?php
include '../../includes/session.php';
include '../../includes/config.php';
include '../../includes/db-functions.php';
include_once '../../includes/call-api.php';

/*get the payment proof screenshot displayed on bootstrap modal*/
if (isset($_POST['get_payment_proof_screenshot']) && !empty($_POST['get_payment_proof_screenshot'])) {
  $payment_proof_id = mysqli_real_escape_string($con,$_POST['get_payment_proof_screenshot']);
  //now get the image path from there.

  $query = mysqli_query($con,"SELECT * FROM payment_proof WHERE payment_proof_id = '$payment_proof_id'");
  if (mysqli_num_rows($query)<=0) {
    echo "no payment proof found";
  }
  else{
    $fetch_proof = mysqli_fetch_array($query);
    if ($fetch_proof['payment_type']=='registration') {
      echo "../".$fetch_proof['payment_screenshot'];
    }
    else if ($fetch_proof['payment_type']=='fund wallet') {
      echo "../".$fetch_proof['payment_screenshot'];
    }
    
  }
}
/*get the payment proof screenshot displayed on bootstrap modal*/





if (isset($_GET['all_approved_payment_proof']) && !empty($_GET['all_approved_payment_proof'])) {
   // echo "hello world";
  
  $arr = array();
  $query = mysqli_query($con, "SELECT payment_type, payment_screenshot, payment_proof_id from payment_proof WHERE payment_status='paid'");
  $count_query = mysqli_num_rows($query);

  if ( $count_query > 0) {
     // echo $count_query;
    while($row = mysqli_fetch_assoc($query)) {
    $arr[] = $row;
    }
    echo json_encode($arr);
  } else{
    echo "no payment proof available";
  }


}




/*FOR ADMIN CONFIRM PAYMENT PROOF*/
if (isset($_POST['confirm_p_user_id']) && !empty($_POST['confirm_p_user_id']) && isset($_POST['confirm_p_proof_id']) && !empty($_POST['confirm_p_proof_id'])) {
  $user_id = mysqli_real_escape_string($con,$_POST['confirm_p_user_id']);
  $payment_proof_id = mysqli_real_escape_string($con,$_POST['confirm_p_proof_id']);
  $session_logged_in_user_id = $user_id; 

  $to_confirm_id = @$_SESSION['admin_id'];
        /*get the incoming user phone number to send sms*/
  $fetch_for_virtual = @query_user_details($session_logged_in_user_id);
  ////iit is returning the mysqli_fetch_Array        
  $user_fullname = $fetch_for_virtual['fullname']; 
  $new_user_phone_number = @$fetch_for_virtual['phone_no'];

  /*get the incoming user phone number to send sms*/ 

    $output = "";               
  $firstname_after_space = explode(" ",$fetch_for_virtual['fullname']);
  $virtual_firstname = ucfirst($firstname_after_space[0]); 
  $virtual_lastname = ucfirst($firstname_after_space[1]); 
  $auto_mail_generate = str_replace(" ", "", strtolower($fetch_for_virtual['fullname']));
  $auto_mail_generate_two = $auto_mail_generate.rand(11111,99999).'@noblemerrycompany.com';
  $virtual_email = (empty($fetch_for_virtual['email_address']) ? $auto_mail_generate_two : $fetch_for_virtual['email_address']);        
  $virtual_email = str_replace(",","",$virtual_email);
  $virtual_phone = $fetch_for_virtual['phone_no'];                  
  $virtual_user_id = $fetch_for_virtual['user_id']; 
  $user_fullname = $fetch_for_virtual['fullname'];

  ///this narration is the name it gonna show
  $virtual_narration = "NOBLEMERRY ".$virtual_firstname.' '.$virtual_lastname;
  $bvn_number = "22250169522";//22250169522

  $extract_phone = substr($virtual_phone, 7);
  $tx_ref = "NMPVA_".$extract_phone.'_'.$virtual_user_id.'_'.time(); ///Destiny Promoters temporary virtual account is wat is means
  $is_permanent = (empty($bvn_number) ? false : true);

  //echo $virtual_email;

    $check_existence = query_check_user_virtual_account_existence($session_logged_in_user_id);
  if (mysqli_num_rows($check_existence)>0) {
    $output = "virtual_account_already_exist";
    }  
  else{
          //$output = "eee reach here2";
      $Create_Virtual_Acct_Response = create_virtual_account_number($bvn_number,$virtual_email,$tx_ref,$virtual_phone,$virtual_firstname,$virtual_lastname,$virtual_narration,$is_permanent); 
      $output = @$Create_Virtual_Acct_Response; 

      //echo $output = $Create_Virtual_Acct_Response['message'];
      //echo $output = $Create_Virtual_Acct_Response['status'];



      ///check if created successfully and insert to db
      if ($Create_Virtual_Acct_Response['status']=='success' && $Create_Virtual_Acct_Response['message']=='Virtual account created') 
            { 
              ///get the response data
              $R_vt_acct_status = $Create_Virtual_Acct_Response['status'];
              $R_vt_acct_message = $Create_Virtual_Acct_Response['message'];
              $R_vt_acct_response_message = $Create_Virtual_Acct_Response['data']['response_message'];
              $R_vt_acct_flw = $Create_Virtual_Acct_Response['data']['flw_ref'];
              $R_vt_acct_order_ref = $Create_Virtual_Acct_Response['data']['order_ref'];
              $R_vt_acct_account_number = $Create_Virtual_Acct_Response['data']['account_number'];
              $R_vt_acct_frequency = $Create_Virtual_Acct_Response['data']['frequency'];
              $R_vt_acct_bank_name = $Create_Virtual_Acct_Response['data']['bank_name'];
              $R_vt_acct_created_at = $Create_Virtual_Acct_Response['data']['created_at'];
              $R_vt_acct_expiry_date = $Create_Virtual_Acct_Response['data']['expiry_date'];
              $R_vt_acct_note = $Create_Virtual_Acct_Response['data']['note'];
              

          $response_insert_virtual_acct_to_db = insert_virtual_account_data_created($session_logged_in_user_id,$tx_ref,$R_vt_acct_status,$R_vt_acct_message,$R_vt_acct_response_message,$R_vt_acct_flw,$R_vt_acct_order_ref,$R_vt_acct_account_number,$R_vt_acct_frequency,$R_vt_acct_bank_name,$R_vt_acct_created_at,$R_vt_acct_expiry_date,$R_vt_acct_note);         

          
          $confirm_payment = confirm_user_registration_payment($user_id);

    if($confirm_payment){ 
      ////send sms starts here
      $sms_message = "Dear ".ucwords($user_fullname).", your Destiny Promoters new account registration had just been confirmed and approved successfully. You can now login to access your dashboard";
      @noblemerry_send_sms($new_user_phone_number,$sms_message);  
      ////send sms ends here

      admin_update_reg_payment_proof($payment_proof_id, "paid");
      //////inser the id of the one that process the payment. 
      admin_insert_payment_confirmed_by($to_confirm_id,$user_id,$payment_proof_id);


      ////send sms starts here
      $sms_message = "Dear ".ucwords($user_fullname).", a permanent virtual account number has been created successfully. Please login to your dashboard to check your virtual account and starting funding your wallet easily.";
      @noblemerry_send_sms($virtual_phone,$sms_message); 
      ////send sms ends here
      $output = "payment_proof_confirmed_successfully";
    }else{
      $output = "error_saving_confirming_payment";
    }
    
    }
            else if ($Create_Virtual_Acct_Response['status']=='error' && $Create_Virtual_Acct_Response['message']=='Invalid bvn' || $Create_Virtual_Acct_Response['message']=='error :Invalid BVN provided') { 
              $output = "invalid_bvn_number";
            }
            else if ($Create_Virtual_Acct_Response['status']=='error' && $Create_Virtual_Acct_Response['message']=='BVN must be 11 digits long') { 
              $output = "bvn_11_digit_long";
            }
            else if ($Create_Virtual_Acct_Response['status']=='error' && $Create_Virtual_Acct_Response['message']=='error processing request, please try again') { 
              $output = "error_processing_request";
            }
            else if ($Create_Virtual_Acct_Response['status']=='error' && $Create_Virtual_Acct_Response['message']=='Validation error: Invalid email address.') { 
              $output = "invalid_email_address";
            }
            else{
              $output = "server_no_respond";
            }
          }
          echo trim($output);
}
/*FOR ADMIN CONFIRM PAYMENT PROOF*/







/*FOR ADMIN PAYMENT PROOF FUND WALLET*/
/*FOR ADMIN PAYMENT PROOF FUND WALLET*/
if (isset($_POST['fw_input_userid']) && !empty($_POST['fw_input_userid']) && isset($_POST['fw_input_user_acctid']) && !empty($_POST['fw_input_user_acctid']) && isset($_POST['fw_input_paymentproof_id']) && !empty($_POST['fw_input_paymentproof_id']) && isset($_POST['fw_input_amounttofund']) && !empty($_POST['fw_input_amounttofund'])) {
  
  ///post the post method here and assign to variable
  $output = "";
  $wallet_txn_ref = 'py_ref-'.time(); 
  $to_confirm_id = @$_SESSION['admin_id'];
  $user_id = mysqli_real_escape_string($con,$_POST['fw_input_userid']);
  $user_post_account_id = mysqli_real_escape_string($con,$_POST['fw_input_user_acctid']);
  $payment_proof_id = mysqli_real_escape_string($con,$_POST['fw_input_paymentproof_id']);
  $wallet_payment_amount = mysqli_real_escape_string($con,$_POST['fw_input_amounttofund']);

  $query_user_all_account = query_user_all_account($user_id);
  $fetch_all_account = mysqli_fetch_array($query_user_all_account);
  $wallet_user_account_id = $fetch_all_account['user_account_id'];


  ////////now process the wallet funding for the user
  $fund_wallet_query =  fund_wallet_creation($user_id, $wallet_user_account_id, $wallet_txn_ref, "manually", $wallet_payment_amount,"successful");

    /*get the incoming user phone number to send sms*/
  $query_new_user = @query_user_details($user_id);
  ////iit is returning the mysqli_fetch_Array        
  $user_fullname = $query_new_user['fullname']; 
  $new_user_phone_number = @$query_new_user['phone_no'];
  /*get the incoming user phone number to send sms*/

    if ($fund_wallet_query) {
      ////send sms starts here
      $sms_message = "Dear ".ucwords($user_fullname).", your Destiny Promoters account wallet has been credited with N".number_format($wallet_payment_amount).". Please access your dashboard to confirm.";
      @noblemerry_send_sms($new_user_phone_number,$sms_message);  
      ////send sms ends here

            //////inser the id of the one that process the payment. 
      admin_insert_payment_confirmed_by($to_confirm_id,$user_id,$payment_proof_id);


      ////now update the payment proof id for the admin
      @admin_update_payment_proof($payment_proof_id);

    $output = "payment_proof_success";
  }
  else{
    $output = "payment_proof_error";
  }




  echo $output;
}
/*FOR ADMIN PAYMENT PROOF FUND WALLET*/
/*FOR ADMIN PAYMENT PROOF FUND WALLET*/





/*FOR ADMIN ADD BULK ACCOUNT */
/*FOR ADMIN ADD BULK ACCOUNT */
/*FOR ADMIN ADD BULK ACCOUNT */
if (isset($_POST['totalNumberOfAccount']) && !empty($_POST['totalNumberOfAccount']) && isset($_POST['usertoken']) && !empty($_POST['usertoken']) && isset($_POST['user_acct_token']) && !empty($_POST['user_acct_token']) && isset($_POST['accountregfee']) && !empty($_POST['accountregfee']) && isset($_POST['admin_token']) && !empty($_POST['admin_token'])) {
    
    $count = "";
    $total_account = mysqli_real_escape_string($con,$_POST['totalNumberOfAccount']);
    $user_id = mysqli_real_escape_string($con,$_POST['usertoken']);
    $user_account_id = mysqli_real_escape_string($con,$_POST['user_acct_token']);
    $accountregfee = mysqli_real_escape_string($con,$_POST['accountregfee']);
    $sess_id = mysqli_real_escape_string($con,$_POST['admin_token']);


    $total_wallet_amount_needed = ($total_account * $accountregfee);
    $account_balance = get_user_account_details($user_account_id, 'user_account_wallet_amount');
    $new_wallet_balance = $account_balance - $total_wallet_amount_needed;

    if ($new_wallet_balance < 0) {
      $output = "insufficient_fund";
    }else{
      $start = 1;
      /*echo strtotime("now") . "<br>";*/
      while($start <= $total_account){ 
        
        $fetch_last_account_id = mysqli_fetch_array(query_last_account_id());
        $last_account_id = $fetch_last_account_id['user_account_id'];

        $user_account_token = 'NBM'.random_numbers(13);
        
        $generate_user_refferal_id = 'RF'.random_strings(15);

        $query_exist_user_data =  mysqli_query($con, "SELECT * FROM user_account WHERE user_id='$user_id' ORDER BY user_account_id DESC");
        $fetch_existing_data = mysqli_fetch_array($query_exist_user_data);
        $referred_referral_id = $fetch_existing_data['referral_id'];

        create_user_account($user_id,$user_account_token, $generate_user_refferal_id,$referred_referral_id, "automated");
        
        $get_current_user_account_id = mysqli_insert_id($con);

        query_counts_increment_user_referral($referred_referral_id);

        $primary_account_balance = get_user_account_details($user_account_id, 'user_account_wallet_amount') -  $accountregfee;

        if( update_user_balance($primary_account_balance, $user_account_id, $con) ){
          admin_insert_user_reg_fee($user_id, $get_current_user_account_id, $sess_id);
        }

        if(query_user_reg_within_forty_days($referred_referral_id)){
          update_users_referral_count_within_forty_days($referred_referral_id);
        }
        /*sleep(2);*/
        $start++;
      }

      /*echo strtotime("now") . "<br>";*/

     /* $output = "<div class='alert alert-success'>Account Generated successfully. 
      DO NOT REFRESH THIS PAGE. <br><p><a href='users'>CLICK HERE TO GO TO MANAGE USERS</a></p>
      </div>";*/
      $output = "bulk_successfully_added";

    }


    echo $output;

}
/*FOR ADMIN ADD BULK ACCOUNT */
/*FOR ADMIN ADD BULK ACCOUNT */
/*FOR ADMIN ADD BULK ACCOUNT */

