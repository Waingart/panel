<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

$tpl = new TPL1("/var/www/www-root/data/www/z.abelar.ru/tpl/panel_index.html");
$intpl = $tpl->content(__DIR__.'/tpl/services.editor.html');
//$userbonusbalance['bonusbalance'] = 'aa';
//$userbonusbalance['bonusdate'] = 'ddd';
$intpl->assign('bonusbalance', $userbonusbalance['bonusbalance']);
$intpl->assign('bonusdate', $userbonusbalance['bonusdate']);
//$intpl->bonusdate = $userbonusbalance['bonusdate'];

   $intpl->modal_windows(__DIR__."/tpl/editModal.".$_SESSION["access_level"].".modal.html");
    
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
    print Helper::add_source('/assets/vendor/inter-phone/css/intlTelInput.css', 'css', 'inter-phone');

});
HOOK::add_action('footer_files', function(){
    print Helper::add_source('/assets/vendor/paper-wizard/js/bootstrap.min.js', 'js', 'paper-wizard');
    print Helper::add_source('/assets/vendor/paper-wizard/js/jquery.bootstrap.wizard.js', 'js', 'paper-wizard');
    print Helper::add_source('/assets/vendor/paper-wizard/js/paper-bootstrap-wizard.js', 'js', 'paper-wizard');
    print Helper::add_source('/assets/vendor/paper-wizard/js/jquery.validate.min.js', 'js', 'paper-wizard');
     print Helper::add_source('/assets/vendor/paper-wizard/js/bootstrap.min.js', 'js', 'paper-wizard');
     
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
    print Helper::add_source('/assets/vendor/inter-phone/js/intlTelInput.js', 'js', 'inter-phone');
    
    print '<!--[if lt IE 10]>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.1/jquery.xdomainrequest.min.js"></script>
<![endif]-->';
?>
<script>
    $("#phone").intlTelInput({
      // allowDropdown: false,
      // autoHideDialCode: false,
      // autoPlaceholder: "off",
      // dropdownContainer: "body",
      // excludeCountries: ["us"],
      // formatOnDisplay: false,
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
      // hiddenInput: "full_number",
       //initialCountry: "auto",
       nationalMode: true,
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
       preferredCountries: ['ru'],
       //separateDialCode: true,
      utilsScript: "/assets/vendor/inter-phone/js/utils.js"
    });
    var telInput = $("#phone"),
  errorMsg = $("#error-msg"),
  validMsg = $("#valid-msg");


var reset = function() {
  telInput.removeClass("error");
  errorMsg.addClass("hide");
  validMsg.addClass("hide");
};

// on blur: validate
telInput.blur(function() {
  reset();
  if ($.trim(telInput.val())) {
    if (telInput.intlTelInput("isValidNumber")) {
      validMsg.removeClass("hide");
    } else {
      telInput.addClass("error");
      errorMsg.removeClass("hide");
    }
  }
});

// on keyup / change flag: reset
telInput.on("keyup change", reset);
  </script>
<?
print '<script>
$(document).ready(function(){
  $(\'input\').iCheck({
    checkboxClass: \'icheckbox_flat-red\',
    radioClass: \'iradio_flat-red\'
  });
});
</script>';

});
/*
Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'services', 'PAGINATION plugin');
Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'services', 'FILTERS plugin');
Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'services', 'DATAGRID plugin');
Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', 'services', 'Date Picker');
Helper::Add_css('/assets/vendor/plugins/iCheck/all.css', 'services', 'iCheck');
Helper::Add_css('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"', 'services', 'Bootstrap WYSIHTML5');
Helper::Add_css('/assets/css/custom.css', 'services', '');


Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'services', 'PAGINATION plugin');
Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'services', '');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'services', 'FILTERS plugin');
Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'services', '');
Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', 'services', 'required from filters plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'services', 'DATAGRID plugin');
Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', 'services', '');
Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'services', 'datepicker');
Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'services', 'datepicker locales');
Helper::Add_js('/assets/vendor/plugins/iCheck/icheck.min.js', 'services', 'iCheck');
Helper::Add_js('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', 'services', 'Bootstrap WYSIHTML5');
Helper::Add_js('/assets/vendor/bs_grid/custom.js', 'services', 'DATAGRID custom');
Helper::Add_js("/controllers/services/tpl/services.".$_SESSION["access_level"].".editor.js", 'services', 'users editor');

$tpl->assign('header_files', Helper::Get_css('services'));
$tpl->assign('footer_files', Helper::Get_js('services'));*/
$tpl->run();
