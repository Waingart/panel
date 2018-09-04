<?
print " entities_generator.php - enabled";
// <<<<<<<<<< SERVICES  ==========================================================================================
/*
id
'titile','soc','stype','ed','ed_cost','smin','smax','available','configs'
*/
//not ok

 $AllEntitiesConfig['clients']['access_config'] = [
	'get_table'=>[
        'allow_action'=>[ADMIN], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_view'=>[
            ADMIN,
        ]
    ],
    'get_it'=>[
        'allow_action'=>[ADMIN], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_view'=>[
            ADMIN,
        ]
    ],
    'modal_editor'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['client_id'],
            ADMIN=>['client_id'],
        ],
        /*'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['user', 'service', 'volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount', 'status'],
            ADMIN=>['user', 'service', 'volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount', 'status'],
        ]*/
    ]
	/*'delete'=>[
        'allow_action'=>[ADMIN], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id'],
            ADMIN=>[],
        ],
        'record_allow_view'=>[
            ADMIN,
        ]
    ],
    
    'status'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
        ]
    ],
     'filldata'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
        ]
    ],
    
     'save'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
        ]
    ],
     'get-invoice-pdf'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
        ]
    ],
    'get-act-pdf'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ]
    ],
    'get_table'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ]
    ]*/
];

$AllEntitiesConfig['clients']['grid_config'] = array(
	'entitie' => 'clients',
	'dbtable' => 'clients',
	'id_field' => 'client_id',
	'visible_fields' => array( 'title','director','email','tel' ),
	'title_fields' => array( 'title'=>'Компания','director'=>'Контактное лицо','email'=>'Email','tel'=>'Телефон'),
	//'size_fields' => array('pay_date'=>'2', 'amount'=>'2', 'pay_descr'=>'2', 'target'=>'2', 'inpay'=>'2'),
	/*'fields_formatting' => array(
			'ed_cost'=>array('pre'=>'', 'post'=>'₽'),
			'soc'=>array(1=>'VK', 2=>'INSTAGRAM'),
			'available'=>array(1=>'Да', 2=>'Нет'),
			'stype'=>array(1=>'Лайки', 2=>'Автолайки', 3=>'Подписки', 4=>'Просмотры видео')
	),
	*/
	'actions' => array('update', 'delete', 'add_new', 'mailto'),
	'operations' => array(
	        'edit' => array('bticon'=>'edit', 'title'=>'Редактировать', 'url'=>'', 'modal_id'=>'editModal'),
	        'mailto' => array('bticon'=>'evenlope', 'title'=>'Написать email', 'url'=>'', 'modal_id'=>'mailtoModal'),
	        'delete' => array('bticon'=>'trash', 'title'=>'Удаление клиента', 'url'=>'', 'modal_id'=>'deleteModal'),
	        'statistic' => array('bticon'=>'trash', 'title'=>'Сводные данные по клиенту', 'url'=>'', 'modal_id'=>'statisticModal'),
	),
	'modals' => array(
	    'editModal' => array('title'=>'Редактирование карточки клиента', 'type'=>0, 'content'=>'edit.modal.html'),
	    'mailtoModal' => array('title'=>'Написать email', 'type'=>0, 'content'=>'mailto.modal.html'),
	    'deleteModal' => array('title'=>'Удаление клиента', 'type'=>0, 'content'=>'delete.modal.html'),
	    'statisticModal' => array('title'=>'Сводные данные по клиенту', 'type'=>0, 'content'=>'statistic.modal.html')
	   
	)
);
$AllEntitiesConfig['clients']['nav_config'] = [
    'menu_title' =>[ADMIN=>'Текущие клиенты'] ,
    'url' => 'clients',
    'url_access' => [ADMIN]
];

 /*clone delete status filldata save get-invoice-pdf get-act-pdf ajax get_table 
 $AllEntitiesConfig['invoices']['access_config'] = [
	'get_table'=>[
        'allow_action'=>[ADMIN], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_view'=>[
            ADMIN,
        ]
    ],
	'delete'=>[
        'allow_action'=>[ADMIN], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id'],
            ADMIN=>[],
        ],
        'record_allow_view'=>[
            ADMIN,
        ]
    ],
    'status'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
        ]
    ],
     'filldata'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
        ]
    ],
     'save'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
        ]
    ],
     'get-invoice-pdf'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
        ]
    ],
    'get-act-pdf'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ]
    ],
    'get_table'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ]
    ]
];

$AllEntitiesConfig['invoices']['grid_config'] = array(
	'entitie' => 'invoices',
	'dbtable' => 'invoices',
	'id_field' => 'id',
	'visible_fields' => array('title', 'descr','soc','stype','ed','ed_cost','smin','smax','available','configs' ),
	'title_fields' => array('title'=>'Название услуги','descr'=>'Описание услуги', 'soc'=>'Соцсеть','stype'=>'Тип услуги','ed'=>'Единица','ed_cost'=>'Стоимость единицы','smin'=>'Минимальный заказ','smax'=>'Максимальный заказ','available'=>'Доступна','configs'=>'Досутпные настройки'),
	//'size_fields' => array('pay_date'=>'2', 'amount'=>'2', 'pay_descr'=>'2', 'target'=>'2', 'inpay'=>'2'),
	'fields_formatting' => array(
			'ed_cost'=>array('pre'=>'', 'post'=>'₽'),
			'soc'=>array(1=>'VK', 2=>'INSTAGRAM'),
			'available'=>array(1=>'Да', 2=>'Нет'),
			'stype'=>array(1=>'Лайки', 2=>'Автолайки', 3=>'Подписки', 4=>'Просмотры видео')
	),
	'actions' => array('update', 'delete', 'add_new'),
	'operations' => array(
	        'edit' => array('bticon'=>'clock', 'title'=>'Изменить услугу', 'url'=>'', 'modal_id'=>'editModal'),
	),
	'modals' => array(
	    'editModal' => array('title'=>'Изменение услуги', 'type'=>0, 'content'=>'edit.modal.html')
	)
);
$AllEntitiesConfig['services']['nav_config'] = [
    'menu_title' =>[ADMIN=>'Настройка услуг'] ,
    'url' => 'services',
    'url_access' => [ADMIN]
];

/*
$AllEntitiesConfig['services']['db_config'] = [
    'table' => 'services',
    'fields' =>[
        'id' => ['type'=>'int(11)', 'id'=>1],
		'title' => ['type'=>'text', 'id'=>0],
		'descr' => ['type'=>'text', 'id'=>0],
        'soc' => ['type'=>'int(11)', 'id'=>0],
        'stype' => ['type'=>'int(11)', 'id'=>0],
		'ed' => ['type'=>'int(11)', 'id'=>0],
		'ed_cost' => ['type'=>'float', 'id'=>0],
		'smin' => ['type'=>'int(11)', 'id'=>0],
		'smax' => ['type'=>'int(11)', 'id'=>0],
		'available' => ['type'=>'int(11)', 'id'=>0],
		'configs' => ['type'=>'text', 'id'=>0],
    ]
];

*/
// SERVICES >>>>>>>> =============================================================================================

