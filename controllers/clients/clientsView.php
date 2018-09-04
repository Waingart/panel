<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

$tpl = new TPL1("/var/www/www-root/data/www/cabinet.ingrid-kld.ru/tpl/panel_index.html");
$intpl = $tpl->content(__DIR__.'/tpl/clients.editor.html');
   $intpl->modal_windows(__DIR__."/tpl/editModal.".$_SESSION["access_level"].".modal.html");
    
   $intpl->modal_windows(__DIR__."/tpl/mailtoModal.".$_SESSION["access_level"].".modal.html");
    
   $intpl->modal_windows(__DIR__."/tpl/deleteModal.".$_SESSION["access_level"].".modal.html");
    
   $intpl->modal_windows(__DIR__."/tpl/statisticModal.".$_SESSION["access_level"].".modal.html");
    

Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'clients', 'PAGINATION plugin');
Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'clients', 'FILTERS plugin');
Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'clients', 'DATAGRID plugin');
Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', 'clients', 'Date Picker');
Helper::Add_css('/assets/vendor/plugins/iCheck/flat/blue.css', 'clients', 'iCheck');
Helper::Add_css('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"', 'clients', 'Bootstrap WYSIHTML5');
Helper::Add_css('/assets/css/custom.css', 'clients', '');


Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'clients', 'PAGINATION plugin');
Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'clients', '');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'clients', 'FILTERS plugin');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'clients', '');
Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', 'clients', 'required from filters plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'clients', 'DATAGRID plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', 'clients', '');
Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'clients', 'datepicker');
Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'clients', 'datepicker locales');
Helper::Add_js('/assets/vendor/plugins/iCheck/icheck.min.js', 'clients', 'iCheck');
Helper::Add_js('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', 'clients', 'Bootstrap WYSIHTML5');
Helper::Add_js('/assets/vendor/bs_grid/custom.js', 'clients', 'DATAGRID custom');
Helper::Add_js("/controllers/clients/tpl/clients.".$_SESSION["access_level"].".editor.js", 'clients', 'users editor');

$tpl->assign('header_files', Helper::Get_css('clients'));
$tpl->assign('footer_files', Helper::Get_js('clients'));
$tpl->run();
