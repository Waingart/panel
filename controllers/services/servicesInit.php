<?global $nav_config;
$nav_config = json_decode('{"menu_title":{"2":"Все события", "0":"Все события", "1":"Все события"},"url":"services","url_access":[0,1,2]}', true);

//if($_SESSION["access_level"] > 1){
$NAV = new NAV();
    NAV::start(
    	 [
    	        
    			'services'=>$NAV->add(['title'=>$nav_config['menu_title'][$_SESSION["access_level"]], 'url'=>$nav_config['url'], 'icon'=>'fa-dashboard'])
    	 ]);
//}
//HOOK::add_action('hook_name', $NAV);
HOOK::add_action('access_rule', 'URI_Shem::set_access_data', [array('url'=>$nav_config['url'], 'access'=>$nav_config['url_access'], 'file'=>'services/servicesController.php')]);

  HOOK::add_action('footer', function(){
        return "<script>
     /*   
        $('#RegNow').modal({ 
      backdrop: 'static',
      keyboard: false 
        });
        $('#RegNow').modal('show');
        $('#RegNow1').modal('show');
          $('#RegNow2').modal('show');
        $('#RegNow3').modal('show');
        $('#Cash').modal('show');
      */  
        $('#wizard').modal('show'); 
        

        function ulogin_data(token){
            $.getJSON(\"//ulogin.ru/token.php?host=\" + encodeURIComponent(location.toString()) + \"&token=\" + token + \"&callback=?\", function(data){
                data = $.parseJSON(data.toString());
                if(!data.error){
                    alert(\"Привет, \"+data.first_name+\" \"+data.last_name+\"!\");
                }
            });
        }
        </script>";
    });
