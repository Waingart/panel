<?
global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

$db->update('clients', array('trash'=>'1'), array('client_id'=>$URI_TO_CTL[1]));
print $db->lastquery;
print 'aaaaa'.$URI_TO_CTL[1];
print json_encode(array());
