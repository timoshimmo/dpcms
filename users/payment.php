<div style="display: none;">
<?php include("sidebar.php");
$flutter_email = (empty($fetch_user_details['email_address']) ? "flutter-payment@noblemerrycompany.com" : $fetch_user_details['email_address']);
?>
</div>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
</head>
<body>

  <form method="POST" action="https://checkout.flutterwave.com/v3/hosted/pay">
  <div> 
    amunt
  </div>
  <input type="hidden" name="public_key" value="<?php echo $api_public_key ?>" />
  <input type="hidden" name="customer[email]" value="<?php echo $flutter_email; ?>" />
  <input type="hidden" name="customer[name]" value="<?php echo $fetch_user_details['fullname']?>"/>
  <input type="hidden" name="tx_ref" value="<?php echo base64_encode('py_ref-'.time()); ?>" />
  <input type="hidden" name="amount"  value="<?php echo $_POST['payment-amount'] ?>" />
  <input type="hidden" name="currency" value="NGN" />
  <input type="hidden" name="meta[token]" value="54" />
  <input type="hidden" name="redirect_url" value="<?php echo get_url() ?>/users/payment-details?account_id=<?php echo base64_encode($_POST['account-id']) ?>&amount=<?php echo base64_encode($_POST['payment-amount']) ?>&payer=<?php echo base64_encode($_POST['user-id']) ?>"/>
  <button type="submit">Pay Now</button>
</form>

<script>
  document. addEventListener("DOMContentLoaded", () => {
    document.querySelector("button").click();  
  });

  
</script>
</body>
</html>