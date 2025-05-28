
<?php include "sidebar.php" ?>

<?php
include("../includes/call-api.php"); 
	$query_new_user = @query_user_details($session_logged_in_user_id);
	////iit is returning the mysqli_fetch_Array
	$new_user_phone_number = @$query_new_user['phone_no'];
		if (isset($_POST['upload'])) {
		
		$output = "";

		$check_existence = user_check_payment_proof_existence($session_logged_in_user_id);
		if (mysqli_num_rows($check_existence)>0) {
			$output = '<div class="alert alert-danger">
					<span>Oops!!, you can not upload another payment proof while the first payment proof uploaded for fund wallet still pending approval.</span>
						</div>';
		} 
		else{
			$tmpname = $_FILES['proof']['tmp_name'];
			$filename = $_FILES['proof']['name'];
			$foldername = 'proof/' ;
			$joinfile = $foldername .uniqid(). $filename;

			$fileType = pathinfo($joinfile, PATHINFO_EXTENSION); 
                 
                // Allow certain file formats 
               $allowTypes = array('jpg', 'png', 'jpeg', 'JPEG', 'PNG', 'JPG'); 

               if(in_array($fileType, $allowTypes)){ 
			$movefile = move_uploaded_file($tmpname, $joinfile);	

			if ($movefile) {
					$query_payment_proof = create_payment_proof($session_logged_in_user_id, $session_logged_in_user_account_id, $joinfile, "fund wallet");

					if ($query_payment_proof) {
			////send sms starts here 
			$sms_message = "Dear ".ucwords($user_fullname).", the wallet proof of payment screenshot you uploaded manually has been received and is pending approval. it would be reviewed and approved within 24-48hours."; 
			@noblemerry_send_sms($user_phone_no,$sms_message);  
			////send sms ends here
							$output = '<div class="alert alert-success">
								<span>Proof submitted successfully. Admin will review within 24-48 hours and your account will be funded automatically.</span>
						</div>';
						header("refresh:3, url=fund-wallet");
					}else{
						$output = '<div class="alert alert-danger">
								<span>Something went wrong try again later.</span>
						</div>';
					}
			}
			}
			else{
				$output = '<div class="alert alert-danger">
								<span>Oops!!. File format error. Allowed file type for image are JPG, JPEG, PNG</span>
						</div>';
			}
		}
	}
?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-container pt-2" style="left: 0!important; width: 100%;" >
		
	<div class="main-content-body pt-5 mt-5">
		<h3 class="text-md-bold mb-3 text-center">Upload screenshot of Payment proof</h3>

		<div class="mt-5 investment-details-wrapper py-5">

			<?php

				if (isset($_POST['upload'])) {
						echo $output;
				}

			?>
			<div class="d-flex justify-content-center mb-3">
				<img style="width:200px" src="" id="preview">
			</div>


			<?php 
			/////check if user already uploaded proof of payment and restrict reuploading again
	$query_reg_payment_proof = mysqli_query($con,"SELECT * FROM payment_proof WHERE user_id = '$session_logged_in_user_id' AND payment_type = 'fund wallet' AND payment_status = 'unpaid'");
	$count_reg_payment_proof = mysqli_num_rows($query_reg_payment_proof);
			if ($count_reg_payment_proof > 0 && !isset($_POST['upload'])) {
			?> 
			<div class="alert alert-danger mt-0">
				Fund Wallet payment awaiting confirmation. Confirmation takes up to 24-48hrs for manual payment proof uploaded. <br><br>Please check back later as you can not upload another payment proof at the moment until your last payment proof uploaded is confirmed by the administrator.
				<br><br>
				<strong>N.B:</strong> Fake proof of payment will be declined and your account will be suspended.

			</div>
			<?php
			}
			else{
			?>

			<form class="d-flex flex-col space-mobile" action="" enctype="multipart/form-data" method="POST"  style="gap:40px;">
			
			<div class="form-inline row align-items-center">
				<div class="col-sm-5">
					<label class="text-md">Choose payment screenshot:</label>
				</div>
				<div class="col-sm-7">
					<input type="file" id="image" required class="form-control input" name="proof">
				</div>
			</div>

			<div class="d-flex justify-content-center">
				<button class="btn btn-main" name="upload">Upload</button>
			</div>
			</form>
			<?php
			}
			?>



		</div>

	</div>

</div>

</div>

<script>
	
	image.addEventListener("change", function(){

          let file = this.files[0];

          let reader = new FileReader();

          reader.readAsDataURL(file);

          reader.onload = function() {
               document.querySelector("#preview").src = reader.result
          }
      })

</script>
</body>
</html>