function operations_button_activate(){
}
load_docs_list();

function load_docs_list(){
  $("#payments").bs_grid({
    ajaxFetchDataURL: "/payments/get_table",
    row_primary_key: "id",
    rowSelectionMode: false,

    columns: [
	  {field: "id", header: "ID"},
	        {field: "user_id", header: "Пользователь"},
	        {field: "lcc", header: "Л/С"},
	        {field: "sum", header: "Сумма"},
	        {field: "docnum", header: "№ квитанции"},
	        {field: "paydate", header: "Дата платежа"},
	        {field: "doc_id", header: "Квитанция"},
	        {field: "status", header: "Статус"},
	        {field: "actions", header: "Операции", is_function: "yes"},
    ],
 
    sorting: [
      {sortingName: "ID", field: "id", order: "ascending"}
    ],
  });
  $("#payments").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

