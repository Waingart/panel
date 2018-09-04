<?
require_once 'classes/yandex-money-sdk-php-master/lib/api.php';
use \YandexMoney\API;

$client_id='03EE2C6D6E413DF085A6A6AA39BC202360766FACEB5FBD55D86EEE68450CE4AD';
$client_secret='6D452C43F6ABEA034134D9B2C28B049C5F85073BE9E4D092584143FF3040DF42AB7506BABC1D1130AC2B5AF35BDE34AFF9FE39AD79B2D0D7318139DA4786E6E3';
$redirect_uri='https://manager.abelar.ru/yamoney/';
$scope= array('account-info', 'operation-history');
    
if(isset($_GET['code'])){
    $code = $_GET['code'];
    
    $access_token_response = API::getAccessToken($client_id, $code, $redirect_uri, $client_secret);
    if(property_exists($access_token_response, "error")) {
        // process error
    }
    $access_token = $access_token_response->access_token;
  
    global $db;
    $db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
    $client = $db->insert('yamoney_tokens', array('user'=>1, 'token'=>$access_token)); 
print "token saved";
    $api = new API($access_token);
    
    // get account info
    $acount_info = $api->accountInfo();
    var_dump($acount_info);
    // check status 
    
    // get operation history with last 3 records
    $operation_history = $api->operationHistory(array("records"=>30));
    var_dump($operation_history);
    // check status 
    

}else{
    global $db;
    $db = new db('localhost', 'manager-abelar', 'manager-abelar', 'manager-abelar');
    $mytoken = $db->select('yamoney_tokens', array('user'=>1)); 

    if($mytoken){
        print "token from db ok";
        $access_token = $mytoken[0]['token'];
        $api = new API($access_token);
    
        // get account info
        $acount_info = $api->accountInfo();
        var_dump($acount_info);
        // check status 
        
        // get operation history with last 3 records
        $operation_history = $api->operationHistory(array("records"=>30));
        var_dump($operation_history);
        // check status 
        foreach($operation_history->operations as $payment_data){
            $data['amount'] = $payment_data->amount;
            $data['pay_date'] = $payment_data->datetime;
            $data['account'] = 1;
            $data['target'] = 1;
            $data['inpay'] = $payment_data->direction=="in"?2:1;
            $data['pay_descr'] = $payment_data->title;
            $data['oper_id'] = $payment_data->operation_id;
            
            $db->insert('payments', $data);
        }
    } else{
    
        $auth_url = API::buildObtainTokenUrl($client_id, $redirect_uri, $scope);
        
        header('Location: '.$auth_url);
    }
}