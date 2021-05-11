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
	
	$acao 			= LimpaEntrada($_REQUEST['acao']);
	$codigo_album 	= LimpaEntrada($_REQUEST['codigo_album']);
	$codigo_user 	= LimpaEntrada($_REQUEST['codigo_user']);
	
	if(empty($codigo_album)):
		header("Location: album-perfil.php?codigo_user=$codigo_user");
	else:
		$rsAlbum = mysql_query("SELECT album FROM rp_albuns WHERE codigo = $codigo_album AND codigo_user = ". $codigo_user ."", $cx);
		if(mysql_num_rows($rsAlbum) > 0):
			$lnAlbum = mysql_fetch_assoc($rsAlbum);
			$tituloAlbum = $lnAlbum['album'];
		else:
		header("Location: album-perfil.php?codigo_user=$codigo_user");
		endif;
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
<script type="text/javascript" src="js/jquery.lightbox-0.5.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.lightbox-0.5.css" media="screen" />
<script type="text/javascript">	
	$(document).ready(function(){
		
		// LIGHTBOX
		 $('.lightbox a').lightBox();
				
	});
</script>
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
        	
            <h3 class="titulo-sessao"><a href="album-perfil.php?codigo_user=<?php echo $codigo_user; ?>" class="txt_vermelho">Álbum de Fotos</a> &raquo; <?php echo $tituloAlbum; ?> </h3>
            
          	<div id="galeria-fotos">
            <?php
				$sql = "SELECT codigo, foto, legenda, codigo_album FROM rp_albuns_fotos WHERE codigo_album = ".$codigo_album;
				$rsFotos = mysql_query($sql, $cx);
				$totalFotos = mysql_num_rows($rsFotos);
				if($totalFotos > 0):
				?>
				<table width="100%" border="0">
                	<tr>
					<?php
					$cont = 0;
                    while($lnFotos = mysql_fetch_assoc($rsFotos)){
                    ?>
                        <td width="166" align="center" valign="top">
                        		<div style="width:128px; overflow:hidden;" class="lightbox" >	
                                    <a href="sgc/uploads/albuns/<?php echo $lnFotos["foto"] ?>" title="<?php echo $lnFotos["legenda"] ?>" id="ver">
                                        <img src="sgc/uploads/albuns/<?php echo $lnFotos["foto"] ?>" height="100" >
	                                </a>
                                </div>
                                <span id="t"><?php echo $lnFotos["legenda"] ?></span>
                        </td>
                    <?php
					$cont++;
					if($cont == 3):
						$cont = 0;
						echo '</tr>';
						echo '<tr>';
					endif;
                    }
					if($cont < 3):
						echo '<td colspan="'.(3 - $cont).'"></td>';
					endif;
                    ?>
                	</tr>
				</table>
				<?php
				endif;
			?>
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