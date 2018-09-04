
$('#updateModal button.submit').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#updateModal form").serializeArray();
      var formURL = '/tasks/stop';
      $.ajax(
      {
          url : formURL,
          type: "POST",
          data : postData,
          success:function(data, textStatus, jqXHR) 
          {
            $('#updateModal').modal('hide');
            $btn.button('reset');
            load_docs_list();
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
             $btn.button('reset');   
          }
      });
});


$('#updateModal').on('show.bs.modal', function (event) { // Если открыли окно редактирования (создания)
  var button = $(event.relatedTarget) // Кнопка, вызвавшая окно
  var recipient = button.data('whatever') // Берем из кнопки данные, их потом можно использовать для заголовка формы. Вот так:
	 /* 
	 var modal = $(this)
	 
	 modal.find('.modal-title').text('')
	 modal.find('.modal-body input').val(recipient)
	 */
	 
  
  if(recipient != 'new'){ 
    $('[name=action]').val('update'); 
    	$.getJSON('/tasks/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=user]').val(data.user);$('input[name=service]').val(data.service);$('input[name=volume]').val(data.volume);$('input[name=complete]').val(data.complete);$('input[name=price]').val(data.price);$('input[name=started]').val(data.started);$('input[name=link]').val(data.link);$('input[name=soctype]').val(data.soctype);$('input[name=startcount]').val(data.startcount);$('input[name=status]').val(data.status);
        $('[name=id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=user]').val('');$('input[name=service]').val('');$('input[name=volume]').val('');$('input[name=complete]').val('');$('input[name=price]').val('');$('input[name=started]').val('');$('input[name=link]').val('');$('input[name=soctype]').val('');$('input[name=startcount]').val('');$('input[name=status]').val('');  }
});
function operations_button_activate(){
	$('button.stop').click(function(){
	  $.getJSON('/tasks/stop/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
}
load_docs_list();

function load_docs_list(){
  $("#tasks").bs_grid({
    ajaxFetchDataURL: "/tasks/get_table",
    row_primary_key: "id",
    rowSelectionMode: false,

    columns: [
	  {field: "id", header: "ID"},
	        {field: "user", header: "Пользователь"},
	        {field: "service", header: "Услуга"},
	        {field: "volume", header: "Объем заказа"},
	        {field: "complete", header: "Уже выполнено"},
	        {field: "price", header: "Стоимость"},
	        {field: "started", header: "Запущен"},
	        {field: "link", header: "Ссылка"},
	        {field: "soctype", header: "Соцсеть"},
	        {field: "startcount", header: "Было изначально"},
	        {field: "status", header: "Статус"},
	        {field: "actions", header: "Операции", is_function: "yes"},
    ],
 
    sorting: [
      {sortingName: "ID", field: "id", order: "ascending"}
    ],
  });
  $("#tasks").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

