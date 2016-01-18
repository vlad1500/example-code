<?php

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;

$xml_data ='<ENVELOPE>
<HEADER>
<VERSION>1</VERSION>
<TALLYREQUEST>Export</TALLYREQUEST>
<TYPE>Data</TYPE>
<ID>Simple Trial balance</ID>
</HEADER>
<BODY>
<DESC>
<STATICVARIABLES>
<EXPLODEFLAG>Yes</EXPLODEFLAG>
<SVEXPORTFORMAT>$$SysName:XML</SVEXPORTFORMAT>
</STATICVARIABLES>
<TDL>
<TDLMESSAGE>
<REPORT NAME="Simple Trial balance">
<FORMS>Simple Trial balance</FORMS>
<TITLE>"Trial Balance"</TITLE>
</REPORT>
<FORM NAME="Simple Trial balance">
<TOPPARTS>Simple TB Part</TOPPARTS>
<HEIGHT>100% Page</HEIGHT>
<WIDTH>100% Page</WIDTH>
</FORM>
<PART NAME="Simple TB Part">
<TOPLINES>Simple TB Title,
Simple TB Details</TOPLINES>
<REPEAT>
Simple TB Details : Simple TB Ledgers
</REPEAT>
<SCROLLED>Vertical</SCROLLED>
<COMMONBORDERS>Yes</COMMONBORDERS>
</PART>
<LINE NAME="Simple TB Title">
<USE>Simple TB Details</USE>
<LOCAL>
Field : Default : Type : String
</LOCAL>
<LOCAL>
Field : Default : Align : Centre
</LOCAL>
<LOCAL>
Field : Simple TB Name Field : Set as: "Particulars"
</LOCAL>
<LOCAL>
Field : Simple TB Amount Field: Set as: "Amount"
</LOCAL>
<BORDER>Flush Totals</BORDER>
</LINE>
<LINE NAME="Simple TB Details">
<FIELDS>Simple TB Name Field</FIELDS>
<FIELDS>Simple TB Parent Field</FIELDS>
<FIELDS>Simple TB PrimaryGroup Field</FIELDS>
<FIELDS>Simple TB Created Field</FIELDS>
<RIGHTFIELDS>Simple TB Amount Field</RIGHTFIELDS>
</LINE>
<FIELD NAME="Simple TB Name Field">
<USE>Name Field</USE>
<SET>$Name</SET>
</FIELD>
<FIELD NAME="Simple TB Parent Field">
<USE>Name Field</USE>
<SET>$Parent</SET>
</FIELD>
<FIELD NAME="Simple TB PrimaryGroup Field">
<USE>Name Field</USE>
<SET>$_PrimaryGroup</SET>
</FIELD>
<FIELD NAME="Simple TB Created Field">
<USE>Date Field</USE>
<SET>$AlteredOn</SET>
</FIELD>
<FIELD NAME="Simple TB Amount Field">
<USE>Amount Field</USE>
<SET>$ClosingBalance</SET>
<BORDER>Thin Left</BORDER>
</FIELD>
<COLLECTION NAME="Simple TB Ledgers">
<TYPE>Ledger</TYPE>
<FILTERS>NoProfitsimple</FILTERS>
</COLLECTION>
<SYSTEM TYPE="Formulae" NAME="NoProfitSimple">
NOT $$IsLedgerProfit
</SYSTEM>
</TDLMESSAGE>
</TDL>
</DESC>
</BODY>
</ENVELOPE>';
		
		
$URL = "http://192.168.1.2:9000"; //Tally PORT ID
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
