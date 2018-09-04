
$(function() {
    var defaultData = [
        {
        text: 'Новая сущность',
        href: '#parent1',
        color: "white",
        backColor: "#605ca8",
        tags: ['4']
        },
      {
        text: 'Сущность: Счета',
        href: '#parent1',
        tags: ['4'],
        nodes: [
          {
            text: 'Набор полей',
            href: '#child1',
            tags: ['2'],
            nodes: [
              {
                text: 'Добавить поле',
              //  selectable: false,
                href: '#grandchild1',
                color: "white",
                backColor: "#605ca8",
                tags: ['clients', 'fields', 'id']
              },
              {
                text: 'Поле `id`',
                href: '#grandchild1',
                tags: ['clients', 'fields', 'id']
              },
              {
                text: 'Поле `summ`',
                href: '#grandchild2',
                tags: ['0']
              }
            ]
          },
          {
            text: 'Кнопки операций',
            href: '#child2',
            tags: ['0']
          }
          ,{
            text: 'Действия с данными',
            href: '#child2',
            tags: ['0']
          },
          {
            text: 'Модальные окна',
            href: '#child2',
            tags: ['0']
          }
        ]
      },
      {
        text: 'Сущность: Клиенты',
        href: '#parent2',
        tags: ['0'],
        nodes: [
          {
            text: 'Набор полей',
            href: '#child1',
            tags: ['clients', 'fields'],
            nodes: [
              {
                text: 'Добавить поле',
               // selectable: false,
                href: '#grandchild1',
                color: "white",
                backColor: "#605ca8",
                tags: ['clients', 'fields', 'id']
              },
              {
                text: 'id',
                href: '#grandchild1',
                tags: ['clients', 'fields', 'id']
              },
              {
                text: 'fio',
                href: '#grandchild2',
                tags: ['0']
              }
            ]
          },
          {
            text: 'Операции',
            href: '#child2',
            tags: ['0']
          }
          ,
          {
            text: 'Модальные окна',
            href: '#child2',
            tags: ['0']
          }
        ]
      }
    ];
    $('#treeview1').treeview({
      data: defaultData,
      levels: 1,
      onNodeSelected: function(event, node) {
              $('#treeview1').prepend('<p>' + node.text + ' was selected</p>');
              if(node.tags[2]=="id"){
                   var clientData = [
                        { name: "Rey Bango", id: 1 },
                    ];
                 
                $("#configModals").tmpl(clientData).appendTo( "#configForms" );
                $("#configForms").html('');
                $("#configFields").tmpl(clientData).appendTo( "#configForms" );
              }
            },
    });

});