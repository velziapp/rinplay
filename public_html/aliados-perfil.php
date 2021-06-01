<?php 
	ob_start();
	session_start();
	
	if(($_SESSION['logado'] == "") or (!isset($_SESSION['logado']))):
		header("Location: index.php");
	endif;
	
	if(($_REQUEST['codigo_user'] == "") or (!isset($_REQUEST['codigo_user']))):
		header("Location: index.php");
	endif;


	include("includes/config.php"); 
	include("includes/connection.php"); 
	include("includes/funcoes.php"); 
	$cx 			= conecta();
	$acao 		  	= LimpaEntrada($_REQUEST['acao']);
	$codigo_user	= LimpaEntrada($_REQUEST['codigo_user']);
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
		<?php include_once("includes/lateral-perfil.php"); ?>
        
		<!-- centro [begin] -->
        <div id="centro">
        	
            <h3 class="titulo-sessao">Aliados</h3><table width="100%" border="0"> 
            <?php
			// BUSCA TODAS A MENSAGEM
			//$s = "SELECT  A.codigo, U.foto, U.nome, U.codigo as codigo_user FROM rp_amigos A JOIN rp_cadastros U ON A.codigo_amigo = U.codigo WHERE A.status = 'S' AND A.codigo_amigo <> ".$_SESSION['logado']." AND A.codigo_user = ".$_SESSION['logado']." ORDER BY U.nome"; 
			$s = "SELECT C.codigo as codigo_user,C.foto, C.nome FROM rp_cadastros C JOIN rp_amigos A ON C.codigo = A.codigo_amigo WHERE A.codigo_user =".$codigo_user." AND A.status = 'S' GROUP BY c.codigo ORDER BY nome";
			$r = mysqli_query( $cx, $s); 	
			if(mysqli_num_rows($r) > 0):
				while($ln = mysqli_fetch_assoc($r)){
					$nomeUsuario = explode(" ", $ln['nome']); 
			?>
            	<tr>
                	<td width="60" valign="top">
                    	<?php if($ln['codigo_user'] == $_SESSION['logado']): ?>
                            <a href="inicio.php">
                                <img src="sgc/uploads/fotos/<?php echo $ln['foto']; ?>" width="50" style="height:50px; overflow:hidden;" border="0">
                            </a>
                        <?php else: ?>
                            <a href="perfil.php?codigo_user=<?php echo $ln['codigo_user']; ?>">
                                <img src="sgc/uploads/fotos/<?php echo $ln['foto']; ?>" width="50" style="height:50px; overflow:hidden;" border="0">
                            </a>
                        <?php endif; ?>
                	</td>
                    <td valign="top">
                        <p><strong><?php echo $nomeUsuario[0]." ".$nomeUsuario[1]; ?></strong></p>
                        <br />
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