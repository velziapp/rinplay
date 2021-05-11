<?php 
	ob_start();
	session_start();
	
	if(($_SESSION['logado'] == "") or (!isset($_SESSION['logado']))):
		header("Location: index.php");
	endif;

	include("includes/config.php"); 
	include("includes/connection.php"); 
	include("includes/funcoes.php"); 
	$cx = conecta();
	
	$acao 				= LimpaEntrada($_REQUEST['acao']);
	$codigo_comunidade 	= LimpaEntrada($_REQUEST['codigo_comunidade']);
	
	// SELECIONA NOME DA COMUNIDADE
	$sql = "SELECT nome FROM rp_comunidades WHERE codigo=".$codigo_comunidade;
	$rsComu = mysql_query($sql, $cx);
	$lnComu = mysql_fetch_assoc($rsComu);
	$nome = $lnComu['nome'];

	// VERIFICA SE O USUÁRIO LOGADO PARTICIPA DA COMUNIDADE
	$membro = "SELECT COUNT(codigo) as qtd FROM rp_comunidades_membros WHERE codigo_user =".$_SESSION['logado']." AND codigo_comunidade=".$codigo_comunidade;
	$result = mysql_query($membro, $cx);
	$l = mysql_fetch_assoc($result);
	if($l['qtd'] == 1)
		$fazparte = true;
	else
		$fazparte = false;
		
	
	// VERIFICA SE O USUÁRIO LOGADO É O DONO DA COMUNIDADE
	$dono = "SELECT COUNT(codigo) as qtd FROM rp_comunidades WHERE codigo_user =".$_SESSION['logado']." AND codigo=".$codigo_comunidade;
	$result = mysql_query($dono, $cx);
	$l = mysql_fetch_assoc($result);
	if($l['qtd'] == 1)
		$edono = true;
	else
		$edono = false;		
		
	if(!$fazparte):
		header("Location: comunidades.php");
	endif;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript">
</script>
</head>
<body>

<div id="geral">
	
    <!-- Topo -->
	<?php include_once("includes/topo.php"); ?>
	
    <!-- conteudo [begin] -->
    <div id="conteudo">
    	
        <!-- lateral esquerda -->
		<?php include_once("includes/lateral-esquerda-comu.php"); ?>
        
		<!-- centro [begin] -->
        <div id="centro">
        	
            <h3 class="titulo-sessao"><a href="comunidade.php" class="txt_vermelho">Comunidades</a> &raquo; <a class="txt_vermelho" href="comunidade-perfil.php?codigo_comunidade=<?php echo $codigo_comunidade; ?>"><?php echo $nome; ?></a> &raquo; Fórum</h3>
    
            <div id="exibe"> 
                              
                <div class="forum" style="margin-top:10px; display:<?php echo ($fazparte == true ? "" : "none"); ?>;">
                    <form method="post" action="comunidade-perfil.php">
                        Criar tópico
                          <input type="text" name="titulo" size="50" />
                        <input type="hidden" name="acao" value="criarTopico">
                        <input type="hidden" name="codigo_comunidade" value="<?php echo $codigo_comunidade; ?>">
                        <input type="submit" value="Criar" />         
                      <input type="button" onclick="document.location.href='comunidade.php';" value="Voltar" />
                  </form>
					
                    <br />
                    <table width="100%" border="0" class="tb-padrao" cellpadding="0" cellspacing="1">
                        <tr>
                            <th align="left">Tópicos</th>
                            <th align="left" width="1">Postagens</th>
                        </tr>
                        <?php
                        $f  = "SELECT codigo, titulo FROM rp_forum WHERE codigo_comunidade = ".$codigo_comunidade." ORDER BY codigo DESC";
                        $consulta = "SELECT COUNT(codigo) FROM rp_forum WHERE codigo_comunidade = ".$codigo_comunidade;
                        $rf = mysql_query($f, $cx);
                        if(mysql_num_rows($rf) > 0){
                            $i = 1;
                            while($dados = mysql_fetch_assoc($rf)){
                            $qtdPosts = mysql_result(mysql_query("SELECT COUNT(codigo) FROM rp_forum_posts WHERE codigo_forum = ".$dados['codigo'],$cx),0,0);
                        	?>
                                <tr>
                                    <td bgcolor="#FFFFFF">
                                    	<a id="link" href="comunidade-topico-view.php?codigo_topico=<?php echo $dados['codigo']; ?>&codigo_comunidade=<?php echo $codigo_comunidade; ?>"><?php echo $dados['titulo']; ?></a>
                                    </td>
                                    <td align="center" bgcolor="#FFFFFF"><?php echo $qtdPosts; ?></td>
                                </tr>
                        	<?php 
                            $i++;
                            }
                        }
                        ?>
                    </table>
                </div>
                
            </div> 
             
        </div>
        <!-- centro [end] -->
		
        <!-- lateral direita [begin] -->
        <?php include_once("includes/lateral-direita.php"); ?>
        <!-- lateral direita [end] -->

        <div class="quebra"></div>
        
    </div>
    <!-- conteudo [end] -->
    
    <!-- rodape -->
	<?php include_once("includes/rodape.php"); ?>

</div>

</body>
</html>