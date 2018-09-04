<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

global $access_config;
$access_config = json_decode('{"get_table":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["id","user_id","doc_id","lcc"],"2":[]},"field_disallow_update":{"1":["id","req_subj","req_time","user_id","user_name","user_email"],"2":["id","req_subj","req_time","user_id","user_name","user_email"]},"record_allow_update":[],"record_allow_view":{"0":2,"OWNER":["user_id"]}},"get_it":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["id","user_id","user_name","user_email"],"2":[]},"field_disallow_update":{"1":["id","req_subj","req_time","user_id","user_name","user_email"],"2":["id","req_subj","req_time","user_id","user_name","user_email"]},"record_allow_update":[],"record_allow_view":{"0":2,"OWNER":["user_id"]}}}', true);



global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

$requests = new requests();

$requests->run($URI_TO_CTL[0]);

class requests { 
    private $db = 0;
    public function requests(){
        global $db;
        $this->db = $db;
    }
    
    function run($run_action){ 
        switch ($run_action) {		case 'manager_answer':
        $this->manager_answer();
        break;	
		case 'user_answer':
        $this->user_answer();
        break;	
		case 'close':
        $this->close();
        break;	
		case 'reopen':
        $this->reopen();
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
		    $act_id = requests;
		    if($_SESSION['access_level'] == '2'){
		        HOOK::add_action("section_title", function(){return 'Обращения';});
		    }else{
		        HOOK::add_action("section_title", function(){return 'Мои обращения';});
		    }
            include('requestsView.php');
        break;
}
    }
    
			function manager_answer () {
									exit();
		}
			function user_answer () {
									exit();
		}
			function close () {
									exit();
		}
			function reopen () {
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
			$get_fields[] = 'id';
$get_fields[] = 'req_subj';
$get_fields[] = 'req_time';
$get_fields[] = 'user_id';
$get_fields[] = 'user_name';
$get_fields[] = 'user_email';
$get_fields[] = 'status';
$get_fields[] = 'id';
			$get_fields = filter_field_disallow_view($access_config, 'get_table', $get_fields);				
			
			$where = check_record_allow_view($access_config, 'get_table', $where); // TODO: мы должны принимать условия where из post или get запроса, чтобы отображать данные таблицы только по условию
			if(!$where){
				print 'diallowed record for you'; exit();
			}
			global $db;
			$page_settings = array(
				"selectCountSQL" => "select count(*) as totalrows from `requests`",  
				"selectSQL" => $this->db->pre_select('requests', $where, $get_fields),
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
            
            	        						$row_status[1] = 'Ожидает Вашего ответа';
        					        						$row_status[2] = 'Ожидает ответа сотрудника';
        					        						$row_status[3] = 'Завершен';
        					        			    if (isset($row_status)){
        			    	$row['status'] = $row_status[$row['status']];
        				}
        													$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#closeModal\" data-url=\"\" data-whatever=\"{$row['id']}\"><i class=\"fa fa-clock\"></i></button>";
									$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#reopenModal\" data-url=\"\" data-whatever=\"{$row['id']}\"><i class=\"fa fa-clock\"></i></button>";
				              
                
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
			
			$get_fields[] = 'id';
$get_fields[] = 'req_subj';
$get_fields[] = 'req_time';
$get_fields[] = 'user_id';
$get_fields[] = 'user_name';
$get_fields[] = 'user_email';
$get_fields[] = 'status';
$get_fields[] = 'id';
	
	
			  		
			  $get_fields = filter_field_disallow_view($access_config, 'get_list', $get_fields);
			  $where = '';
			  $where = check_record_allow_view($access_config, 'get_list', $where);
			  if($where)
			  $requests = $this->db->select('requests', $where, $get_fields); 

			global $db;
			
			print json_encode($requests, true);
			exit();
		}
		function get_it () {
		    global $access_config;
			if(!check_action_allow($access_config, 'get_it')){
				print 'action not allowed';
				exit();
			}
				
			global $URI_TO_CTL;
			
			$get_fields[] = 'id';
$get_fields[] = 'req_subj';
$get_fields[] = 'req_time';
$get_fields[] = 'user_id';
$get_fields[] = 'user_name';
$get_fields[] = 'user_email';
$get_fields[] = 'status';
$get_fields[] = 'id';
	
			$get_fields = filter_field_disallow_view($access_config, 'get_it', $get_fields);
			if($URI_TO_CTL[1]){
				global $db;
				
				
				$where = array('id'=>$URI_TO_CTL[1]);
				$where = check_record_allow_view($access_config, 'get_it', $where);
				if($where){
					$requests = $this->db->select('requests', $where, $get_fields); 
					print json_encode($requests[0], true);
					exit();
				}
			}
		}
}
