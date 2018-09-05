<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"\u041f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u0438","1":"\u041c\u043e\u0439 \u043f\u0440\u043e\u0444\u0438\u043b\u044c"},"url":"users","url_access":[2,1]}', true);

if($_SESSION["access_level"] > 1){
    $NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'users'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
}

//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'users/usersController.php')]);
$db = new db;
$user = $db->select('users', array('id'=>$_SESSION["user_id"]), 'email');
//if($user[0]['email'] == ''){
  
//}
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
            <div class="">
      <div class="login-box-body">
        <p class="login-box-msg">Укажите ваш email, чтобы не потерять доступ к аккаунту. Мы пришлем на него пароль.</p>
        <form action="/users/doreg/" method="post" class="">
          <div class="callout callout-danger main-error hide"></div>
          <div class="row">
            <div class="col-xs-8">
              <div class="form-group has-feedback">
                <label style="width:100%">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                  <input style="width:100%" name="email" type="text" class="form-control" placeholder="Ваш email">
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat">Сохранить</button>
							<input type="hidden" name="act" value="login">
						</div>
					<!-- /.col -->
				</div>
      </form>

        <div class="social-auth-links text-center">
          <p>- или войдите через соцсети -</p>
          <script src="//ulogin.ru/js/ulogin.js"></script>
					<div id="uLogin" data-ulogin="display=panel;theme=classic;fields=first_name,last_name,photo,email;providers=instagram,vkontakte,facebook,odnoklassniki,mailru;hidden=other;redirect_uri=https%3A%2F%2Fz.abelar.ru%2Ftasks%2F;mobilebuttons=0;"></div>
        </div>
        <!-- /.social-auth-links -->
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
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

