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
	
	$acao 		  = LimpaEntrada($_REQUEST['acao']);
	$codigo_user  = LimpaEntrada($_REQUEST['codigo_user']);
	
	switch($acao){
		case "aceitar":
			$UPDATE = "UPDATE rp_amigos SET status = 'S' WHERE codigo_amigo = ".$codigo_user." AND codigo_user = ".$_SESSION['logado'];
			mysqli_query( $cx, $UPDATE);
			header("Location: solicitacao-aliado.php");
		break;
		
		case "recusar":
			$DELETE = "DELETE FROM rp_amigos WHERE codigo_user = ".$codigo_user." AND codigo_amigo = ".$_SESSION['logado'];
			mysqli_query( $cx, $DELETE);
			header("Location: solicitacao-aliado.php");
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
</head>
<body>

<div id="geral">
	
    <!-- Topo -->
	<?php include_once("includes/topo.php"); ?>
	
    <!-- conteudo [begin] -->
    <div id="conteudo">
    	
        <!-- lateral esquerda -->
		<?php include_once("includes/lateral-esquerda.php"); ?>
        
		<!-- centro [begin] -->
        <div id="centro">
        	
            <h3 class="titulo-sessao">Solicitações de Aliados</h3><table width="100%" border="0"> 
            <?php
			// BUSCA TODAS A MENSAGEM
			$s = "SELECT  A.codigo, U.foto, U.nome, U.codigo as codigo_user FROM rp_amigos A JOIN rp_cadastros U ON A.codigo_amigo = U.codigo WHERE A.status = 'N' AND A.codigo_amigo <> ".$_SESSION['logado']." AND A.codigo_user = ".$_SESSION['logado']." ORDER BY U.nome"; 
			$r = mysqli_query( $cx, $s); 	
			if(mysqli_num_rows($r) > 0):
				while($ln = mysqli_fetch_assoc($r)){
					$nomeUsuario = explode(" ", $ln['nome']); 
			?>
            	<tr>
                	<td width="60" valign="top">
                        <a href="verPerfil.php?codigo_user=<?php echo $ln['codigo_user']; ?>">
                            <img src="sgc/uploads/fotos/<?php echo $ln['foto']; ?>" width="50" style="height:50px; overflow:hidden;" border="0">
                        </a>
                	</td>
                    <td valign="top">
                        <p><strong><?php echo $nomeUsuario[0]." ".$nomeUsuario[1]; ?></strong> quer ser seu amigo no RinPlay!</p>
                        <br />
                        <ul class="links-comentario">
                        	<li><a href="solicitacao-aliado.php?acao=aceitar&codigo_user=<?php echo $ln['codigo_user']; ?>">Aceitar</a></li>
                            <li><a href="solicitacao-aliado.php?acao=recusar&codigo_user=<?php echo $ln['codigo_user']; ?>">Recusar</a></li>
                        </ul>
            		</td>
           		</tr>
			<?php        
				}
			else:
				echo "<tr><td>";
				echo "Nenhum pedido de amizade pendente.";
				echo "</tr></td>";
			endif;
			?>
            </table>
                    
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