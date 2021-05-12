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
			$query = "SELECT codigo, email, senha, nome, bemvindo FROM rp_cadastros WHERE email ='" . $email ."' AND senha ='" . $senha ."'";
			$ret   = mysql_query($query , $cx);
			if(mysql_num_rows($ret) > 0){
				while($item=mysql_fetch_array($ret)){
					if (($email == $item[1]) && ($senha == $item[2])){												
						$_SESSION['logado'] = $item[0];
						$_SESSION['Nome'] = $item[3];
						if($item[4] == "N"):
							mysql_query("UPDATE rp_cadastros SET bemvindo = 'S' WHERE codigo = ". $item[0] ."");			
							echo "<script language=javascript>";
							echo "location.href = 'seja-bem-vindo.php';";
							echo "</script>";
						else:
							echo "<script language=javascript>";
							echo "location.href = 'inicio.php';";
							echo "</script>";
						endif;
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
					$headers .= "From: rinplay@rinplay.com\r\n"; // remetente
					$headers .= "Return-Path: rinplay@rinplay.com\r\n"; // return-path
					
					$corpo = "<b>" . $assunto . "</b>";
					$corpo.= "Caro " . $rst_email["nome"] . ",<br>";
					$corpo.= "Conforme sua solicitação, uma nova senha de acesso ao RinPlay foi gerada:<br>";
					$corpo.= "<b>" . $novasenha . "</b><br><br>";
					$corpo.= "<i>[Esta é uma mensagem automática e não deve ser respondida]</i>";				
					
					//$envio = mail($to, $assunto, $corpo, $headers);


					/*ENVIO DE EMAIL
					AUTOR: GUILHERME
					DATA: 10/02/2016
					*/

					include("PHPMailer/class.phpmailer.php");

					$phpmail = new PHPMailer();
					$phpmail->IsSMTP(); // envia por SMTP
					//$phpmail->SMTPDebug  = 2;   
					$phpmail->SMTPAuth = true; // Caso o servidor SMTP precise de autenticacao
					$phpmail->Host = "localhost"; // SMTP servers
					//$phpmail->SMTPSecure = "tls";
					$phpmail->Username = "rinplay@rinplay.com"; // SMTP username
					$phpmail->Password = "r1i2n3p4"; // SMTP password
					$phpmail->IsHTML(true);
					$phpmail->CharSet = "utf-8";
					$phpmail->Port = 587;

					$phpmail->From = "rinplay@rinplay.com";
					$phpmail->FromName = "Rinplay";

					$phpmail->AddAddress($to);
					$phpmail->Subject = $assunto;
					$phpmail->Body = $corpo;
					//$phpmail->SMTPDebug = 2;
					$envio = $phpmail->Send();
					/*[END ENVIO DE EMAIL 10/02/2016]*/
		 
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
	if($acao == "cadastrar"){
		$nome	  		= LimpaEntrada($_POST['nome']);
		$sobrenome 		= LimpaEntrada($_POST['sobrenome']);
		$email 			= LimpaEntrada($_POST['email']);
		$nascimento 	= LimpaEntrada($_POST['nascimento']);
		//$telefone 	= LimpaEntrada($_POST['telefone']);
		$senha      	= LimpaEntrada($_POST['senha']);
		$confirmarsenha = LimpaEntrada($_POST['confirmarsenha']);
		$sexo			= LimpaEntrada($_POST['sexo']);
		$anel			= LimpaEntrada($_POST['anel']);
		$news			= LimpaEntrada($_POST['news']);
		
		// CONCATENANDO NOME E SOBRENOME
		$nome = $nome." ".$sobrenome; 
		
		if($senha == $confirmarsenha){
			$verifica = "SELECT email FROM rp_cadastros WHERE email = '$email'";
			$ret = mysql_query($verifica, $cx);
			if(mysql_num_rows($ret) == 0){
				$SQL = "INSERT INTO rp_cadastros(nome, email, nascimento, senha, sexo, codigo_anel, news)"; 
				$SQL.=" VALUES('$nome', '$email', '$nascimento', '$senha', '$sexo', $anel, '$news')";
				mysql_query($SQL, $cx);
				$old_id = mysql_insert_id();
				// INCLUI AMIGO
				$SQL = "INSERT INTO rp_amigos(codigo_user, codigo_amigo, status) VALUES(". $old_id .", 26, 'S')";
				mysql_query($SQL, $cx);
				$SQL = "INSERT INTO rp_amigos(codigo_user, codigo_amigo, status) VALUES(26, ". $old_id .", 'S')";
				mysql_query($SQL, $cx);
				$msg = "Cadastro efetuado com sucesso! Você já pode efetuar o login.";
			}
			else
				$msg = "Já existe um usuário cadastrado com essa conta de email!";
		}
		else
			$msg = "Confirmação de senha inválida!";	
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.js"></script>
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

	$(document).ready(function(){
		$("#nascimento").mask("99/99/9999");						
	});
	
	$(document).ready(function(){
	$("#frm").validate({
      rules: {
         email: {required: true, email: true},         
		 nome: {required: true},
		 sobrenome: {required: true},
		 nascimento: {required: true},
		 senha: {required: true},                
		 confirmarsenha: {required:true, equalTo:"#senha_cad"}
      }
   });
});

function abreInfo(){
	document.getElementById("Info").style.display = 'block';
}

function fechaInfo(){
	document.getElementById("Info").style.display = 'none';
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
    	
        <div id="apresentacao">
            <h3>O Rinplay é um simulador virtual interativo em tempo real dos cinco momentos da vida.</h3>
            <p>O Rinplay também pode ser considerado um livro de cinco anéis.</p>
            <br />
            <ul>
            	<li><img src="img/anel_terra.gif" title="Anel Terra" alt="Anel Terra" align="middle" /> Anel da Terra - Propósito de vida</li>
            	<li><img src="img/anel_agua.gif" title="Anel Água" alt="Anel Água" align="middle" /> Anel da Água- Fluir da vida</li>
            	<li><img src="img/anel_vento.gif" title="Anel Vento" alt="Anel Vento" align="middle" /> Anel do Vento - Mudanças</li>
            	<li><img src="img/anel_fogo.gif" title="Anel Fogo" alt="Anel Fogo" align="middle" /> Anel do Fogo - Combate</li>
            	<li><img src="img/anel_vazio.gif" title="Anel Vazio" alt="Anel Vazio" align="middle" /> Anel do Vazio - Conclusão dos anéis</li>
            </ul>
            <br />
            <img src="img/persongaem_fogo.png" height="100" />
            <img src="img/persongaem_agua.png" height="100" />
            <img src="img/persongaem_vazio.png" height="100" />
            <img src="img/persongaem_terra.jpg" height="100" />
            <img src="img/persongaem_vento.jpg" height="100" />
        </div>
        
        <div id="formulario-login">
        
        	<h2>Associe-se</h2>
            <p>Título perpétuo gratuito</p>
            <hr size="1" />
            <div id="Info" style="font-size:12px; display:none; border:1px solid #000; background-color:#DADADA; padding-left:5px;">
                <p>
                    A Rinplay cuida da sua segurança e do que é melhor para você. Sua idade nós ajudará a atender às suas necessidades. Você poderá ocultar as informações do seu perfil se desejar e o uso é monitorado pela Política de privacidade do Rinplay.<br /><br />
                    <a href="javascript: fechaInfo();" style="color:red;">[x] fechar</a>
                </p>
            </div>
            <?php 
			if($msg != ""):
				echo '<p class="txt_vermelho">'.$msg.'</p>';
			endif;
			?>                                       
            <form method="POST" action="" name="frm" id="frm">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="24%">Nome:</td>
                        <td width="76%"><input type="text" name="nome" id="nome" size="50"  maxlength="100" /></td>
                    </tr>
                    <tr>
                        <td>Sobrenome:</td>
                        <td><input type="text" name="sobrenome"  id="sobrenome" size="50" maxlength="100" /></td>
                    </tr>                                                                    
                    <tr>
                        <td>E-mail:</td>
                        <td> <input type="text" name="email"  id="email" size="50" maxlength="250" /></td>
                    </tr>
                    <tr>                                    
                        <td>Nascimento:</td>
                        <td> <input name="nascimento" type="text" id="nascimento"  value="" size="50" maxlength="10" /><br /><a href="javascript: abreInfo();" style="font-size:11px; color:#000;">Por que preciso informar minha data de nascimento?</a></td>
                    </tr>
                    
                    <tr>
                        <td>Senha:</td><td> <input name="senha" type="password"  id="senha_cad" size="50" maxlength="100" /></td>
                    </tr>
                    <tr>
                        <td>Confirmar senha:</td><td> <input  name="confirmarsenha" type="password" id="confirmarsenha" size="50" maxlength="100" /></td>
                    </tr>
                    <tr>                                    
                        <td>Sexo: </td>
                        <td>
                            <select name="sexo">
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><input type="checkbox" name="news" id="news" value="S" />
                        Desejo receber novidades do Rinplay em meu e-mail.</td>
                    </tr>                                   
                    <tr></tr>
                    <tr>
                      <td height="25" colspan="2"><span class="txt_vermelho"><strong>GANHAR ou não no jogo da VIDA vai depender das suas escolhas.</strong></span></td>
                    </tr>
                    <tr>
                      <td height="25" colspan="2"><span class="txt_vermelho"><strong>O Rin Play vai te dar os meios mas as jogadas serão SUAS.</strong></span></td>
                    </tr>
                    <tr>
                      <td height="25" colspan="2"><span class="txt_vermelho"><strong>O Rin Play deseja-lhe SORTE.</strong></span></td>
                    </tr>
                    <tr>                                    
                        <td colspan="2">Selecione o anel que representa seu momento de vida hoje.<br /> Identifique-o na tabela ao lado.</td>
                    </tr>
                    <tr>                                    
                        <td colspan="2">
                            <select name="anel">
                                <option value="1">Anel da Terra</option>
                                <option value="2">Anel da Água</option>
                                <option value="3">Anel do Vento</option>
                                <option value="4">Anel do Fogo</option>
                                <option value="5">Anel do Vazio</option>                                            
                            </select>
                        </td>
                    </tr>                                                                            
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="acao" value="cadastrar"><br />
                            <!--<input type="submit" value="Cadastrar"> -->
                            <input type="image" src="img/btn_associar.png">
                        </td>
                    </tr>                                    
                </table>
          </form>
        </div>

        <div class="quebra"></div>
        
    </div>
    <!-- conteudo [end] -->
    
    <!-- rodape -->
	<?php include_once("includes/rodape_index.php"); ?>

</div>

</body>
</html>
