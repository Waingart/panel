<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

global $access_config;
$access_config = json_decode('{"get_table":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["id"],"2":[]},"field_disallow_update":{"1":["id","docnum","sum","docnum","paydate","lcc","doc_id","status","user_id"],"2":["id","docnum","sum","docnum","paydate","lcc","doc_id","status","user_id"]},"record_allow_update":[],"record_allow_view":{"0":2,"OWNER":["user_id"]}},"get_it":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["id"],"2":[]},"field_disallow_update":{"1":["id","docnum","sum","docnum","paydate","lcc","doc_id","status","user_id"],"2":["id","docnum","sum","docnum","paydate","lcc","doc_id","status","user_id"]},"record_allow_update":[],"record_allow_view":{"0":2,"OWNER":["user_id"]}}}', true);



global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

$payments = new payments();

$payments->run($URI_TO_CTL[0]);

class payments { 
    private $db = 0;
    public function payments(){
        global $db;
        $this->db = $db;
    }
    
    function run($run_action){ 
        switch ($run_action) {		case 'get_list':
        $this->get_list();
        break;	
		case 'get_it':
        $this->get_it();
        break;	
		case 'get_table':
        $this->get_table();
        break;	
        case 'go_pay':
        $this->go_pay();
        break;
        case 'go_count':
        $this->go_count();
        break;
        case 'get_count':
        $this->get_count();
        break;
        case 'go_pay_one':
        $this->go_pay_one();
        break;
        case 'pay_result':
        $this->pay_result();
        break;
        
		default:
		    global $act_id;
		    $act_id = payments;
		    if($_SESSION['access_level'] == '2'){
		        HOOK::add_action("section_title", function(){return 'Платежи всех пользователей';});
		    }else{
		        HOOK::add_action("section_title", function(){return 'История платежей';});
		    }
		    
            include('paymentsView.php');
        break;
}
    }
    //-----------------------------
    function go_count()
    {
        include('paymentsCountView.php');
        
        //header('Location: ');
    }
    function get_count()
    {
       //var_dump($_POST);
        foreach($_POST as $postcnt=>$cntvalue){
            if(stristr($postcnt, "cnt_")){
                $cntnum = explode("_", $postcnt)[1];
                $lcc = $this->db->select('users', array('id'=>$_SESSION["user_id"]), 0, array('lcc'))[0]['lcc']; 
        $this->db->update('countners', array('cntvalue'=>$cntvalue), array('cntnum'=>$cntnum, 'lcc'=>$lcc));
        print '';
            }
            
        }
        /*
         print ' <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css"><style>div.full_center{
	width: 100%;
    height: 100%;
    text-align: center;
    position: relative;
}
div.full_center	p {
    font-family: \'Source Sans Pro\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;
     background: #29972963;
    color: white;
    </style><script>window.parent.formsucc();</script>
            <div class="full_center"><p>Спасибо! Показания счетчиков приняты. Прием платежей откроется через несколько дней, Вы получите об этом уведомление на email.</p><a class="btn btn-default btn-xs" onclick="window.parent.closeform()" href="#" style="border: 0px;box-shadow: none;color: rgb(255, 252, 247);font-weight: 600;background: #FFC107;margin-left: 10px;">Закрыть окно</a></div>';
       */
        
        if($_POST['docnum']){
             header('Location: /payments/go_pay_one/'.$_POST['docnum'].'/');
        }else{
            header('Location: /payments/go_pay/');
        }
            
       
    }
    
