function join(arr /*, separator */) {
  var separator = arguments.length > 1 ? arguments[1] : ", ";
  return arr.filter(function(n){return n}).join(separator);
}

function typeDescription(type) {
  var TYPES = {
    'INDIVIDUAL': 'Индивидуальный предприниматель',
    'LEGAL': 'Организация'
  }
  return TYPES[type];
}

function showSuggestion(suggestion) {
  console.log(suggestion);
  var data = suggestion.data;
  if (!data)
    return;
  
  $("#type").text(
    typeDescription(data.type) + " (" + data.type + ")"
  );

  if (data.name)
    $("#name_short").val(join([data.opf && data.opf.short || "", data.name.short || data.name.full], " "));
  
  if (data.name && data.name.full)
    $("#name_full").val(join([data.opf && data.opf.full || "", data.name.full], " "));
  
  $("#inn_kpp").val(join([data.inn, data.kpp], " / "));
  
  if (data.address)
    $("#address").val(data.address.value);
  
  if (data.ogrn)
    $("#ogrn").val(data.ogrn);
    
  if (data.management && data.management.name)
    $("#managmentname").val(data.management.name);
    if(data.type == 'LEGAL'){
        if (data.management && data.management.name)
            $("#managmentname").val(data.management.name);
    }else{
        if (data.name.full)
            $("#managmentname").val(data.name.full);  
    }
  
    if (data.management && data.management.post){
         $("#post").text(data.management.post);   
    }else{
        $("#post").text('Руководитель');   
    }
}

$("#party").suggestions({
  token: "b780011c53d1acd99019797fe2ce510ad18e4ad8",
  type: "PARTY",
  count: 5,
  /* Вызывается, когда пользователь выбирает одну из подсказок */
  onSelect: showSuggestion
});
