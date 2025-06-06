<?php
#ini_set('display_errors', '1');
#ini_set('display_startup_errors', '1');
#error_reporting(E_ALL);
//include connection file
//exit();
include("../../includes/session.php"); 
include("../../includes/config.php"); 
//echo $webhook_secret_key;


    $session_logged_in_user_account_id = $_SESSION['session_logged_in_user_account_id'];
    $session_logged_in_user_id = $_SESSION['session_logged_in_user_id'];

    $filter_start_date = "2024-04-15";
    $filter_end_date = "2024-05-15";
    //echo $filter_end_date;
    ////$tomorrow = date("Y-m-d", strtotime("+ 1 day"));
   // exit();

   // $query_info = mysqli_query($con, "SELECT * FROM user JOIN user_account ON user.user_id = user_account.user_id WHERE user.user_id = '$session_logged_in_user_id' AND user_account.user_account_id='$session_logged_in_user_account_id'");

  //  echo $session_logged_in_user_id;
//exit();
include_once('libs/fpdf.php');
 
class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    $this->Image('https://noblemerrycompany.com/assets/images/merryfav.png',20,5,20);
    $this->SetFont('Arial','B',13);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(80,10,'Virtual Statements',1,0,'C');
    // Line break
    $this->Ln(20);
}
 
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
 
/*$db = new dbObj();
$connString =  $db->getConnstring();*/
$qty_cr = 0;
$payment_status = "Credit";
$display_heading = array('webhook_transaction_id'=>'ID', 'customer_name'=> 'Name', 'tx_ref'=> 'Customer ID','charged_amount'=> 'Amount',);
///$header = mysqli_query($conn, "SHOW columns FROM users WHERE field != 'created_on'");

$result = mysqli_query($con, "SELECT * FROM webhook_event_transaction JOIN user_virtual_account ON webhook_event_transaction.tx_ref = user_virtual_account.virtual_account_tx_ref JOIN user ON user.user_id = user_virtual_account.user_id WHERE (user_virtual_account.user_id = '$session_logged_in_user_id' AND webhook_event_transaction.webhook_event_transaction_status = 'used' AND webhook_event_transaction.event_type_created_at BETWEEN '" . $filter_start_date . "' AND  '" . $filter_end_date . "')"."ORDER by webhook_event_transaction.webhook_event_transaction_id ASC") or die("database error:". mysqli_error($con));
///ORDER by webhook_event_transaction.webhook_event_transaction_id DESC
//$header = mysqli_query($con, "SHOW columns FROM webhook_event_transaction WHERE field = 'webhook_event_transaction_id' AND field = 'customer_name' AND field='tx_ref' AND field='charged_amount'");

if (mysqli_num_rows($result)<=0) {
    // this shows no data for the user.
    exit();
    echo "<script>alert('No data record for virtual transactions');
    </script>";
    header("refresh:1,url=../virtual-transaction");
}
else{
/////proceed please
$fetch_res = mysqli_fetch_array($result);

////get the tx_ref from what we fetched above
$the_cust_id = $fetch_res['virtual_account_tx_ref'];
$query_total_cr = mysqli_query($con, "SELECT * FROM webhook_event_transaction WHERE (tx_ref = '$the_cust_id' AND webhook_event_transaction.event_type_created_at BETWEEN '" . $filter_start_date . "' AND  '" . $filter_end_date . "')");

foreach ($query_total_cr as $total_cr) {
    $qty_cr += $total_cr['charged_amount'];

   // continue;
}

$pdf = new PDF();
//header
$pdf->AddPage();
//foter page
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',12);
/*foreach($header as $heading) {
$pdf->Cell(40,12,$display_heading[$heading['Field']],1);
}*/

$pdf->Cell(100,12,"NAME:- ".strtoupper($fetch_res['fullname'])."",1);
$pdf->Cell(75,12,"VIRTUAL ACCOUNT:- ".$fetch_res['user_vt_acct_account_number']."",1);
$pdf->Ln(2);
$pdf->Ln();
$pdf->Cell(40,12,"CURRENCY:- ".strtoupper($fetch_res['currency'])."",1);
$pdf->Cell(98,12,"CUST ID:- ".$fetch_res['virtual_account_tx_ref']."",1);
$pdf->Cell(52,12,"TOTAL CR:- N".number_format($qty_cr)."",1);
$pdf->Ln();
$pdf->Ln();



$pdf->Cell(22,12,'Date',1);
$pdf->Cell(23,12,'Txn ID',1);
$pdf->Cell(65,12,'Txn Ref',1);
$pdf->Cell(23,12,'Amount',1);
$pdf->Cell(32,12,'Payment Type',1);
$pdf->Cell(25,12,'Status',1);

///40,1


//$pdf->Cell($width_cell[0],10,'Sl No',1,0,C,true);
/*foreach($result as $row) {
$pdf->SetFont('Arial','',10);
$pdf->Ln();
//foreach($row as $column)
$pdf->Cell(35,10,'ddkdk',1);
$pdf->Cell(35,10,$result['webhook_transaction_id'],1);
$pdf->Cell(35,10,$row['webhook_transaction_id'],1);
$pdf->Cell(35,10,$row['webhook_transaction_id'],1);
}*/

//while ($fetch_r = mysqli_fetch_array($result)) {
foreach ($result as $fetch_r) {
    // code...
$pdf->SetFont('Arial','',10);
$pdf->Ln();
//foreach($row as $column)

$pdf->Cell(22,12,date("d/m/Y",strtotime($fetch_r['event_type_created_at'])),1);
$pdf->Cell(23,12,$fetch_r['webhook_transaction_id'],1);
$pdf->Cell(65,12,$fetch_r['flw_ref'],1);
$pdf->Cell(23,12,number_format($fetch_r['charged_amount']),1);
$pdf->Cell(32,12,'Bank Transfer',1);
$pdf->Cell(25,12,$payment_status,1);


}
$pdf->Output();


}
?>
