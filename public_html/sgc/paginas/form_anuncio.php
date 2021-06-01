<?php
	include("../sys-php/connection.php");
	include("../sys-php/funcoes.php");
	
	
	$acao   			 = $_POST['acao'];
	$texto  			 = $_POST['texto'];	
	
	$cx = conecta();
	$TBL = "rp_paginas";
	
	if($acao == "alterar"){
		$sql = "UPDATE $TBL SET anuncio = '$texto'"; 
		mysqli_query( $cx, $sql);
		echo "<script>alert('Registro alterado com sucesso!');</script>";
		echo"<script>opener.location.reload();</script>";
		echo "<script>window.close();</script>";

	}
	
	// TABELA
	$SQL = "SELECT * FROM $TBL";
	$rs = mysqli_query( $cx, $SQL);
	$ln = mysqli_fetch_assoc($rs);
		
?>
<html>
<head>
<title></title>
<script language="JavaScript" src="../sys-js/func.js"></script>
<script language="javascript" type="text/javascript" src="calendar.js"></script>
<link href="../sys-css/config.css" rel="stylesheet" type="text/css">
</head>
<body class="form">
<div class="formulario">
<form method="post" action="form_anuncio.php" name="frm_noticias" enctype="multipart/form-data">
<fieldset><legend>An&uacute;ncio</legend>
<table border="0">
    <tr>
        <td>
        	<?php 
			include_once("../../fckeditor/fckeditor.php") ;
			$oFCKeditor = new FCKeditor('FCKeditor1') ;
			$oFCKeditor->BasePath = "../../fckeditor/" ;
			$oFCKeditor->ToolbarSet = "Basic";
			$oFCKeditor->Config['SkinPath'] = 'skins/office2003/' ;
			$oFCKeditor->Value = $ln["anuncio"];
			$oFCKeditor->InstanceName = "texto";
			$oFCKeditor->Height = 300;
			$oFCKeditor->Width = 600;
			$oFCKeditor->Create();
			?>
        </td>
    </tr>
</table>
<input type="hidden" name="acao" value="alterar">
</fieldset>
<fieldset style="margin-top:5px;">
<center>
<input type="submit" value="Alterar" style="font-family:trebuchet,comic sans,arial; font-size:11px; color:#333333;">
<input type="reset" value="Limpar" style="font-family:trebuchet,comic sans,arial; font-size:11px; color:#333333;">
<input type="button" value="Cancelar" onClick="javascript:window.close()" style="font-family:trebuchet,comic sans,arial; font-size:11px; color:#333333;">
</center>
</fieldset>
</form>
</div>
</body>
</html>