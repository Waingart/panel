<?
$grid_config = array(
	'entitie' => 'task',
	'dbtable' => 'tasks',
	'id_field' => 'task_id',
	'visible_fields' => array('volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount', 'status'),
	'title_fields' => array('volume'=>'Объем заказа', 'startcount'=>'Было изначально', 'complete'=>'Выполнено', 'price'=>'Сумма', 'started'=>'Стартовал', 'link'=>'Ссылка', 'soctype'=>'Соцсеть', 'status'=>'Статус'),
	'tech_fields' => array('task_id'),
	'actions' => array('update', 'delete', 'add_new', 'stop'),
	'action_buttons' => array('delete', 'stop'),
	//'top_buttons' => array('add_new'),
	'modal_buttons' => array('myModal')
);
?>

load_docs_list();
<?
foreach($grid_config['actions'] as $action){ ?>
$('button.<?=$action?>').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#save_<?=$grid_config['entitie']?>").serializeArray();
      var formURL = '/panel/<?=$grid_config['entitie']?>/<?=$action?>';
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
    	$.getJSON('/panel/<?=$grid_config['entitie']?>/get_it/'+recipient, function(data){
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
foreach($grid_config['action_buttons'] as $button){ ?>
	$('button.<?=$button?>').click(function(){
	  $.getJSON('/panel/<?=$grid_config['entitie']?>/<?=$button?>/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
<?
}?>
}


function load_docs_list(){
  $("#<?=$grid_config['entitie']?>").bs_grid({
    ajaxFetchDataURL: "/panel/<?=$grid_config['entitie']?>/get_table",
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
