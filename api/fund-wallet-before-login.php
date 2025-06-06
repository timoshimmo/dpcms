<?php 
include("includes/session.php"); 
include("includes/config.php"); 
include("includes/db-functions.php"); 
?>

<?php
	if (!isset($_SESSION['session_logged_in_user_account_id']) || empty($_SESSION['session_logged_in_user_account_id'])  && !isset($_SESSION['session_logged_in_user_id']) || empty($_SESSION['session_logged_in_user_id']) ) {
	header("Location:signup");
}else{

	$session_logged_in_user_account_id = $_SESSION['session_logged_in_user_account_id'];
	$session_logged_in_user_id = $_SESSION['session_logged_in_user_id'];


	/////check if user already uploaded proof of payment and restrict reuploading again
	$query_reg_payment_proof = mysqli_query($con,"SELECT * FROM payment_proof WHERE user_id = '$session_logged_in_user_id' AND payment_type = 'registration' AND payment_status = 'unpaid'");
	$count_reg_payment_proof = mysqli_num_rows($query_reg_payment_proof);


	///////get the details of just registered n logged in user to create virtual account
	$fetch_for_virtual = query_user_details($session_logged_in_user_id);

	///////insert to create for virtual account number.
	$select_row = mysqli_query($con,"SELECT * FROM track_free_virtual_account WHERE user_id = '$session_logged_in_user_id'"); 
	if (mysqli_num_rows($select_row)>0) {
		///it exist, do not log new data yet
	}
	else{
	$insert_now = mysqli_query($con,"INSERT into track_free_virtual_account SET 
    					user_id = '$session_logged_in_user_id',
    					primary_account_id = '$session_logged_in_user_account_id',
    					created_at = NOW()
    			"); 		
	}


}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Registration Fee Payment | Destiny Promoters Cooperative</title>
 <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="users/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="users/assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="users/assets/css/media.css">
	
	<link rel="stylesheet" type="text/css" href="users/assets/icons/remixicon/remixicon.css">
	<link rel="stylesheet" href="users/assets/css/dataTables.bootstrap4.min.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
	<script src="users/assets/js/jquery-3.5.1.js"></script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="users/assets/js/jquery.dataTables.min.js"></script>
	<script src="users/assets/js/dataTables.bootstrap4.min.js"></script>
	<script defer src="users/assets/js/script.js"></script>
	<style type="text/css">
		
		.modal-body > p:last-child{display: none;}

	</style>
</head>
<body>

<button type="button" class="btn btn-primary modal-toggle" style="display: none;" data-toggle="modal" data-target="#exampleModal">
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Bank Account</h5>
        <a href="" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </a>
      </div>
      <div class="modal-body">
        	<p class="text-danger">N.B: You can upload your proof of payment after transaction</p>

        	<p><center><span class="text-danger text-sm-bold">Please transfer ₦3,000 only to the account below</span></center></p>
        	

        	<div class="row d-flex align-center mb-1">
        		<div class="col-sm-5">
        			<h3 class="text-sm-bold m-0">ACCOUNT NAME:</h3>
        		</div>
        		<div class="col-sm-7">
        			DESTINY PROMOTERS EVANGELICAL OUTREACH
        		</div>
        	</div>


        	<div class="row d-flex align-center mb-1">
        		<div class="col-sm-5">
        			<h3 class="text-sm-bold m-0">ACCOUNT NUMBER</h3>
        		</div>
        		<div class="col-sm-7">
        			0125755698
        		</div>
        	</div>


        	<div class="row d-flex align-center">
        		<div class="col-sm-5">
        			<h3 class="text-sm-bold m-0">BANK NAME:</h3>
        		</div>
        		<div class="col-sm-7">
        			WEMA BANK
        		</div>
        	</div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
<div class="main-container pt-2" style="left: 0!important; width: 100%;" >
		
	<div class="main-content-body pt-5">
		<h3 class="text-md-bold mb-3 text-center">Registration Fee Payment</h3>

		<div class="mt-5 investment-details-wrapper py-5">
			<h6 align="center" style="font-family: cursive;">Hi, <?php echo ucwords($fetch_for_virtual['fullname']); ?></h6>
			<p class="d-flex flex-end">
				<?php
				if ($count_reg_payment_proof > 0) {
				?>
				<div class="alert alert-danger mt-0">
				Registration fee payment awaiting confirmation. Confirmation takes up to 24-48hrs for manual payment proof uploaded. Please check back later.
				<br><br>
				<strong>N.B:</strong> Fake proof of payment will be declined and your account will be suspended.

				</div>
				<?php
				}
				else{
				//	echo '<a class="btn btn-success btn-sm" href="fund-wallet-upload">Upload Payment Proof</a>';
				}
				?>

			</p>
			<form class="d-flex flex-col space-mobile" action="payment-before-login" method="POST"  style="gap:40px;">
			<div class="form-inline row align-items-center">
				<div class="col-sm-4">
					<label class="text-md">Amount:</label> 
				</div>
				<div class="col-sm-8">
					<input type="number" readonly value="1000" required class="form-control input" name="payment-amount">
				</div>
			</div>

			<input type="hidden" class="form-control input" value="<?php echo $session_logged_in_user_id; ?>" name="user-id">

			<div class="form-inline row align-items-center" style="display: none;">

				<div class="col-sm-4">
					<label class="text-md">Choose Account</label>
				</div>
				<div class="col-sm-8"  style="position:relative;">
					<select required name="account-id" class="form-control select" >
						<option value="">DESTINY PROMOTERS Accounts to fund</option>
						<?php
								$query_user_all_account = query_user_all_account($session_logged_in_user_id);

								if(mysqli_num_rows($query_user_all_account) <= 0 ){

								}else{
									while($fetch_all_accounts = mysqli_fetch_array($query_user_all_account) ){
										extract($fetch_all_accounts);
										?>

							<option value="<?php echo $user_account_id; ?>" selected><?php echo $user_account_token; ?></option>
						<?php
							}
						}
						?>
					</select> 
					<i class="ri-arrow-down-s-line select-icon text-blur"></i>


				</div>
			</div>


			<div class="form-inline row align-items-center">

				<div class="col-sm-4">
					<label class="text-md">Payment Option</label>
				</div>
				<div class="col-sm-8" style="position:relative;">
					<select required class="form-control select" id="payment_type">
						<option value="">Select Payment Option</option>
						<option value="online_payment">Online Payment via Flutterwave</option>
					<!-- 	<option value="noblemerry_transfer">Transfer to Our BANK Account</option> -->
					</select> 
					<i class="ri-arrow-down-s-line select-icon text-blur"></i>					
				</div>
			</div>

			<div class="d-flex justify-content-center">
				<button class="btn btn-main proceed">Proceed</button>
			</div>
		</form>
		</div>

	</div>

</div>
</body>
</html>