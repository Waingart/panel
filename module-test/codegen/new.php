<?
/*
$grid_config = array(
	'entitie' => 'user',
	'dbtable' => 'users',
	'id_field' => 'id',
	'visible_fields' => array('username', 'email', 'fio', 'mobile'),
	'tech_fields' => array('id'),
	'actions' => array('update', 'delete', 'add_new')
);
*/
$grid_config = array(
	'entitie' => 'task',
	'dbtable' => 'tasks',
	'id_field' => 'task_id',
	'visible_fields' => array('volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount', 'status'),
	'fields_formatting' => array(
			'price'=>array('pre'=>'', 'post'=>'₽'),
			'soctype'=>array(1=>'VK', 2=>'INSTAGRAM'),
			'status'=>array(1=>'Выполняется', 2=>'Завершено', 3=>'Остановлено', 4=>'Ошибка')
	),
	'tech_fields' => array('task_id'),
	'actions' => array('update', 'delete', 'add_new', 'stop'),
	'operations' => array(
	        'update' => array('bticon'=>'clock', 'title'=>'Пояснение', 'url'=>'', 'modal_id'=>'testModal'),
	        'delete' => array('bticon'=>'trash', 'title'=>'Удалить', 'url'=>'', 'modal_id'=>'deleteModal'),
	),
	'modals' => array(
	    'testModal' => array('title'=>'Заголовок testModal', 'type'=>0, 'content'=>'testModal.modal.html')
	)
);
echo '<?';
?>
if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}
?>

$<?=$grid_config['entitie']?> = new <?=$grid_config['entitie']?>();

switch ($URI_TO_CTL[1]) {<?
	foreach($grid_config['actions'] as $action){
?>
		case '<?=$action?>':
        $<?=$grid_config['entitie']?>-><?=$action?>();
        break;	
<?
	}	?>
		case 'get_list':
        $<?=$grid_config['entitie']?>->get_list();
        break;	
		case 'get_it':
        $<?=$grid_config['entitie']?>->get_it();
        break;	
		case 'get_table':
        $<?=$grid_config['entitie']?>->get_table();
        break;	
		default:
            include('<?=$grid_config['entitie']?>View.php');
        break;
}


class <?=$grid_config['entitie']?> { <?
	foreach($grid_config['actions'] as $action){
	?>
		function <?=$action?> () {
			<?
			if ($action=='add_new'){
			?>
				global $db;
				$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
				<?
					foreach($grid_config['visible_fields'] as $field){
						print '$data[\''.$field.'\'] = $_POST[\''.$field.'\'];'."\r\n";
					}
				?>
				$db->insert('<?=$grid_config['dbtable']?>', $data);
			<?
			}
			?>
			<?
			if ($action=='update'){
			?>
				global $db;
				$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
				<?
					foreach($grid_config['visible_fields'] as $field){
						if($field != $grid_config['id_field'])
							print '$data[\''.$field.'\'] = $_POST[\''.$field.'\'];'."\r\n";
					}
				?>
				$db->update('<?=$grid_config['dbtable']?>', $data, array('<?=$grid_config['id_field']?>'=>$_POST['<?=$grid_config['id_field']?>']));
			<?
			}
			
			if ($action=='delete'){
			?>
				global $URI_TO_CTL;
				global $db;
				$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
				$db->delete('<?=$grid_config['dbtable']?>', array('<?=$grid_config['id_field']?>'=>$URI_TO_CTL[2]));
			<?
			}
			?>
			exit();
		}
	<?
	} ?>
		function get_table (){
			require_once 'dacapo.class.php'; 
			require_once 'jui_filter_rules.php'; 
			require_once 'bs_grid.php'; 

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
				"selectCountSQL" => "select count(*) as totalrows from `<?=$grid_config['dbtable']?>`",  

				"selectSQL" => "select `<?=$grid_config['id_field']?>`, 
				<?
					$a = true;
					foreach($grid_config['visible_fields'] as $field){
						print $a ? "`{$field}`": ", `{$field}`";
						$a = false;
					}
					foreach($grid_config['tech_fields'] as $field){
						print ", `{$field}`";
					}

				?>
				from `<?=$grid_config['dbtable']?>`",
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
            
            	<?
            	foreach($grid_config['visible_fields'] as $field){
        			if (isset($grid_config['fields_formatting'][$field])){
        				foreach($grid_config['fields_formatting'][$field] as $ind=>$val){
        					if( ($ind == 'pre') ||  ($ind == 'post')){
        					continue;
        					    
        					}else{
        					?>
        						$row_<?=$field?>[<?=$ind?>] = '<?=$val?>';
        					<?}
        			    }?>
        			    if (isset($row_<?=$field?>)){
        			    	$row['<?=$field?>'] = $row_<?=$field?>[$row['<?=$field?>']];
        				}
        				<?
        				if($ind == 'pre'){?>
        					$row['<?=$field?>'] = '<?=$val?>'.$row['<?=$field?>'];
        				<?}
        				if($ind == 'post'){?>
        					$row['<?=$field?>'] = $row['<?=$field?>'].'<?=$val?>';
        				<?}
        			}
        		}?>
				<?
        		foreach($grid_config['operations'] as $operation => $config){?>
					$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#<?=$config['modal_id']?>\" data-url=\"<?=$config['url']?>\" data-whatever=\"{$row['<?=$grid_config['id_field']?>']}\"><i class=\"fa fa-<?=$config['bticon']?>\"></i></button>
				<?
				}?>
              
                
                $data['page_data'][$key] = $row;
            
            }
            
            echo json_encode($data);
			exit();
		}
		function get_list () {
			global $URI_TO_CTL;
			global $db;
			$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

			  $<?=$grid_config['entitie']?> = $db->select('<?=$grid_config['dbtable']?>'); 


			print json_encode($<?=$grid_config['entitie']?>, true);
			exit();
		}
		function get_it () {
			global $URI_TO_CTL;
			global $db;
			$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

			if($URI_TO_CTL[2]){
			  $<?=$grid_config['entitie']?> = $db->select('<?=$grid_config['dbtable']?>', array('<?=$grid_config['id_field']?>'=>$URI_TO_CTL[2])); 
				print json_encode($<?=$grid_config['entitie']?>[0], true);
				exit();

			}
		}
}