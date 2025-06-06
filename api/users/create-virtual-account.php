<?php include "sidebar.php";
include '../includes/call-api.php';
?>
 <?php
 $query_for_virtual = get_single_user_only_primary_account($session_logged_in_user_id);
 if (mysqli_num_rows($query_for_virtual)<=0) {
 	echo "not exist";
 }
 else{
 	$fetch_for_virtual = mysqli_fetch_array($query_for_virtual);
 	$firstname_after_space = explode(" ",$fetch_for_virtual['fullname']);
 	$virtual_firstname = ucfirst($firstname_after_space[0]); 
 	$virtual_lastname = ucfirst($firstname_after_space[1]); 
 	$auto_mail_generate = str_replace(" ", "", strtolower($fetch_for_virtual['fullname']));
 	$auto_mail_generate_two = $auto_mail_generate.rand(11111,99999).'@destinypromoterscooperative.com';
 	$virtual_email = (empty($fetch_for_virtual['email_address']) ? $auto_mail_generate_two : $fetch_for_virtual['email_address']); 
 	$virtual_email = str_replace(",","",$virtual_email);
 	$virtual_phone = $fetch_for_virtual['phone_no'];                  
 	$virtual_user_id = $fetch_for_virtual['user_id']; //this is the primary id for table user


 	///get primary account info
 	$balance = $fetch_for_virtual['user_account_wallet_amount']; 
	$primary_account_account_id = $fetch_for_virtual['user_account_id']; 




 } 
 ?>
<?php
		if (isset($_POST['proceed'])) { 
			$output = "";
			///this narration is the name it gonna show
			$virtual_narration = "DPCMS ".$virtual_firstname.' '.$virtual_lastname;
			$account_type_selection = mysqli_real_escape_string($con,$_POST['account_type_selection']);
			$bvn_number = mysqli_real_escape_string($con,addslashes($_POST['bvn_number']));

			$extract_phone = substr($virtual_phone, 7);
			$tx_ref = "NMPVA_".$extract_phone.'_'.$virtual_user_id.'_'.time(); ///Destiny Promoters temporary virtual account is wat is means
			$is_permanent = (empty($bvn_number) ? false : true);
			

			if ($balance < 500) {
				$output = "<div class='alert alert-danger'> 
							Your Primary Account wallet balance is insufficient to create the Virtual Account Number. You need to have up to ₦500 to create a virtual account.
							</div>";
			} 
			else{ 
			///check if user never create account before

				$check_existence = query_check_user_virtual_account_existence($session_logged_in_user_id);
				if (mysqli_num_rows($check_existence)>0) {
				$output = "<div class='alert alert-danger'>               
							You have an existing Virtual Account Number generated for you before. <a href='manage-virtual-account' class='btn btn-success btn-sm'>View Account</a>
							</div>";
				}  
				else{
						//////the email address not empty

											//$output = "eee reach here2";
			$Create_Virtual_Acct_Response = create_virtual_account_number($bvn_number,$virtual_email,$tx_ref,$virtual_phone,$virtual_firstname,$virtual_lastname,$virtual_narration,$is_permanent);
			$output = $Create_Virtual_Acct_Response; 

			//$output = $Create_Virtual_Acct_Response['message'];
			//$output = $Create_Virtual_Acct_Response['status'];



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

    			///remove 200naira from the user primary account wallet
    			update_user_balance($balance - 500, $primary_account_account_id, $con);

    						////send sms starts here
			$sms_message = "Dear ".ucwords($user_fullname).", your permanent virtual account number has been created successfully. Please login to your dashboard to check your virtual account and starting funding your wallet";
			@noblemerry_send_sms($user_phone_no,$sms_message); 
			////send sms ends here

					$output = "<div class='alert alert-success'>     
										Virtual Account Number Created Successfully
										</div>";

					header("refresh:2, url=manage-virtual-account");

        		}
        		else if ($Create_Virtual_Acct_Response['status']=='error' && $Create_Virtual_Acct_Response['message']=='Invalid bvn' || $Create_Virtual_Acct_Response['message']=='error :Invalid BVN provided') { 
        			$output = "<div class='alert alert-danger'>     
										You entered an Invalid BVN Number. Please use a valid BVN
										</div>";
        		}
        		else if ($Create_Virtual_Acct_Response['status']=='error' && $Create_Virtual_Acct_Response['message']=='BVN must be 11 digits long') { 
        			$output = "<div class='alert alert-danger'>     
										BVN must be 11 digits long. Please use a valid BVN
										</div>";
        		}

					


        	}
        }
			
		}
