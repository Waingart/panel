<?
if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

switch($URI_TO_CTL[0]){
	case 'home':
    $tpl = new Templator("widgets.html");
    $tpl->run();
  break;
  case 'invoices':
    $tpl = new Templator("panel_index.html");
    $tpl->content("invoices.html");
    $tpl->modal_content("doc_editor.html");
    $tpl->assign('modal_title', 'Создать счет и акт');
    Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'invoices', 'PAGINATION plugin');
    Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'invoices', 'FILTERS plugin');
    Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'invoices', 'DATAGRID plugin');
    Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', 'invoices', 'Date Picker');
    Helper::Add_css('/assets/vendor/plugins/iCheck/flat/blue.css', 'invoices', 'iCheck');
    Helper::Add_css('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"', 'invoices', 'Bootstrap WYSIHTML5');
    Helper::Add_css('/assets/css/custom.css', 'invoices', '');
    
    
    Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'invoices', 'PAGINATION plugin');
    Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'invoices', '');
    Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'invoices', 'FILTERS plugin');
    Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'invoices', '');
    Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', 'invoices', 'required from filters plugin');
    Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'invoices', 'DATAGRID plugin');
    Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', 'invoices', '');
    Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'invoices', 'datepicker');
    Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'invoices', 'datepicker locales');
    Helper::Add_js('/assets/vendor/plugins/iCheck/icheck.min.js', 'invoices', 'iCheck');
    Helper::Add_js('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', 'invoices', 'Bootstrap WYSIHTML5');
    Helper::Add_js('/assets/vendor/bs_grid/custom.js', 'invoices', 'DATAGRID custom');
    Helper::Add_js('/assets/js/invoice_editor.js', 'invoices', 'Doc editor');
    
  
    $tpl->assign('header_files', Helper::Get_css('invoices'));
    $tpl->assign('footer_files', Helper::Get_js('invoices'));
    $tpl->run();
  break;
  case 'clients':
    $tpl = new Templator("panel_index.html");
    $tpl->content("clients.html");
    $tpl->modal_content("client_editor.html");
    $tpl->assign('modal_title', 'Создать нового киента');
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
    Helper::Add_js('/assets/js/client_editor.js', 'clients', 'clients editor');
    
  
    $tpl->assign('header_files', Helper::Get_css('clients'));
    $tpl->assign('footer_files', Helper::Get_js('clients'));
    $tpl->run();
  break;
