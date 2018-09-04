<?
function __autoload($class_name)
{
	if(@include_once("../classes/" . $class_name . ".class.php")) return;
}

include '../Classes/mpdf60/mpdf.php';


	
	

	make_pdf('contract'.time().'.scan.pdf', $content, $order_count, true);


	
function make_pdf($pdf_file, $content, $order_count=1, $stamp=true){
	$mpdf=new mPDF('');
    $mpdf->SetImportUse();
    
    // forces no subsetting - otherwise the inserted characters may not be contained 
    // in a subset font
    $mpdf->percentSubset = 0;
    
    $search = array(
    	'3'
    
    );
    
    $replacement = array(
    	"personalised"
    );

$mpdf->OverWrite('test3.pdf', $search, $replacement, 'I', 'mpdf.pdf' ) ;
}

