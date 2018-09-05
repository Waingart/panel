
$('#editModal button.submit').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#editModal form").serializeArray();
      var formURL = '/crm/edit';
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
	 
	 modal.find('.modal-title').text('Изменение услуги')
	 modal.find('.modal-body input').val(recipient)
	 */
	 
  
  if(recipient != 'new'){ 
    $('[name=action]').val('update'); 
    	$.getJSON('/crm/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=name]').val(data.name);$('input[name=fam]').val(data.fam);$('input[name=otsh]').val(data.otsh);$('input[name=email]').val(data.email);$('input[name=phone]').val(data.phone);$('input[name=org_form]').val(data.org_form);$('input[name=short_name]').val(data.short_name);
        $('[name=client_id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=name]').val('');$('input[name=fam]').val('');$('input[name=otsh]').val('');$('input[name=email]').val('');$('input[name=phone]').val('');$('input[name=org_form]').val('');$('input[name=short_name]').val('');  }
});
function operations_button_activate(){
	$('button.edit').click(function(){
	  $.getJSON('/crm/edit/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
}
load_docs_list();

function load_docs_list(){
  $("#crm").bs_grid({
    ajaxFetchDataURL: "/crm/get_table",
    row_primary_key: "client_id",
    rowSelectionMode: false,

    columns: [
	  {field: "client_id", header: "ID"},
	        {field: "name", header: "Имя"},
	        {field: "fam", header: "Фамилия"},
	        {field: "otsh", header: "Отчество"},
	        {field: "email", header: "Email"},
	        {field: "phone", header: "Телефон"},
	        {field: "org_form", header: "Форма"},
	        {field: "short_name", header: "Компания"},
	        {field: "actions", header: "Операции", is_function: "yes"},
    ],
 
    sorting: [
      {sortingName: "ID", field: "client_id", order: "ascending"}
    ],
  });
  $("#crm").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

