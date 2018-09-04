<?

class EntitiesGenerator{
    public function EntitiesGenerator($EntitieConfig){
        print 'EntitiesGenerator';//exit();
        $nav_config = $EntitieConfig['nav_config'];
        $grid_config = $EntitieConfig['grid_config'];
        $access_config = $EntitieConfig['access_config'];
        $db_config = $EntitieConfig['db_config'];
        $db_query = "CREATE TABLE IF NOT EXISTS `".$db_config['table']."` (";
        $coma_first = 1;
        foreach($db_config['fields'] as $db_field => $opt){
            if($opt['id'] == 1)
                $id_field = $db_field;
            if(isset($opt['ai']) && ($opt['ai'] == 1))
                $ai_field = $db_field;
                $ai_type = $opt['type'];
            if($coma_first){
                $db_query = $db_query."`$db_field` {$opt['type']} NOT NULL";
            }else{
                $db_query = $db_query.",\r\n`$db_field` {$opt['type']} NOT NULL";
            }
            $coma_first=0;
        }


        $db_query = $db_query.") ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
        if(isset($id_field)){
            $db_query = $db_query." \r\nALTER TABLE `{$db_config['table']}` ADD UNIQUE KEY `{$db_config['table']}` (`$id_field`);";
        }
        if(isset($ai_field)){
            $db_query = $db_query." \r\nALTER TABLE `{$db_config['table']}` CHANGE `$ai_field` `$ai_field` $ai_type NOT NULL AUTO_INCREMENT;";
        }
        
        $db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);
        print $db_query;
        $db->query($db_query); $db_query = '';
        
        $access_config_json = json_encode($access_config);
        $nav_config_json = json_encode($nav_config);
        ob_start();
echo '<?';
?>
if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

global $access_config;
$access_config = json_decode('<?=$access_config_json?>', true);



global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

$<?=$grid_config['entitie']?> = new <?=$grid_config['entitie']?>();

$<?=$grid_config['entitie']?>->run($URI_TO_CTL[0]);

class <?=$grid_config['entitie']?> { 
    private $db = 0;
    public function <?=$grid_config['entitie']?>(){
        global $db;
        $this->db = $db;
    }
    
    function run($run_action){ 
        switch ($run_action) {<?
	foreach($grid_config['actions'] as $action){
?>
		case '<?=$action?>':
        $this-><?=$action?>();
        break;	
<?
	}	?>
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
		    $act_id = <?=$grid_config['entitie']?>;
            include('<?=$grid_config['entitie']?>View.php');
        break;
}
    }
    
	<?
	foreach($grid_config['actions'] as $action){
	?>
		function <?=$action?> () {
			<?
			if ($action=='add_new'){
			?>
				global $db;

				<?
					foreach($grid_config['visible_fields'] as $field){
						print '$data[\''.$field.'\'] = $_POST[\''.$field.'\'];'."\r\n";
					}
				?>
				$this->db->insert('<?=$grid_config['dbtable']?>', $data);
			<?
			}
			?>
			<?
			if ($action=='update'){
			?>
			    global $access_config;
				if(!check_action_allow($access_config, 'update')){
					print 'action not allowed';
					exit();
				}
				
				global $db;

				<?
					foreach($grid_config['visible_fields'] as $field){
						if($field != $grid_config['id_field'])
							print '$data[\''.$field.'\'] = $_POST[\''.$field.'\'];'."\r\n";
					}
				?>
				$data = filter_field_disallow_update($access_config, 'update', $data);
				
				$where = array('<?=$grid_config['id_field']?>'=>$_POST['<?=$grid_config['id_field']?>']);
				$allowed = check_record_allow_update($access_config, 'update', $where);
				if($allowed)
					$this->db->update('<?=$grid_config['dbtable']?>', $data, $where);
			<?
			}
			
			if ($action=='delete'){
			?>
			    global $access_config;
				if(!check_action_allow($access_config, 'delete')){
					print 'action not allowed';
					exit();
				}
				
				global $URI_TO_CTL;
				global $db;

				$where = array('<?=$grid_config['id_field']?>'=>$URI_TO_CTL[1]);
				$where = check_record_allow_update($access_config, $action, $where);
				if($where)
					$this->db->delete('<?=$grid_config['dbtable']?>', $where);
			<?
			}
			?>
			exit();
		}
	<?
	} ?>
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
			<?
			foreach($grid_config['visible_fields'] as $field){
					print '$get_fields[] = \''.$field.'\';'."\r\n";
			}
			if($grid_config['tech_fields']){
    			foreach($grid_config['tech_fields'] as $field){
    					print '$get_fields[] = \''.$field.'\';'."\r\n";
    			}
			}
			print '$get_fields[] = \''.$grid_config['id_field'].'\';'."\r\n";
			?>
			$get_fields = filter_field_disallow_view($access_config, 'get_table', $get_fields);				
			
