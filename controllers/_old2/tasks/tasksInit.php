<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"\u0418\u0441\u043f\u043e\u043b\u043d\u0435\u043d\u0438\u0435 \u0437\u0430\u0434\u0430\u043d\u0438\u0439","1":"\u0418\u0441\u043f\u043e\u043b\u043d\u0435\u043d\u0438\u0435 \u0437\u0430\u0434\u0430\u043d\u0438\u0439"},"url":"tasks","url_access":[2,1]}', true);

$NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'tasks'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
//HOOK::add_action('hook_name', $NAV);
//HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'tasks/tasksController.php')]);

