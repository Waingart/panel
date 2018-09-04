<?
/**
* Class and Function List:
* Function list:
* - tasks()
* - run()
* - update()
* - delete()
* - add_new()
* - stop()
* - get_table()
* - get_list()
* - get_it()
* Classes list:
* - tasks
*/
if (!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
    header('Location: /');
    exit();
}

global $access_config;
$access_config = json_decode('{"get_table":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"record_allow_view":{"0":2,"OWNER":["user"]}},"get_it":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["id"],"2":[]},"record_allow_view":{"0":2,"OWNER":["id"]}},"update":{"allow_action":[2,1],"deny_action":[0],"field_disallow_update":{"1":["user","service","volume","complete","price","started","link","soctype","startcount","status"],"2":["user","service","volume","complete","price","started","link","soctype","startcount"]},"record_allow_update":[2]},"stop":{"allow_action":[2,1],"deny_action":[0],"field_disallow_update":{"1":["user","service","volume","complete","price","started","link","soctype","startcount"],"2":["user","service","volume","complete","price","started","link","soctype","startcount"]},"record_allow_update":[2]},"modal_editor":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":[],"2":[]},"field_disallow_update":{"1":["user","service","volume","complete","price","started","link","soctype","startcount","status"],"2":["user","service","volume","complete","price","started","link","soctype","startcount","status"]}}}', true);

global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

$tasks = new tasks();

$tasks->run($URI_TO_CTL[0]);

class tasks {
    private $db = 0;
    public function tasks() {
        global $db;
        $this->db = $db;
    }

    function run($run_action) {
        switch ($run_action) {
            case 'update':
                $this->update();
            break;
            case 'delete':
                $this->delete();
            break;
            case 'add_new':
                $this->add_new();
            break;
            case 'stop':
                $this->stop();
            break;
            case 'get_list':
                $this->get_list();
            break;
            case 'get_it':
                $this->get_it();
            break;
            case 'get_table':
                $this->get_table();
            break;
            default:
                global $act_id;
                if(!$_SESSION["user_id"]){
                    header('Location: /auth?action=login');
                    exit();
                }
                $act_id = 'tasks';
                include ('tasksView.php');
            break;
        }
    }

