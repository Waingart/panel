<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

$tpl = new TPL1("/var/www/www-root/data/www/cabinet.ingrid-kld.ru/tpl/panel_index.html");
$intpl = $tpl->content(__DIR__.'/tpl/requests.editor.html');
   $intpl->modal_windows(__DIR__."/tpl/closeModal.".$_SESSION["access_level"].".modal.html");
    
   $intpl->modal_windows(__DIR__."/tpl/reopenModal.".$_SESSION["access_level"].".modal.html");
    

Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'requests', 'PAGINATION plugin');
Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'requests', 'FILTERS plugin');
Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'requests', 'DATAGRID plugin');
Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', 'requests', 'Date Picker');
Helper::Add_css('/assets/vendor/plugins/iCheck/flat/blue.css', 'requests', 'iCheck');
Helper::Add_css('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"', 'requests', 'Bootstrap WYSIHTML5');
Helper::Add_css('/assets/css/custom.css', 'requests', '');


Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'requests', 'PAGINATION plugin');
Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'requests', '');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'requests', 'FILTERS plugin');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'requests', '');
Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', 'requests', 'required from filters plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'requests', 'DATAGRID plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', 'requests', '');
Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'requests', 'datepicker');
Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'requests', 'datepicker locales');
Helper::Add_js('/assets/vendor/plugins/iCheck/icheck.min.js', 'requests', 'iCheck');
Helper::Add_js('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', 'requests', 'Bootstrap WYSIHTML5');
Helper::Add_js('/assets/vendor/bs_grid/custom.js', 'requests', 'DATAGRID custom');
Helper::Add_js("/controllers/requests/tpl/requests.".$_SESSION["access_level"].".editor.js", 'requests', 'users editor');

$tpl->assign('header_files', Helper::Get_css('requests'));
$tpl->assign('footer_files', Helper::Get_js('requests'));
$tpl->run();
