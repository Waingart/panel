<?if(!defined('ALLOW_RUN')) { // Запрещаем прямое обращение к файлу
  header('Location: /'); 
  exit();
}

global $access_config;
$access_config = json_decode('{"get_table":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["id"],"2":[]},"field_disallow_update":{"1":["id","docnum","sum","docnum","paydate","lcc","doc_id","status","user_id"],"2":["id","docnum","sum","docnum","paydate","lcc","doc_id","status","user_id"]},"record_allow_update":[],"record_allow_view":{"0":2,"OWNER":["user_id"]}},"get_it":{"allow_action":[2,1],"deny_action":[0],"field_disallow_view":{"1":["id"],"2":[]},"field_disallow_update":{"1":["id","docnum","sum","docnum","paydate","lcc","doc_id","status","user_id"],"2":["id","docnum","sum","docnum","paydate","lcc","doc_id","status","user_id"]},"record_allow_update":[],"record_allow_view":{"0":2,"OWNER":["user_id"]}}}', true);



global $db;
$db = new db(Config::$db_host, Config::$db_user, Config::$db_pass, Config::$db_name);

$payments = new payments();

$payments->run($URI_TO_CTL[0]);

class payments { 
    private $db = 0;
    public function payments(){
        global $db;
        $this->db = $db;
    }
    
    function run($run_action){ 
        
        switch ($run_action) {		
        case 'get_list':
        $this->get_list();
        break;	
		case 'get_it':
        $this->get_it();
        break;	
		case 'get_table':
        $this->get_table();
        break;	
        case 'go_pay':
        $this->go_pay();
        break;
        case 'go_count':
        $this->go_count();
        break;
        case 'get_count':
        $this->get_count();
        break;
        case 'go_pay_one':
        $this->go_pay_one();
        break;
        case 'pay_result':
        $this->pay_result();
        break;
        
		default:
		    global $act_id;
		    $act_id = payments;
		    if($_SESSION['access_level'] == '2'){
		        HOOK::add_action("section_title", function(){return 'Платежи всех пользователей';});
		    }else{
		        HOOK::add_action("section_title", function(){return 'История платежей';});
		    }
		    
            include('paymentsView.php');
        break;
        }
    }
    function get_table(){
        $data = $this->db->select('payments', array('status'=>2));
        foreach($data as $payment){
            $record['sum'] = $payment['sum'];
            $record['date'] = $payment['paydate'];
            
            $users = $this->db->select('users', array('id'=>$payment['user_id']));
            //var_dump($users);
            $record['lc'] = $users[0]['lcc'];
            $odincdata = $this->db->select('1cdata', array('lcc'=>$users[0]['lcc']));
            $record['address'] = $odincdata[0]['building'].', '.$odincdata[0]['room'];
            
            
            $countner = $this->db->select('countners', array('lcc'=>$users[0]['lcc']));
            //$record['address'] = $odincdata[0]['building'].', '.$odincdata[0]['room'];
            $record['cntnum'] = $countner[0]['cntnum'];
            //$record['cnttitle'] = $countner[0]['cnttitle'];
            $record['cntvalue'] = $countner[0]['cntvalue'];
              
              
            $export[] = $record;
            //var_dump($export);
            //exit();
        }
        if($_GET['csv']==1){
            
            foreach($export as $string){
                
                print "{$string['date']};{$string['lc']};{$string['sum']};{$string['cntnum']};{$string['cntvalue']};{$string['address']};\r\n";
                
            }
            
        }else{
            print "<table>";
            foreach($export as $string){
                print "<tr>";
                print "<td>{$string['date']}</td>\t\t<td>{$string['lc']}</td>\t\t<td>{$string['sum']}</td>\t\t<td>{$string['cntnum']}\t\t<td>{$string['cntvalue']}\t\t<td>{$string['address']}</td>\r\n";
                print "</tr>";
            }
            print "</table>";
        }
        
    }
    function get_count(){
        $data = $this->db->select('payments', array('status'=>2)); //, array('status'=>2)
        foreach($data as $payment){
            $record['sum'] = $payment['sum'];
            $record['date'] = $payment['paydate'];
            
            $users = $this->db->select('users', array('id'=>$payment['user_id']));
            //var_dump($users);
            $record['lc'] = $users[0]['lcc'];
            $odincdata = $this->db->select('countners', array('lcc'=>$users[0]['lcc']));
            //$record['address'] = $odincdata[0]['building'].', '.$odincdata[0]['room'];
            $record['cntnum'] = $odincdata[0]['cntnum'];
             $record['cnttitle'] = $odincdata[0]['cnttitle'];
              $record['cntvalue'] = $odincdata[0]['cntvalue'];
            
            
            $export[] = $record;
            //var_dump($export);
            //exit();
        }
        print "<table>";
        foreach($export as $string){
            print "<tr>";
            print "<td>{$string['date']}</td>\t\t<td>\"{$string['lc']}\"</td>\t\t<td>{$string['sum']}</td>\t\t<td>{$string['cntnum']}</td>\t\t<td>{$string['cnttitle']}</td>\t\t<td>{$string['cntvalue']}</td>\r\n";
            print "</tr>";
        }
        print "</table>";
    }
    function get_list(){
        $data = $this->db->select('users');
        foreach($data as $payment){
           // $record['sum'] = $payment['sum'];
           // $record['date'] = $payment['paydate'];
            
            // $users = $this->db->select('users', array('id'=>$payment['user_id']));
            //var_dump($users);
            $record['lcc'] = $payment['lcc'];
            //$record['lc'] = $users[0]['lcc'];
            $odincdata = $this->db->select('countners', array('lcc'=>$payment['lcc']));
            //$record['address'] = $odincdata[0]['building'].', '.$odincdata[0]['room'];
            $record['cntnum'] = $odincdata[0]['cntnum'];
             $record['cnttitle'] = $odincdata[0]['cnttitle'];
              $record['cntvalue'] = $odincdata[0]['cntvalue'];
            
            
            $export[] = $record;
            //var_dump($export);
            //exit();
        }
        print "<table>";
        foreach($export as $string){
            print "<tr>";
            print "<td>{$string['lcc']}</td>\t\t<td>{$string['cntnum']}</td>\t\t<td>{$string['cnttitle']}</td>\t\t<td>{$string['cntvalue']}</td>\r\n";
            print "</tr>";
        }
        print "</table>";
    }
   // go_count
   function go_count(){
        $data = $this->db->select('users');
        foreach($data as $payment){
           // $record['sum'] = $payment['sum'];
           // $record['date'] = $payment['paydate'];
            
            // $users = $this->db->select('users', array('id'=>$payment['user_id']));
            //var_dump($users);
            $record['lcc'] = $payment['lcc'];
            //$record['lc'] = $users[0]['lcc'];
            $odincdata = $this->db->select('1cdata', array('lcc'=>$payment['lcc']));
            //$record['address'] = $odincdata[0]['building'].', '.$odincdata[0]['room'];
            $record['fullname'] = $odincdata[0]['fullname'];
             //$record['cnttitle'] = $odincdata[0]['cnttitle'];
            //  $record['cntvalue'] = $odincdata[0]['cntvalue'];
            
            
            $export[] = $record;
            //var_dump($export);
            //exit();
        }
        print "<table>";
        foreach($export as $string){
            print "<tr>";
            print "<td>{$string['lcc']}</td>\t\t<td>{$string['fullname']}</td>\r\n";
            print "</tr>";
        }
        print "</table>";
    }
}