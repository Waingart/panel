
    $(function() {
    $('#Cash').modal('show');
});
        

$('#editModal button.submit').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("form").serializeArray();
      var formURL = '/services/edit';
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
    	$.getJSON('/services/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=title]').val(data.title);$('input[name=descr]').val(data.descr);$('input[name=soc]').val(data.soc);$('input[name=stype]').val(data.stype);$('input[name=ed]').val(data.ed);$('input[name=ed_cost]').val(data.ed_cost);$('input[name=smin]').val(data.smin);$('input[name=smax]').val(data.smax);$('input[name=available]').val(data.available);$('input[name=configs]').val(data.configs);
        $('[name=id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=title]').val('');$('input[name=descr]').val('');$('input[name=soc]').val('');$('input[name=stype]').val('');$('input[name=ed]').val('');$('input[name=ed_cost]').val('');$('input[name=smin]').val('');$('input[name=smax]').val('');$('input[name=available]').val('');$('input[name=configs]').val('');  }
});
function operations_button_activate(){
	$('button.edit').click(function(){
	  $.getJSON('/services/edit/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
}
load_docs_list();

function load_docs_list(){
  $("#services").bs_grid({
    ajaxFetchDataURL: "/services/get_table",
    row_primary_key: "id",
    rowSelectionMode: false,

    columns: [
	  {field: "id", header: "ID"},
	        {field: "title", header: "Название услуги"},
	        {field: "descr", header: "Описание услуги"},
	        {field: "soc", header: "Соцсеть"},
	        {field: "stype", header: "Тип услуги"},
	        {field: "ed", header: "Единица"},
	        {field: "ed_cost", header: "Стоимость единицы"},
	        {field: "smin", header: "Минимальный заказ"},
	        {field: "smax", header: "Максимальный заказ"},
	        {field: "available", header: "Доступна"},
	        {field: "configs", header: "Досутпные настройки"},
	        {field: "actions", header: "Операции", is_function: "yes"},
    ],
 
    sorting: [
      {sortingName: "ID", field: "id", order: "ascending"}
    ],
  });
  $("#services").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

