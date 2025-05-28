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
$export_name = "DPCMS-Export-Active-Members"; 
$current_date = date("d-m-Y");  
$fileName = $current_date.'-'.$export_name.".xls"; 

// Column names 
$fields = array('S/N', 'FULL NAME', 'PHONE NO', 'ADDRESS', 'TOTAL ACCOUNT', 'WALLET BALANCE', 'STATUS','CREATED DATE','BANK ACCT NAME','BANK NAME','BANK ACCT NO'); 
 
// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n";  
    
    $count = 0;
    $tshirt_msg = "";
   
   	$queryactiveuser = mysqli_query($con,"SELECT * FROM user JOIN user_account ON user_account.user_account_id = user.user_account_id WHERE user_reg_fee !='pending' AND is_acct_active!='no' AND can_login ='yes' ORDER BY user.user_id ASC"); 
    ////differenciate the filter and the full export please END HERE. 
    $count_active_users = mysqli_num_rows($queryactiveuser);
   // echo $count_active_users;
   // exit();
    if( $count_active_users <= 0 ){
          echo "less 0"; 
            } 
            else{  
            while ($row = mysqli_fetch_array($queryactiveuser)) {
                extract($row);

                /////count number of accounts per users. 
                $query_tt_acct = mysqli_query($con,"SELECT * FROM user_account WHERE user_id = '$user_id'");
                $count_tt_acct = mysqli_num_rows($query_tt_acct);

                ///get primary account id wallet balance
                 $query_balance = mysqli_query($con,"SELECT * FROM user_account WHERE user_id = '$user_id' AND user_account_type = 'primary'");
                $fetch_balance =  mysqli_fetch_array($query_balance);


                /////output user bank acct details with the excel data format
                $fetch_excel_bank = mysqli_fetch_array(query_user_bank_details($user_id));
                $the_bank_acct_no = "'".$fetch_excel_bank['account_no']."'";
                ///done with bank stuff here

      
    // Output each row of the data  
 
        //$status = ($row['status'] == 1)?'Active':'Inactive'; 
        $lineData = array($cnt++, ucwords(stripslashes($row['fullname'])), "+234".$row['phone_no'], $row['address'], $count_tt_acct, number_format($fetch_balance['user_account_wallet_amount']), $is_acct_active, @date("d-m-Y h:i:sa ",strtotime($row['user_reg_date'])), $fetch_excel_bank['account_name'],$fetch_excel_bank['lists_bankname'],$the_bank_acct_no); 
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