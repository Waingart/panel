<?php
/**
 * ajax_page_data.dist.php, bs_grid ajax fetch page data template script
 *
 * Sample php file getting totalrows and page data
 *
 * Da Capo database wrapper is required https://github.com/pontikis/dacapo
 *
 * @version 0.9.2 (28 May 2014)
 * @author Christos Pontikis http://pontikis.net
 * @license  http://opensource.org/licenses/MIT MIT license
 **/

// PREVENT DIRECT ACCESS (OPTIONAL) --------------------------------------------
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
	print 'Access denied - not an AJAX request...' . ' (' . __FILE__ . ')';
	exit;
}

// REQUIRED --------------------------------------------------------------------
require_once 'dacapo.class.php';                                 // CONFIGURE
require_once 'jui_filter_rules.php';                       // CONFIGURE
require_once 'bs_grid.php';                                // CONFIGURE

// create new datasource                                            // CONFIGURE
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
	"selectCountSQL" => "select count(*) as totalrows from `payments`",    // CONFIGURE

	"selectSQL" => "select `pay_id`, 
	`amount`,  
	`pay_date`, 
	`account`, 
	`target`, 
	`inpay`, 
	`pay_descr`
	from `payments`",  // CONFIGURE

/*
SELECT c.id as customer_id, c.lastname, c.firstname, c.email, g.gender, c.date_updated
                  FROM customers c INNER JOIN lk_genders g ON (c.lk_genders_id = g.id)
				  
{field: "doc_id", header: "№ Счета"},
            {field: "client", header: "Клиент"},
            {field: "amount", header: "Сумма"},
			{field: "doc_data", header: "Дата счета"},
            {field: "status", header: "Статус"},
            {field: "actions", header: "Операции"},
			*/
	
	"page_num" => $_POST['page_num'],
	"rows_per_page" => $_POST['rows_per_page'],
	"columns" => $_POST['columns'],
	"sorting" =>  isset($_POST['sorting']) ? $_POST['sorting'] : array(),
	"filter_rules" => isset($_POST['filter_rules']) ? $_POST['filter_rules'] : array()
);

$jfr = new jui_filter_rules($ds);
$jdg = new bs_grid($ds, $jfr, $page_settings, $_POST['debug_mode'] == "yes" ? true : false);

$data = $jdg->get_page_data();

$pay_targets[1] = 'Платежи исполнителям';
$pay_targets[2] = 'Личные расходы';
$pay_targets[3] = 'Орг. расходы';

// data conversions (if necessary)
foreach($data['page_data'] as $key => $row) {
	// your code here

	$row['target'] = "<a href=\"{$row['pay_id']}\" data-toggle=\"modal\" data-target=\"#ChangeStatusModal\" data-whatever=\"{$row['pay_id']}\">{$pay_targets[$row['target']]}</a>";
    
    
    $data['page_data'][$key] = $row;

}

echo json_encode($data);