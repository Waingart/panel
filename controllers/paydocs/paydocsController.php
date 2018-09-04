<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

global $access_config;
$access_config = json_decode('{"get_table":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["id","user_id","doc_id","lcc"],"2":[]},"field_disallow_update":{"1":["id","docnum","docdate","sum","doc_id","user_id","lcc","status"],"2":["id","docnum","docdate","sum","doc_id","user_id","lcc","status"]},"record_allow_update":[],"record_allow_view":{"0":2,"OWNER":["user_id"]}},"get_it":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["id"],"2":[]},"field_disallow_update":{"1":["id","docnum","sum","docnum","paydate","lcc","doc_id","status"],"2":["id","docnum","sum","docnum","paydate","lcc","doc_id","status"]},"record_allow_update":[],"record_allow_view":{"0":2,"OWNER":["user_id"]}},"go_pay":{"allow_action":[1],"deny_action":[0]}}', true);



global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

$paydocs = new paydocs();

$paydocs->run($URI_TO_CTL[0]);

class paydocs { 
    private $db = 0;
    public function paydocs(){
        global $db;
        $this->db = $db;
    }
    
    function run($run_action){ 
        if(($run_action != 'load_docs') && ($_SESSION['access_level']==0)){
            header('Location: /auth?action=login');
            exit();
        }
        switch ($run_action) {		case 'get_list':
        $this->get_list();
        break;	
		case 'get_it':
        $this->get_it();
        break;	
		case 'get_table':
        $this->get_table();
        break;	
        case 'load_docs':
        $this->load_docs();
        break;
        //load_docs
		default:
		    global $act_id;
		    $act_id = paydocs;
		    if($_SESSION['access_level'] == '2'){
		        HOOK::add_action("section_title", function(){return 'Квитанции';});
		    }else{
		        HOOK::add_action("section_title", function(){return 'Квитанции';});
		    }
            include('paydocsView.php');
        break;
}
    }
    static function dolg_summ(){
        $dolg_docs = $this->db->select('paydocs', array('status'=>1, 'user_id'=>$_SESSION["user_id"]));
        $summ = 0;
        if(is_array($dolg_docs)){
            foreach($dolg_docs as $d_doc){
                $summ = $summ + $d_doc['sum'];
            }
        }
        return $summ;
        
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
		//	$get_fields[] = 'docnum';
$get_fields[] = 'docdate';
$get_fields[] = 'sum';
$get_fields[] = 'user_id';
$get_fields[] = 'lcc';
$get_fields[] = 'status';
$get_fields[] = 'id';
			$get_fields = filter_field_disallow_view($access_config, 'get_table', $get_fields);				
			$get_fields[] = 'lcc';
			$get_fields[] = 'id';
			$where = check_record_allow_view($access_config, 'get_table', $where); // TODO: мы должны принимать условия where из post или get запроса, чтобы отображать данные таблицы только по условию
			if(!$where){
				print 'diallowed record for you'; exit();
			}
			global $db;
			$page_settings = array(
				"selectCountSQL" => "select count(*) as totalrows from `paydocs`",  
				"selectSQL" => $this->db->pre_select('paydocs', $where, $get_fields),
				"page_num" => $_POST['page_num'],
				"rows_per_page" => $_POST['rows_per_page'],
				"columns" => $_POST['columns'],
				"sorting" =>  isset($_POST['sorting']) ? $_POST['sorting'] : array(),
				"filter_rules" => isset($_POST['filter_rules']) ? $_POST['filter_rules'] : array()
			);
			if($_SESSION['access_level']==1){
			    $page_settings['selectCountSQL'] = "select count(*) as totalrows from `paydocs` where `user_id` =".$_SESSION['user_id'];
			}
			$jfr = new jui_filter_rules($ds);
            $jdg = new bs_grid($ds, $jfr, $page_settings, $_POST['debug_mode'] == "yes" ? true : false);
            
            $data = $jdg->get_page_data();
            
            
            // data conversions (if necessary)
            foreach($data['page_data'] as $key => $row) {
            	// your code here
            		$row['actions'] .= '<a class="btn btn-default btn-xs" target="blank" href="https://cabinet.ingrid-kld.ru/docs/'.$row['lcc'].'_'.$row['docdate'].'.pdf">Открыть</a>';	
                   	if($_SESSION['access_level']==1){
                    if($row['status'] == 1)
        			
        				$row['actions'] .= '<a class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-whatever="'.$row['id'].'" href="#" style="border: 0px;box-shadow: none;color: rgb(255, 252, 247);font-weight: 600;background: #FFC107;margin-left: 10px;">Оплатить</a>';
                   	}		
            	        						$row_status[1] = 'Не оплачено';
        					        						$row_status[2] = 'Оплачено';
        					        						$row_status[3] = 'Ошибка!';
        					        			    if (isset($row_status)){
        			    	$row['status'] = $row_status[$row['status']];
        			    
        			    	
        				}
        						
        							
        						
        							//<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-whatever="all" href="#" style="margin-top: -5px;border: 0px;box-shadow: none;color: rgb(243, 156, 18);font-weight: 600;background: rgb(255, 255, 255);">Оплатить онлайн</a>
        							//	$row['actions'] .= "<button data-backdrop=\"static\" class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#updateModal\" data-url=\"\" data-whatever=\"{$row['id']}\"><i class=\"fa fa-clock\"></i></button>";
				              
                
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
			
			//$get_fields[] = 'docnum';
$get_fields[] = 'docdate';
$get_fields[] = 'sum';
$get_fields[] = 'user_id';
$get_fields[] = 'lcc';
$get_fields[] = 'status';
$get_fields[] = 'id';
	
	
			  		
			  $get_fields = filter_field_disallow_view($access_config, 'get_list', $get_fields);
			  $where = '';
			  $where = check_record_allow_view($access_config, 'get_list', $where);
			  if($where)
			  $paydocs = $this->db->select('paydocs', $where, $get_fields); 

			global $db;
			
			print json_encode($paydocs, true);
			exit();
		}
		function get_it () {
		    global $access_config;
			if(!check_action_allow($access_config, 'get_it')){
				print 'action not allowed';
				exit();
			}
				
			global $URI_TO_CTL;
			
		//	$get_fields[] = 'docnum';
$get_fields[] = 'docdate';
$get_fields[] = 'sum';
$get_fields[] = 'user_id';
$get_fields[] = 'lcc';
$get_fields[] = 'status';
$get_fields[] = 'id';
	
			$get_fields = filter_field_disallow_view($access_config, 'get_it', $get_fields);
			if($URI_TO_CTL[1]){
				global $db;
				
				
				$where = array('id'=>$URI_TO_CTL[1]);
				$where = check_record_allow_view($access_config, 'get_it', $where);
				if($where){
					$paydocs = $this->db->select('paydocs', $where, $get_fields); 
					print json_encode($paydocs[0], true);
					exit();
				}
			}
		}
		function load_docs(){
		    /*
		    $a = '[{"lc":"52992","docum":"","date":"2018-04-30","sum":295.75,"payed":"","file":"52992_2018-04-30.pdf"},{"lc":"52991","docum":"","date":"2018-04-30","sum":900.24,"payed":"","file":"52991_2018-04-30.pdf"},{"lc":"52989","docum":"","date":"2018-04-30","sum":1272.55,"payed":"","file":"52989_2018-04-30.pdf"},{"lc":"52988","docum":"","date":"2018-04-30","sum":1127.44,"payed":"","file":"52988_2018-04-30.pdf"},{"lc":"53432","docum":"","date":"2018-04-30","sum":1972.61,"payed":"","file":"53432_2018-04-30.pdf"},{"lc":"53434","docum":"","date":"2018-04-30","sum":322.78,"payed":"","file":"53434_2018-04-30.pdf"},{"lc":"53433","docum":"","date":"2018-04-30","sum":806.4,"payed":"","file":"53433_2018-04-30.pdf"},{"lc":"53435","docum":"","date":"2018-04-30","sum":778.82,"payed":"","file":"53435_2018-04-30.pdf"},{"lc":"53436","docum":"","date":"2018-04-30","sum":1077.47,"payed":"","file":"53436_2018-04-30.pdf"},{"lc":"53437","docum":"","date":"2018-04-30","sum":1028.57,"payed":"","file":"53437_2018-04-30.pdf"},{"lc":"53438","docum":"","date":"2018-04-30","sum":1071.92,"payed":"","file":"53438_2018-04-30.pdf"},{"lc":"52995","docum":"","date":"2018-04-30","sum":956.09,"payed":"","file":"52995_2018-04-30.pdf"},{"lc":"52994","docum":"","date":"2018-04-30","sum":1171.58,"payed":"","file":"52994_2018-04-30.pdf"},{"lc":"52993","docum":"","date":"2018-04-30","sum":1234.67,"payed":"","file":"52993_2018-04-30.pdf"},{"lc":"52997","docum":"","date":"2018-04-30","sum":1127.44,"payed":"","file":"52997_2018-04-30.pdf"},{"lc":"52998","docum":"","date":"2018-04-30","sum":1127.44,"payed":"","file":"52998_2018-04-30.pdf"},{"lc":"53000","docum":"","date":"2018-04-30","sum":1272.55,"payed":"","file":"53000_2018-04-30.pdf"},{"lc":"53002","docum":"","date":"2018-04-30","sum":900.24,"payed":"","file":"53002_2018-04-30.pdf"},{"lc":"53004","docum":"","date":"2018-04-30","sum":1127.44,"payed":"","file":"53004_2018-04-30.pdf"},{"lc":"53005","docum":"","date":"2018-04-30","sum":1152.66,"payed":"","file":"53005_2018-04-30.pdf"},{"lc":"53006","docum":"","date":"2018-04-30","sum":599.35,"payed":"","file":"53006_2018-04-30.pdf"},{"lc":"53008","docum":"","date":"2018-04-30","sum":900.24,"payed":"","file":"53008_2018-04-30.pdf"},{"lc":"53354","docum":"","date":"2018-04-30","sum":321.17,"payed":"","file":"53354_2018-04-30.pdf"},{"lc":"53010","docum":"","date":"2018-04-30","sum":1039.05,"payed":"","file":"53010_2018-04-30.pdf"},{"lc":"53012","docum":"","date":"2018-04-30","sum":720.19,"payed":"","file":"53012_2018-04-30.pdf"},{"lc":"53014","docum":"","date":"2018-04-30","sum":479.5,"payed":"","file":"53014_2018-04-30.pdf"},{"lc":"53016","docum":"","date":"2018-04-30","sum":1089.56,"payed":"","file":"53016_2018-04-30.pdf"},{"lc":"53018","docum":"","date":"2018-04-30","sum":285.51,"payed":"","file":"53018_2018-04-30.pdf"},{"lc":"53020","docum":"","date":"2018-04-30","sum":165.15,"payed":"","file":"53020_2018-04-30.pdf"},{"lc":"53022","docum":"","date":"2018-04-30","sum":1114.79,"payed":"","file":"53022_2018-04-30.pdf"},{"lc":"53026","docum":"","date":"2018-04-30","sum":1020.14,"payed":"","file":"53026_2018-04-30.pdf"},{"lc":"53029","docum":"","date":"2018-04-30","sum":1909.92,"payed":"","file":"53029_2018-04-30.pdf"},{"lc":"53030","docum":"","date":"2018-04-30","sum":1228.37,"payed":"","file":"53030_2018-04-30.pdf"},{"lc":"53031","docum":"","date":"2018-04-30","sum":1222.1,"payed":"","file":"53031_2018-04-30.pdf"},{"lc":"53399","docum":"","date":"2018-04-30","sum":394.6,"payed":"","file":"53399_2018-04-30.pdf"},{"lc":"53431","docum":"","date":"2018-04-30","sum":1213.4,"payed":"","file":"53431_2018-04-30.pdf"},{"lc":"53430","docum":"","date":"2018-04-30","sum":1170.81,"payed":"","file":"53430_2018-04-30.pdf"},{"lc":"53429","docum":"","date":"2018-04-30","sum":1078.59,"payed":"","file":"53429_2018-04-30.pdf"},{"lc":"53428","docum":"","date":"2018-04-30","sum":1192.08,"payed":"","file":"53428_2018-04-30.pdf"},{"lc":"53427","docum":"","date":"2018-04-30","sum":1206.29,"payed":"","file":"53427_2018-04-30.pdf"},{"lc":"53426","docum":"","date":"2018-04-30","sum":1206.29,"payed":"","file":"53426_2018-04-30.pdf"},{"lc":"53425","docum":"","date":"2018-04-30","sum":1149.54,"payed":"","file":"53425_2018-04-30.pdf"},{"lc":"53424","docum":"","date":"2018-04-30","sum":1277.25,"payed":"","file":"53424_2018-04-30.pdf"},{"lc":"53306","docum":"","date":"2018-04-30","sum":721.4,"payed":"","file":"53306_2018-04-30.pdf"},{"lc":"53239","docum":"","date":"2018-04-30","sum":274.6,"payed":"","file":"53239_2018-04-30.pdf"},{"lc":"53033","docum":"","date":"2018-04-30","sum":1720.61,"payed":"","file":"53033_2018-04-30.pdf"},{"lc":"53035","docum":"","date":"2018-04-30","sum":1102.15,"payed":"","file":"53035_2018-04-30.pdf"},{"lc":"53037","docum":"","date":"2018-04-30","sum":1127.44,"payed":"","file":"53037_2018-04-30.pdf"},{"lc":"53039","docum":"","date":"2018-04-30","sum":1196.82,"payed":"","file":"53039_2018-04-30.pdf"},{"lc":"53040","docum":"","date":"2018-04-30","sum":1184.22,"payed":"","file":"53040_2018-04-30.pdf"},{"lc":"53041","docum":"","date":"2018-04-30","sum":1152.66,"payed":"","file":"53041_2018-04-30.pdf"},{"lc":"53042","docum":"","date":"2018-04-30","sum":144.63,"payed":"","file":"53042_2018-04-30.pdf"},{"lc":"53044","docum":"","date":"2018-04-30","sum":1102.15,"payed":"","file":"53044_2018-04-30.pdf"},{"lc":"53046","docum":"","date":"2018-04-30","sum":1095.88,"payed":"","file":"53046_2018-04-30.pdf"},{"lc":"53048","docum":"","date":"2018-04-30","sum":168.25,"payed":"","file":"53048_2018-04-30.pdf"},{"lc":"53050","docum":"","date":"2018-04-30","sum":175.41,"payed":"","file":"53050_2018-04-30.pdf"},{"lc":"53070","docum":"","date":"2018-04-30","sum":950.71,"payed":"","file":"53070_2018-04-30.pdf"},{"lc":"53071","docum":"","date":"2018-04-30","sum":950.71,"payed":"","file":"53071_2018-04-30.pdf"},{"lc":"53072","docum":"","date":"2018-04-30","sum":1020.14,"payed":"","file":"53072_2018-04-30.pdf"},{"lc":"53073","docum":"","date":"2018-04-30","sum":1026.45,"payed":"","file":"53073_2018-04-30.pdf"},{"lc":"53052","docum":"","date":"2018-04-30","sum":1083.24,"payed":"","file":"53052_2018-04-30.pdf"},{"lc":"53053","docum":"","date":"2018-04-30","sum":1076.92,"payed":"","file":"53053_2018-04-30.pdf"},{"lc":"53054","docum":"","date":"2018-04-30","sum":1739.52,"payed":"","file":"53054_2018-04-30.pdf"},{"lc":"53061","docum":"","date":"2018-04-30","sum":1171.58,"payed":"","file":"53061_2018-04-30.pdf"},{"lc":"53063","docum":"","date":"2018-04-30","sum":1007.49,"payed":"","file":"53063_2018-04-30.pdf"},{"lc":"53064","docum":"","date":"2018-04-30","sum":1058.02,"payed":"","file":"53064_2018-04-30.pdf"},{"lc":"53065","docum":"","date":"2018-04-30","sum":1070.6,"payed":"","file":"53065_2018-04-30.pdf"},{"lc":"53066","docum":"","date":"2018-04-30","sum":988.58,"payed":"","file":"53066_2018-04-30.pdf"},{"lc":"53067","docum":"","date":"2018-04-30","sum":919.15,"payed":"","file":"53067_2018-04-30.pdf"},{"lc":"53068","docum":"","date":"2018-04-30","sum":1203.13,"payed":"","file":"53068_2018-04-30.pdf"},{"lc":"53069","docum":"","date":"2018-04-30","sum":1020.14,"payed":"","file":"53069_2018-04-30.pdf"},{"lc":"53094","docum":"","date":"2018-04-30","sum":1121.11,"payed":"","file":"53094_2018-04-30.pdf"},{"lc":"53093","docum":"","date":"2018-04-30","sum":931.79,"payed":"","file":"53093_2018-04-30.pdf"},{"lc":"53047","docum":"","date":"2018-04-30","sum":1102.15,"payed":"","file":"53047_2018-04-30.pdf"},{"lc":"53049","docum":"","date":"2018-04-30","sum":1064.33,"payed":"","file":"53049_2018-04-30.pdf"},{"lc":"53051","docum":"","date":"2018-04-30","sum":1064.33,"payed":"","file":"53051_2018-04-30.pdf"},{"lc":"53103","docum":"","date":"2018-04-30","sum":938.12,"payed":"","file":"53103_2018-04-30.pdf"},{"lc":"53055","docum":"","date":"2018-04-30","sum":315.15,"payed":"","file":"53055_2018-04-30.pdf"},{"lc":"53056","docum":"","date":"2018-04-30","sum":1058.02,"payed":"","file":"53056_2018-04-30.pdf"},{"lc":"53057","docum":"","date":"2018-04-30","sum":925.47,"payed":"","file":"53057_2018-04-30.pdf"},{"lc":"53058","docum":"","date":"2018-04-30","sum":1026.45,"payed":"","file":"53058_2018-04-30.pdf"},{"lc":"53059","docum":"","date":"2018-04-30","sum":1013.81,"payed":"","file":"53059_2018-04-30.pdf"},{"lc":"53060","docum":"","date":"2018-04-30","sum":1032.76,"payed":"","file":"53060_2018-04-30.pdf"},{"lc":"53062","docum":"","date":"2018-04-30","sum":1026.45,"payed":"","file":"53062_2018-04-30.pdf"},{"lc":"53441","docum":"","date":"2018-04-30","sum":734.54,"payed":"","file":"53441_2018-04-30.pdf"},{"lc":"53440","docum":"","date":"2018-04-30","sum":77.01,"payed":"","file":"53440_2018-04-30.pdf"},{"lc":"53439","docum":"","date":"2018-04-30","sum":812.29,"payed":"","file":"53439_2018-04-30.pdf"},{"lc":"53098","docum":"","date":"2018-04-30","sum":1064.33,"payed":"","file":"53098_2018-04-30.pdf"},{"lc":"53099","docum":"","date":"2018-04-30","sum":1935.15,"payed":"","file":"53099_2018-04-30.pdf"},{"lc":"53032","docum":"","date":"2018-04-30","sum":2023.48,"payed":"","file":"53032_2018-04-30.pdf"},{"lc":"53034","docum":"","date":"2018-04-30","sum":963.34,"payed":"","file":"53034_2018-04-30.pdf"},{"lc":"53036","docum":"","date":"2018-04-30","sum":963.34,"payed":"","file":"53036_2018-04-30.pdf"},{"lc":"53038","docum":"","date":"2018-04-30","sum":1026.45,"payed":"","file":"53038_2018-04-30.pdf"},{"lc":"53100","docum":"","date":"2018-04-30","sum":1196.82,"payed":"","file":"53100_2018-04-30.pdf"},{"lc":"53101","docum":"","date":"2018-04-30","sum":1152.66,"payed":"","file":"53101_2018-04-30.pdf"},{"lc":"53102","docum":"","date":"2018-04-30","sum":1108.48,"payed":"","file":"53102_2018-04-30.pdf"},{"lc":"53043","docum":"","date":"2018-04-30","sum":1102.15,"payed":"","file":"53043_2018-04-30.pdf"},{"lc":"53045","docum":"","date":"2018-04-30","sum":1089.56,"payed":"","file":"53045_2018-04-30.pdf"},{"lc":"53013","docum":"","date":"2018-04-30","sum":1045.37,"payed":"","file":"53013_2018-04-30.pdf"},{"lc":"53015","docum":"","date":"2018-04-30","sum":1045.37,"payed":"","file":"53015_2018-04-30.pdf"},{"lc":"53017","docum":"","date":"2018-04-30","sum":1045.37,"payed":"","file":"53017_2018-04-30.pdf"},{"lc":"53019","docum":"","date":"2018-04-30","sum":906.57,"payed":"","file":"53019_2018-04-30.pdf"},{"lc":"53021","docum":"","date":"2018-04-30","sum":1068.97,"payed":"","file":"53021_2018-04-30.pdf"},{"lc":"53097","docum":"","date":"2018-04-30","sum":1039.05,"payed":"","file":"53097_2018-04-30.pdf"},{"lc":"53023","docum":"","date":"2018-04-30","sum":1045.37,"payed":"","file":"53023_2018-04-30.pdf"},{"lc":"53024","docum":"","date":"2018-04-30","sum":1058.02,"payed":"","file":"53024_2018-04-30.pdf"},{"lc":"53025","docum":"","date":"2018-04-30","sum":1184.22,"payed":"","file":"53025_2018-04-30.pdf"},{"lc":"53027","docum":"","date":"2018-04-30","sum":1670.09,"payed":"","file":"53027_2018-04-30.pdf"},{"lc":"53028","docum":"","date":"2018-04-30","sum":1064.33,"payed":"","file":"53028_2018-04-30.pdf"},{"lc":"52990","docum":"","date":"2018-04-30","sum":1026.45,"payed":"","file":"52990_2018-04-30.pdf"},{"lc":"53095","docum":"","date":"2018-04-30","sum":317.14,"payed":"","file":"53095_2018-04-30.pdf"},{"lc":"53096","docum":"","date":"2018-04-30","sum":1051.68,"payed":"","file":"53096_2018-04-30.pdf"},{"lc":"53092","docum":"","date":"2018-04-30","sum":1051.68,"payed":"","file":"53092_2018-04-30.pdf"},{"lc":"52996","docum":"","date":"2018-04-30","sum":1537.62,"payed":"","file":"52996_2018-04-30.pdf"},{"lc":"52999","docum":"","date":"2018-04-30","sum":2004.58,"payed":"","file":"52999_2018-04-30.pdf"},{"lc":"53001","docum":"","date":"2018-04-30","sum":2238.04,"payed":"","file":"53001_2018-04-30.pdf"},{"lc":"53003","docum":"","date":"2018-04-30","sum":2137.11,"payed":"","file":"53003_2018-04-30.pdf"},{"lc":"53007","docum":"","date":"2018-04-30","sum":1108.48,"payed":"","file":"53007_2018-04-30.pdf"},{"lc":"53009","docum":"","date":"2018-04-30","sum":1039.05,"payed":"","file":"53009_2018-04-30.pdf"},{"lc":"53011","docum":"","date":"2018-04-30","sum":1039.05,"payed":"","file":"53011_2018-04-30.pdf"}]';
		   $_POST['docs_data'] = $a;
		   */
		    $load_docs = json_decode($_POST['docs_data'], true);
		    $docs_uid = $_POST['uid']; // случайное значение больше 1000 сгенерированное передающей стороной
		    $docs_check = $_POST['check']; //  результат выполнения md5($load_password.$docs_uid) сгенерированный передающей стороной
		    
		    $load_password = 'K5FFhtt@e!hr8n1';
		    
		    if($docs_check == md5($load_password.$docs_uid)){
		        print 'успешная авторизация\r\n';
		        print 'данные из json:\r\n';
		        var_dump($load_docs);
		    }else{
		        print 'неверные данные для авторизации';
		    }
		    //if($docs_check == md5($load_password.$docs_uid)){ // $load_password - секретный пароль для проверки подлиности передающей стороны. Передается в виде хеша вместе с $docs_uid
		        
    		  //  $load_docs[0] = ['lc'=>'98756753e43fwdde', 'docnum'=>'0001', 'date'=>'2018-05-12', 'sum'=>2345.19, 'payed'=>1, 'file'=>'filename1.pdf'];
    		  //  $load_docs[1] = ['lc'=>'98756753e43fwdde', 'docnum'=>'0002', 'date'=>'2018-05-13', 'sum'=>5245.62, 'payed'=>1, 'file'=>'filename2.pdf'];
    		  //  $load_docs[2] = ['lc'=>'98756753e43fwdde', 'docnum'=>'0003', 'date'=>'2018-05-14', 'sum'=>234.52, 'payed'=>1, 'file'=>'filename3.pdf'];
    		  //  $load_docs[3] = ['lc'=>'98756753e43fwdde', 'docnum'=>'0004', 'date'=>'2018-05-15', 'sum'=>2355.16, 'payed'=>1, 'file'=>'filename4.pdf'];
    		    
    		    if(is_array($load_docs)){
    		        //print 'load_docs';
    		        foreach($load_docs as $doc){
    		            $data['lcc'] = $doc['lc'];
    		            $data['sum'] = $doc['sum'];
    		            //$data['docnum'] = $doc['docnum'];
    		            $data['docdate'] = $doc['date'];
    		            $user_id = $this->db->select('users', array('lcc'=>$doc['lc']));
    		            $data['user_id'] = $user_id[0]['id'];
    		            $data['status'] = 1;
    		            var_dump($data);
    		            print "\r\n\r\n";
    		            $this->db->insert('paydocs', $data);
    		        }
    		    }
		    //}
		    
		}
}
