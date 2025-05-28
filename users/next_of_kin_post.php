<?php
	
	$fullname = mysqli_real_escape_string($con, $_POST['fullname']);

	$phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);

	$address = mysqli_real_escape_string($con, $_POST['address']);

	$occupation =  isset($_POST['occupation']) ? mysqli_real_escape_string($con, $_POST['occupation']): "";

	$email = mysqli_real_escape_string($con, $_POST['email']);

	$dob = mysqli_real_escape_string($con, $_POST['dob']);

	$gender = mysqli_real_escape_string($con, $_POST['gender']);


?>