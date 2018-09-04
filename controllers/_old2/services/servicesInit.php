<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"\u041d\u0430\u0441\u0442\u0440\u043e\u0439\u043a\u0430 \u0443\u0441\u043b\u0443\u0433"},"url":"services","url_access":[0,1,2]}', true);

if($_SESSION["access_level"] > 1){
$NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'services'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
}
//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'services/servicesController.php')]);

HOOK::add_action('modal_windows', function(){
    ?>
    <div class="modal fade" id="Cash" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
            <h4 class="modal-title" id="myModalLabel">Пополнение баланса</h4>
          </div>
          <div class="modal-body"> 
            форма
          </div>
          <div class="modal-footer">
            <!--button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            <button type="button" class="btn btn-primary save update" data-loading-text="Секундочку...">Сохранить</button-->
          </div>
        </div>
      </div>
    </div> 
    <?
    
});

