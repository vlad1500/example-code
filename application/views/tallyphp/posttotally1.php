<?php

$VoucherTypeName = $_POST['VoucherTypeName']; 
$Date = $_POST['Date']; 
$Ledger1 = $_POST['Ledger1']; 
$Amount1 = $_POST['Amount1']; 
$Ledger2 = $_POST['Ledger2']; 
$Amount2 = $_POST['Amount2']; 
$Narration = $_POST['Narration']; 

$xml_data = '<ENVELOPE><HEADER><VERSION>1</VERSION><TALLYREQUEST>Import</TALLYREQUEST><TYPE>Data</TYPE><ID>Vouchers</ID></HEADER><BODY><DESC><STATICVARIABLES><SVCURRENTCOMPANY>Test Company 123</SVCURRENTCOMPANY></STATICVARIABLES></DESC><DATA><TALLYMESSAGE><VOUCHER ACTION="Create"><DATE>'.$Date.'</DATE><NARRATION>'.$Narration.'</NARRATION><VOUCHERTYPENAME>'.$VoucherTypeName.'</VOUCHERTYPENAME><VOUCHERNUMBER> </VOUCHERNUMBER>
<ALLLEDGERENTRIES.LIST><LEDGERNAME>'.$Ledger2.'</LEDGERNAME><ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE><AMOUNT>-'.$Amount2.'</AMOUNT></ALLLEDGERENTRIES.LIST>
<ALLLEDGERENTRIES.LIST><LEDGERNAME>'.$Ledger1.'</LEDGERNAME><ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE><AMOUNT>'.$Amount1.'</AMOUNT></ALLLEDGERENTRIES.LIST>
</VOUCHER></TALLYMESSAGE></DATA></BODY></ENVELOPE>';
			
$URL = "http://localhost:5200";
$ch = curl_init ($URL);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Content-Type: text/xml'));
curl_setopt ($ch, CURLOPT_POSTFIELDS, "$xml_data");
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec ($ch);
echo "<a href='javascript:history.go(-1)'>Click here to go to form</a>";
?>