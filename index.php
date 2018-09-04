<?
// ToDo:
// +URL parser
// +Navigation constructor
// +Hook dispatcher
// +Templator
// +DB class
// +Autorize controller
// +Core functions loader & run prepare


// Устанавливаем значение, которое будет проверено в файлах, 
// для которых запрещено прямое обращение
define('ALLOW_RUN', true); 

session_start();
//var_dump($_SESSION);
if (!empty($_COOKIE['sid'])) {
    // check session id in cookies
    session_id($_COOKIE['sid']);
}

  // Константы уровней доступа
const GUEST = 0; // неавторизованный посетитель
const USER = 1;  // авторизованный
const ADMIN = 2; // авторизованный с особыми полномочиями (пока не реализовано)

//print "<script>console.log('user id:'+'{$_SESSION["access_level"]}');</script>";

$user_auth = new Auth();
  // Выясняем уровень доступа текущего пользователя
if ($user_auth->isAuthorized()){
  $access_level =  $_SESSION["access_level"];
} else {
 $_SESSION["access_level"] = 0;
  $access_level = GUEST;
}
 
include('core/functions.php');



InintControllers(); //сканируем директорию контроллеров, инклудим их Inint файлы и получаем хуки и настройки, которые в них содержатся

//call_user_func(NAV);
HOOK::add_action("logo_mini", function(){return 'ИНГ';});
//HOOK::add_action("logo_lg", function(){return '<b>Abelar</b> Media';});
HOOK::add_action("logo_lg", function(){return 'ООО «Ингрид»';});
HOOK::add_action("window_title", function(){return 'ООО «Ингрид» – Ваш личный кабинет';});
//HOOK::window_title()

//HOOK::section_title();
//HOOK::user_name


HOOK::add_action('hook_name', new NAV);
HOOK::access_rule();



  // описываем соответствие URI и файлов модулей // Устанавливаем уровень доступа необходимый для некоторых URI
  URI_Shem::set_access_data_manual([
             ['url'=>'', 'access'=>[ USER], 'file'=>''],
          ['url'=>'reg', 'access'=>[GUEST, ADMIN, USER], 'file'=>'reg/regController.php'],
          ['url'=>'users', 'access'=>[ADMIN, USER], 'file'=>'users/usersController.php'],
          ['url'=>'auth', 'access'=>[GUEST, ADMIN, USER], 'file'=>'auth.ctl.php'],
          ['url'=>'chat', 'access'=>[ADMIN, USER], 'file'=>'chat/chatController.php'],
          ['url'=>'entities-generator', 'access'=>[ADMIN, USER], 'file'=>'entities-generator/entities_generator.php'],
          ['url'=>'paydocs', 'access'=>[GUEST, ADMIN, USER], 'file'=>'paydocs/paydocsController.php'],
          ['url'=>'1cexport', 'access'=>[GUEST, ADMIN, USER], 'file'=>'payments/1cExportController.php'],
         // ['url'=>'paydocs/load_docs', 'access'=>[GUEST, ADMIN, USER], 'file'=>'paydocs/paydocsController.php']
      ]
    );
    
$Securiry_Schem = URI_Shem::get_Securiry_Schem();  	
$URI_Schem = URI_Shem::get_URI_Schem();

	// получаем текущий URI
$full_uri = $_SERVER['REQUEST_URI'];
	
	// удаляем из URI строку запроса QUERY_STRING. Остается все, что было до "?"
$no_query_uri = str_replace('?'.$_SERVER['QUERY_STRING'], '', $full_uri); 
	
	// удаляем "/" с которго всегда начинается URI	
$no_query_uri = trim($no_query_uri, '/');
$no_query_uri = trim($no_query_uri, '\\');


	// делаем из URI массив его частей разделенных "/"	
$uri_dir_ierr = explode('/', $no_query_uri); 

  // проверяем наличие полномочий для доступа текущего пользователя
if(isset($Securiry_Schem[$uri_dir_ierr[0]])){              // установлены ли правила доступа для этого URI?
  if(!in_array($access_level, $Securiry_Schem[$uri_dir_ierr[0]])){
    header('Location: /auth?action=login');
    exit();
  }
}

// присоединяем соответствующий файл модуля по URI
if(isset($URI_Schem[$uri_dir_ierr[0]])){
  $URI_TO_CTL = array_slice ($uri_dir_ierr, 1);
  include('controllers/'.$URI_Schem[$uri_dir_ierr[0]]);
}else{
  header("HTTP/1.0 404 Not Found");
  exit();
}

function __autoload($class_name)
{
	if(include_once("classes/" . $class_name . ".class.php")) return;
}