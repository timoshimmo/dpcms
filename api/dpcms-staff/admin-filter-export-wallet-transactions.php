<?php
#ini_set('display_errors', '1');
#ini_set('display_startup_errors', '1');
#error_reporting(E_ALL);
	include '../includes/session.php';
	include("../includes/config.php");
	include '../includes/db-functions.php';
	include '../includes/call-api.php';
if(isset($_SESSION['admin_id'])) {
		//header("Location: log-out?Logout_success");
    $cnt = 1;

// Filter the excel data 
function filterData(&$str){  
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 
  
// Excel file name for download  
$export_name = "DPCMS-Export-General-Members-Defaults"; 
$current_date = date("d-m-Y");  
$fileName = $current_date.'-'.$export_name.".xls"; 

// Column names 
$fields = array('S/N', 'FULL NAME', 'PHONE NO', 'ACCOUNT TYPE', 'ACCOUNT TOKEN', 'TXN REF', 'TXN TYPE','PAYMENT AMOUNT','PAYMENT TYPE','CREATED AT'); 
 
// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n";  
    
    $count = 0;
    $tshirt_msg = "";
   
   	$querylogin_history = mysqli_query($con, "SELECT * FROM wallet_transaction_details JOIN user_account ON user_account.user_account_id = wallet_transaction_details.user_account_id AND  wallet_transaction_details.wallet_payment_amount != 0 JOIN user ON user.user_id = wallet_transaction_details.user_id WHERE user_account.user_account_type = 'primary' ORDER BY wallet_transaction_details.wallet_txn_id DESC");
    ////differenciate the filter and the full export please END HERE. 
    $count_active_users = mysqli_num_rows($querylogin_history);
   // echo $count_active_users;
   // exit();
    if( $count_active_users <= 0 ){
          echo "less 0"; 
            } 
            else{  
            while ($row = mysqli_fetch_array($querylogin_history)) {
                extract($row);


            $trxn_type = ($wallet_payment_txn_id=='manually' ? 'Manual Funding' : 'Gateway Payment');
               /////query users out
            $query_users_out = mysqli_query($con,"SELECT * FROM user WHERE user_id = '$user_id'");
            $fetch_user = mysqli_fetch_array($query_users_out);

      
    // Output each row of the data  
 
        //$status = ($row['status'] == 1)?'Active':'Inactive'; 
        $lineData = array($cnt++, ucwords(stripslashes($fetch_user['fullname'])), "+234".$fetch_user['phone_no'], $row['user_account_type'], $user_account_token, $row['wallet_txn_ref'], $trxn_type, number_format($wallet_payment_amount), $wallet_payment_type, @date("d-m-Y h:i:sa ",strtotime($row['wallet_txn_created_at']))); 
        array_walk($lineData, 'filterData');  
        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
    
}
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
header("Pragma: no-cache"); 
header("Expires: 0");
 
// Render excel data 
echo $excelData; 
 
exit;



}
}
else{
	//header("Location: instructor_statements.php");
  //  echo "<script>history.back();</script>";
}
///instructor_generate_statementvia_excel.php
?>