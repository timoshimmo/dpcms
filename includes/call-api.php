<?php
/*ALL PUBKEY AND SECKEY HERE ARE MEANT FOR PYRAMY*/


function getbank_name(){
		$curl = curl_init();
		$base_url = "https://ravesandboxapi.flutterwave.com/banks";
		$header = array(
		  	"Content-Type: application/json",
		);
		$query = "?country=NG";//pss NG, GH, KE

		curl_setopt_array($curl, array(
		  	CURLOPT_URL => $base_url . $query,
		  	CURLOPT_CUSTOMREQUEST => "GET",
		  	CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_SSL_VERIFYPEER => false, 
		  	CURLOPT_MAXREDIRS => 10,
		  	CURLOPT_TIMEOUT => 180,
		  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  	CURLOPT_HTTPHEADER => $header,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl); 

		if ($err) {
		  	echo "cURL Error #:" . $err;
		} else {
		  	$decodedResponse = json_decode($response, true);
		  	$banks = $decodedResponse['data'];
		}

		//print_r($banks);
		return $banks;
/*for ($i=0; $i <34 ; $i++) { 
	
		echo "<pre>";
print_r($banks[$i]['name']);
print_r("-");
print_r($banks[$i]['code']);
		echo "</pre>";
}*/
		/*$cnt = 1;
		foreach ($banks as $bank) {
			echo $bank['name'].' - '.$bank['code']."<br/>";
		}*/

}



function get_acct_name($accountno,$bankcode,$api_public_key){

		
			$curl = curl_init();
		$base_url = "https://api.ravepay.co/flwv3-pug/getpaidx/api/resolve_account";

		curl_setopt_array($curl, array(
  CURLOPT_URL => $base_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'recipientaccount'       => $accountno,
   'destbankcode'             => $bankcode,
   'PBFPubKey' => $api_public_key
  ]),
  CURLOPT_HTTPHEADER => [
    "content-type: application/json",
    "cache-control: no-cache"
  ],
));

$response = curl_exec($curl);
$err = curl_error($curl);

if($err){
  // there was an error contacting the rave API
  die('Curl returned error: ' . $err);
}

$result = json_decode($response,true);

return $result;
}








function create_virtual_account_number($bvn_number,$email,$tx_ref,$phone,$firstname,$lastname,$narration,$is_permanent){
 global $webhook_secret_key;
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-account-numbers",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode([
    'amount' => '', 
    'email' => $email,
    'is_permanent' => $is_permanent,  
    'bvn' => $bvn_number, 
    'frequency' => '',
    'tx_ref' => $tx_ref,
    'phonenumber' => $phone,  
    'firstname' => $firstname,
    'lastname' => $lastname, 
    'narration' => $narration
]),

  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer FLWSECK-ec6a23ca377c53c1f9b7951280658a0b-195ceabe495vt-X'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$result = json_decode($response,true);

return $result;

}








function insert_virtual_account_data_created($user_id,$virtual_account_tx_ref,$R_vt_acct_status,$R_vt_acct_message,$R_vt_acct_response_message,$R_vt_acct_flw,$R_vt_acct_order_ref,$R_vt_acct_account_number,$R_vt_acct_frequency,$R_vt_acct_bank_name,$R_vt_acct_created_at,$R_vt_acct_expiry_date,$R_vt_acct_note)
{
  global $con; 

      /////now logged to db user_virtual_account tho
      $insert_to_virtual_account = mysqli_query($con,"INSERT into user_virtual_account SET 
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

      return $insert_to_virtual_account;

}





function webhook_transaction_verification_api($transaction_id){
	global $webhook_secret_key;
	            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$transaction_id/verify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array( 
                  "Content-Type: application/json",
                  "Authorization: Bearer FLWSECK-ec6a23ca377c53c1f9b7951280658a0b-195ceabe495vt-X"
                ),
              ));
              
              $return = curl_exec($curl); 
              
              curl_close($curl);

/*echo "<pre>";
echo $return;
echo "</pre>";*/


$res = json_decode($return,true);

return $res;
}










/*october 7*/
function flutterwave_payment_transaction_verification_with_txnid($transaction_id){
  global $webhook_secret_key;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$transaction_id/verify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array( 
                  "Content-Type: application/json",
                  "Authorization: Bearer FLWSECK-ec6a23ca377c53c1f9b7951280658a0b-195ceabe495vt-X"
                ),
              ));
              
              $return = curl_exec($curl);
              
              curl_close($curl);

