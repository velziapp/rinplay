<?php
	include("../sys-php/connection.php");
	include("../sys-php/funcoes.php");
	
	
	$acao   			 = $_POST['acao'];
	$texto  			 = $_POST['texto'];	
	
	$cx = conecta();
	$TBL = "rp_cadastros";
	
	if($acao == "enviar"){
		
		$destinatarios 	= trim(strip_tags($_POST['destinatarios']));
		$assunto	   	= trim(strip_tags($_POST['assunto']));
		$mensagem	   	= trim($_POST['texto']);
		$mensagem		= str_replace('/userfiles/image/', 'http://www.companhiapixel.com.br/userfiles/image/', $mensagem);
		
		if(!empty($assunto) && !empty($mensagem) && !empty($destinatarios)):
			// CONVERTE DESTINATÁRIOS EM ARRAY
			$string = $destinatarios;
			$arrayDes  = explode(',', $string);
		
			require_once('../../phpmailer/class.phpmailer.php');
			
			$mailer = new PHPMailer();
			$mailer->IsSMTP();
			$mailer->SMTPDebug = 1;
			$mailer->IsHTML(true);
			$mailer->Port = 587; //Indica a porta de conexão para a saída de e-mails
			$mailer->Host = 'smtp.companhiapixel.com.br';
			$mailer->SMTPAuth = true; //define se haverá ou não autenticação no SMTP
			$mailer->Username = 'leandro@companhiapixel.com.br'; //Informe o e-mai o completo
			$mailer->Password = 'l1e2a3n4'; //Senha da caixa postal
			$mailer->FromName = 'Rinplay - Novidades'; //Nome que será exibido para o destinatário
			$mailer->From = 'leandro@companhiapixel.com.br'; //Obrigatório ser a mesma caixa postal indicada em "username"
			$mailer->AddAddress("leandro@companhiapixel.com.br",'Rinaplay'); //Destinatários
			
			for($i = 0; $i < count($arrayDes); $i++){
				$mailer->AddBCC(trim($arrayDes[$i]),'');
			}
			
			$mailer->Subject = "$assunto";
			$mailer->Body = "$mensagem";
			
			if(!$mailer->Send()):	
				die("Erro ao tentar enviar e-mail. Mailer Error: " . $mailer->ErrorInfo);
			else:
				$ope = "Newsletter enviada com sucesso!";
			endif;
			
		else:
			$ope = "Preencha todos os campos para enviar!";
		endif;
	}
	
	// TABELA
	$SQL = "SELECT email FROM $TBL WHERE news = 'S'";
	$rs = mysqli_query( $cx, $SQL);		
?>
<html>
<head>
<title></title>
<script language="JavaScript" src="../sys-js/func.js"></script>
<script language="javascript" type="text/javascript" src="calendar.js"></script>
<?php if($ope != ""): ?>
<script type="text/javascript">
	alert('<?php echo $ope; ?>');
</script>
<?php endif;?>
<link href="../sys-css/config.css" rel="stylesheet" type="text/css">
</head>
<body class="form">
<div class="formulario">
<form method="post" action="index.php" name="frm_noticias" enctype="multipart/form-data">
<fieldset>
<legend>Mensagem</legend>
<table border="0">
	<tr>
    	<td width="1" class="texto_form">Assunto:</td>
        <td>
			<?php 
			$destinatarios = "";
			while($ln = mysqli_fetch_assoc($rs)){ 
				$destinatarios = $destinatarios . $ln['email'].", ";
			}
			$destinatarios = $destinatarios . "leandro@companhiapixel.com.br";
			?>
			<textarea name="destinatarios" id="destinatarios" cols="100" rows="4"><?php echo $destinatarios; ?></textarea>
        </td>
    </tr>
	<tr>
    	<td class="texto_form">Assunto:</td>
        <td><input name="assunto" type="text" class="inputs" id="assunto" maxlength="200"></td>
    </tr>
    <tr>
        <td colspan="2">
        	<?php 
			include_once("../../fckeditor/fckeditor.php") ;
			$oFCKeditor = new FCKeditor('FCKeditor1') ;
			$oFCKeditor->BasePath = "../../fckeditor/" ;
			$oFCKeditor->ToolbarSet = "Basic";
			$oFCKeditor->Config['SkinPath'] = 'skins/office2003/' ;
			$oFCKeditor->Value = "Digite o texto da mensagem...";
			$oFCKeditor->InstanceName = "texto";
			$oFCKeditor->Height = 300;
			$oFCKeditor->Width = 600;
			$oFCKeditor->Create();
			?>
        </td>
    </tr>
</table>
<input type="hidden" name="acao" value="enviar">
</fieldset>
<fieldset style="margin-top:5px;">
<center>
<input type="submit" value="Enviar" style="font-family:trebuchet,comic sans,arial; font-size:11px; color:#333333;">
<input type="button" value="Cancelar" onClick="javascript:window.close()" style="font-family:trebuchet,comic sans,arial; font-size:11px; color:#333333;">
</center>
</fieldset>
</form>
</div>
</body>
</html>