<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

global $access_config;
$access_config = json_decode('{"get_table":{"allow_action":[2],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"record_allow_view":[2]},"get_it":{"allow_action":[2],"deny_action":[0],"field_disallow_view":{"1":["id"],"2":[]},"record_allow_view":[2]},"update":{"allow_action":[2,1],"deny_action":[0],"field_disallow_update":{"1":[],"2":[]},"record_allow_update":[2]},"modal_editor":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"field_disallow_update":{"1":[],"2":[]}},"self_add":{"allow_action":[2,1,0],"deny_action":[],"field_disallow_update":{"1":[],"2":[]},"record_allow_update":[2]}}', true);



global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

$crm = new crm();

$crm->run($URI_TO_CTL[0]);

class crm { 
    private $db = 0;
    public function crm(){
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
		case 'self_add':
        $this->self_add();
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
		    $act_id = crm;
            include('crmView.php');
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

				$data['name'] = $_POST['name'];
$data['fam'] = $_POST['fam'];
$data['otsh'] = $_POST['otsh'];
$data['email'] = $_POST['email'];
$data['phone'] = $_POST['phone'];
$data['org_form'] = $_POST['org_form'];
$data['short_name'] = $_POST['short_name'];
				$data = filter_field_disallow_update($access_config, 'update', $data);
				
				$where = array('client_id'=>$_POST['client_id']);
				$allowed = check_record_allow_update($access_config, 'update', $where);
				if($allowed)
					$this->db->update('data_orgs', $data, $where);
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
					$this->db->delete('data_orgs', $where);
						exit();
		}
			function add_new () {
							global $db;

				$data['name'] = $_POST['name'];
$data['fam'] = $_POST['fam'];
$data['otsh'] = $_POST['otsh'];
$data['email'] = $_POST['email'];
$data['phone'] = $_POST['phone'];
$data['org_form'] = $_POST['org_form'];
$data['short_name'] = $_POST['short_name'];
				$this->db->insert('data_orgs', $data);
									exit();
		}
			function self_add () {
			    var_dump($_SESSION['access_level']);
			    if($_SESSION['access_level']==0){
			        $user_auth = new Auth();
			        $password1 = generatePassword();
                    $new_user_id = $user_auth->create($username, $password1);
                    $_SESSION['user_id'] = $new_user_id;
                    $_SESSION['access_level'] = 1;
			    }
			    if($_POST['name']) $data['name'] = $_POST['name'];
                if($_POST['fam'])$data['fam'] = $_POST['fam'];
                if($_POST['otch'])$data['otsh'] = $_POST['otch'];
                if($_POST['email'])$data['email'] = $_POST['email'];
                if($_POST['phone'])$data['phone'] = $_POST['phone'];
                if($_POST['org_form'])$data['org_form'] = $_POST['org_form'];
                if($_POST['party'])$data['short_name'] = $_POST['party'];
                if($_POST['managmentname'])$data['director'] = $_POST['managmentname'];
                if($_POST['ogrn'])$data['ogrn'] = $_POST['ogrn'];
                if($_POST['address'])$data['reg_addr'] = $_POST['address'];
                if($_POST['inn_kpp']){
                    $inn_kpp = explode(' / ', $_POST['inn_kpp']);
                    $data['inn'] = $inn_kpp[0];
                    $data['kpp'] = $inn_kpp[1];
                }
            	if($_POST['name'])$userdata['uname'] = $_POST['name'];
				if($_POST['fam'])$userdata['ufam'] = $_POST['fam'];
				if($_POST['otch'])$userdata['uoth'] = $_POST['otch'];
				if($_POST['phone'])$userdata['phone'] = $_POST['phone'];
				if($_POST['email'])$userdata['email'] = $_POST['email'];    
                    
			    global $db;
                $exist = $this->db->select('data_orgs', array('client_id'=>$_SESSION['user_id']));
                if(!$exist){
                    $data['client_id'] = $_SESSION['user_id'];
    				$this->db->insert('data_orgs', $data);

                }else{
                    $this->db->update('data_orgs', $data, array('client_id'=>$_SESSION['user_id']));
                }
                $bonusbalance = $this->db->select('users', array('id'=>$_SESSION['user_id']), array('bonusbalance'));
               // var_dump($bonusbalance);
                $bonusbalance = $bonusbalance[0]['bonusbalance'] +5000;
                
                $this->db->update('users', array('bonusbalance'=>$bonusbalance), array('id'=>$_SESSION['user_id'], 'bonusdate'=>'0000-00-00' ));
                $this->db->update('users', array('bonusdate'=>date("Y-m-d")), array('id'=>$_SESSION['user_id'], 'bonusdate'=>'0000-00-00' ));

				$this->db->update('users', $userdata, array('id'=>$_SESSION['user_id'] ));
    				
				
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
			$get_fields[] = 'name';
$get_fields[] = 'fam';
$get_fields[] = 'otsh';
$get_fields[] = 'email';
$get_fields[] = 'phone';
$get_fields[] = 'org_form';
$get_fields[] = 'short_name';
$get_fields[] = 'client_id';
			$get_fields = filter_field_disallow_view($access_config, 'get_table', $get_fields);				
			
			$where = check_record_allow_view($access_config, 'get_table', $where); // TODO: мы должны принимать условия where из post или get запроса, чтобы отображать данные таблицы только по условию
			if(!$where){
				print 'diallowed record for you'; exit();
			}
			global $db;
			$page_settings = array(
				"selectCountSQL" => "select count(*) as totalrows from `data_orgs`",  
				"selectSQL" => $this->db->pre_select('data_orgs', $where, $get_fields),
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
            
            	        						$row_org_form[1] = 'ООО';
        					        						$row_org_form[2] = 'ИП';
        					        						$row_org_form[3] = 'физ';
        					        			    if (isset($row_org_form)){
        			    	$row['org_form'] = $row_org_form[$row['org_form']];
        				}
        				        			    if (isset($row_short_name)){
        			    	$row['short_name'] = $row_short_name[$row['short_name']];
        				}
        				        					$row['short_name'] = $row['short_name'].'</a>';
        													$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#editModal\" data-url=\"\" data-whatever=\"{$row['client_id']}\"><i class=\"fa fa-clock\"></i></button>";
				              
                
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
			
			$get_fields[] = 'name';
$get_fields[] = 'fam';
$get_fields[] = 'otsh';
$get_fields[] = 'email';
$get_fields[] = 'phone';
$get_fields[] = 'org_form';
$get_fields[] = 'short_name';
$get_fields[] = 'client_id';
	
	
			  		
			  $get_fields = filter_field_disallow_view($access_config, 'get_list', $get_fields);
			  $where = '';
			  $where = check_record_allow_view($access_config, 'get_list', $where);
			  if($where)
			  $crm = $this->db->select('data_orgs', $where, $get_fields); 

			global $db;
			
			print json_encode($crm, true);
			exit();
		}
		function get_it () {
		    global $access_config;
			if(!check_action_allow($access_config, 'get_it')){
				print 'action not allowed';
				exit();
			}
				
			global $URI_TO_CTL;
			
			$get_fields[] = 'name';
$get_fields[] = 'fam';
$get_fields[] = 'otsh';
$get_fields[] = 'email';
$get_fields[] = 'phone';
$get_fields[] = 'org_form';
$get_fields[] = 'short_name';
$get_fields[] = 'client_id';
	
			$get_fields = filter_field_disallow_view($access_config, 'get_it', $get_fields);
			if($URI_TO_CTL[1]){
				global $db;
				
				
				$where = array('client_id'=>$URI_TO_CTL[1]);
				$allowed = check_record_allow_view($access_config, 'get_it', $where);
				if($allowed){
					$crm = $this->db->select('data_orgs', $where, $get_fields); 
					print json_encode($crm[0], true);
					exit();
				}
			}
		}
}
