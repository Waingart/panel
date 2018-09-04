<? if (!defined('ALLOW_RUN'))
{ // Запрещаем прямое обращение к файлу
    header('Location: /');
    exit();
}

$payments = new payments();

switch ($URI_TO_CTL[0])
{
    case 'change_target':
        $payments->change_target();
    break;
    case 'get_list':
        $payments->get_list();
    break;
    case 'get_it':
        $payments->get_it();
    break;
    case 'get_table':
        $payments->get_table();
    break;
    default:
        include ('paymentsView.php');
    break;
}

class payments
{
    function change_target()
    {
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
            "selectCountSQL" => "select count(*) as totalrows from `payments`",

            "selectSQL" => "select `pay_id`, 
				`pay_date`, `account`, `amount`, `pay_descr`, `target`, `inpay`, `pay_id`				from `payments`",
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
            $row_target[1] = 'Платежи исполнителям';
            $row_target[2] = 'Личные расходы';
            $row_target[3] = 'Орг. расходы';
            if (isset($row_target))
            {
                $row['target'] = $row_target[$row['target']];
            }
            $row_inpay[2] = 'Приход';
            $row_inpay[1] = 'Расход';
            if (isset($row_inpay))
            {
                $row['inpay'] = $row_inpay[$row['inpay']];
            }
            
            $row_account[2] = '<img src="/controllers/payments/tpl/tochka.png" style="display: inherit;    margin: 0 auto" />';
            $row_account[1] = '<img src="/controllers/payments/tpl/yamoney.png"    style="width: 18px;    height: 18px;    display: inherit;    margin: 0 auto" />';
            if (isset($row_account))
            {
                $row['account'] = $row_account[$row['account']];
            }
	        $row['target'] = "<a href=\"#{$row['pay_id']}\" data-toggle=\"modal\" data-target=\"#change_targetModal\" data-whatever=\"{$row['pay_id']}\">{$row['target']}</a>";

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

        $payments = $db->select('payments');

        print json_encode($payments, true);
        exit();
    }
    function get_it()
    {
        global $URI_TO_CTL;
        global $db;
        $db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');

        if ($URI_TO_CTL[1])
        {
            $payments = $db->select('payments', array(
                'pay_id' => $URI_TO_CTL[1]
            ));
            print json_encode($payments[0], true);
            exit();

        }
    }
}

