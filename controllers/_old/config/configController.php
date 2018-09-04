<?
if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

$user = new config();

switch ($URI_TO_CTL[1])
{
    case 'update':
        $user->update();
    break;
    case 'delete':
        $user->delete();
    break;
    case 'add_new':
        $user->add_new();
    break;
    case 'get_list':
        $user->get_list();
    break;
    case 'get_it':
        $user->get_it();
    break;
    case 'get_table':
        $user->get_table();
    break;
    default:
        include('configView.php');
    break;
}

class config
{
    function update()
    {
        global $db;
        $db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
        $data['username'] = $_POST['username'];
        $data['email'] = $_POST['email'];
        $data['fio'] = $_POST['fio'];
        $data['mobile'] = $_POST['mobile'];
        $db->update('users', $data, array(
            'id' => $_POST['id']
        ));
        exit();
    }
    function delete()
    {
        global $URI_TO_CTL;
        global $db;
        $db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
        $db->delete('users', array(
            'id' => $URI_TO_CTL[2]
        ));
        exit();
    }
    function add_new()
    {
        global $db;
        $db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
        $data['username'] = $_POST['username'];
        $data['email'] = $_POST['email'];
        $data['fio'] = $_POST['fio'];
        $data['mobile'] = $_POST['mobile'];
        $db->insert('users', $data);
        exit();
    }
    function get_table()
    {
        $db_settings = array(
            'rdbms' => 'MYSQLi',
            'db_server' => 'localhost',
            'db_user' => 'manager-abelar',
            'db_passwd' => 'manager-abelar',
            'db_name' => 'manager-abelar',
            'db_port' => '3306',
            'charset' => 'utf8',
            'use_pst' => true,
            'pst_placeholder' => 'question_mark'
        );
        $ds = new dacapo($db_settings, null);

        $page_settings = array(
            "selectCountSQL" => "select count(*) as totalrows from `users` ",

            "selectSQL" => "select `id`, 
				`username`, `email`, `fio`, `mobile`, `id`				from `users`",
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
        foreach ($data['page_data'] as $key => $row)
        {
            // your code here
            

            $row['actions'] = "<button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#myModal\" data-whatever=\"{$row['id']}\"><i class=\"fa fa-edit\"></i></button>
                <button class=\"btn btn-primary btn-xs\" data-toggle=\"modal\" data-target=\"#EmailModal\" data-whatever=\"{$row['id']}\"><i class=\"fa fa-envelope\" ></i></button>
            	<button class=\"btn btn-primary btn-xs trash delclient\" data-whatever=\"{$row['id']}\"><i class=\"fa fa-trash\" ></i></button>";

            $data['page_data'][$key] = $row;

        }

        echo json_encode($data);
        exit();
    }
    function get_list()
    {
        global $URI_TO_CTL;
        global $db;
        $db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

        $user = $db->select('users');

        print json_encode($user, true);
        exit();
    }
    function get_it()
    {
        global $URI_TO_CTL;
        global $db;
        $db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

        if ($URI_TO_CTL[2])
        {
            $user = $db->select('users', array(
                'id' => $URI_TO_CTL[2]
            ));
            print json_encode($user[0], true);
            exit();

        }
    }
}

