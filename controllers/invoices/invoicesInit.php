<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"Выставленные счета"},"url":"invoices","url_access":[2]}', true);

$NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'invoices'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'invoices/invoicesController.php')]);

