<?php ini_set('display_errors', 1); ?> 
<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.tochka.com/auth/oauth/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, 'username=stanislav@waingart.com&password=roti5599&grant_type=password');

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/x-www-form-urlencoded",
  "Authorization: Basic aXBob25lYXBwOnNlY3JldA=="
));

$response = curl_exec($ch);
curl_close($ch);
$token = json_decode ( $response, 1);

print "access_token:". $token['access_token']."<br>";
//-----------------------


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.tochka.com/ws/do/R0100");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);
$datefrom = new DateTime('-4 days');
$date = new DateTime();
$from = $datefrom->format('Y-m-d').'T'.$datefrom->format('H:m:i') ;
$to = $date->format('Y-m-d').'T'.$date->format('H:m:i') ;

curl_setopt($ch, CURLOPT_POSTFIELDS, "<message_v1 xmlns=\"http://www.anr.ru/types\" type=\"request\">
<data trn_code=\"R0100\">
<statement_request_v1 xmlns=\"http://www.anr.ru/types\" account_id=\"40702810010500000405\"  start_date=\"".$from."+02:00\" end_date=\"".$to."+02:00\">
</statement_request_v1>
</data>
</message_v1>");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/xml",
  "Authorization: Bearer  ".$token['access_token'],
  "Accept: application/xml"
));

$response = curl_exec($ch);
curl_close($ch);

$xml = new SimpleXMLElement($response);

print "int_id: ".$xml["int_id"][0]."<br>";
require_once('/var/www/www-root/data/www/manager.abelar.ru/classes/db.class.php');

$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
$db->delete('delayed_tasks');

$data['service'] = 'tochka';
$data['tasktype'] = 'gethistory';
$data['id_data'] = $xml["int_id"][0];
$data['upd_time'] = time();
$db->delete('delayed_tasks');
$db->insert('delayed_tasks', $data);

$data['service'] = 'tochka';
$data['tasktype'] = 'updtoken';
$data['id_data'] = $token['access_token'];
$data['upd_time'] = time();

$db->insert('delayed_tasks', $data);

// -------------------

//print "<a href='get2.php?int_id=".$xml["int_id"][0]."&access_token=".$token['access_token']."'>get2</a>";

