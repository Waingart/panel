<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"\u041f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u0438","1":"\u041c\u043e\u0439 \u043f\u0440\u043e\u0444\u0438\u043b\u044c"},"url":"users","url_access":[2,1]}', true);

$NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'users'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'users/usersController.php')]);

 
    

HOOK::add_action("user_name", function(){
    $db = new db;
    $user = $db->select('users', array('id'=>$_SESSION["user_id"]));
   return $user[0]['uname'].' '. $user[0]['ufam'];
   // return 'Иван Иванович';
});