// <<<<<<<<<< TASKS ==========================================================================================
/*
$AllEntitiesConfig['tasks']['access_config'] = [
	'get_table'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'record_allow_view'=>[
            ADMIN,
            'OWNER' =>['id']
        ]
    ],
	'get_it'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id'],
            ADMIN=>[],
        ],
        'record_allow_view'=>[
            ADMIN,
            'OWNER' =>['id']
        ]
    ],
    'update'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['user', 'service', 'volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount', 'status'],
            ADMIN=>['user', 'service', 'volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount'],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
           // 'OWNER' =>['id']
        ]
    ],
	'stop'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['user', 'service', 'volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount'],
            ADMIN=>['user', 'service', 'volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount'],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
           // 'OWNER' =>['id']
        ]
    ],
    'modal_editor'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['user', 'service', 'volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount', 'status'],
            ADMIN=>['user', 'service', 'volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount', 'status'],
        ]
    ]
];

$AllEntitiesConfig['tasks']['grid_config'] = array(
	'entitie' => 'tasks',
	'dbtable' => 'tasks',
	'id_field' => 'id',
	'visible_fields' => array('user', 'service', 'volume', 'complete', 'price', 'started', 'link', 'soctype', 'startcount', 'status' ),
	'title_fields' => array('user'=>'Пользователь', 'service'=>'Услуга', 'volume'=>'Объем заказа', 'complete'=>'Уже выполнено', 'price'=>'Стоимость', 'started'=>'Запущен', 'link'=>'Ссылка', 'soctype'=>'Соцсеть', 'startcount'=> 'Было изначально', 'status'=> 'Статус'),
	//'size_fields' => array('pay_date'=>'2', 'amount'=>'2', 'pay_descr'=>'2', 'target'=>'2', 'inpay'=>'2'),
	'fields_formatting' => array(
			'price'=>array('pre'=>'', 'post'=>'₽'),
			'soctype'=>array(1=>'VK', 2=>'INSTAGRAM'),
			'status'=>array(1=>'Выполняется', 2=>'Завершено', 3=>'Остановлено', 4=>'Ошибка')
	),
	'actions' => array('update', 'delete', 'add_new', 'stop'),
	'operations' => array(
	        'stop' => array('bticon'=>'clock', 'title'=>'Изменить данные пользователя', 'url'=>'', 'modal_id'=>'updateModal'),
	),
	'modals' => array(
	    'stopModal' => array('title'=>'Остановить задание?', 'type'=>0, 'content'=>'stop.modal.html')
	)
);

$AllEntitiesConfig['tasks']['db_config'] = [
    'table' => 'tasks',
    'fields' =>[
        'id' => ['type'=>'int(11)', 'id'=>1, 'ai'=>1],
        'user' => ['type'=>'int(11)', 'id'=>0],
		'service' => ['type'=>'int(11)', 'id'=>0],
		'volume' => ['type'=>'int(11)', 'id'=>0],
        'complete' => ['type'=>'int(11)', 'id'=>0],
        'price' => ['type'=>'float', 'id'=>0],
        'started' => ['type'=>'date', 'id'=>0],
        'link' => ['type'=>'text', 'id'=>0],
        'soctype' => ['type'=>'int(11)', 'id'=>0],
        'startcount' => ['type'=>'int(11)', 'id'=>0],
		'status' => ['type'=>'int(11)', 'id'=>0]
    ]
];
$AllEntitiesConfig['tasks']['nav_config'] = [
    'menu_title' =>[ADMIN=>'Исполнение заданий', USER=>'Исполнение заданий'] ,
    'url' => 'tasks',
    'url_access' => [ADMIN, USER]
];
*/
// TASKS >>>>>>>> =============================================================================================
// <<<<<<<<<< USERS ==========================================================================================
/*
$AllEntitiesConfig['users']['access_config'] = [
	'get_table'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id'],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['lcc', 'uname', 'ufam', 'uoth', 'pass_num', 'email', 'phone', 'password', 'access', 'salt', 'id'],
            ADMIN=>['lcc', 'id'],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
           // 'OWNER' =>['id']
        ],
        'record_allow_view'=>[
            ADMIN,
            'OWNER' =>['id']
        ]
    ],
	'get_it'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id'],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['lcc', 'uname', 'ufam', 'uoth', 'pass_num', 'email', 'phone', 'password', 'access', 'salt', 'id'],
            ADMIN=>['lcc', 'id'],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
            // 'OWNER' =>['id']
        ],
        'record_allow_view'=>[
            ADMIN,
            'OWNER' =>['id']
        ]
    ],
    'update'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id'],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['lcc', 'uname', 'ufam', 'uoth', 'pass_num', 'email', 'phone', 'password', 'access', 'salt', 'id'],
            ADMIN=>['lcc', 'id'],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            ADMIN,
           // 'OWNER' =>['id']
        ],
        'record_allow_view'=>[
            ADMIN,
            'OWNER' =>['id']
        ]
    ],
    'modal_editor'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>[],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['lcc', 'uname', 'ufam', 'uoth', 'pass_num', 'email', 'phone', 'password', 'access', 'salt', 'id'],
            ADMIN=>['id', 'lcc'],
        ]
    ]
];

$AllEntitiesConfig['users']['grid_config'] = array(
	'entitie' => 'users',
	'dbtable' => 'users',
	'id_field' => 'id',
	'visible_fields' => array('lcc', 'uname', 'ufam', 'uoth', 'pass_num', 'email', 'phone'),
	'title_fields' => array('lcc'=>'№ лицевого счета', 'uname'=>'Имя', 'ufam'=>'Фамилия', 'uoth'=>'Отчество', 'pass_num'=>'Серия и номер паспорта', 'email'=>'Email', 'phone'=>'Телефон'),
	//'size_fields' => array('pay_date'=>'2', 'amount'=>'2', 'pay_descr'=>'2', 'target'=>'2', 'inpay'=>'2'),
	/*'fields_formatting' => array(
			'target'=>array(1=>'Платежи исполнителям', 2=>'Личные расходы', 3=>'Орг. расходы'),
			'inpay'=>array(1=>'Приход', 2=>'Расход'),
	),*/
	/*
	//'tech_fields' => array('user_id'),
	'actions' => array('update'),
	'operations' => array(
	        'update' => array('bticon'=>'clock', 'title'=>'Изменить данные пользователя', 'url'=>'', 'modal_id'=>'updateModal'),
	),
	'modals' => array(
	    'updateModal' => array('title'=>'Изменить данные пользователя', 'type'=>0, 'content'=>'update.modal.html')
	)
);
$AllEntitiesConfig['users']['db_config'] = [
    'table' => 'users',
    'fields' =>[
        'id' => ['type'=>'int(11)', 'id'=>1, 'ai'=>1],
        'lcc' => ['type'=>'text', 'id'=>0],
		'password' => ['type'=>'text', 'id'=>0],
        'uname' => ['type'=>'text', 'id'=>0],
        'ufam' => ['type'=>'text', 'id'=>0],
        'uoth' => ['type'=>'text', 'id'=>0],
        'pass_num' => ['type'=>'text', 'id'=>0],
        'email' => ['type'=>'text', 'id'=>0],
        'phone' => ['type'=>'text', 'id'=>0],
		'salt' => ['type'=>'text', 'id'=>0],
		'access' => ['type'=>'int(11)', 'id'=>0]
    ]
];
$AllEntitiesConfig['users']['nav_config'] = [
    'menu_title' =>[ADMIN=>'Пользователи', USER=>'Мой профиль'] ,
    'url' => 'users',
    'url_access' => [ADMIN, USER]
];
*/
// USERS >>>>>>>> =============================================================================================

