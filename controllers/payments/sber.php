<?
define('API_URL_PROD', 'https://securepayments.sberbank.ru/payment/rest/');
define('API_URL_TEST', 'https://3dsec.sberbank.ru/payment/rest/');


class SberPay {
        public function __construct()
        {
            $this->testmode = 'no';
			$this->stage = 'one-stage';
			$this->merchant = 'ingrid_kid-api';
			$this->password = '2%M*r2tzX@!9';
        }
		/*
         * Generate the dibs button link
         */
        public function generate_form($order_id, $amount)
        {
			
			
            $amount = $amount * 100;

            if ($this->testmode == 'yes') {
                $action_adr = API_URL_TEST;
            } else {
                $action_adr = API_URL_PROD;
            }

            $extra_url_param = '';
            if ($this->stage == 'two-stage') {
                $action_adr .= 'registerPreAuth.do';
            } else if ($this->stage == 'one-stage') {
               // $extra_url_param = '&wc-callb=callback_function';
                $action_adr .= 'register.do';
            }

            // prepare args array
            $args = array(
                'userName' => $this->merchant,
                'password' => $this->password,
                'amount' => $amount,
                'returnUrl' => 'https://cabinet.ingrid-kld.ru/payments/pay_result/?order_id=' . $order_id . $extra_url_param,
//                'currency' => CURRENCY_CODE,
                /*'jsonParams' => json_encode(
                    [
                        'CMS:' => 'Wordpress ' . get_bloginfo('version') . " + woocommerce version: " . wpbo_get_woo_version_number(),
                        'Module-Version: ' =>  get_plugin_data(__FILE__)['Version']
                    ]
                ),*/
                'taxSystem' => 0
            );


            

            for ($i = 0; $i++ < 30;) {

                $args['orderNumber'] = $order_id . '_' . $i;

                $rbsCurl = curl_init();
                curl_setopt_array($rbsCurl, array(
                    /* CURLOPT_HTTPHEADER => array(
                        'CMS: Wordpress ' . get_bloginfo('version') . " + woocommerce version: " . wpbo_get_woo_version_number(),
                        'Module-Version: ' . get_plugin_data(__FILE__)['Version'],
                    ), */
                    CURLOPT_URL => $action_adr,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => http_build_query($args, '', '&')
                ));

                $response = curl_exec($rbsCurl);
                curl_close($rbsCurl);


                $response = json_decode($response, true);
                if ($response['errorCode'] != '1') break;
            }


            $errorCode = $response['errorCode'];

            if ($errorCode == 0) {

                header('Location: '.$response['formUrl']);
                exit;

            } else {
                print '<p>' . 'Ошибка #' . $errorCode . ': ' . $response['errorMessage'] . '</p>' .
                '<a class="button cancel" href="'  . '">' . 'Отказаться от оплаты и вернуться в корзину' . '</a>';
            }
        }
		
		public function callb()
        {

            //if (isset($_GET['rbspayment']) AND $_GET['rbspayment'] == 'result') {
                if ($this->testmode == 'yes') {
                    $action_adr = API_URL_TEST;
                } else {
                    $action_adr = API_URL_PROD;
                }

                $action_adr .= 'getOrderStatusExtended.do';

                $args = array(
                    'userName' => $this->merchant,
                    'password' => $this->password,
                    'orderId' => $_GET['orderId'],
                );

                $rbsCurl = curl_init();
                curl_setopt_array($rbsCurl, array(
                    CURLOPT_URL => $action_adr,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => http_build_query($args)
                ));
                $response = curl_exec($rbsCurl);
                curl_close($rbsCurl);


                
                $response = json_decode($response, true);
                //var_dump($response);
                
                $orderStatus = $response['orderStatus'];
                if ($orderStatus == '1' || $orderStatus == '2') {
                   // print 'success';
                    $order_id = $_GET['order_id'];
                    //$order = new WC_Order($order_id);
                   // $order->update_status('processing', __('Платеж успешно оплачен', 'woocommerce'));


                    //$order->payment_complete();
                    //wp_redirect($this->get_return_url($order));
					//header('Location: https://yandex.ru/');
                    //exit;
                    return $order_id;
                } else {
                    //print 'error';
                    $order_id = $_GET['order_id'];
                   // $order = new WC_Order($order_id);
                   // $order->update_status('failed', __('Платеж не оплачен', 'woocommerce'));
                   // add_filter('woocommerce_add_to_cart_message', 'my_cart_messages', 99);
                   // $order->cancel_order();

                   // wc_add_notice(__('Ошибка в проведении оплаты<br/>' . $response['actionCodeDescription'], 'woocommerce'), 'error');
                    //header('Location: https://google.com/');
                    return false;
                }
            //}
        }
}