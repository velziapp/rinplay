<?
	include("sys-php/connection.php");
	ob_start();
	session_start();
	if(($_SESSION['Id'] == "") || ($_SESSION['Id'] == 0)){
	header("location: index.php");	
	}
?>
<html>
<head>
<title></title>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php include('menu.php'); ?>

</body>
</html>