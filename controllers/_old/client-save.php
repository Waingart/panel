<?

error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');

global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');


var_dump($_POST);

if($_POST['action']=='new'){
  $data['title'] = $_POST['customer'];
    $data['director'] = $_POST['director'];
    $data['email'] = $_POST['email'];
    $data['tel'] = $_POST['tel'];
  $db->insert('clients', $data);
}else{

    $data['title'] = $_POST['customer'];
    $data['director'] = $_POST['director'];
    $data['email'] = $_POST['email'];
    $data['tel'] = $_POST['tel'];
  $db->update('clients', $data, array('client_id'=>$_POST['client_id']));
}

