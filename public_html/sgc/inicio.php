<?php
	include("sys-php/connection.php");
	ob_start();
	session_start();
	if(($_SESSION['Id'] == "") || ($_SESSION['Id'] == 0)){
	header("Location: index.php");	
	}
?>
<html>
<head>
<title></title>
</head>
<frameset rows="25, *"  framespacing="0" frameborder="no" border="0">
<frame name="topFrame" scrolling="no" noresize marginwidth="0" marginheight="0" src="topo.php" frameborder="0">
<frame name="meio" scrolling="auto" noresize marginwidth="0" marginheight="0" src="bem_vindo.php">
<noframes>
<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000">
<p><b>Seu browser não suporta frames!</b></p>
</body>
</noframes>
</frameset>
</html>