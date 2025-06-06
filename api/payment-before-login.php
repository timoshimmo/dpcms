<?php 

include("includes/session.php"); 
include("includes/config.php"); 
include("includes/db-functions.php"); 

?>
<?php
  if (!isset($_SESSION['session_logged_in_user_account_id']) || empty($_SESSION['session_logged_in_user_account_id'])  && !isset($_SESSION['session_logged_in_user_id']) || empty($_SESSION['session_logged_in_user_id']) ) {
  header("Location:signup");
}else{


  $session_logged_in_user_account_id = $_SESSION['session_logged_in_user_account_id'];
  $session_logged_in_user_id = $_SESSION['session_logged_in_user_id'];


  $fetch_user_details = query_user_details($session_logged_in_user_id);


  $flutter_email = (empty($fetch_user_details['email_address']) ? "flutter-payment@destinypromoterscooperative.com" : $fetch_user_details['email_address']);

}
?>


<!DOCTYPE html>
<html>
<head> 
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
</head>
<body> 

EDIT ON
<script src="https://checkout.flutterwave.com/v3.js"></script>
<form>
  <div>
    Your order is ₦1,000
  </div>
  <button type="button" id="start-payment-button" onclick="makePayment()">Pay Now</button>
</form>
<script>
  function makePayment() {
    FlutterwaveCheckout({
      public_key: "FLWPUBK-6f0fe6d5fa8bbf2de4c5760509446d44-X",
      tx_ref: "<?php echo base64_encode('py_ref-'.time()); ?>",
      amount: 1000,
      currency: "NGN",
      payment_options: "card, banktransfer, ussd",
      redirect_url: "<?php echo get_url() ?>/users/payment-details?account_id=<?php echo base64_encode($_POST['account-id']) ?>&amount=<?php echo base64_encode($_POST['payment-amount']) ?>&payer=<?php echo base64_encode($_POST['user-id']) ?>&type=<?php echo base64_encode('registration') ?>",
      customer: {
        email: "<?php echo $flutter_email; ?>",
        phone_number: "<?php echo $fetch_user_details['phone_no']?>",
        name: "<?php echo $fetch_user_details['fullname']?>",
      },
      customizations: {
        title: "DESTINY PROMOTERS COOPERATIVES",
        description: "Registration Payment",
        logo: "https://i.ibb.co/2NJ7pCS/dpcms-logo.png",
      },
      callback: function (data){
        console.log("payment callback:", data);
      },
      onclose: function() {
        console.log("Payment cancelled!");
      }
    });
  }
</script>

<script>
  document. addEventListener("DOMContentLoaded", () => {
    document.querySelector("button").click();  
  });

  
</script>
</body>
</html>