<?php include "sidebar.php" ?>

<?php
	
	if (isset($_GET['plan_id']) && !empty($_GET['plan_id'])) {

		$plan_id = base64_decode($_GET['plan_id']);
		
		$fetch_thrift_query = mysqli_fetch_array( query_single_thrift_package($plan_id));
					
	}else{
		header("Location:dashboard");
	}

?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<a href="javascript:history.back()" class="back-link d-inline-flex">
			<i class="ri-arrow-left-s-line"></i>
			<span>Back</span>
		</a>

		<div class="mt-3 investment-details-wrapper">
			<h3 class="text-center">Thrift Plan Details</h3>

			<?php

				if (isset($_POST['save'])) {
					echo $output;
				}

			?>
			
			
			<form action="" method="POST" id="form">
			<div class="mt-4 d-flex flex-col" style="gap:11px;">
				<h4 class="text-md-bold"><?php echo $fetch_thrift_query['thrift_package_name']; ?> plan</h4>

				<div class="d-flex align-center investment-details-list">
					<h6 class="m-0 text-sm-bold">Product Code:</h6>
					<span class="text-blur"><?php echo $fetch_thrift_query['thrift_package_plan_code'] ?></span>
				</div>


				<div class="d-flex align-center investment-details-list">
					<h6 class="m-0 text-sm-bold">Length:</h6>
					<span class="text-blur"><?php echo $fetch_thrift_query['thrift_package_working_weeks'] ?> Weeks</span>
				</div>

				<div class="d-flex align-center investment-details-list">
					<h6 class="m-0 text-sm-bold">Cashback:</h6>
					<span class="text-blur"><?php echo $fetch_thrift_query['thrift_package_profit'] ?>% when you meet up the thrift requirements.</span>
				</div>

				<div class="mt-4 investment-plan-wrapper d-flex">
					
					<div class="investment-plan">
						<p class="m-0 text-blur text-sm">Investment Plan Price</p>
						<span class="text-sm-bold pb-0 text-blur">N<?php  echo number_format($fetch_thrift_query['thrift_package_price'])?></span>
					</div>
				</div>

				<?php

				$query_thrift_exists = mysqli_query($con, "SELECT * FROM thrift_transaction_details WHERE user_account_id ='$session_logged_in_user_account_id' AND thrift_txn_counter='on' AND txn_status ='active' ");
				if(mysqli_num_rows($query_thrift_exists) > 0){
					?>

					<p class="text-center text-danger">You have a running thrift plan on this account</p>
				<?php
					}else if (@$fetch_last_thrift_txn['thrift_fine']  > 0) {
				?>

					<p class="text-center text-danger">You still have a thrift debt and fine that is needed to be paid.</p>	

				<?php

					}
					else if(mysqli_num_rows(let_primary_acct_activate_thrift_once($session_logged_in_user_account_id))>=1){
					     /////This code is to not let primary acct enrol again or activate a new contribution after the first one. but others acct that ain't primary can proceed in adding accts. 
					     echo '<p class="text-center text-danger">Oops!!, Sorry your Primary Account can not re-subscribe to a new thrift package. Please add more account to start a new contribution</p>';
					}
					else{
				?>

				<div class="d-flex justify-content-center mt-5">
						<button id="btn-subscribe" class="investment-plan-btn" name="">Subscribe & Continue</button>
				</div>

				<?php
					}
				?>
			

			</div>
			</form>
		</div>

	</div>

</div>

<script>
	
$("#form").submit(function(e){

		e.preventDefault();

		var formData = new FormData();

	formData.append("plan_id",<?php echo $plan_id ?>);

	formData.append("session_logged_in_user_account_id", <?php echo $session_logged_in_user_account_id ?>);

	formData.append("session_logged_in_user_id", <?php echo $session_logged_in_user_id ?>);

	
	$.ajax({
				url:"ajax-request.php", 
                     type:"POST",  
                     data: formData,
                     cache: false,
                     contentType: false,
            		 processData: false,
                     beforeSend:function(){  
                          $("#btn-subscribe").html("Please Wait...").attr("disabled",true);
                     },
                     success: function(data){	

                    	if (data=='subscribe_success') {
                     		swal("Thrift Plan Activated","Thrift Plan Activated Successfully.","success"); 
                     		$("#btn-subscribe").remove();
                     		$(".mt-4.d-flex.flex-col").append('<p class="text-center text-danger">You have a running thrift plan on this account.</p>');
                     		setTimeout( () => {
                     			window.location.href ="thrift-transaction";	
                     		}, 3000)
                     		
                     	}else if (data=='insufficient_funds') {
                     		swal("Insufficient Wallet Balance","Insufficient Wallet Balance. Kindly Fund Your Wallet.","warning");
                     		$("#btn-subscribe").html("Subscribe & Continue").removeAttr("disabled",true);

                     	}else if (data=='already_subscribe') {
                     		swal("Subscribed Already","You have already subscribe for this package.","danger");
                     		$("#btn-subscribe").remove();
                     		$(".mt-4.d-flex.flex-col").append('<p class="text-center text-danger">You have a running thrift plan on this account.</p>');
                     		setTimeout( () => {
                     			window.location.href ="thrift-transaction";	
                     		}, 3000)
                     	}else {
                     		swal("Unknown error occurred","An Unknown error occured, please contact the administrator","error");
                     		$("#btn-subscribe").html("Subscribe & Continue").removeAttr("disabled",true);
                     	}
                     }
             });  
	})


</script>
</body>
</html>