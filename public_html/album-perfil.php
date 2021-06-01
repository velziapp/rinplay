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
	$codigo_user 	= LimpaEntrada($_REQUEST['codigo_user']);

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
	function alterarAlbum(codigo, album, capa){
		document.getElementById("cadastra").style.display = 'none';
		document.getElementById("altera-album").style.display = 'inline';
		document.getElementById("nome").value = album;
		document.getElementById("codigo_album").value = codigo;
	}
	
	function CalterarAlbum(){
		document.getElementById("cadastra").style.display = 'inline';
		document.getElementById("altera-album").style.display = 'none';
	}
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
        	
            <h3 class="titulo-sessao">Álbum de Fotos</h3>
            
            <div id="lista-albuns">
            <?php
				$s = "SELECT codigo, album, capa FROM rp_albuns WHERE codigo_user = ".$codigo_user." ORDER BY codigo DESC";
				$r = mysqli_query( $cx, $s);
				$consulta = "SELECT COUNT(codigo) FROM rp_albuns WHERE codigo_user = ".$codigo_user;
				while($ln = mysqli_fetch_assoc($r)){
					$qtdFotos = mysqli_result(mysqli_query($cx, "SELECT COUNT(codigo) FROM rp_albuns_fotos WHERE codigo_album = ".$ln['codigo']), 0, 0);
					echo "<div class='lista-album-fotos'>";
					echo "<h4>
							<a href='albumFotos-perfil.php?codigo_album=".$ln['codigo']."&codigo_user=".$codigo_user."'>
								<div  style='height:100px; width:100px; float:left; margin-right:10px; overflow:hidden;'><img src='sgc/uploads/albuns/".$ln['capa']."' width='100'></div>
							</a>
							<strong><span id='t'>".$ln['album']."</strong> (".$qtdFotos." ".($qtdFotos == 1 ? "Foto" : "Fotos").")</strong></span>
						  </h4>";
					echo "</div>";
				}
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