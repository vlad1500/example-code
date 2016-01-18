<?php

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;

$todate=date('j-M-Y');
$fromday = mktime(0,0,0,date("m"),date("d")-2,date("Y"));
$fromdate=date('j-M-Y', $fromday);

echo "Today, the date is ".$todate."<br />";
echo "Day before  yesterday, the date was ".$fromdate."<br />";
echo "The following vouchers are entered between ".$fromdate." and ".$todate."<br />";
echo "<p>&nbsp;</p>";

$xml_data1 ='<ENVELOPE><HEADER><TALLYREQUEST>Export Data</TALLYREQUEST></HEADER><BODY><EXPORTDATA><REQUESTDESC><REPORTNAME>Voucher Register</REPORTNAME><STATICVARIABLES>
<SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>';
$xml_data2 ='<SVFROMDATE>'.$fromdate.'</SVFROMDATE>';
$xml_data3 ='<SVTODATE>'.$todate.'</SVTODATE>';
//$xml_data2 ='<SVFROMDATE>1-Apr-2012</SVFROMDATE>';
//$xml_data3 ='<SVTODATE>7-Apr-2012</SVTODATE>';
$xml_data4 ='</STATICVARIABLES></REQUESTDESC></EXPORTDATA></BODY></ENVELOPE>';
$xml_data = $xml_data1.$xml_data2.$xml_data3.$xml_data4;
		
$URL = "http://localhost:5200"; //Tally PORT ID
$ch = curl_init ($URL);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Content-Type: text/xml'));
curl_setopt ($ch, CURLOPT_POSTFIELDS, "$xml_data");
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec ($ch);

echo $output."<p>&nbsp;</p>";
	
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime); 

echo "This report was generated in ".$totaltime." seconds"; 

?>
