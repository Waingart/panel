<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}


global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

$users = new reg();

$users->run($URI_TO_CTL[0]);

class reg { 
    private $db = 0;
    public function reg(){
        global $db;
        $this->db = $db;
        
    }
    
    function run($run_action){ 
        
        switch ($run_action) {	

            case 'approve':
                
            $this->approve();
        break;	
		    case 'autologin':
            $this->autologin();
        break;	
	
		default:
		   
		    
        break;
        }
    } 
    function approve(){
        global $URI_TO_CTL;
       // print $URI_TO_CTL[1];
        $user_id = explode('_', $URI_TO_CTL[1]);
       
        if($user_id[1] == md5($user_id[0].'secsecsecret')){
            //print 'ok';
             $user = new Auth();
            $password = generatePassword();
            //print $password;
            $res = $this->db->select('users', array('id'=>$user_id[0], 'access'=>0), array('lcc', 'email'));
            if($res){
                $username = $res[0]['lcc'];
                $mailer = new mail_send();
                $key = base64_encode($username.'_'.$password);
                $mailer->sendmail($res[0]['email'], '', 'Регистрация в личном кабинете Ingrid', "Ваш доступ в личный кабинет:<br>\r\nСсылка для входа: <a href='http://cabinet.ingrid-kld.ru/auth?action=login'>http://cabinet.ingrid-kld.ru/auth?action=login</a><br>\r\nЛицевой счет: $username<br>\r\nПароль: $password<br>\r\nВход без пароля: <a href='http://cabinet.ingrid-kld.ru/reg/autologin/$key'>Войти</a>");
                
                $user->changePassword($user_id[0], $password) ;
                
                $this->db->update('paydocs', array('user_id'=>$user_id[0]), array('lcc'=>$username));
                
                print 'Пользователю отправлен доступ в кабинет';
            }else{
                print 'Регистрация этого пользователя уже была подтверждена ранее';
            }
            
        }else{
           print 'Некорректная ссылка для подтверждения.'; 
        }
       // $res = $this->db->select();
        
    }
    function autologin(){
         global $URI_TO_CTL;
         $userauth = explode('_', base64_decode($URI_TO_CTL[1]));
        // var_dump($userauth);
        $user = new Auth();
        
       // var_dump($user->authorize($userauth[0], $userauth[1]));

       header('Location: /paydocs/');
    }
}

 