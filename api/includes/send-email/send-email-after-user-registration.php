<?php
        
        // Email subject 
        $subject = 'Account Registration';  
        $message = "<img src='https://i.ibb.co/6NNW8qK/logo-1.png'>". "\r\n<br/><br/>"; 
        $message.= "Dear <b>".ucwords($fullname). "</b>,\r\n<br/><br/>"; 
        $message.= "Your Destiny Promoters Account has been created successfully but is inactive at the moment and you can not be allowed into the dashboard.". "\r\n<br/><br/>";
        $message.= "You need to make a payment of ₦2,000 for the registration fee before your Account can be activated and allowed to login into the dashboard.". "\r\n<br/><br/>";
        $message.= "You can transfer into the Destiny Promoters Bank Account manually or use the automated online payment gateway to pay for your registration fee and get your Account approved automatically after a successful payment.". "\r\n<br/><br/>";
        $message.= "Please find attached a PDF file with this email to read and know more about the Destiny Promoters Terms and Conditions.". "\r\n<br/><br/>"; 
        $message.= "If you encounter any error or issue(s), please don't hesitate to send us an email via <a href='mailto:support@noblemerrycompany.com'>support@noblemerrycompany.com</a> or call <a href='tel:+2347025060073'>+2347025060073</a> OR <a href='tel:+2347025060074'>+2347025060074</a> ". "\r\n<br/><br/>";   
        
        /*footer*/ 
         $message.= "Best Regards,"."\n<br>";      
         $message.= "Customer Support"."\n<br>";
         $message.= "<b>Noblemerrycompany.com</b>"."\n<br><br>";


// Recipient 
$to = $email; 
 
// Sender 
$from = 'registration@noblemerrycompany.com'; 
$fromName = 'Destiny Promoters '; 
 
// Attachment file 
$file = "files/terms.pdf"; 
 
// Email body content 
$htmlContent = $message; 
 
// Header for sender info 
$headers = "From: $fromName"." <".$from.">"; 
//$headers .= "\nCc: support@noblemerrycompany.mco"; 
$headers .= "\nBcc: kazeemoyetoro@yahoo.com";
 
// Boundary  
$semi_rand = md5(time());  
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
 
// Headers for attachment  
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
// Multipart boundary  
$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  
 
// Preparing attachment 
if(!empty($file) > 0){ 
    if(is_file($file)){ 
        $message .= "--{$mime_boundary}\n"; 
        $fp =    @fopen($file,"rb"); 
        $data =  @fread($fp,filesize($file)); 
 
        @fclose($fp); 
        $data = chunk_split(base64_encode($data)); 
        $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
        "Content-Description: ".basename($file)."\n" . 
        "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
    } 
} 
$message .= "--{$mime_boundary}--"; 
$returnpath = "-f" . $from; 
 
// Send email 
$mail = @mail($to, $subject, $message, $headers, $returnpath);  

?>