			$where = check_record_allow_view($access_config, 'get_table', $where); // TODO: мы должны принимать условия where из post или get запроса, чтобы отображать данные таблицы только по условию
			if(!$where){
				print 'diallowed record for you'; exit();
			}
			global $db;
			$page_settings = array(
				"selectCountSQL" => "select count(*) as totalrows from `<?=$grid_config['dbtable']?>`",  
				"selectSQL" => $this->db->pre_select('<?=$grid_config['dbtable']?>', $where, $get_fields),
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
					$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#<?=$config['modal_id']?>\" data-url=\"<?=$config['url']?>\" data-whatever=\"{$row['<?=$grid_config['id_field']?>']}\"><i class=\"fa fa-<?=$config['bticon']?>\"></i></button>";
				<?
				}?>
              
                
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
			
			<?
			foreach($grid_config['visible_fields'] as $field){
					print '$get_fields[] = \''.$field.'\';'."\r\n";
			}
			if($grid_config['tech_fields']){
    			foreach($grid_config['tech_fields'] as $field){
    					print '$get_fields[] = \''.$field.'\';'."\r\n";
    			}
			}
			print '$get_fields[] = \''.$grid_config['id_field'].'\';'."\r\n";

			?>	
	
			  		
			  $get_fields = filter_field_disallow_view($access_config, 'get_list', $get_fields);
			  $where = '';
			  $where = check_record_allow_view($access_config, 'get_list', $where);
			  if($where)
			  $<?=$grid_config['entitie']?> = $this->db->select('<?=$grid_config['dbtable']?>', $where, $get_fields); 

			global $db;
			
			print json_encode($<?=$grid_config['entitie']?>, true);
			exit();
		}
		function get_it () {
		    global $access_config;
			if(!check_action_allow($access_config, 'get_it')){
				print 'action not allowed';
				exit();
			}
				
			global $URI_TO_CTL;
			
			<?
			foreach($grid_config['visible_fields'] as $field){
					print '$get_fields[] = \''.$field.'\';'."\r\n";
			}
			if($grid_config['tech_fields']){
    			foreach($grid_config['tech_fields'] as $field){
    					print '$get_fields[] = \''.$field.'\';'."\r\n";
    			}
			}
			print '$get_fields[] = \''.$grid_config['id_field'].'\';'."\r\n";
			
			
			?>	
			$get_fields = filter_field_disallow_view($access_config, 'get_it', $get_fields);
			if($URI_TO_CTL[1]){
				global $db;
				
				
				$where = array('<?=$grid_config['id_field']?>'=>$URI_TO_CTL[1]);
				$allowed = check_record_allow_view($access_config, 'get_it', $where);
				if($allowed){
					$<?=$grid_config['entitie']?> = $this->db->select('<?=$grid_config['dbtable']?>', $where, $get_fields); 
					print json_encode($<?=$grid_config['entitie']?>[0], true);
					exit();
				}
			}
		}
}
<?
$entitie_controller_content = ob_get_contents();
ob_clean();

foreach($access_config['get_table']['field_disallow_view'] as $role => $role_fields_disallowed){ 
foreach($grid_config['operations'] as $operation=>$data){ ?>

$('#<?=$data['modal_id']?> button.submit').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#<?=$data['modal_id']?> form").serializeArray();
      var formURL = '/<?=$grid_config['entitie']?>/<?=$operation?>';
      $.ajax(
      {
          url : formURL,
          type: "POST",
          data : postData,
          success:function(data, textStatus, jqXHR) 
          {
            $('#<?=$data['modal_id']?>').modal('hide');
            $btn.button('reset');
            load_docs_list();
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
             $btn.button('reset');   
          }
      });
});


