<?
global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

$db->update('invoice_docs', array('status'=>$URI_TO_CTL[2] ), array('doc_number'=>$URI_TO_CTL[1]));




print $db->last_query;



print json_encode(array());
