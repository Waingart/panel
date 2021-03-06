<?
// ToDo:
// +URL parser
// Navigation constructor
// Hook dispatcher
// +Templator
// +DB class
// +Autorize controller
// Core functions loader & run prepare


// Устанавливаем значение, которое будет проверено в файлах, 
// для которых запрещено прямое обращение
define('ALLOW_RUN', true); 

session_start();

if (!empty($_COOKIE['sid'])) {
    // check session id in cookies
    session_id($_COOKIE['sid']);
}

  // Константы уровней доступа
const GUEST = 0; // неавторизованный посетитель
const USER = 1;  // авторизованный
const ADMIN = 2; // авторизованный с особыми полномочиями (пока не реализовано)

$user_auth = new Auth();
  // Выясняем уровень доступа текущего пользователя
if ($user_auth->isAuthorized()){
  $access_level =  $_SESSION["access_level"];
} else {
 $_SESSION["access_level"] = 0;
  $access_level = GUEST;
}

include('core/functions.php');
if(isset($_POST['token'])){
    $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
    $user = json_decode($s, true);
    $db = new db();
    $usr = $db->select('users', array('socid'=>$user['identity'] ));
    if($usr){
        
        if($user_auth->socauthorize($user['identity'])){
            $access_level =  $_SESSION["access_level"];
            if($user['first_name'] != '')
                $data['uname'] = $user['first_name'];
            if($user['last_name'] != '')
                $data['ufam'] = $user['last_name'];
            if($user['email'] != '')
                $data['email'] = $user['email'];
            if($user['photo'] != '')
                $data['userpic'] = $user['photo'];
            $usr = $db->update('users', $data, array('id'=>$_SESSION["user_id"]));
        }
    }else{
        if($_SESSION["user_id"]){
             $data['socid'] = $user['identity'];
             if($user['first_name'] != '')
                $data['uname'] = $user['first_name'];
            if($user['last_name'] != '')
                $data['ufam'] = $user['last_name'];
            if($user['email'] != '')
                $data['email'] = $user['email'];
            if($user['photo'] != '')
                $data['userpic'] = $user['photo'];
            $usr = $db->update('users', $data, array('id'=>$_SESSION["user_id"]));
        }else{
            $password1 = generatePassword();
            $new_user_id = $user_auth->create($username, $password1);
            $data['socid'] = $user['identity'];
            $data['uname'] = $user['first_name'];
            $data['ufam'] = $user['last_name'];
            $data['email'] = $user['email'];
            $data['userpic'] = $user['photo'];
            $usr = $db->update('users', $data, array('id'=>$new_user_id));
        }
        

    }
    header('Location: /tasks/');
    exit();
}              


InintControllers(); //сканируем директорию контроллеров, инклудим их Inint файлы и получаем хуки и настройки, которые в них содержатся

//call_user_func(NAV);
HOOK::add_action("logo_mini", function(){return 'N4Y';});
//HOOK::add_action("logo_lg", function(){return '<b>Abelar</b> Media';});
HOOK::add_action("logo_lg", function(){return 'Nakrutka 4You';});
HOOK::add_action("window_title", function(){return 'Nakrutka 4You – сервис накрутки';});
//HOOK::window_title()

//HOOK::section_title();
//HOOK::user_name


HOOK::add_action('hook_name', new NAV);
HOOK::access_rule();



  // описываем соответствие URI и файлов модулей // Устанавливаем уровень доступа необходимый для некоторых URI
  URI_Shem::set_access_data_manual([
          ['url'=>'users', 'access'=>[ADMIN, USER], 'file'=>'users/usersController.php'],
          ['url'=>'auth', 'access'=>[GUEST, ADMIN, USER], 'file'=>'auth.ctl.php'],
      //    ['url'=>'chat', 'access'=>[ADMIN, USER], 'file'=>'chat/chatController.php'],
          ['url'=>'entities-generator', 'access'=>[ADMIN, USER], 'file'=>'entities-generator/entities_generator.php'],
        //  ['url'=>'paydocs', 'access'=>[ADMIN, USER], 'file'=>'paydocs/paydocsController.php'],
          ['url'=>'invoices', 'access'=>[ADMIN, USER], 'file'=>'invoices/invoicesController.php'],
       //   ['url'=>'tasks', 'access'=>[ GUEST, ADMIN, USER], 'file'=>'tasks/tasksController.php'],
       //   ['url'=>'tasks/add_new/', 'access'=>[ GUEST,ADMIN, USER], 'file'=>'tasks/tasksController.php'],
          ['url'=>'socauth', 'access'=>[GUEST, ADMIN, USER], 'file'=>'socauth/socauthController.php'],
        //   ['url'=>'cart', 'access'=>[GUEST, ADMIN, USER], 'file'=>'cart/cartController.php']
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
      //print $access_level;
    header('Location: /auth?action=login');
    exit();
  }
}
//var_dump($URI_Schem);
//var_dump( $uri_dir_ierr);

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