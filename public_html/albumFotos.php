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
	
	if((empty($codigo_album)) || (!is_numeric($codigo_album))):
		header("Location: album.php");
	else:
		$rsAlbum = mysqli_query( $cx, "SELECT album FROM rp_albuns WHERE codigo = $codigo_album AND codigo_user = ". $_SESSION['logado'] ."");
		if(mysqli_num_rows($rsAlbum) > 0):
			$lnAlbum = mysqli_fetch_assoc($rsAlbum);
			$tituloAlbum = $lnAlbum['album'];
		else:
			header("Location: album.php");
		endif;
	endif;

	switch($acao){
		case "cadastrarFoto":
			$legenda = LimpaEntrada($_REQUEST['legenda']);		
			
			// UPLOAD FOTO //
			$pasta = "sgc/uploads/albuns/"; // PASTA ONDE FICARA OS UPLOADS
			$permitido = (array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/png')); // TIPOS PERMITIDO PARA UPLOAD	
			$img = $_FILES['foto'];
			$tmp = $img['tmp_name']; // CAMINHO DA IMAGEM
			$name = $img['name']; // NOME DO ARQUIVO ENVIADO
			$type = $img['type']; // TIPO DO ARQUIVO
		
			if(in_array($type, $permitido)){
					// GERA NOME UNICO PARA IMPEDIR DE SUBSTITUIR IMAGEM
					if(($type == 'image/jpg') or ($type == 'image/jpeg') or ($type == 'image/pjpeg')):
						$foto = 'foto-'.md5(uniqid(rand(), true)).'.jpg'; 
					endif;
					if($type == 'image/gif'):
						$foto = 'foto-'.md5(uniqid(rand(), true)).'.gif'; 
					endif;
					if($type == 'image/png'):
						$foto = 'foto-'.md5(uniqid(rand(), true)).'.png'; 
					endif;
					
					uploadImg($tmp, $foto, $type, 800, $pasta, 'S'); // FAZ O UPLOAD
			
					$SQL = "INSERT INTO rp_albuns_fotos (foto, codigo_album, legenda) VALUES ('".$foto."',".$codigo_album.",'".$legenda."')";
					mysqli_query( $cx, $SQL);				
			}
			header("Location: albumFotos.php?codigo_album=$codigo_album");
		break;
		
		case "alterarFoto":
			$codigo_foto = LimpaEntrada($_REQUEST['codigo_foto']);
			$legenda = LimpaEntrada($_REQUEST['legenda']);		
			
			// UPLOAD FOTO //
			$pasta = "sgc/uploads/albuns/"; // PASTA ONDE FICARA OS UPLOADS
			$permitido = (array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/png')); // TIPOS PERMITIDO PARA UPLOAD	
			$img = $_FILES['foto'];
			$tmp = $img['tmp_name']; // CAMINHO DA IMAGEM
			$name = $img['name']; // NOME DO ARQUIVO ENVIADO
			$type = $img['type']; // TIPO DO ARQUIVO
		
			if(empty($name)){	
				$UPDATE = "UPDATE rp_albuns_fotos SET legenda = '".$legenda."' WHERE codigo = ".$codigo_foto." AND codigo_album = ".$codigo_album;
				mysqli_query( $cx, $UPDATE);			
			}
			else{	
				if(in_array($type, $permitido)){
					// GERA NOME UNICO PARA IMPEDIR DE SUBSTITUIR IMAGEM
					if(($type == 'image/jpg') or ($type == 'image/jpeg') or ($type == 'image/pjpeg')):
						$foto = 'foto-'.md5(uniqid(rand(), true)).'.jpg'; 
					endif;
					if($type == 'image/gif'):
						$foto = 'foto-'.md5(uniqid(rand(), true)).'.gif'; 
					endif;
					if($type == 'image/png'):
						$foto = 'foto-'.md5(uniqid(rand(), true)).'.png'; 
					endif;
					
					uploadImg($tmp, $foto, $type, 800, $pasta, 'S'); // FAZ O UPLOAD
			
					$UPDATE = "UPDATE rp_albuns_fotos SET legenda = '".$legenda."', foto ='".$foto."' WHERE codigo = ".$codigo_foto." AND codigo_album = ".$codigo_album;
					mysqli_query( $cx, $UPDATE);				
				}
			}
			header("Location: albumFotos.php?codigo_album=$codigo_album");
		break;
		
		case "excluirFoto":
			$codigo_foto = LimpaEntrada($_REQUEST['codigo_foto']);
			$SQL  = "DELETE FROM rp_albuns_fotos WHERE codigo_album =".$codigo_album." AND codigo = ".$codigo_foto;
			mysqli_query( $cx, $SQL);
			header("Location: albumFotos.php?codigo_album=$codigo_album");
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
<script type="text/javascript" src="js/jquery.lightbox-0.5.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.lightbox-0.5.css" media="screen" />
<script type="text/javascript">	
	function alterarFoto(codigo, codigo_album, legenda){
		document.getElementById("cadastra").style.display = 'none';
		document.getElementById("altera").style.display = 'inline';	
		document.getElementById("legendaA").value = legenda;
		document.getElementById("codigo_foto").value = codigo;
	}
	
	function CalterarFoto(){
		document.getElementById("cadastra").style.display = 'inline';
		document.getElementById("altera").style.display = 'none';
	}
	
	function envia2(){
	   document.altFoto.submit();
	}
	
	$(document).ready(function(){
		
		// LIGHTBOX
		 $('.lightbox a').lightBox();
		
		// VALIDA
		$("#cadFoto").validate({
			rules: {
				foto: {required: true}
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
        	
            <h3 class="titulo-sessao"><a href="album.php" class="txt_vermelho">Álbum de Fotos</a> &raquo; <?php echo $tituloAlbum; ?> </h3>
            
            <div id="cadastra">
				<form method="post" action="albumFotos.php" enctype="multipart/form-data" name="cadFoto" id="cadFoto">
	                <table width="100%" border="0"> 
                        <tr>
                            <td width="1">Legenda:</td>
                            <td><input type="text" name="legenda" id="legenda" class="inputs" size="81" /></td>
                        </tr>
                        <tr>                                                                    
                            <td>Foto:</td>
                            <td><input type="file" name="foto" class="inputs" size="50" /></td>
                        </tr>                                                                    
                        <tr>
                        	<td></td>
                            <td>
                            	<input type="submit" value="Enviar" />
                                <input type="hidden" name="acao" value="cadastrarFoto">
                                <input type="hidden" name="codigo_album" id="codigo_album" value="<?php echo $codigo_album; ?>">
                                <input type="hidden" name="album" id="album" value="<?php echo $album; ?>">
                            </td>
                        </tr>                                                                    
	                </table>                        
                </form>
            </div>
            
            <div id="altera" style="display:none;">
                <form method="post" action="albumFotos.php" enctype="multipart/form-data" name="altFoto">
	                <table width="100%" border="0"> 
                        <tr>
                            <td width="1">Legenda:</td>
                            <td><input type="text" name="legenda" id="legendaA" class="inputs" size="81" /></td>
                        </tr>
                        <tr>                                                                    
                            <td>Foto:</td>
                            <td><input type="file" name="foto" class="inputs" size="50" /></td>
                        </tr>                                                                  
                        <tr>
                        	<td></td>
                            <td>
                                <input type="hidden" name="acao" value="alterarFoto">
                                <input type="hidden" name="codigo_album" id="codigo_album" value="<?php echo $codigo_album; ?>">
                                <input type="hidden" name="codigo_foto" id="codigo_foto">
                            	<input type="submit" value="Enviar" />
                                <input type="button" onclick="CalterarFoto();" value="Cancelar" />
                            </td>
                        </tr>                                                                    
                    </table>                        
				</form>
            </div>
            
            <div id="galeria-fotos">
            <?php
				$sql = "SELECT codigo, foto, legenda, codigo_album FROM rp_albuns_fotos WHERE codigo_album = ".$codigo_album;
				$rsFotos = mysqli_query( $cx, $sql);
				$totalFotos = mysqli_num_rows($rsFotos);
				if($totalFotos > 0):
				?>
				<table width="100%" border="0">
                	<tr>
					<?php
					$cont = 0;
                    while($lnFotos = mysqli_fetch_assoc($rsFotos)){
                    ?>
                        <td width="166" align="center" valign="top">
                        		<div style="width:128px; overflow:hidden;" class="lightbox" >	
                                    <a href="sgc/uploads/albuns/<?php echo $lnFotos["foto"] ?>" title="<?php echo $lnFotos["legenda"] ?>" id="ver">
                                        <img src="sgc/uploads/albuns/<?php echo $lnFotos["foto"] ?>" height="100" >
	                                </a>
                                </div>
                                <span id="t"><?php echo $lnFotos["legenda"] ?></span>
                                <br />
                                <a href="albumFotos.php?acao=excluirFoto&codigo_foto=<?php echo $lnFotos["codigo"] ?>&codigo_album=<?php echo $codigo_album ?>" id="link" class="txt_vermelho">Excluir</a> | 
                                <a href="javascript:alterarFoto('<?php echo $lnFotos["codigo"] ?>','<?php echo $lnFotos["codigo_album"] ?>','<?php echo $lnFotos["legenda"] ?>');" id="link" class="txt_vermelho">Alterar</a>
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