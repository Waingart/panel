<?
global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');



  $invoice = $db->select('invoice_docs', array('doc_number'=>$_POST['doc_number'])); // ToDo: исправить косяк с безопасностью
  $invoice = $invoice[0];

  if(file_exists('test-docfiles/Invoice-'.$invoice['doc_number'].'.pdf')){
    $attach_files[] = array('path'=>'test-docfiles/Invoice-'.$invoice['doc_number'].'.pdf', 'name'=>'Счет '.$invoice['doc_number'].'.pdf');
  }
  if(file_exists('test-docfiles/Act-'.$invoice['act_number'].'.pdf')){
    //$attach_files[] = array('path'=>'test-docfiles/Act-'.$invoice['act_number'].'.pdf', 'name'=>'Акт '.$invoice['act_number'].'.pdf');
  }
 // print json_encode(array(), true);


include('inc/mail-send.php');
//$_POST['mailfrom'] = 'wainstan@ya.ru';
//var_dump($attach_files);
send_invoice($_POST['mailfrom'], $attach_files, $_POST['mailsubj'], $_POST['mailtext'])

?>