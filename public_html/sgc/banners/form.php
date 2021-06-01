<?php
	include("../sys-php/connection.php");
	include("../sys-php/funcoes.php");
	
	
	$acao   			 = $_POST['acao'];
	$codigo   			 = $_REQUEST['codigo'];
	$titulo 			 = $_POST['titulo'];
	$link	 			 = $_POST['link'];
	$data_inicial	     = $_POST['data_inicial'];	
	$data_final		     = $_POST['data_final'];	
	$arquivo		 	 = $_FILES['arquivo']['tmp_name'];	
	$nome_arquivo		 = $_FILES["arquivo"]["name"];
	
	
	$cx = conecta();
	
	$TBL = "rp_banners";
	
	
	if($acao == "inserir"){
	
		$time = date("d-m-Y-H-i-s");
		
		// FAZENDO UPLOAD DO ARQUIVO
		$nome_arquivo = $time . $nome_arquivo;
		copy($arquivo, "../uploads/" . $nome_arquivo);	
		
		$sql = "INSERT INTO ".$TBL."(titulo, data_inicial, data_final, link, arquivo) VALUES ('" . $titulo . "','" . $data_inicial . "','" . $data_final ."','" . $link ."','" . $nome_arquivo ."')";
		mysqli_query( $cx, $sql);
		echo "<script>alert('Registro inserido com sucesso!');</script>";
		echo"<script>opener.location.reload();</script>";
		echo "<script>window.close();</script>";

	}
	
	if($acao == "alterar"){
		$thumb_old = $_POST['thumb_old'];
		$arquivo_old = $_POST['arquivo_old'];
	
		$time = date("d-m-Y-H-i-s");
		
		// FAZENDO UPLOAD DO ARQUIVO
		if($arquivo == ""){
			$nome_arquivo = $arquivo_old;
		}
		else{
			$nome_arquivo = $time . $nome_arquivo;
			copy($arquivo, "../uploads/" . $nome_arquivo);	
		}	
		
		$sql = "UPDATE ".$TBL." SET titulo = '".$titulo."', data_inicial = '".$data_inicial."', data_final = '".$data_final."', arquivo = '".$nome_arquivo."', link = '".$link."' WHERE codigo = ".$codigo; 
		mysqli_query( $cx, $sql);
		echo "<script>alert('Registro alterado com sucesso!');</script>";
		echo"<script>opener.location.reload();</script>";
		echo "<script>window.close();</script>";

	}
	
		
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
<fieldset><legend>Banners</legend><br><br>
<table border="0">
<tr>
	<td><font class="texto_form">Titulo:</font></td>
    <td colspan="3"> <input type="text" name="titulo" class="inputs" value="<?php echo $ln['titulo']; ?>" style="height:20px;" onFocus="this.className='input_focus'" onBlur="this.className='inputs'"></td>
</tr>
<tr>
	<td><font class="texto_form">Link:</font></td>
    <td colspan="3"> <input type="text" name="link" class="inputs" value="<?php echo $ln['link']; ?>" style="height:20px;" onFocus="this.className='input_focus'" onBlur="this.className='inputs'"></td>
</tr>
<tr>
	<td><font class="texto_form">Imagem:</font></td>
    <td colspan="3"> <input type="file" class="inputs" name="arquivo" onFocus="this.className='input_focus'" onBlur="this.className='inputs'"><input type="hidden" name="arquivo_old" value="<?php echo $ln['arquivo']; ?>"></td>
</tr>
<tr>
	<td><font class="texto_form">Data Inicial:</font></td>
    <td><input type="text"  class="inputs_data" value="<?php echo ($codigo > 0 ? $ln['data_inicial'] : date("d/m/Y"));?>" name="data_inicial" onFocus="this.select();lcs(this)" onClick="event.cancelBubble=true;this.select();lcs(this)"></td>
</tr>
<tr>
	<td><font class="texto_form">Data Final:</font></td>
    <td><input type="text"  class="inputs_data" value="<?php echo ($codigo > 0 ? $ln['data_final'] : "31/12/2999");?>" name="data_final" onFocus="this.select();lcs(this)" onClick="event.cancelBubble=true;this.select();lcs(this)"></td>
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