
$('#editModal button.submit').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#editModal form").serializeArray();
      var formURL = '/clients/edit';
      $.ajax(
      {
          url : formURL,
          type: "POST",
          data : postData,
          success:function(data, textStatus, jqXHR) 
          {
            $('#editModal').modal('hide');
            $btn.button('reset');
            load_docs_list();
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
             $btn.button('reset');   
          }
      });
});


$('#editModal').on('show.bs.modal', function (event) { // Если открыли окно редактирования (создания)
  var button = $(event.relatedTarget) // Кнопка, вызвавшая окно
  var recipient = button.data('whatever') // Берем из кнопки данные, их потом можно использовать для заголовка формы. Вот так:
	 /* 
	 var modal = $(this)
	 
	 modal.find('.modal-title').text('Редактирование карточки клиента')
	 modal.find('.modal-body input').val(recipient)
	 */
	 
  
  if(recipient != 'new'){ 
    $('[name=action]').val('update'); 
    	$.getJSON('/clients/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=title]').val(data.title);$('input[name=director]').val(data.director);$('input[name=email]').val(data.email);$('input[name=tel]').val(data.tel);
        $('[name=client_id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=title]').val('');$('input[name=director]').val('');$('input[name=email]').val('');$('input[name=tel]').val('');  }
});

$('#mailtoModal button.submit').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#mailtoModal form").serializeArray();
      var formURL = '/clients/mailto';
      $.ajax(
      {
          url : formURL,
          type: "POST",
          data : postData,
          success:function(data, textStatus, jqXHR) 
          {
            $('#mailtoModal').modal('hide');
            $btn.button('reset');
            load_docs_list();
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
             $btn.button('reset');   
          }
      });
});


$('#mailtoModal').on('show.bs.modal', function (event) { // Если открыли окно редактирования (создания)
  var button = $(event.relatedTarget) // Кнопка, вызвавшая окно
  var recipient = button.data('whatever') // Берем из кнопки данные, их потом можно использовать для заголовка формы. Вот так:
	 /* 
	 var modal = $(this)
	 
	 modal.find('.modal-title').text('Написать email')
	 modal.find('.modal-body input').val(recipient)
	 */
	 
  
  if(recipient != 'new'){ 
    $('[name=action]').val('update'); 
    	$.getJSON('/clients/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=title]').val(data.title);$('input[name=director]').val(data.director);$('input[name=email]').val(data.email);$('input[name=tel]').val(data.tel);
        $('[name=client_id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=title]').val('');$('input[name=director]').val('');$('input[name=email]').val('');$('input[name=tel]').val('');  }
});

$('#deleteModal button.submit').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#deleteModal form").serializeArray();
      var formURL = '/clients/delete';
      $.ajax(
      {
          url : formURL,
          type: "POST",
          data : postData,
          success:function(data, textStatus, jqXHR) 
          {
            $('#deleteModal').modal('hide');
            $btn.button('reset');
            load_docs_list();
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
             $btn.button('reset');   
          }
      });
});


$('#deleteModal').on('show.bs.modal', function (event) { // Если открыли окно редактирования (создания)
  var button = $(event.relatedTarget) // Кнопка, вызвавшая окно
  var recipient = button.data('whatever') // Берем из кнопки данные, их потом можно использовать для заголовка формы. Вот так:
	 /* 
	 var modal = $(this)
	 
	 modal.find('.modal-title').text('Удаление клиента')
	 modal.find('.modal-body input').val(recipient)
	 */
	 
  
  if(recipient != 'new'){ 
    $('[name=action]').val('update'); 
    	$.getJSON('/clients/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=title]').val(data.title);$('input[name=director]').val(data.director);$('input[name=email]').val(data.email);$('input[name=tel]').val(data.tel);
        $('[name=client_id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=title]').val('');$('input[name=director]').val('');$('input[name=email]').val('');$('input[name=tel]').val('');  }
});

$('#statisticModal button.submit').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#statisticModal form").serializeArray();
      var formURL = '/clients/statistic';
      $.ajax(
      {
          url : formURL,
          type: "POST",
          data : postData,
          success:function(data, textStatus, jqXHR) 
          {
            $('#statisticModal').modal('hide');
            $btn.button('reset');
            load_docs_list();
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
             $btn.button('reset');   
          }
      });
});


$('#statisticModal').on('show.bs.modal', function (event) { // Если открыли окно редактирования (создания)
  var button = $(event.relatedTarget) // Кнопка, вызвавшая окно
  var recipient = button.data('whatever') // Берем из кнопки данные, их потом можно использовать для заголовка формы. Вот так:
	 /* 
	 var modal = $(this)
	 
	 modal.find('.modal-title').text('Сводные данные по клиенту')
	 modal.find('.modal-body input').val(recipient)
	 */
	 
  
  if(recipient != 'new'){ 
    $('[name=action]').val('update'); 
    	$.getJSON('/clients/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=title]').val(data.title);$('input[name=director]').val(data.director);$('input[name=email]').val(data.email);$('input[name=tel]').val(data.tel);
        $('[name=client_id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=title]').val('');$('input[name=director]').val('');$('input[name=email]').val('');$('input[name=tel]').val('');  }
});
function operations_button_activate(){
	$('button.edit').click(function(){
	  $.getJSON('/clients/edit/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
	$('button.mailto').click(function(){
	  $.getJSON('/clients/mailto/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
	$('button.delete').click(function(){
	  $.getJSON('/clients/delete/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
	$('button.statistic').click(function(){
	  $.getJSON('/clients/statistic/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
}
load_docs_list();

function load_docs_list(){
  $("#clients").bs_grid({
    ajaxFetchDataURL: "/clients/get_table",
    row_primary_key: "client_id",
    rowSelectionMode: false,

    columns: [
	  {field: "client_id", header: "ID"},
	        {field: "title", header: "Компания"},
	        {field: "director", header: "Контактное лицо"},
	        {field: "email", header: "Email"},
	        {field: "tel", header: "Телефон"},
	        {field: "actions", header: "Операции", is_function: "yes"},
    ],
 
    sorting: [
      {sortingName: "ID", field: "client_id", order: "ascending"}
    ],
  });
  $("#clients").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

