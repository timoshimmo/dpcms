<?php include "sidebar.php" ?>
<?php
	
	if (isset($_POST['save'])) {
		
		$output = "";
		$account_no = $_POST['account_number'];

		$bank_sort_code = $_POST['bank_sort'];

		$account_name = $_POST['account_name'];

		$query_add_details = add_user_bank_details($session_logged_in_user_id, $bank_sort_code, $account_name, $account_no);


		if ($query_add_details) {
			$output = '<div class="alert alert-success col-12 col-md-7 mt-3">
				Account Details added successfully
			</div>';
		}else{
			'<div class="alert alert-danger col-7 mt-3">
				Something went wrong. Try again later
			</div>';
		}
	}

?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">

		<h3 class="text-md-bold mb-2">Bank Details</h3>
		<h6 class="text-sm-bold text-blur">Please add your bank account details below</h6>

		<?php

			if (isset($_POST['save'])) {
		
				echo $output;
				header("refresh:2, url=manage-bank-account");
			} 

		?>
		
		<form class="d-flex flex-col mt-5"  method="POST" style="gap:40px;">
			<div class="form-inline row align-items-center">
				<div class="col-sm-2">
					<label class="text-md">Account Number:</label>
				</div>
				<div class="col-sm-7">
					<input type="number" required class="form-control input" id="accountNumber" name="account_number">
				</div>
			</div>

			<div class="form-inline row align-items-center">
				<div class="col-sm-2">
					<label class="text-md">Bank Name:</label>
				</div>
				<div class="col-sm-7" style="position:relative;">
					<select class="form-control select" required name="bank_sort" id="bankSort">
						<option value="">Select Bank Name</option>
						<?php
							$query_all_banks = fetch_all_bank_account_details();
							if( mysqli_num_rows($query_all_banks) > 0 ){

								while($fetch_bank_details = mysqli_fetch_array($query_all_banks)){
									?>
									<option value="<?php echo $fetch_bank_details['lists_banksortcode']?>"><?php echo $fetch_bank_details['lists_bankname'] ?></option>
								<?php
								}
							}

						?>

					</select>
					<i class="ri-arrow-down-s-line select-icon text-blur"></i>
				</div>
			</div>

			<div class="form-inline row align-items-center">
				<div class="col-sm-2">
					<label class="text-md">Account Name:</label>
				</div>
				<div class="col-sm-7">
					<input type="text" readonly required class="form-control input" id="accountName" name="account_name">
				</div>
			</div>

			<div class="d-flex justify-content-center">
				<button class="btn btn-main" name="save">Save & Continue</button>
			</div>
		</form>
	</div>

</div>

<script>
	
$("button").hide();
$("#bankSort").change(function(e){
 
 var sendInfo =	{
    accountno: accountNumber.value,
    bankcode: bankSort.value
  };


$.ajax({
        type: "POST",
        url: "../includes/phpfiles.php",
        cache: false,
        success: function (msg) {
        	
        	var dataResponse = JSON.parse(msg).data.data;
        	var message = dataResponse.responsemessage.trim();
        
	           if (message == "Approved Or Completed Successfully"){
	    
           		document.querySelector("#accountName").value = dataResponse.accountname;

           		$("button").show();

	           }else{
	           	document.querySelector("#accountName").value = "Invalid Account Details";
	           	$("button").hide();
	           }
        },
        data: sendInfo
    });
  
});
</script>
</body>
</html>