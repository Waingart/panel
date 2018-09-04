<?php


class Auth
{
    private $id;
    private $username;
    private $db;
    private $user_id;
    private $access_level;

    private $is_authorized = false;

    public function __construct($username = null, $password = null)
    {
        $this->username = $username;
        $db = new db();
        $this->db = $db->res;
    }

    public function __destruct()
    {
        $this->db = null;
    }

    public static function isAuthorized()
    {
        if (!empty($_SESSION["user_id"])) {
            return (bool) $_SESSION["user_id"];
        }
        return false;
    }

    public function passwordHash($password, $salt = null, $iterations = 10)
    {
        $salt || $salt = uniqid();
        $hash = md5(md5($password . md5(sha1($salt))));

        for ($i = 0; $i < $iterations; ++$i) {
            $hash = md5(md5(sha1($hash)));
        }

        return array('hash' => $hash, 'salt' => $salt);
    }

    public function getSalt($username) {
        $query = "select salt from users where email = :email limit 1";
        $sth = $this->db->prepare($query);
        $sth->execute(
            array(
                ":email" => $username
            )
        );
        $row = $sth->fetch();
        if (!$row) {
            return false;
        }
        return $row["salt"];
    }
    public function socauthorize($socid, $remember=false)
    {
        $query = "select id, email, socid, `access` from users where
            socid = :socid limit 1";
        $sth = $this->db->prepare($query);

        $sth->execute(
            array(
                ":socid" => $socid,
            )
        );
        $this->user = $sth->fetch();
        
        if (!$this->user) {
            $this->is_authorized = false;
        } else {
            $this->is_authorized = true;
            $this->user_id = $this->user['id'];
            $this->access_level = $this->user['access'];
            $this->saveSession($remember);
        }

        return $this->is_authorized;
    }
    public function authorize($username, $password, $remember=false)
    {
        $query = "select id, email, `access` from users where
            email = :email and password = :password limit 1";
        $sth = $this->db->prepare($query);
        $salt = $this->getSalt($username);

        if (!$salt) {
            return false;
        }

        $hashes = $this->passwordHash($password, $salt);
       // print $hashes['hash'];
        $sth->execute(
            array(
                ":email" => $username,
                ":password" => $hashes['hash'],
            )
        );
        $this->user = $sth->fetch();
        
        if (!$this->user) {
            $this->is_authorized = false;
        } else {
            $this->is_authorized = true;
            $this->user_id = $this->user['id'];
            $this->access_level = $this->user['access'];
            $this->saveSession($remember);
        }

        return $this->is_authorized;
    }

    public function logout()
    {
        if (!empty($_SESSION["user_id"])) {
            unset($_SESSION["user_id"]);
            unset($_SESSION["access_level"]);
        }
    }

    public function saveSession($remember = false, $http_only = true, $days = 7)
    {
        $_SESSION["user_id"] = $this->user_id;
        $_SESSION["access_level"] = $this->access_level;
        
        if ($remember) {
            // Save session id in cookies
            $sid = session_id();

            $expire = time() + $days * 24 * 3600;
            $domain = ""; // default domain
            $secure = false;
            $path = "/";

            $cookie = setcookie("sid", $sid, $expire, $path, $domain, $secure, $http_only);
        }
    }
    public function fill_userdata($user_id, $data) {
        $db = new db();
        $db->update('users', $data, array('id'=>$user_id));
    }
    public function create($username, $password) {
        $user_exists = $this->getSalt($username);

        if ($user_exists) {
            throw new \Exception("Этот email уже зарегистрирован: " . $username, 1);
        }

        $query = "insert into users (lcc, password, salt, access)
            values (:lcc, :password, :salt, 1)";
        $hashes = $this->passwordHash($password);
        $sth = $this->db->prepare($query);

        try {
            $this->db->beginTransaction();
            $result = $sth->execute(
                array(
                    ':lcc' => $hashes['hash'],
                    ':password' => $hashes['hash'],
                    ':salt' => $hashes['salt'],
                )
            );
            $ret = $this->db->lastInsertId(); 
            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollback();
            echo "Database error: " . $e->getMessage();
            die();
        }

        if (!$result) {
            $info = $sth->errorInfo();
            printf("Database error %d %s", $info[1], $info[2]);
            die();
        }else{
            $_SESSION["user_id"] = $ret;
            $_SESSION["access_level"] = 1;
            return $ret; 
        } 
       // var_dump($ret);
        //return $result;
    }
}