/*echo "<pre>";
echo $return;
echo "</pre>";*/


$res = json_decode($return,true);

return $res;
}
/*october 7*/






function noblemerry_send_sms($phone,$sms_message){

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "http://sms.careertouch.ng/api/sendsms.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST", 
  CURLOPT_POSTFIELDS => json_encode([
    'senderid'       => "DPCMS",
   'recipient'             => $phone,
   'message' => $sms_message,
   'product_id' => "dndroute"
  ]),
  CURLOPT_HTTPHEADER => [
    "Accept: /",  
    "Authorization: ebuve6ahiyukipuha5ecafini2e3asezi9emejefeduru1umawe8usige2etaqe",
    "Content-Type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  return $err;
} else {
  return $response;
}

}
/*october 11*/





/*november 5 */
function create_virtual_account_numberPYRA($bvn_number,$email,$tx_ref,$phone,$firstname,$lastname,$narration,$is_permanent){
 global $webhook_secret_key;
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-account-numbers",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode([
    'amount' => '', 
    'email' => $email,
    'is_permanent' => $is_permanent,  
    'bvn' => $bvn_number, 
    'frequency' => '',
    'tx_ref' => $tx_ref,
    'phonenumber' => $phone, 
    'firstname' => $firstname,
    'lastname' => $lastname, 
    'narration' => $narration
]),

  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer FLWSECK-9bb2184b2abcde73c577b9f6b8c28743-X'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$result = json_decode($response,true);

return $result;

}





function webhook_transaction_verification_apiPYRA($transaction_id){
  global $webhook_secret_key;
              $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$transaction_id/verify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array( 
                  "Content-Type: application/json",
                  "Authorization: Bearer FLWSECK-9bb2184b2abcde73c577b9f6b8c28743-X"
                ),
              ));
              
              $return = curl_exec($curl); 
              
              curl_close($curl);

/*echo "<pre>";
echo $return;
echo "</pre>";*/


$res = json_decode($return,true);

return $res;
}
/*november 5 */






/*DELETE VIRTUAL ACCOUNT STARTS HERE*/
function delete_virtual_account($order_ref){
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-account-numbers/".$order_ref,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode([
    'order_ref' => $order_ref, 
    'status' => 'inactive'
]),

  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer FLWSECK-ec6a23ca377c53c1f9b7951280658a0b-195ceabe495vt-X'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$result = json_decode($response,true);

return $result;

}
/*DELETE VIRTUAL ACCOUNT ENDS HERE*/






/*SEND TERMII SMS STARTS HERE DEC 6 2022*/
function termii_send_sms_for_noblemerry($phone,$sms_message){
$phone_intl = "234".$phone;
$curl = curl_init();
$data = array("api_key" => "TLZzvbjCdrxw19Heytm5dpFnVQO2vwjHjZrcq72HiRpQx2a9d0haLcapuvQquE", "to" => $phone_intl,  "from" => "Destiny Promoters",
"sms" => $sms_message,  "type" => "plain",  "channel" => "generic" );

$post_data = json_encode($data);

curl_setopt_array($curl, array(
CURLOPT_URL => "https://api.ng.termii.com/api/sms/send",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "POST",
CURLOPT_POSTFIELDS => $post_data,
CURLOPT_HTTPHEADER => array(
"Content-Type: application/json"
),
));

$response = curl_exec($curl);

curl_close($curl);

$result = json_decode($response,true);

return $result;

}
/*SEND TERMII SMS STARTS HERE DEC 6 2022*/










/*OCTOBER 9 2023*/
function real_noblemerry_create_virtual_account($bvn_number,$email,$tx_ref,$phone,$firstname,$lastname,$narration,$is_permanent){
 global $webhook_secret_key;
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-account-numbers",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode([
    'amount' => '', 
    'email' => $email,
    'is_permanent' => $is_permanent,  
    'bvn' => $bvn_number, 
    'frequency' => '',
    'tx_ref' => $tx_ref,
    'phonenumber' => $phone,  
    'firstname' => $firstname,
    'lastname' => $lastname, 
    'narration' => $narration
]),

  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer FLWSECK-ec6a23ca377c53c1f9b7951280658a0b-195ceabe495vt-X'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$result = json_decode($response,true);

return $result;

}
/*OCTOBER 9 2023*/

?>
