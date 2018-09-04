function operations_button_activate(){
}
load_docs_list();

function load_docs_list(){
  $("#paydocs").bs_grid({
    ajaxFetchDataURL: "/paydocs/get_table",
    row_primary_key: "id",
    rowSelectionMode: false,

    columns: [
	  {field: "id", header: "ID"},
	        {field: "docnum", header: "№ квитанции"},
	        {field: "docdate", header: "Дата квитанции"},
	        {field: "sum", header: "Сумма"},
	        {field: "user_id", header: "Пользователь"},
	        {field: "lcc", header: "Л/С"},
	        {field: "status", header: "Статус"},
	        {field: "actions", header: "Операции", is_function: "yes"},
    ],
 
    sorting: [
      {sortingName: "ID", field: "id", order: "ascending"}
    ],
  });
  $("#paydocs").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

