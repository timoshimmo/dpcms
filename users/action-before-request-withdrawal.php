<?php include "sidebar.php" ?>
<style>
	
	.tobehidden, .self-pick-hidden, #CitiesHidden{
		display: none;
	}
</style>




		<?php

	if (isset($_GET['txn_id']) && !empty($_GET['txn_id']) && isset($_POST['proceed'])) {
		
		$output = "";
		$thrift_txn_id = base64_decode($_GET['txn_id']);
		$incentive_type = mysqli_real_escape_string($con, addslashes($_POST['incentive_type']));
		$incentive_pickup_type = mysqli_real_escape_string($con, addslashes($_POST['incentive_pickup']));
		$incentive_delivery_state = mysqli_real_escape_string($con, addslashes($_POST['incentive_delivery_state']));
		$incentive_delivery_number = mysqli_real_escape_string($con, addslashes($_POST['incentive_delivery_number']));
		$incentive_delivery_amount = mysqli_real_escape_string($con, addslashes($_POST['incentive_delivery_amount']));
		$incentive_delivery_city = mysqli_real_escape_string($con, addslashes($_POST['incentive_delivery_city'])); 

		$set_status = $incentive_pickup_type == "Self-pick" ? "paid" : "pending";

		$create_thrift_incentive_query = set_thrift_incentives($thrift_txn_id, $incentive_type, $incentive_pickup_type, $incentive_delivery_state, $incentive_delivery_number, $incentive_delivery_amount,$incentive_delivery_city, $set_status);

		if ($create_thrift_incentive_query) {
			$output = "<div class='alert alert-success'>
				Incentives Successfully Created
				</div>";
				header("refresh:2, url=thrift-transaction");
		}else{
			$output = "<div class='alert alert-danger'>
				Something Went wrong try again later
				</div>";
				header("refresh:2, url=thrift-transaction");	
		}

	}

?>



<div class="main-container">

	<?php include "header.php" ?>
	<div class="main-content-body">
		<h3 class="text-md-bold mb-3">Choose Incentives &amp; Means of Pick-up</h3>
		<?php
		$get_fasttrack_balance = mysqli_num_rows(check_fasttrack_weeks_balance_paid($session_logged_in_user_account_id));
		if ($get_fasttrack_balance > 0) { 
			////since the user acct hasn't paid for the remain fasttrack balance
				///////fetch the balance and other details related to the user fast track
			$fetch_fasttrack_bal = mysqli_fetch_array(check_fasttrack_weeks_balance_paid($session_logged_in_user_account_id));
				$remaining_weeks_to_balance = $fetch_fasttrack_bal['remaining_weeks_to_balance'];
				$current_weeks_fasttracked = $fetch_fasttrack_bal['current_week_when_fasttracked'];
				$uncovered_thrift_weeks = $fetch_fasttrack_bal['uncovered_weeks_when_fasttracked'];
		?>
		<strong style="color:red;">N.B: You are to pay a sum of ₦<?php echo number_format($remaining_weeks_to_balance); ?> for the <b>(<?php echo $uncovered_thrift_weeks; ?>)</b> uncovered thrift contribution weeks because you fast-tracked when your thrift account was on week <?php echo $current_weeks_fasttracked; ?>.<br><br>Please pay for this on the THRIFT TRANSACTION PAGE and come back to Choose Incentives & Means of Pick-up.</strong><p></p>
		<p><a class="btn btn-success btn-sm" href="thrift-transaction">GO TO THRIFT TRANSACTION PAGE</a></p>
		<?php
			exit();
		}
		else{
			//echo "paid";
		}
		?>
	
		<div class="mt-5 investment-details-wrapper py-5">

			<?php

				if (isset($_GET['txn_id']) && !empty($_GET['txn_id']) && isset($_POST['proceed'])) {
						echo $output;
				}
			?>
			
		<form class="d-flex flex-col space-mobile" action="" method="POST"  style="gap:40px;">
			

			<div class="form-inline row align-items-center">

				<div class="col-sm-4">
					<label class="text-md">Choose Incentive</label>
				</div> 
				<div class="col-sm-8"  style="position:relative;">
					<select required name="incentive_type" class="form-control select" >
						<option value="">Select Incentive</option>
						<option value="Foodstuff">Foodstuff & Provision</option>
					<!-- 	<option value="Provision">Provision</option> -->
					</select> 
					<i class="ri-arrow-down-s-line select-icon text-blur"></i>


				</div>
			</div>


			<div class="form-inline row align-items-center">

				<div class="col-sm-4">
					<label class="text-md">Means of pickup</label>
				</div>
				<div class="col-sm-8" style="position:relative;">
					<select required name="incentive_pickup" class="form-control select" id="pickup">
						<option value="">Select Means of pickup</option>
						<option value="Waybill">Waybill</option>
						<option value="Self-pick">Self-pick</option>
					</select> 
					<i class="ri-arrow-down-s-line select-icon text-blur"></i>					
				</div>
			</div>



			<div class="form-inline row align-items-center tobehidden to-be-hidden">

				<div class="col-sm-4">
					<label class="text-md">Choose States</label>
				</div>
				<div class="col-sm-8" style="position:relative;">
					<select  class="form-control select" name="incentive_delivery_state" id="state">
						<option value="">Select states</option>
					</select> 
					<i class="ri-arrow-down-s-line select-icon text-blur"></i>					
				</div>
			</div>

			<div class="form-inline row align-items-center tobehidden to-be-hidden">
				<div class="col-sm-4">
					<label class="text-md">Enter Phone Number:</label>
				</div>
				<div class="col-sm-8">
					<input type="number" id="number" class="form-control input" name="incentive_delivery_number">
				</div>
			</div>

			<div class="form-inline row align-items-center tobehidden to-be-hidden">
				<div class="col-sm-4">
					<label class="text-md">Waybill Price:</label>
				</div>
				<div class="col-sm-8">
					<input type="number" readonly id="amount" class="form-control input" name="incentive_delivery_amount">
				</div>
			</div>

			<div class="form-inline row align-items-center" id="CitiesHidden">

				<div class="col-sm-4">
					<label class="text-md">Choose Cities</label>
				</div>
				<div class="col-sm-8" style="position:relative;">
					<select class="form-control select" id="city" name="incentive_delivery_city">
						<option value="">Select City</option>
					</select> 
					<i class="ri-arrow-down-s-line select-icon text-blur"></i>					
				</div>
			</div>

			<h2 id="selfPick" class="self-pick-hidden">Our pickup days are monday, wednesday, friday between 10am to 1pm</h2>

			<div class="d-flex justify-content-center">
				<button id="submitBtn" name="proceed" class="btn btn-main proceed">Submit</button>
			</div>
		</form>
		</div>

	</div>

