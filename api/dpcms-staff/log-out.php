<?php
ob_start();
session_start();
unset($_SESSION['admin_id']);
session_destroy();
header("Location: sign-in?Logout-Success");
?>