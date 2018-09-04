<?
global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

$data = $db->select('invoice_docs', array('doc_number'=>$URI_TO_CTL[1]));
$data = $data[0];
$data['doc_number'] = Get_doc_last_number('invoice')+1; Update_doc_last_number('invoice');
$data['act_number'] = Get_doc_last_number('act')+1; Update_doc_last_number('act');
  
unset($data['invoice_id']);
$db->insert('invoice_docs', $data);

print json_encode(array());

function Get_doc_last_number($doctype){
	global $db;
	$number = $db->select("doc_nums", array('doctype'=>$doctype));
	return $number[0]['doc_last_num'];
}
function Update_doc_last_number($doctype){
	global $db;
	$nextnum = Get_doc_last_number($doctype)+1;
	
	$db->update("doc_nums", array('doc_last_num'=>$nextnum), array('doctype'=>$doctype));
	//var_dump($nextnum); exit();
}