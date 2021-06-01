<?php
ob_start();
session_start();

if(($_SESSION['logado'] == "") or (!isset($_SESSION['logado']))):
	die("Você não tem permissão para acessar esta página!");
endif;

include("../includes/config.php"); 
include("../includes/connection.php"); 
include("../includes/funcoes.php"); 
$cx = conecta();

if($_POST['acao'] == 'pag'):
	// PAGINAÇÃO
	$codigo_user	= $_REQUEST['codigo_user'];
	$quantidade		= 10;
	$pagina			= (isset($_REQUEST['pagina'])) ? (int)$_REQUEST['pagina'] : 1;
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
	}
endif;
?>