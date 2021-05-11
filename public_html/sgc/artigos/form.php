<?php
	include("../sys-php/connection.php");
	include("../sys-php/funcoes.php");
	
	
	$acao   			 = $_POST['acao'];
	$codigo   			 = $_REQUEST['codigo'];
	$titulo 			 = $_POST['titulo'];
	$texto  			 = $_POST['texto'];
	$data   		     = $_POST['data'];	
	$arquivo		 	 = $_FILES['arquivo']['tmp_name'];	
	$nome_arquivo		 = $_FILES["arquivo"]["name"];
	$thumb			 	 = $_FILES['thumb']['tmp_name'];	
	$nome_thumb			 = $_FILES["thumb"]["name"];
	
	$cx = conecta();
	
	$TBL = "rp_artigos";
	
	
	if($acao == "inserir"){
	
		$time = date("d-m-Y-H-i-s");
		
		// FAZENDO UPLOAD DO ARQUIVO
		$nome_arquivo = $time . $nome_arquivo;
		copy($arquivo, "../uploads/" . $nome_arquivo);	
		
		// FAZENDO UPLOAD DA THUMB
		$nome_thumb = $time . $nome_thumb;
		copy($thumb, "../uploads/" . $nome_thumb);	
		
		$sql = "INSERT INTO ".$TBL."(titulo, data, texto, arquivo, thumb) VALUES ('" . $titulo . "','" . $data . "','" . $texto ."','" . $nome_arquivo ."','" . $nome_thumb ."')";
		mysql_query($sql, $cx);
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
		
		// FAZENDO UPLOAD DA THUMB
		if($thumb == ""){
			$nome_thumb = $thumb_old;
		}
		else{	
			$nome_thumb = $time . $nome_thumb;
			copy($thumb, "../uploads/" . $nome_thumb);	
		}	
		
		$sql = "UPDATE ".$TBL." SET titulo = '".$titulo."', data = '".$data."', texto = '".$texto."', arquivo = '".$nome_arquivo."', thumb = '".$nome_thumb."' WHERE codigo = ".$codigo; 
		mysql_query($sql, $cx);
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
		$rs = mysql_query($SQL, $cx);
		$ln = mysql_fetch_assoc($rs);
	}
?>
<form method="post" action="form.php?codigo=<?php echo $codigo; ?>" name="frm_noticias" enctype="multipart/form-data">
<fieldset><legend>Artigos</legend><br><br>
<table border="0">
<tr>
	<td><font class="texto_form">Titulo:</font></td>
    <td colspan="3"> <input type="text" name="titulo" class="inputs" value="<?php echo $ln['titulo']; ?>" style="height:20px;" onFocus="this.className='input_focus'" onBlur="this.className='inputs'"></td>
</tr>
<tr>
	<td><font class="texto_form">Arquivo:</font></td>
    <td colspan="3"> <input type="file" class="inputs" name="arquivo" onFocus="this.className='input_focus'" onBlur="this.className='inputs'"><input type="hidden" name="arquivo_old" value="<?php echo $ln['arquivo']; ?>"></td>
</tr>
<tr>
	<td><font class="texto_form">Thumb:</font></td>
    <td colspan="3"> <input type="file" class="inputs" name="thumb" onFocus="this.className='input_focus'" onBlur="this.className='inputs'"><input type="hidden" name="thumb_old" value="<?php echo $ln['thumb']; ?>"></td>
</tr>
<tr>
	<td><font class="texto_form">Data:</font></td>
    <td><input type="text"  class="inputs_data" value="<?php echo ($codigo > 0 ? $ln['data'] : date("d/m/Y"));?>" name="data" onFocus="this.select();lcs(this)" onClick="event.cancelBubble=true;this.select();lcs(this)"></td>
</tr>
<tr>
	<td>
    	<font class="texto_form">Texto:</font></td> 
        <td colspan="3"><textarea name="texto" cols="50" rows="5"><?php echo $ln['texto']; ?></textarea></td>
	</td>
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