case 'payments':
    $tpl = new Templator("panel_index.html");
    $tpl->content("payments.html");
    $tpl->modal_content("client_editor.html");
    $tpl->assign('modal_title', 'Создать нового киента');
    Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'payments', 'PAGINATION plugin');
    Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'payments', 'FILTERS plugin');
    Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'payments', 'DATAGRID plugin');
    Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', 'payments', 'Date Picker');
    Helper::Add_css('/assets/vendor/plugins/iCheck/flat/blue.css', 'payments', 'iCheck');
    Helper::Add_css('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"', 'payments', 'Bootstrap WYSIHTML5');
    Helper::Add_css('/assets/css/custom.css', 'payments', '');
    
    
    Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'payments', 'PAGINATION plugin');
    Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'payments', '');
    Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'payments', 'FILTERS plugin');
    Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'payments', '');
    Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', 'payments', 'required from filters plugin');
    Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'payments', 'DATAGRID plugin');
    Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', 'payments', '');
    Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'payments', 'datepicker');
    Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'payments', 'datepicker locales');
    Helper::Add_js('/assets/vendor/plugins/iCheck/icheck.min.js', 'payments', 'iCheck');
    Helper::Add_js('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', 'payments', 'Bootstrap WYSIHTML5');
    Helper::Add_js('/assets/vendor/bs_grid/custom.js', 'payments', 'DATAGRID custom');
    Helper::Add_js('/assets/js/payment_editor.js', 'payments', 'payments editor');
    
  
    $tpl->assign('header_files', Helper::Get_css('payments'));
    $tpl->assign('footer_files', Helper::Get_js('payments'));
    $tpl->run();
  break;
  case 'users':
    $tpl = new Templator("panel_index.html");
    $tpl->content("users.html");
    $tpl->modal_content("users_editor.html");
    $tpl->assign('modal_title', 'Настройки пользователя');
    Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'users', 'PAGINATION plugin');
    Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'users', 'FILTERS plugin');
    Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'users', 'DATAGRID plugin');
    Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', 'users', 'Date Picker');
    Helper::Add_css('/assets/vendor/plugins/iCheck/flat/blue.css', 'users', 'iCheck');
    Helper::Add_css('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"', 'users', 'Bootstrap WYSIHTML5');
    Helper::Add_css('/assets/css/custom.css', 'users', '');
    
    
    Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'users', 'PAGINATION plugin');
    Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'users', '');
    Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'users', 'FILTERS plugin');
    Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'users', '');
    Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', 'users', 'required from filters plugin');
    Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'users', 'DATAGRID plugin');
    Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', 'users', '');
    Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'users', 'datepicker');
    Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'users', 'datepicker locales');
    Helper::Add_js('/assets/vendor/plugins/iCheck/icheck.min.js', 'users', 'iCheck');
    Helper::Add_js('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', 'users', 'Bootstrap WYSIHTML5');
    Helper::Add_js('/assets/vendor/bs_grid/custom.js', 'users', 'DATAGRID custom');
    Helper::Add_js('/assets/js/users_editor.js', 'users', 'users editor');
    
  
    $tpl->assign('header_files', Helper::Get_css('users'));
    $tpl->assign('footer_files', Helper::Get_js('users'));
    $tpl->run();
  break;
  case 'mail':
    $tpl = new Templator("panel_index.html");
    $tpl->content("mailbox.html");
    //$tpl->modal_content("doc_editor.html");
   // $tpl->assign('modal_title', 'Создать счет и акт');
    Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'invoices', 'PAGINATION plugin');
    Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'invoices', 'FILTERS plugin');
    Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'invoices', 'DATAGRID plugin');
    Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', 'invoices', 'Date Picker');
    Helper::Add_css('/assets/vendor/plugins/iCheck/flat/blue.css', 'invoices', 'iCheck');
    Helper::Add_css('/assets/css/custom.css', 'invoices', '');
    Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'invoices', 'PAGINATION plugin');
    Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'invoices', '');
    Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'invoices', 'FILTERS plugin');
    Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'invoices', '');
    Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', 'invoices', 'required from filters plugin');
    Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'invoices', 'DATAGRID plugin');
    Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', 'invoices', '');
    Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'invoices', 'datepicker');
    Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'invoices', 'datepicker locales');
    Helper::Add_js('/assets/vendor/plugins/plugins/iCheck/icheck.min.js', 'invoices', 'iCheck');
    Helper::Add_js('/assets/vendor/bs_grid/custom.js', 'invoices', 'DATAGRID custom');
    Helper::Add_js('/assets/js/invoice_editor.js', 'invoices', 'Doc editor');

  
    $tpl->assign('header_files', Helper::Get_css('invoices'));
    $tpl->assign('footer_files', Helper::Get_js('invoices'));
    $tpl->run();
  break;
  case 'table':
    $tpl = new Templator("panel_index_clear.html");
    $tpl->content("table.html");
    
    Helper::Add_css('//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', 'mailbox', '');
   // Helper::Add_css('https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css', 'mailbox', '');
    Helper::Add_css('/assets/vendor/plugins/datatables/dataTables.bootstrap.css', 'mailbox', 'DataTables');
    Helper::Add_css('https://cdn.datatables.net/buttons/1.1.2/css/buttons.bootstrap.min.css', 'mailbox', '');
    Helper::Add_css('https://cdn.datatables.net/select/1.1.2/css/select.bootstrap.min.css', 'mailbox', '');
    Helper::Add_css('/assets/vendor/datatables/css/editor.bootstrap.min.css', 'mailbox', '');
   // Helper::Add_css('/assets/vendor/datatables/resources/syntax/shCore.css', 'mailbox', '');
   // Helper::Add_css('/assets/vendor/datatables/resources/demo.css', 'mailbox', '');
    

    
    //Helper::Add_js('//code.jquery.com/jquery-1.12.0.min.js', 'mailbox', '');
    //Helper::Add_js('//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', 'mailbox', '');
    Helper::Add_js('https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js', 'mailbox', '');
    Helper::Add_js('https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js', 'mailbox', '');
    Helper::Add_js('https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js', 'mailbox', '');
    Helper::Add_js('https://cdn.datatables.net/buttons/1.1.2/js/buttons.bootstrap.min.js', 'mailbox', '');
    Helper::Add_js('https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js', 'mailbox', '');
    Helper::Add_js('/assets/vendor/datatables/js/dataTables.editor.min.js', 'mailbox', '');
    Helper::Add_js('/assets/vendor/datatables/js/editor.bootstrap.min.js', 'mailbox', '');
    //Helper::Add_js('/assets/vendor/datatables/resources/syntax/shCore.js', 'mailbox', '');
    //Helper::Add_js('/assets/vendor/datatables/resources/demo.js', 'mailbox', '');
   // Helper::Add_js('/assets/vendor/datatables/resources/editor-demo.js', 'mailbox', '');
    Helper::Add_js('/assets/js/datatable.custom.js', 'mailbox', '');
    

    $tpl->assign('header_files', Helper::Get_css('mailbox'));
    $tpl->assign('footer_files', Helper::Get_js('mailbox'));
    $tpl->run();
  break;
  case 'task';
    include('taskController.php');
    $tpl = new Templator("panel_index.html");
    $tpl->content("tasks.html");
    $tpl->modal_content("task_editor.html");
    $tpl->assign('modal_title', 'Настройки задания');
    Helper::Add_css('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.css', 'users', 'PAGINATION plugin');
    Helper::Add_css('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.bs.min.css', 'users', 'FILTERS plugin');
    Helper::Add_css('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.css', 'users', 'DATAGRID plugin');
    Helper::Add_css('/assets/vendor/plugins/datepicker/datepicker3.css', 'users', 'Date Picker');
    Helper::Add_css('/assets/vendor/plugins/iCheck/flat/blue.css', 'users', 'iCheck');
    Helper::Add_css('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"', 'users', 'Bootstrap WYSIHTML5');
    Helper::Add_css('/assets/css/custom.css', 'users', '');
    
    
    Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/jquery.bs_pagination.min.js', 'users', 'PAGINATION plugin');
    Helper::Add_js('/assets/vendor/bs_grid/bs_pagination/localization/en.min.js', 'users', '');
    Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/jquery.jui_filter_rules.min.js', 'users', 'FILTERS plugin');
    Helper::Add_js('/assets/vendor/bs_grid/jui_filter_rules/minified/localization/en.min.js', 'users', '');
    Helper::Add_js('/assets/vendor/bs_grid/moment.min.js', 'users', 'required from filters plugin');
    Helper::Add_js('/assets/vendor/bs_grid/minified/jquery.bs_grid.min.js', 'users', 'DATAGRID plugin');
    Helper::Add_js('/assets/vendor/bs_grid/minified/localization/en.min.js', 'users', '');
    Helper::Add_js('/assets/vendor/plugins/datepicker/js/bootstrap-datepicker.js', 'users', 'datepicker');
    Helper::Add_js('/assets/vendor/plugins/datepicker/locales/bootstrap-datepicker.ru.min.js', 'users', 'datepicker locales');
    Helper::Add_js('/assets/vendor/plugins/iCheck/icheck.min.js', 'users', 'iCheck');
    Helper::Add_js('/assets/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js', 'users', 'Bootstrap WYSIHTML5');
    Helper::Add_js('/assets/vendor/bs_grid/custom.js', 'users', 'DATAGRID custom');
    Helper::Add_js('/assets/js/task_editor.php', 'users', 'users editor');
    
  
    $tpl->assign('header_files', Helper::Get_css('users'));
    $tpl->assign('footer_files', Helper::Get_js('users'));
    $tpl->run();
  break;
  case 'user';
    include($URI_TO_CTL[0].'/'.$URI_TO_CTL[0].'Controller.php');
    
  break;
  case 'table-test':
    $tpl = new Templator("panel_index_clear.html");
    $tpl->content("table-test.html");

    Helper::Add_js('/assets/vendor/plugins/datatables/jquery.dataTables.min.js', 'mailbox', '');
    Helper::Add_js('/assets/vendor/plugins/datatables/dataTables.bootstrap.min.js', 'mailbox', '');
    //Helper::Add_js('/assets/js/table-test.js', 'mailbox', '');
    
    Helper::Add_js('https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js', 'mailbox', ''); //
    Helper::Add_js('https://cdn.datatables.net/buttons/1.1.2/js/buttons.bootstrap.min.js', 'mailbox', '');  //
    Helper::Add_js('https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js', 'mailbox', '');   //
    
    
    Helper::Add_js('/assets/vendor/datatables/js/dataTables.editor.min.js', 'mailbox', '');
    Helper::Add_js('/assets/vendor/datatables/js/editor.bootstrap.min.js', 'mailbox', '');
    
    Helper::Add_js('/assets/js/datatable.custom.js', 'mailbox', '');
    
    Helper::Add_css('/assets/vendor/plugins/datatables/dataTables.bootstrap.css', 'mailbox', '');
    
    Helper::Add_css('https://cdn.datatables.net/buttons/1.1.2/css/buttons.bootstrap.min.css', 'mailbox', ''); //
    Helper::Add_css('https://cdn.datatables.net/select/1.1.2/css/select.bootstrap.min.css', 'mailbox', '');  //
    Helper::Add_css('/assets/vendor/datatables/css/editor.bootstrap.min.css', 'mailbox', '');   //
    
    
    $tpl->assign('header_files', Helper::Get_css('mailbox'));
    $tpl->assign('footer_files', Helper::Get_js('mailbox'));
    $tpl->run();
  break;
  case 'clients-ajax':
    include('ajax_clients.php');
    
  break;
  
  case 'invoices-ajax':
    include('ajax_invoices.php');
    
  break;
  case 'payments-ajax':
    include('ajax_payments.php');
    
  break;
  case 'users-ajax':
    include('ajax_users.php');
    
  break;
  case 'invoice-clone':
    include('ajax_invoice-clone.php');
    
  break;
  case 'invoice-delete':
    include('ajax_invoice-delete.php');
    
  break;
  case 'client-delete':
    include('ajax_client-delete.php');
    
  break;
  
  case 'invoice-status':
    include('ajax_invoice-status.php');
    
  break;
  case 'invoices-filldata':
    include('get_json_doc.php');
  break;
  case 'client-filldata':
    include('get_json_client.php');
  break;
   case 'user-filldata':
   include('get_json_user.php');
  break;
  case 'invoice-save':
    include('invoice-save.php');
  break;  
  case 'client-save':
    include('client-save.php');
  break;  
  case 'user-save':
    include('user-save.php');
  break;
  case 'mail-send':
    include('send_mail.php');
  break;
  case 'krumo':
    include('krumo.php');
  break;
  case 'get-invoice-pdf':
    include('get_pdf_doc.php');
  break;
  case 'get-act-pdf':
    include('get_pdf_doc.php');
  break;
  case 'mail-ajax':
    include('checkbox.php');
    
  break;
  default:
    $tpl = new Templator("widgets.html");
    $tpl->run();
}