    function go_pay_one(){
        include('sber.php');
        // подсчитвыаем сумму по квитанциям
        // сохраняем список квитанций
       // $amount = HOOK::dolg_summ();
       global $URI_TO_CTL;
       //print $URI_TO_CTL[1];
      // print ' test';
       //exit();
       $dolg_docs = $this->db->select('paydocs', array('status'=>1, 'user_id'=>$_SESSION["user_id"], 'id'=>$URI_TO_CTL[1]));
        $amount = 0;
        if(is_array($dolg_docs)){
            $dolg_docs_ids = array();
            foreach($dolg_docs as $d_doc){
                $amount = $amount + $d_doc['sum'];
                $dolg_docs_ids[] = $d_doc['id'];
            }
        }
       // $this->db->select();

//lcc



        if($amount > 0){
    
           $last_id = $this->db->insert('payments', array('user_id'=>$_SESSION["user_id"],'sum'=>$amount, 'paydate'=>date("Y-m-d") ,'doclist'=>json_encode($dolg_docs_ids), 'status'=>1));
            // $last_id
            // $amount
           // var_dump($last_id);
            //exit;
            $sber = new SberPay();
            
            if($_GET['rbspayment']=='result'){
            	$sber->callb();
            }else{
            	$sber->generate_form($last_id, $amount); //370.50
            }
        }else{
           print ' <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css"><style>div.full_center{
	width: 100%;
    height: 100%;
    text-align: center;
    position: relative;
}
div.full_center	p {
    font-family: \'Source Sans Pro\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;
    background: #FFC107;
    color: white;
    </style><script>window.parent.formsucc();</script>
            <div class="full_center"><p>Больше нет неоплаченных счетов</p><a class="btn btn-default btn-xs" onclick="window.parent.closeform()" href="#" style="border: 0px;box-shadow: none;color: rgb(255, 252, 247);font-weight: 600;background: #FFC107;margin-left: 10px;">Закрыть окно</a></div>';
            
        }
    }
    function go_pay(){

        include('sber.php');
        // подсчитвыаем сумму по квитанциям
        // сохраняем список квитанций
       // $amount = HOOK::dolg_summ();
       $dolg_docs = $this->db->select('paydocs', array('status'=>1, 'user_id'=>$_SESSION["user_id"]));
        $amount = 0;
        if(is_array($dolg_docs)){
            $dolg_docs_ids = array();
            foreach($dolg_docs as $d_doc){
                $amount = $amount + $d_doc['sum'];
                $dolg_docs_ids[] = $d_doc['id'];
            }
        }
       // $this->db->select();

//lcc



        if($amount > 0){
    
           $last_id = $this->db->insert('payments', array('user_id'=>$_SESSION["user_id"],'sum'=>$amount, 'paydate'=>date("Y-m-d") ,'doclist'=>json_encode($dolg_docs_ids), 'status'=>1));
            // $last_id
            // $amount
           // var_dump($last_id);
            //exit;
            $sber = new SberPay();
            
            if($_GET['rbspayment']=='result'){
            	$sber->callb();
            }else{
            	$sber->generate_form($last_id, $amount); //370.50
            }
        }else{
            print ' <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css"><style>div.full_center{
	width: 100%;
    height: 100%;
    text-align: center;
    position: relative;
}
div.full_center	p {
    font-family: \'Source Sans Pro\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;
    background: #FFC107;
    color: white;
    </style><script>window.parent.formsucc();</script>
            <div class="full_center"><p>Больше нет неоплаченных счетов</p><a class="btn btn-default btn-xs" onclick="window.parent.closeform()" href="#" style="border: 0px;box-shadow: none;color: rgb(255, 252, 247);font-weight: 600;background: #FFC107;margin-left: 10px;">Закрыть окно</a></div>';
            
        }
    }
    function pay_result(){
        include('sber.php');
        //print 'pay_result';
       $sber = new SberPay();
       $pay_id = $sber->callb();
       //print $_GET['order_id'];
       //var_dump( $pay_id);
       if($pay_id !== false){
           $pay_id = explode("_", $pay_id);
           $pay_id = $pay_id[0];
           
           // берем номера квитанций из платежа, 
           $pay_rec = $this->db->select('payments', array('id'=>$pay_id));
           $doclist = json_decode($pay_rec[0]['doclist'], true);
            // меняем каждой квитанции статус
           //print 'upd_docs';
           foreach($doclist as $doc_id){
               $this->db->update('paydocs', array('status'=>2), array('id'=>$doc_id));
           }
          
           // меняем статус самого платежа
            ///print 'upd_pays';
           $this->db->update('payments', array('status'=>2), array('id'=>$pay_id));
           //
            print ' <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css"><style>div.full_center{
	width: 100%;
    height: 100%;
    text-align: center;
    position: relative;
}
div.full_center	p {
    font-family: \'Source Sans Pro\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;
     background: #29972963;
    color: white;
    </style><script>window.parent.formsucc();</script>
            <div class="full_center"><p>Спасибо! Ваш платеж принят.</p><a class="btn btn-default btn-xs" onclick="window.parent.closeform()" href="#" style="border: 0px;box-shadow: none;color: rgb(255, 252, 247);font-weight: 600;background: #FFC107;margin-left: 10px;">Закрыть окно</a></div>';
            
            
         }else{
              print ' <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css"><style>div.full_center{
	width: 100%;
    height: 100%;
    text-align: center;
    position: relative;
}
div.full_center	p {
    font-family: \'Source Sans Pro\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;
     background: rgb(255, 87, 34);
    color: white;
    </style><script>window.parent.formsucc();</script>
            <div class="full_center"><p>Что-то пошло не так. Попробуйте еще раз.</p><a class="btn btn-default btn-xs" onclick="window.parent.closeform()" href="#" style="border: 0px;box-shadow: none;color: rgb(255, 252, 247);font-weight: 600;background: #FFC107;margin-left: 10px;">Закрыть окно</a></div>';
            
    
    
          
           $pay_id = $_GET['order_id'];
           $pay_id = explode("_", $pay_id);
           $pay_id = $pay_id[0];
           $this->db->update('payments', array('status'=>3), array('id'=>$pay_id));
       }
       // $pay_id
        //$dolg_docs_ids = json_decode();
        //$_GET['order_id']
    }
    //-----------------------------
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
			$get_fields[] = 'user_id';
$get_fields[] = 'lcc';
$get_fields[] = 'sum';
//$get_fields[] = 'docnum';
$get_fields[] = 'paydate';
$get_fields[] = 'doc_id';
$get_fields[] = 'status';
$get_fields[] = 'id';
			$get_fields = filter_field_disallow_view($access_config, 'get_table', $get_fields);				
			
