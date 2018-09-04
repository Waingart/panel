<?
if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

switch($_GET['action']){
	case 'login':
    $tpl = new Templator("login.html");
    $tpl->run();
    break;
    
  case 'ajax':
    include('ajax.php');
    break;
    
  case 'register':
    $tpl = new Templator("register.html");
    $tpl->run();
    break;
    
  case 'after-register':
    $tpl = new Templator("after-register.html");
    $tpl->run();
    break;
    
  default:
    header('Location: /'); 
    exit();
}