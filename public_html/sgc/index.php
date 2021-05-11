<?php 
	ob_start();
	session_start();
	include("sys-php/connection.php");
	include("sys-php/funcoes.php");
	
	$login = limpaEntrada( $_POST["login"] );
	$senha = limpaEntrada( $_POST["senha"] );
	$acao  = limpaEntrada( $_POST["actID"] );
	$cx    = conecta();
	// Tabela do Banco
	$TBL   = "rp_admin"; 
	 
	 
	 // LOGAR NO ADMIN //
	if($acao == "logar"){		
		if (($login == "") || ($senha == ""))
		{
		  $msg = "<font color='red'>Usuario e/ou Senha n&atilde;o preenchidos</font>";
		} 
		else
		{
		$query = "SELECT codigo, login, senha FROM ".$TBL." WHERE login ='" . $login ."' AND senha ='" . $senha ."'";
		
		$ret=mysql_query($query , $cx);
			if(mysql_num_rows($ret) > 0){
				while($item=mysql_fetch_array($ret)){
					if (($login == $item[1]) && ($senha == $item[2]))
					{
					$_SESSION['Id'] = $item[0];
					header("location: inicio.php");		
					}
				}
			}
			else
			{
			$msg = "<font color='red'>Voc&ecirc; n&atilde;o est&aacute; cadastrado</font>";
			}	
		}
	}
	
	
	// ALTERAR SENHA //
if( $acao == "alterar" ){
	
	$novasenha = limpaEntrada( $_POST["novasenha"] );
	$confirmesenha = limpaEntrada( $_POST["confirmesenha"] );
	
	if( $login == "" || $senha == "" || $novasenha == "" || $confirmesenha == "" ){
		$msg = "Dados incompletos - Tente novamente";
	}
	if( $novasenha != $confirmesenha ){
		$msg = "Nova senha e sua confirmação n&atilde;o conferem - Tente novamente";
	}
	else{
		
		$rsLogin = mysql_query( "SELECT codigo, login, senha FROM ".$TBL." WHERE login = '$login' AND senha = '$senha'", $cx );
		if( mysql_num_rows( $rsLogin ) == 0 ){
			$msg = "Login e/ou Senha inv&aacute;lidos - Tente novamente";
		} else{
			$rst_login = mysql_fetch_assoc( $rsLogin );
			if( $login == $rst_login["login"] ){				
				$sql = "UPDATE ".$TBL." SET senha = '" . $novasenha . "' WHERE codigo = " . $rst_login["codigo"];
				mysql_query( $sql, $cx );				
				$msg = "Senha alterada com sucesso";				
			}
		}
		
	}	
	
}

// ESQUECI SENHA //
if( $acao == "lembrar" ){

	if( $login == ""  ){
		$msg = "Login inv&aacute;lido - Tente novamente";
	}
	else{
		
		$rsLogin = mysql_query( "SELECT codigo, login, nome, email FROM ".$TBL." WHERE login = '$login'", $cx );
		if( mysql_num_rows( $rsLogin ) == 0 ){
			$msg = "Login inv&aacute;lido - Tente novamente";
		} else{
			$rst_login = mysql_fetch_assoc( $rsLogin );
			if( $login == $rst_login["login"] ){
				
				$arrSenhaKeys = array( "1", "2", "3", "4", "5", "6", "7", "8", "9", "H", "X", "Z", "G", "B", "U", "E", "A", "T", "K", "J" );
				$novasenha = "";
				for( $x = 0; $x <= 7; $x++ ){
					$novasenha .= $arrSenhaKeys[rand( 0, 19 )];
				}
				
				$assunto = "Recuperação de senha: Administrador<br><br>";
				
				/* Montando o cabeçalho da mensagem */
				$headers = "MIME-Version: 1.1 \n";
				$headers .= "Content-type: text/html; charset=utf-8 \n";
				$headers .= "From: helder@ciapixel.com.br\r\n"; // remetente
				$headers .= "Cc: ".$rst_login["email"]."\r\n"; // remetente
				$headers .= "Return-Path: helder@ciapixel.com.br\r\n"; // return-path
				
				$corpo = "<b>" . $assunto . "</b>";
				$corpo.= "Caro " . $rst_login["nome"] . ",<br>";
				$corpo.= "Conforme sua solicitação, uma nova senha de acesso ao sistema de administração foi gerada:<br>";
				$corpo.= "<b>" . $novasenha . "</b><br><br>";
				$corpo.= "<i>[Esta é uma mensagem automática e não deve ser respondida]</i>";				
				
				
				if( mail( $rst_login["email"], $assunto, $corpo, $headers ) ){
					$sql = "UPDATE ".$TBL." SET senha = '" . $novasenha . "' WHERE codigo = " . $rst_login["codigo"];
					mysql_query( $sql, $cx );
					$msg = "Uma nova senha foi gerada e enviada para o seu email";
				} else{
					$msg = "Ocorreu um erro durante o envio do email. Tente novamente mais tarde ou entre em contato com a CiaPixel";
				}
	
			}
		}
		mysql_free_result( $rsLogin );		
	}
	
}

