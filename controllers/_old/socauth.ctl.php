<?php
//print 'run';
//var_dump($_GET);
require_once 'classes/SocialAuther/autoload.php';
//print 'run';
$adapterConfigs = array(
    'vk' => array(
        'client_id'     => '',
        'client_secret' => '',
        'redirect_uri'  => urlencode("https://manager.abelar.ru/socauth/?provider=vk")
    ),
    /*'odnoklassniki' => array(
        'client_id'     => '',
        'client_secret' => '',
        'redirect_uri'  => 'http://localhost/auth?provider=odnoklassniki',
        'public_key'    => ''
    ),
    'mailru' => array(
        'client_id'     => '',
        'client_secret' => '',
        'redirect_uri'  => 'http://localhost/auth/?provider=mailru'
    ),
    'yandex' => array(
        'client_id'     => '',
        'client_secret' => '',
        'redirect_uri'  => 'http://localhost/auth/?provider=yandex'
    ),*/
    'google' => array(
        'client_id'     => '551188422756-t01lst652s77o1rf8pq7eqnq4hl95j19.apps.googleusercontent.com',
        'client_secret' => 'yA0s-Y8mS3wnn-Yi2Mll9WEv',
        'redirect_uri'  => urlencode("https://manager.abelar.ru/socauth/?provider=google")
    ),
    'facebook' => array(
        'client_id'     => '1658376177781754',
        'client_secret' => '7d2cb9d5d26e96f395f17d25caf1c5c1',
        'redirect_uri'  => urlencode("https://manager.abelar.ru/socauth/?provider=facebook"),
        'response_type' => 'code',
        'scope'         => 'email,user_birthday'
    )
);

$adapters = array();
foreach ($adapterConfigs as $adapter => $settings) {
    $class = 'SocialAuther\Adapter\\' . ucfirst($adapter);
    $adapters[$adapter] = new $class($settings);
}

if (isset($_GET['provider']) && array_key_exists($_GET['provider'], $adapters) ) {
    $auther = new SocialAuther\SocialAuther($adapters[$_GET['provider']]);
    
    if ($auther->authenticate()) {
      print 'ok';
      /* $usr = $dbconn->select("SELECT *  FROM `users` WHERE `social_provider` = ? AND `social_id` = ? LIMIT 1;", $auther->getProvider(), $auther->getSocialId());
        
        if (empty($usr[0]['ID'])) {
            $dbconn->insert("insert into `users` (`login`,`password`,`email`,`social_provider`,`social_id`,`social_name`,`lang`,`theme`,`isactive`,`lastlogin`) values (?,?,?,?,?,?,?,?,?,?)",
                                        $auther->getProvider()."_".$auther->getSocialId(),
                                        md5(time().uniqid( rand(), true )),
                                        (!$auther->getEmail()?"":$auther->getEmail()),
                                        $auther->getProvider(),
                                        $auther->getSocialId(),
                                        (!$auther->getName()?"":$auther->getName()),
                                        "en",
                                        "default",
                                        1,
                                        time()); /*
            /*echo $dbconn->error();*/
        //}
        /* else {
            $usr = $usr[0];
            $userFromDb = new stdClass();
            $userFromDb->provider   = $usr['Provider'];
            $userFromDb->socialId   = $usr['Social_id'];
            $userFromDb->name       = $usr['Name'];
            $userFromDb->email      = $usr['Email'];
        }

        $user = new stdClass();
        $user->provider   = $auther->getProvider();
        $user->socialId   = $auther->getSocialId();
        $user->name       = $auther->getName();
        $user->email      = $auther->getEmail();

        if (isset($userFromDb) && $userFromDb != $user) {

            $dbconn->update("UPDATE `users` SET `social_name` = ?, `email` = ? WHERE `id`= ?",
                (empty($user->name)?"":$user->name),
                (empty($user->email)?"":$user->email),
                $usr['ID']
            );
        }*/
        
      //  $session->validate_social($auther->getProvider(),$auther->getSocialId());
    }
}else{
    print '<a href="'.$adapters['google']->getAuthUrl().'">try</a>';
}

//header("location:index.php");
exit();