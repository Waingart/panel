<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"\u041f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u0438","1":"\u041c\u043e\u0439 \u043f\u0440\u043e\u0444\u0438\u043b\u044c"},"url":"users","url_access":[2,1]}', true);

$NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'users'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'users/usersController.php')]);
$db = new db;
$user = $db->select('users', array('id'=>$_SESSION["user_id"]), 'email');
if($user[0]['email'] == ''){
    HOOK::add_action('footer', function(){
        return "<script>$('#RegNow').modal({ 
      backdrop: 'static',
      keyboard: false 
        });
        $('#RegNow').modal('show');</script>";
    });
}
HOOK::add_action('modal_windows', function(){
?>
<!-- Modal -->
    <div class="modal fade" id="RegNow" tabindex="-1" role="dialog" aria-labelledby="RegNowLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
            <h4 class="modal-title" id="RegNowLabel">Быстрая регистрация</h4>
          </div>
          <div class="modal-body"> 
            <iframe  style="width: 100%;    height: 100%;    min-height: 250px;    border: none;    display: block;    overflow: hidden;" src="/users/regform/"></iframe>
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

HOOK::add_action("user_name", function(){
    $db = new db;
    $user = $db->select('users', array('id'=>$_SESSION["user_id"]));
   return $user[0]['uname'].' '. $user[0]['ufam'];
   // return 'Иван Иванович';
});
HOOK::add_action("user_balance", function(){
    $db = new db;
    $user = $db->select('users', array('id'=>$_SESSION["user_id"]));
   return $user[0]['balance'];
   // return 'Иван Иванович';
});
HOOK::add_action("user_pic", function(){
    $db = new db;
    $user = $db->select('users', array('id'=>$_SESSION["user_id"]));
    if($user[0]['userpic'] != ''){
        return $user[0]['userpic'];
    }else{
         return '/assets/img/userpic.png';
    }
   
   // return 'Иван Иванович';
});
