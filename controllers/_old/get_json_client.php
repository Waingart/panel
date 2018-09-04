<?
global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

if($URI_TO_CTL[1]){
  $client = $db->select('clients', array('client_id'=>$URI_TO_CTL[1])); // ToDo: исправить косяк с безопасностью
  $client = $client[0];

}


print json_encode($client, true);
//var_dump($invoice);

?>