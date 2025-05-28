<!DOCTYPE html>
<html>
<head>
	<title>Noblemerry Dashboard</title>
<?php include "sidebar.php" ?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body pt-5">
		<h3 class="text-md-bold mb-3">Add Funds to Wallet</h3>
		<h5 class="text-sm-bold subtitle text-blur">Please confirm the Amount to fund & Information details below before you continue</h5>

		<div class="mt-5 investment-details-wrapper py-5">
			<form class="d-flex flex-col space-mobile" action="" method="POST" style="gap:40px;">
				<div class="form-inline row align-items-center">
					<div class="col-sm-4">
						<label class="text-md">Full Name:</label>
					</div>
					<div class="col-sm-8">
						<input type="text" readonly class="form-control input" name="">
					</div>
				</div>


				<div class="form-inline row align-items-center">
					<div class="col-sm-4">
						<label class="text-md">Email:</label>
					</div>
					<div class="col-sm-8">
						<input type="text" readonly class="form-control input" name="">
					</div>
				</div>

				<div class="form-inline row align-items-center">
					<div class="col-sm-4">
						<label class="text-md">Phone Number:</label>
					</div>
					<div class="col-sm-8">
						<input type="text" readonly class="form-control input" name="">
					</div>
				</div>

				<div class="form-inline row align-items-center">
					<div class="col-sm-4">
						<label class="text-md">Amount to Fund:</label>
					</div>
					<div class="col-sm-8">
						<input type="text" readonly class="form-control input" name="">
					</div>
				</div>

				<div class="d-flex justify-content-center">
					<button class="btn btn-main">Pay Now</button>
				</div>
			</form>
		</div>

	</div>

</div>
</body>
</html>