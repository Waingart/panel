<?

include 'Classes/mpdf60/mpdf.php';
function arr_replace($arr, $tpl){
		foreach ($arr as $tag => $value){
			$tpl = str_replace($tag, $value, $tpl);
		}
		return $tpl;
	}
function gen_pdf_inv($array, $pdf_file, $doctype='inv'){
	if($doctype=='inv'){
		$tpl = file_get_contents('tpl/pdf/inv_tpl.html'); //good_inv.htm
		$order_tpl = file_get_contents('tpl/pdf/inv_orderlist_tpl.html');
	}else{
		// -----------------
		$tpl = file_get_contents('tpl/pdf/act_tpl.html'); //good_inv.htm
		$order_tpl = file_get_contents('tpl/pdf/act_orderlist_tpl.html');
		//------------------
	}
	
	
	$arr = array();
	$arr['{doc_date_num}'] = "СЧЕТ № ".$array['invoice_number'].' от '.$array['invoice_date'];
	//---------
	$arr['{doc_num}'] = $array['act_number'];
	$arr['{doc_date}'] = $array['invoice_date'];
	//---------
	$arr['{client_name}'] = $array['customer'];
	$arr['{bill_name}'] = '';
	$arr['{full_total}'] = $array['total'];
	$arr['{text_total}'] = $array['text_total'];
	
	$tpl = arr_replace($arr, $tpl);
	$arr = array();
	
	$line_number = 1;
	foreach ($array['order_list'] as $order){
		$arr['{line_number}'] = $line_number;
		$arr['{title}'] = $order['title'];
		$arr['{ed}'] = $order['ed'];
		$arr['{count}'] = $order['count'];
		$arr['{price}'] = $order['price'];
		$arr['{linetotal}'] = $order['total'];

		$order_lines .= arr_replace($arr, $order_tpl);
		
		$line_number++;
	}

	$content = arr_replace(array('{order_lines}' =>  $order_lines), $tpl);

	$order_count = count($array['order_list']);
	//print $order_count;
	//var_dump($array['order_list']);
	make_pdf($doctype, $pdf_file.'.scan.pdf', $content, $order_count, true);
	make_pdf($doctype, $pdf_file, $content, $order_count, false);

	
}
function make_pdf($doctype, $pdf_file, $content, $order_count=1, $stamp=true){
	$mpdf=new mPDF('utf-8', 'A4');
	if($stamp){
		
		if($doctype=='inv'){
			
			if($order_count>1){
				$stamp_margin = $order_count*1.5*3;
			}
			$mpdf->SetWatermarkImage('tpl/pdf/IMG_1574.png', 1, array(50,52), array(130,115+$stamp_margin)); // invoice
			$stamp_margin = 0;
		} else{
			$stamp_margin = 0;
			if($order_count>1){
				$stamp_margin = $order_count*1.7*3;
			}
			$mpdf->SetWatermarkImage('tpl/pdf/IMG_1574.png', 1, array(50,52), array(30,100+$stamp_margin)); // act
			$stamp_margin = 0;
		}
		$mpdf->showWatermarkImage = true;
	}
	$mpdf->WriteHTML($content);
	$mpdf->Output($pdf_file,"F");
}

