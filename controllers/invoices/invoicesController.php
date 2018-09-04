<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

global $access_config;
$access_config = json_decode('{"get_table":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"field_disallow_update":{"1":[],"2":[]}},"delete":{"allow_action":[2],"deny_action":[0],"field_disallow_view":{"1":["id"],"2":[]},"record_allow_view":[2]},"status":{"allow_action":[2,1],"deny_action":[0],"field_disallow_update":{"1":[],"2":[]},"record_allow_update":[2]},"filldata":{"allow_action":[2,1],"deny_action":[0],"field_disallow_update":{"1":[],"2":[]},"record_allow_update":[2]},"save":{"allow_action":[2,1],"deny_action":[0],"field_disallow_update":{"1":[],"2":[]},"record_allow_update":[2]},"get-invoice-pdf":{"allow_action":[2,1],"deny_action":[0],"field_disallow_update":{"1":[],"2":[]},"record_allow_update":[2]},"get-act-pdf":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"field_disallow_update":{"1":[],"2":[]}}}', true);



global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

$invoices = new invoices();

$invoices->run($URI_TO_CTL[0]);

class invoices { 
    private $db = 0;
    public function invoices(){
        global $db;
        $this->db = $db;
    }
    
    function run($run_action){ 
        switch ($run_action) {		case 'update':
        $this->update();
        break;	
		case 'delete':
        $this->delete();
        break;
        case 'filldata':
        $this->filldata();
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
		    $act_id = invoices;
            include('invoicesView.php');
        break;
}
    }
    
			function update () {
									    global $access_config;
				if(!check_action_allow($access_config, 'update')){
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
				
				$where = array('id'=>$_POST['id']);
				$allowed = check_record_allow_update($access_config, 'update', $where);
				if($allowed)
					$this->db->update('invoices', $data, $where);
						exit();
		}
			function delete () {
									    global $access_config;
				if(!check_action_allow($access_config, 'delete')){
					print 'action not allowed';
					exit();
				}
				
				global $URI_TO_CTL;
				global $db;

				$where = array('id'=>$URI_TO_CTL[1]);
				$where = check_record_allow_update($access_config, $action, $where);
				if($where)
					$this->db->delete('invoices', $where);
						exit();
		}
		function filldata () {
		    global $URI_TO_CTL;
		    if($URI_TO_CTL[1]){
              $invoice = $this->db->select('invoice_docs', array('doc_number'=>$URI_TO_CTL[1])); // ToDo: исправить косяк с безопасностью
              $invoice = $invoice[0];
              $invoice['service_details'] = json_decode($invoice['service_details'], true);
            }
            $services = $this->db->select('list_services');
            $clients = $this->db->select('clients');


            $invoice['customer_list'] = $clients;
            $invoice['services_list'] = $services;
            
            
            print json_encode($invoice, true);
		}
			function add_new () {
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
				$this->db->insert('invoices', $data);
									exit();
		}
			function get_table (){
		global $access_config;
			if(!check_action_allow($access_config, 'get_table')){
				print 'action not allowed';
				exit();
			}
				
			$db_settings = array(
				'rdbms' => 'MYSQLi',
				'db_server' => 	Config::$db_host,
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
			
			/*
			$where = check_record_allow_view($access_config, 'get_table', $where); // TODO: мы должны принимать условия where из post или get запроса, чтобы отображать данные таблицы только по условию
			if(!$where){
				print 'diallowed record for you'; exit();
			}
			*/
			global $db;
			$page_settings = array(
	"selectCountSQL" => "select count(*) as totalrows from `invoice_docs`",    // CONFIGURE
	"selectSQL" => "select `invdoc`.`doc_number` as doc_id, `cl`.`title` as client,  `invdoc`.`total` as amount, `invdoc`.`invoice_date` as doc_data, `invdoc`.`status` as status, `invdoc`.`doc_number` as actions  from `invoice_docs` `invdoc` 
	JOIN `clients` `cl`  ON (`invdoc`.`client_id` = `cl`.`client_id`)",  // CONFIGURE

/*
SELECT c.id as customer_id, c.lastname, c.firstname, c.email, g.gender, c.date_updated
                  FROM customers c INNER JOIN lk_genders g ON (c.lk_genders_id = g.id)
				  
{field: "doc_id", header: "№ Счета"},
            {field: "client", header: "Клиент"},
            {field: "amount", header: "Сумма"},
			{field: "doc_data", header: "Дата счета"},
            {field: "status", header: "Статус"},
            {field: "actions", header: "Операции"},
			*/
	
	"page_num" => $_POST['page_num'],
	"rows_per_page" => $_POST['rows_per_page'],
	"columns" => $_POST['columns'],
	"sorting" =>  isset($_POST['sorting']) ? $_POST['sorting'] : array(),
	"filter_rules" => isset($_POST['filter_rules']) ? $_POST['filter_rules'] : array()
);

$jfr = new jui_filter_rules($ds);
$jdg = new bs_grid($ds, $jfr, $page_settings, $_POST['debug_mode'] == "yes" ? true : false);

$data = $jdg->get_page_data();

// data conversions (if necessary)
foreach($data['page_data'] as $key => $row) {
	// your code here

	$statuses[0] = 'Не отправлен';
	$statuses[1] = 'Отправлен';
	$statuses[2] = 'Оплачен';
	$statuses[3] = 'Отменен';
	
	$row['status'] = '<a href="#'.$row['actions'].'" data-toggle="modal" data-target="#ChangeStatusModal" data-whatever="'.$row['actions'].'" >'.$statuses[$row['status']].'</a>';

    $row['actions'] = "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#myModal\" data-whatever=\"{$row['actions']}\"><i class=\"fa fa-edit\"></i></button>
    <button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#DownloadModal\" data-whatever=\"{$row['actions']}\"><i class=\"fa fa-cloud-download\" ></i></button>
    <button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#EmailModal\" data-whatever=\"{$row['actions']}\"><i class=\"fa fa-envelope\" ></i></button>
    <button class=\"btn btn-primary btn-xs clone\" data-whatever=\"{$row['actions']}\"><i class=\"fa fa-clone\" ></i></button>
	<button class=\"btn btn-primary btn-xs trash\" data-whatever=\"{$row['actions']}\"><i class=\"fa fa-trash\" ></i></button>";
    
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
			  $where = check_record_allow_view($access_config, 'get_list', $where);
			  if($where)
			  $invoices = $this->db->select('invoices', $where, $get_fields); 

			global $db;
			
			print json_encode($invoices, true);
			exit();
		}
		function get_it () {
		    global $access_config;
			if(!check_action_allow($access_config, 'get_it')){
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
			if($URI_TO_CTL[1]){
				global $db;
				
				
				$where = array('id'=>$URI_TO_CTL[1]);
				$allowed = check_record_allow_view($access_config, 'get_it', $where);
				if($allowed){
					$invoices = $this->db->select('invoices', $where, $get_fields); 
					print json_encode($invoices[0], true);
					exit();
				}
			}
		}
}