// PAYMENTS <<<<<<<<< =========================================================================================
/*
$AllEntitiesConfig['payments']['access_config'] = [
	'get_table'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id'],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['id', 'docnum', 'sum', 'docnum', 'paydate', 'lcc', 'doc_id', 'status', 'user_id'],
            ADMIN=>['id', 'docnum', 'sum', 'docnum', 'paydate', 'lcc', 'doc_id', 'status', 'user_id'],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            //ADMIN,
            //'OWNER' =>['user_id']
        ],
        'record_allow_view'=>[
            ADMIN,
            'OWNER' =>['user_id']
        ]
    ],'get_it'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id'],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['id', 'docnum', 'sum', 'docnum', 'paydate', 'lcc', 'doc_id', 'status', 'user_id'],
            ADMIN=>['id', 'docnum', 'sum', 'docnum', 'paydate', 'lcc', 'doc_id', 'status', 'user_id'],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            //ADMIN,
            //'OWNER' =>['user_id']
        ],
        'record_allow_view'=>[
            ADMIN,
            'OWNER' =>['user_id']
        ]
    ]
];
/*
$grid_config = array(
	'entitie' => 'task',
	'dbtable' => 'tasks',
	'id_field' => 'task_id',
	'visible_fields' => array('service', 'soctype', 'volume', 'complete', 'price', 'started', 'link',  'startcount', 'status'),
	'fields_formatting' => array(
			'price'=>array('pre'=>'', 'post'=>'₽'),
			'soctype'=>array(1=>'VK', 2=>'INSTAGRAM'),
			'status'=>array(1=>'Выполняется', 2=>'Завершено', 3=>'Остановлено', 4=>'Ошибка')
	),
	'tech_fields' => array('task_id'),
	'actions' => array('update', 'delete', 'add_new', 'stop')
);
*/
/*
$AllEntitiesConfig['payments']['grid_config'] = array(
	'entitie' => 'payments',
	'dbtable' => 'payments',
	'id_field' => 'id',
	'visible_fields' => array('user_id', 'lcc', 'sum', 'docnum', 'paydate',  'doc_id', 'status'),
	'title_fields' => array('user_id'=>'Пользователь', 'lcc'=>'Л/С', 'sum'=>'Сумма', 'docnum'=>'№ квитанции', 'paydate'=>'Дата платежа', 'doc_id'=>'Квитанция', 'status' => 'Статус'),
	//'size_fields' => array('pay_date'=>'2', 'amount'=>'2', 'pay_descr'=>'2', 'target'=>'2', 'inpay'=>'2'),
	'fields_formatting' => array(
			'status'=>array(1=>'Успешно', 2=>'Ошибка!'),
	),
	//'tech_fields' => array('user_id'),
	'actions' => [],

);

$AllEntitiesConfig['payments']['db_config'] = [
    'table' => 'payments',
    'fields' =>[
        'id' => ['type'=>'int(11)', 'id'=>1],
		'user_id' => ['type'=>'int(11)', 'id'=>0],
        'lcc' => ['type'=>'text', 'id'=>0],
        'sum' => ['type'=>'float', 'id'=>0],
        'doc_id' => ['type'=>'int(11)', 'id'=>0],
		'docnum' => ['type'=>'int(11)', 'id'=>0],
		'paydate' => ['type'=>'date', 'id'=>0],
		'status' => ['type'=>'int(11)', 'id'=>0],
    ]
];

$AllEntitiesConfig['payments']['nav_config'] = [
    'menu_title' =>[ADMIN=>'Платежи', USER=>'Мои платежи'] ,
    'url' => 'payments',
    'url_access' => [ADMIN, USER]
];
*/
// PAYMENTS >>>>>>>>> =========================================================================================

