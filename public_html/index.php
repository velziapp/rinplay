<?php 
	ob_start();
	session_start();

phpinfo();
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
