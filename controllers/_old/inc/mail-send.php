<?
//require_once('config.php');
 
require_once('PHPMailer-master/class.phpmailer.php');
require_once('PHPMailer-master/class.smtp.php');

function send_invoice($toemail, $attach_files, $mailsubj, $mailtext){
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
	$mail->FromName = 'Абеляр Медиа';
	$mail->addAddress($toemail);
	foreach($attach_files as $att_file){
		$mail->addAttachment($att_file['path'], $att_file['name']);
	}
	
	$mail->isHTML(true);
	 
	$mail->Subject = $mailsubj;
	$mail->Body = $mailtext;

		  
	if( $mail->send() ){
	   echo 'Письмо отправлено';
	}else{
	   echo 'Ошибка: ' . $mail->ErrorInfo;
	}
	
}
/*
$mail = new PHPMailer;
$mail->SMTPDebug = 2;
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
$mail->FromName = 'Абеляр Медиа';
$mail->addAddress('wainstan@ya.ru');
 
$mail->isHTML(true);
 
$mail->Subject =  $_POST['mailsubj'];
$mail->Body = $_POST['mailtext'];

      
if( $mail->send() ){
   echo 'Письмо отправлено';
}else{
   echo 'Ошибка: ' . $mail->ErrorInfo;
}
var_dump($_POST);
*/