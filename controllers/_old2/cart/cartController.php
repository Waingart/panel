<?
if (!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
    header('Location: /');
    exit();
}
$cart = new cart();

$cart->run($URI_TO_CTL[0]);

class cart {
    private $db = 0;
    public function cart() {
        $this->db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);
    }
    function run($run_action) {
        global $URI_TO_CTL;
        switch ($run_action) {
            case 'show':
                //$this->show();
                //var_dump($URI_TO_CTL);
                $socid =$URI_TO_CTL[1];
                $stype =$URI_TO_CTL[2];
                include ('cartView.php');
            break;
            default:
                $socid = 1;
                //include ('cartView.php');
            break;
        }
    }
    function show(){
        $URI_TO_CTL[0];
    }
}