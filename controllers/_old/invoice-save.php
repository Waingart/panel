<?

error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');

global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');


//print strtotime("02/29/2016");

/* Подключаем модуль склонения числительных	*/
include("text-morph.php");
/* Берем из запроса сумму Итого */
$array['total'] = $_POST['total'];

/* Создаем сумму прописью и сохраняем в $array['text_total'] */
//$summ_text = sklon($_POST['total']);
$summ_text = num2str($_POST['total']);
//$array['text_total'] = $summ_text['n']['i'].' '.$summ_text['unit']['i'];
$array['text_total'] = $summ_text;

/* Берем из запроса номер акта и счета */
$array['invoice_number'] = $_POST['inv_num'];
$array['act_number'] = $_POST['act_num'];
//print strtotime(str_replace("/", "-", $_POST['doc_date']));

/* Берем из запроса дату выставления */
//print str_replace("/", "-", $_POST['doc_date']);
$doc_date = strtotime($_POST['doc_date']);

/* Создаем удобочитаемую дату для документов: "24" апреля 2017 г. */
if($_POST['doc_date']){
	print "!!!date exist!!!!";
	$array['invoice_date_text'] = '"'.date('j', $doc_date).'" '.getRusMonth(date('n', $doc_date)).' '.date('Y', $doc_date).' г.';
	$array['invoice_date'] = $_POST['doc_date'];
}else{
	print "!!!date not exist!!!!";
	$array['invoice_date_text'] = '"'.date('j', time()).'" '.getRusMonth(date('n', time())).' '.date('Y', time()).' г.';
	$array['invoice_date'] = date("Y-m-d");
}

/* Берем из запроса наименование клиента */		
$array['customer'] = get_client_name($_POST['customer']);
	

/* Класс "услуга" */
class MyService {
	public $title;
	public $paid_period;
	public $ed;
	public $price;
	public $count;
	public $total;
	public $service_id;
}
/* Класс "счет" */
class Invoice {
	public $number;
	public $act_number;
	public $date;
	public $total;
	public $services;
	public $client_id;
	public $client_name;
	
	public function AddService($service){
		$this->services[] = $service;
		foreach ($this->services as $s){
			$this->total += $s->total;
		}
	}
}

/* Создаем новый "счет" */
  $invoice = new Invoice();
  $invoice->number = $_POST['inv_num'];
  $invoice->act_number = $_POST['act_num'];
  $invoice->client_id = $_POST['customer'];
  $invoice->client_name = get_client_name($_POST['customer']);
  $invoice->date = $array['invoice_date'];

    
foreach($_POST['service'] as $num => $line){
	$service_title = $_POST['title'][$num];
 /* if(is_numeric($_POST['service'][$num])){
    $service_title = get_service_title($_POST['service'][$num]);
  }else{
    $service_title = $_POST['title'][$num];
  }
*/
	$array['order_list'][] = array(
		'title'=> $service_title,
		'ed'=>$_POST['metric'][$num],
		'count'=>$_POST['count'][$num],
		'price'=>$_POST['price'][$num],
		'linetotal'=>$_POST['rowtotal'][$num]
	);
  
  $service = new MyService();
  $service->service_id = $_POST['service'][$num];

  $service->title = $service_title;
  if($res_val)
	$service->paid_period = ' (до '.date("d.m.y", $res_val).')';
  $service->ed = $_POST['metric'][$num];
  $service->price = $_POST['price'][$num];

  $service->count = $_POST['count'][$num];
  $service->total = $_POST['count'][$num]*$_POST['price'][$num];
  
  $invoice->AddService($service);
  

}

  $data = array(
  'doc_number' => $_POST['inv_num'],
  'act_number' => $_POST['act_num'],
  'invoice_date' => $array['invoice_date'],
  'total' => $_POST['total'],
  'client_id' => $_POST['customer'],
  'service_details' => json_encode($invoice->services)
);

if($_POST['action']=='new'){
    if($data['doc_number']=='#auto')
        $data['doc_number'] = Get_doc_last_number('invoice')+1; Update_doc_last_number('invoice');
    if($data['act_number']=='#auto')
    $data['act_number'] = Get_doc_last_number('act')+1; Update_doc_last_number('act');
  $db->insert('invoice_docs', $data);
}elseif($_POST['action']=='update'){
  var_dump($data);
  $db->update('invoice_docs', $data, array('doc_number'=>$_POST['inv_num']));
}


$json_data = json_encode($array);
//file_put_contents("array.json", $json_data);

function get_client_name($client_id){
	global $db;
	$client_name = $db->query("select `title` FROM `clients` WHERE `client_id` = $client_id");
	return $client_name[0]['title'];
}
function get_client_emails($client_id){
	global $db;
	$client_emails = $db->query("select `email1` FROM `contact_persones` WHERE `client_id` = $client_id");
	return $client_emails;
}
function get_service_title($service_id){
	global $db;
	$db_service = $db->select('list_services', array('service_id'=>$service_id));
	return $db_service[0]['title'];;
}
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