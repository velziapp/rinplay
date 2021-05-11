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
		case "criarComunidade":
			$nome 			  = LimpaEntrada($_REQUEST['nome']);
			$codigo_categoria = LimpaEntrada($_REQUEST['codigo_categoria']);
			$descricao		  = LimpaEntrada($_REQUEST['descricao']);
			
			// UPLOAD FOTO //
			$pasta = "sgc/uploads/comunidades/"; // PASTA ONDE FICARA OS UPLOADS
			$permitido = (array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/png')); // TIPOS PERMITIDO PARA UPLOAD	
			$img = $_FILES['foto'];
			$tmp = $img['tmp_name']; // CAMINHO DA IMAGEM
			$name = $img['name']; // NOME DO ARQUIVO ENVIADO
			$type = $img['type']; // TIPO DO ARQUIVO
			
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
				
				uploadImg($tmp, $foto, $type, 0, $pasta, 'N'); // FAZ O UPLOAD
		
				$SQL  = "INSERT INTO rp_comunidades (nome, descricao, codigo_categoria, foto, codigo_user) VALUES ('".$nome."','".$descricao."',".$codigo_categoria.",'".$foto."',".$_SESSION['logado'].")";
				mysql_query($SQL, $cx);
			}
			header("Location: minhas-comunidades.php");
		break;
		
		case "excluirComunidade":
			$codigo_comunidade = LimpaEntrada($_REQUEST['codigo_comunidade']);
			$SQL  = "DELETE FROM rp_comunidades WHERE codigo_user =".$_SESSION['logado']." AND codigo = ".$codigo_comunidade;
			mysql_query($SQL, $cx);
			$SQL  = "DELETE FROM rp_comunidades_membros WHERE codigo_comunidade =".$codigo_comunidade;
			mysql_query($SQL, $cx);
			header("Location: minhas-comunidades.php");
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
		<?php include_once("includes/lateral-esquerda.php"); ?>
        
		<!-- centro [begin] -->
        <div id="centro">
        	
            <h3 class="titulo-sessao"><a href="comunidade.php" class="txt_vermelho">Comunidades</a> &raquo; Minhas Comunidades</h3>
			
            <table width="100%" border="0">
            <?php
				$s = "SELECT * FROM rp_comunidades WHERE codigo_user = ".$_SESSION['logado']." ORDER BY nome";
				$r = mysql_query($s, $cx);
				while($ln = mysql_fetch_assoc($r)){
					$qtdMembros = mysql_result(mysql_query("SELECT COUNT(codigo) FROM rp_comunidades_membros WHERE codigo_comunidade = ".$ln['codigo'],$cx),0,0);
					echo "<tr><td rowspan='2' width='80' align='center'>";
					echo "<a href='comunidade-perfil.php?codigo_comunidade=".$ln['codigo']."'><img src='sgc/uploads/comunidades/".$ln['foto']."' height='50' align='left'></a>";
					echo "</td>";
					echo "<td valign='top'>";
					echo "<h4>
	   					  	<strong><span id='t'>".$ln['nome']." (".$qtdMembros." ".($qtdMembros == 1 ? "membro" : "membros").")</span></strong>
						  </h4>";
					echo "</td></tr>";
					echo "<tr><td valign='top'>".$ln['descricao']."</td></tr>";
					echo "<tr><td height='10' colspan='2'></td></tr>";
				}
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