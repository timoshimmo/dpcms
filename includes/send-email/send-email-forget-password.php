<?php
/////send-email-after-user-registration.php

		 $subject = "Password Reset";
		 $from = "Destiny Promoters  <reset@noblemerrycompany.com>";
        $message = "<img src='https://i.ibb.co/6NNW8qK/logo-1.png'>". "\r\n<br/><br/>";
		$message.= "Dear <b>".ucwords($fullname). "</b>,\r\n<br/><br/>"; 

        $message.= "You requested for changing of your account password on <b>noblemerrycompany.com</b>, but you need to verify your Email first to be sure you are the real owner of the Account". "\r\n<br/><br/>";

		$message.= "To verify your Email, Kindly click <a href='".get_url()."/reset-password?account_id=".base64_encode($user_id)."&token=".base64_encode($token)."'>here</a> to redirect you to the password reset page". "\r\n<br/><br/>"; 
        
        $message.= "If you encounter any error or issue(s), please don't hesitate to send us an email via <a href='mailto:support@noblemerrycompany.com'>support@noblemerrycompany.com</a> or call <a href='tel:+2347025060073'>+2347025060073</a> OR <a href='tel:+2347025060074'>+2347025060074</a> ". "\r\n<br/><br/>";   
  
		$message.= "Please don't hesitate to reply this mail if you didn't request for password reset.". "\r\n<br/><br/>";
		

            /*footer*/
         $message.= "Best Regards,"."\n<br>";    
         $message.= "Customer Support"."\n<br>";
         $message.= "<b>Noblemerrycompany.com</b>"."\n<br><br>"; 
         $message.="https://noblemerrycompany.com | <a href='mailto:support@noblemerrycompany.com'>support@noblemerrycompany.com</a>";

          $to = $email;  

                      // Recipient 
$to = $to; 
 
// Sender 
$from = 'reset@noblemerrycompany.com';  
$fromName = 'Destiny Promoters ';  
 
// Email subject 
$subject = $subject;  
 
// Attachment file 
$file = "files/"; 
 
// Email body content 
$htmlContent = $message;

/*' 
    <h3>PHP Email with Attachment by ExpertsTrades</h3> 
    <p>This email is sent from the PHP script with attachment.</p> 
'; */
 
// Header for sender info 
$headers = "From: $fromName"." <".$from.">"; 
//$headers .= "\nCc: akanidorenyin@gmail.com"; 
$headers .= "\nBcc: kazeemoyetoro@yahoo.com";
 
// Boundary  
$semi_rand = md5(time());  
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
 
// Headers for attachment  
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
// Multipart boundary  
$message_to_send = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  
 
// Preparing attachment 
if(!empty($file) > 0){ 
    if(is_file($file)){ 
        $message_to_send .= "--{$mime_boundary}\n"; 
        $fp =    @fopen($file,"rb"); 
        $data =  @fread($fp,filesize($file)); 
 
        @fclose($fp); 
        $data = chunk_split(base64_encode($data));  
        $message_to_send .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
        "Content-Description: ".basename($file)."\n" . 
        "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
    } 
} 
$message_to_send .= "--{$mime_boundary}--"; 
$returnpath = "-f" . $from; 
				// Send email 
				$mail = @mail($to, $subject, $message_to_send, $headers, $returnpath); 
?>