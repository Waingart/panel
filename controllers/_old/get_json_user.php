<?
global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

if($URI_TO_CTL[1]){
  $client = $db->query('select `id`, 
	`username`,  
	`email`, 
	`mobile`,
	`fio`
	from `users` where id='.$URI_TO_CTL[1]);

}


print json_encode($client[0], true);
//var_dump($invoice);

?>