<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"\u041e\u0431\u0440\u0430\u0449\u0435\u043d\u0438\u044f","1":"\u041C\u043E\u0438\u0020\u043E\u0431\u0440\u0430\u0449\u0435\u043D\u0438\u044F"},"url":"requests","url_access":[2,1]}', true);

$NAV = new NAV();
   /* NAV::start(
    	 [
    	        
    			'requests'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);*/
//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'requests/requestsController.php')]);

