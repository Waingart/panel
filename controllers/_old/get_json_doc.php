<?
global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

if($URI_TO_CTL[1]){
  $invoice = $db->select('invoice_docs', array('doc_number'=>$URI_TO_CTL[1])); // ToDo: исправить косяк с безопасностью
  $invoice = $invoice[0];
  $invoice['service_details'] = json_decode($invoice['service_details'], true);
}
$services = $db->select('list_services');
$clients = $db->select('clients');


$invoice['customer_list'] = $clients;
$invoice['services_list'] = $services;


print json_encode($invoice, true);
//var_dump($invoice);

?>