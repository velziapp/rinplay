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
		  $msgLog = "ERRO: Campos n?o preenchidos!";
		} 
		else{
			$query = "SELECT codigo, email, senha, nome FROM rp_cadastros WHERE email ='" . $email ."' AND senha ='" . $senha ."'";
			$ret   = mysqli_query( $cx, $query );
			if(mysqli_num_rows($ret) > 0){
				while($item=mysqli_fetch_array($ret)){
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
				$msgLog = "ERRO: Voc? n?o est? cadastrado!";
			}	
		}
	}
	
	// ESQUECI SENHA //
	if( $acao == "lembrar" ){
	
		if( $email == ""  ){
			$msgLog = "ERRO: E-mail inv&aacute;lido!";
		}
		else{
			
			$rsEmail = mysqli_query( $cx ,  "SELECT codigo, nome, email FROM rp_cadastros WHERE email = '$email'");
			if( mysqli_num_rows( $rsEmail ) == 0 ){
				$msgLog = "ERRO: Login inv&aacute;lido";
			} else{
				$rst_email = mysqli_fetch_assoc( $rsEmail );
				if( $email == $rst_email["email"] ){
					$arrSenhaKeys = array( "1", "2", "3", "4", "5", "6", "7", "8", "9", "H", "X", "Z", "G", "B", "U", "E", "A", "T", "K", "J" );
					$novasenha = "";
					for( $x = 0; $x <= 6; $x++ ){
						$novasenha .= $arrSenhaKeys[rand( 0, 19 )];
					}
					
					$assunto = "[Recupera??o de senha: RinPlay]<br><br>";
					$to = $rst_email["email"];
					/* Montando o cabe?alho da mensagem */
					$headers = "MIME-Version: 1.1 \n";
					$headers .= "Content-type: text/html; charset=utf-8 \n";
					$headers .= "From: leandro@companhiapixel.com.br\r\n"; // remetente
					$headers .= "Return-Path: leandro@companhiapixel.com.br\r\n"; // return-path
					
					$corpo = "<b>" . $assunto . "</b>";
					$corpo.= "Caro " . $rst_email["nome"] . ",<br>";
					$corpo.= "Conforme sua solicita??o, uma nova senha de acesso ao RinPlay foi gerada:<br>";
					$corpo.= "<b>" . $novasenha . "</b><br><br>";
					$corpo.= "<i>[Esta ? uma mensagem autom?tica e n?o deve ser respondida]</i>";				
					
					$envio = mail($to, $assunto, $corpo, $headers);
		 
					if($envio){
						$sql = "UPDATE rp_cadastros SET senha = '" . $novasenha . "' WHERE codigo = " . $rst_email["codigo"];
						mysqli_query( $cx ,  $sql);
						$msgLog = "Uma nova senha foi gerada e enviada para o seu email";
					}
					else 
						$msgLog = "ERRO: Ocorreu um erro durante o envio do email.";
					}
			}
			((mysqli_free_result( $rsEmail ) || (is_object( $rsEmail ) && (get_class( $rsEmail ) == "mysqli_result"))) ? true : false);		
		}
		
	}
	
	// tabela
	$pagina = mysqli_fetch_assoc(mysqli_query( $cx, "SELECT sobre FROM rp_paginas"));
	$texto = $pagina['sobre'];
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
            <h2>Sobre</h2>
            <?php echo $texto; ?>
        </div>
    </div>

	<div class="quebra"> </div>
    <!-- conteudo [end] -->
    
    <!-- rodape -->
	<?php include_once("includes/rodape.php"); ?>

</div>

</body>
</html>