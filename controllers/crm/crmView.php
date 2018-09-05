<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

$tpl = new TPL1("/var/www/www-root/data/www/z.abelar.ru/tpl/panel_index.html");
$intpl = $tpl->content(__DIR__.'/tpl/crm.editor.html');
   $intpl->modal_windows(__DIR__."/tpl/editModal.".$_SESSION["access_level"].".modal.html");
    

Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'crm', 'PAGINATION plugin');
Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'crm', 'FILTERS plugin');
Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'crm', 'DATAGRID plugin');
Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', 'crm', 'Date Picker');
Helper::Add_css('/assets/vendor/plugins/iCheck/flat/blue.css', 'crm', 'iCheck');
Helper::Add_css('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"', 'crm', 'Bootstrap WYSIHTML5');
Helper::Add_css('/assets/css/custom.css', 'crm', '');


Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'crm', 'PAGINATION plugin');
Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'crm', '');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'crm', 'FILTERS plugin');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'crm', '');
Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', 'crm', 'required from filters plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'crm', 'DATAGRID plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', 'crm', '');
Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'crm', 'datepicker');
Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'crm', 'datepicker locales');
Helper::Add_js('/assets/vendor/plugins/iCheck/icheck.min.js', 'crm', 'iCheck');
Helper::Add_js('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', 'crm', 'Bootstrap WYSIHTML5');
Helper::Add_js('/assets/vendor/bs_grid/custom.js', 'crm', 'DATAGRID custom');
Helper::Add_js("/controllers/crm/tpl/crm.".$_SESSION["access_level"].".editor.js", 'crm', 'users editor');

$tpl->assign('header_files', Helper::Get_css('crm'));
$tpl->assign('footer_files', Helper::Get_js('crm'));
$tpl->run();
