<?php 
	date_default_timezone_set("Africa/Lagos");
	/*this public key is for all payment for flutterwave*/
	$api_public_key = "FLWPUBK-6f0fe6d5fa8bbf2de4c5760509446d44-X";

	/*this secret key is to confirm payment transaction id*/
	$api_secret_key = "FLWSECK-ec6a23ca377c53c1f9b7951280658a0b-195ceabe495vt-X";

	/*this is for fetch acct number and mustn't be changed*/
	$kz_pub_key = 	  "FLWPUBK-6f0fe6d5fa8bbf2de4c5760509446d44-X";

	/*this is flutterwave webhook secret has to use in webhook file for virtual*/
	$flw_secret_hash_signature = "968ede24d51f2f13d5bcff9d0c0fe278774d998de3e37374466fde1b8c7be471";


	/*this is flutterwave secret key to confirm webhook payment 4 virtual account*/
	$webhook_secret_key = "FLWSECK-ec6a23ca377c53c1f9b7951280658a0b-195ceabe495vt-X";

	/*to get account starting token name e.g NBM + acct */
	$token_name = "DPC";

	$con = mysqli_connect("localhost","destfpki_dpcms","destfpki_dpcms","destfpki_dpcms");
	if (!$con) { 
		echo "Database not Connected to the server"; 
	}
	else{
		/////do nothing
	}
?>