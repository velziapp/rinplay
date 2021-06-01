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
	$codigo_user	= LimpaEntrada($_REQUEST['codigo_user']);
	
	// PAGINAÇÃO
	$quantidade		= 10;
	$pagina			= (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
	$inicio			= ($quantidade * $pagina) - $quantidade;
	$totalLink		= 5;
	
	// TOTAL DE MENSAGENS
	$verificaAmigos = mysqli_query( $cx, "SELECT * FROM rp_amigos WHERE codigo_user = ". $codigo_user ." OR (codigo_amigo = ". $codigo_user ." AND codigo_user = ". $_SESSION['logado'] .") AND status = 'S'");
	if(mysqli_num_rows($verificaAmigos) > 0):
		$sql = "SELECT
					rp_mensagens.codigo,
					rp_mensagens.codigo_user AS codigo_user,
					(SELECT rp_cadastros.nome FROM rp_cadastros WHERE rp_cadastros.codigo = rp_mensagens.codigo_user) AS nome,
					(SELECT rp_cadastros.foto FROM rp_cadastros WHERE rp_cadastros.codigo = rp_mensagens.codigo_user) AS foto,
					rp_amigos.codigo_amigo AS amigo,
					rp_amigos.codigo_user AS usuario,
					rp_mensagens.mensagem
				FROM
					rp_mensagens, rp_amigos
				WHERE
					((rp_mensagens.codigo_user = rp_amigos.codigo_user OR rp_mensagens.codigo_user = rp_amigos.codigo_amigo) AND rp_amigos.status = 'S' AND rp_amigos.codigo_user = ". $codigo_user .")
					OR
					((rp_mensagens.codigo_user = rp_amigos.codigo_user OR rp_mensagens.codigo_user = rp_amigos.codigo_amigo) AND rp_amigos.status = 'S' AND rp_amigos.codigo_amigo = ". $codigo_user .")
				GROUP BY rp_mensagens.codigo
				";
	else:
		$sql = "SELECT
					rp_mensagens.codigo,
					rp_mensagens.codigo_user AS codigo_user,
					(SELECT rp_cadastros.nome FROM rp_cadastros WHERE rp_cadastros.codigo = rp_mensagens.codigo_user) AS nome,
					(SELECT rp_cadastros.foto FROM rp_cadastros WHERE rp_cadastros.codigo = rp_mensagens.codigo_user) AS foto,
					rp_mensagens.mensagem
				FROM
					rp_mensagens
				WHERE
					rp_mensagens.codigo_user = ". $codigo_user ."
				GROUP BY rp_mensagens.codigo
				";
	endif;
	$rsTotal = mysqli_query( $cx, $sql);
	$totalRegistros = mysqli_num_rows($rsTotal);
	
	// CONTINUA PAGINAÇÃO
	$totalPagina	= ceil($totalRegistros / $quantidade);
	
	switch($acao){
		
		case "add":
			$SQL = "INSERT INTO rp_amigos(codigo_user, codigo_amigo, status) VALUES(".$_SESSION['logado'].", ".$codigo_user.", 'S')";
			mysqli_query( $cx, $SQL);
			$SQL = "INSERT INTO rp_amigos(codigo_user, codigo_amigo, status) VALUES(".$codigo_user.", ".$_SESSION['logado'].", 'S')";
			mysqli_query( $cx, $SQL);
			header("location: perfil.php?codigo_user=$codigo_user");
		break;
		
		case "del":
			$DELETE1 = "DELETE FROM rp_amigos WHERE codigo_user = ".$_SESSION['logado']." AND codigo_amigo = ".$codigo_user;
			mysqli_query( $cx, $DELETE1);
			$DELETE2 = "DELETE FROM rp_amigos WHERE codigo_user = ".$codigo_user." AND codigo_amigo = ".$_SESSION['logado'];
			mysqli_query( $cx, $DELETE2);
			header("location: perfil.php?codigo_user=$codigo_user");
		break;

		case "msg":
			$mensagem = LimpaEntrada($_POST['mensagem']);
			$query = "INSERT INTO rp_mensagens(codigo_user, mensagem) VALUES(".$_SESSION['logado'].", '".$mensagem."')";
			mysqli_query( $cx, $query);	
			header("location: perfil.php?codigo_user=$codigo_user");
			break;
			
		case "foto":
			// UPLOAD FOTO //
			$pasta = "sgc/uploads/cp_fotos/"; // PASTA ONDE FICARA OS UPLOADS
			$permitido = (array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/png')); // TIPOS PERMITIDO PARA UPLOAD	
			$img = $_FILES['foto'];
			$tmp = $img['tmp_name']; // CAMINHO DA IMAGEM
			$name = $img['name']; // NOME DO ARQUIVO ENVIADO
			$type = $img['type']; // TIPO DO ARQUIVO
						
			if(in_array($type, $permitido)){
				// GERA NOME UNICO PARA IMPEDIR DE SUBSTITUIR IMAGEM
				if(($type == 'image/jpg') or ($type == 'image/jpeg') or ($type == 'image/pjpeg')):
					$foto = md5(uniqid(rand(), true)).'.jpg'; 
				endif;
				if($type == 'image/gif'):
					$foto = md5(uniqid(rand(), true)).'.gif'; 
				endif;
				if($type == 'image/png'):
					$foto = md5(uniqid(rand(), true)).'.png'; 
				endif;
				
				uploadImg($tmp, $foto, $type, 450, $pasta, 'S'); // FAZ O UPLOAD
		
				$query = "INSERT INTO rp_mensagens(codigo_user, foto) VALUES(".$_SESSION['logado'].", '$foto')";
				mysqli_query( $cx, $query);	
				header("location: inicio.php");
			}
			break;
			
		case "excluir":
			$codigo_msg = LimpaEntrada($_REQUEST['codigo_msg']);
			$codigoUser = LimpaEntrada($_REQUEST['codigoUser']);
			if($_SESSION['logado'] == $codigoUser){
				$d = "DELETE FROM rp_mensagens WHERE codigo = ".$codigo_msg;
				mysqli_query( $cx, $d);	
				
				$m = "DELETE FROM rp_mensagens_comentarios WHERE codigo_mensagem = ".$codigo_msg;
				mysqli_query( $cx, $m);	
			}
			header("location: perfil.php?codigo_user=$codigo_user");
			break;	
		
		case "excluir_comentario":
			$codigo_comentario = LimpaEntrada($_REQUEST['codigo_comentario']);	
			$d = "DELETE FROM rp_mensagens_comentarios WHERE codigo = ".$codigo_comentario;
			mysqli_query( $cx, $d);	
			header("location: perfil.php?codigo_user=$codigo_user");
			break;	
			
		case "comentar":
			$comentario = LimpaEntrada($_POST['comentario']);
			$codigo_msg = LimpaEntrada($_REQUEST['codigo_msg']);
			$s = "INSERT INTO rp_mensagens_comentarios(codigo_user, comentario, codigo_mensagem) VALUES(".$_SESSION['logado'].", '".$comentario."', ".$codigo_msg.")";
			mysqli_query( $cx, $s);
			header("location: perfil.php?codigo_user=$codigo_user");
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
<script type="text/javascript" src="js/jquery.maskedinput-1.1.4.pack.js" ></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/jquery.lightbox-0.5.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.lightbox-0.5.css" media="screen" />
<script type="text/javascript">
function abreComentario( codigo ){
	document.getElementById("comente"+codigo).style.display = 'inline';
}
function fechaComentario( codigo ){
	document.getElementById("comente"+codigo).style.display = 'none';
	document.getElementById("comentario").value = '';
}
function envia2(){
   document.frmComentarios.submit();
}
function limpa(){
	if(document.fmsg.mensagem.value == "Como está seu momento de vida agora?"){
		document.fmsg.mensagem.value = "";
		document.fmsg.enviar.style.display ='inline';  
	}	
}
$(document).ready(function(){
	// validacao de post de mensagens
	$("#fmsg").submit(function(){
		var mensagem = $("#mensagem").val();
		
		if(mensagem.length <= 1){
			alert('Digite a mensagem, antes de compartilhar!');
			return false;
		}
	});
	$("#ffotos #foto").change(function(){
		$("#ffotos #enviar").css('display', 'inline');
	});
	// paginacao das mensagens
	$(".pag").live('click', function(){
		$(".pag-full").html("<img src='img/ajax-loader.gif' />");
		var pagina = $(this).attr('rel');
		$.post(
			"ajax/mensagens-perfil.php", 
			{acao:"pag", pagina:pagina, codigo_user:<?php echo $codigo_user; ?>}, 
			function(valor){
				$(".pag-full").fadeOut();
				$("#centro").append(valor);
				return false;
			}
		);
	});
	// barra compartilhar
	$("#ul-compartilhar a").click(function(){
		var valor = $(this).attr("rel");
		$("#ul-compartilhar li").removeClass();
		$("#ul-compartilhar li#li-"+valor).addClass("compartilhe-ativo");
		
		$(".barra-compartilhar").css('display', 'none');
		$("#compartilhar-"+valor).css('display', 'block');
		
	});
	// LIGHTBOX
	$('.lightbox a').lightBox();
	// COMPARTILHAR
	$(".compartilhar-link").live('click', function(){
		var valor = $(this).attr("rel");
		document.location.href = "inicio.php?acao=compartilhar&" + valor;
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
		<?php include_once("includes/lateral-perfil.php"); ?>
        
		<!-- centro [begin] -->
        <div id="centro">
        	
           <!-- perfil [begin] -->
            <?php
			$consulta = "SELECT C.*, A.titulo, A.descricao FROM rp_cadastros C JOIN rp_aneis A ON A.codigo = C.codigo_anel WHERE C.codigo =".$codigo_user;
			$result	  = mysqli_query( $cx, $consulta);
			$ln = mysqli_fetch_assoc($result);
			
			// DEFININDO A IDADE			
			$data1 = Year($ln['nascimento']);
			$data2 = date("Y");
			
			$idade = $data2 - $data1;
			?>
            <h3 class="titulo-sessao"><?php echo $ln['nome']; ?></h3>
			<table class="tb-comentario" border="0" width="100%"> 
				<tr>
					<td width="110">
					   <b>Sexo:</b>
					</td> 
                    <td>
					   <?php echo $ln['sexo']; ?>
					</td>                               
				</tr>
				<tr>
					<td>
						<b>Nascimento:</b>
					</td>
                    <td>
						<?php echo $ln['nascimento']; ?>
					</td>                              
				</tr>
				<tr>
					<td>
					   <b>Idade:</b>
					</td> 
                    <td>
					   <?php echo $idade; ?> anos
					</td>                              
				</tr>
				<tr>
					<td>
					  <b>Anel:</b>
					</td>
                    <td>
					  <?php echo $ln['titulo']; ?>
					</td>                                
				</tr> 
				<tr>
					<td>
						<b>Descrição do Anel:</b>
					</td>
                    <td>
						<?php echo utf8_encode($ln['descricao']); ?>
					</td>                             
				</tr>             
			</table>                    
            <!-- perfil [end] -->
            
            <?php if(!$add): ?>
            
           	<!-- compartilhar [begin] -->
            <div id="compartilhar">
            	
                <ul id="ul-compartilhar">
                	<li><h3>Compartilhar:</h3></li>
                    <li class="compartilhe-ativo" id="li-status"><a href="javascript:void(0);" rel="status">status</a></li>
                    <li id="li-fotos"><a href="javascript:void(0);" rel="fotos">imagem</a></li>
                </ul>
                <div class="quebra"></div>
               
                <div class="barra-compartilhar" id="compartilhar-status">
                    <form method="POST" action="perfil.php?codigo_user=<?php echo $codigo_user; ?>" name="fmsg" id="fmsg">
                        <input type="text" name="mensagem" id="mensagem" onfocus="limpa();" value="Como está seu momento de vida agora?" />
                        <input type="hidden" name="acao" id="acao" value="msg">
                        <input type="submit" value="Compartilhar" name="enviar" id="enviar" style="display:none; width:90px;" />          
                    </form>
                </div>
                
                <div class="barra-compartilhar" id="compartilhar-fotos" style="display:none;">
                    <form method="POST" action="perfil.php?codigo_user=<?php echo $codigo_user; ?>" name="ffotos" id="ffotos" enctype="multipart/form-data">
                        <input type="file" name="foto" id="foto" size="50"  />
                        <input type="hidden" name="acao" id="acao" value="foto">
                        <input type="submit" value="Compartilhar" name="enviar" id="enviar" style="display:none;"  />          
                    </form>
                </div>
                
            </div>
            <!-- compartilhar [end] -->
            <?php endif; ?>
            <?php
			// BUSCA TODAS AS MENSAGENS
			$verificaAmigos = mysqli_query( $cx, "SELECT * FROM rp_amigos WHERE codigo_user = ". $codigo_user ." OR (codigo_amigo = ". $codigo_user ." AND codigo_user = ". $_SESSION['logado'] .") AND status = 'S'");
			if(mysqli_num_rows($verificaAmigos) > 0):
				$s = "SELECT
						rp_mensagens.codigo,
						rp_mensagens.foto AS imagem,
						rp_mensagens.codigo_user AS codigo_user,
						(SELECT rp_cadastros.nome FROM rp_cadastros WHERE rp_cadastros.codigo = rp_mensagens.codigo_user) AS nome,
						(SELECT rp_cadastros.foto FROM rp_cadastros WHERE rp_cadastros.codigo = rp_mensagens.codigo_user) AS foto,
						rp_amigos.codigo_amigo AS amigo,
						rp_amigos.codigo_user AS usuario,
						rp_mensagens.mensagem,
						rp_mensagens.id_mensagem_compartilhar,
						rp_mensagens.id_usuario_compartilhar
					FROM
						rp_mensagens, rp_amigos
					WHERE
						((rp_mensagens.codigo_user = rp_amigos.codigo_user OR rp_mensagens.codigo_user = rp_amigos.codigo_amigo) AND rp_amigos.status = 'S' AND rp_amigos.codigo_user = ". $codigo_user .")
						OR
						((rp_mensagens.codigo_user = rp_amigos.codigo_user OR rp_mensagens.codigo_user = rp_amigos.codigo_amigo) AND rp_amigos.status = 'S' AND rp_amigos.codigo_amigo = ". $codigo_user .")
					GROUP BY rp_mensagens.codigo
					ORDER BY rp_mensagens.codigo DESC
						LIMIT $inicio, $quantidade";			
			else:
				$s = "SELECT
						rp_mensagens.codigo,
						rp_mensagens.foto AS imagem,
						rp_mensagens.codigo_user AS codigo_user,
						(SELECT rp_cadastros.nome FROM rp_cadastros WHERE rp_cadastros.codigo = rp_mensagens.codigo_user) AS nome,
						(SELECT rp_cadastros.foto FROM rp_cadastros WHERE rp_cadastros.codigo = rp_mensagens.codigo_user) AS foto,
						rp_mensagens.mensagem,
						rp_mensagens.id_mensagem_compartilhar,
						rp_mensagens.id_usuario_compartilhar
					FROM
						rp_mensagens
					WHERE
						rp_mensagens.codigo_user = ". $codigo_user ."
					GROUP BY rp_mensagens.codigo
					ORDER BY rp_mensagens.codigo DESC
						LIMIT $inicio, $quantidade";
			endif;
			
			
			$r = mysqli_query( $cx, $s);
			if(mysqli_num_rows($r) > 0){
				while($ln = mysqli_fetch_assoc($r)){
						if($ln['id_mensagem_compartilhar'] > 0){
							$compartilhado = true;
							$s = "SELECT
								rp_mensagens.codigo,
								rp_mensagens.foto AS imagem,
								rp_mensagens.codigo_user AS codigo_user,
								(SELECT rp_cadastros.nome FROM rp_cadastros WHERE rp_cadastros.codigo = rp_mensagens.codigo_user) AS nome,
								(SELECT rp_cadastros.foto FROM rp_cadastros WHERE rp_cadastros.codigo = rp_mensagens.codigo_user) AS foto,
								rp_mensagens.mensagem,
								rp_mensagens.id_mensagem_compartilhar,
								rp_mensagens.id_usuario_compartilhar
							FROM
								rp_mensagens
							WHERE
								rp_mensagens.codigo = ". $ln['id_mensagem_compartilhar'] ."
							GROUP BY rp_mensagens.codigo";
							$fotoCompartilhar = mysqli_result(mysqli_query( $cx, "SELECT rp_cadastros.foto FROM rp_cadastros WHERE rp_cadastros.codigo = ". $ln['codigo_user'] .""), 0 , 0);
							$nomeCompartilhar = mysqli_result(mysqli_query( $cx, "SELECT rp_cadastros.nome FROM rp_cadastros WHERE rp_cadastros.codigo = ". $ln['codigo_user'] .""), 0 , 0);
							$idCompartilhar = mysqli_result(mysqli_query( $cx, "SELECT rp_cadastros.codigo FROM rp_cadastros WHERE rp_cadastros.codigo = ". $ln['codigo_user'] .""), 0 , 0);
							$msgCompartilhar = $ln['codigo'];
							
							$ln = mysqli_fetch_assoc(mysqli_query( $cx, $s));
						} else {
							$compartilhado = false;	
						}

						$nomeUsuario = explode(" ", $ln['nome']); 
						// BUSCA A QAUNTIDADE DE COMENTÁRIOS DA MENSAGEM 
						$id_mensagem = $ln['codigo'];
						$qtd = mysqli_result(mysqli_query($cx, "SELECT COUNT(codigo) FROM rp_mensagens_comentarios WHERE codigo_mensagem = ".$ln['codigo']), 0, 0); 
						?>
                        
                        <table class="tb-comentario" width="100%" border="0" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td width="1" valign="top" rowspan="5">
                                	<?php if($ln['codigo_user'] == $_SESSION['logado']): ?>
                                        <a href="inicio.php">
                                        <img src="sgc/uploads/fotos/<?php echo $ln['foto']; ?>" height="50" width="50">
                                        </a>
                                    <?php 
									elseif($compartilhado): 
										if($idCompartilhar == $_SESSION['logado']):
										?>
                                       		<a href="inicio.php">
                                            <img src="sgc/uploads/fotos/<?php echo $fotoCompartilhar; ?>" height="50" width="50">
                                            </a>
										<?php 
										else:
										?>
                                            <a href="perfil.php?codigo_user=<?php echo $idCompartilhar; ?>">
                                            <img src="sgc/uploads/fotos/<?php echo $fotoCompartilhar; ?>" height="50" width="50">
                                            </a>
										<?php
										endif;
									else: 
									?>
                                        <a href="perfil.php?codigo_user=<?php echo $ln['codigo_user']; ?>">
                                        <img src="sgc/uploads/fotos/<?php echo $ln['foto']; ?>" height="50" width="50">
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <?php 
									$imagem = $ln['imagem'];
									if(empty($imagem)): 
										if($compartilhado):
										?>
                                            <strong><?php echo $nomeCompartilhar; ?></strong> <span style="color:#CCC; font-size:11px;">compartilhou uma mensagem de </span><strong><a href="perfil.php?codigo_user=<?php echo $ln['codigo_user']; ?>"><?php echo $nomeUsuario[0]." ".$nomeUsuario[1]; ?></a></strong>
                                            <br />
                                            <span style='font-size:12px;'><?php echo $ln['mensagem']; ?> </span><br />
                                    	<?php 
										else:
										?>
                                            <strong><?php echo $nomeUsuario[0]." ".$nomeUsuario[1]; ?></strong> <span style="color:#CCC; font-size:11px;">escreveu uma mensagem</span>
                                            <br />
                                            <span style='font-size:12px;'><?php echo $ln['mensagem']; ?> </span><br />
										<?php
										endif;
									else: 
										if($compartilhado):
										?>
                                            <strong><?php echo $nomeCompartilhar; ?></strong> <span style="color:#CCC; font-size:11px;">compartilhou uma foto de </span><strong><a href="perfil.php?codigo_user=<?php echo $ln['codigo_user']; ?>"><?php echo $nomeUsuario[0]." ".$nomeUsuario[1]; ?></a></strong>
                                            <br />
                                            <div style="border:1px solid #CCC; padding:2px; background-color:#FFF; width:100px;" class="lightbox"><a href="sgc/uploads/cp_fotos/<?php echo $imagem; ?>" ><img src="sgc/uploads/cp_fotos/<?php echo $imagem; ?>" width="100" /></a></div>
                                    	<?php 
										else:
										?>
                                            <strong><?php echo $nomeUsuario[0]." ".$nomeUsuario[1]; ?></strong> <span style="color:#CCC; font-size:11px;">compartilhou uma foto</span>
                                            <br />
                                            <div style="border:1px solid #CCC; padding:2px; background-color:#FFF; width:100px;" class="lightbox"><a href="sgc/uploads/cp_fotos/<?php echo $imagem; ?>" ><img src="sgc/uploads/cp_fotos/<?php echo $imagem; ?>" width="100" /></a></div>
										<?php
										endif;
                                    endif; ?>
                                </td>
                            </tr>
                            <tr>
                            	<td  valign="top">
                                	<ul class="links-comentario">
                                    	<?php echo ($qtd > 0 ? "<li>". $qtd ." contribuiram</li>" : ""); ?>
                                        <li><a href="javascript:void(0);" onclick="abreComentario(<?php echo $ln['codigo']; ?>)">contribuir</a></li>
                                        
										<?php 
										if($compartilhado == "S"):
											if($idCompartilhar != $_SESSION['logado']): 
											?>
												<li><a href="javascript:void(0);" class="compartilhar-link" rel="codigo_msg=<?php echo $ln['codigo']; ?>&codigo_usuario=<?php echo $idCompartilhado; ?>">compartilhar</a></li>
											<?php
											endif;
										else:
											if($ln['codigo_user'] != $_SESSION['logado']): 
											?>
												<li><a href="javascript:void(0);" class="compartilhar-link" rel="codigo_msg=<?php echo $ln['codigo']; ?>&codigo_usuario=<?php echo $ln['codigo_user']; ?>">compartilhar</a></li>
											<?php
											endif;
										endif; 
										?>
                                        
                                        <?php 
										if($compartilhado == "S"):
											if($idCompartilhar == $_SESSION['logado']): 
											?>
                                                <li><a href="inicio.php?acao=excluir&codigo_msg=<?php echo $msgCompartilhar; ?>&codigoUser=<?php echo $idCompartilhar; ?>">excluir</a></li>
											<?php
											endif;
										else:
											if($ln['codigo_user'] == $_SESSION['logado']): 
											?>
                                                <li><a href="inicio.php?acao=excluir&codigo_msg=<?php echo $ln['codigo']; ?>&codigoUser=<?php echo $ln['codigo_user']; ?>">excluir</a></li>
											<?php
											endif;
										endif; 
										?>
                                        
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                            	<td>
								<?php
                                // BUSCA TODOS OS COMENTÁRIOS DA MENSAGEM
                                $sql = "SELECT M.*, U.foto, U.nome, U.codigo as codigoUser FROM rp_mensagens_comentarios M JOIN rp_cadastros U ON M.codigo_user = U.codigo AND codigo_mensagem = ".$id_mensagem." ORDER BY M.codigo DESC";
                                $qryComentario = mysqli_query( $cx, $sql);
                                if(mysqli_num_rows($qryComentario) > 0){
								?>
                                	<table class="tb-subcomentario" width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <?php
                                    while($lnComentario = mysqli_fetch_assoc($qryComentario)){
                                    $nomeUsuario = explode(" ", $lnComentario['nome']); 
                               		?>
                                    <tr>
                                    	<td rowspan="3" width="1" valign="top">
                                    		<img src="sgc/uploads/fotos/<?php echo $lnComentario['foto']; ?>" height="30" width="30">
                                    	</td>
                                    </tr>
                                    <tr>
                                    	<td valign="top">
                                    		<strong><?php echo $nomeUsuario[0]." ".$nomeUsuario[1]; ?></strong>
                                            <br />
                                            <span style='font-size:12px;'><?php echo $lnComentario['comentario']; ?></span>
										</td>
                                    </tr>
                                    <tr>
										<td valign="top">
                                        	<?php if($_SESSION['logado'] == $lnComentario['codigoUser']){ ?>
                                            	<ul class="links-comentario">
                                                	<li><a href="inicio.php?acao=excluir_comentario&codigo_comentario=<?php echo $lnComentario['codigo']; ?>&codigo_msg=<?php echo $lnComentario['codigo_mensagem']; ?>">excluir</a></li>
                                                </ul>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td height="1" colspan="4" class="td-branco"></td>
                                    </tr>
									<?php }	?>
									</table>
								<?php
								}
                                ?>
                                </td>
                            </tr>
                            <tr>
                            	<td  valign="top">
                                	<!-- DIV OCULTA DOS COMENTÁRIOS -->
                                    <div id="comente<?php echo $ln['codigo'];?>" style="display:none;">
                                    <form method="post" name="frmComentarios" action="inicio.php">
                                        <input type="text" name="comentario" id="comentario" size="58">
                                        <input type="submit"  value="Enviar" />
                                        <input type="button" onclick="fechaComentario(<?php echo $ln['codigo']; ?>);" value="Cancelar" />
                                        <input type="hidden" name="acao" value="comentar">
                                        <input type="hidden" name="codigo_msg" value="<?php echo $ln['codigo']; ?>">
                                    </form>  
                                    </div>
								</td>
                            </tr>
                        </table>
					<?php        
				}
				GeraPaginacaoDemanda($pagina, $totalPagina);
			} else {
				echo "<p>Nenhuma mensagem at&eacute; o momento.</p>";	
			}
			?>
            
           
            
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