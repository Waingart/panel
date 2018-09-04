<?
require_once('/var/www/www-root/data/www/manager.abelar.ru/classes/db.class.php');

$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');


$int_id = $db->select('delayed_tasks', array('service'=>'tochka', 'tasktype'=>'gethistory'));
$access_token = $db->select('delayed_tasks', array('service'=>'tochka', 'tasktype'=>'updtoken'));
$int_id = $int_id[0]['id_data'];
$access_token= $access_token[0]['id_data'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.tochka.com/ws/do/R0101");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS,     "<message_v1 type=\"request\" int_id=\"".$int_id."\">
    <data trn_code=\"R0101\"></data>
    </message_v1>");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/xml",
  "Authorization: Bearer  ".$access_token,
  "Accept: application/xml"
));

$response = curl_exec($ch);
curl_close($ch);
//var_dump($response);
$xml = new SimpleXMLElement($response);

//var_dump($xml->data->statement_response_v1->days[0]->day[1]);
foreach($xml->data->statement_response_v1->days[0]->day as $day){
    foreach($day->records[0] as $records){
        print $records[0]['purpose'].'<br>';
        
        $data['amount'] = $records[0]['sum'];
            $data['pay_date'] = $records[0]['origin_date'];
            $data['account'] = 2;
            $data['target'] = 1;
            $data['inpay'] = $records[0]['debit']=="true"?1:2;
            $data['pay_descr'] = $records[0]['purpose'];
            $data['oper_id'] = intval(str_replace(";", "", $records[0]['core_banking_id']));
            
            $db->insert('payments', $data);
    }
    
}