<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
	function getDivHeight () {
		var divh = document.getElementById('right').offsetHeight;
     	alert(divh +"px");
	}
	
	function strCount () {
		var str = document.getElementById('string').value;
		var count = str.length;
		alert(count)
	}
</script>
<style>
	#container {
		width: 675px;
		height: 525px;
		padding: 10px;
		border: 1px solid black;
	}
	#right {
		float: left;
		width: 48%;
		border: 1px solid black;
	}
	#copy {
		width: 48%;
		border: 1px solid black;
		float: right
	}
</style>
</head>

<body>
<input id="string" type="text" size="50">
<input name="count" type="button" value="String Count" onClick="strCount();"><br>
<input name="compute" type="button" value="Compute Div Height" onClick="getDivHeight();"><br>
<div id="container">
	<div id="right">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent blandit venenatis purus. Integer massa libero, vehicula id, consequat sed, tincidunt nec, purus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Suspendisse potenti. Nunc vulputate magna non magna. Aenean lorem eros, adipiscing quis, semper non, dictum a, nunc. Curabitur ut sem. Pellentesque a est id neque hendrerit ultrices. Donec vulputate tincidunt turpis. Curabitur dignissim vestibulum nunc. Aliquam felis lorem, ultrices sit amet, convallis a, accumsan vel, ante. Proin aliquam turpis sed augue. In pellentesque, magna a pulvinar adipiscing, est orci adipiscing felis, sed laoreet urna magna quis neque. Proin facilisis aliquet urna.</div>
	<div id="copy">
		<?php
			$str = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent blandit venenatis purus. Integer massa libero, vehicula id, consequat sed, tincidunt nec, purus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Suspendisse potenti. Nunc vulputate magna non magna. Aenean lorem eros, adipiscing quis, semper non, dictum a, nunc. Curabitur ut sem. Pellentesque a est id neque hendrerit ultrices. Donec vulputate tincidunt turpis. Curabitur dignissim vestibulum nunc. Aliquam felis lorem, ultrices sit amet, convallis a, accumsan vel, ante. Proin aliquam turpis sed augue. In pellentesque, magna a pulvinar adipiscing, est orci adipiscing felis, sed laoreet urna magna quis neque. Proin facilisis aliquet urna.";
			echo wordwrap($str,52, '<br>');
		?>
	</div>
</div>
</body>
</html>
