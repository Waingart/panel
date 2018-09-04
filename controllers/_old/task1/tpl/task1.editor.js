
load_docs_list();
$('button.update').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#save_task1").serializeArray();
      var formURL = '/task1/update';
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
$('button.delete').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#save_task1").serializeArray();
      var formURL = '/task1/delete';
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
$('button.add_new').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#save_task1").serializeArray();
      var formURL = '/task1/add_new';
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
$('button.stop').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#save_task1").serializeArray();
      var formURL = '/task1/stop';
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
    	$.getJSON('/task1/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=volume]').val(data.volume);$('input[name=complete]').val(data.complete);$('input[name=price]').val(data.price);$('input[name=started]').val(data.started);$('input[name=link]').val(data.link);$('input[name=soctype]').val(data.soctype);$('input[name=startcount]').val(data.startcount);$('input[name=status]').val(data.status);
        $('[name=task_id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=volume]').val('');$('input[name=complete]').val('');$('input[name=price]').val('');$('input[name=started]').val('');$('input[name=link]').val('');$('input[name=soctype]').val('');$('input[name=startcount]').val('');$('input[name=status]').val('');  }
});

function operations_button_activate(){
	$('button.update').click(function(){
	  $.getJSON('/task1/update/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
	$('button.delete').click(function(){
	  $.getJSON('/task1/delete/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
}


function load_docs_list(){
  $("#task1").bs_grid({
    ajaxFetchDataURL: "/task1/get_table",
    row_primary_key: "task_id",
    rowSelectionMode: false,

    columns: [
	  {field: "task_id", header: "ID"},
	        {field: "volume", header: "Объем заказа"},
	        {field: "complete", header: "Выполнено"},
	        {field: "price", header: "Сумма"},
	        {field: "started", header: "Стартовал"},
	        {field: "link", header: "Ссылка"},
	        {field: "soctype", header: "Соцсеть"},
	        {field: "startcount", header: "Было изначально"},
	        {field: "status", header: "Статус"},
	        {field: "actions", header: "Операции", is_function: "yes"},
    ],
 
    sorting: [
      {sortingName: "ID", field: "task_id", order: "ascending"}
    ],
  });
  $("#task1").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

