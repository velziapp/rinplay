<?php
	include("../sys-php/connection.php");
	include("../sys-php/funcoes.php");
	
	
	$acao   	= $_POST['acao'];
	$descricao 	= $_POST['descricao'];
	$cx 		= conecta();
	
	$TBL = "rp_comunidades_ctg";
	
	
	if($acao == "inserir"){
	
		$sql = "INSERT INTO ".$TBL."(descricao) VALUES ('" . $descricao . "')";
		mysqli_query( $cx, $sql);
		echo "<script>alert('Registro inserido com sucesso!');</script>";
		echo"<script>opener.location.reload();</script>";
		echo "<script>window.close();</script>";

	}
	
	/*
	if($acao == "alterar"){
			
		$sql = "UPDATE ".$TBL." SET titulo = '".$titulo."', data = '".$data."', texto = '".$texto."', arquivo = '".$nome_arquivo."', thumb = '".$nome_thumb."' WHERE codigo = ".$codigo; 
		mysql_query($sql, $cx);
		echo "<script>alert('Registro alterado com sucesso!');</script>";
		echo"<script>opener.location.reload();</script>";
		echo "<script>window.close();</script>";

	}
	*/
		
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
<?php 
	if($codigo > 0){
		$SQL = "SELECT * FROM ".$TBL." WHERE codigo =".$codigo;
		$rs = mysqli_query( $cx, $SQL);
		$ln = mysqli_fetch_assoc($rs);
	}
?>
<form method="post" action="form.php?codigo=<?php echo $codigo; ?>" name="frm_noticias" enctype="multipart/form-data">
<fieldset><legend>Comunidades > Categorias</legend><br><br>
<table border="0">
    <tr>
        <td><font class="texto_form">Descri&ccedil;&atilde;o:</font></td>
        <td colspan="3"> <input type="text" name="descricao" class="inputs" value="<?php echo $ln['descricao']; ?>" style="height:20px;" onFocus="this.className='input_focus'" onBlur="this.className='inputs'"></td>
    </tr>
</table>
<input type="hidden" name="acao" value="<?php echo ($codigo > 0 ? "alterar" : "inserir");?>">
</fieldset>
<fieldset style="margin-top:5px;">
<center>
<input type="submit" value="<?php echo ($codigo > 0 ? "Alterar" : "Inserir");?>" style="font-family:trebuchet,comic sans,arial; font-size:11px; color:#333333;">
<input type="reset" value="Limpar" style="font-family:trebuchet,comic sans,arial; font-size:11px; color:#333333;">
<input type="button" value="Cancelar" onClick="javascript:window.close()" style="font-family:trebuchet,comic sans,arial; font-size:11px; color:#333333;">
</center>
</fieldset>
</form>
</div>
</body>
</html>