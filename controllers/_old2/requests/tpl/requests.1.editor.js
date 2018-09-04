
$('#closeModal button.submit').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#closeModal form").serializeArray();
      var formURL = '/requests/close';
      $.ajax(
      {
          url : formURL,
          type: "POST",
          data : postData,
          success:function(data, textStatus, jqXHR) 
          {
            $('#closeModal').modal('hide');
            $btn.button('reset');
            load_docs_list();
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
             $btn.button('reset');   
          }
      });
});


$('#closeModal').on('show.bs.modal', function (event) { // Если открыли окно редактирования (создания)
  var button = $(event.relatedTarget) // Кнопка, вызвавшая окно
  var recipient = button.data('whatever') // Берем из кнопки данные, их потом можно использовать для заголовка формы. Вот так:
	 /* 
	 var modal = $(this)
	 
	 modal.find('.modal-title').text('Подтвердите действие')
	 modal.find('.modal-body input').val(recipient)
	 */
	 
  
  if(recipient != 'new'){ 
    $('[name=action]').val('update'); 
    	$.getJSON('/requests/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=req_subj]').val(data.req_subj);$('input[name=req_time]').val(data.req_time);$('input[name=user_id]').val(data.user_id);$('input[name=user_name]').val(data.user_name);$('input[name=user_email]').val(data.user_email);$('input[name=status]').val(data.status);
        $('[name=id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=req_subj]').val('');$('input[name=req_time]').val('');$('input[name=user_id]').val('');$('input[name=user_name]').val('');$('input[name=user_email]').val('');$('input[name=status]').val('');  }
});

$('#reopenModal button.submit').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#reopenModal form").serializeArray();
      var formURL = '/requests/reopen';
      $.ajax(
      {
          url : formURL,
          type: "POST",
          data : postData,
          success:function(data, textStatus, jqXHR) 
          {
            $('#reopenModal').modal('hide');
            $btn.button('reset');
            load_docs_list();
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
             $btn.button('reset');   
          }
      });
});


$('#reopenModal').on('show.bs.modal', function (event) { // Если открыли окно редактирования (создания)
  var button = $(event.relatedTarget) // Кнопка, вызвавшая окно
  var recipient = button.data('whatever') // Берем из кнопки данные, их потом можно использовать для заголовка формы. Вот так:
	 /* 
	 var modal = $(this)
	 
	 modal.find('.modal-title').text('Подтвердите действие')
	 modal.find('.modal-body input').val(recipient)
	 */
	 
  
  if(recipient != 'new'){ 
    $('[name=action]').val('update'); 
    	$.getJSON('/requests/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=req_subj]').val(data.req_subj);$('input[name=req_time]').val(data.req_time);$('input[name=user_id]').val(data.user_id);$('input[name=user_name]').val(data.user_name);$('input[name=user_email]').val(data.user_email);$('input[name=status]').val(data.status);
        $('[name=id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=req_subj]').val('');$('input[name=req_time]').val('');$('input[name=user_id]').val('');$('input[name=user_name]').val('');$('input[name=user_email]').val('');$('input[name=status]').val('');  }
});
function operations_button_activate(){
	$('button.close').click(function(){
	  $.getJSON('/requests/close/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
	$('button.reopen').click(function(){
	  $.getJSON('/requests/reopen/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
}
load_docs_list();

function load_docs_list(){
  $("#requests").bs_grid({
    ajaxFetchDataURL: "/requests/get_table",
    row_primary_key: "id",
    rowSelectionMode: false,

    columns: [
	  {field: "id", header: "ID"},
	        {field: "req_subj", header: "Тема запроса"},
	        {field: "req_time", header: "Создан"},
	        {field: "user_name", header: "Имя"},
	        {field: "user_email", header: "Email"},
	        {field: "status", header: "Статус"},
	        {field: "actions", header: "Операции", is_function: "yes"},
    ],
 
    sorting: [
      {sortingName: "ID", field: "id", order: "ascending"}
    ],
  });
  $("#requests").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

