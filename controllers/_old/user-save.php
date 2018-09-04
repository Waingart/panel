<?

error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');

global $db;
$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');


var_dump($_POST);

if($_POST['action']=='new'){
  $data['username'] = $_POST['username'];
    $data['fio'] = $_POST['fio'];
    $data['email'] = $_POST['email'];
    $data['mobile'] = $_POST['mobile'];
  $db->insert('users', $data);
}else{

    $data['username'] = $_POST['username'];
    $data['fio'] = $_POST['fio'];
    $data['email'] = $_POST['email'];
    $data['mobile'] = $_POST['mobile'];
  $db->update('users', $data, array('id'=>$_POST['user_id']));
}