    function update() {
        global $access_config;
        if (!check_action_allow($access_config, 'update')) {
            print 'action not allowed';
            exit();
        }

        global $db;

        $data['user'] = $_POST['user'];
        $data['service'] = $_POST['service'];
        $data['volume'] = $_POST['volume'];
        $data['complete'] = $_POST['complete'];
        $data['price'] = $_POST['price'];
        $data['started'] = $_POST['started'];
        $data['link'] = $_POST['link'];
        $data['soctype'] = $_POST['soctype'];
        $data['startcount'] = $_POST['startcount'];
        $data['status'] = $_POST['status'];
        $data = filter_field_disallow_update($access_config, 'update', $data);

        $where = array(
            'id' => $_POST['id']
        );
        $allowed = check_record_allow_update($access_config, 'update', $where);
        if ($allowed) $this
            ->db
            ->update('tasks', $data, $where);
        exit();
    }
    function delete() {
        global $access_config;
        if (!check_action_allow($access_config, 'delete')) {
            print 'action not allowed';
            exit();
        }

        global $URI_TO_CTL;
        global $db;

        $where = array(
            'id' => $URI_TO_CTL[1]
        );
        $where = check_record_allow_update($access_config, $action, $where);
        if ($where) $this
            ->db
            ->delete('tasks', $where);
        exit();
    }
    function add_new() {
        print 'dddddd';
        $where['id'] = $_POST['service'];

	     $service_price = $this->db->select('services', $where, array('ed', 'ed_cost')); 
	     $service_price = $service_price[0];
	     $one_cost = $service_price['ed_cost'] / $service_price['ed'];
	     $order_cost = $one_cost * intval($_POST['quantity']);
	     var_dump($_SESSION['user_id']); //exit();
	     $user_auth = new Auth();
		if(!$_SESSION['user_id']){
		    print 'ccc';
		    $password1 = generatePassword();
            $new_user_id = $user_auth->create($username, $password1);
             
            
            $usr = $this->db->update('users', $data, array('id'=>$new_user_id));
		}
        $data['user'] = $_SESSION['user_id'];
        $data['service'] = $_POST['service'];
        $data['volume'] = $_POST['quantity'];
        $data['complete'] = 0;
        $data['price'] = $order_cost;
        $data['started'] = date("Y:m:d H:i");
        $data['link'] = $_POST['link'];
        $data['soctype'] = $_POST['soc'];
       // $data['startcount'] = $_POST['startcount'];
       $data['status'] = 1;
        $this
            ->db
            ->insert('tasks', $data);
        exit();
    }
    function stop() {
        exit();
    }
    function get_table() {
        global $access_config;
        if (!check_action_allow($access_config, 'get_table')) {
            print 'action not allowed';
            exit();
        }

        $db_settings = array(
            'rdbms' => 'MYSQLi',
            'db_server' => Config::$db_host,
            'db_user' => Config::$db_user,
            'db_passwd' => Config::$db_pass,
            'db_name' => Config::$db_name,
            'db_port' => '3306',
            'charset' => 'utf8',
            'use_pst' => true,
            'pst_placeholder' => 'question_mark'
        );
        $ds = new dacapo($db_settings, null);
        $get_fields[] = 'user';
        $get_fields[] = 'service';
        $get_fields[] = 'volume';
        $get_fields[] = 'complete';
        $get_fields[] = 'price';
        $get_fields[] = 'started';
        $get_fields[] = 'link';
        $get_fields[] = 'soctype';
        $get_fields[] = 'startcount';
        $get_fields[] = 'status';
        $get_fields[] = 'id';
        $get_fields = filter_field_disallow_view($access_config, 'get_table', $get_fields);

        $where = check_record_allow_view($access_config, 'get_table', $where); // TODO: мы должны принимать условия where из post или get запроса, чтобы отображать данные таблицы только по условию
        if (!$where) {
            print 'diallowed record for you';
            exit();
        }
        global $db;
        //var_dump($where);
        $page_settings = array(
            "selectCountSQL" => "select count(*) as totalrows from `tasks`".$this->db->where($where),
            "selectSQL" => $this
                ->db
                ->pre_select('tasks', $where, $get_fields) ,
            "page_num" => $_POST['page_num'],
            "rows_per_page" => $_POST['rows_per_page'],
            "columns" => $_POST['columns'],
            "sorting" => isset($_POST['sorting']) ? $_POST['sorting'] : array() ,
            "filter_rules" => isset($_POST['filter_rules']) ? $_POST['filter_rules'] : array()
        );
        $jfr = new jui_filter_rules($ds);
        $jdg = new bs_grid($ds, $jfr, $page_settings, $_POST['debug_mode'] == "yes" ? true : false);

        $data = $jdg->get_page_data();

        // data conversions (if necessary)
        foreach ($data['page_data'] as $key => $row) {
            // your code here
            if (isset($row_price)) {
                $row['price'] = $row_price[$row['price']];
            }
            $row['price'] = $row['price'] . '₽';
            $row_soctype[1] = 'VK';
            $row_soctype[2] = 'INSTAGRAM';
            if (isset($row_soctype)) {
                $row['soctype'] = $row_soctype[$row['soctype']];
            }
            $row_status[0] = 'Ожидает оплаты';
            $row_status[1] = 'Выполняется';
            $row_status[2] = 'Завершено';
            $row_status[3] = 'Остановлено';
            $row_status[4] = 'Ошибка';
            
            $row['actions'] ='';
            if($row['status'] == 0){
                $row['actions'] = "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#updateModal\" data-url=\"\" data-whatever=\"{$row['id']}\"><i class=\"fa fa-clock\"></i>Оплатить</button>";
            }
            $row['actions'] .= " <button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#updateModal\" data-url=\"\" data-whatever=\"{$row['id']}\"><i class=\"fa fa-clock\"></i>Остановить</button>";
            if (isset($row_status)) {
                $row['status'] = $row_status[$row['status']];
            }
            $data['page_data'][$key] = $row;
            
        }

        echo json_encode($data);
        exit();
    }
    function get_list() {
        global $access_config;
        if (!check_action_allow($access_config, 'get_list')) {
            print 'action not allowed';
            exit();
        }
        global $URI_TO_CTL;

        $get_fields[] = 'user';
        $get_fields[] = 'service';
        $get_fields[] = 'volume';
        $get_fields[] = 'complete';
        $get_fields[] = 'price';
        $get_fields[] = 'started';
        $get_fields[] = 'link';
        $get_fields[] = 'soctype';
        $get_fields[] = 'startcount';
        $get_fields[] = 'status';
        $get_fields[] = 'id';

        $get_fields = filter_field_disallow_view($access_config, 'get_list', $get_fields);
        $where = '';
        $where = check_record_allow_view($access_config, 'get_list', $where);
        if ($where) $tasks = $this
            ->db
            ->select('tasks', $where, $get_fields);

        global $db;

        print json_encode($tasks, true);
        exit();
    }
    function get_it() {
        global $access_config;
        if (!check_action_allow($access_config, 'get_it')) {
            print 'action not allowed';
            exit();
        }

        global $URI_TO_CTL;

        $get_fields[] = 'user';
        $get_fields[] = 'service';
        $get_fields[] = 'volume';
        $get_fields[] = 'complete';
        $get_fields[] = 'price';
        $get_fields[] = 'started';
        $get_fields[] = 'link';
        $get_fields[] = 'soctype';
        $get_fields[] = 'startcount';
        $get_fields[] = 'status';
        $get_fields[] = 'id';

        $get_fields = filter_field_disallow_view($access_config, 'get_it', $get_fields);
        if ($URI_TO_CTL[1]) {
            global $db;

            $where = array(
                'id' => $URI_TO_CTL[1]
            );
            $allowed = check_record_allow_view($access_config, 'get_it', $where);
            if ($allowed) {
                $tasks = $this
                    ->db
                    ->select('tasks', $where, $get_fields);
                print json_encode($tasks[0], true);
                exit();
            }
        }
    }
}

