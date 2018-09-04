<?
if (!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /');
  exit();
}

global $access_config;
$access_config = json_decode('{"get_table":{"allow_action":[2],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"record_allow_view":[2]},"get_it":{"allow_action":[2],"deny_action":[0],"field_disallow_view":{"1":["id"],"2":[]},"record_allow_view":[2]},"update":{"allow_action":[2,1],"deny_action":[0],"field_disallow_update":{"1":[],"2":[]},"record_allow_update":[2]},"modal_editor":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"field_disallow_update":{"1":[],"2":[]}}}', true);
global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);
$services = new services();
$services->run($URI_TO_CTL[0]);

class services {
  private $db = 0;
  public

  function services()
  {
    global $db;
    $this->db = $db;
  }

  function run($run_action)
  {
    switch ($run_action) {
    case 'update' : $this->update();
    break;

  case 'delete':
    $this->delete();
    break;

  case 'add_new':
    $this->add_new();
    break;

  case 'get_list':
    $this->get_list();
    break;

  case 'get_it':
    $this->get_it();
    break;

  case 'get_table':
    $this->get_table();
    break;

  default:
    global $act_id;
    $act_id = services;
    include ('servicesView.php');

    break;
  }
}

function update()
{
  global $access_config;
  if (!check_action_allow($access_config, 'update')) {
    print 'action not allowed';
    exit();
  }

  global $db;
  $data['title'] = $_POST['title'];
  $data['descr'] = $_POST['descr'];
  $data['soc'] = $_POST['soc'];
  $data['stype'] = $_POST['stype'];
  $data['ed'] = $_POST['ed'];
  $data['ed_cost'] = $_POST['ed_cost'];
  $data['smin'] = $_POST['smin'];
  $data['smax'] = $_POST['smax'];
  $data['available'] = $_POST['available'];
  $data['configs'] = $_POST['configs'];
  $data = filter_field_disallow_update($access_config, 'update', $data);
  $where = array(
    'id' => $_POST['id']
  );
  $allowed = check_record_allow_update($access_config, 'update', $where);
  if ($allowed) $this->db->update('services', $data, $where);
  exit();
}

function delete()
{
  global $access_config;
  if (!check_action_allow($access_config, 'delete')) {
    print 'action not allowed';
    exit();
  }

  global $URI_TO_CTL;
  global $db;
  $where = array(
    'id' => $URI_TO_CTL[1]
  );
  $where = check_record_allow_update($access_config, $action, $where);
  if ($where) $this->db->delete('services', $where);
  exit();
}

function add_new()
{
  global $db;
  $data['title'] = $_POST['title'];
  $data['descr'] = $_POST['descr'];
  $data['soc'] = $_POST['soc'];
  $data['stype'] = $_POST['stype'];
  $data['ed'] = $_POST['ed'];
  $data['ed_cost'] = $_POST['ed_cost'];
  $data['smin'] = $_POST['smin'];
  $data['smax'] = $_POST['smax'];
  $data['available'] = $_POST['available'];
  $data['configs'] = $_POST['configs'];
  $this->db->insert('services', $data);
  exit();
}

function get_table()
{
  global $access_config;
  if (!check_action_allow($access_config, 'get_table')) {
    print 'action not allowed';
    exit();
  }

  $db_settings = array(
    'rdbms' => 'MYSQLi',
    'db_server' => Config::$db_host,
    'db_user' => Config::$db_user,
    'db_passwd' => Config::$db_pass,
    'db_name' => Config::$db_name,
    'db_port' => '3306',
    'charset' => 'utf8',
    'use_pst' => true,
    'pst_placeholder' => 'question_mark'
  );
  $ds = new dacapo($db_settings, null);
  $get_fields[] = 'title';
  $get_fields[] = 'descr';
  $get_fields[] = 'soc';
  $get_fields[] = 'stype';
  $get_fields[] = 'ed';
  $get_fields[] = 'ed_cost';
  $get_fields[] = 'smin';
  $get_fields[] = 'smax';
  $get_fields[] = 'available';
  $get_fields[] = 'configs';
  $get_fields[] = 'id';
  $get_fields = filter_field_disallow_view($access_config, 'get_table', $get_fields);
  $where = check_record_allow_view($access_config, 'get_table', $where); // TODO: мы должны принимать условия where из post или get запроса, чтобы отображать данные таблицы только по условию
  if (!$where) {
    print 'diallowed record for you';
    exit();
  }

  global $db;
  $page_settings = array(
    "selectCountSQL" => "select count(*) as totalrows from `services`",
    "selectSQL" => $this->db->pre_select('services', $where, $get_fields) ,
    "page_num" => $_POST['page_num'],
    "rows_per_page" => $_POST['rows_per_page'],
    "columns" => $_POST['columns'],
    "sorting" => isset($_POST['sorting']) ? $_POST['sorting'] : array() ,
    "filter_rules" => isset($_POST['filter_rules']) ? $_POST['filter_rules'] : array()
  );
  $jfr = new jui_filter_rules($ds);
  $jdg = new bs_grid($ds, $jfr, $page_settings, $_POST['debug_mode'] == "yes" ? true : false);
  $data = $jdg->get_page_data();

  // data conversions (if necessary)

  foreach($data['page_data'] as $key => $row) {

    // your code here

    $row_soc[1] = 'VK';
    $row_soc[2] = 'INSTAGRAM';
    if (isset($row_soc)) {
      $row['soc'] = $row_soc[$row['soc']];
    }

    $row_stype[1] = 'Лайки';
    $row_stype[2] = 'Автолайки';
    $row_stype[3] = 'Подписки';
    $row_stype[4] = 'Просмотры видео';
    if (isset($row_stype)) {
      $row['stype'] = $row_stype[$row['stype']];
    }

    if (isset($row_ed_cost)) {
      $row['ed_cost'] = $row_ed_cost[$row['ed_cost']];
    }

    $row['ed_cost'] = $row['ed_cost'] . '₽';
    $row_available[1] = 'Да';
    $row_available[2] = 'Нет';
    if (isset($row_available)) {
      $row['available'] = $row_available[$row['available']];
    }

    $row['actions'].= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#editModal\" data-url=\"\" data-whatever=\"{$row['id']}\"><i class=\"fa fa-clock\"></i></button>";
    $data['page_data'][$key] = $row;
  }

  echo json_encode($data);
  exit();
}

