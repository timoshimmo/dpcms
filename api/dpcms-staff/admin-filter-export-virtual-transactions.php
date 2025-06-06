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
$fields = array('S/N', 'TXN ID', 'FULL NAME', 'PHONE NO', 'VIRTUAL REF', 'PAYMENT AMOUNT', 'STATUS', 'EVENT TYPE','CURRENCY','CREATED AT'); 
 
// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n";  
    
    $count = 0;
    $tshirt_msg = "";
   
   	$querylogin_history = mysqli_query($con,"SELECT * FROM webhook_event_transaction JOIN user_virtual_account ON user_virtual_account.virtual_account_tx_ref = webhook_event_transaction.tx_ref ORDER by webhook_event_transaction.webhook_event_transaction_id DESC"); 
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

               /////query users out
            $query_users_out = mysqli_query($con,"SELECT * FROM user WHERE user_id = '$user_id'");
            $fetch_user = mysqli_fetch_array($query_users_out);

      
    // Output each row of the data  
 
        //$status = ($row['status'] == 1)?'Active':'Inactive'; 
        $lineData = array($cnt++,$webhook_transaction_id, ucwords(stripslashes($fetch_user['fullname'])), "+234".$fetch_user['phone_no'], $row['flw_ref'], number_format($charged_amount), $row['status'], $event_type, $currency, @date("d-m-Y h:i:sa ",strtotime($row['event_type_created_at']))); 
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