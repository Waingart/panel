<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"\u0422\u0435\u043a\u0443\u0449\u0438\u0435 \u043a\u043b\u0438\u0435\u043d\u0442\u044b"},"url":"clients","url_access":[2]}', true);

$NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'clients'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'clients/clientsController.php')]);

