<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

global $db;
global $URI_TO_CTL;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);
$lcc = $db->select('users', array('id'=>$_SESSION["user_id"]), 0, array('lcc'))[0]['lcc']; 
//var_dump($lcc);
$ctns = $db->select('countners', array('lcc'=>$lcc));
//var_dump($URI_TO_CTL);
?>
<link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css"><style>div.full_center{
	width: 100%;
    height: 100%;
    text-align: center;
    position: relative;
}
div.full_center	p {
    font-family: \'Source Sans Pro\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;
    background: #FFC107;
    color: white;
    </style>
            <div class="full_center"><p>Пожалуйста, укажите показания счетчиков</p>
                <div class="panel panel-default">
      <div class="panel-body">
        <form id="cntform" method="POST" action="/payments/get_count/">
          <div class="row">
            <? foreach($ctns as $cnt){ ?>
              <div class="col-md-12">
                <div class="form-group">
                  <label><?=$cnt['cnttitle']?></label>
				     <input name="cnt_<?=$cnt['cntnum']?>" value="<?=$cnt['cntvalue']?>" type="text" class="form-control">
                </div><!-- /.form-group -->
              </div><!-- /.col -->
              <? } ?>
          </div>
          <input type="hidden" name="docnum" value="<?=$URI_TO_CTL[1]?>"/>
        </form>
      </div>
    </div>
            
            
            <a class="btn btn-default btn-lg" onclick="document.getElementById('cntform').submit();" href="#" style="border: 0px;box-shadow: none;color: rgb(255, 252, 247);font-weight: 600;background: #FFC107;margin-left: 10px;">Переейти к оплате</a></div>
