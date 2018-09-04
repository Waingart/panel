
load_docs_list();
$('button.change_target').click(function(){
  var $btn = $(this).button('loading');
      var postData = $("#save_payments").serializeArray();
      var formURL = '/payments/change_target';
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
    	$.getJSON('/payments/get_it/'+recipient, function(data){
       $('[name=action]').val('update');

       $('input[name=pay_date]').val(data.pay_date);$('input[name=amount]').val(data.amount);$('input[name=pay_descr]').val(data.pay_descr);$('input[name=target]').val(data.target);$('input[name=inpay]').val(data.inpay);
        $('[name=pay_id]').val(recipient);
        
       

      });
  }else{
	 $('[name=action]').val('new');

      $('input[name=pay_date]').val('');$('input[name=amount]').val('');$('input[name=pay_descr]').val('');$('input[name=target]').val('');$('input[name=inpay]').val('');  }
});

function operations_button_activate(){
	$('button.change_target').click(function(){
	  $.getJSON('/payments/change_target/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
}


function load_docs_list(){
  $("#payments").bs_grid({
    ajaxFetchDataURL: "/payments/get_table",
    row_primary_key: "pay_id",
    rowSelectionMode: false,

    columns: [
	 // {field: "pay_id", header: "ID"},
	        {field: "pay_date", header: "Дата"},
	        {field: "account", header: "Счет"},
	        {field: "amount", header: "Сумма"},
	        {field: "pay_descr", header: "Контрагент"},
	        {field: "target", header: "Назначение"},
	        {field: "inpay", header: "Направление"},
	        /* {field: "actions", header: "Операции", is_function: "yes"},*/
    ],
 
    sorting: [
      {sortingName: "ID", field: "pay_date", order: "descending"}
    ],
  });
  $("#payments").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

