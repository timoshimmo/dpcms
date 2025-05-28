<?php 
include("../includes/session.php"); 
include("../includes/config.php"); 
include("../includes/db-functions.php"); 

if (!isset($_SESSION['session_logged_in_user_account_id']) || empty($_SESSION['session_logged_in_user_account_id'])  && !isset($_SESSION['session_logged_in_user_id']) || empty($_SESSION['session_logged_in_user_id']) ) {
	header("Location:logout");
}
else{
	$session_logged_in_user_account_id = $_SESSION['session_logged_in_user_account_id'];
	$session_logged_in_user_id = $_SESSION['session_logged_in_user_id'];

	$query_info = mysqli_query($con, "SELECT * FROM user JOIN user_account ON user.user_id = user_account.user_id WHERE user.user_id = '$session_logged_in_user_id' AND user_account.user_account_id='$session_logged_in_user_account_id'");

	if (mysqli_num_rows($query_info) > 0 ) { 

		$fetch_user_details = mysqli_fetch_array($query_info);
		$user_fullname = $fetch_user_details['fullname'];
		$user_phone_no = $fetch_user_details['phone_no'];


		if($fetch_user_details['user_reg_fee'] == "pending"){ 
			header("Location:logout");
		}
	}else{

		header("Location:logout");
	}
}




$the_hour = date("H");
$dateTerm = ($the_hour > 17) ? "Evening" : (($the_hour > 12) ? "Afternoon" : "Morning");
$query_fetch_user_details = query_user_details($session_logged_in_user_id);
	
			
/*GETS USERS LAST THRIFT TRANSACTION*/
$query_last_thrift_txn = query_users_thrift_transaction_desc($session_logged_in_user_account_id);

if (mysqli_num_rows($query_last_thrift_txn) > 0) {

	$fetch_last_thrift_txn = mysqli_fetch_array($query_last_thrift_txn);

}



$get_user_account_type = get_user_account_details($session_logged_in_user_account_id, 'user_account_type');

?>

 <meta charset="utf-8">
 	<title>Member's Dashboard | Destiny Promoters Cooperative</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/media.css">
	<link rel="icon" href="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" type="image/png">
	<link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/2NJ7pCS/dpcms-logo.png" />
	<link rel="stylesheet" type="text/css" href="assets/icons/remixicon/remixicon.css">
	<link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
	<script src="assets/js/jquery-3.5.1.js"></script>
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/dataTables.bootstrap4.min.js"></script>
	<script defer src="assets/js/script.js"></script>
	<script src="../assets/js/popper.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>

<?php include "set-active-page.php" ?>

<!-- SIDEBAR STARTS -->
<div class="sidebar">
	<div class="sidebar-top d-flex justify-content-center">
		<a class="d-block-desktop" href="index"><img src="https://i.ibb.co/prWn33kB/destiny-pdf.jpg" style="max-width: 100%;"></a>

		<!-- SIDEBAR MOBILE USER STARTS -->
		<div class="header-right-user d-flex-mobile">
				<div class="header-right-img-wrapper">
					<img src="<?php echo $query_fetch_user_details['photo'] ? $query_fetch_user_details['photo'] : "photos/avatar-1.jpg.png" ?>" class="header-right-img">
				</div>

				<div class="d-flex flex-col">
					<h6 class="text-sm-bold mb-0 text-white-mobile"><?php echo ucwords(stripslashes($fetch_user_details['fullname'])); ?></h6>
					<span class="text-sm-blur text-white-mobile"><?php echo $fetch_user_details['user_account_token'];?></span> <span class="text-sm-blur text-white-mobile">(<?php echo ($fetch_user_details['user_account_type']=='others' ? "Other" : "Primary") ?> Account)</span>
				</div>
		</div>
		<img class="d-mobile toggle-close" src="assets/images/times.png">
		<!-- SIDEBAR MOBILE USER STARTS -->

	</div>

	<div class="sidebar-navigation d-flex flex-col">
		<a href="index" class="sidebar-navigation-list <?php echo $home ?>  d-flex align-center">
			<img src="assets/images/grid.png">
			<span class="text-white">Dashboard</span>
			<i class="ri-arrow-right-s-line nav-icon text-white"></i>
		</a>


<div class="sidebar-dropdown">
<a href="javascript:void();" class="sidebar-navigation-list sidebar-menu-link <?php echo $profile ?>  d-flex align-center">
<img src="assets/images/user.png">
<span class="text-white">My Profile</span>
<i class="ri-arrow-right-s-line nav-icon text-white"></i>
</a>

<div class="dropdown-menus d-flex flex-col">
<a href="profile" class="sidebar-navigation-list text-white <?php echo $profile ?>">Go to My Profile</a>