// PAYDOCS <<<<<<<<< ==========================================================================================
/*

    Операции: оплатить, смотреть квитанцию без кнопки по ссылке
*/
/*
$AllEntitiesConfig['paydocs']['access_config'] = [
	'get_table'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id', 'user_id', 'doc_id', 'lcc'],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['id', 'docnum', 'docdate', 'sum', 'doc_id', 'user_id',  'lcc', 'status'],
            ADMIN=>['id', 'docnum', 'docdate', 'sum', 'doc_id', 'user_id',  'lcc', 'status'],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            //ADMIN,
            //'OWNER' =>['user_id']
        ],
        'record_allow_view'=>[
            ADMIN,
            'OWNER' =>['user_id']
        ]
    ],
	'get_it'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id'],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['id', 'docnum', 'sum', 'docnum', 'paydate', 'lcc', 'doc_id', 'status'],
            ADMIN=>['id', 'docnum', 'sum', 'docnum', 'paydate', 'lcc', 'doc_id', 'status'],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            //ADMIN,
            //'OWNER' =>['user_id']
        ],
        'record_allow_view'=>[
            ADMIN,
            'OWNER' =>['user_id']
        ]
    ],
	'go_pay'=>[
		'allow_action'=>[USER], 
        'deny_action'=>[GUEST], 
	]
];

// OK
$AllEntitiesConfig['paydocs']['grid_config'] = array(
	'entitie' => 'paydocs',
	'dbtable' => 'paydocs',
	'id_field' => 'id',
	'visible_fields' => array('docnum', 'docdate', 'sum', 'user_id',  'lcc', 'status'),
	'title_fields' => array('docnum'=>'№ квитанции', 'docdate'=>'Дата квитанции', 'sum'=>'Сумма', 'user_id' => 'Пользователь',  'lcc'=>'Л/С', 'status' => 'Статус'),
	//'size_fields' => array('pay_date'=>'2', 'amount'=>'2', 'pay_descr'=>'2', 'target'=>'2', 'inpay'=>'2'),
	'fields_formatting' => array(
			'status'=>array(1=>'Оплачено', 2=>'Ошибка!', 3=>'Не оплачено',),
	),
	//'tech_fields' => array('user_id'),
	'actions' => [],

);

//OK
$AllEntitiesConfig['paydocs']['db_config'] = [
    'table' => 'paydocs',
    'fields' =>[
        'id' => ['type'=>'int(11)', 'id'=>1],
        'lcc' => ['type'=>'text', 'id'=>0],
        'sum' => ['type'=>'float', 'id'=>0],
		'docnum' => ['type'=>'int(11)', 'id'=>0],
		'docdate' => ['type'=>'date', 'id'=>0],
		'user_id' => ['type'=>'int(11)', 'id'=>0],
		'status' => ['type'=>'int(11)', 'id'=>0],
    ]
];
$AllEntitiesConfig['paydocs']['nav_config'] = [
    'menu_title' =>[ADMIN=>'Квитанции', USER=>'Квитанции'] ,
    'url' => 'paydocs',
    'url_access' => [ADMIN, USER]
];
*/
// PAYDOCS >>>>>>>>> ==========================================================================================

