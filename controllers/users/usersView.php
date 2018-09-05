<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

$tpl = new TPL1("/var/www/www-root/data/www/z.abelar.ru/tpl/panel_index.html");
$intpl = $tpl->content(__DIR__.'/tpl/users.'.$_SESSION["access_level"].'.editor.html');
   $intpl->modal_windows(__DIR__."/tpl/updateModal.".$_SESSION["access_level"].".modal.html");
    


HOOK::add_action('header_files', function(){
    print Helper::add_source('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'css', 'PAGINATION plugin');
    print Helper::add_source('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'css', 'FILTERS plugin');
    print Helper::add_source('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'users', 'DATAGRID plugin');
    print Helper::add_source('/assets/vendor/plugins/datepicker/datepicker3.css', 'css', 'Date Picker');
    
    print Helper::add_source('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css', 'css', 'Bootstrap WYSIHTML5');
    print Helper::add_source('https://cdn.jsdelivr.net/npm/suggestions-jquery@17.10.1/dist/css/suggestions.min.css', 'css', 'suggestions');
    print Helper::add_source('/assets/css/custom.css', 'css');
    
    print Helper::add_source('/assets/vendor/paper-wizard/css/paper-bootstrap-wizard.css', 'css', 'paper-wizard');
    print Helper::add_source('/assets/vendor/paper-wizard/css/themify-icons.css', 'css', 'paper-wizard');
    
    print Helper::add_source('/assets/vendor/suggestions/suggestions.css', 'css', 'suggestions');
    print Helper::add_source('https://cdn.jsdelivr.net/npm/suggestions-jquery@17.10.1/dist/css/suggestions.min.css', 'css', 'suggestions');
    print Helper::add_source('/assets/vendor/plugins/iCheck/flat/_all.css', 'css', 'iCheck');
});
HOOK::add_action('footer_files', function(){
    print Helper::add_source('/assets/vendor/paper-wizard/js/bootstrap.min.js', 'js', 'paper-wizard');
    print Helper::add_source('/assets/vendor/paper-wizard/js/jquery.bootstrap.wizard.js', 'js', 'paper-wizard');
    print Helper::add_source('/assets/vendor/paper-wizard/js/paper-bootstrap-wizard.js', 'js', 'paper-wizard');
    print Helper::add_source('/assets/vendor/paper-wizard/js/jquery.validate.min.js', 'js', 'paper-wizard');
    
    print Helper::add_source('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'js', 'PAGINATION plugin');
    print Helper::add_source('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'js', '');
    print Helper::add_source('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'js', 'FILTERS plugin');
    print Helper::add_source('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'js', '');
    print Helper::add_source('/assets/vendor/bs_grid/moment.min.js', 'js', 'required from filters plugin');
    print Helper::add_source('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'js', 'DATAGRID plugin');
    print Helper::add_source('/assets/vendor/bs_grid/minified/localization/en.min.js', 'js', '');
    print Helper::add_source('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'js', 'datepicker');
    print Helper::add_source('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'js', 'datepicker locales');
    print Helper::add_source('/assets/vendor/plugins/iCheck/icheck.min.js', 'js', 'iCheck');
    print Helper::add_source('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', 'js', 'Bootstrap WYSIHTML5');
    print Helper::add_source('/assets/vendor/bs_grid/custom.js', 'js', 'DATAGRID custom');
    print Helper::add_source("/controllers/users/tpl/users.".$_SESSION["access_level"].".editor.js", 'js', 'users editor');
    //print Helper::add_source('https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js', 'js', 'suggestions');
    print Helper::add_source('https://cdn.jsdelivr.net/npm/suggestions-jquery@17.10.1/dist/js/jquery.suggestions.min.js', 'js', 'suggestions');
    print Helper::add_source('/assets/vendor/suggestions/suggestions.js', 'js', 'suggestions');
    print '<!--[if lt IE 10]>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.1/jquery.xdomainrequest.min.js"></script>
<![endif]-->';

print '<script>
$(document).ready(function(){
  $(\'input\').iCheck({
    checkboxClass: \'icheckbox_flat-red\',
    radioClass: \'iradio_flat-red\'
  });
});
</script>';

});
//$tpl->assign('header_files', Helper::Get_css('users'));
//$tpl->assign('footer_files', Helper::Get_js('users'));
$tpl->run();