desconecta( $cx );
	?>
<html>  
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="sys-css/config.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="sys-js/admin.login.js"></script>
</head>
<body bgcolor="#FFFFFF" background="sys-img/fundo_form.gif" onLoad="document.getElementById('login').focus();">
	<center>
	<table border="0" style="border:1px #999999 solid; background-color:#FFFFFF; margin-top:100px; width:450px;">
	<form name="frmlogin" id="frmLogin" action="index.php" method="post">
		<tr>
			<td colspan="3" style="padding-top:10px; padding-bottom:10px; background-color:#E9E9E9" class="txtLegendaLogin" align="center">
				<b>
				<?php
					if( isset( $msg ) ) echo "<span style='color:#990000'>" . $msg . "</span>";
					else echo "<span style='color:#006699'>Digite o seu usu&aacute;rio e senha</span>";
				?>
				</b>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td width="150" align="right" class="txtLegendaLogin">Login</td>
			<td width="110" colspan="2"><input type="text" name="login" id="login" size="20" class="txtCampos"></td>
		</tr>
		<tr>
			<td align="right" class="txtLegendaLogin">Senha</td>
			<td><input type="password" name="senha" id="senha" size="20" class="txtCampos" maxlength="12"></td>
            <td width="170">
                <a href="javascript:preparaAlteraSenha();" class="txtLegendaLogin" style="color:#000;">Alterar</a> /
                <a href="javascript:preparaEsqueciSenha();" class="txtLegendaLogin" style="color:#000;">Esqueci</a>
            </td>
		</tr>
		<tr id="alteraLinha0" style="display:table-row;">
			<td align="right" class="txtLegendaLogin"></td>
			<td colspan="2"><input name="btnLogin" type="submit" class="btnLogin" value=" OK "></td>
		</tr>
		<tr id="alteraLinha1" style="display:none;">
			<td align="right" class="txtLegendaLogin">Nova senha</td>
			<td colspan="2"><input type="password" name="novasenha" id="novasenha" size="20" class="txtCampos" maxlength="12"></td>
		</tr>
		<tr id="alteraLinha2" style="display:none;">
			<td align="right" class="txtLegendaLogin">Confirme a senha</td>
			<td colspan="2"><input type="password" name="confirmesenha" id="confirmesenha" size="20" class="txtCampos" maxlength="12"></td>
		</tr>
		<tr id="alteraLinha3" style="display:none;">
			<td align="right" class="txtLegendaLogin"></td>
			<td colspan="2">
   				<input name="btnAlterarSenha" type="submit" class="btnLogin" value=" OK ">
   				<input name="btnCancelar" type="button" class="btnLogin" value=" Cancelar " onClick="cancelaAlteraSenha();">
			</td>
		</tr>
		<tr>
			<td style="height:20px;"></td>
		</tr>
        <input type="hidden" name="actID" id="actID" value="logar">
	</form>
	</table>
	</center>

</body>
</html>