<?php

if(!defined('ALLOW_RUN'))
  die('Not allow');

if (!empty($_COOKIE['sid'])) {
    // check session id in cookies
    session_id($_COOKIE['sid']);
}
session_start();

class AuthorizationAjaxRequest extends AjaxRequest
{
    public $actions = array(
        "login" => "login",
        "logout" => "logout",
        "register" => "register",
    );

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            // Method Not Allowed
            http_response_code(405);
            header("Allow: POST");
            $this->setFieldError("main", "Method Not Allowed");
            return;
        }
        setcookie("sid", "");

        $username = $this->getRequestParam("email");
        $password = $this->getRequestParam("password");
        $remember = !!$this->getRequestParam("remember-me");

        if (empty($username)) {
            $this->setFieldError("email", "Введите ваш email");
            return;
        }

        if (empty($password)) {
            $this->setFieldError("password", "Введите пароль");
            return;
        }

        $user = new Auth();
        $auth_result = $user->authorize($username, $password, $remember);

        if (!$auth_result) {
            $this->setFieldError("password", "Неверный email или пароль");
            return;
        }

        $this->status = "ok";
        $this->setResponse("redirect", "/users");
        $this->message = sprintf("Hello, %s! Access granted.", $username);
    }

    public function logout()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            // Method Not Allowed
            http_response_code(405);
            header("Allow: POST");
            $this->setFieldError("main", "Method Not Allowed");
            return;
        }

        setcookie("sid", "");

        $user = new Auth();
        $user->logout();

        $this->setResponse("redirect", ".");
        $this->status = "ok";
    }

    public function register()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            // Method Not Allowed
            http_response_code(405);
            header("Allow: POST");
            $this->setFieldError("main", "Method Not Allowed");
            return;
        }

        setcookie("sid", "");

        $username = $this->getRequestParam("username");
        $email = $this->getRequestParam("email");
        $password1 = $this->getRequestParam("password1");
        $password2 = $this->getRequestParam("password2");

        if (empty($username)) {
            $this->setFieldError("username", "Укажите лицевой счет");
            return;
        }
        
        if (empty($email)) {
            $this->setFieldError("email", "Укажите email");
            return;
        }
        
       /* if (empty($password1)) {
            $this->setFieldError("password1", "Enter the password");
            return;
        }

        if (empty($password2)) {
            $this->setFieldError("password2", "Confirm the password");
            return;
        }

        if ($password1 !== $password2) {
            $this->setFieldError("password2", "Confirm password is not match");
            return;
        }
*/
        $user = new Auth();
        $password1 = generatePassword();
        try {
            $new_user_id = $user->create($username, $password1);
            $data['pass_num'] = $_POST["docnum"];
            $fio = explode(' ', trim($this->getRequestParam("fio")));
            $data['ufam'] = $fio[0];
            $data['uname'] = $fio[1];
            $data['uoth'] = $fio[2];
            $data['phone'] = $this->getRequestParam("phone");
            $data['email'] = $email;
           // var_dump($new_user_id);
            $user->fill_userdata($new_user_id, $data);
            $mailer = new mail_send();
            $mailer->sendmail($_POST["email"], '', 'Регистрация в личном кабинете Ingrid', "Ваш доступ в личный кабинет:<br>\r\nСсылка для входа: <a href='http://cabinet.ingrid-kld.ru/auth?action=login'>http://cabinet.ingrid-kld.ru/auth?action=login</a><br>\r\nЛицевой счет: $username<br>\r\nПароль: $password1");
            //
        } catch (\Exception $e) {
            $this->setFieldError("username", $e->getMessage());
            return;
        }
        $user->authorize($username, $password1);

        $this->message = sprintf("Добро пожаловать, %s! Спасибо за регистрацию.", $username);
        $this->setResponse("redirect", "/users/");
        $this->status = "ok";
    }
}
//$user = new Auth();
//$user->create('manager', '987654321');
$ajaxRequest = new AuthorizationAjaxRequest($_REQUEST);
$ajaxRequest->showResponse();
