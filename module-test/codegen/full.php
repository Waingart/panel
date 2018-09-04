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
	'entitie' => 'task1',
	'dbtable' => 'tasks',
	'id_field' => 'task_id',
	'visible_fields' => array('volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount', 'status'),
	'title_fields' => array('volume'=>'Объем заказа', 'startcount'=>'Было изначально', 'complete'=>'Выполнено', 'price'=>'Сумма', 'started'=>'Стартовал', 'link'=>'Ссылка', 'soctype'=>'Соцсеть', 'status'=>'Статус'),
	'size_fields' => array('volume'=>'2', 'startcount'=>'2', 'complete'=>'2', 'price'=>'2', 'started'=>'2', 'link'=>'2', 'soctype'=>'2', 'status'=>'2'),
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

ob_start();
echo '<?';
?>
if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}


$<?=$grid_config['entitie']?> = new <?=$grid_config['entitie']?>();

switch ($URI_TO_CTL[0]) {<?
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
				$db->delete('<?=$grid_config['dbtable']?>', array('<?=$grid_config['id_field']?>'=>$URI_TO_CTL[1]));
			<?
			}
			?>
			exit();
		}
	<?
	} ?>
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
					$row['actions'] .= "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#<?=$config['modal_id']?>\" data-url=\"<?=$config['url']?>\" data-whatever=\"{$row['<?=$grid_config['id_field']?>']}\"><i class=\"fa fa-<?=$config['bticon']?>\"></i></button>";
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

			if($URI_TO_CTL[1]){
			  $<?=$grid_config['entitie']?> = $db->select('<?=$grid_config['dbtable']?>', array('<?=$grid_config['id_field']?>'=>$URI_TO_CTL[1])); 
				print json_encode($<?=$grid_config['entitie']?>[0], true);
				exit();

			}
		}
}
<?
$entitie_controller_content = ob_get_contents();
ob_clean();
?>

load_docs_list();
<?
foreach($grid_config['actions'] as $action){ ?>
$('button.<?=$action?>').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#save_<?=$grid_config['entitie']?>").serializeArray();
      var formURL = '/<?=$grid_config['entitie']?>/<?=$action?>';
      $.ajax(
      {
          url : formURL,
          type: "POST",
          data : postData,
          success:function(data, textStatus, jqXHR) 
          {
            $('#myModal').modal('hide');
            $btn.button('reset');
            load_docs_list();
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
             $btn.button('reset');   
          }
      });
});
<?
} ?>

$('#myModal').on('show.bs.modal', function (event) { // Если открыли окно редактирования (создания)
  var button = $(event.relatedTarget) // Кнопка, вызвавшая окно
  var recipient = button.data('whatever') // Берем из кнопки данные, их потом можно использовать для заголовка формы. Вот так:
	 /* 
	 var modal = $(this)
	 modal.find('.modal-title').text('New message to ' + recipient)
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

function operations_button_activate(){
<?
foreach($grid_config['operations'] as $button=>$opt){ ?>
	$('button.<?=$button?>').click(function(){
	  $.getJSON('/<?=$grid_config['entitie']?>/<?=$button?>/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
<?
}?>
}


function load_docs_list(){
  $("#<?=$grid_config['entitie']?>").bs_grid({
    ajaxFetchDataURL: "/<?=$grid_config['entitie']?>/get_table",
    row_primary_key: "<?=$grid_config['id_field']?>",
    rowSelectionMode: false,

    columns: [
	  {field: "<?=$grid_config['id_field']?>", header: "ID"},
	  <? foreach($grid_config['visible_fields'] as $field){ ?>
      {field: "<?=$field?>", header: "<?=$grid_config['title_fields'][$field]?>"},
	  <? } ?>
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
$entitie_js_content = ob_get_contents();
ob_clean();
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
foreach($grid_config['modals'] as $modal => $opt){
    $modals = array(); ?>
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
            if(isset($grid_config['size_fields'][$field])){
                $size = $grid_config['size_fields'][$field];
            }else{
                $size = 2;
            }
            
            ?>
              <div class="col-md-<?=$size?>">
                <div class="form-group">
                  <label><?=$grid_config['title_fields'][$field]?></label>
                <input name="<?=$field?>" type="text" class="form-control">
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
            <button type="button" class="btn btn-primary save update" data-loading-text="Секундочку...">Сохранить</button>
          </div>
        </div>
      </div>
    </div> 
<?
$modals[$modal] = ob_get_contents();
ob_clean();
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

$tpl = new TPL1("/var/www/www-root/data/www/manager.abelar.ru/tpl/panel_index.html");
$intpl = $tpl->content(__DIR__.'/tpl/<?=$grid_config['entitie']?>.editor.html');
<?
foreach($grid_config['modals'] as $modal => $opt){?>
    $intpl->modal_windows(__DIR__."/tpl/<?=$modal?>.modal.html");
    
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
Helper::Add_js("/controllers/<?=$grid_config['entitie']?>/tpl/<?=$grid_config['entitie']?>.editor.js", '<?=$grid_config['entitie']?>', 'users editor');


$tpl->assign('header_files', Helper::Get_css('<?=$grid_config['entitie']?>'));
$tpl->assign('footer_files', Helper::Get_js('<?=$grid_config['entitie']?>'));
$tpl->run();
<?

$entitie_view_content = ob_get_contents();
ob_clean();
?>
<?
$controllers_dir = '/var/www/www-root/data/www/manager.abelar.ru/controllers/';

$new_controller_dir = $controllers_dir.$grid_config['entitie'];

if (!file_exists($new_controller_dir) && !is_dir($new_controller_dir)) { //создаем директорию нового контроллера, если еще не создана
	mkdir($new_controller_dir);
}
file_put_contents($new_controller_dir.'/'.$grid_config['entitie'].'View.php', $entitie_view_content); //пишем файл отображения
file_put_contents($new_controller_dir.'/'.$grid_config['entitie'].'Controller.php', $entitie_controller_content); //пишем файл контроллера

if (!file_exists($new_controller_dir.'/tpl') && !is_dir($new_controller_dir.'/tpl')) {
	mkdir($new_controller_dir.'/tpl');
}
file_put_contents($new_controller_dir.'/tpl/'.$grid_config['entitie'].'.editor.html', $entitie_html_content); //пишем главный шаблон 
file_put_contents($new_controller_dir.'/tpl/'.$grid_config['entitie'].'.editor.js', $entitie_js_content); //пишем js фронтенд

foreach($modals as $modal=>$modal_content){ //пишем все модальные окна отдельно 
	file_put_contents($new_controller_dir.'/tpl/'.$modal.'.modal.html', $modal_content);
}

?>