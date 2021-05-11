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
	
	// VERIFICA SE O USUÁRIO LOGADO PARTICIPA DA COMUNIDADE
	$membro = "SELECT COUNT(codigo) as qtd FROM rp_comunidades_membros WHERE codigo_user =".$_SESSION['logado']." AND codigo_comunidade=".$codigo_comunidade;
	$result = mysql_query($membro, $cx);
	$l = mysql_fetch_assoc($result);
	if($l['qtd'] == 1):
		$fazparte = true;
	else:
		$fazparte = false;
	endif;
	
	// VERIFICA SE O USUÁRIO LOGADO É O DONO DA COMUNIDADE
	$dono = "SELECT COUNT(codigo) as qtd FROM rp_comunidades WHERE codigo_user =".$_SESSION['logado']." AND codigo=".$codigo_comunidade;
	$result = mysql_query($dono, $cx);
	$l = mysql_fetch_assoc($result);
	if($l['qtd'] == 1)
		$edono = true;
	else
		$edono = false;		
		
	// SELECIONA NOME DA COMUNIDADE
	$sql = "SELECT nome FROM rp_comunidades WHERE codigo=".$codigo_comunidade;
	$rsComu = mysql_query($sql, $cx);
	$lnComu = mysql_fetch_assoc($rsComu);
	$nome = $lnComu['nome'];
	
	switch($acao){
		case "criarTopico":
			$titulo = LimpaEntrada($_REQUEST['titulo']);
			$SQL = "INSERT INTO rp_forum(codigo_comunidade, titulo, codigo_user) VALUES(".$codigo_comunidade.", '".$titulo."' ,".$_SESSION['logado'].")";
			mysql_query($SQL, $cx);
			header("location: comunidade-perfil.php?codigo_comunidade=".$codigo_comunidade);		
		break;
		
		case "participar":
			$PARTICIPAR = "INSERT INTO rp_comunidades_membros(codigo_comunidade, codigo_user) VALUES(".$codigo_comunidade.",".$_SESSION['logado'].")";
			mysql_query($PARTICIPAR, $cx);
			header("location: comunidade-perfil.php?codigo_comunidade=".$codigo_comunidade);		
		break;
		
		case "deixar":
			$DEIXAR = "DELETE FROM rp_comunidades_membros WHERE codigo_comunidade =".$codigo_comunidade." AND codigo_user =".$_SESSION['logado'];
			mysql_query($DEIXAR, $cx);
			header("location: comunidade-perfil.php?codigo_comunidade=".$codigo_comunidade);		
		break;	
		
		case "alterarComunidade":
			$nome 			   = LimpaEntrada($_REQUEST['nome']);
			$codigo_categoria  = LimpaEntrada($_REQUEST['codigo_categoria']);
			$descricao		   = LimpaEntrada($_REQUEST['descricao']);
			$codigo_comunidade = LimpaEntrada($_REQUEST['codigo_comunidade']);
		
			// UPLOAD FOTO //
			$pasta = "sgc/uploads/comunidades/"; // PASTA ONDE FICARA OS UPLOADS
			$permitido = (array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/png')); // TIPOS PERMITIDO PARA UPLOAD	
			$img = $_FILES['foto'];
			$tmp = $img['tmp_name']; // CAMINHO DA IMAGEM
			$name = $img['name']; // NOME DO ARQUIVO ENVIADO
			$type = $img['type']; // TIPO DO ARQUIVO
		
			if(empty($name)){	
				$UPDATE = "UPDATE rp_comunidades SET nome = '".$nome."', codigo_categoria = ".$codigo_categoria.", descricao = '".$descricao."' WHERE codigo = ".$codigo_comunidade;
				mysql_query($UPDATE, $cx);			
				header("location: comunidade-perfil.php?codigo_comunidade=".$codigo_comunidade);	
			}
			else{	
				if(in_array($type, $permitido)){
					// GERA NOME UNICO PARA IMPEDIR DE SUBSTITUIR IMAGEM
					if(($type == 'image/jpg') or ($type == 'image/jpeg') or ($type == 'image/pjpeg')):
						$foto = 'comunidade-'.md5(uniqid(rand(), true)).'.jpg'; 
					endif;
					if($type == 'image/gif'):
						$foto = 'comunidade-'.md5(uniqid(rand(), true)).'.gif'; 
					endif;
					if($type == 'image/png'):
						$foto = 'comunidade-'.md5(uniqid(rand(), true)).'.png'; 
					endif;
					
					uploadImg($tmp, $foto, $type, 480, $pasta, 'S'); // FAZ O UPLOAD
			
					$UPDATE = "UPDATE rp_comunidades SET nome = '".$nome."', codigo_categoria = ".$codigo_categoria.", descricao = '".$descricao."', foto = '".$foto."' WHERE codigo = ".$codigo_comunidade;
					mysql_query($UPDATE, $cx);		
					header("location: comunidade-perfil.php?codigo_comunidade=".$codigo_comunidade);			
				}
			}
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
 	function mostraAlterar(){
		document.getElementById("exibe").style.display = 'none';
		document.getElementById("altera").style.display = 'inline';
	}
	function ocultaAlterar(){
		document.getElementById("exibe").style.display = 'inline';
		document.getElementById("altera").style.display = 'none';
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
		<?php include_once("includes/lateral-esquerda-comu.php"); ?>
        
		<!-- centro [begin] -->
        <div id="centro">
        	
            <h3 class="titulo-sessao"><a href="comunidade.php" class="txt_vermelho">Comunidades</a> &raquo; <?php echo $nome; ?></h3>
			
            <div id="exibe">                    
                <?php
                    $s = "SELECT C.*, U.nome as dono FROM rp_comunidades C JOIN rp_cadastros U ON C.codigo_user = U.codigo WHERE C.codigo = ".$codigo_comunidade;
                    $r = mysql_query($s, $cx);
                    while($ln = mysql_fetch_assoc($r)){
                        $qtdMembros = mysql_result(mysql_query("SELECT COUNT(codigo) FROM rp_comunidades_membros WHERE codigo_comunidade = ".$ln['codigo'],$cx),0,0);
                        ?>
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td width="1" valign="top" rowspan="4">
                                	<img src="sgc/uploads/comunidades/<?php echo $ln["foto"]; ?>" width="128" align="left" />
                                </td>
                            </tr>
                            <tr>
                                <th valign="top" align="left" height="1">
                                	<h3><?php echo $ln['nome']; ?></h3>
                                </th>
                            <tr>
                                <td valign="top" height="1">
                                	<?php 
                                	echo "";
									echo $qtdMembros . " ";
									echo ($qtdMembros == 1) ? "membro" : "membros";
									echo " | ";
									?>
                                    Moderador: <a href="perfil.php?id=<?php echo $ln['codigo_user']; ?>"><?php echo $ln['dono']; ?></a>
                                </td>
                            </tr>
                           	<tr>
                                <td valign="top">
                                	<?php echo nl2br($ln['descricao']); ?>
                                </td>
                            </tr>
                        </table>
						<?php
                    }
                ?>
                <div class="forum" style="width:100%; margin-top:20px; display:<?php echo ($fazparte == true ? "" : "none"); ?>;">
                    <h3 class="titulo-sessao">Fórum</h3>

                    <form method="POST" action="comunidade-perfil.php">
                      <span id="txt">Criar  tópico</span> 
                      <input type="text" name="titulo" class="inputs" style="width:280px;"/>
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
                        $f  = "SELECT codigo, titulo FROM rp_forum WHERE codigo_comunidade = ".$codigo_comunidade." ORDER BY codigo DESC LIMIT 6";
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
						?>							
                            <tr>
                                <th colspan="2" align="right">
                                    <a href="comunidade-topico.php?codigo_comunidade=<?php echo $codigo_comunidade; ?>" class="txt_vermelho" id="link">Visualizar todos</a>
                                </th>
                            </tr>
                        <?php
						} else {
						?>
							<tr>
                            	<td colspan="2" bgcolor="#FFFFFF">Nenhum tópico no momento.</td>
                            </tr>
						<?php	
						}
						?>
                    </table>
                </div>
            </div> 
            
            <div id="altera" style="display:none;">
                <?php
                    $qry = "SELECT * FROM rp_comunidades WHERE codigo =".$codigo_comunidade;
                    $rt  = mysql_query($qry, $cx);
                    $ln  = mysql_fetch_assoc($rt);
                ?>
                <table style=" height:70px;" border="0"> 
                    <form method="post" action="comunidade-perfil.php" enctype="multipart/form-data">
                        <tr>
                            <td>Nome:</td>
                            <td>                                    
                                <input type="text" name="nome" id="nome" size="75" value="<?php echo $ln['nome']; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>Categoria:</td>
                            <td>                                    
                                <?php comboPorTabela( "codigo_categoria", "rp_comunidades_ctg", "codigo", "descricao", $ln['codigo_categoria'], "", "", true, "" );?>
                            </td>
                        </tr>
                        <tr>                                                                    
                            <td>Foto:</td>
                            <td>                          
                                <input type="file" name="foto" size="55" />          
                            </td>
                        </tr>
                        <tr>
                            <td>Descrição:</td>
                            <td>                                    
                                <textarea name="descricao" id="descricao" cols="75"><?php echo $ln['descricao']; ?></textarea>
                            </td>
                        </tr>
                       	<tr>
                            <td colspan="2">
                            	<input type="hidden" name="codigo_comunidade" value="<?php echo $codigo_comunidade; ?>">
                                <input type="hidden" name="acao" value="alterarComunidade">
                                <input type="submit" value="Salvar" />
                                <input type="button" value="Cancelar" onclick="ocultaAlterar();" />
                            </td>
                        </tr>                                                                    
                    </form>
                </table>                        
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