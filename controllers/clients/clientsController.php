<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

global $access_config;
$access_config = json_decode('{"get_table":{"allow_action":[2],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"record_allow_view":[2]},"get_it":{"allow_action":[2],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"record_allow_view":[2]},"modal_editor":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["client_id"],"2":["client_id"]}}}', true);



global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

$clients = new clients();

$clients->run($URI_TO_CTL[0]);

class clients { 
    private $db = 0;
    public function clients(){
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
		case 'add_new':
        $this->add_new();
        break;	
		case 'mailto':
        $this->mailto();
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
		    $act_id = clients;
            include('clientsView.php');
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
$data['director'] = $_POST['director'];
$data['email'] = $_POST['email'];
$data['tel'] = $_POST['tel'];
				$data = filter_field_disallow_update($access_config, 'update', $data);
				
				$where = array('client_id'=>$_POST['client_id']);
				$allowed = check_record_allow_update($access_config, 'update', $where);
				if($allowed)
					$this->db->update('clients', $data, $where);
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

				$where = array('client_id'=>$URI_TO_CTL[1]);
				$where = check_record_allow_update($access_config, $action, $where);
				if($where)
					$this->db->delete('clients', $where);
						exit();
		}
			function add_new () {
							global $db;

				$data['title'] = $_POST['title'];
$data['director'] = $_POST['director'];
$data['email'] = $_POST['email'];
$data['tel'] = $_POST['tel'];
				$this->db->insert('clients', $data);
									exit();
		}
			function mailto () {
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
$get_fields[] = 'director';
$get_fields[] = 'email';
$get_fields[] = 'tel';
$get_fields[] = 'client_id';
			$get_fields = filter_field_disallow_view($access_config, 'get_table', $get_fields);				
			
			$where = check_record_allow_view($access_config, 'get_table', $where); // TODO: мы должны принимать условия where из post или get запроса, чтобы отображать данные таблицы только по условию
			if(!$where){
				print 'diallowed record for you'; exit();
			}
			global $db;
			$page_settings = array(
				"selectCountSQL" => "select count(*) as totalrows from `clients`",  
				"selectSQL" => $this->db->pre_select('clients', $where, $get_fields),
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
            
            										$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#editModal\" data-url=\"\" data-whatever=\"{$row['client_id']}\"><i class=\"fa fa-edit\"></i></button>";
									$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#mailtoModal\" data-url=\"\" data-whatever=\"{$row['client_id']}\"><i class=\"fa fa-evenlope\"></i></button>";
									$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#deleteModal\" data-url=\"\" data-whatever=\"{$row['client_id']}\"><i class=\"fa fa-trash\"></i></button>";
									$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#statisticModal\" data-url=\"\" data-whatever=\"{$row['client_id']}\"><i class=\"fa fa-trash\"></i></button>";
				              
                
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
$get_fields[] = 'director';
$get_fields[] = 'email';
$get_fields[] = 'tel';
$get_fields[] = 'client_id';
	
	
			  		
			  $get_fields = filter_field_disallow_view($access_config, 'get_list', $get_fields);
			  $where = '';
			  $where = check_record_allow_view($access_config, 'get_list', $where);
			  if($where)
			  $clients = $this->db->select('clients', $where, $get_fields); 

			global $db;
			
			print json_encode($clients, true);
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
$get_fields[] = 'director';
$get_fields[] = 'email';
$get_fields[] = 'tel';
$get_fields[] = 'client_id';
	
			$get_fields = filter_field_disallow_view($access_config, 'get_it', $get_fields);
			if($URI_TO_CTL[1]){
				global $db;
				
				
				$where = array('client_id'=>$URI_TO_CTL[1]);
				$allowed = check_record_allow_view($access_config, 'get_it', $where);
				if($allowed){
					$clients = $this->db->select('clients', $where, $get_fields); 
					print json_encode($clients[0], true);
					exit();
				}
			}
		}
}
