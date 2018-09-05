<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"\u0411\u0430\u0437\u0430 \u043a\u043b\u0438\u0435\u043d\u0442\u043e\u0432"},"url":"crm","url_access":[0,1,2]}', true);

if($_SESSION["access_level"]>1){
$NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'crm'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
}
//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'crm/crmController.php')]);

