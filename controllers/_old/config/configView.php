<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

$tpl = new TPL1("/var/www/www-root/data/www/manager.abelar.ru/tpl/panel_index.html");
$intpl = $tpl->content(__DIR__.'/tpl/config.editor.html');

Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'task1', 'PAGINATION plugin');
Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'task1', 'FILTERS plugin');
Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'task1', 'DATAGRID plugin');
Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', 'task1', 'Date Picker');
Helper::Add_css('/assets/vendor/plugins/iCheck/flat/blue.css', 'task1', 'iCheck');
Helper::Add_css('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"', 'task1', 'Bootstrap WYSIHTML5');
Helper::Add_css('/assets/css/custom.css', 'task1', '');
Helper::Add_css('/assets/vendor/plugins/treeview/bootstrap-treeview.min.css', 'task1', '');
 
 

Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'task1', 'PAGINATION plugin');
Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'task1', '');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'task1', 'FILTERS plugin');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'task1', '');
Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', 'task1', 'required from filters plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'task1', 'DATAGRID plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', 'task1', '');
Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'task1', 'datepicker');
Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'task1', 'datepicker locales');
Helper::Add_js('/assets/vendor/plugins/iCheck/icheck.min.js', 'task1', 'iCheck');
Helper::Add_js('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', 'task1', 'Bootstrap WYSIHTML5');
Helper::Add_js('/assets/vendor/bs_grid/custom.js', 'task1', 'DATAGRID custom');
Helper::Add_js("/assets/vendor/plugins/treeview/bootstrap-treeview.min.js", 'task1', 'treeview');
Helper::Add_js("/assets/vendor/jquery-tmpl/jquery.tmpl.min.js", 'task1', 'jquery.tmpl');
Helper::Add_js("/controllers/config/tpl/config.editor.js", 'task1', 'users editor');


$tpl->assign('header_files', Helper::Get_css('task1'));
$tpl->assign('footer_files', Helper::Get_js('task1'));
$tpl->run();