HOOK::add_action('modal_windows', function(){
?>
<!-- Modal -->
    <div class="modal fade" id="RegNow1" tabindex="-1" role="dialog" aria-labelledby="RegNowLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
            <h4 class="modal-title" id="RegNowLabel">Быстрая регистрация</h4>
          </div>
          <div class="modal-body"> 
            <div class="">
              <div class="login-box-body">
                <p class="login-box-msg">Привяжите Ваш аккаунт в соцсети и входите одним кликом!</p>
                  <div class="callout callout-danger main-error hide"></div>
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="form-group has-feedback" style="text-align: center;">
                        <script src="//ulogin.ru/js/ulogin.js"></script>
        								<div style="margin: 0 auto;" id="uLogin1" data-ulogin="display=panel;theme=classic;fields=first_name,last_name,photo,email;providers=instagram,vkontakte,facebook,odnoklassniki,mailru;hidden=other;redirect_uri=https%3A%2F%2Fz.abelar.ru%2Ftasks%2F;mobilebuttons=0;"></div>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-12">
        							<button style="width:300px; margin: 30px auto;" type="submit" class="btn btn-block btn-flat" onClick="parent.$('#RegNow1').modal('hide');">У меня нет аккаунта в соцсетях</button>
        						</div>
        						<!-- /.col -->
        					</div>
                <!-- /.social-auth-links -->
              </div><!-- /.login-box-body -->
            </div><!-- /.login-box -->
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
HOOK::add_action('modal_windows', function(){
?>
<!-- Modal -->
    <div class="modal fade" id="RegNow2" tabindex="-1" role="dialog" aria-labelledby="RegNowLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
            <h4 class="modal-title" id="RegNowLabel">Быстрая регистрация</h4>
          </div>
          <div class="modal-body"> 
            <div class="">
              <div class="login-box-body">
                <p class="login-box-msg">Привяжите Ваш аккаунт в соцсети и входите одним кликом!</p>
                  <div class="callout callout-danger main-error hide"></div>
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="form-group has-feedback" style="text-align: center;">
                        <script src="//ulogin.ru/js/ulogin.js"></script>
        								<div style="margin: 0 auto;" id="uLogin1" data-ulogin="display=panel;theme=classic;fields=first_name,last_name,photo,email;providers=instagram,vkontakte,facebook,odnoklassniki,mailru;hidden=other;redirect_uri=https%3A%2F%2Fz.abelar.ru%2Ftasks%2F;mobilebuttons=0;"></div>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-12">
        							<button style="width:300px; margin: 30px auto;" type="submit" class="btn btn-block btn-flat" onClick="parent.$('#RegNow1').modal('hide');">У меня нет аккаунта в соцсетях</button>
        						</div>
        						<!-- /.col -->
        					</div>
                <!-- /.social-auth-links -->
              </div><!-- /.login-box-body -->
            </div><!-- /.login-box -->
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

/*
HOOK::add_action('modal_windows', function(){
?>
<!-- Modal -->
        <div class="modal fade" id="RegNow3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
            <h4 class="modal-title" id="myModalLabel">Шаг 1. Заполните карточку компании</h4>
          </div>
          <div class="modal-body"> 


<section class="result">
  <div class="row">
      <div class="col-lg-3 col-xs-3" style="text-align:right">
          <label>Имя</label>
      </div>
      <div class="col-lg-9 col-xs-9">
          <input id="name" class="form-control">
      </div>
      
  </div>
  <div class="row">
      
      <div class="col-lg-3 col-xs-3" style="text-align:right">
          <label>Отчество</label>
      </div>
      <div class="col-lg-9 col-xs-9">
          <input id="otch" class="form-control">
      </div>
  </div>
  <div class="row">
      <div class="col-lg-3 col-xs-3" style="text-align:right">
          <label>Телефон</label>
      </div>
      <div class="col-lg-9 col-xs-9">
          <input id="phone" class="form-control">
      </div>
  </div>
  <div class="row">
      <div class="col-lg-3 col-xs-3" style="text-align:right">
          <label>Email</label>
      </div>
      <div class="col-lg-9 col-xs-9">
          <input id="email" class="form-control">
      </div>
  </div>
</section>
<section class="private">
    <div class="row">
      <div class="col-lg-12 col-xs-12" style="text-align:center">
         <label>
                  <input type="checkbox" class="flat-red">
                  Даю согласие на получение полезных материалов на Email
                </label>
      </div>
      
  </div>
    
</section>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Очистить выбор</button>
            <button type="button" class="btn btn-primary save update" data-loading-text="Секундочку...">Да, все правильно</button>
          </div>
        </div>
      </div>
    </div> 
<?    
}); 
HOOK::add_action('modal_windows', function(){
    ?>
    <div class="modal fade" id="Cash" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
            <h4 class="modal-title" id="myModalLabel">Шаг 1. Заполните карточку компании</h4>
          </div>
          <div class="modal-body"> 

<section class="query">    
<input id="party" name="party" type="text" size="100" class="form-control" placeholder="Начните ввод названия Вашей организации или ИП и выберите из списка"/>
</section>
<section class="private">
    <div class="row">
      <div class="col-lg-12 col-xs-12" style="text-align:center">
         <label>
                  <input type="checkbox" class="flat-red">
                  Нет юрлица и ИП
                </label>
      </div>
      
  </div>
    
</section>
<section class="result">
  <!--p id="type"></p-->
  <!--div class="row">
    <label>Краткое наименование</label>
    <input id="name_short">
  </div-->
  <!--div class="row">
    <label>Полное наименование</label>
    <input id="name_full">
  </div-->
  
  <div class="row">
      <div class="col-lg-3 col-xs-3" style="text-align:right">
          <label>ОГРН</label>
      </div>
      <div class="col-lg-9 col-xs-9">
          <input id="ogrn" class="form-control">
      </div>
      
  </div>
  <div class="row">
      
      <div class="col-lg-3 col-xs-3" style="text-align:right">
          <label>ИНН / КПП</label>
      </div>
      <div class="col-lg-9 col-xs-9">
          <input id="inn_kpp" class="form-control">
      </div>
  </div>
  <div class="row">
      <div class="col-lg-3 col-xs-3" style="text-align:right">
          <label>Адрес</label>
      </div>
      <div class="col-lg-9 col-xs-9">
          <input id="address" class="form-control">
      </div>
  </div>
  <div class="row">
      <div class="col-lg-3 col-xs-3" style="text-align:right">
          <label id="post">Руководитель</label>
      </div>
      <div class="col-lg-9 col-xs-9">
          <input id="managmentname" class="form-control">
      </div>
  </div>
</section>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Очистить выбор</button>
            <button type="button" class="btn btn-primary save update" data-loading-text="Секундочку...">Да, все правильно</button>
          </div>
        </div>
      </div>
    </div> 

    <?
    
});
*/
HOOK::add_action("user_name", function(){
    $db = new db;
    $user = $db->select('users', array('id'=>$_SESSION["user_id"]));
    if($user){
        return $user[0]['uname'].' '. $user[0]['ufam'];
    }else{
        return 'Новый Пользователь';
    }
   
   // return 'Иван Иванович';
});
HOOK::add_action("user_balance", function(){
    $db = new db;
    $user = $db->select('users', array('id'=>$_SESSION["user_id"]));
    if($user[0]['balance']){
        return $user[0]['balance'];
    }else{
        return '0';
    }
   
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
