<?php 
	ob_start();
	session_start();

	include("includes/config.php"); 
	include("includes/connection.php"); 
	include("includes/funcoes.php"); 
	$cx = conecta();
	
	$msgLog   = "";
	$email = LimpaEntrada( $_POST["email"] );
	$senha = LimpaEntrada( $_POST["senha"] );
	$acao = $_POST['acao'];
	$msg  = "";
	
	 // LOGAR 
	 if($acao == "logar"){		
		if (($email == "") || ($senha == "")){
		  $msgLog = "ERRO: Campos não preenchidos!";
		} 
		else{
			$query = "SELECT codigo, email, senha, nome FROM rp_cadastros WHERE email ='" . $email ."' AND senha ='" . $senha ."'";
			$ret   = mysql_query($query , $cx);
			if(mysql_num_rows($ret) > 0){
				while($item=mysql_fetch_array($ret)){
					if (($email == $item[1]) && ($senha == $item[2])){												
						$_SESSION['logado'] = $item[0];
						$_SESSION['Nome'] = $item[3];						
						echo "<script language=javascript>";
						echo "location.href = 'inicio.php';";
						echo "</script>";
					}
				}
			}
			else{
				$msgLog = "ERRO: Você não está cadastrado!";
			}	
		}
	}
	
	// ESQUECI SENHA //
	if( $acao == "lembrar" ){
	
		if( $email == ""  ){
			$msgLog = "ERRO: E-mail inv&aacute;lido!";
		}
		else{
			
			$rsEmail = mysql_query( "SELECT codigo, nome, email FROM rp_cadastros WHERE email = '$email'", $cx );
			if( mysql_num_rows( $rsEmail ) == 0 ){
				$msgLog = "ERRO: Login inv&aacute;lido";
			} else{
				$rst_email = mysql_fetch_assoc( $rsEmail );
				if( $email == $rst_email["email"] ){
					$arrSenhaKeys = array( "1", "2", "3", "4", "5", "6", "7", "8", "9", "H", "X", "Z", "G", "B", "U", "E", "A", "T", "K", "J" );
					$novasenha = "";
					for( $x = 0; $x <= 6; $x++ ){
						$novasenha .= $arrSenhaKeys[rand( 0, 19 )];
					}
					
					$assunto = "[Recuperação de senha: RinPlay]<br><br>";
					$to = $rst_email["email"];
					/* Montando o cabeçalho da mensagem */
					$headers = "MIME-Version: 1.1 \n";
					$headers .= "Content-type: text/html; charset=utf-8 \n";
					$headers .= "From: leandro@companhiapixel.com.br\r\n"; // remetente
					$headers .= "Return-Path: leandro@companhiapixel.com.br\r\n"; // return-path
					
					$corpo = "<b>" . $assunto . "</b>";
					$corpo.= "Caro " . $rst_email["nome"] . ",<br>";
					$corpo.= "Conforme sua solicitação, uma nova senha de acesso ao RinPlay foi gerada:<br>";
					$corpo.= "<b>" . $novasenha . "</b><br><br>";
					$corpo.= "<i>[Esta é uma mensagem automática e não deve ser respondida]</i>";				
					
					$envio = mail($to, $assunto, $corpo, $headers);
		 
					if($envio){
						$sql = "UPDATE rp_cadastros SET senha = '" . $novasenha . "' WHERE codigo = " . $rst_email["codigo"];
						mysql_query( $sql, $cx );
						$msgLog = "Uma nova senha foi gerada e enviada para o seu email";
					}
					else 
						$msgLog = "ERRO: Ocorreu um erro durante o envio do email.";
					}
			}
			mysql_free_result( $rsEmail );		
		}
		
	}
	// tabela
	$pagina = mysql_fetch_assoc(mysql_query("SELECT termos FROM rp_paginas", $cx));
	$texto = $pagina['termos'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?php echo $title;?></title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput-1.1.4.pack.js" ></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript">
function preparaEsqueciSenha(){
	var f;

	f = document.getElementById("frmLogin");
	f.acao.value = "lembrar";
	f.submit();
}

function enviar(){
	document.frm.submit();
}

</script>
</head>
<body>

<div id="geral">
	
    <!-- Topo [begin] -->
	<?php include_once("includes/topo.php"); ?>
    <!-- Topo [end] -->
	
    <!-- conteudo [begin] -->
  <div id="conteudo">
  		<div id="conteudo-generico">
        <br />
        <h2>Regras do Rinplay</h2>
        <br />
        
        <?php echo $texto; ?>
        
        </div>
        </div>
        
        <div class="quebra">
    </div>
    <!-- conteudo [end] -->
    
    <!-- rodape -->
	<?php include_once("includes/rodape.php"); ?>

</div>

</body>
</html>