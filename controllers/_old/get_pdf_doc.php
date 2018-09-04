<?
global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

include("text-morph.php");
include('act_inv_save.php');

function get_client_name($client_id){
	global $db;
	$client_name = $db->query("select `title` FROM `clients` WHERE `client_id` = $client_id");
	return $client_name[0]['title'];
}
function get_client_email($client_id){
	global $db;
	$client_name = $db->query("select `clients`.`email` FROM `clients`WHERE `clients`.`client_id` = $client_id");
	return $client_name[0]['email'];
}

if($URI_TO_CTL[1]){
  $invoice = $db->select('invoice_docs', array('doc_number'=>$URI_TO_CTL[1])); // ToDo: исправить косяк с безопасностью
  $invoice = $invoice[0];

  $array['total'] = $invoice['total'];
  //$summ_text = sklon($invoice['total']); // num2str
    $summ_text = num2str($invoice['total']); // num2str
    
  //$array['text_total'] = $summ_text['n']['i'].' '.$summ_text['unit']['i'];
  $array['text_total'] = $summ_text;
  
  $array['invoice_number'] = $invoice['doc_number']; 
  $array['act_number'] = $invoice['act_number']; 
  $timestamp = strtotime($invoice['invoice_date']);
  $array['invoice_date'] = '"'.date('j', $timestamp).'" '.getRusMonth(date('n', $timestamp)).' '.date('Y', $timestamp).' г.';; 
  $array['customer'] = get_client_name($invoice['client_id']); 
  $array['order_list'] = json_decode($invoice['service_details'], true);

  if(file_exists('test-docfiles/Invoice-'.$array['invoice_number'].'.pdf')){
      unlink('test-docfiles/Invoice-'.$array['invoice_number'].'.pdf');
      unlink('test-docfiles/Invoice-'.$array['invoice_number'].'.scan.pdf');
  }
    gen_pdf_inv($array, 'test-docfiles/Invoice-'.$array['invoice_number'].'.pdf');
  
  if(file_exists('test-docfiles/Act-'.$array['act_number'].'.pdf')){
      unlink('test-docfiles/Act-'.$array['act_number'].'.pdf');
      unlink('test-docfiles/Act-'.$array['act_number'].'.scan.pdf');
  }
    gen_pdf_inv($array, 'test-docfiles/Act-'.$array['act_number'].'.pdf', 'act');
  
  print json_encode(array(
  'client_mail'=>get_client_email($invoice['client_id']),
  'doc_number'=>$array['invoice_number'],
  'inv'=>array(
    'link'=>'/test-docfiles/Invoice-'.$array['invoice_number'].'.pdf', 
    'name'=>'Invoice-'.$array['invoice_number'].'.pdf'), 
    
  'act'=>array(
  'link'=>'/test-docfiles/Act-'.$array['act_number'].'.pdf', 
  'name'=>'Act-'.$array['act_number'].'.pdf'
  )
  ), true);
}




?>