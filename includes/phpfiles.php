<?php
include 'session.php';
require "config.php";
include "db-functions.php";
include "call-api.php";

/* This Code is for Bank Account Name Verification*/

if ( isset($_POST['accountno']) && !empty($_POST['accountno']) && isset($_POST['bankcode']) && !empty($_POST['bankcode'])) {

		$accountno = mysqli_real_escape_string($con,$_POST['accountno'] );

		$bankcode = mysqli_real_escape_string($con,$_POST['bankcode'] );

		$processAccount  = get_acct_name($accountno,$bankcode, $kz_pub_key);
		
		echo json_encode($processAccount);

}



/////////to subscribe to newsletter mailchimp
	/*ALL MEMBERS TO SUBSCRIBE TO NEWS LETTER*/
	if (isset($_POST['mailchimp_subscribe_member_email_address']) && !empty($_POST['mailchimp_subscribe_member_email_address'])) {
		 
		 $msg = '';
    	 $email = strtolower($_POST['mailchimp_subscribe_member_email_address']);


    	         // MailChimp API credentials
    	 	//first work mailchimp details FOR WALEXHCY 
        /*$apiKey = '7f93538c417dd4768a1f93ba46b6af5d-us1';
        $listID = '78f8837230'; */
    	 	//first work mailchimp details FOR WALEXHCY

    	 	//THIS MAILCHIMP DETAILS IS FOR MAIN Destiny Promoters 
        $apiKey = '56b2b66a292fdf73c83b6282e25964c1-us10';
        $listID = 'f29091817d';   
        //THIS MAILCHIMP DETAILS IS FOR MAIN Destiny Promoters 

        
        // MailChimp API URL 
        $memberID = md5(strtolower($email));
        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1); 
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;
        
        // member information
        $json = json_encode([
            'email_address' => $email,
            'status'        => 'pending',
            'merge_fields'  => [
                'FNAME'     => '',
                'LNAME'     => ''
            ]
        ]);
        
        // send a HTTP POST request with curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch); 
        
        // store the status message based on response code
        if ($httpCode == 200) {
            echo "newsletter_successfully_subscribed";
        } else {
            switch ($httpCode) {
                case 214: 
                    $msg = 'already_subscribed';
                    break;
                default:
                    $msg = 'problem_encountered_try_later';
                    break;
            }
            echo $msg;
        }
    

    	 
	}
	/*ALL MEMBERS TO SUBSCRIBE TO NEWS LETTER*/







    /*TO CREATE A WALLET TO WALLET TRANSFER PIN AND SMS OTP*/
    if (isset($_POST['set_wallet_transfer_pin']) && !empty($_POST['set_wallet_transfer_pin']) && isset($_POST['set_wallet_transfer_token']) && !empty($_POST['set_wallet_transfer_token']) && isset($_POST['to_use_transfer_sms_otp']) && !empty($_POST['to_use_transfer_sms_otp'])) 
    {
        $set_wallet_transfer_pin = mysqli_real_escape_string($con,$_POST['set_wallet_transfer_pin']);
    $set_wallet_transfer_token = mysqli_real_escape_string($con,$_POST['set_wallet_transfer_token']);
        $to_use_transfer_sms_otp = mysqli_real_escape_string($con,$_POST['to_use_transfer_sms_otp']);

        /////check for the user that owns the md5
        $query_user = mysqli_query($con,"SELECT * FROM user WHERE md5(user_id) = '$set_wallet_transfer_token' AND can_login = 'yes'");
        if (mysqli_num_rows($query_user)<=0) {
            echo "account_not_exist";
        }
        else{
            $fetch_users = mysqli_fetch_array($query_user);
            extract($fetch_users);
            ///now check if the otp code sent to the user number correct
            $query_sms_otp = mysqli_query($con,"SELECT * FROM user WHERE wallet_transfer_sms_otp = '$to_use_transfer_sms_otp'");
            if (mysqli_num_rows($query_sms_otp)<=0) {
                echo "user_sms_otp_not_valid";
            }
            else{
                ////continue since sms otp correct and valid
                ///now create the PIN
                $query_create_pin = create_wallet_transfer_pin($user_id,$set_wallet_transfer_pin);

                if ($query_create_pin) {
                ////send sms starts here
                $sms_message = "Dear ".ucwords($fullname).", your wallet transfer PIN has been created successfully. Please DO NOT SHARE with anyone and Destiny Promoters will never ask for your wallet transfer PIN. Thanks for choosing Destiny Promoters !!";
                @noblemerry_send_sms($phone_no,$sms_message); 
                ////send sms ends here
                echo "wallet_pin_success";
                }
                else{
                    echo "wallet_pin_error";
                }

        }
        //echo $set_wallet_transfer_token;
    }
}
    /*TO CREATE A WALLET TO WALLET TRANSFER PIN AND SMS OTP*/






    /*JAN 16 2023*/
    /*TO CREATE WALLET TO WALLET REAL TRANSFER OF FUNDS STARTS HERE*/
    if (isset($_POST['w2w_select_type_of_transfer']) && !empty($_POST['w2w_select_type_of_transfer']) && isset($_POST['self_w2w_select_acct_to_debit']) && !empty($_POST['self_w2w_select_acct_to_debit']) && isset($_POST['wallet_amt_to_transfer']) && !empty($_POST['wallet_amt_to_transfer']) && isset($_POST['self_w2w_select_acct_to_credit']) && !empty($_POST['self_w2w_select_acct_to_credit']) && isset($_POST['user_token']) && !empty($_POST['user_token'])) {
        
        /////now process the post method
        $w2w_select_type_of_transfer = mysqli_real_escape_string($con,$_POST['w2w_select_type_of_transfer']);
        $self_w2w_select_acct_to_debit = mysqli_real_escape_string($con,$_POST['self_w2w_select_acct_to_debit']);
        $wallet_amt_to_transfer = mysqli_real_escape_string($con,$_POST['wallet_amt_to_transfer']);
        $self_w2w_select_acct_to_credit = mysqli_real_escape_string($con,$_POST['self_w2w_select_acct_to_credit']);
        $user_token = mysqli_real_escape_string($con,$_POST['user_token']);

        if ($w2w_select_type_of_transfer=='self') {
            ///process all codes for self transfer here.
            $query_output = query_single_user_account_with_id($self_w2w_select_acct_to_debit,$user_token);
            if (mysqli_num_rows($query_output)==1) {
                $fetch_for_wallet = mysqli_fetch_array($query_output);
            }

            echo "okay na";
        }
        else{
            echo "not self";
        }

        

    }
    /*TO CREATE WALLET TO WALLET REAL TRANSFER OF FUNDS ENDS HERE*/

