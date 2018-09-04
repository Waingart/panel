<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"\u041f\u043b\u0430\u0442\u0435\u0436\u0438","1":"\u041c\u043e\u0438 \u043f\u043b\u0430\u0442\u0435\u0436\u0438"},"url":"payments","url_access":[2,1]}', true);

$NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'payments'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'payments/paymentsController.php')]);
HOOK::add_action('modal_content', function(){
    return '<iframe id="payform"  style="width:390px; height:100%; min-height:700px; border:none; display:block; margin: auto" src=""></iframe>'; //s
});
HOOK::add_action('modal_title', function(){
    return 'Оплата картой';
});

$db = new db;
$dolg_docs = $db->select('paydocs', array('status'=>1, 'user_id'=>$_SESSION["user_id"]));
$amount = 0;
if(is_array($dolg_docs)){
    foreach($dolg_docs as $d_doc){
        $amount = $amount + $d_doc['sum'];
    }
}
if($amount > 0){
    HOOK::add_filter('is_dolg', 1);
    HOOK::add_filter('dolg_sum', $amount);
}else{
    HOOK::add_filter('dolg_sum', 0);
    HOOK::add_filter('is_dolg', 0);
}
//HOOK::add_filter('is_dolg', 1);

