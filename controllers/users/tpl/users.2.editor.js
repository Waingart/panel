
$('#updateModal button.submit').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#updateModal form").serializeArray();
      var formURL = '/users/update';
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
	 
	 modal.find('.modal-title').text('Изменить данные пользователя')
	 modal.find('.modal-body input').val(recipient)
	 */
	 
  
  if(recipient != 'new'){ 
    $('[name=action]').val('update'); 
    	$.getJSON('/users/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=lcc]').val(data.lcc);$('input[name=uname]').val(data.uname);$('input[name=ufam]').val(data.ufam);$('input[name=uoth]').val(data.uoth);$('input[name=balance]').val(data.balance);$('input[name=email]').val(data.email);$('input[name=phone]').val(data.phone);
        $('[name=id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=lcc]').val('');$('input[name=uname]').val('');$('input[name=ufam]').val('');$('input[name=uoth]').val('');$('input[name=balance]').val('');$('input[name=email]').val('');$('input[name=phone]').val('');  }
});
function operations_button_activate(){
	$('button.update').click(function(){
	  $.getJSON('/users/update/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
}
load_docs_list();

function load_docs_list(){
  $("#users").bs_grid({
    ajaxFetchDataURL: "/users/get_table",
    row_primary_key: "id",
    rowSelectionMode: false,

    columns: [
	  {field: "id", header: "ID"},
	        {field: "uname", header: "Имя"},
	        {field: "balance", header: "Баланс"},
	        {field: "email", header: "Email"},
	        {field: "phone", header: "Телефон"},
	        {field: "actions", header: "Операции", is_function: "yes"},
    ],
 
    sorting: [
      {sortingName: "ID", field: "id", order: "ascending"}
    ],
  });
  $("#users").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