		function get_list () {
		    global $access_config;
			if(!check_action_allow($access_config, 'get_list')){
				print 'action not allowed';
				exit();
			}
			global $URI_TO_CTL;
			
			
			$get_fields[] = 'title';
$get_fields[] = 'descr';
$get_fields[] = 'soc';
$get_fields[] = 'stype';
$get_fields[] = 'ed';
$get_fields[] = 'ed_cost';
$get_fields[] = 'smin';
$get_fields[] = 'smax';
$get_fields[] = 'available';
$get_fields[] = 'configs';
$get_fields[] = 'id';
	
	
			  		
			  $get_fields = filter_field_disallow_view($access_config, 'get_list', $get_fields);
			  $where = '';
			  if($_POST['action'] == 'get-services'){
			    $where['stype'] = $_POST['type'];
			    $where['soc'] = $_POST['soc'];
			    
			    $services = $this->db->select('services', $where, $get_fields); 
                foreach($services as $sv){
                    print "<option value=\"{$sv['id']}\"> {$sv['id']}. {$sv['title']} - {$sv['ed_cost']}₽ за {$sv['ed']} шт</option>";
                }
			  }
			  
			 if($_POST['action'] == 'get-price'){
			     $where['id'] = $_POST['service-id'];

			     $service_price = $this->db->select('services', $where, array('ed', 'ed_cost')); 
			     $service_price = $service_price[0];
			     $one_cost = $service_price['ed_cost'] / $service_price['ed'];
			     $order_cost = $one_cost * intval($_POST['quantity']);
			     print $order_cost;
			 }
			  /*
			  
			  */
			  ///$where = check_record_allow_view($access_config, 'get_list', $where);
			  //var_dump($where);
			  //if($where !== false)
			  
			
			
		//	print json_encode($services, true);
			exit();
		}

function get_it()
{
  global $access_config;
  if (!check_action_allow($access_config, 'get_it')) {
    print 'action not allowed';
    exit();
  }

  global $URI_TO_CTL;
  $get_fields[] = 'title';
  $get_fields[] = 'descr';
  $get_fields[] = 'soc';
  $get_fields[] = 'stype';
  $get_fields[] = 'ed';
  $get_fields[] = 'ed_cost';
  $get_fields[] = 'smin';
  $get_fields[] = 'smax';
  $get_fields[] = 'available';
  $get_fields[] = 'configs';
  $get_fields[] = 'id';
  $get_fields = filter_field_disallow_view($access_config, 'get_it', $get_fields);
  if ($URI_TO_CTL[1]) {
    global $db;
    $where = array(
      'id' => $URI_TO_CTL[1]
    );
    $allowed = check_record_allow_view($access_config, 'get_it', $where);
    if ($allowed) {
      $services = $this->db->select('services', $where, $get_fields);
      print json_encode($services[0], true);
      exit();
    }
  }
}
}