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
$('#myModal').on('change', 'select.services', function(eventObject){ // Если происходит изменение выбранной услуги в списке...
	set_service_selected($(this));
});
function set_service_selected(elem, title){
	if(elem.val() != '0'){ // если выбрана настоящая услуга, а не подсказка

	  elem.parent().find('.name_services').remove();		// Удаляем старый элемент input
	  if(title){
		  srv_val = title; // Устанавливаем переданное название услуги
	  }else{
		  var srv_val = elem.parent().find('select :selected').html(); // получаем выбранное название услуги
	  }
	  
	  elem.parent().find('select').hide(); // прячем select
	  elem.after('<input type="text" name="title[]" value="'+srv_val+'"class="form-control name_services services"/>'); // Вставляем после него новое поле, чтобы можно было вручную исправить название услуги (например добавить пояснение)
	}
}
$('button.save').click(function(){
  var $btn = $(this).button('loading');
  //$("#save_doc").submit(function(e) {
      var postData = $("#save_user").serializeArray();
      var formURL = $("#save_user").attr("action");
      $.ajax(
      {
          url : formURL,
          type: "POST",
          data : postData,
          success:function(data, textStatus, jqXHR) 
          {
            $('#myModal').modal('hide');
            $btn.button('reset');
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

$('#reservation').datepicker({
format: 'yyyy-mm-dd',
todayBtn:true,
todayHighlight:true,
  language:'ru'
});

$('button.add_row').click(function(){
  add_orderrow();
  fill_services();
  return false;
});

function operations_button_activate(){
	$('button.clone').click(function(){
	  //var button = $(event.relatedTarget) // Button that triggered the modal
	  //var doc_number = button.data('whatever') // Extract info from data-* attributes
	  $.getJSON('/panel/invoice-clone/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
	$('button.trash').click(function(){
	  //var button = $(event.relatedTarget) // Button that triggered the modal
	  //var doc_number = button.data('whatever') // Extract info from data-* attributes
	  $.getJSON('/panel/invoice-delete/'+$(this).data('whatever'), function(data){
		  load_docs_list();
	  });
	  return false;
	});
	$('button.statuschange').click(function(){
	  //var button = $(event.relatedTarget) // Button that triggered the modal
	  //var doc_number = button.data('whatever') // Extract info from data-* attributes
	  $.getJSON('/panel/invoice-status/'+$(this).data('whatever')+'/'+$(this).data('target'), function(data){
		 // load_docs_list();
	  });
	  return false;
	});
}


$('#myModal').on('click', '.remove_orderrow', function(eventObject){
  $(eventObject.target).closest('.orderrow').remove();
  refresh_total();
});
$('#myModal').on('keyup', '.name_count', function(eventObject){
  var service_count = $(eventObject.target).val();
  var service_price = $(eventObject.target).closest('.orderrow').find('.name_price').eq(0).val();
  // .val()
  var name_rowtotal = $(eventObject.target).closest('.orderrow').find('.name_rowtotal').eq(0);
  
  if (typeof parseInt(service_price) === 'number'){
    if (typeof parseInt(service_count) === 'number'){
      var rowtotal = service_count * service_price;
      name_rowtotal.val(rowtotal);
      refresh_total();
    }
  }
    
});
$('#myModal').on('keyup', '.name_price', function(eventObject){
  var service_count = $(eventObject.target).val();
  var service_price = $(eventObject.target).closest('.orderrow').find('.name_count').eq(0).val();
  // .val()
  var name_rowtotal = $(eventObject.target).closest('.orderrow').find('.name_rowtotal').eq(0);
  
  if (typeof parseInt(service_price) === 'number'){
    if (typeof parseInt(service_count) === 'number'){
      var rowtotal = parseInt(service_count) * parseInt(service_price);
      name_rowtotal.val(rowtotal);
      refresh_total();
    }
  }
    
});
function refresh_total(){
  var total;
  total = 0;
  $('.name_rowtotal').each(function(){
    if ($(this).val() == '') 
       $(this).val('0');
    total = total + parseInt($(this).val());
  });
  $('.name_total').val(total);
}

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
 
  if(recipient == 'new'){ // По кнопке узнаем, что это создание новго счета
    $('[name=action]').val('new');  // Меняем action формы
  } else {
    if (recipient != undefined){
      $.getJSON('/panel/user-filldata/'+recipient, function(data){
       $('[name=action]').val('update');

      
	    $('[name=fio]').val(data.fio);
        $('[name=email]').val(data.email);
        $('[name=username]').val(data.username);
        $('[name=mobile]').val(data.mobile);
        $('[name=user_id]').val(recipient);
        
       

      });
    }
  }
});





function add_orderrow(){
 /*
 if (new_row !='') {
        
         
	   var next_num = $(".orderrow").length;
	   
		new_row.find('.services').attr('name', 'service['+next_num+']');
        new_row.find('.name_count').attr('name', 'count['+next_num+']');
        new_row.find('.name_price').attr('name', 'price['+next_num+']');
        new_row.find('.name_metric').attr('name', 'metric['+next_num+']');
        new_row.find('.name_rowtotal').attr('name', 'rowtotal['+next_num+']');
		
		new_row.find('.services').attr('name', 'service[]');
        new_row.find('.name_count').attr('name', 'count[]');
        new_row.find('.name_price').attr('name', 'price[]');
        new_row.find('.name_metric').attr('name', 'metric[]');
        new_row.find('.name_rowtotal').attr('name', 'rowtotal[]');
		
		
       
    } else {
      new_row = clean_orderrow.clone();
	  $(".row.title").after(new_row);
      //new_row.addClass('new');
      //new_row.find('label').remove();
    }
	*/
	if($(".orderrow").length){
			$(".orderrow:last").after(clean_orderrow.clone());
		}else{
			$(".row.title").after(clean_orderrow.clone());
		}
    
}
function clear_doc(){
	$(".orderrow").remove();
	new_row ='';
  /*
  $(".orderrow").not(':first').remove();
  $(".orderrow").find('.name_count').val('');
  $(".orderrow").find('.name_price').val('');
  $(".orderrow").find('.name_price').val('');
  $(".orderrow").find('.name_rowtotal').val('');
  $(".name_total").val('');
  $('[name=inv_num]').val('');
  $('[name=doc_date]').val('');
  $('[name=act_num]').val('');
  */
  
}
function fill_services(){
  var items = [];
  items.push('<option value="0">Выберите услугу из списка...</option>');
  items.push('<option value="custom">Ввести вручную...</option>');
  $.each(services_list, function(key, srv){
    items.push('<option value="' + srv.service_id + '">' + srv.title + '</option>');
  });
    $(".orderrow:last").find('.services').html(items.join(''));
}
function load_docs_list(){
	$("#users").bs_grid({
 
        ajaxFetchDataURL: "/panel/users-ajax",
        row_primary_key: "id",
        rowSelectionMode: false,
 
      columns: [	
          {field: "username", header: "Логин"},
          	{field: "email", header: "Email"},
			{field: "fio", header: "Имя"},
      {field: "mobile", header: "Телефон"},
      {field: "actions", header: "Операции", is_function: "yes"},
      ],
 
      sorting: [
          {sortingName: "Логин", field: "username", order: "descending"}
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
  $("#users").bs_grid({
		onDisplay: function() {
		operations_button_activate();
		}
	});
}
