<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}


$task1 = new task1();

switch ($URI_TO_CTL[0]) {		case 'update':
        $task1->update();
        break;	
		case 'delete':
        $task1->delete();
        break;	
		case 'add_new':
        $task1->add_new();
        break;	
		case 'stop':
        $task1->stop();
        break;	
		case 'get_list':
        $task1->get_list();
        break;	
		case 'get_it':
        $task1->get_it();
        break;	
		case 'get_table':
        $task1->get_table();
        break;	
		default:
            include('task1View.php');
        break;
}


class task1 { 		function update () {
										global $db;
				$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
				$data['volume'] = $_POST['volume'];
$data['complete'] = $_POST['complete'];
$data['price'] = $_POST['price'];
$data['started'] = $_POST['started'];
$data['link'] = $_POST['link'];
$data['soctype'] = $_POST['soctype'];
$data['startcount'] = $_POST['startcount'];
$data['status'] = $_POST['status'];
				$db->update('tasks', $data, array('task_id'=>$_POST['task_id']));
						exit();
		}
			function delete () {
										global $URI_TO_CTL;
				global $db;
				$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
				$db->delete('tasks', array('task_id'=>$URI_TO_CTL[1]));
						exit();
		}
			function add_new () {
							global $db;
				$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
				$data['volume'] = $_POST['volume'];
$data['complete'] = $_POST['complete'];
$data['price'] = $_POST['price'];
$data['started'] = $_POST['started'];
$data['link'] = $_POST['link'];
$data['soctype'] = $_POST['soctype'];
$data['startcount'] = $_POST['startcount'];
$data['status'] = $_POST['status'];
				$db->insert('tasks', $data);
									exit();
		}
			function stop () {
									exit();
		}
			function get_table (){

			$db_settings = array(
				'rdbms' => 'MYSQLi',
				'db_server' => 'localhost',
				'db_user' => 'manager-abelar',
				'db_passwd' => 'manager-abelar',
				'db_name' => 'manager-abelar',
				'db_port' => '3306',
				'charset' => 'utf8',
				'use_pst' => true,
				'pst_placeholder' => 'question_mark'
			);
			$ds = new dacapo($db_settings, null);
			
			
			$page_settings = array(
				"selectCountSQL" => "select count(*) as totalrows from `tasks`",  

				"selectSQL" => "select `task_id`, 
				`volume`, `complete`, `price`, `started`, `link`, `soctype`, `startcount`, `status`, `task_id`				from `tasks`",
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
            
            	        			    if (isset($row_price)){
        			    	$row['price'] = $row_price[$row['price']];
        				}
        				        					$row['price'] = $row['price'].'₽';
        				        						$row_soctype[1] = 'VK';
        					        						$row_soctype[2] = 'INSTAGRAM';
        					        			    if (isset($row_soctype)){
        			    	$row['soctype'] = $row_soctype[$row['soctype']];
        				}
        				        						$row_status[1] = 'Выполняется';
        					        						$row_status[2] = 'Завершено';
        					        						$row_status[3] = 'Остановлено';
        					        						$row_status[4] = 'Ошибка';
        					        			    if (isset($row_status)){
        			    	$row['status'] = $row_status[$row['status']];
        				}
        													$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#testModal\" data-url=\"\" data-whatever=\"{$row['task_id']}\"><i class=\"fa fa-clock\"></i></button>";
									$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#deleteModal\" data-url=\"\" data-whatever=\"{$row['task_id']}\"><i class=\"fa fa-trash\"></i></button>";
				              
                
                $data['page_data'][$key] = $row;
            
            }
            
            echo json_encode($data);
			exit();
		}
		function get_list () {
			global $URI_TO_CTL;
			global $db;
			$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

			  $task1 = $db->select('tasks'); 


			print json_encode($task1, true);
			exit();
		}
		function get_it () {
			global $URI_TO_CTL;
			global $db;
			$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

			if($URI_TO_CTL[1]){
			  $task1 = $db->select('tasks', array('task_id'=>$URI_TO_CTL[1])); 
				print json_encode($task1[0], true);
				exit();

			}
		}
}
