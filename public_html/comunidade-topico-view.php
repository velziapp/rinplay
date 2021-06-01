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
	$codigo_topico	   	= LimpaEntrada($_REQUEST['codigo_topico']);
	
	// SELECIONA NOME DA COMUNIDADE
	$sql = "SELECT nome FROM rp_comunidades WHERE codigo=".$codigo_comunidade;
	$rsComu = mysqli_query( $cx, $sql);
	$lnComu = mysqli_fetch_assoc($rsComu);
	$nome = $lnComu['nome'];

	// VERIFICA SE O USUÁRIO LOGADO PARTICIPA DA COMUNIDADE
	$membro = "SELECT COUNT(codigo) as qtd FROM rp_comunidades_membros WHERE codigo_user =".$_SESSION['logado']." AND codigo_comunidade=".$codigo_comunidade;
	$result = mysqli_query( $cx, $membro);
	$l = mysqli_fetch_assoc($result);
	if($l['qtd'] == 1)
		$fazparte = true;
	else
		$fazparte = false;
		
	
	// VERIFICA SE O USUÁRIO LOGADO É O DONO DA COMUNIDADE
	$dono = "SELECT COUNT(codigo) as qtd FROM rp_comunidades WHERE codigo_user =".$_SESSION['logado']." AND codigo=".$codigo_comunidade;
	$result = mysqli_query( $cx, $dono);
	$l = mysqli_fetch_assoc($result);
	if($l['qtd'] == 1)
		$edono = true;
	else
		$edono = false;		
		
	if(!$fazparte):
		header("Location: comunidades.php");
	endif;
	
	switch($acao){
		case "criarPost":
			$post = LimpaEntrada($_REQUEST['post']);
			$SQL = "INSERT INTO rp_forum_posts(codigo_user, codigo_forum, post) VALUES(".$_SESSION['logado'].",".$codigo_topico.", '".$post."')";
			mysqli_query( $cx, $SQL);
			header("location: comunidade-topico-view.php?codigo_comunidade=".$codigo_comunidade."&codigo_topico=".$codigo_topico);		
		break;
		
		case "excluirPost":
			$codigo_post = LimpaEntrada($_REQUEST['codigo_post']);
			$SQL = "DELETE FROM rp_forum_posts WHERE codigo = ".$codigo_post." AND codigo_forum = ".$codigo_topico." AND codigo_user = ".$_SESSION['logado'];
			mysqli_query( $cx, $SQL);
			header("location: comunidade-topico-view.php?codigo_comunidade=".$codigo_comunidade."&codigo_topico=".$codigo_topico);		
		break;
	}
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
                    <form method="POST" action="comunidade-topico-view.php">
                        Responder 
                        <input type="text" name="post" class="inputs" size="50" />
                        <input type="hidden" name="acao" value="criarPost">
                        <input type="hidden" name="codigo_comunidade" value="<?php echo $codigo_comunidade; ?>">
                        <input type="hidden" name="codigo_topico" value="<?php echo $codigo_topico; ?>">
                        <input type="submit" value="Enviar" />            
                      <input type="button" onclick="document.location.href='comunidade-topico.php?codigo_comunidade=<?php echo $codigo_comunidade; ?>';" value="Voltar" />
                    </form>
					
                    <br />
                    <?php
					// BUSCA O TÓPICO 
					$s = "SELECT F.*, U.foto, U.nome, U.codigo as codigo_user FROM rp_forum F JOIN rp_cadastros U ON F.codigo_user = U.codigo WHERE F.codigo = ".$codigo_topico;
					$r = mysqli_query( $cx, $s); 	
					if(mysqli_num_rows($r) > 0)
						while($ln = mysqli_fetch_assoc($r)){
							$nomeUsuario = explode(" ", $ln['nome']); 
							?>
                            <table class="tb-comentario" width="100%" border="0" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td width="1" valign="top" rowspan="2">
                                	<a href="perfil.php?codigo_user=<?php echo $ln['codigo_user']; ?>">
                                    	<img src="sgc/uploads/fotos/<?php echo $ln['foto']; ?>" height="50" width="50">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                	<strong><?php echo $nomeUsuario[0]." ".$nomeUsuario[1]; ?></strong>
                                    <br />
                                    <span style='font-size:12px;'><?php echo $ln['titulo']; ?></span><br />
                                </td>
                            </tr>
                            </table>
							<?php        
						}
					?>

					<?php
					// BUSCA TODOS OS POSTS DO TÓPICO
					$s = "SELECT P.*, U.foto, U.nome, U.codigo as codigoUser FROM rp_forum_posts P JOIN rp_cadastros U ON P.codigo_user = U.codigo AND P.codigo_forum = ".$codigo_topico." ORDER BY P.codigo DESC";
					$r = mysqli_query( $cx, $s);
					$consulta = "SELECT COUNT(codigo) FROM rp_forum_posts WHERE codigo_forum =".$codigo_topico;  	
					if(mysqli_num_rows($r) > 0)
						while($ln = mysqli_fetch_assoc($r)){
							$nomeUsuario = explode(" ", $ln['nome']); 
							?>
                            <table class="tb-comentario" width="100%" border="0" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td width="1" valign="top" rowspan="3">
                                	<a href="perfil.php?codigo_user=<?php echo $ln['codigo_user']; ?>">
                                    	<img src="sgc/uploads/fotos/<?php echo $ln['foto']; ?>" height="50" width="50">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                	<strong><?php echo $nomeUsuario[0]." ".$nomeUsuario[1]; ?></strong>
                                    <br />
                                    <span style='font-size:12px;'><?php echo $ln['post']; ?></span><br />
                                </td>
                            </tr>
                            <tr>
                            	<td  valign="top">
                                	<ul class="links-comentario">
                                        <?php if($ln['codigo_user'] == $_SESSION['logado']): ?>
                                        <li><a href="comunidade-topico-view.php?acao=excluirPost&codigo_topico=<?php echo $ln['codigo_forum']; ?>&codigo_post=<?php echo $ln['codigo']; ?>&codigo_comunidade=<?php echo $codigo_comunidade; ?>">excluir</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </td>
                            </tr>
                            </table>							
					<?php        
						}
					?>
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