<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

global $access_config;
$access_config = json_decode('{"get_table":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"field_disallow_update":{"1":["lcc","uname","ufam","uoth","pass_num","email","phone","password","access","salt","id"],"2":["lcc","id"]},"record_allow_update":[2],"record_allow_view":{"0":2,"OWNER":["id"]}},"get_it":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["id"],"2":[]},"field_disallow_update":{"1":["lcc","uname","ufam","uoth","pass_num","email","phone","password","access","salt","id"],"2":["lcc","id"]},"record_allow_update":[2],"record_allow_view":{"0":2,"OWNER":["id"]}},"update":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["id"],"2":[]},"field_disallow_update":{"1":["lcc","uname","ufam","uoth","pass_num","email","phone","password","access","salt","id"],"2":["lcc","id"]},"record_allow_update":[2],"record_allow_view":{"0":2,"OWNER":["id"]}},"modal_editor":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"field_disallow_update":{"1":["lcc","uname","ufam","uoth","pass_num","email","phone","password","access","salt","id"],"2":["id","lcc"]}}}', true);



global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

$users = new users();

$users->run($URI_TO_CTL[0]);

class users { 
    private $db = 0;
    public function users(){
        global $db;
        $this->db = $db;
    }
    
    function run($run_action){ 
        switch ($run_action) {		case 'update':
        $this->update();
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
		    $act_id = users;
		    if($_SESSION['access_level'] == '2'){
		        HOOK::add_action("section_title", function(){return 'Пользователи';});
		    }else{
		        HOOK::add_action("section_title", function(){return 'Мой профиль';});
		    }
            include('usersView.php');
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

				$data['lcc'] = $_POST['lcc'];
$data['uname'] = $_POST['uname'];
$data['ufam'] = $_POST['ufam'];
$data['uoth'] = $_POST['uoth'];
$data['pass_num'] = $_POST['pass_num'];
$data['email'] = $_POST['email'];
$data['phone'] = $_POST['phone'];
				$data = filter_field_disallow_update($access_config, 'update', $data);
				
				
				$where = array('id'=>$_POST['id']);
				$allowed = check_record_allow_update($access_config, 'update', $where);
				var_dump($data);
				if($allowed)
				    $this->db->update('users', $data, $where);
				/*
				$where_filtered = check_record_allow_update($access_config, 'update', $where);
				if($where_filtered === true){
					$this->db->update('users', $data, $where);
					print 'dddd'.$this->db->lastquery;
				}
				if(is_array($where_filtered)){
				    $this->db->update('users', $data, $where_filtered);
				}
				*/
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
			$get_fields[] = 'lcc';
$get_fields[] = 'uname';
$get_fields[] = 'ufam';
$get_fields[] = 'uoth';
$get_fields[] = 'pass_num';
$get_fields[] = 'email';
$get_fields[] = 'phone';
$get_fields[] = 'id';
//var_dump($access_config);
			$get_fields = filter_field_disallow_view($access_config, 'get_table', $get_fields);				
		//	var_dump($get_fields);
			$where = check_record_allow_view($access_config, 'get_table', $where); // TODO: мы должны принимать условия where из post или get запроса, чтобы отображать данные таблицы только по условию
			if(!$where){
				print 'diallowed record for you'; exit();
			}
			global $db;
			$page_settings = array(
				"selectCountSQL" => "select count(*) as totalrows from `users`",  
				"selectSQL" => $this->db->pre_select('users', $where, $get_fields),
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
            
            										$row['actions'] .= "<button data-backdrop=\"static\" class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#updateModal\" data-url=\"\" data-whatever=\"{$row['id']}\">Изменить</button>";
				              
                
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
			
			$get_fields[] = 'lcc';
$get_fields[] = 'uname';
$get_fields[] = 'ufam';
$get_fields[] = 'uoth';
$get_fields[] = 'pass_num';
$get_fields[] = 'email';
$get_fields[] = 'phone';
$get_fields[] = 'id';
	
	
			  		
			  $get_fields = filter_field_disallow_view($access_config, 'get_list', $get_fields);
			  $where = '';
			  $allowed = check_record_allow_view($access_config, 'get_list', $where);
			  if($allowed)
			  $users = $this->db->select('users', $where, $get_fields); 

			global $db;
			
			print json_encode($users, true);
			exit();
		}
		function get_it () {
		    global $access_config;
			if(!check_action_allow($access_config, 'get_it')){
				print 'action not allowed';
				exit();
			}
				
			global $URI_TO_CTL;
			
			$get_fields[] = 'lcc';
$get_fields[] = 'uname';
$get_fields[] = 'ufam';
$get_fields[] = 'uoth';
$get_fields[] = 'pass_num';
$get_fields[] = 'email';
$get_fields[] = 'phone';
$get_fields[] = 'id';
	
			$get_fields = filter_field_disallow_view($access_config, 'get_it', $get_fields);
			
			if($URI_TO_CTL[1]){
				global $db;
				
				
				$where = array('id'=>$URI_TO_CTL[1]);
				$allowed = check_record_allow_view($access_config, 'get_it', $where);
				//var_dump($where);
				if($allowed){
					$users = $this->db->select('users', $where, $get_fields); 
					print json_encode($users[0], true);
					exit();
				}
			}else{
			    $where = array('id'=>$_SESSION['user_id']);
				$allowed = check_record_allow_view($access_config, 'get_it', $where);
				// $where['id'] = $_SESSION['user_id'];
				//var_dump($where);
				if($allowed){
					$users = $this->db->select('users', $where, $get_fields); 
					//var_dump( $SESSION);
					print json_encode($users[0], true);
					exit();
				}else{
				    print 'not';
				}
			}
		}
}
