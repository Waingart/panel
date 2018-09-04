<?php ini_set('display_errors', 1); ?> 
<?
$datefrom = new DateTime('-4 days');
$date = new DateTime();

$from = $datefrom->format('Y-m-d').'T'.$datefrom->format('H:m:i') ;
$to = $date->format('Y-m-d').'T'.$date->format('H:m:i') ;

print 'from: '.$from.' to: '.$to;