$('#<?=$data['modal_id']?>').on('show.bs.modal', function (event) { // Если открыли окно редактирования (создания)
  var button = $(event.relatedTarget) // Кнопка, вызвавшая окно
  var recipient = button.data('whatever') // Берем из кнопки данные, их потом можно использовать для заголовка формы. Вот так:
	 /* 
	 var modal = $(this)
	 
	 modal.find('.modal-title').text('<?=$grid_config['modals'][$data['modal_id']]['title']?>')
	 modal.find('.modal-body input').val(recipient)
	 */
	 
  
  if(recipient != 'new'){ 
    $('[name=action]').val('update'); 
    	$.getJSON('/<?=$grid_config['entitie']?>/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       <?
		foreach($grid_config['visible_fields'] as $field){
			if($field != $grid_config['id_field'])
				print '$(\'input[name='.$field.']\').val(data.'.$field.');';
		}
	?>

        $('[name=<?=$grid_config['id_field']?>]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      <?
		foreach($grid_config['visible_fields'] as $field){
			if($field != $grid_config['id_field'])
				print '$(\'input[name='.$field.']\').val(\'\');';
		}
	?>
  }
});
<?
} ?>
function operations_button_activate(){
<?
if(count($grid_config['operations'])>0){
foreach($grid_config['operations'] as $button=>$opt){ ?>
	$('button.<?=$button?>').click(function(){
	  $.getJSON('/<?=$grid_config['entitie']?>/<?=$button?>/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
<?
}
}
?>
}
load_docs_list();

function load_docs_list(){
  $("#<?=$grid_config['entitie']?>").bs_grid({
    ajaxFetchDataURL: "/<?=$grid_config['entitie']?>/get_table",
    row_primary_key: "<?=$grid_config['id_field']?>",
    rowSelectionMode: false,

    columns: [
	  {field: "<?=$grid_config['id_field']?>", header: "ID"},
	  <? foreach($grid_config['visible_fields'] as $field){ 
			if(!in_array($field, $role_fields_disallowed)){
	  ?>
      {field: "<?=$field?>", header: "<?=$grid_config['title_fields'][$field]?>"},
	  <? } 
	}?>
      {field: "actions", header: "Операции", is_function: "yes"},
    ],
 
    sorting: [
      {sortingName: "ID", field: "<?=$grid_config['id_field']?>", order: "ascending"}
    ],
  });
  $("#<?=$grid_config['entitie']?>").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

<?
$entitie_js_content[$role] = ob_get_contents();
ob_clean();
}
?>
<?
/*'modals' => array(
	    'testModal' => array('title'=>'Заголовок testModal', 'type'=>0, 'content'=>'testModal.modal.html')
	)
	
	'id_field' => 'task_id',
	'visible_fields' => array('volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount', 'status'),
	'title_fields' => array('volume'=>'Объем заказа', 'startcount'=>'Было изначально', 'complete'=>'Выполнено', 'price'=>'Сумма', 'started'=>'Стартовал', 'link'=>'Ссылка', 'soctype'=>'Соцсеть', 'status'=>'Статус'),
	'size_fields' => array('volume'=>'2', 'startcount'=>'2', 'complete'=>'2', 'price'=>'2', 'started'=>'2', 'link'=>'2', 'soctype'=>'2', 'status'=>'2'),

*/
$modals = array();
foreach($access_config['modal_editor']['field_disallow_view'] as $role => $role_fields_disallowed){ 
foreach($grid_config['modals'] as $modal => $opt){
     ?>
    <div class="modal fade" id="<?=$modal?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
            <h4 class="modal-title" id="myModalLabel"><?=$opt['title']?></h4>
          </div>
          <div class="modal-body">  
<?
    if($opt['type']==0){?>
    <form action="" method="post" id="">
      <div class="box-body scroll-box">
        <div class="row">
    <?
        foreach($grid_config['visible_fields'] as $field){
			if(in_array($field, $role_fields_disallowed))
				continue;
            if(isset($grid_config['size_fields'][$field])){
                $size = $grid_config['size_fields'][$field];
            }else{
                $size = 2;
            }
            $disabled = ' ';
            ?>
              <div class="col-md-<?=$size?>">
                <div class="form-group">
                  <label><?=$grid_config['title_fields'][$field]?></label>
				  <?
				  if(in_array($field, $access_config['modal_editor']['field_disallow_update'][$role]))
					  $disabled = ' disabled="disabled" ';
				  ?>
                <input name="<?=$field?>"<?=$disabled?>type="text" class="form-control">
                </div><!-- /.form-group -->
              </div><!-- /.col -->
            <?
        }?>
            <input name="<?=$grid_config['id_field']?>" type="hidden">
            <input name="action" type="hidden">
             </div>
            <hr>
          </div><!-- /.box-body -->
        </form>
        <?
    }?>

            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            <button type="button" class="btn btn-primary submit" data-loading-text="Секундочку...">Сохранить</button>
          </div>
        </div>
      </div>
    </div> 
<?
$modals[$modal.'.'.$role] = ob_get_contents();
ob_clean();
}
}?>

<div class="box box-primary">
    <div class="panel panel-default">
      <div class="panel-body">
        <!--button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" data-whatever="new"><i class="fa fa-plus"></i>&nbsp;&nbsp;Добавить пользователя</button-->
      </div>
    </div>
	<div id="<?=$grid_config['entitie']?>"></div>	
</div>
<?
echo '<? $this->modal_windows(); ?>';
?>
<?
$entitie_html_content = ob_get_contents();
ob_clean();
?>
<?
echo '<?'; ?>
if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

$tpl = new TPL1("/var/www/www-root/data/www/cabinet.ingrid-kld.ru/tpl/panel_index.html");
$intpl = $tpl->content(__DIR__.'/tpl/<?=$grid_config['entitie']?>.editor.html');
<?
foreach($grid_config['modals'] as $modal => $opt){?>
   $intpl->modal_windows(__DIR__."/tpl/<?=$modal?>.".$_SESSION["access_level"].".modal.html");
    
<?
}?>

Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', '<?=$grid_config['entitie']?>', 'PAGINATION plugin');
Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', '<?=$grid_config['entitie']?>', 'FILTERS plugin');
Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', '<?=$grid_config['entitie']?>', 'DATAGRID plugin');
Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', '<?=$grid_config['entitie']?>', 'Date Picker');
Helper::Add_css('/assets/vendor/plugins/iCheck/flat/blue.css', '<?=$grid_config['entitie']?>', 'iCheck');
Helper::Add_css('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"', '<?=$grid_config['entitie']?>', 'Bootstrap WYSIHTML5');
Helper::Add_css('/assets/css/custom.css', '<?=$grid_config['entitie']?>', '');


Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', '<?=$grid_config['entitie']?>', 'PAGINATION plugin');
Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', '<?=$grid_config['entitie']?>', '');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', '<?=$grid_config['entitie']?>', 'FILTERS plugin');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', '<?=$grid_config['entitie']?>', '');
Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', '<?=$grid_config['entitie']?>', 'required from filters plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', '<?=$grid_config['entitie']?>', 'DATAGRID plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', '<?=$grid_config['entitie']?>', '');
Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', '<?=$grid_config['entitie']?>', 'datepicker');
Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', '<?=$grid_config['entitie']?>', 'datepicker locales');
Helper::Add_js('/assets/vendor/plugins/iCheck/icheck.min.js', '<?=$grid_config['entitie']?>', 'iCheck');
Helper::Add_js('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', '<?=$grid_config['entitie']?>', 'Bootstrap WYSIHTML5');
Helper::Add_js('/assets/vendor/bs_grid/custom.js', '<?=$grid_config['entitie']?>', 'DATAGRID custom');
Helper::Add_js("/controllers/<?=$grid_config['entitie']?>/tpl/<?=$grid_config['entitie']?>.".$_SESSION["access_level"].".editor.js", '<?=$grid_config['entitie']?>', 'users editor');

$tpl->assign('header_files', Helper::Get_css('<?=$grid_config['entitie']?>'));
$tpl->assign('footer_files', Helper::Get_js('<?=$grid_config['entitie']?>'));
$tpl->run();
<?

$entitie_view_content = ob_get_contents();
ob_clean();
?>
<?
echo '<?'; ?>
global $nav_config;
$nav_config = json_decode('<?=$nav_config_json?>', true);

$NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'<?=$grid_config['entitie']?>'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'<?=$grid_config['entitie']?>/<?=$grid_config['entitie']?>Controller.php')]);