			$where = check_record_allow_view($access_config, 'get_table', $where); // TODO: мы должны принимать условия where из post или get запроса, чтобы отображать данные таблицы только по условию
			if(!$where){
				print 'diallowed record for you'; exit();
			}
			global $db;
			//var_dump($where);
			if(!is_array($where)){
			    $where = [];
			
			}
			$where[] = '`status` > 1';
			//var_dump($this->db->pre_select('payments', $where, $get_fields));
			//var_dump($where);
			$page_settings = array(
				"selectCountSQL" => $this->db->pre_select('payments', $where, array('count(*) as totalrows')),  //SELECT FOUND_ROWS () "select count(*) as totalrows from `payments`",
				"selectSQL" => $this->db->pre_select('payments', $where, $get_fields),
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
            
            	        						$row_status[1] = 'Не завершено';
        					        						$row_status[2] = 'Успешно';
        					        						$row_status[3] = 'Ошибка';
        					        			    if (isset($row_status)){
        			    	$row['status'] = $row_status[$row['status']];
        				}
        								              
                
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
			
			$get_fields[] = 'user_id';
$get_fields[] = 'lcc';
$get_fields[] = 'sum';
//$get_fields[] = 'docnum';
$get_fields[] = 'paydate';
$get_fields[] = 'doc_id';
$get_fields[] = 'status';
$get_fields[] = 'id';
	
	
			  		
			  $get_fields = filter_field_disallow_view($access_config, 'get_list', $get_fields);
			  $where = '';
			  $where = check_record_allow_view($access_config, 'get_list', $where);
			  if($where)
			  $payments = $this->db->select('payments', $where, $get_fields); 

			global $db;
			
			print json_encode($payments, true);
			exit();
		}
		function get_it () {
		    global $access_config;
			if(!check_action_allow($access_config, 'get_it')){
				print 'action not allowed';
				exit();
			}
				
			global $URI_TO_CTL;
			
			$get_fields[] = 'user_id';
$get_fields[] = 'lcc';
$get_fields[] = 'sum';
//$get_fields[] = 'docnum';
$get_fields[] = 'paydate';
$get_fields[] = 'doc_id';
$get_fields[] = 'status';
$get_fields[] = 'id';
	
			$get_fields = filter_field_disallow_view($access_config, 'get_it', $get_fields);
			if($URI_TO_CTL[1]){
				global $db;
				
				
				$where = array('id'=>$URI_TO_CTL[1]);
				$where = check_record_allow_view($access_config, 'get_it', $where);
				if($where){
					$payments = $this->db->select('payments', $where, $get_fields); 
					print json_encode($payments[0], true);
					exit();
				}
			}
		}
}
