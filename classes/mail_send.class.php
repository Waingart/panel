<?
//require_once('config.php');
 
require_once('PHPMailer-master/class.phpmailer.php');
require_once('PHPMailer-master/class.smtp.php');

class mail_send {
    function sendmail($toemail, $attach_files=0, $mailsubj='', $mailtext=''){
        $mail = new PHPMailer;
    	//$mail->SMTPDebug = 2;
    	$mail->isSMTP();
    	$mail->Host = 'ssl://smtp.yandex.ru';
    	$mail->SMTPAuth = true;
    
    	$mail->Username = 'bill@abelar.ru';
    	$mail->Password = '8v3VmDGIedii';
    	$mail->SMTPSecure = 'ssl';
    	//$mail->SMTPSecure = 'tls';
    	$mail->Port = 465;
    
    	$mail->CharSet = 'UTF-8';
    	$mail->SetFrom = 'bill@abelar.ru';
    	$mail->SetFrom('bill@abelar.ru');
    	$mail->FromName = 'ООО "Ингрид" - управляющая компания';
    	$mail->addAddress($toemail);
    	if(is_array($attach_files)){
    	    foreach($attach_files as $att_file){
        		$mail->addAttachment($att_file['path'], $att_file['name']);
        	}
    	}
    	
    	
    	$mail->isHTML(true);
    	 
    	$mail->Subject = $mailsubj;
    	$mail->Body = $mailtext;
     $mail->send(); 
    	/*	  
    	if(){
    	   echo 'Письмо отправлено';
    	}else{
    	   echo 'Ошибка: ' . $mail->ErrorInfo;
    	}
    	*/
	
    }
}