<?

$entitie_init_content = ob_get_contents();
ob_clean();
?>
<?
$controllers_dir = '/var/www/www-root/data/www/mgnt.abelar.ru/controllers/';

$new_controller_dir = $controllers_dir.$grid_config['entitie'];

if (!file_exists($new_controller_dir) && !is_dir($new_controller_dir)) { //создаем директорию нового контроллера, если еще не создана
	mkdir($new_controller_dir);
}
file_put_contents($new_controller_dir.'/'.$grid_config['entitie'].'View.php', $entitie_view_content); //пишем файл отображения
file_put_contents($new_controller_dir.'/'.$grid_config['entitie'].'Controller.php', $entitie_controller_content); //пишем файл контроллера
file_put_contents($new_controller_dir.'/'.$grid_config['entitie'].'Init.php', $entitie_init_content); //пишем файл инициализатора

if (!file_exists($new_controller_dir.'/tpl') && !is_dir($new_controller_dir.'/tpl')) {
	mkdir($new_controller_dir.'/tpl');
}
file_put_contents($new_controller_dir.'/tpl/'.$grid_config['entitie'].'.editor.html', $entitie_html_content); //пишем главный шаблон 
foreach($entitie_js_content as $role=>$js_content){ //пишем js для каждой роли пользователя 
	file_put_contents($new_controller_dir.'/tpl/'.$grid_config['entitie'].'.'.$role.'.editor.js', $js_content); //пишем js фронтенд
}
print "modals: ".count($modals);
foreach($modals as $modal=>$modal_content){ //пишем все модальные окна отдельно 
	file_put_contents($new_controller_dir.'/tpl/'.$modal.'.modal.html', $modal_content);
}

    //end  function EntitiesGenerator    
    }
} // end class EntitiesGenerator    