if($_SESSION['access_level'] == 0){	
HOOK::add_action('modal_windows', function(){
?>
<!-- Modal -->
    <div class="modal fade" id="wizard" tabindex="-1" role="dialog" aria-labelledby="RegNowLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
		            <!--      Wizard container        -->
<div class="wizard-container">
	<div class="card wizard-card" data-color="red" id="wizard">
		
			<!--        You can switch " data-color="azure" "  with one of the next bright colors: "blue", "green", "orange", "red"           -->
			<div class="wizard-header hidden-xs">
				<h3 class="wizard-title">Find your next desk</h3>
				<p class="category">Book from thousands of unique work and meeting spaces.</p>
			</div>
			<div class="wizard-navigation">
				<div class="progress-with-circle">
					<div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="3" style="width: 21%;"></div>
				</div>
				<ul>
					<!--li>
						<a href="#captain" data-toggle="tab">
							<div class="icon-circle">
								<i class="ti-briefcase"></i>
							</div>
							Соцсети
						</a>
					</li-->
					<li>
						<a href="#description" data-toggle="tab">
							<div class="icon-circle"> <i class="ti-user"></i> </div> Контакты </a>
					</li>
					<li>
						<a href="#details" data-toggle="tab">
							<div class="icon-circle"> <i class="ti-briefcase"></i> </div> Реквизиты </a>
					</li>
				</ul>
			</div>
			<div class="tab-content">
			    
				<div class="tab-pane" id="details">
				    <form id="egrul" action="" method="">
					<section class="query">
						<input id="party" name="party" type="text" size="100" class="form-control" placeholder="Начните ввод названия Вашей организации или ИП и выберите из списка" /> </section>
					<section class="private">
						<div class="row">
							<div class="col-lg-12 col-xs-12" style="text-align:center">
								<label>
									<input type="checkbox" id="noorg" name="noorg" class="flat-red"> Нет юрлица и ИП </label>
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
							<div class="col-lg-3 hidden-xs" style="text-align:right">
								<label>ОГРН</label>
							</div>
							<div class="col-lg-9">
								<input id="ogrn" name="ogrn" class="form-control" placeholder="ОГРН"> </div>
						</div>
						<div class="row">
							<div class="col-lg-3 hidden-xs" style="text-align:right">
								<label>ИНН / КПП</label>
							</div>
							<div class="col-lg-9">
								<input id="inn_kpp" name="inn_kpp" class="form-control" placeholder="ИНН / КПП"> </div>
						</div>
						<div class="row">
							<div class="col-lg-3 hidden-xs" style="text-align:right">
								<label>Адрес</label>
							</div>
							<div class="col-lg-9">
								<input id="address" name="address" class="form-control" placeholder="Адрес"> </div>
						</div>
						<div class="row">
							<div class="col-lg-3 hidden-xs" style="text-align:right">
								<label id="post">Руководитель</label>
							</div>
							<div class="col-lg-9">
								<input id="managmentname" name="managmentname" class="form-control" placeholder="Директор"> </div>
						</div>
					</section>
					</form>
				</div>
				<div class="tab-pane" id="captain">
					<h5 class="info-text">How do you want to rent the office? </h5>
					<div class="">
						<div class="login-box-body">
							<p class="login-box-msg">Привяжите Ваш аккаунт в соцсети и входите одним кликом!</p>
							<div class="callout callout-danger main-error hide"></div>
							<div class="row">
								<div class="col-xs-12">
									<div class="form-group has-feedback" style="text-align: center;">
										<script src="//ulogin.ru/js/ulogin.js"></script>
										<div id="uLogin_0362e9c0" data-uloginid="0362e9c0"></div>
									</div>
								</div>
								<!-- /.col -->
								<div class="col-xs-12">
									<button style="    width: 300px;
    padding: 13px;
    padding-left: 63px;
    margin: 30px auto;
    background-image: url(http://narutoprivate.com/static/not-found.png);
    background-size: 40px;
    background-repeat: no-repeat;
    background-position-y: 3px;
    background-position-x: 11px; margin: 30px auto;" type="submit" class="btn btn-block btn-flat" onClick="parent.$('#RegNow1').modal('hide');">У меня нет аккаунта в соцсетях</button>
								</div>
								<!-- /.col -->
							</div>
							<!-- /.social-auth-links -->
						</div>
						<!-- /.login-box-body -->
					</div>
					<!-- /.login-box -->
				</div>
				<div class="tab-pane" id="description">
				    <form id="contacts" action="" method="">
					<section class="result">
						<div class="row">
							<div class="col-lg-2 hidden-xs" style="text-align:right">
								<label>Имя</label>
							</div>
							<div class="col-lg-9 col-xs-12">
								<input id="name" name="name" class="form-control" placeholder="Имя"> </div>
						</div>
						<div class="row">
							<div class="col-lg-2 hidden-xs" style="text-align:right">
								<label>Отчество</label>
							</div>
							<div class="col-lg-9 col-xs-12">
								<input id="otch" name="otch" class="form-control" placeholder="Отчество"> </div>
						</div>
						<div class="row">
							<div class="col-lg-2 hidden-xs" style="text-align:right">
								<label>Телефон</label>
							</div>
							<div class="col-lg-9 col-xs-12">
								<input id="phone" name="phone" class="form-control" type="tel" placeholder="Телефон" autocomplete="on"> 
								
                                    <span id="valid-msg" class="hide">✓</span>
                                    <span id="error-msg" class="hide">✘</span>
								</div>
						</div>
						<div class="row">
							<div class="col-lg-2 hidden-xs" style="text-align:right">
								<label>Email</label>
							</div>
							<div class="col-lg-9 col-xs-12">
								<input id="email" name="email" type="email" class="form-control" placeholder="Email"> </div>
						</div>
					</section>
					<section class="private">
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-lg-offset-2" style="">
								<input type="checkbox" class="flat-red" checked>
								<label class="forcheck"> Даю согласие на получение полезных материалов на Email </label>
							</div>
							<div class="col-lg-12 col-xs-12 col-lg-offset-2" style="">
								<input type="checkbox" class="flat-red" checked>
								<label class="forcheck"> Хочу, чтобы мне перезвонили для консультации </label>
							</div>
						</div>
					</section>
					</form>
				</div>
			</div>
			<div class="wizard-footer">
				<div class="pull-right">
					<input type='button' class='btn btn-next btn-fill btn-primary' name='next' value='Далее' />
					<input type='button' class='btn btn-finish btn-fill btn-primary' id="finish" name='finish' value='Готово!' /> </div>
				<div class="pull-left">
					<input type='button' class='btn btn-previous btn-default' name='previous' value='Назад' /> </div>
				<div class="clearfix"></div>
			</div>
		</form>
	</div>
</div>
<!-- wizard container -->
        </div>
      </div>
    </div> 
<?    
}); 
}