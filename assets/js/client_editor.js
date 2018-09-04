var new_row = '';
var clean_orderrow = '';
var services_list = [];

load_docs_list();

$("#compose-textarea").wysihtml5();
 
$("#sendmail").submit(function(e){
  e.preventDefault(); //STOP default action
});
 
$('button.mailsend').click(function(){
  var $btn = $(this).button('loading');
  var emailform = $("#sendmail");
  var postData = emailform.serializeArray();
  var formURL = emailform.attr("action");
  $.ajax(
  {
      url : formURL,
      type: "POST",
      data : postData,
      success:function(data, textStatus, jqXHR) 
      {
        $('#EmailModal').modal('hide');
        $btn.button('reset');
          //data: return data from server
      },
      error: function(jqXHR, textStatus, errorThrown) 
      {
         $btn.button('reset');
          //if fails      
      }
  });
});

$('button.save').click(function(){
  var $btn = $(this).button('loading');
  //$("#save_doc").submit(function(e) {
      var postData = $("#save_client").serializeArray();
      var formURL = $("#save_client").attr("action");
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
              //data: return data from server
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
             $btn.button('reset');
              //if fails      
          }
      });
     // e.preventDefault(); //STOP default action
  //});
   
  // $("#save_doc").submit(); //Submit  the FORM
});





$('#EmailModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
 
    $.getJSON('/panel/get-invoice-pdf/'+recipient, function(data){
      $('[name=mailfrom]').val(data.client_mail);
      $('[name=doc_number]').val(data.doc_number);
	   
      $('.mailbox-attachments .inv .mailbox-attachment-name span').text(data.inv.name);
      $('.mailbox-attachments .inv .mailbox-attachment-name').attr('href', data.inv.link); 
      $('.mailbox-attachments .inv .mailbox-attachment-size a').attr('href', data.inv.link);
       
      $('.mailbox-attachments .act .mailbox-attachment-name span').text(data.act.name);
      $('.mailbox-attachments .act .mailbox-attachment-name').attr('href', data.act.link);
      $('.mailbox-attachments .act .mailbox-attachment-size a').attr('href', data.act.link);
    });
});

$('#DownloadModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
 
    $.getJSON('/panel/get-invoice-pdf/'+recipient, function(data){
      $('.mailbox-attachments .inv .mailbox-attachment-name span').text(data.inv.name);
      $('.mailbox-attachments .inv .mailbox-attachment-name').attr('href', data.inv.link); 
      $('.mailbox-attachments .inv .mailbox-attachment-size a').attr('href', data.inv.link);
       
      $('.mailbox-attachments .act .mailbox-attachment-name span').text(data.act.name);
      $('.mailbox-attachments .act .mailbox-attachment-name').attr('href', data.act.link);
      $('.mailbox-attachments .act .mailbox-attachment-size a').attr('href', data.act.link);
    });
});

$('#ChangeStatusModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
 

      $('button.statuschange').attr('data-whatever', recipient); 
     
  
});

$('#myModal').on('show.bs.modal', function (event) { // Если открыли окно редактирования (создания) счета...
  var button = $(event.relatedTarget) // Кнопка, вызвавшая окно
  var recipient = button.data('whatever') // Берем из кнопки данные, их потом можно использовать для заголовка формы. Вот так:
	 /* 
	 var modal = $(this)
	 modal.find('.modal-title').text('New message to ' + recipient)
	 modal.find('.modal-body input').val(recipient)
	 */
	 if(clean_orderrow == ''){
		 clean_orderrow = $($(".orderrow").prop('outerHTML'));}
 
  if(recipient != 'new'){ // По кнопке узнаем, что это создание новго счета
    $('[name=action]').val('update');  // Меняем action формы
    $.getJSON('/panel/client-filldata/'+recipient, function(data){  // Берем данные с сервера (список клиентов и услуг)
        $('input[name=customer]').val(data['title']); 
        $('input[name=director]').val(data['director']); 
        $('input[name=email]').val(data['email']); 
        $('input[name=tel]').val(data['tel']); 
        $('input[name=client_id]').val(data['client_id']); 
      
    });
  }else{
      $('[name=action]').val('new'); 
      $('input[name=customer]').val(''); 
        $('input[name=director]').val(''); 
        $('input[name=email]').val(''); 
        $('input[name=tel]').val(''); 
  }
});


function operations_button_activate(){
	$('button.delclient').click(function(){
	  //var button = $(event.relatedTarget) // Button that triggered the modal
	  //var doc_number = button.data('whatever') // Extract info from data-* attributes
	  $.getJSON('/panel/client-delete/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});

}


function load_docs_list(){
	$("#clients").bs_grid({
 
        ajaxFetchDataURL: "/panel/clients-ajax",
        row_primary_key: "client_id",
        rowSelectionMode: false,

      columns: [
			{field: "client_id", header: "№ Клиента"},
      {field: "title", header: "Компания"},
			{field: "director", header: "Руководитель"},
			{field: "email", header: "Email"},
      {field: "tel", header: "Телефон"},
      {field: "actions", header: "Операции", is_function: "yes"},
      ],
 
      sorting: [
          {sortingName: "№ Клиента", field: "client_id", order: "ascending"}
      ],

      filterOptions: {
          filters: [
              {
                  filterName: "Рекламная кампания", "filterType": "text", field: "doc_id", filterLabel: "№ Счета",
                  excluded_operators: ["in", "not_in"],
                  filter_interface: [
                      {
                          filter_element: "input",
                          filter_element_attributes: {"type": "text"}
                      }
                  ]
              }
          ]
      }
  });
  $("#clients").bs_grid({
		onDisplay: function() {
		operations_button_activate();
		}
	});
}
