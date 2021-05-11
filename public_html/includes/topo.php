<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4016516-47']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<script type="text/javascript">
function limpaBusca(){
	if(document.frmBusca.palavra.value == "Procurar"){
		document.frmBusca.palavra.value = "";
		document.frmBusca.btProcurar.style.display ='inline';  
	}	
}
</script>
	<div id="topo">
    	<?php if($_SESSION['logado'] == ""): ?>
            <!-- Topo Login [begin] -->
            <div id="topo-login">
                
                <h1><a href="index.php"><img src="img/rinplay.png" alt="Rinplay" title="Rinplay" /></a></h1>
                
                <div id="login">
                    
                    <form action="" name="frmLogin" id="frmLogin" method="post">
                        <table border="0" cellspacing="0" cellpadding="0" align="right">
                            <?php if($msgLog != ""): ?>
                            <tr>
                                <th align="left" colspan="3"><?php echo $msgLog; ?></th>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th align="left">E-mail</th>
                                <th colspan="2" align="left">Senha</th>
                            </tr>
                            <tr>
                                <td width="140"><input type="text" name="email" id="email" size="20" maxlength="100" /></td>
                                <td width="140"><input type="password" name="senha" id="senha" size="20" maxlength="50" /></td>
                                <td align="right"><input type="hidden" name="acao" id="acao" value="logar" /><input type="submit" name="jogar" id="jogar" value="Jogar" /></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2"><a href="javascript:void(0);" onclick="preparaEsqueciSenha();" title="Clique aqui para recuperar sua senha!">Esqueceu sua senha?</a></td>
                            </tr>
                        </table>                
                    </form>
                    
                </div>
                
                <div class="quebra"></div>
                
            </div>
            <!-- Topo Login [end] -->
        <?php 
		else: 
		// DEFININDO O ANEL DO USUÁRIO
			if($_SESSION['logado'] != ""){
				$cod_anel = mysql_result(mysql_query("SELECT codigo_anel FROM rp_cadastros WHERE codigo = ".$_SESSION['logado'],$cx),0,0);
				if($cod_anel == 1){
					$img_anel = "anel_terra.gif";
					$titleAnel = "Anel da Terra";
				}
				if($cod_anel == 2){
					$img_anel = "anel_agua.gif";
					$titleAnel = "Anel da Água";
				}
				if($cod_anel == 3){
					$img_anel = "anel_vento.gif";
					$titleAnel = "Anel do Vento";
				}
				if($cod_anel == 4){
					$img_anel = "anel_fogo.gif";
					$titleAnel = "Anel do Fogo";
				}
				if($cod_anel == 5){
					$img_anel = "anel_vazio.gif";
					$titleAnel = "Anel do Vazio";
				}
			}		
		?>
            <!-- Topo Login [begin] -->
            <div id="topo-sessao">
                
                <h1><a href="inicio.php"><img src="img/rinplay.png" alt="Rinplay" title="Rinplay" /></a></h1>
                
                <div id="topo-barra">
                	<form method="POST" action="busca.php" name="frmBusca" id="frmBusca">
                        <input type="text" name="palavra" id="palavra" value="Procurar" onfocus="limpaBusca();" size="97" maxlength="100" />
                        <input type="submit" name="btProcurar" id="btProcurar" value="OK" style="display:none;" />        
                    </form>  
                </div>
                
                <div id="topo-links">
                	<ul>
                		<li><a href="inicio.php">Ringame</a></li>
                        <li><a href="dados.php">Rinface</a></li>
                        <li><a href="logout.php">Sair</a></li>
                	</ul>
                </div>
                
                <div class="quebra"></div>
                
            </div>
            <!-- Topo Login [end] -->
        <?php endif; ?>
    
    </div>