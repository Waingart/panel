<?
function __autoload($class_name)
{
	if(@include_once("../classes/" . $class_name . ".class.php")) return;
}

include '../Classes/mpdf60/mpdf.php';


	$content = file_get_contents('contract_tpl.htm'); //good_inv.htm
	

	make_pdf('test.pdf', $content, $order_count, true);


	
function make_pdf($pdf_file, $content, $order_count=1, $stamp=true){
	$mpdf=new mPDF('utf-8', 'A4', '10', 'Arial');
    	$mpdf->setAutoTopMargin='stretch';
    	$mpdf->autoMarginPadding= 5;
    	$mpdf->setAutoBottomMargin= 'pad';
	$mpdf->SetHTMLHeader('
<table width="100%" height="300">
    <tr>
        <td width="50%"><img src="logo.png" width="200" /></td>
        <td width="50%" style="text-align: right;"><p class="header">г. Калининград, ул. Геологическая 1, офис 401<br>
ООО «Абеляр Медиа»<br>
т. (4012) 507‐277</p></td>
    </tr>
</table><hr>');	
	
	$mpdf->SetHTMLFooter('{PAGENO}');
	
	$stamp_margin = 0;
	//$mpdf->SetWatermarkImage('tpl/pdf/IMG_1574.png', 1, array(50,52), array(130,115+$stamp_margin)); // invoice

	$mpdf->showWatermarkImage = true;
	
	$mpdf->WriteHTML($content);
	$mpdf->Output($pdf_file,"F");
}