// REQUESTS <<<<<<<<< ==========================================================================================

// ok
/*
$AllEntitiesConfig['requests']['access_config'] = [
	'get_table'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id', 'user_id'],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['id', 'req_subj', 'req_time', 'user_id', 'user_name', 'user_email'],
            ADMIN=>['id', 'req_subj', 'req_time', 'user_id', 'user_name', 'user_email'],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            //ADMIN,
            //'OWNER' =>['user_id']
        ],
        'record_allow_view'=>[
            ADMIN,
            'OWNER' =>['user_id']
        ]
    ],
	'get_it'=>[
        'allow_action'=>[ADMIN,USER], 
        'deny_action'=>[GUEST], 
        'field_disallow_view'=>[ // эти поля не будут видны в интерфейсе у перечисленных ролей
            USER=>['id', 'user_id', 'user_name', 'user_email'],
            ADMIN=>[],
        ],
        'field_disallow_update'=>[ // эти поля будут запрещены к редактированию в интерфейсе у перечисленных ролей
            USER=>['id', 'req_subj', 'req_time', 'user_id', 'user_name', 'user_email'],
            ADMIN=>['id', 'req_subj', 'req_time', 'user_id', 'user_name', 'user_email'],
        ],
        'record_allow_update'=>[ // внести изменения в эти записи смогут только перечисленные роли или OWNER
            //ADMIN,
            //'OWNER' =>['user_id']
        ],
        'record_allow_view'=>[
            ADMIN,
            'OWNER' =>['user_id']
        ]
    ]
];

// ok
$AllEntitiesConfig['requests']['grid_config'] = array(
	'entitie' => 'requests',
	'dbtable' => 'requests',
	'id_field' => 'id',
	'visible_fields' => array('id', 'req_subj', 'req_time', 'user_id', 'user_name', 'user_email', 'status'),
	'title_fields' => array('id', 'req_subj' => 'Тема запроса', 'req_time' => 'Создан', 'user_id' => 'Пользователь', 'user_name' => 'Имя', 'user_email' => 'Email', 'status' => 'Статус'),
	//'size_fields' => array('pay_date'=>'2', 'amount'=>'2', 'pay_descr'=>'2', 'target'=>'2', 'inpay'=>'2'),
	'fields_formatting' => array(
			'status'=>array(1=>"Ожидает Вашего ответа", 2=>"Ожидает ответа сотрудника", 3=>"Завершен"),
	),
	//'tech_fields' => array('user_id'),
	'actions' => ['manager_answer', 'user_answer', 'close', 'reopen'],

	'operations' => array(
	        'close' => array('bticon'=>'clock', 'title'=>'Спасибо, проблема решена', 'url'=>'', 'modal_id'=>'closeModal'),
			'reopen' => array('bticon'=>'clock', 'title'=>'Возобновить диалог', 'url'=>'', 'modal_id'=>'reopenModal'),
	),
	'modals' => array(
	    'closeModal' => array('title'=>'Подтвердите действие', 'type'=>0, 'content'=>'update.modal.html'),
		'reopenModal' => array('title'=>'Подтвердите действие', 'type'=>0, 'content'=>'update.modal.html')
	)

);

// ok
$AllEntitiesConfig['requests']['db_config'] = [
    'table' => 'requests',
    'fields' =>[
        'id' => ['type'=>'int(11)', 'id'=>1],
		'req_subj' => ['type'=>'text', 'id'=>0],
        'req_time' => ['type'=>'datetime', 'id'=>0],
        'user_name' => ['type'=>'text', 'id'=>0],
		'user_email' => ['type'=>'text', 'id'=>0],
		'user_id' => ['type'=>'int(11)', 'id'=>0],
		'status' => ['type'=>'int(11)', 'id'=>0],
    ]
];
$AllEntitiesConfig['requests']['nav_config'] = [
    'menu_title' =>[ADMIN=>'Обращения', USER=>'Мои запросы'] ,
    'url' => 'requests',
    'url_access' => [ADMIN, USER]
];
*/
// REQUESTS >>>>>>>>> ==========================================================================================



foreach($AllEntitiesConfig as $entitie => $EntitieConfig){
   $result_log = new EntitiesGenerator($EntitieConfig);
}

