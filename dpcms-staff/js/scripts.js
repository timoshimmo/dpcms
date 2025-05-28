$(document).ready(function(){

/*to close all modal*/
$(".btn-modal-closeout").click(function(){
	$(".modal").modal("hide");
});


/*JS TO VIEW MODAL IN PAYMENT PROOF*/

$("table").on("click","button.btn_click_show_modal",function(){
//alert("hey");
var payment_proof_id = $(this).attr("id");
//alert(payment_proof_id);


	if (payment_proof_id=='') {
		alert("Payment proof can not be fetched at the moment for this user. try again please");
	}
	else{
		$.ajax({
			type: "POST",
			url: "./includes/phpfiles.php",
			data: {get_payment_proof_screenshot:payment_proof_id},
			cache: false,
			beforeSend: function(){
			$("button.btn_click_show_modal"+payment_proof_id).html("Please wait...").attr("disabled",true);
			},
			success: function(data){
				setTimeout(function(){
				$("#myModalViewPaymentProof").modal("show");
				$(".img-show-payment-proof").attr("src","");
				$(".img-show-payment-proof").attr("src",data);
				$("button.btn_click_show_modal"+payment_proof_id).html("View Screenshot").removeAttr("disabled",true).attr("id",payment_proof_id);
				},500);

			}
		});
	}
});


/*JS TO VIEW MODAL IN PAYMENT PROOF*/







///CONFIRM PAYMENT JS STARTS HERE
///CONFIRM PAYMENT JS STARTS HERE


///CONFIRM PAYMENT JS STARTS HERE
$("table").on("click","button.btn_confirm_payment",function(){
 var el = $(this);
 var confirm_p_user_id = $(this).attr("confirm_p_user_id");
 var confirm_p_proof_id = $(this).attr("confirm_p_proof_id");

 if (confirm("You are about to confirm this Payment proof. \n \n Click OK to Proceed")) {
 	//////do nothing
 	$.ajax({
 		type: "POST",
		url: "./includes/phpfiles.php",
 		data: {confirm_p_user_id,confirm_p_proof_id},
 		cache: false,
 		beforeSend: function(){
 			el.html("Processing...").attr("disabled",true);
 		},
 		success: function(data){
 			var data = data.trim();
 			if (data=='virtual_account_already_exist') {
 				swal("Virtual Account Exist","A Virtual Account Number has already been generated for this user. The system can not confirm or process the payment proof again. Please contact the developer on this","warning");
 				el.html("Confirm Payment").removeAttr("disabled",true);
 			}
 			else if (data=="payment_proof_confirmed_successfully") {
 				swal("Payment Confirmed Successfully","A virtual account number has been created for this user as the payment proof was also confirmed.","success");
 				 setTimeout(function(){
				  el.html("Confirmed").removeAttr("disabled",true);
				  el.removeClass("btn_confirm_payment");
				  $("#btn_decline_payment"+confirm_p_proof_id).hide();
				  $("#btn_delete_payment_proof"+confirm_p_proof_id).hide();
				  },2000);
 			}
 			else if (data=="error_saving_confirming_payment") {
 				swal("An error occurred","Please try again later as the system can not saved any data to database","error");
 				el.html("Confirm Payment").removeAttr("disabled",true);
 			}
 			else if (data=="invalid_bvn_number") {
 				swal("Invalid BVN Number","The BVN number to process the virtual account the system is about to create for this user is Invalid. Please try again later or contact the developer","error");
 				el.html("Confirm Payment").removeAttr("disabled",true);
 			}
 			else if (data=="bvn_11_digit_long") {
 				swal("Invalid BVN Number","BVN must be 11 digits long. Please use a valid BVN","error");
 				el.html("Confirm Payment").removeAttr("disabled",true); 
 			}
 			else if (data=="error_processing_request") {
 				swal("Error Processing Request","The system can not generate a virtual account for this user at the moment due to the server error. Please try again after 30-50 mins","warning");
 				el.html("Confirm Payment").removeAttr("disabled",true);
 			}
 			else if (data=="invalid_email_address") {
 				swal("Invalid Email Address","The Email Address to process the virtual account the system is about to create for this user is an Invalid Email because it was generated from the user full name. Please look into the user full name as some symbols are not allowed","error");
 				el.html("Confirm Payment").removeAttr("disabled",true);
 			} 
 			else if (data=="server_no_respond") {
 				swal("Server Not Responding","The system can not connect to the API and no incoming response. TRY LATER","warning");
 				el.html("Confirm Payment").removeAttr("disabled",true);
 			}
 			else{
 				swal("Oops!!, Something went wrong","An unknown error occured, the system can not detect what\'s happening. Please try to upgrade the server","error");
 				el.html("Confirm Payment").removeAttr("disabled",true);
 			}
 			
 		}
 	})
 	
 }
 else{
 	////do nothing since can't confirmed
 }

}); 
///CONFIRM PAYMENT JS ENDS HERE

///CONFIRM PAYMENT JS ENDS HERE
///CONFIRM PAYMENT JS ENDS HERE



////////FUND WALLET PAYMENT PROOF STARTS HERE
////////FUND WALLET PAYMENT PROOF STARTS HERE
////////FUND WALLET PAYMENT PROOF STARTS HERE
////////FUND WALLET PAYMENT PROOF STARTS HERE
////////FUND WALLET PAYMENT PROOF STARTS HERE

$("table").on("click","button.btn_fund_wallet",function(){
 var el = $(this);
 var fundwallet_user_id = $(this).attr("fundwallet_user_id");
 var fundwallet_user_acctid = $(this).attr("fundwallet_user_acctid");
 var fundwallet_payment_proofid = $(this).attr("fundwallet_payment_proofid");
 var fundwallet_username = $(this).attr("fundwallet_username");

 el.html("Please wait...");
 setTimeout(function(){
 $("#myModalViewFundWalletPaymentProof").modal("show");
 },100);


 setTimeout(function(){
 	el.html("Fund Wallet");
 /// now load the other ids
 $(".fw_input_userid").val(fundwallet_user_id);
 $(".fw_input_user_acctid").val(fundwallet_user_acctid);
 $(".fw_input_paymentproof_id").val(fundwallet_payment_proofid);

 $(".fw_input_membername").val(fundwallet_username);
  },150);


});


////////JS for the modal once popup
$(".btn-modal-fund-wallet").click(function(e){
	e.preventDefault();
	var el = $(this);
	var fw_input_userid = $(".fw_input_userid").val();
	var fw_input_user_acctid = $(".fw_input_user_acctid").val();
	var fw_input_paymentproof_id = $(".fw_input_paymentproof_id").val();
	var fw_input_membername = $(".fw_input_membername").val();
	var fw_input_amounttofund = $(".fw_input_amounttofund").val();

	if (fw_input_amounttofund=='') {
		swal("Enter An Amount","You need to enter an amount to fund the member's account","warning");
	}
	else if (fw_input_amounttofund==0) {
		swal("Amount is Zero","You can not fund a user's wallet with zero","warning");
	} 
	else if (fw_input_userid=='' || fw_input_user_acctid=='' || fw_input_paymentproof_id=='') {
		swal("Invalid Paramters Passed","Can not process Token. Contact the Developer","error");
	}
	else if (confirm("Are you sure you want to fund the user\'s wallet ? \n\n OK to Continue")) {
		$.ajax({ 
			type: "POST",
			url: "./includes/phpfiles.php",
			data:{fw_input_userid,fw_input_user_acctid,fw_input_paymentproof_id,fw_input_amounttofund},
			cache: false, 
			beforeSend: function(){
				el.html("Please wait...").attr("disabled",true);
			},
			success: function(data){
				data = data.trim();
				if (data=='payment_proof_success') {
					$(".fw_input_amounttofund").val('');
					el.html("Fund Wallet").removeAttr("disabled",true);
				  $("#myModalViewFundWalletPaymentProof").modal("hide");

				  

					swal("Success","Wallet Account funded Successfully","success");

					setTimeout(function(){
				  $("#f_wallet_button"+fw_input_paymentproof_id).html("Funded").addClass("btn-success").removeAttr("disabled",true).removeClass("btn_fund_wallet");
				  //el.removeClass("btn_fund_wallet");
				  $("#btn_decline_payment"+fw_input_paymentproof_id).hide();
				  $("#btn_delete_payment_proof"+fw_input_paymentproof_id).hide();
				  },2000);
				}

				else if (data=='payment_proof_error') {
					swal("Error Occured","The user's account can not be funded at the moment. Please try again later and if this error persist, contact the developer","error");

					el.html("Fund Wallet").removeAttr("disabled",true);
					$(".fw_input_amounttofund").val('');
				}
			}
		});
	}
})
////////FUND WALLET PAYMENT PROOF ENDS HERE
////////FUND WALLET PAYMENT PROOF ENDS HERE
////////FUND WALLET PAYMENT PROOF ENDS HERE
////////FUND WALLET PAYMENT PROOF ENDS HERE


















/////ADD BULK ACCOUNT STARTS HERE
	$(".btnsubmit_create_account").click(function(e){
		e.preventDefault();
		el = $(this);
		var totalNumberOfAccount = $("#totalNumberOfAccount").val();
		var usertoken = $(".usertoken").val();
		var user_acct_token = $(".user_acct_token").val();
		var accountregfee = $(".accountregfee").val();
		var admin_token = $(".admin_token").val();



		if (totalNumberOfAccount=='') {
			swal("Input Number of Bulk Account","","error");
		}
		else if (totalNumberOfAccount <20) {
			swal("Bulk Account is 20 Minimum","You can only create a minimum of 20 accounts.","warning");
		}
		else if (totalNumberOfAccount >100) {
			swal("Bulk Account is 100 Max","You can only add 100 bulk account at once","warning");
		}
		else{
			$.ajax({
				type: "POST",
				url: "./includes/phpfiles.php",
				data:{totalNumberOfAccount,usertoken,user_acct_token,accountregfee,admin_token},
				cache: false,
				beforeSend: function(data){
					el.html("please wait...").attr("disabled",true);
					$("#totalNumberOfAccount").attr("disabled",true);
				},
				success: function(data){
					//alert(data);

					if (data=='insufficient_fund') {
						swal("Insufficient Funds","Insufficient Funds in the user primary account wallet to create the "+totalNumberOfAccount+" Bulk Accounts.","error");
						el.html("Add Bulk Account").removeAttr("disabled",true);
						$("#totalNumberOfAccount").removeAttr("disabled",true);
					}
					else if (data=='bulk_successfully_added'){
						swal(+totalNumberOfAccount+" Account Added Successfully","You will be redirected to assign thrift page","success");
						el.html("Add Bulk Account").removeAttr("disabled",true);
						$("#totalNumberOfAccount").removeAttr("disabled",true);
						
						setTimeout(function() {
        					window.location="admin-assign-thrift-to-bulk-account?token="+btoa(usertoken)+"";
      					 }, 5000);
					}
					else{
						swal("An unknown error occurred","Try again later and/or contact the developer if this persist","error");
					}
					
				}
			})

			
		}
		//

	});
/////ADD BULK ACCOUNT ENDS HERE



});