</div>

<script type="text/javascript">
		
$(document).ready(function(){ 

  $('#state').change(function(){
    loadCity($(this).find(':selected').val())

    var selectedText = this.options[this.selectedIndex].textContent;

   amount.value = getData(selectedText)

   
   if( this.value != "" ){
   		
   		submitBtn.style.display = "none"
   		CitiesHidden.style.display = "flex"

   }
  })


   $('#city').change(function(){

   		if(this.value != "") submitBtn.style.display = "inline-block"

   })

  pickup.addEventListener("change", function(){
  	console.log( this.value )
  	 if (this.value == "Self-pick") {
  	 	selfPick.classList.remove("self-pick-hidden");
  	 	Array.from(document.querySelectorAll(".to-be-hidden")).map(inputs => inputs.classList.add("tobehidden"))
  	 	submitBtn.style.display = "inline-block"
  	 	CitiesHidden.style.display = "none"
  	 }else if (this.value == "Waybill") {
  	 	Array.from(document.querySelectorAll(".to-be-hidden")).map(inputs => inputs.classList.remove("tobehidden"))
  	 	selfPick.classList.add("self-pick-hidden");
  	 	submitBtn.style.display = "none"
  	 }
  })

});


		function loadState(countryId){ 
        $("#state").children().remove()
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: "get=state&countryId=" + countryId
            }).done(function( result ) { 
                
                    $("#state").append($(result));
                
            });
}



function getData(selectedText) {
		var south = ["Anambra & Enugu & Ebonyi" ,"Imo & Abia", "Edo & Delta", "Akwa Ibom", "Rivers & Bayelsa", "Lagos", "Cross River", "Oyo & Osun","Ondo & Ekiti","Ogun"];

		var northCentral = ["Benue", "Kwara & Kogi", "Plateau & Nassarawa", "Niger"];

		var north = ["Borno & Yobe", "Adamawa", "Bauchi & Gombe", "Taraba", "Kaduna", "Kano & Jigawa", "Katsina", "Sokoto & Kebbi & Zamfara"];

		if (selectedText == "Federal Capital Dist" || northCentral.includes(selectedText)) return 8000
		else if( south.includes(selectedText) ) return 8000
		else if( north.includes(selectedText) ) return 10000
		else return 0

}


loadState(156);

function loadCity(stateId){
        $("#city").children().remove()
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: "get=city&stateId=" + stateId
            }).done(function( result ) {
                
                    $("#city").append($(result));
                
            });
}

</script>
</body>
</html>