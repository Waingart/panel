function operations_button_activate(){
}
load_docs_list();

function load_docs_list(){
  $("#paydocs").bs_grid({
    ajaxFetchDataURL: "/paydocs/get_table",
    row_primary_key: "id",
    rowSelectionMode: false,

    columns: [
	        {field: "id", header: "№ квитанции"},
	        {field: "docdate", header: "Дата квитанции"},
	        {field: "sum", header: "Сумма"},
	        //{field: "doсfile", header: "Открыть квитанцию"},
	        {field: "status", header: "Статус"},
	        {field: "actions", header: "Операции", is_function: "yes"},
    ],
 
    sorting: [
      {sortingName: "Дата", field: "docdate", order: "ascending"}
    ],
  });
  $("#paydocs").bs_grid({
    onDisplay: function() {
	  operations_button_activate();
	}
  });
}

