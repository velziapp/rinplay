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
	
	$acao = LimpaEntrada($_REQUEST['acao']);

	switch($acao){
		case "criarAlbum":
			$album = LimpaEntrada($_REQUEST['album']);
			$SQL  = "INSERT INTO rp_albuns (album, codigo_user) VALUES ('".$album."',".$_SESSION['logado'].")";
			mysqli_query( $cx, $SQL);
			header("Location: album.php");
		break;
		
		case "alterarAlbum":
			$codigo_album = LimpaEntrada($_REQUEST['codigo_album']);
			$novoNome 	  = LimpaEntrada($_REQUEST['nome']);
			
			// UPLOAD FOTO //
			$pasta = "sgc/uploads/albuns/"; // PASTA ONDE FICARA OS UPLOADS
			$permitido = (array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/png')); // TIPOS PERMITIDO PARA UPLOAD	
			$img = $_FILES['capa'];
			$tmp = $img['tmp_name']; // CAMINHO DA IMAGEM
			$name = $img['name']; // NOME DO ARQUIVO ENVIADO
			$type = $img['type']; // TIPO DO ARQUIVO
		
			if(empty($name)){	
				$UPDATE = "UPDATE rp_albuns SET album = '".$novoNome."' WHERE codigo = ".$codigo_album;
				mysqli_query( $cx, $UPDATE);		
				header("Location: album.php");	
			}
			else{	
				if(in_array($type, $permitido)){
					// GERA NOME UNICO PARA IMPEDIR DE SUBSTITUIR IMAGEM
					if(($type == 'image/jpg') or ($type == 'image/jpeg') or ($type == 'image/pjpeg')):
						$foto = 'capa-'.md5(uniqid(rand(), true)).'.jpg'; 
					endif;
					if($type == 'image/gif'):
						$foto = 'capa-'.md5(uniqid(rand(), true)).'.gif'; 
					endif;
					if($type == 'image/png'):
						$foto = 'capa-'.md5(uniqid(rand(), true)).'.png'; 
					endif;
					
					uploadImg($tmp, $foto, $type, 400, $pasta, 'S'); // FAZ O UPLOAD
			
					$UPDATE = "UPDATE rp_albuns SET album = '".$novoNome."', capa ='".$foto."' WHERE codigo = ".$codigo_album;
					mysqli_query( $cx, $UPDATE);		
					header("Location: album.php");		
				}
			}
		break;
		
		case "excluirAlbum":
			$codigo_album = LimpaEntrada($_REQUEST['codigo_album']);
			$SQL  = "DELETE FROM rp_albuns WHERE codigo_user =".$_SESSION['logado']." AND codigo = ".$codigo_album;
			mysqli_query( $cx, $SQL);
			$SQL  = "DELETE FROM rp_albuns_fotos WHERE codigo_album =".$codigo_album;
			mysqli_query( $cx, $SQL);
			header("Location: album.php");
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
	
	function envia(){
	   document.cadAlbum.submit();
	}
	
	function envia2(){
	   document.altAlbum.submit();
	}
	
	$(document).ready(function(){
		
		// VALIDA
		$("#cadAlbum").validate({
			rules: {
				album: {required: true}
			}
	   });
		
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
		<?php include_once("includes/lateral-esquerda.php"); ?>
        
		<!-- centro [begin] -->
        <div id="centro">
        	
            <h3 class="titulo-sessao">Álbum de Fotos</h3>
            
            <div id="cadastra">
                <form method="post" action="album.php" name="cadAlbum" id="cadAlbum">
                    Criar novo álbum: 
                    <input type="text" name="album" id="album" class="inputs" size="60" />
                    <input type="hidden" name="acao" value="criarAlbum">
                    <input type="submit" value="Criar" />          
                </form>
            </div>
            
            <div id="altera-album">
                <form method="post" action="album.php" enctype="multipart/form-data" name="altAlbum" id="altAlbum">
                    <table width="100%" border="0"> 
                        <tr>
                            <td width="88">Alterar álbum:</td>
                            <td><input type="text" name="nome" id="nome" size="69" /></td>
                        </tr>
                        <tr>                                                                    
                            <td>Capa do álbum:</td>
                            <td><input type="file" name="capa" id="capa" size="50" /></td>
                        </tr>                                                                    
                        <tr>
                            <td></td>
                            <td>
                            	<input type="button" onclick="envia2();" value="Salvar" />
                                <input type="button" onclick="CalterarAlbum();" value="Cancelar" />
                                <input type="hidden" name="acao" value="alterarAlbum">
                            	<input type="hidden" name="codigo_album" id="codigo_album">
                            </td>
                        </tr>                                                                    
                    </table>                        
                </form>
            </div>
            
            <div id="lista-albuns">
            <?php
				$s = "SELECT codigo, album, capa FROM rp_albuns WHERE codigo_user = ".$_SESSION['logado']." ORDER BY codigo DESC";
				$r = mysqli_query( $cx, $s);
				$consulta = "SELECT COUNT(codigo) FROM rp_albuns WHERE codigo_user = ".$_SESSION['logado'];
				while($ln = mysqli_fetch_assoc($r)){
					$qtdFotos = mysqli_result(mysqli_query($cx, "SELECT COUNT(codigo) FROM rp_albuns_fotos WHERE codigo_album = ".$ln['codigo']), 0, 0);
					echo "<div class='lista-album-fotos'>";
					echo "<h4>
							<a href='albumFotos.php?codigo_album=".$ln['codigo']."&album=".$ln['album']."'>
								<div  style='height:100px; width:100px; float:left; margin-right:10px; overflow:hidden;'><img src='sgc/uploads/albuns/".$ln['capa']."' width='100'></div>
							</a>
							<strong><span id='t'>".$ln['album']."</strong> (".$qtdFotos." ".($qtdFotos == 1 ? "Foto" : "Fotos").")</strong></span>
						  </h4>";
					echo "<a href='albumFotos.php?codigo_album=".$ln['codigo']."' id='link'>Adicionar Fotos</a> | 
							<a href='album.php?acao=excluirAlbum&codigo_album=".$ln['codigo']."' id='link'>Excluir</a> | 
							<a href=\"javascript:alterarAlbum(".$ln['codigo'].",'".$ln['album']."','".$ln['capa']."');\" id='link'>Alterar</a>";
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