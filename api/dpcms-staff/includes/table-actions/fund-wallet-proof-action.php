						<!-- Button to Open the Modal -->
<button type="button" class="btn btn-info btn-sm btn_click_show_modal btn_click_show_modal<?php echo $payment_proof_id; ?>" id="<?php echo $payment_proof_id; ?>">
  View screenshot <?php// echo $payment_proof_id; ?>
</button>

					<?php
						if($payment_status =="unpaid"){
					?>
					

					<button class="btn btn-secondary btn-sm btn_fund_wallet" fundwallet_user_id="<?php echo $user_id ?>" fundwallet_user_acctid="<?php echo $user_account_id ?>" fundwallet_payment_proofid="<?php echo $payment_proof_id ?>" fundwallet_username="<?php echo ucwords($fullname); ?>" id="f_wallet_button<?php echo $payment_proof_id ?>">Fund Wallet</button>


					<a class="btn btn-danger btn-sm" id="btn_decline_payment<?php echo $payment_proof_id ?>" href="admin-decline-payment-proof?payment_proof_id=<?php echo base64_encode($payment_proof_id) ?>&token=<?php echo base64_encode($sess_id); ?>&user=<?php echo base64_encode($user_id); ?>">Decline Payment</a>

					<!-- we need to have a delete button here to delete proof or duplicate proof -->
					<a class="btn btn-primary btn-sm" id="btn_delete_payment_proof<?php echo $payment_proof_id ?>" href="admin-delete-payment-proof?payment_proof_id=<?php echo base64_encode($payment_proof_id) ?>&user=<?php echo base64_encode($user_id); ?>" onclick="return confirm('You are about to delete this Payment proof. \n \nClick OK to Proceed');">Delete Payment Proof</a>
					<?php
					}else if($payment_status =="cancelled"){
					?>
						<button class="btn btn-danger btn-sm">Cancelled</button>  
						<a class="btn btn-primary btn-sm" href="admin-delete-payment-proof?payment_proof_id=<?php echo base64_encode($payment_proof_id) ?>&user=<?php echo base64_encode($user_id); ?>" onclick="return confirm('You are about to delete this Payment proof. \n \nClick OK to Proceed');">Delete Payment Proof</a>
					<?php
					}else{
					?>

						<button class="btn btn-success btn-sm">Confirmed</button> 

					<?php
					}