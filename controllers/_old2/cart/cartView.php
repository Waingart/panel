<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

$tpl = new TPL1(__DIR__.'/tpl/cart.html');
$tpl->assign('stype', $stype);
$tpl->assign('socid', $socid);
$tpl->run();
