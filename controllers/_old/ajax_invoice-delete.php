<?
global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

$db->delete('invoice_docs', array('doc_number'=>$URI_TO_CTL[1]));


print json_encode(array());
