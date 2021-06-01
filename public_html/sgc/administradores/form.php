<?php
include("../sys-php/connection.php");
include("../sys-php/funcoes.php");


$cx		= conecta();
$acao 	= $_POST['acao'];
$cNome 	= $_POST['cNome'];
$cLogin = $_POST['cLogin'];
$cSenha	= $_POST['cSenha'];
$cEmail	= $_POST['cEmail'];
// Tabela do Banco
	$TBL   = "rp_admin"; 



if( $acao == "incluir"){		
	// Inclui o admin //		
	$SQL = "INSERT INTO ".$TBL."( nome, login, senha, email)"; 
	$SQL .=	"VALUES( '$cNome', '$cLogin', '$cSenha', '$cEmail' )";
	mysqli_query( $cx ,  $SQL);

	// Informa o usuário //
	msgbox( "Registro cadastrado com sucesso!\\n$obs", "index.php" );
	echo "<script>if( !confirm( 'Deseja adicionar outro registro?' ) ) this.close();<". "/" ."script>";
}
?>
<html>
<head>
<title>DigiNews | <?php echo $title_admin; ?></title>
<script language="javascript" src="../../tools/epoch_v201_en/epoch_classes.js"></script>
<script language="JavaScript">
	function iniciaCalendarios(){
		new Epoch( "ep_popup", "popup", document.getElementById( "cData" ) );
		new Epoch( "ep_popup", "popup", document.getElementById( "cDataInicio" ) );
		new Epoch( "ep_popup", "popup", document.getElementById( "cDataTermino" ) );
	};

	function ValidaForm(){
		if(document.frm.cNome.value == "")
	 		{
		 		alert("Campo Obrigatorio !");
		 		document.frm.cNome.style.background = "#fc5a5a";
		 		document.frm.cNome.focus();
	 		}
		else
	 		{
				document.frm.cNome.style.background = "#FFFFFF";
				return true;
	 		} 
		
	}	
	
</script>
<link rel="stylesheet" type="text/css" href="../../tools/epoch_v201_en/epoch_styles.css" />
<link href="../sys-css/config.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#F1F1F1" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="border-style:none;" onLoad="this.focus(); iniciaCalendarios();">
<fieldset style="width:100%; height:100%;">
<legend class="txtFrame">Cadastro de Administradores</legend>
	<table width="200" border="0" cellpadding="1" cellspacing="0" align="center">
		<form name="frm" action="<?php echo $PHP_SELF; ?>" method="post" enctype="multipart/form-data">
		<tr>
			<td>
			
				<table width="100%" cellpadding="0" cellspacing="0" class="txtLabel">
					<tr>
						<td class="txtLabel" width="70%">Nome:</td>
					</tr>
					<tr>
						<td><input name="cNome" type="text" class="txtCampos" id="cNome" style="width:460px;" tabindex="1" maxlength="150"></td>
					</tr>	
				</table>	
                
                <table width="100%" cellpadding="0" cellspacing="0" class="txtLabel">
					<tr>
						<td class="txtLabel" width="70%">Login:</td>
					</tr>
					<tr>
						<td><input name="cLogin" type="text" class="txtCampos" id="cLogin" style="width:460px;" tabindex="1" maxlength="150"></td>
					</tr>	
				</table>	
                
                <table width="100%" cellpadding="0" cellspacing="0" class="txtLabel">
					<tr>
						<td class="txtLabel" width="70%">Senha:</td>
					</tr>
					<tr>
						<td><input name="cSenha" type="text" class="txtCampos" id="cSenha" style="width:460px;" tabindex="1" maxlength="150"></td>
					</tr>	
				</table>	
                
                <table width="100%" cellpadding="0" cellspacing="0" class="txtLabel">
					<tr>
						<td class="txtLabel" width="70%">Email:</td>
					</tr>
					<tr>
						<td><input name="cEmail" type="text" class="txtCampos" id="cEmail" style="width:460px;" tabindex="1" maxlength="150"></td>
					</tr>	
				</table>		
				
			</td>
		</tr>	
		
		<tr>
			<td align="right" colspan="2">				
				<input type="reset" name="cReset" class="btnCancelar" value="Cancelar">
				<input type="button" value="Incluir" class="btnCadastrar" onClick="if( ValidaForm() ) submit();">
                <input type="button" value="Fechar" onClick="javascript:window.close()" class="btnCancelar">
				<input type="hidden" name="acao" value="incluir">
			</td>
		</tr>
		</form>
	</table><?php

((is_null($___mysqli_res = mysqli_close( $cx ))) ? false : $___mysqli_res);

?>
</fieldset>
</body>
</html>