?>

<div class="main-container">
	<?php include "header.php" ?>


	<div class="main-content-body pt-5"><br>
		<h3 class="text-md-bold mb-3">Create Virtual Account Number <?php //echo $auto_mail_generate_two; ?></h3>
		<div class='alert alert-danger'>
					Virtual accounts are generated account details (account number and bank) that allow Destiny Promoters members to transfer funds to their primary account via bank transfer and automatically get their wallet funded. <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModalLong">Read More</a>
				</div>

		<div class="mt-5 investment-details-wrapper py-5">

			<?php

				if (isset($_POST['proceed'])) {
					print_r($output); 
					//echo var_export($is_permanent); ///to print if true or false instead of 1 or 2
					//echo gettype(1234);
				}

			?>
			<p align="center" style="color: #CE0016;">Creating a virtual account doesn't require a BVN anymore!!</p>
			
			<form class="d-flex flex-col space-mobile" method="POST"  style="gap:40px;">
			<div class="form-inline row align-items-center">
				<div class="col-sm-4">
					<label class="text-md">Account Type:</label>
				</div>
				<div class="col-sm-8">
					<select class="form-control account_type_selection" name="account_type_selection" required>
						<option value="">Select Account Type</option>
						<option value="temporary" disabled>Temporary Virtual Account</option>
						<option value="permanent">Permanent Virtual Account</option>
					</select>
				</div>
			</div>


			<div class="form-inline row align-items-center div_display_bvn_field">
				<div class="col-sm-4">
					<label class="text-md">BVN Number:</label>
				</div>
				<div class="col-sm-8">
					<small style="color: red;"><b>NB:</b> We only use your BVN to process static virtual account</small> 
					<input type="password" name="bvn_number" class="form-control input" placeholder="enter your 11 digit BVN Number" maxlength="11" value="22250169522" readonly oninput="javascript:if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength)" required>
				</div>
			</div>
			


			<div class="d-flex justify-content-center">
				<button class="btn btn-main proceed" name="proceed">Proceed</button>
			</div>
		</form>
		
		</div>

	</div>

</div>

</body>
</html>


<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Virtual accounts</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<p>Virtual accounts are either dynamic (temporary) or static (permanent). A temporary account number expires after an hour of its creation. In contrast, a static account number doesn't expire because it will be connected with your <b>BVN</b> and attached to Destiny Promoters only.</p>

      	<p><b>NB:</b> If you're creating a static virtual account, your valid <b>BVN</b> is also required as it will be a permanent account and doesn't expires unlike the temporary account where you do not need your <b>BVN</b> but expires 1 hour later after creating it.</p>

      	<p><b>NB: You'll be charged ₦500 for creating a virtual account (temporary or permanent account). You're here by advised to fund your primary account wallet as the virtual account will be generated instantly.</b></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="../js/jquery.min.js"></script>
<script>
	$(document).ready(function(e){
		
		$(".div_display_bvn_field").hide();
/*		$(".account_type_selection").change(function(e){
			//var class_todiv = "form-inline row align-items-center div_display_bvn_field";
			if ($(this).val() == 'permanent') {
				//alert($(this).val());
				//show the bvn form field
				$(".div_display_bvn_field").show();
			}
			else{
				//hide the bvn form field
				$(".div_display_bvn_field").hide();
				//alert("permanent not selected");
			}
		});*/

	});
</script>