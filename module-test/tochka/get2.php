<?


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.tochka.com/ws/do/R0101");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS,     "<message_v1 type=\"request\" int_id=\"".$_GET["int_id"]."\">
    <data trn_code=\"R0101\"></data>
    </message_v1>");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/xml",
  "Authorization: Bearer  ".$_GET['access_token'],
  "Accept: application/xml"
));

$response = curl_exec($ch);
curl_close($ch);
var_dump($response);
$xml = new SimpleXMLElement($response);

//var_dump($xml->data->statement_response_v1->days[0]->day[1]);
foreach($xml->data->statement_response_v1->days[0]->day as $day){
    foreach($day->records[0] as $records){
        print $records[0]['purpose'].'<br>';
    }
    
}