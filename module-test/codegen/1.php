<?
$grid_config = array(
	'entitie' => 'user',
	'dbtable' => 'users',
	'id_field' => 'id',
	'visible_fields' => array('username', 'email', 'fio', 'mobile'),
	'tech_fields' => array('id'),
	'actions' => array('update', 'delete', 'add_new')
);
echo '<?';
?>
if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

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
				"selectCountSQL" => "select count(*) as totalrows from `<?=$grid_config['dbtable']?>` where not `trash`",  

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
				from `<?=$grid_config['dbtable']?>` where not `trash`",
				"page_num" => $_POST['page_num'],
				"rows_per_page" => $_POST['rows_per_page'],
				"columns" => $_POST['columns'],
				"sorting" =>  isset($_POST['sorting']) ? $_POST['sorting'] : array(),
				"filter_rules" => isset($_POST['filter_rules']) ? $_POST['filter_rules'] : array()
			);
		}
		function get_list () {
			global $URI_TO_CTL;
			global $db;
			$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

			  $<?=$grid_config['entitie']?> = $db->select('<?=$grid_config['dbtable']?>'); 


			print json_encode($<?=$grid_config['entitie']?>, true);
		}
		function get_it () {
			global $URI_TO_CTL;
			global $db;
			$db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

			if($URI_TO_CTL[2]){
			  $<?=$grid_config['entitie']?> = $db->select('<?=$grid_config['dbtable']?>', array('<?=$grid_config['id_field']?>'=>$URI_TO_CTL[2])); 
				print json_encode($<?=$grid_config['entitie']?>[0], true);

			}
		}
}