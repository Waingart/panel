<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"\u041a\u0432\u0438\u0442\u0430\u043d\u0446\u0438\u0438","1":"\u041a\u0432\u0438\u0442\u0430\u043d\u0446\u0438\u0438"},"url":"paydocs","url_access":[2,1]}', true);

$NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'paydocs'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'paydocs/paydocsController.php')]);

//HOOK::add_action('dolg_summ', 'paydocs::dolg_summ');