<a href="add-account" class="sidebar-navigation-list text-white ">Add More Account</a>

<a href="clear-bulk-account-defaults" class="sidebar-navigation-list text-white ">&raquo; Clear Bulk Account Defaults</a>

<a href="change-password" class="sidebar-navigation-list text-white">Change Password</a>

<a href="manage-bank-account" class="sidebar-navigation-list text-white <?php echo $payment ?>">View Bank Details</a>


</div>
</div>




		<?php 
	if ($query_fetch_user_details['is_acct_active'] == 'yes' && !empty($query_fetch_user_details['photo']) && mysqli_num_rows(query_user_bank_details($session_logged_in_user_id))) {
		?>





<!-- settlement accounts starts here -->
<!-- settlement accounts starts here -->
<!-- settlement accounts starts here -->
<!-- settlement accounts starts here -->

<div class="sidebar-dropdown" style="display: none;">
<a href="javascript:void();" class="sidebar-navigation-list sidebar-menu-link  d-flex align-center">
<i style="font-size:15px;" class="ri-sd-card-fill text-white"></i>
<span class="text-white">Settlements Account(s)</span>
<i class="ri-arrow-right-s-line nav-icon text-white"></i>
</a>

<div class="dropdown-menus d-flex flex-col">
<a href="account-due-for-clearance" class="sidebar-navigation-list text-white">&raquo; Due for Clearance</a>
<a href="javascript:void();" class="sidebar-navigation-list text-white" onclick="swal('Updates Coming Soon','Please check back later','success')">&raquo; All Paid Accounts</a>
<a href="bulk-account-requests-withdrawal" class="sidebar-navigation-list text-white" >&raquo;Request Bulk Account Withdrawal</a>
<a href="list-of-acct-for-next-settlement" class="sidebar-navigation-list text-white" >&raquo; Account for Next Settlement</a>


</div>
</div>

<!-- settlement accounts ends here -->
<!-- settlement accounts ends here -->
<!-- settlement accounts ends here -->
<!-- settlement accounts ends here -->
<!-- settlement accounts ends here -->

<!-- 		<a href="wallet-transfer" class="sidebar-navigation-list d-flex align-center">
			<i style="font-size:15px;" class="ri-sd-card-fill text-white"></i>
			<span class="text-white">Wallet to Wallet Transfer</span>
			<i class="ri-arrow-right-s-line nav-icon text-white"></i>
		</a> -->

		<a href="investment" class="sidebar-navigation-list <?php echo $investment ?> d-flex align-center">
			<!-- <img src="assets/images/graph.png"> -->
			<i style="font-size:15px;" class="ri-file-lock-line text-white"></i>
			<span class="text-white">Thrift Package</span>
			<i class="ri-arrow-right-s-line nav-icon text-white"></i>
		</a>

<div class="sidebar-dropdown">
<a href="javascript:void();" class="sidebar-navigation-list sidebar-menu-link  d-flex align-center">
<img src="assets/images/bag.png">
<span class="text-white">Manage Transactions</span>
<i class="ri-arrow-right-s-line nav-icon text-white"></i>
</a>

<div class="dropdown-menus d-flex flex-col">

<a href="wallet-transaction" class="sidebar-navigation-list text-white <?php echo $wal_transaction ?>"><i style="font-size:15px;" class="ri-wallet-3-line text-white"></i> Wallet Transactions</a>
<a href="thrift-transaction" class="sidebar-navigation-list text-white <?php echo $thrift_transaction ?>"><img src="assets/images/bag.png"> Thrift Transactions</a>
<a href="virtual-transaction" class="sidebar-navigation-list text-white"><img src="assets/images/bag.png"> Virtual Transactions</a>
</div>
</div>

<!-- 		<a href="wallet-transaction" class="sidebar-navigation-list <?php //echo $wal_transaction ?> d-flex align-center">
			<i style="font-size:15px;" class="ri-wallet-3-line text-white"></i>
			<span class="text-white">Wallet Transaction</span>
			<i class="ri-arrow-right-s-line nav-icon text-white"></i>
		</a>

		<a href="thrift-transaction" class="sidebar-navigation-list <?php //echo $thrift_transaction ?> d-flex align-center">
			<img src="assets/images/bag.png">
			<span class="text-white">Thrift Transaction</span>
			<i class="ri-arrow-right-s-line nav-icon text-white"></i>
		</a> -->

		<?php
			}
		?>

		<a href="logout" class="sidebar-navigation-list spacing d-flex align-center">
			<img src="assets/images/logout.png">
			<span class="text-white">Logout</span>
		</a>
	</div>
</div>
<!-- SIDEBAR ENDS -->