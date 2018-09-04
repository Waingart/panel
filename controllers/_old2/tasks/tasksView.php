<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

$tpl = new TPL1("/var/www/www-root/data/www/nakrutka.demo.abelar.ru/tpl/panel_index.html");
$intpl = $tpl->content(__DIR__.'/tpl/tasks.editor.html');
   $intpl->modal_windows(__DIR__."/tpl/stopModal.".$_SESSION["access_level"].".modal.html");
    

Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'tasks', 'PAGINATION plugin');
Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'tasks', 'FILTERS plugin');
Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'tasks', 'DATAGRID plugin');
Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', 'tasks', 'Date Picker');
Helper::Add_css('/assets/vendor/plugins/iCheck/flat/blue.css', 'tasks', 'iCheck');
Helper::Add_css('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"', 'tasks', 'Bootstrap WYSIHTML5');
Helper::Add_css('/assets/css/custom.css', 'tasks', '');


Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'tasks', 'PAGINATION plugin');
Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'tasks', '');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'tasks', 'FILTERS plugin');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'tasks', '');
Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', 'tasks', 'required from filters plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'tasks', 'DATAGRID plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', 'tasks', '');
Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'tasks', 'datepicker');
Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'tasks', 'datepicker locales');
Helper::Add_js('/assets/vendor/plugins/iCheck/icheck.min.js', 'tasks', 'iCheck');
Helper::Add_js('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', 'tasks', 'Bootstrap WYSIHTML5');
Helper::Add_js('/assets/vendor/bs_grid/custom.js', 'tasks', 'DATAGRID custom');
Helper::Add_js("/controllers/tasks/tpl/tasks.".$_SESSION["access_level"].".editor.js", 'tasks', 'users editor');

$tpl->assign('header_files', Helper::Get_css('tasks'));
$tpl->assign('footer_files', Helper::Get_js('tasks'));
$tpl->run();
