<?php
require_once('../phpmailer/class.phpmailer.php');

if(isset($_POST['submit']))
{
$tomail=$_POST['to_email'];
$frommail=$_POST['from_email'];

$mail = new PHPMailer();


$mail->SetFrom('$frommail', '');

$mail->AddReplyTo("$frommail","");

$address = "$tomail";
$mail->AddAddress($address, "");



$mail->AddReplyTo("$tomail","");

$body=$_POST['body'];
$mail->MsgHTML($body);

$mail->From= "$frommail";
$mail->Subject    = "Request To view the book ";

if(!$mail->Send()) 
{

echo "Mailer Error: " . $mail->ErrorInfo;

} 
else 
{

echo "Message sent!";
 echo '<script language="JavaScript" type="text/javascript">


this.close();

</script>';

}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>mail me</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form name="" method="post" action="#">
<table border="0">
<tr>
<td>To</td>
<td><input type="text" value="" name="to_email" /></td>
</tr>
<tr>
<td>From</td>
<td><input type="text" value="" name="from_email" /></td>
</tr>
<tr>
<td align="left" valign="top">Message</td>
<td><textarea cols="20" rows="10" name="body">Hi, 
I have published a book. Please check : 
<?php echo $_GET['url']; ?></textarea></td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="submit" value=""  class="mailme"/></td>
</tr>
</table>
</form>


</body>
</html>