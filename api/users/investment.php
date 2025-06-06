<?php include "sidebar.php" ?>

<div class="main-container">
	<?php include "header.php" ?>
	<div class="main-content-body">
		<h3 class="text-md-bold mb-2">Thrift Packages</h3>
		<h6 class="text-sm-bold text-blur">Please Select any thrift package from the packages below</h6>

		<div class="grid-lists-lg mt-5">
				<?php

					$query_thrift_package = query_all_thrift_package();

					if (mysqli_num_rows($query_thrift_package) > 0) {
						
						while ($fetch_query_thrift_package = mysqli_fetch_array($query_thrift_package)) {
							extract($fetch_query_thrift_package);
							?>

					<div class="grid-lists-6 d-flex align-center">
						<div class="col-lists-icon-box">
							<img src="assets/images/dollar-bag.png" style="width:60px">
						</div>

						<div class="d-flex flex-col grid-lists-text-desc" style="justify-content:space-between;">
							<h5 class="text-blur"><?php  echo $thrift_package_name ?></h5>
							<span class="color-main">NGN<?php  echo number_format($thrift_package_price)?></span>
							<p class="mb-0"><?php echo $thrift_package_profit ?>% empowerment after <?php echo $thrift_package_working_weeks ?> weeks. <a href="../terms" target="_blank" style="color:#CE0016;">Terms & Conditions Apply</a></p>
							<a class="investment-link" href="investment-details?plan_id=<?php echo base64_encode($thrift_package_id) ?>">Read More</a>
						</div>
					</div>	

					<?php
						}
					}

				?>

					
			</div>
	</div>

</div>
</htnl>