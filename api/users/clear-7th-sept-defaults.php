<?php include "sidebar.php" ?>
<?php
$qty= 0;
$query_def = clear_sept7th_defaults_without_charges($session_logged_in_user_id);
$no_of_defaults = mysqli_num_rows($query_def);
//echo $no_of_defaults;
///this is the defaults without charges 
$def_without_charge = $no_of_defaults * 1300;




/////defaults with charges below
	
	if (isset($_POST['clear_bulk_default'])) {
		include("../includes/call-api.php"); 
		
		$output = "";
		$no_of_defaulted = mysqli_real_escape_string($con,$_POST['no_of_defaulted']);

		$payment_with_out_charges = mysqli_real_escape_string($con,$_POST['amout_to_pay']);
		$payment_with_charges = $payment_with_out_charges;

		////////all balances whatsoever should be removed from the user primary account wallet irespective of the account trying to clear whole defaults once.
		$query_primary_account = get_only_primary_user_account_details($session_logged_in_user_id);
		$primary_account_id = $query_primary_account['user_account_id'];
		$balance = $query_primary_account['user_account_wallet_amount'];
		//$output = $balance;

		///substract total thrift fine with charges FROM balance
		$deduction = $balance - $payment_with_charges;

		if ($no_of_defaulted <=0) {
				$output = '<div class="alert alert-danger col-12 col-md-7 mt-3">
				You do not have any Defaults to clear on this account. Thank you
				</div>';
		}
		else{
			////continue the whole process here. 
			//check if payment with charges not greater than balance
			if ($payment_with_charges > $balance) {
				$output = '<div class="alert alert-danger col-12 col-md-7 mt-3">
				Insufficient funds. Your primary account wallet balance is lesser than ₦'.number_format($payment_with_charges).' that you ought to pay.
				</div>';
			}
			else if ($deduction<0) {
				$output = '<div class="alert alert-danger col-12 col-md-7 mt-3">
				Insufficient funds. Kindly fund your wallet to pay your debt of ₦'.number_format($payment_with_charges).' that you ought to pay.
				</div>';
			}
			else{


				while ($num = mysqli_fetch_array($query_def)) {
    			extract($num); 
    			// $user_account_id;
    			////now clear the defaults and weekly history
				$process_charges = mysqli_query($con,"UPDATE thrift_transaction_details SET thrift_fine = $thrift_fine - 1300 WHERE user_account_id = '$user_account_id' AND user_id = '$session_logged_in_user_id'") or die(mysqli_error($con));

				////update the debts_log to update the status updated.
					@mysqli_query($con,"UPDATE debts_log2 SET status_updated = 'yes' WHERE user_account_id = '$user_account_id' AND user_id = '$session_logged_in_user_id'") or die(mysqli_error($con));
    			
				} 
				if ($process_charges) {
					$output = "bulk_debt_cleared_success";
					$query_dlog = query_log_bulk_defaults_clearance($session_logged_in_user_id,$no_of_defaulted,$payment_with_charges,$payment_with_out_charges,$balance,$deduction); 
					update_user_balance($deduction, $primary_account_id, $con);
				}
				else{
				$output = '<div class="alert alert-success col-12 col-md-7 mt-3">
				Something went wrong, Try again later.
				</div>';
				}


			}
		}

	}

?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">

		<h3 class="text-md-bold mb-2">Pay for Defaults cleared for 7th of Sept Contribution</h3>
		<h6 class="text-sm-bold text-blur">On this page, you will pay for defaults cleared without charges by just clicking a button.</h6>
		<p>You are to pay the Default Fee With Out Charges and you will see the total number of accounts affected. </p>

		<?php

			if (isset($_POST['clear_bulk_default'])) {
		
				//echo $output;
				//header("refresh:2, url=manage-bank-account");
				if ($output=='bulk_debt_cleared_success') {
				echo '<div class="alert alert-success col-12 col-md-7 mt-3">
				The defaults payments paid successfully without charges. Wait while the system redirects you.
				</div>';
				header("refresh:1, url=dashboard");
				}
				else{
					echo $output;
				}
			} 

		?>
		
		<form class="d-flex flex-col mt-5"  method="POST" style="gap:40px;">



		<div class="row">
			<div class="form-inline row align-items-center">
				<div class="col-sm-3">
				</div>
				<div class="col-sm-3">
					<label class="text-md">No of Defaulted Account(s):</label>
				</div>
				<div class="col-sm-1">
					<input type="number" required class="form-control input" name="no_of_defaulted" value="<?php echo $no_of_defaults; ?>" readonly>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="form-inline row align-items-center">
				<div class="col-sm-3">
				</div>
				<div class="col-sm-3">
					<label class="text-md">Amount Without Charges:</label>
				</div>
				<div class="col-sm-3">
					<input type="number" required class="form-control input" id="accountNumber" name="account_number" placeholder="₦<?php echo number_format($def_without_charge); ?>" readonly>
					<input type="hidden" name="amout_to_pay" value="<?php echo $def_without_charge; ?>">
				</div>
			</div>
		</div>

		<?php if ($def_without_charge>0) {
			?>
			<div class="d-flex justify-content-center">
				<button class="btn btn-main" name="clear_bulk_default" onclick="return confirm('Are you sure you are ready to pay the money? \n\n It can not be Undo \n\n OK to Continue');">Pay ₦<?php echo number_format($def_without_charge); ?></button>
			</div>
		<?php
		}
		else {
			///do nothing
		}

			?>
		</form>
	</div>

</div>


</body>
</html>